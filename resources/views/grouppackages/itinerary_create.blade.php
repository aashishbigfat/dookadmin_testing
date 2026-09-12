@extends('layouts.apps')
@section('headSection')
@section('title', 'Group Tours | Day Wise Itinerary')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('css/customCSS/itinerary.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Group Tours - Day Wise Itinerary Create</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('group_packages')}}">Group Tours</a></li>
        <li class="active">Day Wise Itinerary Create</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="steps clearfix text-center">
              @include('layouts/group_itinerary_menu')
            </div>
          </div>
        </div>
        @if($itinerary <= $tour)
          <form role="form" id="ItineraryForm">
            @csrf
              <div class="box-body">
                <div class="col-md-2 col-lg-2 col-xl-2 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label>Day</label> <span class="validationError" id="day_error"></span>
                    <input type="text" class="form-control" name="day" id="day" value="{{ $itinerary }}" readonly="">
                  </div>
                </div>
                <div class="col-md-10 col-lg-10 col-xl-10 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label>Day Heading</label> <span class="validationError" id="heading_error"></span>
                    <input type="text" class="form-control" name="heading" id="heading" placeholder="Enter day heading">
                  </div>
                </div>
                <!-- <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label>Inclusion</label>
                    <textarea class="form-control" name="inclusion" id="inclusion"></textarea>
                  </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label>Exclusion</label>
                    <textarea class="form-control" name="exclusion" id="exclusion"></textarea>
                  </div>
                </div> -->
                <div class="col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label>Description</label> <span class="validationError" id="description_error"></span>
                    <textarea class="form-control" name="description" id="description"></textarea>
                  </div>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 col-xl-6">
                  <div class="form-group">
                  <label>Inclusions</label>
                    <select class="form-control Inclusions" name="Inclusions[]" id="Inclusions" multiple="">
                      @foreach($inclusions as $inclusion)
                        <option value="{{$inclusion->id}}">{{$inclusion->name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="box-body">
                <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12 col-xl-4">
                  <div class="form-group">
                  <label>Destinations</label> <span class="validationError" id="destinations_error"></span>
                    <select class="form-control destinations" name="destinations[]" id="destinations" multiple="">
                      @foreach($destinations as $destination)
                        <option value="{{$destination->id}}">{{$destination->dest_name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-8 col-lg-8 col-sm-12 col-xs-12 col-xl-8">
                  <div class="form-group">
                  <label>Point Of Interests</label> <span class="validationError" id="pois_error"></span>
                    <select class="form-control pois" name="pois[]" id="pois" multiple="">
                      
                    </select>
                  </div>
                </div>
                <div class="col-md-12 col-lg-12 col-sm-12 button-submit">
                  <button class="btn btn-primary active" type="button" id="store_form"><span class="crop_text"><i class="fa fa-save"></i> Save</span>
                    <span class="crop_wait" style="display: none">                      
                     Please Wait <i class="fa fa-circle-o-notch fa-spin"></i>
                  </span>
                  </button>
                  <!-- <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 3%; visibility: hidden;"> -->
                  <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
                </div> 
              </div>
          </form>
          @else
          <div class="col-md-12" style="margin-bottom: 10px">
            <h4>Itinerary details for all days has been completed</h4>
          </div>
        @endif
      </div>
      <div class="box">
            <div class="box-header with-border">
              <h4>Day Wise Itinerary List</h4>
            </div>
            <div class="ItninerarListing">
              @include('grouppackages/itinerary_list')
            </div>
            
          </div>
    </section>
  </div>

  @endsection
  @section('footerSection')
  <div class="modal fade" id="edit-item" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form method="post" name="myForm" enctype="multipart/form-data" id="myForm">
            @csrf
            <div class="modal-dialog modal-xl" role="document" style="width: 80%">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Day Wise Itinerary</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                  <div class="itinerary-setup m-t-20">
                   <div class="days" style="margin:-10px">
                   <div class="col-md-2 col-lg-2 col-xl-2 col-sm-12 col-xs-12 sss">
                  <div class="form-group">
                    <label>Day</label> <span class="validationError" id="edit_day_error"></span>
                    <input type="text" class="form-control" name="edit_day" id="edit_day" readonly="">
                    <input type="hidden" name="edit_id" id="edit_id" value="">
                    <input type="hidden" class="form-control" name="route_id" value="{{request()->route('id')}}">
                  </div>
                </div>
                <div class="col-md-10 col-lg-10 col-xl-10 col-sm-12 col-xs-12 sss">
                  <div class="form-group">
                    <label>Day Heading</label> <span class="validationError" id="edit_heading_error"></span>
                    <input type="text" class="form-control" name="edit_heading" id="edit_heading">
                  </div>
                </div>
                
                <div class="col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12 sss">
                  <div class="form-group">
                    <label>Description</label> <span class="validationError" id="edit_description_error"></span>
                    <textarea class="form-control" name="edit_description" id="edit_description"></textarea>
                  </div>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 col-xl-6" style="margin-right: 20%;">
                  <label>Inclusions</label>
                  <div class="form-group" id="EditInclusion">
                  
                    <select class="form-control Edit_Inclusions" name="Edit_Inclusions[]" id="Edit_Inclusions" multiple="">
                      @foreach($inclusions as $inclusion)
                        <option value="{{$inclusion->id}}">{{$inclusion->name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12 col-xl-4 sss">
                  <div class="form-group edit_dest">
                  <label>Destinations</label> <span class="validationError" id="edit_destinations_error"></span>
                    <select class="form-control edit_destinations" name="edit_destinations[]" id="edit_destinations" multiple="">
                     @foreach($destinations as $destination)
                        <option value="{{$destination->id}}">{{$destination->dest_name}}</option>
                      @endforeach
                      
                    </select>
                  </div>
                </div>
                <div class="col-md-8 col-lg-8 col-sm-12 col-xs-12 col-xl-8 sss">
                  <div class="form-group edit_point">
                  <label>Point Of Interests</label> <span class="validationError" id="edit_pois_error"></span>
                    <select class="form-control edit_pois" name="edit_pois[]" id="edit_pois" multiple="">
                      @foreach($iti_destinations as $value)
                        <option value="{{json_encode($value)}}">{{$value->poi_name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              </div>

              </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
                        <button type="submit" class="btn btn-primary" id="update_itinerary">
                          <span class="crop_text_edit"><i class="fa fa-save"></i> Update</span>
                          <span class="crop_wait_edit" style="display: none">                      
                           Please Wait <i class="fa fa-circle-o-notch fa-spin"></i>
                        </span>
                        </button>
                        <!-- <img src="{{ asset('images/loader.gif') }}" id="edit_gif" style="width: 6%; visibility: hidden;"> -->
                        <span id="messages"></span>
                    </div>
                </div>
        </form>
    </div>
  <script>
    $('#Inclusions').select2({
      placeholder: 'Select Inclusion(s)',
    });
    $('#Edit_Inclusions').select2({
      placeholder: 'Select Inclusion(s)',
    });
  </script>
  <script type="text/javascript">
    $(document).ready(function () {
            $('#store_form').click(function (e) {
                e.preventDefault();
                //$('#gif').show();
                $(".crop_wait").show();
                $(".crop_text").hide();
                var heading = $('#heading').val();
                if (heading == "") {
                    $("span#heading_error").html('This field is required!');
                    $("input#heading").focus();
                    return false;
                }
                var days = $('#days').val();
                if (days == "") {
                    $("span#sub_title_error").hide();
                    $("span#days_error").html('This field is required!');
                    $("input#days").focus();
                    return false;
                }
       
                // var description = $('#description').val();
                // if (description == "") {
                //     $("span#transport_type_error").hide();
                //     $("span#description_error").html('This field is required!');
                //     $("textarea#description").focus();
                //     return false;
                // }
                // var destinations = $('#destinations').val();
                // if (destinations == "") {
                //     $("span#description_error").hide();
                //     $("span#destinations_error").html('This field is required!');
                //     $("select#destinations").focus();
                //     return false;
                // }

                //$('#gif').css('visibility', 'visible');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{ route('group_packages_itinerary_store',request()->route('id')) }}",
                    data: $('#ItineraryForm').serialize(),
                    success: function (data) {
                        //$('#gif').hide();
                        $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                        location.reload();
                    },
                    errors: function () {

                    }

                });
            });
        });
      
  </script>
  <script>
    $('#destinations').change(function(){
      var destinationID = $(this).val(); 
      var route_id = <?php echo request()->route('id'); ?>;
      //alert(route_id);
      if(destinationID){
          $.ajax({
             type:"GET",
             url:"{{url('/get-itinerary-destination-pois-ajax')}}?destination_id="+destinationID+"&route_id="+route_id,
             success:function(res){
              if(res && res.length > 0){
                //console.log(poi_selected);
                $("#pois").html('');
                  $.each(res,function(key,value){   
                       var datas=JSON.stringify(value); 
                       datas = datas.replace("'",""); 
                      // console.log(datas);               
                      $("#pois").append("<option value='"+datas+"'>"+value.poi_name+"</option>");
                  });
                  $("#pois").val(poi_selected);
             
              }else{
                 $("#pois").empty();
              }
             }
          });
      }else{
          $("#pois").empty();
      }      
    });
    var poi_selected=[];
    $('#pois').change(function(){
      var poiId = $(this).val(); 
      //alert(destinationID);
      if(poiId){
        poi_selected=poiId;

      }
    });
  </script>
  <script>

    $("li a").each(function() {   
      //alert(this.href);
        if (this.href == window.location.href) {
            $(this).addClass("active");
        }
    })
  </script>
  <script type="text/javascript">
        $(".disableItinerary").click(function () {
          var id = $(this).data("id");
           // console.log(id);
            var token = $("meta[name='csrf-token']").attr("content");
            if (confirm("Are you sure you want to delete this Itinerary?"))
              $.ajax(
              {
                url: '/group-package-itinerary-disable/' + id,
                type: 'POST',
                data: {
                    "id": id,
                    "_token": token,
                },
                success: function (data) {
                  alert("Itinerary deleted successfully!!");
                  window.location.reload();
                }
              });
        });
</script>
<script>
    var destinationID = [];
    $('.edit_destinations').change(function(){
      destinationID.push($(this).val()); 
      //alert(destinationID);
      var route_id = <?php echo request()->route('id'); ?>;
      if(destinationID){
          $.ajax({
             type:"GET",
             url:"{{url('/get-itinerary-destination-pois-ajax')}}?destination_id="+destinationID+"&route_id="+route_id,
             success:function(res){
              if(res && res.length > 0){
                //console.log(poi_selected);
                $("#edit_pois").html('');
                  $.each(res,function(key,value){  
                  //console.log(value); 
                  //console.log(key); 
                       var datas=JSON.stringify(value);
                       datas = datas.replace("'","");  
                       //console.log(datas);               
                      $("#edit_pois").append("<option value='"+datas+"'>"+value.poi_name+"</option>");
                  });
                  $("#edit_pois").val(poi_selected);
             
              }else{
                 $("#edit_pois").empty();
              }
             }
          });
      }else{
          $("#edit_pois").empty();
      }      
    });
    var poi_selected=[];
    $('#edit_pois').change(function(){
      var poiId = $(this).val(); 
      if(poiId){
        poi_selected=poiId;
      }
    });
  </script>
<script>
     $('.edit-item').on('click', function() {
      $('#edit-item').modal('show');
            var id = $(this).data('id');
            var day_number = $(this).data('daynumber');
            var day_heading = $(this).data('dayheading');
            //var inclusion = $(this).data('inclusion');
            //var exclusion = $(this).data('exclusion');
            // alert(exclusion);
            var inclusionid = $(this).attr("data-inclusionID");
            var inclusion_id = JSON.parse(inclusionid);

            var inclusionname = $(this).attr("data-inclusionName");
            var inclusion_name = JSON.parse(inclusionname);

            var description = $(this).data('description');

            var destination_id = $(this).attr("data-destinationid");
            var dest_id = JSON.parse(destination_id);

            var destination_name = $(this).attr("data-destinationname");
            var dest_name = JSON.parse(destination_name);

            var poi_id = $(this).attr("data-poiid");
            var pois_id =JSON.parse(poi_id);
            //console.log(pois_id);

            var poi_name = $(this).attr("data-poiname");
            var pois_name = JSON.parse(poi_name);
            //alert(pois_name);

            $("#edit_id").val(id);
            $("#edit_day").val(day_number);
            $("#edit_heading").val(day_heading);
            // $('#edit_inclusion').summernote().summernote('code', inclusion);
            // $('#edit_exclusion').summernote().summernote('code', exclusion);
            $('#edit_description').summernote().summernote('code', description);

            var dataInclu = [];
            $('#Edit_Inclusions').val('').trigger('change');
            
            for (var i = 0; i < inclusion_id.length; i++) {
                var $select = $("#Edit_Inclusions");
                var items = {id: inclusion_id[i], text: inclusion_name[i]};
                //console.log(items);
                var dataInclu = $select.val() || [];
                $(items).each(function () {
                    if (!$select.find("option[value='" + this.id + "']").length) {
                        $select.append(new Option(this.text, this.id, true, true));
                    }
                    dataInclu.push(this.id);
                });
                $select.val(dataInclu).trigger('change');
            }

            var data = [];
            $('#edit_destinations').val('').trigger('change');

            for (var i = 0; i < dest_id.length; i++) {
                var $select = $("#edit_destinations");
                var items = {id: dest_id[i], text: dest_name[i]};
                //console.log(items);
                var data = $select.val() || [];
                $(items).each(function () {
                    if (!$select.find("option[value='" + this.id + "']").length) {
                        $select.append(new Option(this.text, this.id, true, true));
                    }
                    data.push(this.id);
                });
                $select.val(data).trigger('change');
            }

            var datas = [];
            $('#edit_pois').val('').trigger('change');

            for (var i = 0; i < pois_id.length; i++) {
                var $select1 = $("#edit_pois");
                var idpoi = JSON.stringify(pois_id[i]);
                var items1 = {id: idpoi, text: pois_name[i]};
                //console.log(items1);
                var datas = $select1.val() || [];
                $(items1).each(function () {
                    if (!$select1.find("option[value='" + this.id + "']").length) {
                        $select1.append(new Option(this.text, this.id, true, true));
                    }
                    datas.push(this.id);
                });
                $select1.val(datas).trigger('change');
            }
            
        });

</script>
<script type="text/javascript">
    $(document).ready(function () {
            $('#update_itinerary').click(function (e) {
                e.preventDefault();
                //$('#edit_gif').show();
                $(".crop_wait_edit").show();
                $(".crop_text_edit").hide();
                var edit_id = $('#edit_id').val();
                var days = $('#edit_day').val();
                if (days == "") {
                    
                    $("span#edit_day_error").html('This field is required!');
                    $("input#edit_day").focus();
                    return false;
                }
                var heading = $('#edit_heading').val();
                if (heading == "") {
                  $("span#edit_day_error").hide();
                    $("span#edit_heading_error").html('This field is required!');
                    $("input#edit_heading").focus();
                    return false;
                }
                
                // var destinations = $('#edit_destinations').val();
                // if (destinations == "") {
                //     $("span#edit_heading_error").hide();
                //     $("span#edit_destinations_error").html('This field is required!');
                //     $("select#edit_destinations").focus();
                //     return false;
                // }
                // var pois = $('#edit_pois').val();
                // if (pois == "") {
                //     $("span#edit_destinations_error").hide();
                //     $("span#edit_pois_error").html('This field is required!');
                //     $("select#edit_pois").focus();
                //     return false;
                // }

                //$('#edit_gif').css('visibility', 'visible');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: '/group-package/itinerary/update/' + edit_id,
                    data: $('#myForm').serialize(),
                    success: function (data) {
                        //$('#edit_gif').hide();
                        $('#messages').html("<span class='sussecmsg'>Success!</span>");
                        location.reload();
                    },
                    errors: function () {

                    }

                });
            });
        });
      
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
  <script src="{{asset('js/customJS/itinerary.js')}}"></script>
  @endsection