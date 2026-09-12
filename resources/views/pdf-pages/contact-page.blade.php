<div class="tab-pane" id="kt_tabs_6_13" role="tabpanel">
    <div class="row">
        <div class="col-xl-12 col-lg-12 order-lg-3 order-xl-1">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__body">

                    <form method="POST" action="{{route('contact-update')}}" enctype="multipart/form-data" class="itinerary-setup m-t-20" id="banner_page">
                        @csrf
                        <div class="form-group">
                            <label>Company Name</label>
                            <input type="text" id="company_name" name="company_name" class="form-control" value="{{$contact_data->company_name}}" placeholder="Enter company name..">
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Name</label>
                                <input type="text" id="name" name="name" value="{{$contact_data->name}}" class="form-control" placeholder="Enter name..">
                                <input type="hidden" name="contact_id" value="{{$contact_data->id}}">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Contact No.</label>
                                <input type="text" id="phone" name="phone" value="{{$contact_data->phone}}" class="form-control" placeholder="Enter contact no.">
                            </div>
                            <div class="form-group col-md-4">
                                <label>WhatsApp No.</label>
                                <input type="text" id="mobile" name="mobile" value="{{$contact_data->mobile}}" class="form-control" placeholder="Enter WhatsApp No.">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Email</label>
                                <input type="email" id="email" name="email" value="{{$contact_data->email}}" class="form-control" placeholder="Enter email..">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Address</label>
                                <input type="text" id="address" name="address" value="{{$contact_data->address}}" class="form-control" placeholder="Enter name..">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Manager Name</label>
                                <input type="text" id="manager_name" name="manager_name" value="{{$contact_data->manager_name}}" class="form-control" placeholder="Enter manager name..">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Manager phone</label>
                                <input type="text" id="manager_phone" name="manager_phone" value="{{$contact_data->manager_phone}}" class="form-control" placeholder="Enter manager mobile..">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Manager email</label>
                                <input type="text" id="manager_email" name="manager_email" value="{{$contact_data->manager_email}}" class="form-control" placeholder="Enter manager email..">
                            </div>
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
                <input type="hidden" name="id" value="{{$contact_data->id}}">
                <input type="hidden" name="page_name" value="contact">
                <input type="hidden" name="package_id" value="{{$contact_data->departure_id}}">
                <div size="A4" style="position: relative" id="containercontact" class="pdf_container_di">
                    <div class="contact">
                        <h1 style="text-align: center;margin-bottom:78px;">Contact Details</h1>
                        <div class="travel-guide-box">
                            <div style="display:flex;vertical-align: top;align-items: end;margin-bottom:10px;">
                                <img src="{{asset('media/itinerary/guide.png')}}" style="width:40px;margin-right:10px;">
                                <div>
                                    <h1>Tour Manager</h1>
                                    @if($contact_data->manager_name != '')
                                        <p>{{$contact_data->manager_name}}</p>
                                    @endif
                                    @if($contact_data->manager_phone != '')
                                        <p>{{$contact_data->manager_phone}}</p>
                                    @endif
                                    @if($contact_data->manager_email != '')
                                        <p>{{$contact_data->manager_email}}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="contact-detail-box">
                            <div class="contact-detail">
                                <img src="https://www.dookinternational.com/images/logo.png" alt="company-logo" style="max-width:100%;margin:0 auto 12px;" width="100px">
                                <h2 style="font-size:30px">{{$contact_data->company_name}}</h2>
                                <p style="border-bottom:1px solid #fff;width: 100px;margin:10px auto;"></p>
                                <p style="font-size:14px;"><strong>Company Contact</strong></p>
                                <p><span><strong>Name:</strong> {{$contact_data->name}}</span> </p>
                                <p> <span><strong>Tel.:</strong> {{$contact_data->phone}}</span></p>
                                <p> <span><strong>M.:</strong> {{$contact_data->mobile}}</span></p>
                                <p><span><strong>Email:</strong> {{$contact_data->email}}</span></p>
                                <p style="border-bottom:1px solid #fff;width: 100px;margin:10px auto;"></p>
                                <address>
                                    <strong>Address</strong><br>
                                    <span>{{$contact_data->address}}</span>
                                </address>
                            </div>
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
        /* div#containercontact {
            background: white;
            display: block;
            margin: 0 auto;
        }
        div#containercontact {
            width: 210mm;
            height: 297mm;
            overflow: hidden;
        }
        .contact-detail-box{position: relative;bottom:0;width:50%;right:0;margin-left: auto;}
        .contact-detail{width:100%;background:#2f2f2f;position:relative;margin-left:auto;text-align:center;color:#fff;padding:25px;padding-top:0;}
        .contact-detail p{font-size:20px;}
        .contact-detail address span{display:block;font-size:16;font-weight:500}
        .contact-detail::before{
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            bottom: 100%;
            right: 0;
            border-right: 396px solid #2f2f2f;
            border-top: 170px solid transparent;
        }
        .travel-guide-box{width:50%;background:#861619;position:absolute;margin-left:auto;color:#fff;padding:25px;bottom:0px;left:0;float:left;}
        .travel-guide-box::before{
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            bottom: 100%;
            right: 0;
            border-right: 336px solid #ed2028;
            border-top: 162px solid transparent;
        }
        .travel-guide-box::after{
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            bottom: 100%;
            right: 0;
            left: 0;
            border-left: 396px solid #861619;
            border-top: 170px solid transparent;
        }
        .watermark {
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
        .contact{position:absolute;bottom:0;width:100%;} */
    </style>
</head>