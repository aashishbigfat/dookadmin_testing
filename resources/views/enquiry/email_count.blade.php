@php
use Carbon\Carbon;
@endphp
@extends('layouts.apps')
@section('headSection')
@section('title', 'Email Counts')
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
        <a class="btn btn-primary" href="#" title="click now.!"> Total Enquiry <sup
                style="color:#ffeb00">{{$total}}</sup></a>
        <ol class="breadcrumb">
            <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a>Email Count Reports</a></li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border" style="display: flex;">
                        <form action="{{route('eamil.count')}}" method="get"
                            style="display: inline-flex;margin-bottom: 0;">
                            <div class="input-group date" style="margin-right: 10px;">
                                <input type="text" class="form-control pull-right" name="start_date"
                                    id="start_date_email_reports" value="{{ $startDate }}" autocomplete="off"
                                    placeholder="From">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar start-calendar"></i>
                                </div>
                            </div>
                            <div class="input-group date">
                                <input type="text" class="form-control pull-right" name="end_date"
                                    id="end_date_email_reports" value="{{ $endDate }}" autocomplete="off"
                                    placeholder="To">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar end-calendar"></i>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info" style="margin-left: 10px;">Search</button>
                        </form>
                    </div>
                    <div class="dataIndex" id="dataIndex">
                        <div class="box-body" style="overflow-x: scroll;">
                            <table id="email_count" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>
                                            <a
                                                href="{{ route('daily_reports', ['start_date' => request('start_date'), 'end_date' => request('end_date'),'sort_order' => 'asc']) }}">Email
                                            </a>
                                        </th>
                                        <th>Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach( $reports as $key => $enquiry )
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $enquiry->email }}</td>
                                        <td>{{ $enquiry->count }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
    $(document).ready(function() {
        $('#email_count').DataTable({
            "paging": true,
            "ordering": true,
            "searching": true,
        });
    });
</script>

<script>
    $( document ).ready(function() {
      $('#start_date_email_reports').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy-mm-dd',
      });
      $('#end_date_email_reports').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy-mm-dd',
      });
      
    });
</script>

@endsection