@php
use Carbon\Carbon;
@endphp
@extends('layouts.apps')
@section('headSection')
@section('title', 'Country Counts')
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
        <a class="btn btn-primary" href="#" title="click now.!"> Total Lead <sup
                style="color:#ffeb00">{{$total_lead}}</sup></a>
        <a class="btn btn-danger" href="#" title="click now.!"> Missed Lead <sup
                style="color:#ffeb00">{{$missed_lead}}</sup></a>
        <ol class="breadcrumb">
            <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a>Lead Reports</a></li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border" style="display: flex;">
                        <form action="{{route('lead.count')}}" method="get"
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
                        <div style="margin-left: 10px;"><span class="text-danger">Note : Select start date from
                                30-10-2023</span>
                        </div>
                    </div>
                    {{--------------}}
                    <div style="margin-left: 10px;">
                        <h3>Missed Lead</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table id="lead_report" class="table table-bordered">
                            <thead>
                                @if($enquiries->count() > 0)
                                <tr>
                                    <th style="width:10%"></th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>TravelDate</th>
                                    <th>Traveler</th>
                                    <th>Enquiry Date</th>
                                    <th>Source</th>
                                    <th>Url</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach( $enquiries as $key => $enquiry )
                                <tr>
                                    <td style="width: 10px;!important">{{ $loop->index + 1 }}</td>
                                    <td>{{$enquiry->name}}</td>
                                    <td><a href="mailto:{{$enquiry->email}}">{{$enquiry->email}}</a></td>
                                    <td>{{$enquiry->mob_no}}</td>
                                    <td>{{date('d-M-Y', strtotime($enquiry->travel_date))}}</td>
                                    <td>{{$enquiry->no_of_traveler}}</td>
                                    <td>{{date('d-M-Y', strtotime($enquiry->created_at))}}</td>
                                    <td>@if($enquiry->source == "") Dook @else {{$enquiry->source}} @endif</td>
                                    <td><a href="{{$enquiry->url}}" target="_blank">{{$enquiry->url}}</a></td>
                                </tr>
                                @endforeach
                                @else
                                <h3 style="text-align:center;">No missed lead found for these date</h3>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    {{------------ --}}
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
        $('#lead_report').DataTable({
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