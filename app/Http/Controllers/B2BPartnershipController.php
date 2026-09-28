<?php

namespace App\Http\Controllers;

use App\B2BPartnershipEnquiry;
use App\Support\XlsxWriter;
use Carbon\Carbon;
use Illuminate\Http\Request;

class B2BPartnershipController extends Controller
{
    // Optional date-range filters. With neither date set, every enquiry is shown.
    const DATE_FILTERS = [
        'received' => ['label' => 'Enquiry date', 'button' => 'Filter by enquiry date', 'from' => 'received_from', 'to' => 'received_to'],
        'travel' => ['label' => 'Travel date', 'button' => 'Filter by travel date', 'from' => 'travel_from', 'to' => 'travel_to'],
    ];

    const PER_PAGE_OPTIONS = [10, 25, 50, 100];
    const DEFAULT_PER_PAGE = 25;

    public function index(Request $request)
    {
        [$query, $filters, $dateFilters] = $this->filteredQuery($request);

        $perPage = in_array((int) $request->query('per_page'), self::PER_PAGE_OPTIONS, true) ? (int) $request->query('per_page') : self::DEFAULT_PER_PAGE;
        $enquiries = $query->orderBy('id', 'DESC')->paginate($perPage)->withQueryString();

        // A page past the end (e.g. after results shrank) goes to the last page instead of showing nothing.
        if ($enquiries->isEmpty() && $enquiries->currentPage() > 1) {
            return redirect($enquiries->url($enquiries->lastPage()));
        }
        $perPageOptions = self::PER_PAGE_OPTIONS;

        // Dashboard figures cover all enquiries, regardless of the filters.
        $now = Carbon::now(B2BPartnershipEnquiry::DISPLAY_TIMEZONE);
        $stats = [
            'total' => B2BPartnershipEnquiry::count(),
            'today' => B2BPartnershipEnquiry::where('created_at', '>=', $now->copy()->startOfDay()->utc())->count(),
            'last_7_days' => B2BPartnershipEnquiry::where('created_at', '>=', $now->copy()->subDays(6)->startOfDay()->utc())->count(),
            'this_month' => B2BPartnershipEnquiry::where('created_at', '>=', $now->copy()->startOfMonth()->utc())->count(),
        ];

        return view('b2b_partnership.index', compact('enquiries', 'filters', 'dateFilters', 'stats', 'perPage', 'perPageOptions'));
    }

    // Downloads the enquiries matching the list's filters (all of them when none are set).
    public function export(Request $request)
    {
        [$query] = $this->filteredQuery($request);

        $columns = [
            ['title' => 'Enquiry Date (IST)', 'width' => 19, 'type' => 'datetime'],
            ['title' => 'Name', 'width' => 22],
            ['title' => 'Company Name', 'width' => 26],
            ['title' => 'Email', 'width' => 30],
            ['title' => 'Phone', 'width' => 18],
            ['title' => 'Agency Country', 'width' => 18],
            ['title' => 'Programme Type', 'width' => 20],
            ['title' => 'Interested Destinations', 'width' => 34],
            ['title' => 'Travel Month', 'width' => 13, 'type' => 'month'],
            ['title' => 'Traveler Count', 'width' => 14, 'type' => 'number'],
            ['title' => 'Hotel Category', 'width' => 16],
            ['title' => 'Budget Range', 'width' => 24],
            ['title' => 'Brief', 'width' => 60],
        ];

        $rows = (function () use ($query) {
            foreach ($query->lazyByIdDesc(500) as $enquiry) {
                yield [
                    $enquiry->receivedAt(),
                    $enquiry->name,
                    $enquiry->company_name,
                    $enquiry->email,
                    $enquiry->mobile,
                    $enquiry->agency_country,
                    $enquiry->travel_type,
                    implode(', ', array_map([B2BPartnershipEnquiry::class, 'destinationLabel'], (array) $enquiry->destinations)),
                    $enquiry->travel_month,
                    $enquiry->no_of_travellers,
                    $enquiry->hotel_category ?: 'Please advise',
                    $enquiry->budget_range,
                    $enquiry->comment,
                ];
            }
        })();

        $path = tempnam(sys_get_temp_dir(), 'b2b-export-');
        try {
            XlsxWriter::write($path, 'B2B Enquiries', $columns, $rows);
        } catch (\Throwable $e) {
            @unlink($path);
            throw $e;
        }

        $filename = 'b2b-partnership-enquiries-' . Carbon::now(B2BPartnershipEnquiry::DISPLAY_TIMEZONE)->format('Y-m-d-His') . '.xlsx';

        return response()
            ->download($path, $filename, ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
            ->deleteFileAfterSend(true);
    }

    public function show($id)
    {
        $enquiry = B2BPartnershipEnquiry::findOrFail($id);

        return view('b2b_partnership.show', compact('enquiry'));
    }

    // Builds the enquiries query from the request's filters (search, destination,
    // programme type and the two date ranges). Returns [query, filters, dateFilters].
    private function filteredQuery(Request $request)
    {
        $tz = B2BPartnershipEnquiry::DISPLAY_TIMEZONE;
        $filters = [
            'q' => trim((string) $request->q),
            'destination' => $request->destination,
            'travel_type' => $request->travel_type,
        ];

        $dateFilters = [];
        foreach (self::DATE_FILTERS as $key => $config) {
            $from = $this->validDate($request->query($config['from']));
            $to = $this->validDate($request->query($config['to']));
            if ($from && $to && $from > $to) {
                [$from, $to] = [$to, $from];
            }
            $dateFilters[$key] = $config + [
                'from_value' => $from,
                'to_value' => $to,
                'applied' => $from || $to,
                'summary' => $this->rangeSummary($from, $to),
                'remove_url' => $request->fullUrlWithoutQuery([$config['from'], $config['to'], 'page']),
            ];
        }

        $query = B2BPartnershipEnquiry::query();
        if ($filters['q'] !== '') {
            $like = '%' . addcslashes($filters['q'], '%_\\') . '%';
            $query->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                    ->orWhere('company_name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('mobile', 'like', $like)
                    ->orWhere('agency_country', 'like', $like);
            });
        }
        if (in_array($filters['destination'], B2BPartnershipEnquiry::DESTINATIONS, true)) {
            $query->whereJsonContains('destinations', $filters['destination']);
        }
        if (in_array($filters['travel_type'], B2BPartnershipEnquiry::TRAVEL_TYPES, true)) {
            $query->where('travel_type', $filters['travel_type']);
        }

        // Enquiry dates are picked in IST; created_at is stored in UTC.
        $received = $dateFilters['received'];
        if ($received['from_value']) {
            $query->where('created_at', '>=', Carbon::parse($received['from_value'], $tz)->startOfDay()->utc());
        }
        if ($received['to_value']) {
            $query->where('created_at', '<=', Carbon::parse($received['to_value'], $tz)->endOfDay()->utc());
        }

        // The form only asks for a travel month (stored as its 1st day), so a month
        // matches when any part of it falls inside the chosen range.
        $travel = $dateFilters['travel'];
        if ($travel['from_value']) {
            $query->where('travel_month', '>=', Carbon::parse($travel['from_value'])->startOfMonth()->toDateString());
        }
        if ($travel['to_value']) {
            $query->where('travel_month', '<=', $travel['to_value']);
        }

        return [$query, $filters, $dateFilters];
    }

    // Accepts a real calendar date as YYYY-MM-DD, any year.
    private function validDate($value)
    {
        if (!is_string($value) || !preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $value, $m)) {
            return null;
        }

        return (int) $m[1] > 0 && checkdate((int) $m[2], (int) $m[3], (int) $m[1]) ? $value : null;
    }

    private function rangeSummary($from, $to)
    {
        $format = fn ($date) => Carbon::parse($date)->format('d M Y');
        if ($from && $to) {
            return $format($from) . ' – ' . $format($to);
        }

        return $from ? 'from ' . $format($from) : ($to ? 'until ' . $format($to) : '');
    }
}
