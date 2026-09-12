@extends('layouts.apps')
@section('headSection')
@section('title', 'Top Destinations')
<link rel="stylesheet" href="{{asset('css/customCSS/top_destination.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Top Destinations</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Top Destinations</li>
      </ol>
    </section>
    <hr class="TopHeaderBorder" style="border-bottom: 2px solid #777">
    <section class="content">

      <div class="row">
        <form role="form" id="topDestination">
          @csrf
            <div class="box-body">
              <div class="col-md-4 col-lg-4 col-xl-4 col-sm-12 col-xs-12">
                  <div class="form-group">
                  <label>Top Region For</label> <span class="validationError" id="region_for_error"></span>
                    <select class="form-control region_for" name="region_for" id="destination_for">
                      <option value="home">Home</option>
                      <option value="departures">Departures</option>
                      <option value="experiences">Experiences</option>
                      <option value="destinations">Destinations</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6 col-lg-6" style="margin-bottom: 29px;">
                  <label>Design Demo</label>
                  <a class="dropdown-item edit viewImage" data-toggle="modal">
                    <img src="{{asset('images/region_model.png')}}" width="80" height="80">
                  </a>
              </div>
              <!-- <div>Image Size :<span></span></div> -->
              <div class="col-md-4 col-lg-4 col-xl-4 col-sm-12 col-xs-12">
                  <div class="form-group">
                  <label>Region</label> <span class="validationError" id="top_region_error"></span>
                    <select class="form-control top_region" name="top_region" id="top_region">
                      <option value="">Select Region</option>
                      @foreach($regions as $region)
                        <option value="{{$region->id}}" data-name="{{$region->region_name}}">{{$region->region_name}}</option>
                      @endforeach
                    </select>
                  </div>
                  <input type="hidden" name="top_region_name" id="top_region_name">
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 col-xl-6 sss">
                  <div class="form-group edit_dest">
                    <label>Region Label</label> <span class="validationError" id="label_name_error"></span><br>
                    <input class="form-control" type="text" name="label_name" id="label_name">
                  </div>
                </div>
                
                <div class="col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12">
                  <label>Countries</label> <span class="validationError" id="country_error"></span>
                  <div class="row">
                  <div class="form-group col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12" id="countriesHtml">
                    
                  </div>
                  <input type="hidden" id="country_validation_check">
                  </div>
                </div>
                <div class="col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12">
                  <label>Experiences</label> <span class="validationError" id="exp_error"></span>
                  <div class="row">
                  <div class="form-group col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12" id="experiences">
                    @foreach($experiences as $exp)
                    <div class="checkbox checked act col-md-2">
                    <label>
                      <input type="checkbox" name="experiencesId[]" datas="{{$exp->experience_name}}" id="exp_{{$exp->id}}" class="experiencesId" value="{{$exp->id}}">{{$exp->experience_name}}
                      <input type="hidden" name="experiencesName[]" class="exp_{{$exp->id}}" >
                    </label>
                    </div>
                    @endforeach
                    <input type="hidden" id="validation_check">
                  </div>
                </div>
              </div>
            </div>
              <div class="box-body">
              <div class="col-md-12 col-lg-12 col-sm-12">
                <h3>Image</h3>
                <hr style="border-bottom: 2px solid #777">
              </div> 
              <div class="col-md-3 col-lg-3 col-sm-12">
                <div class="form-group">
                 <label style="margin-bottom: 15px">Top Region Image</label> <span class="validationError" id="image_error"></span> 
                 <div class="input-group">
                    <input type="file" name="region_image" id="uploadFileBanner" accept="image/jpeg, image/jpg, image/png, image/gif," onchange="readURL(this);">
                  <button type="button" class="btn btn-primary">Choose Image</button>
                </div>
                </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                  <img id="blah" onclick="triggerImage()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="80" height="80"/>
              </div>
              <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left">
                <button class="btn btn-primary active" type="button" id="store_form"><i class="fa fa-save"></i> Save</button>
                <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 3%; visibility: hidden;">
                <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
              </div> 
            </div>

        </form>
      </div>
      
    </section>
  </div>
  <div class="modal fade" id="popupImage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document" style="width: 54%">
      <div class="modal-content">
        <div class="modal-body">
          <button type="button" class="close text-right imgview" data-dismiss="modal" aria-label="Close">
            <i class="fa fa-close"></i></button>
          <div class="itinerary-setup m-t-20" style="margin-left: -15px;margin-bottom: -15px;margin-top: -15px;">
            <img src="{{asset('images/region_model.png')}}">
          </div>
        </div>
      </div>
    </div>
  </div>
  <style type="text/css">
    table.loading>tbody{position:relative}table.loading>tbody:after{position:absolute;top:0;left:0;right:0;bottom:0;background-color:rgba(0,0,0,.1);background-image:url("{{ asset('images/loaders.gif') }}");background-position:center;background-repeat:no-repeat;background-size:65px 65px;content:""}.box-header.with-border{border-bottom:none}a.dropdown-item.edit {padding-left: 10px !important;display: inline-block;padding: 5px;}.btn-group-sm>.btn, .btn-sm {padding: 1px 3px !important;}.inlineFlax{display: inline-flex;}button.close.text-right.imgview {margin-left: 107%;}
  </style>
  @endsection
  @section('footerSection')
  <script>
    $("#top_region").select2();
    $('.viewImage').on('click', function() {
      $('#popupImage').modal('show');
    });
  </script>
  <script type="text/javascript">
    $( document ).ready(function() {
      $('.experiencesId').change(function(){
        var isChecked = $(this).is(':checked');
        var id = $(this).attr('id');
        var data_name = $(this).attr('datas');
        if(isChecked){
          $("."+id).val(data_name);
          $("#validation_check").val(id);
        }
        else{
          $("."+id).val('');
        }
      });
    });
  </script>
  <script>
    $(document).on('click', '.countryId', function(){
        var isChecked = $(this).is(':checked');
        var id = $(this).attr('id');
        var data_name = $(this).attr('datas');
        if(isChecked){
          $("."+id).val(data_name);
          $("#country_validation_check").val(id);
        }
        else{
          $("."+id).val('');
        }
      });
  </script>
 <script>
  $('#top_region').change(function(){
      var region_id = $("#top_region").val();
     var region_name = $(this).find(':selected').data('name')
      if(region_id){
        if(region_id){
            $.ajax({
               type:"GET",
               url:"{{url('/get-region_countries-ajax')}}?region_id="+region_id,
               success:function(res){
                //console.log(res);
                if(res && res.length > 0){
                  $("#countriesHtml").html('');
                    $.each(res,function(key,value){         
                        $("#countriesHtml").append('<div class="checkbox checked act col-md-3"><label><input type="checkbox" name="countryId[]" datas="'+value.country_name+'" id="country_'+value.id+'" class="countryId" value="'+value.id+'"><input type="hidden" name="countryName[]" class="country_'+value.id+'" >'+value.country_name+'</label><div>'); 
                        $('#top_region_name').val(region_name);  
                        $('#label_name').val(region_name);
                    });
                }else{
                   $("#countriesHtml").empty();
                }
               }
            });
        }else{
            $("#countriesHtml").empty();
        }      
      }else{
        $("#countriesHtml").html('');
      }
    });
  </script>
   <script>
    $('#destination').change(function(){
      var dest_id = $("#destination").val();
      //console.log(dest_id)
      if(dest_id){
          $.ajax({
             type:"GET",
             url:"{{url('/get-top-dest-name')}}?dest_id="+dest_id,
             success:function(res){
              if(res && res.length > 0){
                  $.each(res,function(key,value){         
                      $("#destination_name").val(value.dest_name);  
                      $("#view_label").val(value.dest_name);
                  });
              }
             }
          });
      }  
    });
  </script>
  <script type="text/javascript">
    $(document).ready(function () {

            $('#store_form').click(function (e) {
                e.preventDefault();
                $('#gif').show();
                var dest = $('#top_region_name').val();
                //alert(dest);
                if (dest == "") {
                    $("span#top_region_error").html('This field is required!');
                    $("select#top_region").focus();
                    return false;
                }
                var label_name = $('#label_name').val();
                if (label_name == "") {
                    $("span#top_region_error").html();
                    $("span#label_name_error").html('This field is required!');
                    $("input#label_name").focus();
                    return false;
                }
                var validation_ck = $('#country_validation_check').val();
                if (validation_ck == "") {
                    $("span#top_region_error").html();
                    $("span#label_name_error").html();
                    $("span#country_error").html('Please select atleast 1 Country!');
                    $("input#country_validation_check").focus();
                    return false;
                }
                var validation_ck = $('#validation_check').val();
                if (validation_ck == "") {
                    $("span#top_region_error").html();
                    $("span#label_name_error").html();
                    $("span#country_error").html();
                    $("span#exp_error").html('Please select atleast 1 Experience!');
                    $("input#validation_check").focus();
                    return false;
                }
                $('#gif').css('visibility', 'visible');
                var formDatas = new FormData(document.getElementById('topDestination'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{ route('top_destinations_region_store') }}",
                    data: formDatas,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                      console.log(data);
                        $('#gif').hide();
                        $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                        //window.location = data.url;
                        location.reload();
                    },
                    errors: function () {

                    }

                });
            });
        });
  </script>
  <script>
      //Ajax Pagination
    $(window).on('hashchange', function() {
        if (window.location.hash) {
            var page = window.location.hash.replace('#', '');
            if (page == Number.NaN || page <= 0) {
                return false;
            }else{
                getData(page);
            }
        }
    });
    
    $(document).ready(function()
    {
        $(document).on('click', '.pagination a',function(event)
        {
          $('#topDestData').addClass('loading');
            event.preventDefault();
  
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
  
            var myurl = $(this).attr('href');
            var page=$(this).attr('href').split('page=')[1];
  
            getData(page);
        });
  
    });
  
    function getData(page){
        $.ajax(
        {
            url: '?page=' + page,
            type: "get",
            datatype: "html"
        }).done(function(data){
          $('#topDestData').removeClass('loading');
            $("#TodDestListing").empty().html(data);
           // location.hash = page;
        }).fail(function(jqXHR, ajaxOptions, thrownError){
              alert('No response from server');
        });
    }
    function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#blah')
                            .attr('src', e.target.result);
                    };

                    reader.readAsDataURL(input.files[0]);
                  }
                }


     function triggerImage(){
              $('#uploadFileBanner').trigger('click');
     }  
    </script>
  @endsection