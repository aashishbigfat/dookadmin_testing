@extends('layouts.apps')
@section('headSection')
@section('title', 'Group Tours | Inclusions')
<link rel="stylesheet" href="{{asset('css/customCSS/inclusion.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Add Inclusions</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('group_packages')}}">Group Tours</a></li>
        <li class="active">Add Inclusions</li>
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
        <form id="InclusionForm">
          @csrf
            <div class="box-body">
           <!--  <div>
              <label class="kt-checkbox all">
                <input type="checkbox" id="checkAll">
                  Check All
                <span></span>
              </label>
            </div>
            <br> -->

            <div class="inclusion-checkbox" style="margin-bottom: 10px">
            <span class="checkbox_error"></span>
            <input type="hidden" name="departure_id" value="{{request()->route('id')}}">
              <div class="col-md-10 col-lg-10 col-xl-10 col-sm-12 col-xs-12">
                @foreach($inclusion_masters as $inclusion_master)
                  <div class="col-md-3 col-lg-3 col-xl-3 col-sm-12 col-xs-12">
                    <div class="form-group">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" class="checkbox_name" name="names[]" value="{{$inclusion_master->name}}{{$loop->index}}">
                            {{$inclusion_master->name}} 
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-9 col-lg-9 col-xl-9 col-sm-12 col-xs-12">
                      <div class="form-group">
                        <input type="text" id="descriptions" name="descriptions[]" class="form-control" value="{{$inclusion_master->description}}" placeholder=" ">
                      </div>
                  </div>
                @endforeach
              </div>
            </div>
            </div>
            <div class="box-body wrappers">
              <div class="col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12">
                <h4 style="padding-bottom: 20px;">Not in list? Add More</h4>
              </div>
              <div class="rowes">
                <div class="col-md-4 col-lg-4 col-xl-4 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label>Name</label>
                    <input id="name" name="name[]" class="form-control" type="text" placeholder=" " value="">
                  </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12">
                  <div class="form-group">
                    <label>Description</label>
                    <input type="text" id="description" name="description[]" class="form-control" placeholder=" ">
                  </div>
                </div>
                <div class="col-md-2 col-lg-2 col-xl-2 col-sm-12 col-xs-12" style="margin-top: 25px;">
                  <div class="floating-label">
                    <a href="javascript:void(0);" class="add_button" title="Add field"><img class="ImgWidth" src="{{asset('images/add-icon.png')}}"></a>
                  </div>
                </div>
              </div>
            </div>
            <div class="box-body">
              <div class="col-md-12 col-lg-12 col-sm-12 button-submit">
                <button class="btn btn-primary active" type="button" id="store_form">
                  <span class="crop_text"><i class="fa fa-save"></i> Save</span>
                    <span class="crop_wait" style="display: none">                      
                     Please Wait <i class="fa fa-circle-o-notch fa-spin"></i>
                  </span>
                </button>
                <!-- <img src="{{ asset('images/loader.gif') }}" id="gif" style="width: 3%; visibility: hidden;"> -->
                <span class="text-success" id="mesegese" style="margin-left: 10px"></span>
              </div>
            </div> 
          </form>
      </div>
    </section>
  </div>
  <style type="text/css">
    
  </style>
  @endsection
  @section('footerSection')
  <script type="text/javascript">
  $(document).ready(function(){
        $('input[type="checkbox"]').click(function(){
            if($(this).prop("checked") == true){
            }
            else if($(this).prop("checked") == false){
            }
        });
    });
  $(document).ready(function(){
    var maxFields = 20; //Input fields increment limitation
    var addButtons = $('.add_button'); //Add button selector
    var wrappers = $('.wrappers'); //Input field wrapper
    var fieldHTMLs = '<div class="rowes"><div class="col-md-4 col-lg-4 col-xl-4 col-sm-12 col-xs-12"><label>Name</label><div class="form-group"><input name="name[]" id="name" class="form-control" type="text"></div></div><div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12"><label>Description</label><div class="form-group"><input type="text" name="description[]" id="description" class="form-control"></div></div><div class="col-md-2 col-lg-2 col-xl-2 col-sm-12 col-xs-12" style="margin-top: 25px;"><div class="floating-label"><a href="javascript:void(0);" class="remove_button"><img class="ImgWidth" src="{{ asset("images/remove-icon.png")}}"/></a></div></div></div>';  
    var x = 1;
    
    $(addButtons).click(function(){
        if(x < maxFields){ 
            x++;
            $(wrappers).append(fieldHTMLs);
        }
    });
    $(wrappers).on('click', '.remove_button', function(e){
        e.preventDefault();
         $(".rowes").last().remove();
        
        x--;
    });
  });

  // Form Submit 

    $(document).ready(function () {
            $('#store_form').click(function (e) {
                e.preventDefault();
                //$('#gif').show();
                $(".crop_wait").show();
                $(".crop_text").hide();
                var checkbox = $("input[type='checkbox']").val();
                //alert(checkbox);
                if (checkbox == "") {
                    $("span#checkbox_error").html('Please select atleast 1 checkbox');
                    $(".inclus").focus();
                    return false;
                }
                

                //$('#gif').css('visibility', 'visible');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    url: "{{ route('group_date_inclusion_store',request()->route('date_id')) }}",
                    data: $('#InclusionForm').serialize(),
                    success: function (data) {
                        //$('#gif').hide();
                        $('#mesegese').html("<span class='sussecmsg'>Success!</span>");
                        window.location = data.url;
                        //location.reload();
                    },
                    errors: function () {

                    }

                });
            });
        });
</script>
<script>
  if (this.href = "{{ route('departure_date_inclusion',['id'=>request()->route('id'),'date_id'=>request()->route('date_id')])}}") {
     $(".depDates").addClass("active");
  }
</script>
  @endsection