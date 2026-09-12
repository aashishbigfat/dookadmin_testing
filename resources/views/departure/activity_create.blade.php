@extends('layouts.apps')
@section('headSection')
@section('title', 'Departure | Activities')
<link rel="stylesheet" href="{{asset('css/customCSS/activity.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Departure - Add Activities</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('departures')}}">Departure</a></li>
        <li class="active">Activities</li>
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
              <div class="col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12">
                <div class="col-md-2">
                  <label>Experiences: </label> 
                </div>
                <div class="col-md-10">
                  @foreach($experiences as $experience)
                  <span><strong>{{$experience->experience_name}}, </strong></span>
                  @endforeach
                </div>
                 <hr class="col-md-12 hrd">
                <div class="col-md-12" style="margin-top: 20px;margin-bottom: 15px;">
                  <label>Activities:</label> <span class="validationError" id="activities_error"></span>
                </div>
                <div class="col-md-12 selectAll" style="margin-bottom: 12px;"><input id="selectAll" type="checkbox"><label for="selectAll" style="margin-left: 6px;color: #9a191e;">Select All</label></div>
                <div class="row">
                  <div class="form-group col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12" id="activities">
                  @foreach($activities as $activity)
                    <div class="checkbox checked act col-md-2" style="padding: 5px;">
                      <label><input type="checkbox" name="activities[]" value="{{$activity->id}}">{{$activity->activity_name}}</label>
                    </div>
                  @endforeach
                  </div>
                </div>
              </div>
              <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left">
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
    </section>
  </div>
  <style type="text/css">
    .hrd {
    border-top: 2px solid #20649b !important;
    margin-left: -15px;
}
  </style>
  @endsection
  @section('footerSection')
<script>
  $("#selectAll").click(function(){
    $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
});
</script>
<script>
    // function getActivity(reff_id){

    //   var expId = $("#experiences_"+reff_id).val(); 
    //   var refId = $("#activities_"+reff_id);
    //   var route_id = <?php //echo request()->route('id'); ?>;
    //   if(expId){
    //       $.ajax({
    //          type:"GET",
    //          url:"{{url('/activity-ajax')}}?experience_id="+expId+"&route_id="+route_id,
    //          success:function(res){
    //           if(res && res.length > 0){
    //             $(refId).html('');
    //               $.each(res,function(key,value){   
    //                   var datas=JSON.stringify(value);      
    //                   $(refId).append('<div class="checkbox checked act col-md-2"><label><input type="checkbox" name="activities['+reff_id+'][]" value="'+value.id+'">'+value.activity_name+'</label></div>');   
    //               });
    //           }else{
    //              $(refId).empty();
    //           }
    //          }
    //       });
    //   }else{
    //       $(refId).empty();
    //   }      
    // };
</script>

<script type="text/javascript">
  $(document).ready(function () {
    $('#store_form').click(function (e) {
      e.preventDefault();
      $(".crop_wait").show();
      $(".crop_text").hide();
      $('#gif').css('visibility', 'visible');
      $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: 'POST',
        url: "{{ route('packages_activities_store',request()->route('id')) }}",
        data: $('#poiForm').serialize(),
        success: function (data) {
          //console.log(data);
          $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
          location.reload();
        },
        errors: function () {

        }
      });
    });
  });
  $("li a").each(function() {   
    if (this.href == window.location.href) {
        $(this).addClass("active");
    }
  })
</script>

@endsection