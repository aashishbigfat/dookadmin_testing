@extends('layouts.apps')
@section('headSection')
@section('title', 'Visa Edit')
<link rel="stylesheet" href="{{asset('css/customCSS/pages.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>Visa Edit</h1>
    <ol class="breadcrumb">
      <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a>Visa</a></li>
    </ol>
  </section>
  <hr class="TopHeaderBorder" style="border-bottom: 2px solid #777">
  <div class="content">

    @if( $errors->count() > 0 )
    <ul>
      @foreach( $errors->all() as $message )
      <li class="list-group-item list-group-item-danger ">{{ $message }}</li>
      @endforeach
    </ul>
    @endif

    <form action="{{ route('visa.update', $visa->id) }}" method="POST" enctype="multipart/form-data" id="VisaDocs"
      class="dropzone">
      @method('PATCH')
      @csrf
      <div class="row">
        <div class="col-sm-3" style="height: 80px;">
          <div class='form-group'>
            <label for="area_unit">Visa Category*</label>
            <select name="visa_category" id="visa_category" class="form-control" required="">
              @foreach($visa_categories as $visa_category)
              <option value="{{$visa_category->name}}" <?php if($visa_category->name == $visa->visa_category){ echo
                'selected';} else{ '';}?>>{{$visa_category->name}}</option>
              @endforeach
            </select>
            <span class="validationError" id="vc_error"></span>
          </div>
        </div>
        <div class="col-sm-3">
          <div class='form-group'>
            <label for="climate_type">*Passport Issuing Country</label>
            <select name="passport_holder_country" id="from_country" class="form-control from_country select10">
              <option value="" selected disabled>--Select Country--</option>

              @foreach ($countries as $country)
              <option value="{{ $country->country_name }}" <?php if($country->country_name ==
                $visa->passport_holder_country){ echo 'selected';} else{ '';}
                ?>>{{ $country->country_name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="col-sm-3">
          <div class='form-group'>
            <label for="to_country">*Country of Residence</label>
            <select name="country_of_residence" id="to_country" class="form-control from_country select11">
              <option value="" selected disabled>--Select Country--</option>
              @foreach ($countries as $country)
              <option value="{{ $country->country_name }}" <?php if($country->country_name ==
                $visa->country_of_residence){
                echo 'selected';} else{ '';}
                ?>>{{ $country->country_name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="col-sm-3">
          <div class='form-group'>
            <label for="area_unit">*Visiting Country</label>
            <select name="visiting_country" id="to_country" class="form-control from_country select12">
              <option value="" selected disabled>--Select Country--</option>
              @foreach ($countries as $country)
              <option value="{{ $country->country_name }}" <?php if($country->country_name ==
                $visa->visiting_country){
                echo
                'selected';} else{ '';}
                ?>>{{ $country->country_name }}</option>
              @endforeach

            </select>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-3" style="height: 80px;">
          <div class='form-group'>
            <label for="area_unit">Visa Required*</label>
            <select name="visa_required" id="visa_required" class="form-control" required="">
              @foreach($req_visa as $req_v)
              <option value="{{$req_v->name}}" <?php if($req_v->name == $visa->visa_required){ echo 'selected';} else{
                '';}?>>{{$req_v->name}}</option>
              @endforeach

            </select>
            <span class="validationError" id="vr_error"></span>
          </div>
        </div>
        <div class="col-sm-3">
          <div class='form-group'>
            <label for="area_unit">Visa On Arrival</label>
            <select name="visa_arrival" id="visa_arrival" class="form-control">
              <!-- <option value="" selected>Select Country</option>  -->
              @foreach($arriv_visa as $arr_v)
              <option value="{{$arr_v->name}}" <?php if($arr_v->name == $visa->visa_arrival){ echo 'selected';} else{
                '';}?>>{{$arr_v->name}}</option>
              @endforeach

            </select>
          </div>
        </div>
        <div class="col-sm-3">
          <div class='form-group'>
            <label for="area_unit">Visa Type</label>
            <select name="visa_type" id="visa_type" class="form-control">
              <option value="" selected="">Select Visa Type</option>
              @foreach($visa_types as $visa_type)
              <option value="{{$visa_type->name}}" <?php if($visa_type->name == $visa->visa_type){ echo 'selected';}
                else{
                '';}?>>{{$visa_type->name}}</option>
              @endforeach

            </select>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-3">
          <div class='form-group'>
            <label>Fees</label>
            <input type="text" name="fees" class="form-control" id="EstCost" value="{{$visa->fees}}">
          </div>
        </div>
        <div class="col-sm-3">
          <div class='form-group'>
            <label>Processing time (In days)</label>
            <input type="text" name="processing_time" class="form-control" id="EstTime"
              value="{{$visa->processing_time}}">
          </div>
        </div>
        <div class="col-sm-3">
          <div class='form-group'>
            <label>Stay Period</label>
            <input type="text" name="stay_period" class="form-control" id="stay_period" value="{{$visa->stay_period}}">
          </div>
        </div>
        <div class="col-sm-3">
          <div class='form-group'>
            <label>Validity</label>
            <input type="text" name="validity" class="form-control" id="validity" value="{{$visa->validity}}">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
          <h3>Detailed Informations (Documentations)</h3>
          <hr style="border-bottom: 2px solid #777">
        </div>
        <div class="col-sm-12">
          <div class='form-group'>
            <label>Required Documents</label>
            <textarea class="form-control" id="summernote" name="required_documents"
              rows="3">{{ $visa->required_documents }}</textarea>
          </div>
        </div>
        <div class="col-sm-12">
          <div class='form-group'>
            <label>Eligibility Criteria</label>
            <textarea class="form-control" id="summernote1"
              name="eligibility_criteria">{{ $visa->eligibility_criteria }}</textarea>
          </div>
        </div>
        <div class="col-sm-12">
          <div class='form-group'>
            <label>Exemptions (if any)</label>
            <textarea class="form-control" id="summernote2" value=""
              name="exemptions">{{ $visa->exemptions }}</textarea>
          </div>
        </div>
        <div class="col-sm-12">
          <div class='form-group'>
            <label>General Information</label>
            <textarea class="form-control" id="summernote3" value=""
              name="general_information">{{ $visa->general_information }}</textarea>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
          <h3>Other Description</h3>
          <hr style="border-bottom: 2px solid #777">
        </div>
        <div class="col-sm-12">
          <div class='form-group'>
            <label>Additional Information (FAQs)</label>
            <textarea class="form-control" id="summernote4" value=""
              name="additional_info">{{ $visa->additional_info }}</textarea>

          </div>
        </div>
        <div class="col-sm-12">
          <div class='form-group'>
            <label> Visa Application Process</label>
            <textarea class="form-control" id="summernote5" value=""
              name="visa_application_process">{{ $visa->visa_application_process }}</textarea>

          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
          <h3>Meta Informations</h3>
          <hr style="border-bottom: 2px solid #777">
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label>Meta Title</label>
            <textarea class="form-control" name="meta_title" id="meta_title">{{$visa->meta_title}}</textarea>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label>Meta Keywords</label>
            <textarea class="form-control" name="meta_keywords" id="meta_keywords">{{$visa->meta_keywords}}</textarea>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label>Meta Description</label>
            <textarea class="form-control" name="meta_description" id="meta_description"
              rows="4">{{$visa->meta_description}}</textarea>
          </div>
        </div>
      </div>
      {{-- <div class="row wrappers">
        <div class="col-md-12">
          <h5>Resource Urls</h5>
          <hr>
        </div>
        <div class="col-md-5">
          @foreach($urls as $value)
          <div class="form-group">
            <label for=""></label>
            <input type="text" class="form-control" name="urls[]" value="{{$value}}" id="name">
          </div>
          @endforeach
        </div>

        <div class="col-md-2">
          <div class="floating-label">
            <a href="javascript:void(0);" class="add_button btn btn-outline-primary formlabelmargin"
              title="Add field"><i class="fas fa-plus"></i> Add</a>
          </div>
        </div>
      </div> --}}
      <div class="row" style="margin-top: 15px">
        <div class="col-sm-3">
          <div class="container col-md-12 ">
            <input type="submit" class="btn btn-primary sub" placeholder="Submit">

          </div>
        </div>
      </div>

    </form>
  </div>

</div>
@endsection
@section('footerSection')

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-bs4.js"></script>

<script type="text/javascript">
  $('#summernote').summernote({
  toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            //['fontname', ['fontname']],
            //['fontsize', ['fontsize']],
            //['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
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
        //focus: true,
        placeholder: 'Description ........'
});
$('#summernote1').summernote({
  toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            //['fontname', ['fontname']],
            //['fontsize', ['fontsize']],
            //['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
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
        //focus: true,
        placeholder: 'Description ........'
});
$('#summernote2').summernote({
  toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            //['fontname', ['fontname']],
            //['fontsize', ['fontsize']],
            //['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
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
        //focus: true,
        placeholder: 'Description ........'
});
$('#summernote3').summernote({
  toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            //['fontname', ['fontname']],
            //['fontsize', ['fontsize']],
            //['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
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
        //focus: true,
        placeholder: 'Description ........'
});
$('#summernote4').summernote({
  toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            //['fontname', ['fontname']],
            //['fontsize', ['fontsize']],
            //['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
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
        //focus: true,
        placeholder: 'Description ........'
});

$('#summernote5').summernote({
  toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            //['fontname', ['fontname']],
            //['fontsize', ['fontsize']],
            //['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
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
        //focus: true,
        placeholder: 'Description ........'
});
$('#summernote6').summernote({
  toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            //['fontname', ['fontname']],
            //['fontsize', ['fontsize']],
            //['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
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
        //focus: true,
        placeholder: 'Description ........'
});

$("#test").click(function() {
  var markupStr = $('#summernote').summernote('code');
  alert(markupStr);
});
//end



$('.document').select2({
      placeholder: 'Select document',
      ajax: {
      url: '/get-document-ajax',
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
          return {
          results:  $.map(data, function (item) {
          return {
            text: item.document_name,
            id: item.id
          }
          })
          };
      },
      cache: true
      }
});

</script>

<script>
  $('.select10').select2();
    $('.select11').select2();
    $('.select12').select2();
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>
<style>
  .error {
    color: red;
  }
</style>

<script>
  $(document).ready(function () {
      if ($("#VisaDocs").length > 0) {
    $('#VisaDocs').validate({
     
     //initialize the plugin
      rules: {
         
      from_country: {
             required: true
         },
        to_country: {
            required: true
        },
        visa_category: {
             required: true
            
        },
         estimated_cost: {
             required: true
            
         },
         estimated_time: {
             required: true
            
         },
      }
    });
  }
});

$(document).ready(function(){
    var maxFields = 20; //Input fields increment limitation
    var addButtons = $('.add_button'); //Add button selector
    var wrappers = $('.wrappers'); //Input field wrapper
    var fieldHTMLs = '<div class="col-md-12 rowes"  id=#rowes"><div class="row"><div class="col-md-5" style=""><label>Name</label><div class="form-group"><input name="urls[]" id="name" class="form-control" type="text"></div></div><div class="col-md-2" style="margin-top: 25px;"><div class="floating-label"><a href="javascript:void(0);" class="remove_button">X</a></div></div></div></div>';  
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

$("#visa_required").on('change', function() {
    var aaa = $('#visa_required').val();
   // alert(aaa);
    if(aaa == 'No'){
      $('#visa_arrival').attr('readonly', true);
      $('#visa_arrival').attr("style", "pointer-events: none;");
      $('#visa_type').attr('readonly', true);
      $('#visa_type').attr("style", "pointer-events: none;");
      $('#EstCost').prop('readonly', true);
      $('#EstTime').prop('readonly', true);
      $('#stay_period').prop('readonly', true);
      $('#validity').prop('readonly', true);
    }else if(aaa == 'Yes'){
      $('#visa_arrival').attr('readonly', false);
      $('#visa_arrival').attr("style", "");
      $('#visa_type').attr('readonly', false);
      $('#visa_type').attr("style", "");
      $('#EstCost').prop('readonly', false);
      $('#EstTime').prop('readonly', false);
      $('#stay_period').prop('readonly', false);
      $('#validity').prop('readonly', false);
    }
  });

</script>


@endsection