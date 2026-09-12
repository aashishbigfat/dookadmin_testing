@extends('layouts.apps')
@section('headSection')
@section('title', 'Monthly Reports')
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
        <a class="btn btn-primary" href="{{ route('enquiries') }}" title="click now.!"> Click to see all Dook Enquiries <sup style="color:#ffeb00">{{ $total }}</sup></a>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a>Monthly Reports</a></li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                   <div class="box-header with-border" style="display: flex;">
                        <form action="{{ route('report') }}" method="get" style="display: inline-flex; margin-bottom: 0;">
                            <div class="input-group date" style="margin-right: 10px;">
                                <input type="date" class="form-control pull-right" name="start_date" id="start_date_daily_reports" value="{{ $startDate }}" autocomplete="off" placeholder="Ex- yy-mm-dd">
                            </div>
                            <div class="input-group date">
                                <input type="date" class="form-control pull-right" name="end_date" id="end_date_daily_reports" value="{{ $endDate }}" autocomplete="off" placeholder="Ex- yy-mm-dd">
                            </div>
                            <button type="submit" class="btn btn-info" style="margin-left: 10px;">Search</button>
                        </form>
                    </div>
                 <div class="row">
                  <div class="col-md-12 mt-3" style="display: flex;justify-content: center;padding: 10px;">
                    <?php
                        // Use Carbon for date formatting
                        use Carbon\Carbon;

                        $formattedStartDate = Carbon::parse($startDate)->format('d-m-Y');
                        $formattedEndDate = Carbon::parse($endDate)->format('d-m-Y');
                    ?>
                    <h4><b>Date:</b> {{ $formattedStartDate }} To {{ $formattedEndDate }} <b>Grand Total: {{ $totalCounts['Total'] }}</b></h4>
                  </div>
                  <hr>
                </div>
                    <div class="dataIndex" id="dataIndex">
                        <div class="box-body" style="overflow-x: scroll;">
                            <table id="destinationListData" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Destination</th>
                                        <th>Total Count</th>
                                        <th>Google</th>
                                        <th>Facebook</th>
                                        <th>Instagram</th>
                                        <th>Get a call back</th>
                                        <th>Enquire now form</th>
                                        <th>Other</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($destinationTotals as $key => $destination)
                                    <tr>
                                        <td>{{ ($destinationTotals->currentPage() - 1) * $destinationTotals->perPage() + $key + 1 }}</td>
                                        <td>{{ htmlspecialchars($destination->destination) }}</td>
                                        <td>{{ $finalSourceCounts[$destination->destination]['Total'] }}</td>
                                        <td>{{ $finalSourceCounts[$destination->destination]['Google'] }}</td>
                                        <td>{{ $finalSourceCounts[$destination->destination]['Facebook'] }}</td>
                                        <td>{{ $finalSourceCounts[$destination->destination]['Instagram'] }}</td>
                                        <td>{{ $finalSourceCounts[$destination->destination]['Get a Call Back form'] }}</td>
                                         <td>{{ $finalSourceCounts[$destination->destination]['Enquire now form'] }}</td>
                                        <td>{{ $finalSourceCounts[$destination->destination]['Other'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                 <tfoot>
                                    <tr>
                                      <td colspan="2"><b>GrandTotal</b></td>
                                      <td><b>{{ $totalCounts['Total'] }}</b></td>
                                      <td><b>{{ $totalCounts['Google'] }}</b></td>
                                      <td><b>{{ $totalCounts['Facebook'] }}</b></td>
                                      <td><b>{{ $totalCounts['Instagram'] }}</b></td>
                                      <td><b>{{ $totalCounts['Get a Call Back form'] }}</b></td>
                                       <td><b>{{ $totalCounts['Enquire now form'] }}</b></td>
                                      <td><b>{{ $totalCounts['Other'] }}</b></td>
                                    </tr>
                                  </tfoot>
                            </table>
                            <div class="box-footer clearfix text-right">
                                {{ $destinationTotals->withQueryString()->links() }}
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
       
    </section>
</div>
<style type="text/css">
    .box-header.with-border {
        border-bottom: none;
    }
</style>
@endsection

@section('footerSection')
@endsection
