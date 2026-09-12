@extends('layouts.apps')
@section('headSection')
@section('title', 'Dook Signature')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css">
@endsection

@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="buttonInline">
            <a href="https://adm.dookinternational.com/signature/" target="_blank">
                <span class="btn btn-success">Generate Signature<sup style="color:#ffeb00"></sup></span>
            </a>
        </div>
        <ol class="breadcrumb">
            <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a>Dook Signature</a></li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="dataIndex" id="dataIndex">
                        <div style="padding: 20px;">

                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                            @endif
                            <form action="{{ route('upload_signature') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div style="margin-bottom: 10px;">
                                    <label for="emp_img" class="form-label">Employee image</label>
                                    <input type="file" class="form-control" name="employee_image" id="emp_img"
                                        aria-describedby="emp_img">
                                </div>
                                <div style="margin-bottom: 10px;">
                                    <label for="emp_sig" class="form-label">Employee Signature</label>
                                    <input type="file" class="form-control" name="employee_signature" id="emp_sig">
                                </div>
                                <button type="submit" class="btn btn-primary">Upload</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<style type="text/css">
    .box-header.with-border {
        border-bottom: none
    }
</style>
@endsection
<style type="text/css">
    .ui-datepicker-buttonpane.ui-widget-content {
        display: none;
    }
</style>
@section('footerSection')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>



<style type="text/css">
    .buttonInline {
        display: flex;
        align-items: center;
    }

    .buttonInline .btn {
        margin-right: 10px;
    }

    #leadSync {
        position: relative;
    }

    #leadSync #messages {
        position: absolute;
        top: -10%;
        width: 100%;
    }
</style>
@endsection