@extends('layouts.apps')
@section('headSection')
@section('title', 'Package | Inclusions')
<link rel="stylesheet" href="{{asset('css/customCSS/inclusion.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Add Inclusions</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Package</a></li>
        <li class="active">Add Inclusions</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="steps clearfix text-center">
              @include('layouts/land_itinerary_menu')
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
              <div class="col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12">
                @foreach($inclusion_masters as $key => $inclusion_master)
                  <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12">
                    <div class="form-group">
                      <div class="checkbox">
                        <label>
                          <input type="hidden" name="icons[]" value="{{$inclusion_master->icon}}">
                          <input type="checkbox" class="checkbox_name" name="names[]" value="{{$inclusion_master->name}}{{$loop->index}}">
                          <img src="{{generateSignedUrl('inclusion/'.$inclusion_master->icon)}}" alt="icon" width="12">
                            {{$inclusion_master->name}}
                        </label>
                      </div>
                      <div class="form-group">
                        <input type="text" id="descriptions" name="descriptions[]" class="form-control" value="{{$inclusion_master->description}}" placeholder=" ">
                      </div>
                    </div>
                  </div>
                  <!-- <div class="col-md-9 col-lg-9 col-xl-9 col-sm-12 col-xs-12">
                      
                  </div> -->
                @endforeach
              </div>
            </div>
            </div>
            <div class="box-body wrappers">
              <div class="col-md-12 col-lg-12 col-xl-12 col-sm-12 col-xs-12">
                <h4 style="padding-bottom: 20px;">Not in list? Add More</h4>
              </div>
              <div class="rowes">
                <div class="col-md-2 position-static" id="icon_sel">
                  <div class="form-group">
                      <label for="">Inclusion Icon</label>
                      <div class="inclusionSelectIcon" onclick="selectIcon('icon',this,1)" id="clickIcon_1">
                          Select Inclusion Icon
                      </div>
                      <div class="inclusionSelect_Icon">
                          @foreach($inclusion_icon as $key => $row)
                              <div class="selectedIcon">
                                  <img src="{{generateSignedUrl('inclusion/'.$row->icon)}}" alt="icon" onclick="selectedIcon({{str_replace(' ', '', $row->name)}})" id="clickID{{$key}}">
                                  <input type="radio" name="inclusion-icon" id="{{str_replace(' ', '', $row->name)}}" value="{{$row->icon}}">
                              </div>
                          @endforeach
                      </div>
                      {{-- <select class="form-control icons" name="icon[]" id="icons">
                          @foreach($inclusion_icon as $row)
                              <option value="{{$row->icon}}" data-image="{{asset('inclusion-images/'.$row->icon)}}">{{$row->name}}</option>
                          @endforeach
                      </select> --}}
                  </div>
                </div>
                <div class="col-md-3 col-lg-3 col-xl-3 col-sm-12 col-xs-12">
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
                <div class="col-md-1 col-lg-1 col-xl-1 col-sm-12 col-xs-12" style="margin-top: 25px;">
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
    .inclusionSelectIcon {
        border-bottom: 1px solid #ccc;
        padding: 7px 6px;
        cursor: pointer;
        display: flex;
    }
    .inclusionSelect_Icon {
        display: none;
    }
    .inclusionSelect_Icon.show {
        display: flex !important;
        flex-wrap: wrap;
        padding: 12px 18px;
        box-shadow: 3px 2px 6px 0 rgb(0 0 0 / 38%);
        width: 100%;
        position: absolute;
        z-index: 2;
        max-width: 400px;
        bottom: 43%;
        left: 254px;
        background-color: #fff;
        border-radius: 5px;
    }
    .selectedIcon {
        padding: 4px 6px;
        flex: 1;
        max-width: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .inclusionSelect_Icon .selectedIcon img {
        width: 24px;
        cursor: pointer;
    }
    .inclusionSelectIcon img {
      width: 24px;
      margin-right: 6px;
    }.selectedIcon input {
    display: none;
}
  </style>
  @endsection
  @section('footerSection')
  <script src="{{asset('js/customJS/inclusion.js')}}"></script>
  <script type="text/javascript">
    $(document).ready(function(){
          $('input[type="checkbox"]').click(function(){
              if($(this).prop("checked") == true){
              }
              else if($(this).prop("checked") == false){
              }
          });
      });
    $(".icons").select2({
        templateResult: formatIcon,
        templateSelection: formatIcon,
    });
    function formatIcon(opt1) {
        if (!opt1.id) {
            return opt1.text.toUpperCase();
        }

        var optimage = $(opt1.element).attr('data-image');
        console.log(optimage)
        if (!optimage) {
            return opt1.text.toUpperCase();
        } else {
            var $opt1 = $(
                '<span><img src="' + optimage + '" width="20px" /> ' + opt1.text.toUpperCase() + '</span>'
            );
            return $opt1;
        }
    };
    $(document).ready(function () {
        $('input[type="checkbox"]').click(function () {
            if ($(this).prop("checked") == true) {
            } else if ($(this).prop("checked") == false) {
            }
        });
    });
    $(document).ready(function () {
        var maxFields = 20; //Input fields increment limitation
        var addButtons = $('.add_button'); //Add button selector
        var wrappers = $('.wrappers'); //Input field wrapper
        //var fieldHTMLs = '';
        var x = 1;

        $(addButtons).click(function () {
            if (x < maxFields) {
                x++;
                $(wrappers).append(`<div class="rowes col-md-12"><div class="row position-relative"><div class="col-md-2 position-static"><label for="">Inclusion Icon</label><div class="inclusionSelectIcon" onclick="selectIcon('icon',this,${x})" id="clickIcon_${x}")>Select Inclusion Icon</div></div><div class="col-md-3"><label>Name</label><div class="form-group"><input name="name[]" id="name" class="form-control" type="text"></div></div><div class="col-md-6"><label>Description</label><div class="form-group"><input type="text" name="description[]" id="description" class="form-control"></div></div><div class="col-md-1" style="margin-top: 25px;"><div class="floating-label"><a href="javascript:void(0);" class="remove_button"><img class="ImgWidth" src="{{ asset("images/remove-icon.png")}}" /></a></div></div></div></div>`);

                $(".icons_add").select2({
                    templateResult: formatState,
                    templateSelection: formatState
                });

                function formatState(opt) {
                    if (!opt.id) {
                        return opt.text.toUpperCase();
                    }

                    var optimage = $(opt.element).attr('data-image');
                    console.log(optimage)
                    if (!optimage) {
                        return opt.text.toUpperCase();
                    } else {
                        var $opt = $(
                            '<span><img src="' + optimage + '" width="20px" /> ' + opt.text.toUpperCase() + '</span>'
                        );
                        return $opt;
                    }
                };
            }
        });
        $(wrappers).on('click', '.remove_button', function (e) {
            e.preventDefault();
            $(".rowes").last().remove();
            x--;
        });
    });

  // $(document).ready(function(){
  //   var maxFields = 20; //Input fields increment limitation
  //   var addButtons = $('.add_button'); //Add button selector
  //   var wrappers = $('.wrappers'); //Input field wrapper
  //   var fieldHTMLs = '<div class="rowes"><div class="col-md-4 col-lg-4 col-xl-4 col-sm-12 col-xs-12"><label>Name</label><div class="form-group"><input name="name[]" id="name" class="form-control" type="text"></div></div><div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12"><label>Description</label><div class="form-group"><input type="text" name="description[]" id="description" class="form-control"></div></div><div class="col-md-2 col-lg-2 col-xl-2 col-sm-12 col-xs-12" style="margin-top: 25px;"><div class="floating-label"><a href="javascript:void(0);" class="remove_button"><img class="ImgWidth" src="{{ asset("images/remove-icon.png")}}"/></a></div></div></div>';  
  //   var x = 1;
    
  //   $(addButtons).click(function(){
  //       if(x < maxFields){ 
  //           x++;
  //           $(wrappers).append(fieldHTMLs);
  //       }
  //   });
  //   $(wrappers).on('click', '.remove_button', function(e){
  //       e.preventDefault();
  //        $(".rowes").last().remove();
        
  //       x--;
  //   });
  // });

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
                    url: "{{ route('packages_inclusion_store',request()->route('id')) }}",
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

    $("li a").each(function() {   
      //alert(this.href);
        if (this.href == window.location.href) {
            $(this).addClass("active");
        }
    })
  </script>
  @endsection