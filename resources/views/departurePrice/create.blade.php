@extends('layouts.apps')
@section('headSection')
@section('title', 'Departures | Departure Price Update')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('css/customCSS/itinerary.css')}}">

<style>
  .row {
    margin-right: 0px; 
    margin-left: 0px; 
  }
</style>
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Departure Price Update</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('departures')}}">Departures</a></li>
        <li class="active">Departure Price Update</li>
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
        <div class="dataIndex" id="dataIndex">
           <div class="box-body bg-light">
             <table id="departureListData" class="table table-bordered">
                <tbody>
                  <tr>
                    <th>#</th>
                    <th>Shairing</th>
                    <th>Transport</th>
                    <th>Meal</th>
                    <th>Hotel</th>
                    <th>Minimum Pax</th>
                    <th>Peice</th>
                    <th style="width: 8%">Dook Price(INR)</th>
                    <th style="width: 8%">Dook Price(USD)</th>
                    <th style="width: 8%">Action</th>
                  </tr>
                  @if(count($departure_pricing)>0)
                  @foreach($departure_pricing as $key=>$row)
                  <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$row->sharing}}</td>
                    <td>{{$row->transport_type}}</td>
                    <td>{{$row->meal_type}}</td>
                    <td>{{$row->hotel_type}}</td>
                    <td>{{$row->group_size}}</td>
                    <td>{{$row->currency_symbol}} {{$row->price}}</td>
                    <form role="form" id="ItineraryForm{{$row->id}}">
                      <input type="hidden" name="id" value="{{$row->id}}">
                      <input type="hidden" name="departure_id" value="{{$row->departure_id}}">
                      <input type="hidden" name="d_date_id" value="{{$row->d_date_id}}">
                      <td><input type="text" class="form-control" name="price_inr" id="price_inr{{$row->id}}" value="{{$row->dook_price_inr}}"></td>
                      <td><input type="text" class="form-control" name="price_usd" id="price_usd{{$row->id}}" value="{{$row->dook_price_usd}}"></td>
                      <td>
                        <button class="btn btn-primary active" type="button" id="store_form{{$row->id}}"> <span class="crop_text"><i class="fa fa-save"></i> Save</span>
                        </span>
                        </button>
                      </td>
                    </form>
                  </tr>
                  @endforeach
                  @endif
                </tbody>
             </table>
          </div>
        </div>
    </section>
  </div>
  @endsection
  @section('footerSection')
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script type="text/javascript">
  @foreach($departure_pricing as $row)
    $(document).ready(function () {
        $('#store_form{{$row->id}}').click(function (e) {
            e.preventDefault();
            $('#store_form').html('Please Wait');
          $('#gif').show();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'POST',
                url: "{{route('departure_pricing_update')}}",
                data: $('#ItineraryForm{{$row->id}}').serialize(),
                success: function (data) {
                    var id = data.id;
              
                    $('#price_inr'+id).val(data.dook_price_inr);
                    $('#price_usd'+id).val(data.dook_price_usd);
                    $('#store_form{{$row->id}}').html('success');
                    //location.reload();
                },
                errors: function () {

                }

            });
        });
        $("#price_inr{{$row->id}}").on("keypress keyup blur", function (event) {
            $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
        $("#price_usd{{$row->id}}").on("keypress keyup blur", function (event) {
            $(this).val($(this).val().replace(/[^\d].+/, ""));
            if ((event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
    });
  
  @endforeach  
  </script>