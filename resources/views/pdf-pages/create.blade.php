@extends('layouts.apps')
@section('headSection')
@section('title', 'PDF Create')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/dropzone/dist/dropzone.css" />
  <link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet"/>
  <link rel="stylesheet" href="{{asset('css/customCSS/package.css')}}">
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
        <form id="itineraryPdfCreation" method="post" action="{{route('store_module', request()->route('id'))}}" name="myForm">
          @csrf
          <div class="box-body">
            <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-group">
                  <label>File name</label> <span class="validationError" id="title_error"></span>
                  <input type="text" class="form-control" name="file_name" id="file_name" placeholder="Enter file name.. ">
                </div>
              </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                     <div class="kt-checkbox-list">
                        <label class="kt-checkbox kt-checkbox--solid">
                           <input type="checkbox" name="itinerary[]" id="banner" value="banner" checked="checked"> Banner
                           <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--solid">
                           <input type="checkbox" name="itinerary[]" id="basic_detail" value="basic_detail" checked="checked"> Basic Details
                           <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--solid">
                           <input type="checkbox" name="itinerary[]" id="day_wise" value="day_wise" checked="checked"> Day Wise Itineraries
                           <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--solid">
                           <input type="checkbox" name="itinerary[]" id="inclusions" value="inclusions" checked="checked"> Inclusions
                           <span></span>
                        </label>
                     </div>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group">
                     <div class="kt-checkbox-list">
                        <label class="kt-checkbox kt-checkbox--solid">
                           <input type="checkbox" name="itinerary[]" id="terms" value="terms" checked="checked" checked="checked"> Terms & Conditions
                           <span></span>
                        </label>
                        <label class="kt-checkbox kt-checkbox--solid">
                           <input type="checkbox" name="itinerary[]" id="contact" value="contact" checked="checked"> Contact
                           <span></span>
                        </label>
                     </div>
                  </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa"></i>Go to PDF Editor</button>
          </div>
        </form>
      </div>
      <div class="row">
          <div class="col-12">
            <table class="table table-striped- table-hover table-checkable">
                <thead>
                  <tr>
                      <th>Document Name</th>
                      <th></th>
                      <th></th>
                      <th></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($data as $value)
                    <tr>
                        <td>{{$value->file_name}}</td>
                        <td>
                        <a style="color: #5867dd; cursor: pointer;" id="btnShow_{{$value->id}}"><i class="fas fa-eye-dropper"></i> Preview</a>
                        </td>

                        <td>
                          <a href="https://serv.itineraryfinder.com/folder/pdf/dook-admin/{{$value->file_name}}" target="_blank"><i class="fas fa-file-invoice"></i> Download Print Version</a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
            </table>
          </div>
        </div>
    </section>
  </div>
  @endsection
  @section('footerSection')
    <script>
      $("li a").each(function() {   
          if (this.href == window.location.href) {
              $(this).addClass("active");
          }
      })
    </script>

  @endsection