@extends('layouts.apps')
@section('headSection')
@section('title', 'Departures | Terms & Conditions')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('css/customCSS/inclusion.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Terms & Conditions</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('departure_dates',request()->route('id'))}}">Departure Dates</a></li>
        <li class="active">Terms & Conditions</li>
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
        <form role="form" id="TermConditionForm">
          @csrf
            <div class="box-body">
              <div class="col-md-12 col-lg-12 col-sm-12">
                <span style="font-size: 22px;"> Terms & Conditions </span>&nbsp; &nbsp; <span style="font-size: 18px;float: right;"> {{$dep_main->title}} | {{$dep_main->no_of_nights}}N/{{$dep_main->no_of_days}}D | {{$dep_main->dep_dook_ref_id}} </span>
              </div>
              <div class="col-md-12 col-lg-12 col-sm-12" style="margin-top: 30px;">
                <div class="form-group">
                  <input type="hidden" name="departure_id" value="{{request()->route('id')}}">
                    <textarea class="form-control" name="terms_payment" id="terms_payment">{!! $terms->termspayment !!}</textarea>
                 
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
  <script>
    $(document).ready(function () {
        $('#store_form').click(function (e) {
          e.preventDefault();
          $(".crop_wait").show();
          $(".crop_text").hide();
          //$('#gif').css('visibility', 'visible');
          var formDatas = new FormData(document.getElementById('TermConditionForm'));
          $.ajax({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              method: 'POST',
              url: "{{ route('departure_date_terms_update',request()->route('date_id')) }}",
              data: formDatas,
              contentType: false,
              processData: false,
              success: function (data) {
                //console.log(data);
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
     $(document).ready(function() {
      $('#terms_payment').summernote({
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
        height:500,
        focus: true
        });
    });

    $("li a").each(function() {   
      //alert(this.href);
        if (this.href == window.location.href) {
            $(this).addClass("active");
        }
    })
  </script>
  @endsection