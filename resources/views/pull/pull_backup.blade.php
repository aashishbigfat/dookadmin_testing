@extends('layouts.apps')
@section('headSection')
@section('title', 'Pull Departures')
@endsection
@section('main-content')
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="box-body">
        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12" style="margin-top: -25px;">
          <h3>Pull Departures</h3>
        </div>
        
        <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 mt-4 marginTop">
          <p class="btn btn-default">Total Approved Departures <sup class="label label-success" style="top: -15px;">{{$data->total}}</sup></p>
          <p class="btn btn-default">Pulled Departures<sup class="label label-success" style="top: -15px;">{{$data->pull_status}}</sup></p>
          <p class="btn btn-default">Remaining Departure to Pull<sup class="label label-success" style="top: -15px;">{{$data->remaining}}</sup></p>
          <p class="btn btn-default">Changes in Departures<sup class="label label-success" style="top: -15px;">{{$data->change_status}}</sup></p>
        </div>
      <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 mt-4 marginTop">
        <form role="form" id="DeparturPull" class="mt-4">
          @csrf
          {{-- <div class="col-md-4 col-lg-4 col-sm-6 col-xs-12">
          <div class="form-group">
          

          <label>Select Country</label> <span class="validationError" id="pull_error"></span>
            <select class="form-control pull" name="pull" id="pull">
              <option value="">Select Country</option>
              @foreach($countries as $country)
                <option value="{{$country->country_name}}">{{$country->country_name}}</option>
              @endforeach
            </select> 
          </div>
          </div> --}}
        
          <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 marginTops">
            <input type="hidden" name="DCPull" id="DCPull" value="DCPull">

            <button class="btn btn-primary active" type="button" id="pull_form"><i class="fa fa-save"></i> Pull Departures</button>
            <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 8%; visibility: hidden;">
            <span class="text-success" id="messages" style="margin-left: 10px"></span>
          </div> 
          <div class="col-md-6 col-lg-6 col-sm-6 col-xs-6 marginTops text-right">
            <input type="hidden" name="Sync" id="Sync" value="Sync">

            <button class="btn btn-primary active" type="button" id="sync_pull_form"><i class="fa fa-refresh"></i> Sync Departures</button>
            <img src="{{ asset('images/loader.gif') }}" id="gif1" style="width: 8%; visibility: hidden;">
            <span class="text-success" id="messages1" style="margin-left: 10px"></span>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>
<style type="text/css">
  .marginTop {top: 50px;}.marginTops {top: 50px;}
</style>
@endsection
@section('footerSection')
<script type="text/javascript">
    $(document).ready(function () {
      $('#pull_form').click(function (e) {
        e.preventDefault();
        $('#gif').show();

        $('#gif').css('visibility', 'visible');
        $('#pull_form').html('Please wait...')
        $('#pull_form').prop('disabled', true);
        $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: "{{ route('pull_store') }}",
          data: $('#DeparturPull').serialize(),
          success: function (data) {
            console.log(data.status);
              $('#gif').hide();
              $('#pull_form').html('Pull Departures')
              $('#pull_form').prop('disabled', false);
              $('#messages').html("<span class='sussecmsg'>"+data.status+"</span>");
              setTimeout(function () {
                window.location.reload();
              },3000);
          },
          errors: function () {
            $('#gif').hide();
            $('#messages').html("<span class='sussecmsg'>Something went wrong!!</span>");
          }
        });
      });
    });

    $(document).ready(function () {
      $('#sync_pull_form').click(function (e) {
        e.preventDefault();
        $('#gif1').show();

        $('#gif1').css('visibility', 'visible');
        $('#sync_pull_form').html('Please wait while be syncing...')
        $('#sync_pull_form').prop('disabled', true);
        $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: "{{ route('sync_store') }}",
          data: $('#DeparturPull').serialize(),
          success: function (data) {
            console.log(data.status);
              $('#gif').hide();
              $('#sync_pull_form').html('Sync Departures')
              $('#sync_pull_form').prop('disabled', false);
              $('#messages1').html("<span class='sussecmsg'>"+data.status+"</span>");
              setTimeout(function () {
                window.location.reload();
              },3000);
          },
          errors: function () {
            $('#gif').hide();
            $('#messages1').html("<span class='sussecmsg'>Something went wrong in syncing!</span>");
          }
        });
      });
    });
</script>
@endsection