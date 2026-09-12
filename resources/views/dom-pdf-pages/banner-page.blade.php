@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
<div class="tab-pane active" id="kt_tabs_6_1" role="tabpanel">
    <div class="row">
        <div class="col-xl-12 col-lg-12 order-lg-12 order-xl-12">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__body">

                    <form method="POST" action="{{route('banner-update')}}" enctype="multipart/form-data" class="itinerary-setup m-t-20" id="banner_page">
                        @csrf
                        <div class="form-group col-md-3">
                            <label>Company Name</label>
                            <input type="text" id="company_name" name="company_name" class="form-control" value="{{$banner_data->company_name}}" placeholder="Enter company name..">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Contact No.</label>
                            <input type="text" id="phone" name="contact_no" value="{{$banner_data->contact_no}}" class="form-control" placeholder="Enter phone..">
                            @if(isset($banner_data->id))
                                <input type="hidden" name="id" value="{{$banner_data->id}}">
                            @else
                                <input type="hidden" name="id" value="">
                            @endif
                            <input type="hidden" name="departureid" value="{{request()->route('id')}}">
                        </div>
                        <div class="form-group col-md-3">
                            <label>WhatsApp No.</label>
                            <input type="text" id="w_mobile" name="whatsapp_no" value="{{$banner_data->whatsapp_no}}" class="form-control" placeholder="Enter WhatsApp No.">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Email</label>
                            <input type="email" id="email" name="email" value="{{$banner_data->email}}" class="form-control" placeholder="Enter email..">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Website</label>
                            <input type="text" id="website" name="website" value="{{$banner_data->website}}" class="form-control" placeholder="Enter company website..">
                        </div>
                        <div class="form-group col-md-4">
                            <label style="margin-bottom: 15px">Banner Image <span style="color: red">Required Size = W: 800px H: 1150px</span></label> <span class="validationError" id="image_error"></span> 
                                <input type="file" class="uploadFileBannerP" name="banner_image" id="uploadFileBanner" accept="image/jpeg, image/jpg, image/png," onchange="readURL(this);">
                        </div>
                        <div class="col-md-4">
                            <?php 
                                // $aa = "https://adm.dookinternational.com/dook/images/package/".$banner_data->banner_image;
                                // $image = getimagesize($aa);
                                // $width = $image[0];
                                // $height = $image[1];
                                // echo "Current Image W:". $width.",";
                                // echo "H:". $height;
                            ?>
                            <img id="banner" onclick="triggerImage()" src="{{generateSignedUrl('package/'. $banner_data->banner_image)}}" class="fetured_images_view" width="140" height="80"/>
                        </div>
                        <div class="col-md-12 text-left">
                            <button type="submit" class="btn btn-info m-t-20" type="button"><i class="fas fa-save"></i>Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

