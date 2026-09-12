<div class="tab-pane" id="kt_tabs_6_11" role="tabpanel">
    <div class="row">
        <div class="col-xl-12 col-lg-12 order-lg-3 order-xl-1">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__body">

                    <form method="POST" action="{{route('inc-exc-update')}}" enctype="multipart/form-data" class="itinerary-setup m-t-20" id="banner_page">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 headings">
                                <h5>Inclusion</h5>
                                <hr>
                            </div>
                            @foreach($inclusion_data as $inclusion)
                            <div class="form-group col-md-4">
                                <label>Name</label>
                                <input type="text" id="name" name="inclusion_name[]" value="{{$inclusion->name}}" class="form-control" placeholder="Inclusion name..">
                                <input type="hidden" name="inclusion_id[]" value="{{$inclusion->id}}" />
                            </div>
                            @endforeach
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-info m-t-20 pull-right" type="button"><i class="fas fa-save"></i>Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <div class="col-xl-12 col-lg-12 order-lg-12 order-xl-12">
            <form method="POST" action="{{route('add-page')}}">
                @csrf
                <input type="hidden" name="page_name" value="inclusion">
                <input type="hidden" name="package_id" value="{{$banner_data->departure_id}}">
                <div size="A4" style="position: relative" id="container" class="pdf_container_di">
                    <div style="display: block">
                        <div class="compnayName" style="position: relative;background:#cc2127;color:#fff;display: inline-block;padding: 20px;">
                            <h1>Inclusions</h1>
                        </div>
                        <div class="inclusions">
                            <ul class="inclusions-list">
                                @foreach($inclusion_data as $inclusion)
                                    <li>{{$inclusion->name}}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <a href="https://www.tutterflycrm.com/" class="btm_powered">Powered By:
                        <div style="margin: 0;display:flex;align-items:flex-end;">
                            <img src="{{asset('images/tutterfly_logo.png')}}">
                            <strong>Tutterfly CRM</strong>
                        </div>
                    </a>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-info m-t-20 pull-center" type="button"><i class="fas fa-save"></i>Add Page for pdf</button>
                    </div>
                </div>
            </form>
            <div class="watermark">Dook International</div>
        </div>

    </div>
</div>

<head>
    <style>
        /* .watermark {
            position: absolute;
            transform: rotate(-45deg);
            font-size: 60px;
            color: #ddd;
            font-weight: bold;
            z-index: 0;
            opacity: 0.3;
            top: calc(50% - 22px);
            left: calc(50% - 265px);
        }
        div#container {
            background: white;
            display: block;
            margin: 0 auto;
        }
        div#container {
            width: 210mm;
            height: 297mm;
            overflow: hidden;
        }
        .inclusions{position: relative;}
        .inclusions ul.inclusions-list{height: 300px;left: 50px;bottom: 50px;padding:20px;width:40%}
        ul.inclusions-list li{width:100%;font-size:22px;font-weight:bold;display:block;color:#fff;}
        ul.inclusions-list li img{width:20px;margin-right:10px;top: 2px;position: relative;}
        ul.inclusions-list li{width:100%;font-size:22px;font-weight:bold;display:block;color:#fff;}
       ul.inclusions-list li::before{content:"\21D2";position: relative;color: #da7270;margin-right: 5px;} */
    </style>
</head>