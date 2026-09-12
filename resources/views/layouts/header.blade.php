<header class="main-header">
    <!-- Logo -->
    <a href="{{ route('home') }}" class="logo" style="padding: 0px">
      <span class="logo-lg">Dook International
       <img style="width: 20%; margin-top: -3px;" src="{{asset('images/logo.png')}}">
      </span>
    </a>
    @php
      $count4img = App\DepartureImage::where('image_compress', 1)->where('image','!=', null)->count();
      $countFimg = App\Departure::where('image_compress', 1)->where('image','!=', null)->count();
      $countBimg = App\Departure::where('banner_image_compress', 1)->where('image','!=', null)->count();
      $total = $count4img+$countFimg+$countBimg;

      $count4imgR = App\DepartureImage::where('image_compress', 0)->where('image','!=', null)->count();
      $countFimgR = App\Departure::where('image_compress', 0)->where('image','!=', null)->count();
      $countBimgR = App\Departure::where('banner_image_compress', 0)->where('image','!=', null)->count();
      $totalRemaining = $count4imgR+$countFimgR+$countBimgR;

      $rs_dollor = App\RsDollor::select('id','inr','usd')->first();

      $total_tags = App\Tag::count();
    @endphp
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
     
      <a class="tagsCss" href="{{route('tag_index')}}" title="Add Tags">
        Add Tags
        <span class="label label-success">{{$total_tags}}</span>
      </a>

      
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown messages-menu">
            <a href="{{route('index_mailer')}}">
              Mailer Shoot
            </a>
          </li>
          @if(Auth::user()->admin_type == 'a')
          <li class="dropdown messages-menu">
            <a class="rsv_dollor"  data-toggle="modal" data-id="{{ $rs_dollor->id }}" data-inr="{{ $rs_dollor->inr }}" data-usd="{{ $rs_dollor->usd }}" title="Update Rupee Dollor" style="cursor: pointer;margin-right: 5px;">
              USD 1 <-> INR {{$rs_dollor->inr}}
            </a>
          </li>
          @endif

          {{--<li class="dropdown messages-menu">
           <!--  <a href="{{route('image_compress')}}"> -->
            <a href="#">
              Package Image Compressed
              <span class="label label-success">{{$total}}</span>
            </a>
          </li>
          <li class="dropdown notifications-menu">
            <!-- <a href="{{route('image_compress')}}"> -->
            <a href="#">
              Remaining Package Image Compressed
              <span class="label label-warning">{{$totalRemaining}}</span>
            </a>
          </li>--}}
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{asset('images/logo.png')}}" class="user-image">
              <span class="hidden-xs">{{Auth()->user()->name}}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <!-- <li class="user-header">
                <img src="{{asset('images/logo.png')}}" class="img-circle" alt="User Image">

                <p>
                  {{Auth()->user()->name}}
                </p>
              </li> -->
            
              <li class="user-footer">
                <div class="text-left">
                  <a href="#">
                    <i class="fa fa-user" style="margin-right: 5px"></i>Profile
                </a>
                </div>
                
              </li>
              <li class="user-footer">
                <div class="text-left">
                  @if(Auth::guest())
                    <a href="{{route('login')}}" class="btn btn-default btn-flat">Login</a>
                    @else

                    <a href="{{route('logout')}}" 
                    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                      <i class="fa fa-power-off" style="margin-right: 5px"></i>Logout
                    </a>
                    <form id="logout-form" action="{{route('logout')}}" method="POST" style="display: none;">
                      {{csrf_field()}}
                    </form>
                  @endif
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <!-- <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li> -->
        </ul>
      </div>
    </nav>
   

  </header>
  <style type="text/css">
    .totalRemainingImg.main-header .navbar .nav>li>a>.label {
      position: absolute;
      top: 2px;
      right: 7px;
      text-align: center;
      font-size: 15px;
      padding: 2px 3px;
      line-height: .9;
  }
  .modal-content.classes {
    position: relative;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    width: 100%;
    pointer-events: auto;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ebedf2;
    border-radius: .3rem;
    outline: 0;
  }
  a.tagsCss {
    float: left;
    background-color: transparent;
    background-image: none;
    padding: 15px 15px;
    font-family: fontAwesome;
    color: aquamarine;
}
a.tagsCss:hover{background-color: #367fa9;}
  </style>

  <div class="modal fade" id="rupee-dollor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <form method="post" name="rupeeDollor" enctype="multipart/form-data" id="myFormrupeeDollor">
    @csrf
    <div class="modal-dialog modal-xl" role="document" style="width: 40%">
      <div class="modal-content classes">
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLabel">Update Rupee VS Dollor <span style="float: right;"><button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close"></i> Close</button></span></h4>
        </div>
        <div class="modal-body">
          <div class="itinerary-setup m-t-20">
          <div class="days" style="margin:-10px">
          <div class="rowes">
            <input class="form-control" type="hidden" id="edit_id_rs">
            <div class="col-md-3 col-lg-3 col-xl-3 col-sm-12 col-xs-12">
              <div class="form-group">
                <label>Dollor USD</label>
                <input class="form-control" type="text" id="dollors" disabled="">
              </div>
            </div>
            <div class="col-md-3 col-lg-3 col-xl-3 col-sm-12 col-xs-12">
              <div class="form-group">
                <label>Rupee INR</label>
                <input type="text" id="rupies" name="rupies" class="form-control">
              </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12 text-center" style="margin-top: 25px;">
              <button type="submit" class="btn btn-primary" id="update_inr_usd">
                <span class="crop_text_edit_rs"><i class="fa fa-save"></i> Update</span>
                <span class="crop_wait_edit_rs" style="display: none">                      
                 Please Wait <i class="fa fa-circle-o-notch fa-spin"></i>
               </span>
             </button>
             <span id="messages_rs"></span>
           </div>
          </div>
      </div>
    </div>

  </div>
  </div>
  </div>
  </form>
  </div>