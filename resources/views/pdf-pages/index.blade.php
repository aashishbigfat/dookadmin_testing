@extends('layouts.apps')
@section('headSection')
@section('title', 'PDF Create')
    <meta charset="utf-8">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/dropzone/dist/dropzone.css" />
  <link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet"/>
  <link rel="stylesheet" href="{{asset('css/customCSS/package.css')}}">
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,500,700,800,900,300 | Lato:400,700,300italic| Playfair+Display:400,700,900,400italic,700italic,900italic' rel='stylesheet' type='text/css' />
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Darker+Grotesque:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Redressed&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    <script src="https://adm.dookinternational.com/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="https://adm.dookinternational.com/bower_components/jquery-ui/jquery-ui.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
    <style>
        *, ::after, ::before {
            box-sizing: border-box;
            margin:0;padding:0;
        }
        #container, .pdf_container_di{
            background: #fff;
            display: block;
            margin: 0 auto;
            width: 210mm;
            height: 297mm;
            overflow: hidden;
        }
        body #container, .pdf_container_di{
            font-family: 'Open Sans', sans-serif;
        }
        #container .font-change p, .pdf_container_di .font-change p{
            font-family: 'Open Sans', sans-serif;
        }
        #container h1, #container h2, #container h3, #container h4, #container h5, #container h6,
        .pdf_container_di h1, .pdf_container_di h2, .pdf_container_di h3, .pdf_container_di h4,
        .pdf_container_di h5, .pdf_container_dih6{font-family: 'Dancing Script', cursive;font-weight:bold;margin:0;line-height: 1;}
        .compnayName:after{
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            top: 0;
            right: -60px;
            left: 100%;
            border-top: 77px solid #cc2127;
            border-right: 60px solid transparent;
        }
        .leftcornor:before{
            width: 0;
            content: "";
            position: absolute;
            left:-60px;;
            height: 0;
            border-bottom: 60px solid #b14e51;
            border-left: 60px solid transparent;
        }
        .toprightleftcornor::before{
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            top: 0;
            right: -60px;
            left: 100%;
            border-bottom: 77px solid #cc2127;
            border-left: 60px solid transparent;
        }
        .lefttoright::before{
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            bottom: 0;
            /* right: -60px; */
            border-bottom: 180px solid #ffffff;
            border-right: 400px solid transparent;
        }
        .lefttoright::after{
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            bottom: 0;
             right: 0px;
            border-bottom: 267px solid #ffffff;
            border-left: 400px solid transparent;
        }
        .departure-basic-details{padding:20px;display:block;width:100%;float: left;}
        .departure-basic-details h2{font-size:2.3rem;line-height:1;margin-bottom:25px;}
        .departure-basic-details p{font-size:1.6rem;line-height: 1.5;font-family: 'Open Sans', sans-serif;}
        .departure-basic-details p span{color:#b52818;font-family: 'Open Sans', sans-serif;margin:0;line-height:1;font-size:1.6rem;}
        ul.placestovisit li{
            width: 50%;
            float: left;
            display: flex;
            line-height: 1.5;
            font-size: 20px;
            align-items: center;
            margin:0;
            margin-bottom: 10px;
            font-family: 'Open Sans', sans-serif;
        }
        ul.placestovisit li img{width:50px;height:50px;object-fit:cover;margin-right:10px}
        .attraction-points p{font-family: 'Open Sans', sans-serif;margin:0;}
        .watermark{
            position: absolute;
            transform: rotate(-45deg);
            font-size: 60px;
            color: #ddd;
            font-weight: bold;
            z-index: 0;
            opacity: 0.3;
            top: calc(50% - 22px);
            left: calc(50% - 265px);
        }
        .DaywiseTopbg{
            height: 20%;width:100%;
            background:#484848;
            position: relative;
            padding:50px;margin-bottom: 14%;
        }
        .Daywisebottomrightbg{height: 71%;width:50%;background:#e16a61;position:relative;right:0;float: right;padding:20px;color:#fff;display: flex;align-items: center;}
        .Daywisebottomrightbg::before{
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            bottom:100%;
            right: 0px;
            border-bottom: 94px solid #e16a61;
            border-left: 10.5cm solid transparent;
        }
        .Daywisebottomrightbg p{line-height:1.1;font-size:16px;margin:0;}
        .DaywiseTopbg::after{
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            top:100%;
            right: 0px;
            border-top: 185px solid #484848;
            border-right: 21cm solid transparent;
        }
        .DaywiseTopbg h1{font-size:2.5rem;color:#fff;}
        .DaywiseTopbg p{font-size:2rem;color:#fff;}
        .Poi-imgDaywise{width:90%;height:400px;border:10px solid #fff;margin:auto;box-shadow: 0px 1px 5px 0px #ddd;position: relative;}
        .dayItineraryDesc_dook{
            margin-top: 13px;
        }
        .dayItineraryDesc_dook ul{
            list-style-type: none !important;
        }
        .dayItineraryDesc_dook ul li{
            position: relative;
            font-size: 11px;
            line-height:1.3;
            padding: 0 22px 0 34px;
            text-align: justify;
            margin:0;
        }
        .dayItineraryDesc_dook ul li:not(:last-child){margin-bottom:4px;}
        .dayItineraryDesc_dook ul li:before{
            position: absolute;
            content: "\2022";
            font-size: 20px;
            left: 20px;
            top: -6px;
            color: #333;
        }
        .terms-condition.dayItineraryDesc_dook h4{margin:8px 0 5px;}
        .inclusions{position: relative;}
        .inclusions ul.inclusions-list{height: 300px;left: 50px;bottom: 50px;padding:20px;width:40%}
        ul.inclusions-list li{width:100%;
            /* font-size:22px; */
            font-size:16px;
            /* font-weight:bold; */
            display:block;}
        ul.inclusions-list li::before{content:"\21D2";position: relative;color: #da7270;margin-right: 5px;}
        .contact-detail-box{position: relative;bottom:0;width:50%;right:0;margin-left: auto;}
        .contact-detail{width:100%;background:#2f2f2f;position:relative;margin-left:auto;text-align:center;color:#fff;padding:25px;padding-top:0;}
        .contact-detail p{font-size:16px;}
        .contact-detail address span{display:block;font-size:16;font-weight:500}
        .contact-detail address{
            margin-bottom:26px;
            font-size: 13px;
        }
        .contact-detail::before{
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            bottom: 100%;
            right: 0;
            border-right: 396px solid #2f2f2f;
            border-top: 170px solid transparent;
        }
        .travel-guide-box{width:50%;background:#861619;position:absolute;margin-left:auto;color:#fff;padding:25px;bottom:0px;left:0;float:left;}
        .travel-guide-box::before{
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            bottom: 100%;
            right: 0;
            border-right: 336px solid #ed2028;
            border-top: 162px solid transparent;
        }
        .travel-guide-box::after{
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            bottom: 100%;
            right: 0;
            left: 0;
            border-left: 396px solid #861619;
            border-top: 170px solid transparent;
        }
        .contact{position:absolute;bottom:0;width:100%;}
        .btm_powered{
            position: absolute;
            bottom: 0;
            right: 0;
            color: #fff !important;
            background-color: #023F75;
            padding: 7px 16px 12px;
            font-size: 10px;
            display: flex;
            align-items: flex-end;
            box-shadow: 0 0 6px 0 rgb(255 255 255 / 42%);
            border-radius: 12px 0 0 0;
            line-height: 1;
            margin: 0;
            text-decoration: none !important;
        }
        .btm_powered img{
            width: 24px;
            margin: 0 3px 0 6px;
        }
        .btm_powered strong{
            font-size: 12px;
            line-height: 1;
        }
    </style>
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Package</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('packages')}}">Package</a></li>
        <li class="active">Basic Detail Create</li>
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
        <div class="col-xl-12 col-lg-12 order-lg-12 order-xl-12">
                        <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-portlet__head">
                                <div class="kt-portlet__head-label headings">
                                    <h3 class="" style="margin-top: 0px; margin-bottom: 10px;"> Pdf Pages
                                    </h3>
                                </div>
                                <div class="kt-portlet__head-toolbar">
                                    <div class="kt-portlet__head-wrapper" style="display: inline-flex; margin-bottom: 20px;margin-top: 10px;">
                                        <a href="{{route('pdf-pages.create',request()->route('id'))}}" class="btn btn-success btn-icon-sm" style="margin-right: 50px;"> <i class="la la-list-ul"></i>Pdf List</a>
                                        <form method="post" id="PdfGenerateId">
                                            @csrf
                                            <input type="hidden" name="tenant_id" value="{{$tenant_id}}">
                                            <input type="hidden" name="route_id" value="{{$route_id}}">
                                            <input type="hidden" name="itinerary_id" value="{{$banner_data->itinerary_id}}">
                                            <button type="button" class="btn btn-primary btn-icon-sm" id="submitPdfCreate" data-toggle="modal" data-target="#loadMe"> <i class="la la-file-pdf-o"></i>Generate PDF</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-portlet pdfMenu">
                                <div class="kt-portlet__body">
                                    <ul class="nav nav-tabs nav-tabs-line nav-tabs-bold nav-tabs-line-3x nav-tabs-line-success" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#kt_tabs_6_1" role="tab">Banner</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#kt_tabs_6_2" role="tab">Basic Details</a>
                                        </li>
                                        @foreach($itinerary_data as $i_data)
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#kt_tabs_6_itinerary{{$loop->index}}" role="tab">Day{{$loop->index +1}}</a>
                                        </li>
                                        @endforeach
                                        
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#kt_tabs_6_11" role="tab">Inclusion/Exclusion</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#kt_tabs_6_12" role="tab">Terms & Conditions</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#kt_tabs_6_13" role="tab">Contact</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        @include('pdf-pages/banner-page')
                                        @include('pdf-pages/basic-detail-page')
                                        @include('pdf-pages/contact-page')
                                        @include('pdf-pages/terms-page')
                                        @include('pdf-pages/inclusion-exclusion-page')

                                        @foreach($itinerary_data as $itinerary)
                                        @if($loop->index == 0)
                                            @include('pdf-pages/itinerary1-page')
                                        @endif
                                        @if($loop->index == 1)
                                            @include('pdf-pages/itinerary2-page')
                                        @endif
                                        @if($loop->index == 2)
                                            @include('pdf-pages/itinerary3-page')
                                        @endif
                                        @if($loop->index == 3)
                                            @include('pdf-pages/itinerary4-page')
                                        @endif
                                        @if($loop->index == 4)
                                            @include('pdf-pages/itinerary5-page')
                                        @endif
                                        @if($loop->index == 5)
                                            @include('pdf-pages/itinerary6-page')
                                        @endif
                                        @if($loop->index == 6)
                                            @include('pdf-pages/itinerary7-page')
                                        @endif
                                        @if($loop->index == 7)
                                            @include('pdf-pages/itinerary8-page')
                                        @endif
                                        @if($loop->index == 8)
                                            @include('pdf-pages/itinerary9-page')
                                        @endif
                                        @if($loop->index == 9)
                                            @include('pdf-pages/itinerary10-page')
                                        @endif
                                        @if($loop->index == 10)
                                            @include('pdf-pages/itinerary11-page')
                                        @endif
                                        @if($loop->index == 11)
                                            @include('pdf-pages/itinerary12-page')
                                        @endif
                                        @if($loop->index == 12)
                                            @include('pdf-pages/itinerary13-page')
                                        @endif
                                        @if($loop->index == 13)
                                            @include('pdf-pages/itinerary14-page')
                                        @endif
                                        @if($loop->index == 14)
                                            @include('pdf-pages/itinerary15-page')
                                        @endif
                                        @if($loop->index == 15)
                                            @include('pdf-pages/itinerary16-page')
                                        @endif
                                        @if($loop->index == 16)
                                            @include('pdf-pages/itinerary17-page')
                                        @endif
                                        @if($loop->index == 17)
                                            @include('pdf-pages/itinerary18-page')
                                        @endif
                                        @if($loop->index == 18)
                                            @include('pdf-pages/itinerary19-page')
                                        @endif
                                        @if($loop->index == 19)
                                            @include('pdf-pages/itinerary20-page')
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
      </div>
    </section>
  </div>
  <div class="modal fade" id="loadMe" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="loadMeLabel">
  <div class="modal-dialog modal-sm" id="addRemoveClass" role="document">
    <div class="modal-content">
        <div class="modal-header" id="model_header" style="display: none;padding: 5px;">
        <h5 class="modal-title" id="exampleModalLabel" style="padding: 5px;color:#c8aa05">Pdf Creation!</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -45px;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <div class="loader"></div>
            <h3 class="test_trasitions">Please wait while we generate your PDF</h3>
            <div clas="loader-txt">
                <div class="col-12 tableHideShow" style="display: none;">
                    <table class="table table-striped- table-hover table-checkable tableHide">
                        <thead>
                            <tr>
                               <th></th>
                               <th></th>
                               <th></th>
                               <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="filenames"></td>
                                <td id="filenames_preview"></td>
                                <td>
                                    @if($devDeparture)
                                        <p id="filenames_downloads"></p>
                                    @else
                                        <p id="filenames_download"></p>
                                    @endif
                                </td>
                                      
                                <td>
                                    
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="message_show"></div>
            </div>
        </div>
    </div>
  </div>
</div>
<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
  <script type="text/javascript">
   $(document).ready(function() {
        $("#submitPdfCreate").on("click", function(e) {
        console.log('ggg');
        e.preventDefault();
        $(".loader").show();
        $(".test_trasitions").show();
            var formDatas = new FormData(document.getElementById('PdfGenerateId'));
            $.ajax({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              method: 'POST',
              url: "{{route('generate_pdf')}}",
              data: formDatas,
              contentType: false,
              processData: false,
              success: function (data) {
                 console.log(data);
                 var user_code = data.user_cade;
                 var file_name = data.file_name;
                  $.ajax({
                    type:"get",
                    url: "{{route('call_pdf')}}",
                    data: { 'fileName': file_name,
                            'userCodes': user_code
                          },
                    success: function(file_data) {
                        console.log(file_data);
                        if(file_data.error){
                            $(".loader").hide();
                            $(".test_trasitions").hide();
                            $("#model_header").css("display", "block");
                            $('#message_show').html("Something went wrong!");
                        }
                        else{
                            createPdfTable(file_name);
                        }
                        
                    },
                    statusCode:{
                        504:function(){
                            var pdfStatus=false;                          
                            setTimeout(function(){ 
                                    pdfStatus=checkPdfStatus(user_code);
                                    if(pdfStatus==false){
                                        console.log('second hit');
                                        setTimeout(function(){ 
                                           pdfStatus=checkPdfStatus(user_code);
                                        }, 3000);
                                    }
                                    if(pdfStatus==true){
                                        console.log('true status');
                                          createPdfTable(file_name);
                                    }

                            }, 3000);

                            setTimeout(function(){ 
                                createPdfTable(file_name);
                            }, 60000);
                        },
                        500:function(){
                           
                        }
                    },
                    errors: function (err) {
                        console.log(file_data);
                        $(".loader").hide();
                        $(".test_trasitions").hide();
                        $("#model_header").css("display", "block");
                        $('#message_show').html("Something went wrong!");
                    }
                });
                
              },
              errors: function () {
                $(".crop_wait").hide();
                $(".crop_text").show();
                $('#message').html("<span class='sussecmsg'>Something went wrong!</span>");
              }
        });
    });
});

   function createPdfTable(file_name)
   {
        $("#addRemoveClass").addClass('modal-lg');
        $("#addRemoveClass").removeClass('modal-sm');
        $(".loader").hide();
        $(".test_trasitions").hide();
        $("#model_header").css("display", "block");
        $(".tableHideShow").show();
        $(".tableHideShow").css("display", "block");
        $('#filenames').html(file_name);
        $('#filenames_preview').html("<a href='https://serv.itineraryfinder.com/folder/pdf/dook-admin/"+file_name+"' style='color: #5867dd; cursor: pointer;' id=''><i class='fas fa-eye-dropper'></i>Preview</a>");
        $('#filenames_downloads').html("<a href='https://serv.itineraryfinder.com/folder/pdf/dook-admin/"+file_name+"' style='color: #5867dd; cursor: pointer;' id='' target='_blank' download><i class='fas fa-eye-dropper'></i>Download Web Version</a>");
        $('#filenames_download').html("<a href='https://serv.itineraryfinder.com/folder/pdf/dook-admin/"+file_name+"' style='color: #5867dd; cursor: pointer;' data-index='Before Download Itinerary PDF, Please Activate Your Departure!' name='tab' id='pdfItineraray' download><i class='fa fa-file-alt'></i> Download Pdf</a>");
   }

   function checkPdfStatus(user_code)
   {
    var pdfStatus=false;
        $.ajax({
              method: 'GET',
              url: "{{route('check_pdf_status')}}",
              data: {user_code:user_code},
              success: function (data) {
                 console.log(data);
                 pdfStatus=data;
               },
               errors: function () {
                    pdfStatus=false;
               }
          
        });
        return pdfStatus;
   }
</script>
<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#banner')
                    .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
    function triggerImage(){
        $('#uploadFileBanner').trigger('click');
    }

    function readURLLogo(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#logo')
                    .attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function triggerImageLogo(){
        $('#uploadFileLogo').trigger('click');
    }
    
    function readURL1(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#banner1')
                    .attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function triggerImage1(){
        $('#uploadFileBanner1').trigger('click');
    }

    function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#banner2')
                    .attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function triggerImage2(){
        $('#uploadFileBanner2').trigger('click');
    }

    function readURL3(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#banner3')
                    .attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function triggerImage3(){
        $('#uploadFileBanner3').trigger('click');
    }

    function readURL4(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#banner4')
                    .attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function triggerImage4(){
        $('#uploadFileBanner4').trigger('click');
    }

    function readURL5(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#banner5')
                    .attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function triggerImage5(){
        $('#uploadFileBanner5').trigger('click');
    }

    function readURL6(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#banner6')
                    .attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function triggerImage6(){
        $('#uploadFileBanner6').trigger('click');
    }

    function readURLInclusion(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#inc_image')
                    .attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function triggerImageInclusion(){
        $('#uploadFileInclusion').trigger('click');
    }

    function readURLExclusion(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#exc_image')
                    .attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function triggerImageExclusion(){
        $('#uploadFileExclusion').trigger('click');
    }
</script>
<script src="{{ asset('plugins/custom/tinymce/tinymce.bundle.js') }}" type="text/javascript">
</script>
<script src="{{asset('js/pages/crud/forms/editors/tinymce.js')}}" type="text/javascript"></script>
<style type="text/css">
  .tox-tinymce {
      border-radius: 4px !important;
      height: 360px !important;
  }
  span.ck-file-dialog-button {
      display: none;
  }

  button.tox-tbtn.tox-tbtn--select.tox-tbtn--bespoke {
      display: none;
  }

  button.tox-tbtn.tox-tbtn--disabled {
      display: none;
  }

  .tox-toolbar__group:nth-of-type(2) {
      display: none;
  }

  .tox-toolbar__group:nth-of-type(5) {
      display: none;
  }

  .tox-tinymce {
      border-radius: 4px !important;
      height: 250px !important;
  }

  .tox-statusbar {
      display: none !important;
  }

  .tox-toolbar:nth-of-type(3) .tox-toolbar__group:nth-of-type(3) {
      display: none !important;
  }

  .tox-toolbar:nth-of-type(3) .tox-toolbar__group:nth-of-type(4) {
      display: none !important;
  }

  .tox-toolbar:nth-of-type(3) .tox-toolbar__group:nth-of-type(6) {
      display: none !important;
  }

  .tox-toolbar {
      display: inline-flex !important;
  }

  h4.kt-portlet__head-title.completeItinerary {
      color: forestgreen;
  }

  .edit_image {
      width: 100%;
  }
.pdfMenu .nav-tabs.nav-tabs-line .nav-link{padding:7px 10px;}
.pdfMenu .nav-tabs.nav-tabs-line .nav-item{padding:0;margin: 0;}
.pdfMenu .nav-tabs.nav-tabs-line .nav-item:not(:last-child) a{border-right:1px solid #ff8679;}
.pdfMenu .nav-tabs.nav-tabs-line a.nav-link,.pdfMenu .nav-tabs.nav-tabs-line.nav.nav-tabs .nav-link{color: #ff8679;border-radius:0;}
.pdfMenu .nav-tabs.nav-tabs-line.nav-tabs-line-success a.nav-link.active,.pdfMenu .nav-tabs.nav-tabs-line.nav-tabs-line-success a.nav-link:hover,.pdfMenu .nav-tabs.nav-tabs-line.nav-tabs-line-success.nav.nav-tabs .nav-link.active,.pdfMenu .nav-tabs.nav-tabs-line.nav-tabs-line-success.nav.nav-tabs .nav-link:hover{
    color: #1dc9b7;
    border-bottom: 1px solid #1dc9b7;
    border-right:1px solid #1dc9b7;
}
  .btn-file-upload:after {
      content: attr(value);
      position: absolute;
      top: 0px;
      left: 0;
      bottom: 0;
      width: 48%;
      background: #795548;
      color: white;
      border-radius: 2px;
      text-align: center;
      font-size: 12px;
      line-height: 2;
      padding-top: 7px;
  }

  .btn-file-upload-edit:after {
      content: attr(value);
      position: absolute;
      top: 0px;
      left: 0;
      bottom: 0;
      width: 44%;
      background: #795548;
      color: white;
      border-radius: 2px;
      text-align: center;
      font-size: 12px;
      line-height: 2;
      padding-top: 7px;
  }

  div#file_img-error {
      margin-top: 20px;
      font-weight: 500;
  }

  .floating-label label {

      top: -17px;
      font-size: 14px;
      color: #7B401D;
      background: #fffcea;
      padding: 0 5px;
      font-weight: 500;
      transition: top 0.7s ease, opacity 0.7s ease;
      margin: 0;
      line-height: 1;
      font-size: 12px;
  }

  label.custom-file-label {
      top: 0px;
      padding-top: 12px;
      color: #555;
  }
</style>

<style type="text/css">
.loader {
  position: relative;
  text-align: center;
  margin: 15px auto 35px auto;
  z-index: 9999;
  display: block;
  width: 80px;
  height: 80px;
  border: 10px solid rgba(0, 0, 0, .3);
  border-radius: 50%;
  border-top-color: #000;
  animation: spin 1s ease-in-out infinite;
  -webkit-animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
  to {
    -webkit-transform: rotate(360deg);
  }
}

@-webkit-keyframes spin {
  to {
    -webkit-transform: rotate(360deg);
  }
}


/** MODAL STYLING **/

.modal-content {
  border-radius: 0px;
  box-shadow: 0 0 20px 8px rgba(0, 0, 0, 0.7);
}

.modal-backdrop.show {
  opacity: 0.75;
}

.loader-txt {
  p {
    font-size: 13px;
    color: #666;
    small {
      font-size: 11.5px;
      color: #999;
    }
  }
}

#output {
  padding: 25px 15px;
  background: #222;
  border: 1px solid #222;
  max-width: 350px;
  margin: 35px auto;
  font-family: 'Roboto', sans-serif !important;
  p.subtle {
    color: #555;
    font-style: italic;
    font-family: 'Roboto', sans-serif !important;
  }
  h4 {
    font-weight: 300 !important;
    font-size: 1.1em;
    font-family: 'Roboto', sans-serif !important;
  }
  p {
    font-family: 'Roboto', sans-serif !important;
    font-size: 0.9em;
    b {
      text-transform: uppercase;
      text-decoration: underline;
    }
  }
}
</style>
  @endsection
  @section('footerSection')
  <script>
    $(document).ready(function() {
        for(i=0;i<=25;i++){
          $('#description'+i).summernote({
             toolbar: [
                ['style', ['style']],
                ['style', ['bold', 'italic', 'underline']],
                //['fontname', ['fontname']],
                //['fontsize', ['fontsize']],
                // ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture']],
                ['view', ['codeview']]
            ],
            callbacks: {
                onPaste: function (e) {
                  var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
                  var bufferText1 = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                  e.preventDefault();
                  var div = $('<div />');
                  div.append(bufferText);
                  div.find('*').removeAttr('style');
                  setTimeout(function () {
                  if(bufferText){
                    document.execCommand('insertHtml', false, div.html());
                  }else{
                    document.execCommand('insertText', false, bufferText1);
                  }
                  }, 10);
                }
              },
            styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
            height:150,
            focus: true
          });
      }
    });
</script>
    <script>
      $("li a").each(function() {   
          if (this.href == window.location.href) {
              $(this).addClass("active");
          }
      })
    </script>

  @endsection