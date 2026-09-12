<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\DookEnquiry;
use Carbon\Carbon;

class EnquiryController extends Controller
{
    public function getEnquiries(Request $request)
    {
        $date = $request->start_date;
        $end_date = $request->end_date;
        $end_dates = date('Y-m-d', strtotime($end_date."+24 hours"));
        $tfcLeadF = $request->tfcLeadF;
        //dd($end_dates);
        if($request->start_date != "" && $request->end_date != "" && $request->tfcLeadF == ""){
            $enquiries = DB::table('dook_enquiries')
                ->whereNotIn('status',[7,6,5,17])
                ->whereBetween('created_at',[$date.'%', $end_dates.'%'])

                ->orderBy('id','DESC')
                ->paginate(25);
            $totals = DB::table('dook_enquiries')
                ->whereNotIn('status',[7,6,5,17])
                ->whereBetween('created_at',[$date.'%', $end_dates.'%'])
                ->count();
                //dd('gg');
        }elseif($request->tfcLeadF == "in" && $request->start_date == "" && $request->end_date == ""){
            $enquiries = DB::table('dook_enquiries')
                ->whereNotIn('status',[7,6,5,17])
                ->where('tfc_lead',0)

                ->orderBy('id','DESC')
                ->paginate(25);
            $totals = DB::table('dook_enquiries')
                ->whereNotIn('status',[7,6,5,17])
                ->where('tfc_lead',0)
                ->count();
                //dd('gg jjj');
        }elseif($request->tfcLeadF == 1 && $request->start_date == "" && $request->end_date == ""){
            $enquiries = DB::table('dook_enquiries')
                ->whereNotIn('status',[7,6,5,17])
                ->where('tfc_lead',1)

                ->orderBy('id','DESC')
                ->paginate(25);
            $totals = DB::table('dook_enquiries')
                ->whereNotIn('status',[7,6,5,17])
                ->where('tfc_lead',1)
                ->count();
                //dd('gg jjj');
        }elseif($request->tfcLeadF == "in" && $request->start_date != "" && $request->end_date != ""){
            $enquiries = DB::table('dook_enquiries')
                ->whereNotIn('status',[7,6,5,17])
                ->where('tfc_lead',0)
                ->whereBetween('created_at',[$date.'%', $end_dates.'%'])

                ->orderBy('id','DESC')
                ->paginate(25);
            $totals = DB::table('dook_enquiries')
                ->whereNotIn('status',[7,6,5,17])
                ->where('tfc_lead',0)
                ->whereBetween('created_at',[$date.'%', $end_dates.'%'])
                ->count();
                //dd('g');
        }
        elseif($request->tfcLeadF == 1 && $request->start_date != "" && $request->end_date != ""){
            $enquiries = DB::table('dook_enquiries')
                ->whereNotIn('status',[7,6,5,17])
                ->where('tfc_lead',1)
                ->whereBetween('created_at',[$date.'%', $end_dates.'%'])

                ->orderBy('id','DESC')
                ->paginate(25);
            $totals = DB::table('dook_enquiries')
                ->whereNotIn('status',[7,6,5,17])
                ->where('tfc_lead',1)
                ->whereBetween('created_at',[$date.'%', $end_dates.'%'])
                ->count();
                //dd('g');
        }
        else{
            $enquiries = DB::table('dook_enquiries')
                    ->whereNotIn('status',[7,6,5,17])
                    ->orderBy('id','DESC')
                    ->paginate(25);
            $totals = 0;
        }
        $total = DB::table('dook_enquiries')->whereNotIn('status', [7,6,5,17])->count();
        $totalSyncData = DB::table('dook_enquiries')->where('tfc_lead', 1)->count();
        $tfcLeadF = ($tfcLeadF == '')?'no':$tfcLeadF;

        // Count today total enquiry
        $today = Carbon::today()->toDateString();
        $today_enquiry = DookEnquiry::whereDate('created_at', $today)->whereNotIn('status', [7,6,5,17])->count();

        return view('enquiry.index',compact('enquiries','total','date','end_date','totals','totalSyncData','tfcLeadF','today_enquiry'));
    }

    public function filterEnquiries(Request $request)
    {
        $date = $request->start_date;
        $end_date = $request->end_date;
        $end_dates = date('Y-m-d', strtotime($end_date."+24 hours"));
        $tfcLeadF = $request->tfcLeadF;
        //dd($end_dates);
        if($request->start_date != "" && $request->end_date != "" && $request->tfcLeadF == ""){
            $enquiries = DB::table('dook_enquiries')
                ->whereNotIn('status',[7,6,5,17])
                ->whereBetween('created_at',[$date.'%', $end_dates.'%'])
                ->select('name', 'email', 'mob_no', 'travel_date', 'no_of_traveler', 'url', 'created_at', 'source')
                ->orderBy('id','DESC')
                ->get();
            $totals = DB::table('dook_enquiries')
                ->whereNotIn('status',[7,6,5,17])
                ->whereBetween('created_at',[$date.'%', $end_dates.'%'])
                ->count();
                //dd('gg');
        }elseif($request->tfcLeadF == "in" && $request->start_date == "" && $request->end_date == ""){
            $enquiries = DB::table('dook_enquiries')
                ->whereNotIn('status',[7,6,5,17])
                ->select('name', 'email', 'mob_no', 'travel_date', 'no_of_traveler', 'url', 'created_at', 'source')
                ->where('tfc_lead',0)
                ->orderBy('id','DESC')
                ->get();
            $totals = DB::table('dook_enquiries')
                ->whereNotIn('status',[7,6,5,17])
                ->where('tfc_lead',0)
                ->count();
                //dd('gg jjj');
        }elseif($request->tfcLeadF == 1 && $request->start_date == "" && $request->end_date == ""){
            $enquiries = DB::table('dook_enquiries')
                ->whereNotIn('status',[7,6,5,17])
                ->where('tfc_lead',1)
                ->select('name', 'email', 'mob_no', 'travel_date', 'no_of_traveler', 'url', 'created_at', 'source')
                ->orderBy('id','DESC')
                ->get();
            $totals = DB::table('dook_enquiries')
                ->whereNotIn('status',[7,6,5,17])
                ->where('tfc_lead',1)
                ->count();
                //dd('gg jjj');
        }elseif($request->tfcLeadF == "in" && $request->start_date != "" && $request->end_date != ""){
            $enquiries = DB::table('dook_enquiries')
                ->whereNotIn('status',[7,6,5,17])
                ->where('tfc_lead',0)
                ->whereBetween('created_at',[$date.'%', $end_dates.'%'])
                ->select('name', 'email', 'mob_no', 'travel_date', 'no_of_traveler', 'url', 'created_at', 'source')
                ->orderBy('id','DESC')
                ->get();
            $totals = DB::table('dook_enquiries')
                ->whereNotIn('status',[7,6,5,17])
                ->where('tfc_lead',0)
                ->whereBetween('created_at',[$date.'%', $end_dates.'%'])
                ->count();
                //dd('g');
        }
        elseif($request->tfcLeadF == 1 && $request->start_date != "" && $request->end_date != ""){
            $enquiries = DB::table('dook_enquiries')
                ->whereNotIn('status',[7,6,5,17])
                ->where('tfc_lead',1)
                ->whereBetween('created_at',[$date.'%', $end_dates.'%'])
                ->select('name', 'email', 'mob_no', 'travel_date', 'no_of_traveler', 'url', 'created_at', 'source')
                ->orderBy('id','DESC')
                ->get();
            $totals = DB::table('dook_enquiries')
                ->whereNotIn('status',[7,6,5,17])
                ->where('tfc_lead',1)
                ->whereBetween('created_at',[$date.'%', $end_dates.'%'])
                ->count();
                //dd('g');
        }
        else{
            $enquiries = DB::table('dook_enquiries')
                    ->whereNotIn('status',[7,6,5,17])
                    ->select('name', 'email', 'mob_no', 'travel_date', 'no_of_traveler', 'url', 'created_at', 'source')
                    ->orderBy('id','DESC')
                    ->limit(25000)
                    ->get();
            $totals = 0;
        }
        $total = DB::table('dook_enquiries')->whereNotIn('status', [7,6,5,17])->count();
        $totalSyncData = DB::table('dook_enquiries')->where('tfc_lead', 1)->count();
        $tfcLeadF = ($tfcLeadF == '')?'no':$tfcLeadF;

        // Count today total enquiry
        $today = Carbon::today()->toDateString();
        $today_enquiry = DookEnquiry::whereDate('created_at', $today)->whereNotIn('status', [7,6,5,17])->count();

        return view('enquiry.filter',compact('enquiries','total','date','end_date','totals','totalSyncData','tfcLeadF','today_enquiry'));
    }

    public function getJobs()
    {
        $jobs = DB::table('dook_enquiries')
                    ->where('status', 7)
                    ->orderBy('id','DESC')
                    ->paginate(15);
        foreach ($jobs as $key => $value) {
            $value->file =env('AWS_URL') .'/careers'.'/'.$value->file;
        }
        $total = DB::table('dook_enquiries')->where('status', 7)->count();
        
        return view('enquiry.job_enquiry',compact('jobs','total'));
    }

    public function deleteEnquiries(Request $request, $id)
    {
        $enquiry  = DookEnquiry::where('id',$id)->delete();
        return response()->json(['success'=>'Success!']);
    }

    public function syncLeadWithTFC()
    {
        $emails = DB::table('dook_enquiries')
                ->whereNotIn('status',[7,6,5,17])
                ->where('tfc_lead',0)
                ->where('not_in_tfc',0)
                ->where('created_at','>=','2022-07-21')
                ->inRandomOrder()
                ->limit(200)
                ->select('email',DB::raw('DATE(created_at) AS created_at'))
                ->get();
        $post = array(
            'data' => $emails
        );
        $decode_data = json_encode($post);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://dooktravels.tutterflycrm.com/tfc/api/check_capture_leads');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $decode_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',                                                                                
            'Content-Length: ' . strlen($decode_data))                                                                       
        );
        $response = curl_exec($ch);
        $leads = json_decode($response);
        //dd($leads);
        $i = 0;
        if(count($leads->results)>0){
            foreach ($leads->results as $key => $value) {
                if($value->leadCheck>0){
                    $save = DookEnquiry::where('email',$value->email)->first();

                    //$save = DookEnquiry::find($data->id);
                    $save->tfc_lead = 1;
                    $save->tfc_lead_id = $value->lead_id;
                    $save->save();
                    $i++;
                }
            }
        }
        return response()->json($i);   
    }

    public function syncLeadWithTFCRefId()
    {
        $emails = DB::table('dook_enquiries')
                ->whereNotIn('status',[7,6,5,17])
                ->where('created_at','>=','2022-08-30')
                ->where('tfc_lead',0)
                ->where('not_in_tfc',0)
                ->where('ref_id','!=','')
                ->inRandomOrder()
                ->limit(150)
                ->select('ref_id')
                ->get();
        $post = array(
            'data' => $emails
        );
        $decode_data = json_encode($post);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://dooktravels.tutterflycrm.com/tfc/api/check_capture_leads_ref_id');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $decode_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',                                                                                
            'Content-Length: ' . strlen($decode_data))                                                                       
        );
        $response = curl_exec($ch);
        $leads = json_decode($response);
        //dd($leads);
        $i = 0;
        if(count($leads->results)>0){
            foreach ($leads->results as $key => $value) {
                $save = DookEnquiry::where('ref_id',$value->ref_id)->first();
                if($value->leadCheck>0){
                    $save->tfc_lead = 1;
                    $save->tfc_lead_id = $value->lead_id;
                    $save->save();
                    $i++;
                }
                else{
                    $save->tfc_lead = 0;
                    $save->not_in_tfc = 1;
                    $save->save();
                    $i++;
                }
            }
        }
        return response()->json($i);   
    }

    public function abcd(){

        $update = DookEnquiry::find(11635);
        $update->ref_id = "DOOK-11635";
        $update->source = "";
        $update->save();
        $curl_data = json_encode(array("token"=> env('TUTTERFLY_CRM_TOKEN'),"lead"=>array("first_name"=>"", "last_name"=>"Kharag Dudhoria","city"=>"Guwahati","country"=>"India", "email"=>"kharag_dudhoria@yahoo.com", "mobile"=>"9819388858", "phone"=>"9819388858", "company"=>"", "website"=>"","campaign_name"=>"WhatsApp Enquiry-","ref_id"=>"DOOK-11635","custom_fields"=>array("segment"=>"", "destination"=>"Baku", "description"=>"", "no_of_passengers"=>50,"date_of_travel"=>"2022-10-14","bnpl"=>1))));

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://dooktravels.tutterflycrm.com/tfc/api/capture_lead');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curl_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',                                                                                
            'Content-Length: ' . strlen($curl_data))                                                                       
        );

        $result = curl_exec($ch);
        curl_close($ch);
    }

    public function watResume()
    {
        $jobs = DB::table('dook_enquiries')
                    ->where('status', 17)
                    ->orderBy('id','DESC')
                    ->paginate(50);
        foreach ($jobs as $key => $value) {
            $value->resume = url('dook/resume').'/'.$value->resume;
        }
        $total = DB::table('dook_enquiries')->where('status', 17)->count();
        
        return view('enquiry.wat_job_enquiry',compact('jobs','total'));
    }

    public function dailyReport(Request $request)
    {
        $sort_order = $request->input('sort_order')?$request->input('sort_order'):'desc';
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $start_date = date('Y-m-d', strtotime($startDate));
        $end_dates = date('Y-m-d', strtotime($endDate."+24 hours"));
    
        $query = DookEnquiry::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->whereNotIn('status', [7, 6, 5, 17])
            ->orderBy(DB::raw('DATE(created_at)'), $sort_order)
            ->groupBy(DB::raw('DATE(created_at)'));
    
        if ($startDate && $endDate) {
            $query->whereBetween('created_at',[$start_date.'%', $end_dates.'%']);
        }
    
        $reports = $query->paginate(15);
        $total = DB::table('dook_enquiries')->whereNotIn('status', [7,6,5,17])->count();
    
        return view('enquiry.daily_report', compact('reports', 'total','startDate','endDate','sort_order'));
    }

    public function emailCount(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $start_date = date('Y-m-d', strtotime($startDate));
        $end_dates = date('Y-m-d', strtotime($endDate."+24 hours"));

        if ($startDate && $endDate) {
            $email = DookEnquiry::whereNotNull('email')
                ->whereNotIn('status', [7, 6, 5, 17])
                ->whereBetween('created_at',[$start_date.'%', $end_dates.'%'])
                ->pluck('email');

            $reports = DookEnquiry::select('email', \DB::raw('count(*) as count'))
                ->whereIn('email', $email)
                ->groupBy('email')
                ->havingRaw('count(*) > 1')
                ->orderBy(\DB::raw('max(created_at)'), 'DESC')
                ->get();

            $total = DB::table('dook_enquiries')
                ->whereNotIn('status', [7,6,5,17])
                ->whereBetween('created_at',[$start_date.'%', $end_dates.'%'])
                ->count();    
            
        }else{
            $reports = DookEnquiry::select('email', \DB::raw('count(*) as count'))
                ->whereNotNull('email')
                ->whereNotIn('status', [7, 6, 5, 17])
                ->groupBy('email')
                ->havingRaw('count(*) > 1')
                ->orderBy(\DB::raw('max(created_at)'), 'DESC')
                ->get(); 

            $total = DB::table('dook_enquiries')
                ->whereNotIn('status', [7,6,5,17])
                ->count();
        }    

        return view('enquiry.email_count', compact('total','reports','startDate','endDate'));
    }

    public function countryCount(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $start_date = date('Y-m-d', strtotime($startDate));
        $end_dates = date('Y-m-d', strtotime($endDate."+24 hours"));

        if ($startDate && $endDate) {
            $reports = DookEnquiry::select('country', \DB::raw('count(*) as count'))
                ->whereBetween('created_at',[$start_date.'%', $end_dates.'%'])
                ->whereNotNull('country')
                ->whereNotIn('status', [7, 6, 5, 17])
                ->groupBy('country')
                ->orderBy(\DB::raw('max(created_at)'), 'DESC')
                ->get();

            $total = DB::table('dook_enquiries')
                ->whereNotIn('status', [7,6,5,17])
                ->whereBetween('created_at',[$start_date.'%', $end_dates.'%'])
                ->count();  
        }else{
            $reports = DookEnquiry::select('country', \DB::raw('count(*) as count'))
                ->whereNotNull('country')
                ->whereNotIn('status', [7, 6, 5, 17])
                ->groupBy('country')
                ->orderBy(\DB::raw('max(created_at)'), 'DESC')
                ->get(); 

            $total = DB::table('dook_enquiries')
                ->whereNotIn('status', [7,6,5,17])
                ->count();
        }    
    

        return view('enquiry.country_count', compact('total','reports','startDate','endDate'));
    }

      public function leadCount(Request $request){
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $start_date = date('Y-m-d', strtotime($startDate));
        $end_dates = date('Y-m-d', strtotime($endDate."+24 hours"));

        // if($startDate && $startDate < '10/30/2023'){
        //    $start_date_error = true;
        // }

        if ($startDate && $endDate) {
            $total_lead = DB::table('dook_enquiries')
                ->whereNotIn('status', [7, 6, 5, 17])
                ->where('tfc_lead_id', '!=', 0)
                ->whereBetween('created_at',[$start_date.'%', $end_dates.'%'])
                ->count();

            $missed_lead = DB::table('dook_enquiries')
                ->whereNotIn('status', [7,6,5,17])
                ->where('tfc_lead_id',0)
                ->whereBetween('created_at',[$start_date.'%', $end_dates.'%'])
                ->count();

            $enquiries = DB::table('dook_enquiries')
                ->whereNotIn('status',[7,6,5,17])
                ->where('tfc_lead_id',0)
                ->whereBetween('created_at',[$start_date.'%', $end_dates.'%'])
                ->orderBy('id','DESC')
                ->get();
        }else{
            $total_lead = DB::table('dook_enquiries')
                ->whereNotIn('status', [7, 6, 5, 17])
                ->where('tfc_lead_id', '!=', 0)
                ->whereBetween('created_at', ['2023-10-30 00:00:00', now()])
                ->count();

            $missed_lead = DB::table('dook_enquiries')
                ->whereNotIn('status', [7,6,5,17])
                ->where('tfc_lead_id',0)
                ->whereBetween('created_at', ['2023-10-30 00:00:00', now()])
                ->count();

            $enquiries = DB::table('dook_enquiries')
                ->whereNotIn('status',[7,6,5,17])
                ->where('tfc_lead_id',0)
                ->whereBetween('created_at', ['2023-10-30 00:00:00', now()])
                ->orderBy('id','DESC')
                ->get();
        }    
    
        return view('enquiry.lead_count', compact('total_lead','missed_lead','enquiries','startDate','endDate'));
    }

    
    public function generateReport(Request $request)
    {
        // Retrieve input data
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $origin = $request->input('origin');
        $destination = $request->input('destination');
        $travelDate = $request->input('travel_date');
        $source = $request->input('source');

        // Common destination names with known wrong spellings stored in DB.
        $destinationAliases = [
            'Almaty' => ['almaty', 'alamty', 'almatty', 'almathy', 'almati', 'almatt', 'almarty', 'almary', 'alamghty', 'almat'],
            'Baku' => ['baku'],
            'Azerbaijan' => ['azerbaijan', 'azarbiajan', 'azerbaijan'],
            'Kazakhstan' => ['kazakhstan', 'kazakistan', 'kazakhastan', 'kazaksthan'],
            'Tashkent' => ['tashkent', 'taskent'],
            'Bishkek' => ['bishkek'],
            'Georgia' => ['georgia'],
            'Armenia' => ['armenia'],
            'Turkey' => ['turkey', 'turkiye'],
            'Dubai' => ['dubai'],
            'Vietnam' => ['vietnam', 'viet nam'],
            'Thailand' => ['thailand'],
            'Singapore' => ['singapore'],
            'Malaysia' => ['malaysia'],
            'Russia' => ['russia'],
            'Europe' => ['europe'],
        ];

        // Common source names with known variants stored in DB.
        $sourceAliases = [
            'Facebook' => ['facebook', 'fb', 'fb-sitelink-1', 'fb-sitelink-4'],
            'Instagram' => ['instagram', 'ig'],
            'Google' => ['google', 'gogle'],
            'Organic' => ['organic', ''],
            'Enquire Now Form' => ['enquire now form'],
            'Get A Call Back Form' => ['get a call back form'],
            'Contact Us' => ['contactus', 'contact us'],
            'B2C Salesforce' => ['b2c salesforce'],
            'All Subscribers' => ['all subscribers'],
            'Netcorebroadcast' => ['netcorebroadcast'],
            'Trustpilot' => ['trustpilot'],
            'Visa' => ['visa'],
            'Perplexity' => ['perplexity'],
            'Chatgpt.com' => ['chatgpt.com'],
            'General' => ['general'],
            'B2C' => ['b2c'],
            'Message' => ['msg', 'message'],
            'Test' => ['test'],
        ];

        $normalizeSource = function ($value) use ($sourceAliases) {
            $value = strtolower(trim((string) $value));

            foreach ($sourceAliases as $label => $aliases) {
                if (in_array($value, $aliases)) {
                    return $label;
                }
            }

            if ($value == '') {
                return 'Organic';
            }

            return ucwords($value);
        };

        $normalizeDestination = function ($value) use ($destinationAliases) {
            $value = strtolower(trim((string) $value));

            if ($value == '') {
                return null;
            }

            foreach ($destinationAliases as $label => $aliases) {
                foreach ($aliases as $alias) {
                    if (strpos($value, $alias) !== false) {
                        return $label;
                    }
                }
            }

            return ucwords($value);
        };

        // Initialize query
        $query = DookEnquiry::query();

        // Apply filters
        if ($fromDate) {
            $fromDateTime = date('Y-m-d 00:00:00', strtotime($fromDate));
            $query->where('created_at', '>=', $fromDateTime);
        }

        if ($toDate) {
            $toDateTime = date('Y-m-d 23:59:59', strtotime($toDate));
            $query->where('created_at', '<=', $toDateTime);
        }

        if ($origin) {
            $query->whereRaw('LOWER(origin) LIKE ?', ['%' . strtolower(trim($origin)) . '%']);
        }

        if ($destination) {
            $destinationLower = strtolower(trim($destination));
            $destinationSearchTerms = [$destinationLower];

            foreach ($destinationAliases as $label => $aliases) {
                if ($destinationLower == strtolower($label) || in_array($destinationLower, $aliases)) {
                    $destinationSearchTerms = array_unique(array_merge($destinationSearchTerms, $aliases));
                    break;
                }
            }

            $query->where(function ($q) use ($destinationSearchTerms) {
                foreach ($destinationSearchTerms as $term) {
                    $q->orWhereRaw('LOWER(destination) LIKE ?', ['%' . $term . '%']);
                }
            });
        }

        if ($travelDate) {
            $query->where(function ($q) use ($travelDate) {
                $q->where('travel_date', date('d-m-Y', strtotime($travelDate)))
                    ->orWhere('travel_date', date('Y-m-d', strtotime($travelDate)))
                    ->orWhere('travel_date', $travelDate);
            });
        }

        if ($source) {
            $sourceLower = strtolower(trim($source));
            $sourceSearchTerms = [$sourceLower];

            foreach ($sourceAliases as $label => $aliases) {
                if ($sourceLower == strtolower($label) || in_array($sourceLower, $aliases)) {
                    $sourceSearchTerms = array_unique(array_merge($sourceSearchTerms, $aliases));
                    break;
                }
            }

            if (in_array('', $sourceSearchTerms) || in_array('organic', $sourceSearchTerms)) {
                $query->where(function ($q) use ($sourceSearchTerms) {
                    $q->whereNull('source')
                        ->orWhere('source', '');

                    foreach ($sourceSearchTerms as $term) {
                        if ($term != '' && $term != 'organic') {
                            $q->orWhereRaw('LOWER(source) = ?', [$term]);
                        }
                    }
                });
            } else {
                $query->where(function ($q) use ($sourceSearchTerms) {
                    foreach ($sourceSearchTerms as $term) {
                        $q->orWhereRaw('LOWER(source) = ?', [$term]);
                    }
                });
            }
        }

        // Fetch enquiries only if at least one filter is applied
        $enquiries = ($fromDate || $toDate || $origin || $destination || $travelDate || $source) ? $query->get() : collect();

        // Group data by clean destination name
        $destinationData = $enquiries->groupBy(function ($enquiry) use ($normalizeDestination) {
            return $normalizeDestination($enquiry->destination) ?: 'Unknown';
        })->map(function ($destinationEnquiries, $destinationName) {
            // Enquiry count for this destination
            $enquiryCount = $destinationEnquiries->count();

            // Lead count (tfc_lead_id > 0)
            $leadCount = $destinationEnquiries->where('tfc_lead_id', '>', 0)->count();

            // Count duplicates
            $duplicates = $destinationEnquiries->groupBy(function ($enquiry) {
                return strtolower(trim((string) $enquiry->email)) . '|'
                    . trim((string) $enquiry->mob_no) . '|'
                    . trim((string) $enquiry->travel_date) . '|'
                    . strtolower(trim((string) $enquiry->destination));
            })->filter(function ($group) {
                return $group->count() > 1;
            });

            $duplicateCount = $duplicates->count();

            // Calculate the difference between lead count and duplicate count
            $leadDuplicateDifference = $leadCount - $duplicateCount;

            return [
                'destination' => $destinationName,
                'enquiryCount' => $enquiryCount,
                'leadCount' => $leadCount,
                'duplicateCount' => $duplicateCount,
                'leadDuplicateDifference' => $leadDuplicateDifference
            ];
        })->values()->toArray();

        $totals = [
            'enquiryCount' => array_sum(array_column($destinationData, 'enquiryCount')),
            'leadCount' => array_sum(array_column($destinationData, 'leadCount')),
            'duplicateCount' => array_sum(array_column($destinationData, 'duplicateCount')),
            'leadDuplicateDifference' => array_sum(array_column($destinationData, 'leadDuplicateDifference')),
        ];

        // Fetch unique origins, destinations, and sources for the dropdown
        $origins = DookEnquiry::select('origin')
            ->whereNotNull('origin')
            ->where('origin', '!=', '')
            ->distinct()
            ->orderBy('origin')
            ->pluck('origin');

        $rawDestinations = DookEnquiry::select('destination')
            ->whereNotNull('destination')
            ->where('destination', '!=', '')
            ->distinct()
            ->pluck('destination');

        $destinationList = [];

        foreach ($rawDestinations as $rawDestination) {
            $parts = preg_split('/[,\/]+/', $rawDestination);

            foreach ($parts as $part) {
                $cleanDestination = $normalizeDestination($part);

                if ($cleanDestination) {
                    $destinationList[] = $cleanDestination;
                }
            }
        }

        $destinations = collect($destinationList)->unique()->sort()->values();

        $rawSources = DookEnquiry::select('source')->distinct()->pluck('source');
        $sourceList = [];

        foreach ($rawSources as $rawSource) {
            $sourceList[] = $normalizeSource($rawSource);
        }

        $sourceList[] = 'Organic';
        $sources = collect($sourceList)->unique()->sort()->values();

        return view('enquiry.fixed_departure_queries', [
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'selected_travel_date' => $travelDate,
            'destinationData' => $destinationData,
            'origins' => $origins,
            'destinations' => $destinations,
            'sources' => $sources,
            'selected_origin' => $origin,
            'selected_destination' => $destination,
            'selected_source' => $source,
            'totals' => $totals,
        ]);
    }
    //    public function monthReport(Request $request)
    // {
    //     $sort_order = $request->input('sort_order', 'desc');
    //     $startDate = $request->input('start_date', date('Y-m-01')); 
    //     $endDate = $request->input('end_date', date('Y-m-d'));

    //     $startDate = date('Y-m-d', strtotime($startDate));
    //     $endDate = date('Y-m-d 23:59:59', strtotime($endDate));

    //     $destinations = ['Almaty', 'Baku', 'Azerbaijan', 'Kazakhstan', 'Uzbekistan', 'Georgia', 'Turkey','Istanbul', 'Tashkent', 'Tbilisi', 'Bishkek'];

    //     // Query for filtered reports
    //     $query = DookEnquiry::select(
    //             DB::raw('COALESCE(destination, "Other") as destination'),
    //             DB::raw('COALESCE(source, "Unknown") as source'),
    //             DB::raw('COUNT(*) as count')
    //         )
    //         ->where(function($q) use ($destinations) {
    //             $q->whereIn('destination', $destinations)
    //               ->orWhereNull('destination');
    //         })
    //         ->whereBetween('created_at', [$startDate, $endDate])
    //         ->groupBy(DB::raw('COALESCE(destination, "Other")'), DB::raw('COALESCE(source, "Unknown")'))
    //         ->orderBy(DB::raw('COUNT(*)'), $sort_order);

    //     $reports = $query->paginate(15);

    //     // Calculate total counts
    //     $totalCounts = DookEnquiry::whereNotIn('status', [7, 6, 5, 17])
    //         ->where(function($q) use ($destinations) {
    //             $q->whereIn('destination', $destinations)
    //               ->orWhereNull('destination');
    //         })
    //         ->whereBetween('created_at', [$startDate, $endDate])
    //         ->select(DB::raw('COALESCE(destination, "Other") as destination'), DB::raw('COALESCE(source, "Unknown") as source'), DB::raw('COUNT(*) as count'))
    //         ->groupBy(DB::raw('COALESCE(destination, "Other")'), DB::raw('COALESCE(source, "Unknown")'))
    //         ->pluck('count', 'destination');

    //     $total = DookEnquiry::count();

    //     return view('enquiry.montly_queries', compact('reports', 'totalCounts', 'startDate', 'endDate', 'sort_order', 'destinations', 'total'));
    // }

   public function monthReport(Request $request)
    {
        $sort_order = $request->input('sort_order', 'desc');
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-d'));

        $startDate = date('Y-m-d', strtotime($startDate));
        $endDate = date('Y-m-d 23:59:59', strtotime($endDate));

        $destinations = ['Almaty', 'Baku', 'Azerbaijan', 'Kazakhstan', 'Uzbekistan', 'Georgia', 'Turkey', 'Istanbul', 'Tashkent', 'Tbilisi', 'Bishkek', 'Sri Lanka', 'Russia', 'Malaysia', 'Kyrgyzstan', 'Indonesia', 'Honeymoon', 'Dubai', 'Belarus', 'Armenia', 'Central Asia', 'South Korea', 'Europe'];

        // Query for total count per destination
        $destinationTotals = DookEnquiry::select(
                DB::raw('COALESCE(destination, "Other") as destination'),
                DB::raw('COUNT(*) as total_count')
            )
            ->where(function($q) use ($destinations) {
                $q->whereIn('destination', $destinations)
                  ->orWhereNull('destination');
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('COALESCE(destination, "Other")'))
            ->orderBy(DB::raw('COUNT(*)'), $sort_order)
            ->paginate(15);

        // Query for counts grouped by destination and source
        $sourceCounts = DookEnquiry::select(
                DB::raw('COALESCE(destination, "Other") as destination'),
                DB::raw('COALESCE(source, "Other") as source'),
                DB::raw('COUNT(*) as count')
            )
            ->where(function($q) use ($destinations) {
                $q->whereIn('destination', $destinations)
                  ->orWhereNull('destination');
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('COALESCE(destination, "Other")'), DB::raw('COALESCE(source, "Other")'))
            ->get()
            ->groupBy('destination');

        // Prepare the final source counts per destination
        $finalSourceCounts = $destinationTotals->mapWithKeys(function ($destination) use ($sourceCounts) {
            $destinationName = $destination->destination;
            $counts = [
                'Google' => 0,
                'Facebook' => 0,
                'Instagram' => 0,
                'Get a Call Back form' =>0,
                'Enquire now form' =>0,
                'Other' => 0,
            ];

            if (isset($sourceCounts[$destinationName])) {
                foreach ($sourceCounts[$destinationName] as $sourceCount) {
                    $source = $sourceCount->source;
                    $count = $sourceCount->count;

                    if (in_array($source, ['Google', 'Facebook', 'Instagram','Get a Call Back form','Enquire now form'])) {
                        $counts[$source] = $count;
                    } else {
                        $counts['Other'] += $count;
                    }
                }
            }

            $counts['Total'] = array_sum($counts); 
            return [$destinationName => $counts];
        });

        // Calculate the global totals
        $totalCounts = [
            'Google' => $finalSourceCounts->pluck('Google')->sum(),
            'Facebook' => $finalSourceCounts->pluck('Facebook')->sum(),
            'Instagram' => $finalSourceCounts->pluck('Instagram')->sum(),
            'Get a Call Back form' => $finalSourceCounts->pluck('Get a Call Back form')->sum(),
            'Enquire now form' => $finalSourceCounts->pluck('Enquire now form')->sum(),
            'Other' => $finalSourceCounts->pluck('Other')->sum(),
            'Total' => $finalSourceCounts->pluck('Total')->sum(),
        ];

        $total = DookEnquiry::count();

        return view('enquiry.montly_queries', compact('destinationTotals', 'finalSourceCounts', 'totalCounts', 'startDate', 'endDate', 'sort_order', 'destinations', 'total'));
    }



    // public function monthReport(Request $request)
    // {
    //     $sort_order = $request->input('sort_order', 'desc');
    //     $startDate = date('Y-m-01');
    //     $endDate = date('Y-m-d');
    //     $end_dates = date('Y-m-d 23:59:59', strtotime($endDate));
        
    //     // List of specific destinations to filter by
    //     $destinations = ['Almaty', 'Baku', 'Azerbaijan', 'Kazakhstan', 'Uzbekistan', 'Georgia', 'Turkey','Istanbul','Tashkent'];
        
    //     $query = DookEnquiry::select(
    //             DB::raw('COALESCE(destination, "Other") as destination'),
    //             DB::raw('COUNT(*) as count')
    //         )
    //         ->where(function($q) use ($destinations) {
    //             $q->whereIn('destination', $destinations)
    //               ->orWhereNull('destination');
    //         })
    //         ->whereBetween('created_at', [$startDate, $end_dates])
    //         ->groupBy(DB::raw('COALESCE(destination, "Other")'))
    //         ->orderBy(DB::raw('COUNT(*)'), $sort_order);

    //     $reports = $query->paginate(15);

    //     $totalCounts = DookEnquiry::whereNotIn('status', [7, 6, 5, 17])
    //         ->where(function($q) use ($destinations) {
    //             $q->whereIn('destination', $destinations)
    //               ->orWhereNull('destination');
    //         })
    //         ->whereBetween('created_at', [$startDate, $end_dates])
    //         ->select(DB::raw('COALESCE(destination, "Other") as destination'), DB::raw('COUNT(*) as count'))
    //         ->groupBy(DB::raw('COALESCE(destination, "Other")'))
    //         ->pluck('count', 'destination')
    //         ->count();
    //    $total = DB::table('dook_enquiries')->count();
    //    return view('enquiry.montly_queries', compact('reports', 'totalCounts', 'startDate', 'endDate', 'sort_order', 'destinations','total'));
    // }


     public function bookingdetail()
    {
        $booking = DB::table('agent_customer')->get();
        $travel =DB::table('travel')->get();                   
        
        return view('enquiry.booking',compact('booking'));
    }
}
