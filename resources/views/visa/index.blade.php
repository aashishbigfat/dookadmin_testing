@extends('layouts.apps')
@section('headSection')
@section('title', 'Visa')
<link rel="stylesheet" href="{{asset('css/customCSS/pages.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>Visa List</h1>
    <ol class="breadcrumb">
      <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a>Visa</a></li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <div class="row">
              <div class="col-md-4">
                <a class="btn btn-primary" href="{{ $url = route('visa.create')}}"><i class="fa fa-plus"
                    aria-hidden="true"></i>
                  Add New Visa
                </a>
                <span class="btn btn-success">Total Visa <span style="color:#ffeb00">{{$total}}</span></span>
              </div>
              <div class="col-md-8">
                <form action="{{route('visa.index')}}" class="widget-search-form" method="get">
                  <div class="row dest-serach">
                    <div class="col-md-3">
                      <div class="form-group">
                        <select name="from_country_id" id="country1" label="country"
                          class="form-control country select2" onchange="resetKey()">
                          <option value="" selected disabled>--From Country--</option>
                          @foreach($countries as $country)
                          <option value="{{$country->iso_3}}" <?php if($from_country_id==$country->iso_3){echo
                            'selected';} ?>
                            >{{$country->country_name}}</option>
                          @endforeach
                        </select>
                      </div>

                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <select name="to_country_id" id="country2" label="country" class="form-control country select2"
                          onchange="resetKey()">
                          <option value="" selected disabled>--To Country--</option>
                          @foreach($countries as $country)
                          <option value="{{$country->iso_3}}" <?php if($to_country_id==$country->iso_3){echo
                            'selected';}
                            ?>
                            >{{$country->country_name}}</option>
                          @endforeach
                        </select>
                      </div>

                    </div>
                    <div class="col-sm-1">
                      <button type="submit" class="btn btn-primary"> Search </button>
                    </div>
                    <div class="col-sm-2">
                      <a class="btn btn-secondary" href="{{route('visa.index')}}"> Reset </a>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="dataIndex" id="dataIndex">
            @include('visa/visa_index_data')
          </div>
        </div>

      </div>
    </div>
  </section>
</div>


@endsection

<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
@section('footerSection')

<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('.toggle-class').change(function() {
        var status = $(this).prop('checked') == true ? 1 : 0; 
        var visa_id = $(this).data('id'); 
         
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '/changeStatusv',
            data: {'status': status, 'visa_id': visa_id},
            success: function(data){
              console.log(data.success)
              alert('Status changed successfully');
            }
        });
    })
  });
</script>
<script type="text/javascript">
  $(function(){
    $('#daterangepicker1').daterangepicker();    
  });
</script>
<!-- CK Editor -->
<script src="https://cdn.ckeditor.com/4.8.0/standard/ckeditor.js"></script>
<script>
  $(window).load(function(){
    CKEDITOR.replace('editor1');      
  });
  $('#country1').select2();
  $('#country2').select2();
</script>

<br>
@if ( $errors->count() > 0 )
<p>The following errors have occurred:</p>
<ul>
  @foreach( $errors->all() as $message )
  <li class="error ">{{ $message }}</li>
  @endforeach
</ul>
@endif

@endsection