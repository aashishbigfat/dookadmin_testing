@extends('layouts.apps')
@section('headSection')
@section('title', "Landing Destination's Top Destinations")
<link rel="stylesheet" href="{{asset('css/customCSS/top_destination.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Landing Destination's Top Destinations</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Landing Destination's Top Destinations</li>
      </ol>
    </section>
    <hr class="TopHeaderBorder" style="border-bottom: 2px solid #777">
    <section class="content">

      <div class="row">
        
        <form role="form" id="topDestination">
          @csrf
            <div class="box-body">
              <div class="col-md-12 col-lg-12" style="margin-top: -29px;margin-bottom: 20px">
                <div style="float: left"><label>Top Destination Section Design Demo</label>
                  <a class="dropdown-item edit viewImage" data-toggle="modal">
                    <img src="{{asset('images/destination_model.png')}}" width="80" height="80">
                  </a>
                </div>
              </div>
              <div class="col-md-2 col-lg-2 col-xl-2 col-sm-12 col-xs-12">
                  <div class="form-group">
                  <label>Grid Number</label> <span class="validationError" id="destination_for_error"></span>
                    <select class="form-control destination_for" name="destination_for" id="destination_for">
                      <option value="">Select No..</option>
                      <option value="1" data-size="(W:500, H:500)">1</option>
                      <option value="2" data-size="(W:500, H:500)">2</option>
                      <option value="3" data-size="(W:500, H:500)">3</option>
                      <option value="4" data-size="(W:640, H:302)">4</option>
                      <option value="5" data-size="(W:500, H:500)">5</option>
                      <option value="6" data-size="(W:958, H:894)">6</option>
                      <option value="7" data-size="(W:1000, H:366)">7</option>
                      <option value="8" data-size="(W:500, H:500)">8</option>
                      <option value="9" data-size="(W:1000, H:366)">9</option>
                      <option value="10" data-size="(W:1000, H:366)">10</option>
                      <option value="11" data-size="(W:1000, H:366)">11</option>
                      <option value="12" data-size="(W:500, H:500)">12</option>
                      <option value="13" data-size="(W:958, H:894)">13</option>
                      <option value="14" data-size="(W:500, H:500)">14</option>
                      <option value="15" data-size="(W:500, H:500)">15</option>
                      <option value="16" data-size="(W:500, H:500)">16</option>
                      <option value="17" data-size="(W:640, H:302)">17</option>
                      <option value="18" data-size="(W:500, H:500)">18</option>
                      <option value="19" data-size="(W:500, H:500)">19</option>
                      <option value="20" data-size="(W:500, H:500)">20</option>
                      <option value="21" data-size="(W:500, H:500)">21</option>
                      <option value="22" data-size="(W:640, H:302)">22</option>
                      <option value="23" data-size="(W:500, H:500)">23</option>
                      <option value="24" data-size="(W:958, H:894)">24</option>
                    </select>
                  </div>
                </div>
              
              <div class="col-md-4 col-lg-4 col-xl-4 col-sm-12 col-xs-12">
                  <div class="form-group">
                  <label>Destination</label> <span class="validationError" id="destination_error"></span>
                    <select class="form-control destination" name="destination" id="destination">
                    </select>
                  </div>
                  <!-- <input type="hidden" name="destination_name" id="destination_name">
                  <input type="hidden" name="slug_url" id="slug_url"> -->
                </div>
                <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12 col-xl-4 sss">
                  <div class="form-group edit_dest">
                    <label>Label</label> <span class="validationError" id="view_label_error"></span><br>
                    <input class="form-control" type="text" name="view_label" id="view_label">
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
                <h3>Image <span id="imgSize" style="color: #d71921"></span></h3>
                <hr style="border-bottom: 2px solid #777">
              </div> 
              <div class="col-md-3 col-lg-3 col-sm-12">
                <div class="form-group">
                 <label style="margin-bottom: 15px">Destination Image</label> <span class="validationError" id="image_error"></span> 
                 <div class="input-group">
                    <input type="file" name="dest_image" id="uploadFileBanner" accept="image/jpeg, image/jpg, image/png, image/gif," onchange="readURL(this);">
                  <button type="button" class="btn btn-primary">Choose Image</button>
                </div>
                </div>
              </div>
              <div class="col-md-3 col-lg-3 col-sm-12">
                  <img id="blah" onclick="triggerImage()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="80" height="80"/>
              </div>
              <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-right" style="margin-top: 20px">
                <button class="btn btn-primary active" type="button" id="store_form"><i class="fa fa-save"></i> Save</button>
                <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 3%; visibility: hidden;">
                <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
              </div> 
            </div>

        </form>
        <div class="col-md-12 col-lg-12 col-sm-12">
          <h4>Total Number of Grid = 24 are Created so Create Form Disable</h4>
        </div>
      </div>
      <div class="box">
        <div class="box-header with-border">
          <span style="font-size: 16px;">Top Destinations List</span>
          <span style="float: right;" class="btn btn-success">Total <span style="color:#ffeb00">{{$total}}</span></span>
        </div>
        <div class="TodDestListing" id="TodDestListing">
          @include('landingdestination/data_list')
        </div>
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
            <img src="{{asset('images/destination_model.png')}}">
          </div>
        </div>
      </div>
    </div>
  </div>
  <style type="text/css">
    table.loading>tbody{position:relative}table.loading>tbody:after{position:absolute;top:0;left:0;right:0;bottom:0;background-color:rgba(0,0,0,.1);background-image:url("{{ asset('images/loaders.gif') }}");background-position:center;background-repeat:no-repeat;background-size:65px 65px;content:""}
  </style>
  @endsection
  @section('footerSection')
  <script>
    $('#destination_for').select2();
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
    $('.destination').select2(
        {
            placeholder: 'Select Destination',
            ajax: {
                url: "/get-top-destination-ajax",
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.dest_name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        }).on('select2:opening', function(e) {
    $(this).data('select2').$dropdown.find(':input.select2-search__field').attr('placeholder', 'Search Destination..')})
  </script>
   <script>
    // $('#destination').change(function(){
    //   var dest_id = $("#destination").val();
    //   if(dest_id){
    //       $.ajax({
    //          type:"GET",
    //          url:"{{url('/get-top-dest-name')}}?dest_id="+dest_id,
    //          success:function(res){
    //           if(res && res.length > 0){
    //               $.each(res,function(key,value){         
    //                   $("#destination_name").val(value.dest_name);  
    //                   $("#view_label").val(value.dest_name);
    //               });
    //           }
    //          }
    //       });
    //   }  
    // });
  </script>
  <script>
    $('.destination_for').change(function(){
      var data_size = $(this).find(':selected').attr('data-size');
      if(data_size){
        $("#imgSize").html(data_size);
      }  
    });
  </script>
  <script type="text/javascript">
    $(document).ready(function () {

            $('#store_form').click(function (e) {
                e.preventDefault();
                $('#gif').show();
                var dest = $('#destination').val();
                //alert(dest);
                if (dest == null) {
                    $("span#destination_error").html('This field is required!');
                    $("select#destination").focus();
                    return false;
                }
                var label_name = $('#view_label').val();
                if (label_name == "") {
                    $("span#destination_error").html();
                    $("span#view_label_error").html('This field is required!');
                    $("input#view_label").focus();
                    return false;
                }
                var validation_ck = $('#validation_check').val();
                if (validation_ck == "") {
                    $("span#destination_error").html();
                    $("span#view_label_error").html();
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
                    url: "{{ route('top_destinations_destinations_store') }}",
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