@extends('layouts.apps')
@section('headSection')
@section('title', 'B2B Partnerships')

@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>B2B Partnerships <small>Enquiries from the website's B2B partnerships form (times in IST)</small></h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">B2B Partnerships</li>
      </ol>
    </section>
    <section class="content b2b-dashboard">
      <div class="row">
        <div class="col-md-3 col-sm-6">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-handshake-o"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total enquiries</span>
              <span class="info-box-number">{{ number_format($stats['total']) }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-clock-o"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Today</span>
              <span class="info-box-number">{{ number_format($stats['today']) }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-calendar-o"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Last 7 days</span>
              <span class="info-box-number">{{ number_format($stats['last_7_days']) }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-calendar"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">This month</span>
              <span class="info-box-number">{{ number_format($stats['this_month']) }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">Enquiries</h3>
              <form class="b2b-filters" method="get" action="{{ route('b2b_partnerships') }}">
                @if($perPage !== \App\Http\Controllers\B2BPartnershipController::DEFAULT_PER_PAGE)
                <input type="hidden" name="per_page" value="{{ $perPage }}">
                @endif
                <input type="search" class="form-control" name="q" value="{{ $filters['q'] }}" placeholder="Name, company, email, phone or country">
                <select class="form-control" name="destination">
                  <option value="">All destinations</option>
                  @foreach(\App\B2BPartnershipEnquiry::DESTINATIONS as $destination)
                  <option value="{{ $destination }}" @if($filters['destination'] === $destination) selected @endif>{{ \App\B2BPartnershipEnquiry::destinationLabel($destination) }}</option>
                  @endforeach
                </select>
                <select class="form-control" name="travel_type">
                  <option value="">All programme types</option>
                  @foreach(\App\B2BPartnershipEnquiry::TRAVEL_TYPES as $type)
                  <option value="{{ $type }}" @if($filters['travel_type'] === $type) selected @endif>{{ $type }}</option>
                  @endforeach
                </select>
                @foreach($dateFilters as $key => $dateFilter)
                <div class="b2b-date-filter" data-date-filter>
                  @if($dateFilter['applied'])
                  <span class="b2b-date-chip">
                    <button type="button" class="b2b-date-chip-label" data-date-toggle aria-expanded="false" aria-controls="b2b-date-panel-{{ $key }}" title="Change this filter">
                      <i class="fa fa-calendar"></i> {{ $dateFilter['label'] }}: {{ $dateFilter['summary'] }}
                    </button>
                    <a class="b2b-date-chip-remove" href="{{ $dateFilter['remove_url'] }}" title="Remove filter" aria-label="Remove {{ strtolower($dateFilter['label']) }} filter">&times;</a>
                  </span>
                  @else
                  <button type="button" class="btn btn-default" data-date-toggle aria-expanded="false" aria-controls="b2b-date-panel-{{ $key }}">
                    <i class="fa fa-calendar"></i> {{ $dateFilter['button'] }}
                  </button>
                  @endif
                  <div class="b2b-date-panel" id="b2b-date-panel-{{ $key }}" role="group" aria-label="{{ $dateFilter['label'] }} range" hidden>
                    <p class="b2b-date-panel-title">{{ $dateFilter['label'] }}</p>
                    <label>From <input type="date" class="form-control" name="{{ $dateFilter['from'] }}" value="{{ $dateFilter['from_value'] }}" data-applied="{{ $dateFilter['from_value'] }}" min="0001-01-01" max="9999-12-31" data-date-from></label>
                    <label>To <input type="date" class="form-control" name="{{ $dateFilter['to'] }}" value="{{ $dateFilter['to_value'] }}" data-applied="{{ $dateFilter['to_value'] }}" min="0001-01-01" max="9999-12-31" data-date-to></label>
                    @if($key === 'travel')
                    <p class="b2b-date-panel-help">Travel is recorded by month. Enquiries without a travel month are hidden while this filter is on.</p>
                    @endif
                    <p class="b2b-date-panel-error text-danger" hidden>Choose at least one date.</p>
                    <div class="b2b-date-panel-actions">
                      <button type="submit" class="btn btn-primary btn-sm" data-date-apply>Apply</button>
                      <button type="button" class="btn btn-default btn-sm" data-date-cancel>Cancel</button>
                    </div>
                  </div>
                </div>
                @endforeach
                <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Filter</button>
                {{-- Exports what the list currently shows: the applied filters, all pages. --}}
                <a class="btn btn-success" href="{{ route('b2b_partnerships.export', \Illuminate\Support\Arr::except(request()->query(), ['page', 'per_page'])) }}" title="Download these enquiries as an Excel file"><i class="fa fa-file-excel-o"></i> Export</a>
              </form>
            </div>
            @if($enquiries->count() > 0)
            <div class="b2b-list-bar">
              <span>Showing <strong>{{ number_format($enquiries->firstItem()) }}–{{ number_format($enquiries->lastItem()) }}</strong> of <strong>{{ number_format($enquiries->total()) }}</strong> {{ $enquiries->total() == 1 ? 'enquiry' : 'enquiries' }}</span>
              {{-- Changing the page size keeps the applied filters and goes back to page 1. --}}
              <form class="b2b-per-page" method="get" action="{{ route('b2b_partnerships') }}">
                @foreach(\Illuminate\Support\Arr::except(request()->query(), ['page', 'per_page']) as $name => $value)
                @if(is_string($value))
                <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                @endif
                @endforeach
                <label for="b2b-per-page">Rows per page</label>
                <select class="form-control input-sm" id="b2b-per-page" name="per_page">
                  @foreach($perPageOptions as $option)
                  <option value="{{ $option }}" @if($option === $perPage) selected @endif>{{ $option }}</option>
                  @endforeach
                </select>
                <noscript><button type="submit" class="btn btn-default btn-sm">Go</button></noscript>
              </form>
            </div>
            @endif
            <div class="box-body table-responsive">
              @if($enquiries->count() > 0)
              <table class="table table-bordered table-hover b2b-table">
                <thead>
                  <tr>
                    <th class="b2b-serial">S. No.</th>
                    <th>Name</th>
                    <th>Company Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Agency Country</th>
                    <th>Programme Type</th>
                    <th>Interested Destinations</th>
                    <th>Travel Month</th>
                    <th>Traveler Count</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($enquiries as $enquiry)
                  <tr>
                    {{-- Continues across pages: page 2 at 25 per page starts at 26. --}}
                    <td class="b2b-serial">{{ $enquiries->firstItem() + $loop->index }}</td>
                    <td><a href="{{ route('b2b_partnerships.show', $enquiry->id) }}" title="View full enquiry"><strong>{{ $enquiry->name }}</strong></a></td>
                    <td>{{ $enquiry->company_name }}</td>
                    <td><a class="b2b-break" href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a></td>
                    <td><a class="b2b-nowrap" href="tel:{{ $enquiry->mobile }}">{{ $enquiry->mobile }}</a></td>
                    <td>{{ $enquiry->agency_country }}</td>
                    <td>{{ $enquiry->travel_type ?: '—' }}</td>
                    <td>
                      @foreach((array) $enquiry->destinations as $destination)
                      <span class="label label-primary">{{ \App\B2BPartnershipEnquiry::destinationLabel($destination) }}</span>
                      @endforeach
                    </td>
                    <td class="b2b-nowrap">{{ $enquiry->travel_month ? $enquiry->travel_month->format('M Y') : '—' }}</td>
                    <td>{{ $enquiry->no_of_travellers ?: '—' }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @elseif($stats['total'] == 0)
              <h4 class="text-center text-muted">No B2B enquiries yet. Submissions from the website form will appear here.</h4>
              @else
              <h4 class="text-center text-muted">No enquiries match these filters.</h4>
              @endif
            </div>
            @if($enquiries->hasPages())
            <div class="box-footer b2b-pages">
              <span>Page <strong>{{ $enquiries->currentPage() }}</strong> of <strong>{{ $enquiries->lastPage() }}</strong></span>
              {{ $enquiries->links('b2b_partnership.pagination') }}
            </div>
            @endif
          </div>
        </div>
      </div>
    </section>
</div>
<style>
  .b2b-dashboard .b2b-filters {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
    margin-top: 12px;
  }

  .b2b-dashboard .b2b-filters .form-control {
    width: auto;
  }

  .b2b-dashboard .b2b-filters input[type="search"] {
    min-width: 260px;
  }

  .b2b-dashboard .b2b-filters label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin: 0;
    font-weight: normal;
  }

  .b2b-dashboard .b2b-date-filter {
    position: relative;
  }

  .b2b-dashboard .b2b-date-chip {
    display: inline-flex;
    align-items: stretch;
    border: 1px solid #3c8dbc;
    border-radius: 3px;
    background: #ecf4f9;
  }

  .b2b-dashboard .b2b-date-chip-label {
    border: 0;
    background: none;
    padding: 6px 10px;
    color: #1f5f86;
  }

  .b2b-dashboard .b2b-date-chip-remove {
    display: flex;
    align-items: center;
    padding: 0 11px;
    border-left: 1px solid #3c8dbc;
    color: #1f5f86;
    font-size: 20px;
    line-height: 1;
    text-decoration: none;
  }

  .b2b-dashboard .b2b-date-chip-remove:hover,
  .b2b-dashboard .b2b-date-chip-remove:focus {
    background: #3c8dbc;
    color: #fff;
  }

  .b2b-dashboard .b2b-date-panel {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    z-index: 20;
    width: 270px;
    max-width: calc(100vw - 32px);
    padding: 12px;
    background: #fff;
    border: 1px solid #d2d6de;
    border-radius: 3px;
    box-shadow: 0 6px 16px rgba(0, 0, 0, .15);
  }

  .b2b-dashboard .b2b-filters .b2b-date-panel label {
    display: block;
    margin-bottom: 8px;
  }

  .b2b-dashboard .b2b-filters .b2b-date-panel .form-control {
    width: 100%;
    margin-top: 4px;
  }

  .b2b-dashboard .b2b-date-panel-title {
    font-weight: 600;
    margin: 0 0 8px;
  }

  .b2b-dashboard .b2b-date-panel-help {
    color: #777;
    font-size: 12px;
    margin: 0 0 8px;
  }

  .b2b-dashboard .b2b-date-panel-error {
    margin: 0 0 8px;
  }

  .b2b-dashboard .b2b-date-panel-actions {
    display: flex;
    gap: 6px;
  }

  .b2b-dashboard .b2b-table .label {
    display: inline-block;
    margin: 0 2px 3px 0;
  }

  .b2b-dashboard .b2b-serial {
    width: 1%;
    white-space: nowrap;
    text-align: center;
  }

  .b2b-dashboard .b2b-nowrap {
    white-space: nowrap;
  }

  .b2b-dashboard .b2b-break {
    word-break: break-all;
  }

  .b2b-dashboard .b2b-list-bar,
  .b2b-dashboard .b2b-pages {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 8px 16px;
  }

  .b2b-dashboard .b2b-list-bar {
    padding: 10px 10px 0;
  }

  .b2b-dashboard .b2b-per-page {
    display: flex;
    align-items: center;
    gap: 6px;
    margin: 0;
  }

  .b2b-dashboard .b2b-per-page label {
    margin: 0;
    font-weight: normal;
  }

  .b2b-dashboard .b2b-per-page .form-control {
    width: auto;
  }

  .b2b-dashboard .pagination {
    margin: 0;
  }
</style>
<script>
  // Date filters: each button opens a From/To panel; applied filters show as a chip
  // whose × link removes them. Closing a panel without applying discards the edits.
  (function () {
    var form = document.querySelector('.b2b-filters');
    var filters = Array.prototype.slice.call(form.querySelectorAll('[data-date-filter]'));

    function parts(filter) {
      return {
        toggle: filter.querySelector('[data-date-toggle]'),
        panel: filter.querySelector('.b2b-date-panel'),
        from: filter.querySelector('[data-date-from]'),
        to: filter.querySelector('[data-date-to]'),
        error: filter.querySelector('.b2b-date-panel-error')
      };
    }

    // Keep "From" on or before "To".
    function syncLimits(p) {
      p.from.max = p.to.value || '9999-12-31';
      p.to.min = p.from.value || '0001-01-01';
    }

    function closePanel(filter) {
      var p = parts(filter);
      if (p.panel.hidden) return;
      p.panel.hidden = true;
      p.toggle.setAttribute('aria-expanded', 'false');
      p.from.value = p.from.dataset.applied;
      p.to.value = p.to.dataset.applied;
      p.error.hidden = true;
      syncLimits(p);
    }

    filters.forEach(function (filter) {
      var p = parts(filter);
      syncLimits(p);

      p.toggle.addEventListener('click', function () {
        var opening = p.panel.hidden;
        filters.forEach(closePanel);
        if (opening) {
          p.panel.hidden = false;
          p.toggle.setAttribute('aria-expanded', 'true');
          p.from.focus();
        }
      });

      filter.querySelector('[data-date-cancel]').addEventListener('click', function () {
        closePanel(filter);
        p.toggle.focus();
      });

      // Applying needs at least one date (clearing both on an applied filter removes it).
      filter.querySelector('[data-date-apply]').addEventListener('click', function (event) {
        var wasApplied = p.from.dataset.applied || p.to.dataset.applied;
        if (!p.from.value && !p.to.value && !wasApplied) {
          event.preventDefault();
          p.error.hidden = false;
          p.from.focus();
        }
      });

      [p.from, p.to].forEach(function (input) {
        input.addEventListener('change', function () {
          p.error.hidden = true;
          syncLimits(p);
        });
      });
    });

    document.addEventListener('click', function (event) {
      filters.forEach(function (filter) {
        if (!filter.contains(event.target)) closePanel(filter);
      });
    });

    document.addEventListener('keydown', function (event) {
      if (event.key !== 'Escape') return;
      filters.forEach(function (filter) {
        if (!parts(filter).panel.hidden) {
          closePanel(filter);
          parts(filter).toggle.focus();
        }
      });
    });

    // Leave empty fields out of the URL; re-enable them if the page is restored from history.
    var fields = Array.prototype.slice.call(form.querySelectorAll('input, select'));
    form.addEventListener('submit', function () {
      fields.forEach(function (field) {
        if (field.name && field.value === '') field.disabled = true;
      });
    });
    window.addEventListener('pageshow', function () {
      fields.forEach(function (field) { field.disabled = false; });
    });

    // Rows per page applies as soon as it changes.
    var perPage = document.getElementById('b2b-per-page');
    if (perPage) {
      perPage.addEventListener('change', function () {
        perPage.form.submit();
      });
    }
  })();
</script>
@endsection
