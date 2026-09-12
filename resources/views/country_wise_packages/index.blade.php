@extends('layouts.apps')
@section('headSection')
@section('title', 'Country Wise Packages List')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>All Country Wise Packages</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a>Activities</a></li>
      </ol>
    </section> 
      <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">                
                <a href="{{ route('country-wise-packages.create') }}" class="btn btn-primary mb-3">Create New Package</a>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif 
            </div>
            <!-- /.box-header -->
            <div class="dataIndex" id="dataIndex">
                 <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sr. No.</th> 
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Featured Image</th>
                            <th>Banner Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($packages as $package)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $package->name }}</td>
                            <td>{{ $package->slug }}</td>
                            <td>
                                @if($package->featured_image)
                                    <img src="{{generateSignedUrl($package->featured_image)}}" width="50" height="50" style="border-radius: 100px;" />
                                @endif
                            </td>
                            <td>
                                @if($package->banner_image)
                                    <img src="{{ generateSignedUrl($package->banner_image) }}" width="50" height="50" style="border-radius: 100px;" />
                                @endif
                            </td>
                            <td>
                                <!-- <a href="{{ route('country-wise-packages.show', $package->id) }}" class="btn btn-info btn-sm">View</a> -->
                                <a href="{{ route('country-wise-packages.edit', $package->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <a href="#" class="btn btn-danger btn-sm">
                                <form action="{{ route('country-wise-packages.destroy', $package->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this?')">
                                    @csrf
                                    @method('DELETE')
                                  <button class="" style="background: none;border: none;">Delete</button>
                                </form>
                            </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
          </div>
          
        </div>
      </div>
    </section>

</div>
  @endsection
  @section('footerSection')
