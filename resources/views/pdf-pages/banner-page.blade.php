@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
<div class="tab-pane active" id="kt_tabs_6_1" role="tabpanel">
    <div class="row">
        <div class="col-lg-4 col-xl-5 col-md-12">
            <div class="row">
                <div class="col-xl-12 col-lg-12 order-lg-12 order-xl-12">
                    <div class="kt-portlet kt-portlet--height-fluid">
                        <div class="kt-portlet__body">

                            <form method="POST" action="{{route('banner-update')}}" enctype="multipart/form-data" class="itinerary-setup m-t-20" id="banner_page">
                                @csrf
                                <div class="form-group">
                                    <label>Company Name</label>
                                    <input type="text" id="company_name" name="company_name" class="form-control" value="{{$banner_data->company_name}}" placeholder="Enter company name..">
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>Contact No.</label>
                                        <input type="text" id="phone" name="phone" value="{{$banner_data->phone}}" class="form-control" placeholder="Enter phone..">
                                        <input type="hidden" name="itinerary_id" value="{{$banner_data->itinerary_id}}">
                                        <input type="hidden" name="id" value="{{$banner_data->id}}">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>WhatsApp No.</label>
                                        <input type="text" id="w_mobile" name="w_mobile" value="{{$banner_data->w_mobile}}" class="form-control" placeholder="Enter WhatsApp No.">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Email</label>
                                        <input type="email" id="email" name="email" value="{{$banner_data->email}}" class="form-control" placeholder="Enter email..">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Website</label>
                                        <input type="text" id="website" name="website" value="{{$banner_data->website}}" class="form-control" placeholder="Enter company website..">
                                    </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-6 col-lg-6 col-sm-12">
                                    <div class="form-group">
                                     <label style="margin-bottom: 15px">Banner Image <br><span style="color: red">Required Size = <br>W: 800px <br> H: 1150px</span></label> <span class="validationError" id="image_error"></span> 
                                     <div class="input-group">

                                        <input type="file" name="banner_image" id="uploadFileBanner" accept="image/jpeg, image/jpg, image/png, image/gif," onchange="readURL(this);">
                                    </div>
                                    </div>
                                  </div>
                                  <div class="col-md-6 col-lg-6 col-sm-12">
                                    <?php 
                                        // $aa = "https://adm.dookinternational.com/dook/images/package/".$banner_data->banner_image;
                                        // $image = getimagesize($aa);
                                        // $width = $image[0];
                                        // $height = $image[1];
                                        // echo "Current Image W:". $width.",";
                                        // echo "H:". $height;
                                    ?>
                                      <img id="banner" onclick="triggerImage()" src="https://adm.dookinternational.com/dook/images/package/{{$banner_data->banner_image}}" class="fetured_images_view" width="80" height="80"/>
                                  </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12 text-right">
                                        <button type="submit" class="btn btn-info m-t-20 pull-right" type="button"><i class="fas fa-save"></i>Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-xl-7 col-md-12">
            <div class="row">
                <div class="col-xl-12 col-lg-12 order-lg-12 order-xl-12">
                    <form method="POST" action="{{route('add-page')}}">
                        @csrf
                        <input type="hidden" name="id" value="{{$banner_data->id}}">
                        <input type="hidden" name="page_name" value="banner">
                        <input type="hidden" name="package_id" value="{{$banner_data->departure_id}}">
                        <div size="A4" style="position: relative" id="container">
                            <div class="" style="position: relative;width: 100%;height: 100%">
                                <div class="leftcornor" style="background: #b14e51;height: 50px;width: 80%;position: absolute;bottom:0;right: 0;"></div>
                                <img src="https://adm.dookinternational.com/dook/images/package/{{$banner_data->banner_image}}" alt="" style="width: 100%;object-fit: cover;height: 70%;">
                                <img src="{{asset('media/itinerary/cover_graphic.png')}}" alt="" style="max-width: 100%;position: absolute;top: 0;left: 0;width: 100%;">
                                <div style="position: absolute;bottom:50px;right: 80px;width: 370px;text-align: center;color: #fff;">
                                    <img src="https://www.dookinternational.com/images/logo.png" alt="" style="max-width:100%;height: auto;margin:0 auto 12px;">
                                    <h1 style="font-size:52px;line-height:1;margin:0;margin-bottom:16px;">{{$banner_data->company_name}}</h1>
                                </div>
                                <div style="position: absolute;bottom:60px;left:25px;width: 270px;color: #000;">
                                    @if($banner_data->phone != '')
                                    <p style="line-height: 2;display:flex;align-items:center;font-size:14px;"><img src="{{asset('media/itinerary/phone.png')}}" alt="" style="width: 20px;"> &nbsp;<span style="margin-left: 3px;line-height:1;">{{$banner_data->phone}}</span></p>
                                    @endif
                                    @if($banner_data->w_mobile != '')
                                    <p style="line-height: 2;display:flex;align-items:center;font-size:14px;"><img src="{{asset('media/itinerary/phone.png')}}" alt="" style="width: 20px;"> &nbsp;<span style="margin-left: 3px;line-height:1;">{{$banner_data->w_mobile}}</span></p>
                                    @endif
                                    @if($banner_data->email != '')
                                    <p style="line-height: 2;display:flex;align-items:center;font-size:14px;"><img src="{{asset('media/itinerary/email.png')}}" alt="" style="width: 20px;"> &nbsp;<span style="margin-left: 3px;line-height:1;">{{$banner_data->email}}</span></p>
                                    @endif
                                    @if($banner_data->website != '')
                                    <p style="line-height: 2;display:flex;align-items:center;font-size:14px;"><img src="{{asset('media/itinerary/website.png')}}" alt="" style="width: 20px;"> &nbsp;<span style="margin-left: 3px;line-height:1;">{{$banner_data->website}}</span></p>
                                    @endif
                                </div>
                            </div>
                            <a href="https://www.tutterflycrm.com/" class="btm_powered" style="right:56px;">Powered By:
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
    </div>
</div>

