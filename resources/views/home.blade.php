@extends('layouts.apps')
@section('headSection')
@section('title', 'Dashboard')
@endsection
@section('main-content')
<div class="content-wrapper" style="min-height: 1153px !important">
    <section class="content-header">
      <h1>
        Dashboard
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>
    <section class="content">
      <!-- Info boxes -->
      <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-gift"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total <br>Packages</span>
              <span class="info-box-number">{{$totalpackage}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
         <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-gift"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total <br>Active Packages</span>
              <span class="info-box-number">{{$package}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-globe"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total <br>Regions</span>
              <span class="info-box-number">{{$regions}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-flag"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total <br>Countries</span>
              <span class="info-box-number">{{$countries}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-map-marker"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total <br>Destinations</span>
              <span class="info-box-number">{{$destination}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-users"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total <br>Group Tours</span>
              <span class="info-box-number">{{$group}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
         <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-map-marker"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total <br>POIs</span>
              <span class="info-box-number">{{$poi}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-tasks"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total <br>Activities</span>
              <span class="info-box-number">{{$activities}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-globe"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total <br>Experiences</span>
              <span class="info-box-number">{{$experience}}</span>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  @endsection