@extends('layouts.apps')
@section('headSection')
@section('title', 'Mega Menu Country')
<link rel="stylesheet" href="{{asset('css/customCSS/top_destination.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Mega Menu Country</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Mega Menu Country</li>
      </ol>
    </section>
    <hr style="border-bottom: 2px solid #777">
    <section class="content">

      <div class="row">
        <div class="col-md-3">
        </div>
        <div class="col-md-6">
          <form role="form" id="topDestination" style="margin-top: -18px">
            @csrf
              <div class="box-body">
                <div class="col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <span class="validationError" id="destination_error"></span>
                    <select class="form-control country" name="country[]" id="country" multiple="" autofocus>
                    </select>
                  </div>
                </div>
                <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-center" style="margin-top: 20px">
                  <button class="btn btn-primary active" type="button" id="store_form">
                    <span class="crop_text"><i class="fa fa-save"></i> Save</span>
                      <span class="crop_wait" style="display: none">                      
                       Please Wait <i class="fa fa-circle-o-notch fa-spin"></i>
                    </span>
                  </button>
                  <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
                </div> 
              </div>
          </form>
        </div>
        <div class="col-md-3">
        </div>
      </div>
      <div class="row DestinationCountryBox">
        <div class="col-md-3">
        </div>
        <div class="col-md-6">
          <div class="box-header with-border">
            <span style="font-size: 16px;font-weight: 600;">Mega Menu Countries</span>
            <span style="float: right;" class="btn btn-success">Total <span style="color:#ffeb00">{{$total}}</span></span>
          </div>
          <div class="TodDestListing" id="TodDestListing">
          <div class="box-body">
            <table id="departureListData" class="table table-bordered">
              <thead>
                <tr>
                  <th style="width: 3px;">S.No</th>
                  <th>Country</th>
                  <th style="width: 5%">Action</th>
                </tr>
              </thead>
                <tbody class="row_position">
                @if(count($countries)> 0 )
                  @foreach( $countries as $key => $value )
                    <tr id="{{ $value->id }}">
                      <td>{{ $loop->index +1 }}</td>
                      <td>{{$value->name}}</td>
                      <td>
                        <a class="popularDest" data-id="{{ $value->id }}" title="Delete Mega Country?" style="cursor: pointer;">
                          <i class="fa fa-trash" style="color: #9a191e;"></i>
                        </a>
                      </td>
                    </tr>
                  @endforeach
                @endif
              </tbody>
            </table>
          </div>

          </div>
        </div>
        <div class="col-md-3">
        </div>
      </div>
    </section>
  </div>
  <style type="text/css">
    .DestinationCountryBox .col-md-6{box-shadow: 3px 2px 4px 0 #a55a5a;background: #fff;}.box-footer{border-top: none;background-color: transparent;}
  </style>
  @endsection
  @section('footerSection')
 <script>
  $('.country').select2(
  {
    placeholder: 'Search Countries',
    ajax: {
      url: "/get-top-footer-countries-ajax",
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
          return {
              results: $.map(data, function (item) { 
                  return {
                      text: item.country_name,
                      id: item.id
                  }
              })
          };
      },
      cache: true
    }
  })
  </script>
  <script type="text/javascript">
    $(".popularDest").click(function () {
      if (confirm("Are you sure you want to delete this?"))
      var id = $(this).data("id");
      var token = $("meta[name='csrf-token']").attr("content");
      if(id){
      $.ajax(
        {
          url: '/megamenu-country/disable/' + id,
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
    </script>
  <script type="text/javascript">
    $(document).ready(function () {

      $('#store_form').click(function (e) {
          e.preventDefault();
          $(".crop_wait").show();
          $(".crop_text").hide();
          var dest = $('#country').val();
          //console.log(dest.length);
          if (dest.length == 0) {
            $(".crop_wait").hide();
            $(".crop_text").show();
            $("span#country_error").html('This field is required!');
            $("select#country").focus();
            return false;
          }
          var formDatas = new FormData(document.getElementById('topDestination'));
          $.ajax({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              method: 'POST',
              url: "{{ route('mega_country_store') }}",
              data: formDatas,
              contentType: false,
              processData: false,
              success: function (data) {
                console.log(data);
                  //$('#gif').hide();
                  $('#mesegese').html("<span class='sussecmsg'>Successfully Added!!</span>");
                  //window.location = data.url;
                  window.location.reload();
              },
              errors: function () {
                $(".crop_wait").hide();
                $(".crop_text").show();
              }
          });
      });
    });
  </script>
  <script type="text/javascript">
    $( ".row_position" ).sortable({
        delay: 150,
        stop: function() {
            var selectedData = new Array();
            $('.row_position>tr').each(function() {
                selectedData.push($(this).attr("id"));
            });
            updateOrder(selectedData);
        }
    });


    function updateOrder(data) {
      console.log(data);
        $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:"{{ route('positionReshiftingCountry') }}",
            type:'post',
            data:{position:data},
            success:function(){
                alert('Your changes successfully saved');
                window.location.reload();
            }
        })
    }
</script>
  @endsection