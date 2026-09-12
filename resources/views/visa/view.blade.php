@extends('layouts.apps')
@section('headSection')
@section('title', 'Visa View')
<link rel="stylesheet" href="{{asset('css/customCSS/pages.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>View Visa</h1>
    <ol class="breadcrumb">
      <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a>Visa</a></li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12 paddingBottom10"></div>

      <div class="col-md-12">
        <div class="panel panel-sky">
          <div class="panel-heading">
            <h4>{{$visa->country_of_residence}} to {{$visa->visiting_country}}</h4>

          </div>
          <div class="panel-body collapse in">
            <table class="table table-striped table-bordered datatables">

              <tr>
                <th>Passport Holder Country</th>
                <td>{{$visa->passport_holder_country}}</td>
              </tr>
              <tr>
                <th>Country Of Residence</th>
                <td>{{$visa->country_of_residence}}</td>
              </tr>
              <tr>
                <th>Visiting Country</th>
                <td>{{$visa->visiting_country}}</td>
              </tr>

              <tr>
                <th>Visa Type</th>
                <td>{{$visa->visa_type}}</td>
              </tr>
              <tr>
                <th>Visa Category</th>
                <td>{{$visa->visa_category}}</td>
              </tr>

              <tr>
                <th>Processing Time</th>
                <td>{{$visa->processing_time}}</td>
              </tr>
              <tr>
                <th>Stay Period</th>
                <td>{{$visa->stay_period}}</td>
              </tr>
              <tr>
                <th>Validity</th>
                <td>{{$visa->validity}}</td>
              </tr>
              <tr>
                <th>Fees</th>
                <td>{{$visa->fees}}</td>
              </tr>
              <tr>
                <th>Required Document</th>
                <td>{!!$visa->required_documents!!}</td>
              </tr>
              <tr>
                <th>Eligibility Creteria</th>
                <td>{!! $visa->eligibility_criteria !!}</td>
              </tr>
              <tr>
                <th>Exemption</th>
                <td>{!! $visa->exemptions!!}</td>
              </tr>
              <tr>
                <th>General Information</th>
                <td>{!!$visa->general_information!!}</td>
              </tr>
              <tr>
                <th>Additional Information</th>
                <td>{!!$visa->additional_info!!}</td>
              </tr>
              <tr>
                <th>Visa Application Process</th>
                <td>{!!$visa->visa_application_process!!}</td>
              </tr>
              <tr>
                <th>Resource Urls</th>
                <td>
                  <ul>
                    {{-- @foreach($urls as $value)
                    <li> <a href="{{$value}}">{{$value}}</a></li>
                    @endforeach --}}
                  </ul>
                </td>
              </tr>






            </table>

          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<style type="text/css">
  .country_image {
    border-radius: 52%;
    height: 60px;
    width: 60px;
  }

  .datatables th {
    width: 20%;
  }
</style>

@endsection