@php
use Carbon\Carbon;
@endphp
@extends('layouts.apps')
@section('headSection')
@section('title', 'Daily Reports')
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
        <a class="btn btn-primary" href="{{route('enquiries')}}" title="click now.!"> Click to see all Dook
            Enquiries <sup style="color:#ffeb00">{{$total}}</sup></a>
        <ol class="breadcrumb">
            <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a>Daily Reports</a></li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border" style="display: flex;">
                        <form action="{{route('daily_reports')}}" method="get"
                            style="display: inline-flex;margin-bottom: 0;">
                            <div class="input-group date" style="margin-right: 10px;">
                                <input type="text" class="form-control pull-right" name="start_date"
                                    id="start_date_daily_reports" value="{{ $startDate }}" autocomplete="off"
                                    placeholder="Ex- yy-mm-dd">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar start-calendar"></i>
                                </div>
                            </div>
                            <div class="input-group date">
                                <input type="text" class="form-control pull-right" name="end_date"
                                    id="end_date_daily_reports" value="{{ $endDate }}" autocomplete="off"
                                    placeholder="Ex- yy-mm-dd">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar end-calendar"></i>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info" style="margin-left: 10px;">Search</button>
                        </form>
                    </div>
                    <div class="dataIndex" id="dataIndex">
                        <div class="box-body" style="overflow-x: scroll;">
                            <table id="destinationListData" class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>#</th>
                                        <th>
                                            @if ($sort_order == 'asc')
                                            <a
                                                href="{{ route('daily_reports', ['start_date' => request('start_date'), 'end_date' => request('end_date'),'sort_order' => 'desc']) }}">Date
                                                <i class="fa fa-arrow-down"></i>
                                            </a>
                                            @else
                                            <a
                                                href="{{ route('daily_reports', ['start_date' => request('start_date'), 'end_date' => request('end_date'),'sort_order' => 'asc']) }}">Date
                                                <i class="fa fa-arrow-up"></i>
                                            </a>
                                            @endif
                                        </th>
                                        <th>Count</th>
                                    </tr>
                                    @foreach( $reports as $key => $enquiry )
                                    <tr>
                                        <td>{{ ($reports->currentpage()-1) * $reports->perpage() + $key + 1 }}</td>
                                        <td>{{ Carbon::parse($enquiry->date)->format('jS - M - Y') }}</td>
                                        <td>{{ $enquiry->count }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="box-footer clearfix text-right">
                                {{ $reports->withQueryString()->links() }}
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
        border-bottom: none
    }
</style>
@endsection

@section('footerSection')
<script>
    $( document ).ready(function() {
      $('#start_date_daily_reports').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy-mm-dd',
      });
      $('#end_date_daily_reports').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy-mm-dd',
      });
      
    });
</script>

@endsection