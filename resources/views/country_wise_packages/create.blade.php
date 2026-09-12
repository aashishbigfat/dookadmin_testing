@extends('layouts.apps')
@section('headSection')
@section('title', 'Country Wise Packages List')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
  @endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Create New Country Wise Package</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Create</li>
      </ol>
    </section>
      <hr style="border-bottom: 2px solid #777">

     <section class="content">
         <div class="row">
            <form action="{{ route('country-wise-packages.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @include('country_wise_packages.form')
                <div class="col-md-12 col-lg-12 col-xl-12">
                <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </section>
</div>
  @endsection
  @section('footerSection')
   <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
  <script type="text/javascript">
     $('#edit_description').summernote().summernote('code', description);
        $(document).ready(function() {
      $('#description').summernote({
        toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
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
        height:200,
        focus: true
      });
       $('#header_sub_title').summernote({
        toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
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
    });
  </script>
<script>
$(document).ready(function() {
    $('#departure_ids').select2({
            placeholder: "Select Departures",
            allowClear: true
        });
    $('#country_id').select2({
        placeholder: 'Select Country',
        allowClear: true,
        width: '100%'
    });
     $('#destination_id').select2({
        placeholder: 'Select Destination',
        allowClear: true,
        width: '100%'
    });
});
</script>

  @endsection