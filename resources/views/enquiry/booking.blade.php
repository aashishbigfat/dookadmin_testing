@extends('layouts.apps')
@section('headSection')
@section('title', 'Dook Wesbsite Booking')
@endsection
@section('main-content')
<div class="content-wrapper">
 
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="dataIndex" id="dataIndex">
              @include('enquiry/index_data_book')
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
<script type="text/javascript">
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
  </script>
 
@endsection