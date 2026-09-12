@extends('layouts.apps')
@section('headSection')
@section('title', 'Dook Applied Job')
@endsection
@section('main-content')
<div class="content-wrapper">
  <section class="content-header">
      <span class="btn btn-success" style="margin-right: 100px;">Applied Job<sup style="color:#ffeb00">{{$total}}</sup></span>

      <a class="btn btn-primary" href="{{route('enquiries')}}" title="click now.!"> Click to see all Dook Enquiries</a>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a>Applied Jobs</a></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="dataIndex" id="dataIndex">
              <div class="box-body" style="overflow-x: scroll;">
                <table id="destinationListData" class="table table-bordered">
                  <tbody>
                    <tr>
                      <th>#</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Mobile</th>
                      <th>Job Title</th>
                      <th>EnquiryDate</th>
                      <th>Resume</th>
                      <!-- <th style="width:1%">Action</th> -->
                    </tr>
                    @if(count($jobs)> 0 )
                    @foreach( $jobs as $key => $job )
                    <tr>
                      <td>{{ ($jobs->currentpage()-1) * $jobs->perpage() + $key + 1 }}</td>
                      <td>{{$job->name}}</td>
                      <td><a href="mailto:{{$job->email}}">{{$job->email}}</a></td>
                      <td>{{$job->mob_no}}</td>
                      <td>{{$job->job_title}}</td>
                      <td>{{date('d-M-Y', strtotime($job->created_at))}}</td>
                      <td><a href="{{$job->resume}}">Download</a></td>
                      {{--<td>
                        <a class="deleteJob" data-id="{{ $job->id }}" title="Delete?" style="cursor: pointer; color: #F9423C;"> 
                          <i class="fa fa-trash"></i> 
                        </a>
                      </td>--}}
                    </tr>
                    @endforeach
                  @endif
                </tbody>
                </table>
              </div>
              <div class="box-footer clearfix text-right">
                  {{ $jobs->onEachSide(1)->links() }}
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <style type="text/css">
    .box-header.with-border{border-bottom:none}
   
  </style>
  @endsection
@section('footerSection')
<!-- <script type="text/javascript">
    $(".deleteJob").click(function () {
      if (confirm("Are you sure you want to delete this Enquiry?"))
      var id = $(this).data("id");
      var token = $("meta[name='csrf-token']").attr("content");
      if(id){
        $.ajax(
        {
          url: '/enquiry_delete/' + id,
          type: 'POST',
          data: {
              "id": id,
              "_token": token,
          },
          success: function (data) {
            window.location.reload();
          }
        });
      }
    });
  </script> -->
 
@endsection