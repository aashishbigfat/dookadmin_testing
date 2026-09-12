@extends('layouts.apps')
@section('headSection')
@section('title', 'Dook Enquiries')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
@endsection

@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="buttonInline">
            <span class="btn btn-success">Dook Total Enquiries <sup style="color:#ffeb00">{{$total}}</sup></span>

            <a class="btn btn-primary" href="{{route('job_enquiries')}}" target="_blank" title="click now.!"> Click to
                see all Applied Job</a>

            <span class="btn btn-success">Total Lead Sync <sup style="color:#ffeb00">{{$totalSyncData}}</sup></span>
            <form id="leadSync" method="POST" style="margin:0;display: none;">
                @csrf
                <button class="btn btn-warning" type="button" id="SyncLeads" title="click now.!"> Sync Lead with
                    TFC</button>
                <span class="text-success" id="messages"></span>
            </form>
            <form id="leadSyncRefId" method="POST" style="margin:0;">
                @csrf
                <button class="btn btn-danger" type="button" id="SyncLeadsRefId" title="click now.!"> Sync Lead with
                    RefID</button>
                <span class="text-success" id="messagess"></span>
            </form>
            <a class="btn btn-primary" href="{{route('job_enquiries')}}" target="_blank" title="click now.!"> Dook
                Jobs</a>
            {{-- <a class="btn btn-primary" href="{{route('wat_job_enquiries')}}" target="_blank" title="click now.!">
                Wat
                Jobs</a> --}}
            {{-- <a class="btn btn-primary" href="{{route('filter.enquery')}}" target="_blank" title="click now.!">
                Filter</a> --}}
            <a class="btn btn-warning" href="{{route('daily_reports')}}" target="_blank" title="click now.!"> Daily
                Reports
                <sup style="color:#ffeb00">{{$today_enquiry}}</sup></a>
        </div>

        <ol class="breadcrumb">
            <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a>Dook Enquiries</a></li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border" style="display: flex;">

                        <form action="{{route('filter.enquery')}}" method="get"
                            style="display: inline-flex;margin-bottom: 0;">
                            {{-- <div class="col-md-4 edit_dest">
                                <select class="form-control tfcLeadF" name="tfcLeadF" id="tfcLeadF">
                                    <option value="" <?php if($tfcLeadF=="no" ) { echo "selected" ; } ?>>Select..
                                    </option>
                                    <option value="1" @if($tfcLeadF==1 && $tfcLeadF !='no' ) selected="" @endif>Yes
                                    </option>
                                    <option value="in" @if($tfcLeadF==0 && $tfcLeadF !='no' ) selected="" @endif>No
                                    </option>
                                </select>
                            </div> --}}
                            <div class="input-group date">
                                <input type="text" class="form-control pull-right" name="start_date" id="start_date"
                                    value="{{$date}}" autocomplete="off" placeholder="Ex- yy-mm-dd">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar start-calendar"></i>
                                </div>
                            </div>
                            <div class="input-group date">
                                <input type="text" class="form-control pull-right" name="end_date" id="end_date"
                                    value="{{$end_date}}" autocomplete="off" placeholder="Ex- yy-mm-dd">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar end-calendar"></i>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-info" style="margin-left: 10px;">Search</button>
                            @if($totals == 0)

                            @else
                            <span class="btn btn-success"
                                style="margin-left: 100px;background-color: #637174;border-color: #637174;">Search
                                Results <sup style="color:#ffeb00">{{$totals}}</sup></span>
                            @endif
                        </form>
                    </div>
                    <div class="dataIndex" id="dataIndex">
                        @include('enquiry/index_data')
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
<style type="text/css">
    .ui-datepicker-buttonpane.ui-widget-content {
        display: none;
    }
</style>
@section('footerSection')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
    $(document).ready(function () {
      $('#SyncLeads').click(function (e) {
          e.preventDefault();
          $('#SyncLeads').html('Please wait...')
          $('#SyncLeads').prop('disabled', true);
          var formDatas = new FormData(document.getElementById('leadSync'));
          $.ajax({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              method: 'POST',
              url: "{{ route('synce_lead_tfc') }}",
              data: formDatas,
              contentType: false,
              processData: false,
              success: function (data) {
                 if(data){
                  $('#messages').html("<span class='sussecmsgWrong'>Data Sync Successfully</span>");
                  setTimeout(function () {
                    window.location.reload();
                  }, 2000);
                  
                 }else{
                  $('#messages').html("<span class='sussecmsg'>0 Data Synced!</span>");
                  setTimeout(function () {
                    window.location.reload();
                  }, 2000);
                }
              },
              errors: function () {
                $('#messages').html("<span class='sussecmsg'>Something went wrong!</span>");
              }

          });
      });
    });

    $(document).ready(function () {
      $('#SyncLeadsRefId').click(function (e) {
          e.preventDefault();
          $('#SyncLeadsRefId').html('Please wait...')
          $('#SyncLeadsRefId').prop('disabled', true);
          var formDatas = new FormData(document.getElementById('leadSyncRefId'));
          $.ajax({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              method: 'POST',
              url: "{{ route('synce_lead_tfc_refid') }}",
              data: formDatas,
              contentType: false,
              processData: false,
              success: function (data) {
                 if(data){
                  $('#messagess').html("<span class='sussecmsgWrong'>Data Sync Successfully</span>");
                  setTimeout(function () {
                    window.location.reload();
                  }, 2000);
                  
                 }else{
                  $('#messagess').html("<span class='sussecmsg'>0 Data Synced!</span>");
                  setTimeout(function () {
                    window.location.reload();
                  }, 2000);
                }
              },
              errors: function () {
                $('#messagess').html("<span class='sussecmsg'>Something went wrong!</span>");
              }

          });
      });
    });
</script>
<script type="text/javascript">
    $(".deleteEnquiry").click(function () {
      if (confirm("Are you sure you want to delete this Enquiry?"))
      var id = $(this).data("id");
      var token = $("meta[name='csrf-token']").attr("content");
      if(id){
        $.ajax(
        {
          url: '/enquiry_delete/' + id,
          type: 'POST',
          data: {
              "id": id,
              "_token": token,
          },
          success: function (data) {
            window.location.reload();
          }
        });
      }
    });

    $( document ).ready(function() {
      $('#start_date').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy-mm-dd',
        // onSelect: function (date) {
        //     console.log(date);
        //     // $('#end_date').datepicker({
        //     //  minDate:new Date(date)
        //     // });

        //     $('#end_date').datepicker({
        //     changeMonth: true,
        //     changeYear: true,
        //     showButtonPanel: true,
        //     dateFormat: 'yy-mm-dd',
        //     minDate:new Date(date),
        //   });
        // }
      });
      $('#end_date').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy-mm-dd',
      });
      
    });
    $('.start-calendar').click(function () {
      $("#start_date").focus();
    });
    $('.end-calendar').click(function () {
      $("#end_date").focus();
      //var sdate = $("#start_date").val();
      // if(sdate){

      // }else{
      //   alert('Please select start date first!');
      // }
    });

     // $('#end_date').click(function () {
     //    var sdate = $("#start_date").val();
     //    if(sdate){

     //    }else{
     //      alert('Please select start date first!');
     //    }
     // })
</script>
<style type="text/css">
    .buttonInline {
        display: flex;
        align-items: center;
    }

    .buttonInline .btn {
        margin-right: 10px;
    }

    #leadSync {
        position: relative;
    }

    #leadSync #messages {
        position: absolute;
        top: -10%;
        width: 100%;
    }
</style>
@endsection