@extends('layouts.apps')
@section('headSection')
@section('title', 'Departure | Date Wise Departure')
<link rel="stylesheet" href="{{asset('css/customCSS/departure_pointofinterest.css')}}">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Date Wise Departures Edit</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('departure_dates',request()->route('id'))}}">Departure Dates</a></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="steps clearfix text-center">
              @include('layouts/itinerary_menu')
            </div>
          </div>
        </div>
        <form role="form" id="poiForm">
          @csrf
          <div class="box-body">

            <div class="col-md-12" style="margin-bottom: 30px;font-size: 18px;"><u><b>Departure Name: </b></u><span>{{$departure->title}} {{$departure->dep_dook_ref_id}}</span>&nbsp; &nbsp;<u><b>Departure ID: </b></u><span>{{$departure->dep_dook_ref_id}}</span> 
            </div>
           
            <div class="col-md-3 col-lg-3 col-xl-3">
              <div class="form-group">
                <label>Dep Date </label>
                <input type="text" class="form-control" value="{{date('d M, Y', strtotime($departureDate->date))}}" disabled>
                
              </div>
            </div>
            <div class="col-md-2 col-lg-2 col-xl-2">
              <div class="form-group">
                <label>DC Price </label>
                <input type="hidden" class="form-control" name="edit_id" id="edit_id" value="{{request()->route('date_id')}}">
                <input type="text" class="form-control" name="" id="dc_price" value="{{$departureDate->dc_currency}} {{$departureDate->dc_price}}" disabled>

                
              </div>
            </div>
            <div class="col-md-2 col-lg-2 col-xl-2">
              <div class="form-group">
                <label>Dook Price (INR)</label>
                <input type="hidden" class="form-control" name="price_inr_currency" value="₹">
                <input type="text" class="form-control" name="price_inr" id="price_inr" value="{{$departureDate->price}}" autocomplete="off">
                <span class="text-danger" id="price_error"></span>
              </div>
            </div>
            <div class="col-md-2 col-lg-2 col-xl-2">
              <div class="form-group">
                <label>Dook Price (USD)</label>
                <input type="hidden" class="form-control" name="price_usd_currency" value="$">
                <input type="text" class="form-control" name="price_usd" id="price_usd" value="{{$departureDate->price_usd}}" autocomplete="off">
                <span class="text-danger" id="price_usd_error"></span>
              </div>
            </div>

            @if(in_array(32, json_decode($columns)))
            <div class="col-md-3 col-lg-3 col-xl-3">
              <div class="form-group">
                <label>Room Sharing</label>
                <input type="text" class="form-control" name="room_sharing" id="room_sharing" value="{{$departureDate->sharing}}">
              </div>
            </div>
            @endif
            @if(in_array(33, json_decode($columns)))
            <div class="col-md-2 col-lg-2 col-xl-2">
              <div class="form-group">
                <label>Flight Class</label>
                <input type="text" class="form-control" name="flight_class" id="flight_class" value="{{$departureDate->flight_class}}">
              </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="">Age Bracket</label>
                    <input type="text" class="form-control" name="age_bracket" id="age_bracket" value="{{$departureDate->age_bracket}}">
                </div>
            </div>
            @endif
            @if(in_array(35, json_decode($columns)))
            <div class="col-md-2 col-lg-2 col-xl-2">
              <div class="form-group">
                <label>Hotel Category</label>
                <input type="text" class="form-control" name="hotel_type" id="hotel_type" value="{{$departureDate->hotel_type}}">
              </div>
            </div>
            @endif
            @if(in_array(36, json_decode($columns)))
            <div class="col-md-3 col-lg-3 col-xl-3">
              <div class="form-group">
                <label>Transport Type</label>
                <input type="text" class="form-control" name="transport_type" id="transport_type" value="{{$departureDate->transport_type}}">
              </div>
            </div>
            @endif
            <div class="col-md-3 col-lg-3 col-xl-3">
              <div class="form-group">
                <label>Airport Transfers</label>
                <input type="text" class="form-control" name="airport_transfers" id="airport_transfers" value="{{$departureDate->airport_transfers}}">
              </div>
            </div>
            @if(in_array(38, json_decode($columns)))
            <div class="col-md-3 col-lg-3 col-xl-3">
              <div class="form-group">
                <label>Meal Plan</label>
                <input type="text" class="form-control" name="meal_type" id="meal_type" value="{{$departureDate->meal_type}}">
              </div>
            </div>
            @endif
            <div class="col-md-12 col-lg-12" style="margin-top: 20px">
              <div class="form-group">
                <label>Description</label>
                <textarea class="form-control" name="edit_description" id="edit_description" style="height: 200px">{{$departureDate->description}}</textarea>
              </div>
            </div>
            <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-right">
              <button class="btn btn-primary active" type="button" id="store_form">
                <span class="crop_text"><i class="fa fa-edit"></i> Update</span>
                  <span class="crop_wait" style="display: none">                      
                   Please Wait <i class="fa fa-circle-o-notch fa-spin"></i>
                </span>
              </button>
              <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
            </div> 
          </div>
        </form>
      </div>
     
    </section>
  </div>

  @endsection
  @section('footerSection')
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
  <script type="text/javascript">
    $(document).ready(function () {
      $('#store_form').click(function (e) {
        e.preventDefault();
        $(".crop_wait").show();
        $(".crop_text").hide();
        $.ajax({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: "{{ route('departure_date_update',request()->route('date_id')) }}",
          data: $('#poiForm').serialize(),
          success: function (data) {
            console.log(data);
              $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
              //location.reload();
              window.location = data.url;
          },
          errors: function () {

          }
        });
      });
  });
</script>
<script type="text/javascript">
  $("#price_inr").keypress(function (e) {
    if (e.which != 8 && e.which != 0 && (e.which != 43)  && e.which != 107 && (e.which < 48 || e.which > 57)) {
      //display error message
      $("#price_error").html("Digits Only").
      show().fadeOut(3000);
      return false;
    }
  });

  $("#price_usd").keypress(function (e) {
    if (e.which != 8 && e.which != 0 && (e.which != 43)  && e.which != 107 && (e.which < 48 || e.which > 57)) {
      //display error message
      $("#price_usd_error").html("Digits Only").
      show().fadeOut(3000);
      return false;
    }
  });
  var default_inr = "<?php echo $inr; ?>";
    $('#price_inr').keyup(function () {
        var price_inr;
        price_inr = parseFloat($('#price_inr').val());
        if (price_inr) {
            var result = Math.round(price_inr / default_inr);
            if ($("#price_usd").val(result)) {
                $("#price_usd").val(result)
                //$("#price_usd").prop("readonly", true);
            }
        } else {
            $("#price_usd").val('')
            //$("#price_usd").prop("readonly", false);
        }
    });
    $('#price_usd').keyup(function () {
        var price_usd;
        price_usd = parseFloat($('#price_usd').val());
        if (price_usd) {
            var result = Math.round(price_usd * default_inr);
            if ($("#price_inr").val(result)) {
                $("#price_inr").val(result)
                //$("#price_inr").prop("readonly", true);
            }
        } else {
            $("#price_inr").val('')
            //$("#price_inr").prop("readonly", false);
        }
    });
</script>
<script>

  if (this.href = "{{ route('departure_date_edit',['id'=>request()->route('id'),'date_id'=>request()->route('date_id')])}}") {
     $(".depDates").addClass("active");
  }

</script>
@endsection