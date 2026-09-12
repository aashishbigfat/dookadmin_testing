@extends('layouts.apps')
@section('headSection')
@section('title', 'Dook Enquiries')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
<link rel='stylesheet'
  href='https://cdn.datatables.net/v/bs-3.3.6/jqc-1.12.3/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.12/af-2.1.2/b-1.2.2/b-colvis-1.2.2/b-html5-1.2.2/b-print-1.2.2/cr-1.3.2/fc-3.2.2/fh-3.1.2/kt-2.1.3/r-2.1.0/rr-1.1.2/sc-1.4.2/se-1.2.0/datatables.min.css'>
  <style>
    table.table-bordered.dataTable tbody th, table.table-bordered.dataTable tbody td {
  border-bottom-width: 0;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  max-width: 200px;
}
  </style>
@endsection

@section('main-content')
<div class="content-wrapper">
  <section class="content-header">
    <div class="buttonInline" style="display:flex;justify-content: end;">
      <ol class="breadcrumb">
        <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a>Dook Enquiries Report</a></li>
      </ol>
    </div>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border pb-4" style="display: flex;justify-content:center">
            <h2>Dook Enquiries Report</h2>
          </div>
          <hr>
          <div class="dataIndex" id="dataIndex" style="padding-bottom: 35px;">
            <div class="container">
              <div class="row">
                <form action="{{ route('enquiry.report') }}" method="GET">
              
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="origin">Origin:</label>
                      <select class="form-control" id="origin" name="origin">
                        <option value="">Select Origin</option>
                        @foreach($origins as $origin)
                        <option value="{{ $origin }}" {{ isset($selected_origin) && $selected_origin==$origin
                          ? 'selected' : '' }}>
                          {{ $origin }}
                        </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="from_date">From Date:</label>
                      <input type="date" class="form-control" id="from_date" name="from_date" value="{{ $from_date ?? '' }}">

                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="to_date">To Date:</label>
                    <input type="date" class="form-control" id="to_date" name="to_date" value="{{ $to_date ?? '' }}">

                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="destination">Destination:</label>
                      <select class="form-control destinations" id="destination" name="destination">
                        <option value="">Select Destination</option>
                        @foreach($destinations as $destination)
                        <option value="{{ $destination }}" {{ isset($selected_destination) &&
                          $selected_destination==$destination ? 'selected' : '' }}>
                          {{ $destination }}
                        </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="travel_date">Travel Date:</label>
                     <input type="date" class="form-control" id="travel_date" name="travel_date" value="{{ $selected_travel_date ?? '' }}">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="source">Source:</label>
                      <select class="form-control" id="source" name="source">
    <option value="">Select Source</option>

    @foreach($sources as $source)
        <option value="{{ $source }}" {{ isset($selected_source) && $selected_source == $source ? 'selected' : '' }}>
            {{ $source }}
        </option>
    @endforeach
</select>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Generate Report</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        @if(isset($destinationData) && count($destinationData) > 0)
        <div class="box">
          <div class="container mt-5" style="padding-bottom: 35px;">
            <div class="box-header with-border pb-4 mb-4" style="display: flex;justify-content:center">
              <h2>Enquiry Report from {{ $from_date }} to {{ $to_date }}</h2>
            </div>
            <hr>
            {{-- <table class="table table-striped table-bordered ">
              <thead>
                <tr>
                  <th>Destination</th>
                  <th>Enquiry Count</th>
                  <th>Lead Count</th>
                  <th>Duplicate Count</th>
                  <th>Enquiry-Lead Difference</th>
                </tr>
              </thead> --}}
              <table id="example" class="table table-striped table-bordered dataTable" cellspacing="0" width="100%"
                role="grid" aria-describedby="example_info" style="width: 100%;">
                <thead>
                  <tr role="row">
                    <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1"
                      aria-label="Name: activate to sort column ascending" style="width: 137px !important;">Destination
                    </th>
                    <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1"
                      aria-label="Position: activate to sort column ascending" style="width: 215px;">Enquiry Count</th>
                    <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1"
                      aria-label="Office: activate to sort column ascending" style="width: 100px;">Lead Count</th>
                    <th class="sorting_desc" tabindex="0" aria-controls="example" rowspan="1" colspan="1"
                      aria-label="Age: activate to sort column ascending" aria-sort="descending" style="width: 44px;">
                      Duplicate Count</th>
                    <th class="sorting" tabindex="0" aria-controls="example" rowspan="1" colspan="1"
                      aria-label="Start date: activate to sort column ascending" style="width: 93px;">Enquiry-Lead
                      Difference</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($destinationData as $data)
                  <tr role="row">
                    <td>{{ $data['destination'] }}</td>
                    <td>{{ $data['enquiryCount'] }}</td>
                    <td>{{ $data['leadCount'] }}</td>
                    <td>{{ $data['duplicateCount'] }}</td>
                    <td>{{ $data['leadDuplicateDifference'] }}</td>
                  </tr>
                  @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th>{{ $totals['enquiryCount'] }}</th>
                        <th>{{ $totals['leadCount'] }}</th>
                        <th>{{ $totals['duplicateCount'] }}</th>
                        <th>{{ $totals['leadDuplicateDifference'] }}</th>
                    </tr>
                </tfoot>
              </table>
          </div>
        </div>
       @elseif(request()->hasAny(['from_date', 'to_date', 'origin', 'destination', 'travel_date', 'source']))
    <div class="box">
        <div class="container" style="padding: 30px;">
            <h4>No enquiry data found for selected filter.</h4>
        </div>
    </div>
@endif
      </div>
    </div>
  </section>
</div>
<style type="text/css">
  .box-header.with-border {
    border-bottom: none
  }
</style>
@endsection
<style type="text/css">
  .ui-datepicker-buttonpane.ui-widget-content {
    display: none;
  }
</style>
@section('footerSection')
<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
<script>
$(function () {
    if ($('#example').length) {
        $('#example').DataTable({
            paging: false,
            fixedHeader: true,
            dom: 'Bfrtip',
            buttons: ['excel', 'pdf', 'copy', 'colvis']
        });
    }
});
</script>
@endsection