@extends('layouts.apps')
@section('headSection')
@section('title', 'Countries')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('css/customCSS/country.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
  <section class="content-header">
      <h1>Country Edit</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('countries_index')}}"></i>Countries</a></li>
        <li><a>Country Edit</a></li>
      </ol>
    </section>
    <hr class="TopHeaderBorder" style="border-bottom: 2px solid #777">
    <section class="content">
      <div class="row">
        <div class="box-body" style="margin-top: -15px">
          <ul class="nav nav-tabs TabGrid" role="tablist">
            <li class="active"><a href="#countryPackage" role="tab" data-toggle="tab">Country Tour Packages</a></li>
            <li><a href="#aboutCountry" role="tab" data-toggle="tab">About Country</a></li>
            <li><a href="#countryAttraction" role="tab" data-toggle="tab">Country Tourist Attractions</a></li>
            <li><a href="#countryGroupTours" role="tab" data-toggle="tab">Country Group Tours</a></li>
            <li><a href="#countryVisa" role="tab" data-toggle="tab">Country Visa</a></li>
            <li><a href="#countryExperience" role="tab" data-toggle="tab">Country Experience</a></li>
            <li><a href="#beforYouGo" role="tab" data-toggle="tab">Before You Go</a></li>
          </ul>
          <div class="tab-content">
            <div class="responsiveTab tab-pane fade in active" id="countryPackage">
              <form role="form" id="countryPackageEditForm">
                @csrf
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Country Name</label>
                    <input type="text" class="form-control" name="edit_country" id="edit_country" readonly ="" value="{{$countries->country_name}}">
                    <input type="hidden" class="form-control" name="tour_packages_section" value="TPS_Checks">
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Slug URL</label>
                    <input type="text" class="form-control" name="edit_slug_url" id="edit_slug_url" value="{{$countries->slug_url}}">
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4" style="margin-bottom: 15px;">
                  <div class="form-group">
                    <label>Country Exist in Dook?</label>
                    <br>
                    <label class="radio-inline">
                      <input type="radio" name="country_exist" value="0" {{ ($countries->country_exist=="0")? "checked" : "" }}>No
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="country_exist" value="1" {{ ($countries->country_exist=="1")? "checked" : "" }}>Yes
                    </label>
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Title</label>
                    <input type="text" class="form-control" name="edit_title" id="edit_title" value="{{$countries->title}}">
                  </div>
                </div>
                <div class="col-md-8 col-lg-8 col-xl-8">
                  <div class="form-group">
                    <label>Sub Title</label>
                    <textarea class="form-control" name="edit_sub_title" id="edit_sub_title">{{$countries->sub_title}}</textarea> 
                  </div>
                </div>
                <div class="col-md-8 col-lg-8 col-xl-8">
                  <div class="form-group">
                    <label>Text 1</label>
                    <textarea class="form-control" name="text_1" id="text_1">{{$countries->text_1}}</textarea>
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Image 1 <span style="color: #9a191e">(W:958, H:894)</span></label>
                    <input type="file" class="form-control" name="image_1" id="image_1" accept="image/jpeg, image/jpg, image/png, image/gif," class="errorBanner image_1" onchange="readURLImage1(this);" />
                  </div>
                  <div class="col-md-2 col-lg-2" id="ifImage_1" style="margin-top: 10px">
                    <?php if($countries->image_1 == null) { ?>
                      <img id="image_1_show" onclick="triggerImage1()" src="{{asset('images/no-image.png')}}" class="" width="300" height="120"/>
                    <?php } else {?>
                      <img id="image_1_show" onclick="triggerImage1()" src="{{generateSignedUrl('country/'.$countries->image_1)}}" class="" width="300" height="180"/>
                    <?php } ?>
                  </div>
                </div>
                <div class="col-md-8 col-lg-8 col-xl-8">
                  <div class="form-group">
                    <label>Text 2</label>
                    <textarea class="form-control" name="text_2" id="text_2">{{$countries->text_2}}</textarea>
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Image 2 <span style="color: #9a191e">(W:958, H:894)</span></label>
                    <input type="file" class="form-control" name="image_2" id="image_2" accept="image/jpeg, image/jpg, image/png, image/gif," class="errorBanner image_2" onchange="readURLImage2(this);" />
                  </div>
                  <div class="col-md-2 col-lg-2" id="ifImage_2" style="margin-top: 10px">
                    <?php if($countries->image_2 == null) { ?>
                      <img id="image_2_show" onclick="triggerImage2()" src="{{asset('images/no-image.png')}}" class="" width="300" height="180"/>
                    <?php } else {?>
                      <img id="image_2_show" onclick="triggerImage2()" src="{{generateSignedUrl('country/'.$countries->image_2)}}" class="" width="300" height="180"/>
                    <?php } ?>
                  </div>
                </div>
                <div class="col-md-8 col-lg-8 col-xl-8">
                  <div class="form-group">
                    <label>Text 3</label>
                    <textarea class="form-control" name="text_3" id="text_3">{{$countries->text_3}}</textarea>
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Image 3 <span style="color: #9a191e">(W:958, H:894)</span></label>
                    <input type="file" class="form-control" name="image_3" id="image_3" accept="image/jpeg, image/jpg, image/png, image/gif," class="errorBanner image_3" onchange="readURLImage3(this);" />
                  </div>
                  <div class="col-md-2 col-lg-2" id="ifImage_3" style="margin-top: 10px">
                    <?php if($countries->image_3 == null) { ?>
                      <img id="image_3_show" onclick="triggerImage3()" src="{{asset('images/no-image.png')}}" class="" width="300" height="180"/>
                    <?php } else {?>
                      <img id="image_3_show" onclick="triggerImage3()" src="{{generateSignedUrl('country/'.$countries->image_3)}}" class="" width="300" height="180"/>
                    <?php } ?>
                  </div>
                </div>
                <div class="col-md-12 col-lg-12 col-xl-12">
                  <div class="form-group">
                    <label>Text 4</label>
                    <textarea class="form-control" name="text_4" id="text_4">{{$countries->text_4}}</textarea>
                  </div>
                </div> 
                <div class="box-body">
                  <div class="col-md-4 col-lg-4" style="margin-top: 5px">
                    <div class="form-group">
                      <label for="exampleInputFile" class="exampleInputFile">Featured Image <span style="color: #9a191e">(W:464, H:260)</span></label>
                      <div class="input-group">
                        <input type="file" name="edit_image" id="edit_image" accept="image/jpeg, image/jpg, image/png, image/gif," class="errorFeatured countryImages edit_image " onchange="readURL(this);">
                        <button type="button" class="btn btn-primary">Choose Images</button>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2 col-lg-2" id="ifImage" style="margin-top: 10px">
                    <?php if($countries->image == null) { ?>
                      <img id="image_show" onclick="triggerImage()" src="{{asset('images/no-image.png')}}" class="" width="80" height="60"/>
                    <?php } else {?>
                      <img id="image_show" onclick="triggerImage()" src="{{generateSignedUrl('country/'.$countries->image)}}" class="" width="80" height="60"/>
                    <?php } ?>
                  </div>
          
                  <div class="col-md-4 col-lg-4" style="margin-top: 5px">
                    <div class="form-group">
                      <label for="exampleInputFile" class="exampleInputFile">Banner Image <span style="color: #9a191e">(W:1920, H:768)</span> </label>
                      <div class="input-group">
                        <input type="file" name="edit_banner_image" id="edit_banner_image" accept="image/jpeg, image/jpg, image/png, image/gif," class="errorBanner countryImages edit_banner_image" onchange="readURLBanner(this);">
                        <button type="button" class="btn btn-primary">Choose Images</button>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2 col-lg-2" id="ifImageBanner" style="margin-top: 20px">
                    <?php if($countries->banner_image == null) { ?>
                      <img id="image_banner_show" onclick="triggerImageBanner()" src="{{asset('images/no-image.png')}}" class="" width="120" height="60"/>
                    <?php } else {?>
                      <img id="image_banner_show" onclick="triggerImageBanner()" src="{{generateSignedUrl('country/'.$countries->banner_image)}}" class="" width="120" height="60"/>
                    <?php } ?>
                  </div>
                   <div class="col-md-4 col-lg-4" style="margin-top: 5px">
                    <div class="form-group">
                      <label for="exampleInputFile" class="exampleInputFile">Mobile Banner Image <span style="color: #9a191e">(W:1920, H:768)</span> </label>
                      <div class="input-group">
                        <input type="file" name="edit_mobile_banner_image" id="edit_banner_image" accept="image/jpeg, image/jpg, image/png, image/gif," class="errorBanner countryImages edit_banner_image" onchange="readURLBanner(this);">
                        <button type="button" class="btn btn-primary">Choose Images</button>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2 col-lg-2" id="ifImageBanner" style="margin-top: 20px">
                    <?php if($countries->mobile_banner_image == null) { ?>
                      <img id="image_banner_show" onclick="triggerImageBanner()" src="{{asset('images/no-image.png')}}" class="" width="120" height="60"/>
                    <?php } else {?>
                      <img id="image_banner_show" onclick="triggerImageBanner()" src="{{generateSignedUrl('country/'.$countries->mobile_banner_image)}}" class="" width="120" height="60"/>
                    <?php } ?>
                  </div>
         
                </div>
                <div class="box-body">
                  <div class="col-md-12 col-lg-12 col-sm-12">
                    <h3>Meta Informations</h3>
                    <hr style="border-bottom: 2px solid #777">
                  </div>  
                  <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label>Meta Title</label> 
                      <textarea class="form-control" name="meta_title" id="meta_title">{{$countries->meta_title}}</textarea>
                    </div>
                  </div>
                  <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label>Meta Keywords</label>
                      <textarea class="form-control" name="meta_keywords" id="meta_keywords">{{$countries->meta_keywords}}</textarea>
                    </div>
                  </div>
                  <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label>Meta Description</label>
                      <textarea class="form-control" name="meta_description" id="meta_description">{{$countries->meta_description}}</textarea>
                    </div>
                  </div>
                </div>
                <div class="box-body" style="margin-top: 30px;">
                  <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left">
                    <button class="btn btn-primary active" type="button" id="country_package_send_form">
                      <span class="crop_text"><i class="fa fa-edit"></i> Update info</span>
                      <span class="crop_wait" style="display: none">                      
                        <i class="fa fa-circle-o-notch fa-spin"></i> Please Wait
                      </span>
                    </button>
                    <span class="text-success" id="message" style="margin-left: 10px"></span>
                  </div> 
                </div>
              </form>
            </div> 

            <!-- About Country Section -->

            <div class="responsiveTab tab-pane fade" id="aboutCountry">
              <form role="form" id="aboutCountryEditForm">
                @csrf
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Country Name</label>
                    <input type="text" class="form-control" name="edit_country" id="edit_country" readonly ="" value="{{$countries->country_name}}">
                    <input type="hidden" class="form-control" name="tour_packages_section" value="ATPS_Checks">
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>About Country Slug URL</label>
                    <input type="text" class="form-control" name="about_country_slug_url" id="about_country_slug_url" value="{{$countries->about_country_slug_url}}">
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4" style="margin-bottom: 15px">
                  <div class="form-group">
                    <label>Visa on Arrival</label>
                    <br>
                    <label class="radio-inline">
                      <input type="radio" name="visa_on_arrival" value="0" {{ ($countries->visa_on_arrival=="0")? "checked" : "" }}>No
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="visa_on_arrival" value="1" {{ ($countries->visa_on_arrival=="1")? "checked" : "" }}>Yes
                    </label>
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Title</label>
                    <input type="text" class="form-control" name="about_title" id="about_title" value="{{$countries->about_title}}">
                  </div>
                </div>
                <div class="col-md-8 col-lg-8 col-xl-8">
                  <div class="form-group">
                    <label>Sub Title</label>
                    <textarea class="form-control" name="about_sub_title" id="about_sub_title">{{$countries->about_sub_title}}</textarea> 
                  </div>
                </div>
                
                <div class="col-md-6 col-lg-6 col-xl-6">
                  <div class="form-group">
                    <label>Administrative And Territorial Structure</label>
                    <textarea class="form-control" name="administrative_territorial" id="administrative_territorial">{{$countries->administrative_territorial}}</textarea>
                  </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-6">
                  <div class="form-group">
                    <label>Land Boundaries</label>
                    <textarea class="form-control" name="land_boundaries" id="land_boundaries">{{$countries->land_boundaries}}</textarea>
                  </div>
                </div>
                <div class="col-md-8 col-lg-8 col-xl-8 col-sm-12 col-xs-8">
                  <div class="form-group">
                    <label>Major Destinations (Cities)</label>
                    <br>
                    <select class="form-control major_destinations" name="major_destinations[]" id="major_destinations" multiple="">
                    @foreach($major_destinations as $major_destination)
                      <option value="{{$major_destination->id}}" 
                        @foreach($selected_destination as $s_destination)
                          @if($s_destination->id == $major_destination->id)
                            selected
                          @endif
                        @endforeach
                        >{{$major_destination->dest_name}}
                      </option>
                    @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-12 col-lg-12 col-xl-12">
                  <div class="form-group">
                    <label>About Country Description</label>
                    <textarea class="form-control" name="about_description" id="about_description">{{$countries->about_description}}</textarea>
                  </div>
                </div> 
                <div class="col-md-12 col-lg-12 col-xl-12">
                  <div class="form-group">
                    <label>Country Tourism Description</label>
                    <textarea class="form-control" name="tourism_description" id="tourism_description">{{$countries->tourism_description}}</textarea>
                  </div>
                </div>  
                <div class="col-md-12 col-lg-12 col-xl-12">
                  <div class="form-group">
                    <label>Country Travel Guide Description</label>
                    <textarea class="form-control" name="guide_description" id="guide_description">{{$countries->guide_description}}</textarea>
                  </div>
                </div>
                <div class="col-md-12 col-lg-12 col-xl-12">
                  <div class="form-group">
                    <label>Country Visa Information Description</label>
                    <textarea class="form-control" name="about_visa_information" id="about_visa_information">{{$countries->about_visa_information}}</textarea>
                  </div>
                </div>
                <div class="box-body">
                  <div class="col-md-4 col-lg-4" style="margin-top: 10px">
                    <div class="form-group">
                      <label for="exampleInputFile" class="exampleInputFile">Banner Image <span style="color: #9a191e">(W:1920, H:768)</span> </label>
                      <div class="input-group">
                        <input type="file" name="edit_banner_image_about" id="edit_banner_image_about" accept="image/jpeg, image/jpg, image/png, image/gif," class="errorBanner countryImages edit_banner_image" onchange="readURLBannerAbout(this);">
                        <button type="button" class="btn btn-primary">Choose Images</button>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2 col-lg-2" id="ifImageBanner" style="margin-top: 20px">
                    <?php if($countries->banner_image_about == null) { ?>
                      <img id="image_banner_about" onclick="triggerImageBannerAbout()" src="{{asset('images/no-image.png')}}" class="" width="120" height="60"/>
                    <?php } else {?>
                      <img id="image_banner_about" onclick="triggerImageBannerAbout()" src="{{generateSignedUrl('country/'.$countries->banner_image_about)}}" class="" width="120" height="60"/>
                    <?php } ?>
                  </div>
                </div>
                <div class="box-body">
                  <div class="col-md-12 col-lg-12 col-sm-12">
                    <h3>Meta Informations</h3>
                    <hr style="border-bottom: 2px solid #777">
                  </div>  
                  <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label>Meta Title</label> 
                      <textarea class="form-control" name="about_meta_title" id="about_meta_title">{{$countries->about_meta_title}}</textarea>
                    </div>
                  </div>
                  <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label>Meta Keywords</label>
                      <textarea class="form-control" name="about_meta_keywords" id="about_meta_keywords">{{$countries->about_meta_keywords}}</textarea>
                    </div>
                  </div>
                  <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label>Meta Description</label>
                      <textarea class="form-control" name="about_meta_description" id="about_meta_description">{{$countries->about_meta_description}}</textarea>
                    </div>
                  </div>
                </div>
                <div class="box-body" style="margin-top: 30px;">
                  <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left">
                    <button class="btn btn-primary active" type="button" id="about_country_send_form">
                      <span class="crop_text_about"><i class="fa fa-edit"></i> Update info</span>
                      <span class="crop_wait_about" style="display: none">                      
                        <i class="fa fa-circle-o-notch fa-spin"></i> Please Wait
                      </span>
                    </button>
                    <span class="text-success" id="message_about" style="margin-left: 10px"></span>
                  </div> 
                </div>
              </form>
            </div>  

            <!-- About Country Section -->

            <div class="responsiveTab tab-pane fade" id="countryAttraction">
              <form role="form" id="countryAttractionEditForm">
                @csrf
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Country Name</label>
                    <input type="text" class="form-control" name="edit_country" id="edit_country" readonly ="" value="{{$countries->country_name}}">
                    <input type="hidden" class="form-control" name="tour_packages_section" value="TTPS_Checks">
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Country Attraction Slug URL</label>
                    <input type="text" class="form-control" name="country_attraction_slug_url" id="country_attraction_slug_url" value="{{$countries->country_attraction_slug_url}}">
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Title</label>
                    <input type="text" class="form-control" name="attraction_title" id="attraction_title" value="{{$countries->attraction_title}}">
                  </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-6">
                  <div class="form-group">
                    <label>Sub Title</label>
                    <textarea class="form-control" name="attraction_sub_title" id="attraction_sub_title">{{$countries->attraction_sub_title}}</textarea> 
                  </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-6">
                  <div class="form-group">
                    <label>Attraction Heading</label>
                    <textarea class="form-control" name="attraction_heading" id="attraction_heading">{{$countries->attraction_heading}}</textarea> 
                  </div>
                </div>
                <div class="col-md-12 col-lg-12 col-xl-12">
                  <div class="form-group">
                    <label>Description</label>
                    <textarea class="form-control" name="attraction_description" id="attraction_description">{{$countries->attraction_description}}</textarea>
                  </div>
                </div>
                <div class="box-body">
                  <div class="col-md-4 col-lg-4" style="margin-top: 10px">
                    <div class="form-group">
                      <label for="exampleInputFile" class="exampleInputFile">Country Attraction Banner Image <span style="color: #9a191e">(W:1920, H:768)</span> </label>
                      <div class="input-group">
                        <input type="file" name="edit_banner_image_attraction" id="edit_banner_image_attraction" accept="image/jpeg, image/jpg, image/png, image/gif," class="errorBanner countryImages edit_banner_image" onchange="readURLBannerAttraction(this);">
                        <button type="button" class="btn btn-primary">Choose Banner Images</button>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2 col-lg-2" id="ifImageBanner" style="margin-top: 10px">
                    <?php if($countries->banner_image_attraction == null) { ?>
                      <img id="image_banner_attraction" onclick="triggerImageBannerAttraction()" src="{{asset('images/no-image.png')}}" class="" width="120" height="60"/>
                    <?php } else {?>
                      <img id="image_banner_attraction" onclick="triggerImageBannerAttraction()" src="{{generateSignedUrl('country/'.$countries->banner_image_attraction)}}" class="" width="120" height="60"/>
                    <?php } ?>
                  </div>
                </div>
                <div class="box-body">
                  <div class="col-md-12 col-lg-12 col-sm-12">
                    <h3>Meta Informations</h3>
                    <hr style="border-bottom: 2px solid #777">
                  </div>  
                  <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label>Meta Title</label> 
                      <textarea class="form-control" name="attraction_meta_title" id="attraction_meta_title">{{$countries->attraction_meta_title}} </textarea>
                    </div>
                  </div>
                  <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label>Meta Keywords</label>
                      <textarea class="form-control" name="attraction_meta_keywords" id="attraction_meta_keywords">{{$countries->attraction_meta_keywords}}</textarea>
                    </div>
                  </div>
                  <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label>Meta Description</label>
                      <textarea class="form-control" name="attraction_meta_description" id="attraction_meta_description">{{$countries->attraction_meta_description}}</textarea>
                    </div>
                  </div>
                </div>
                <div class="box-body" style="margin-top: 30px;">
                  <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left">
                    <button class="btn btn-primary active" type="button" id="country_attraction_send_form">
                      <span class="crop_text_attraction"><i class="fa fa-edit"></i> Update info</span>
                      <span class="crop_wait_attraction" style="display: none">                      
                        <i class="fa fa-circle-o-notch fa-spin"></i> Please Wait
                      </span>
                    </button>
                    <span class="text-success" id="message_attraction" style="margin-left: 10px"></span>
                  </div> 
                </div>
              </form>
            </div>  

            <!-- Country Group Tours Section -->

            <div class="responsiveTab tab-pane fade" id="countryGroupTours">
              <form role="form" id="countryGroupToursEditForm">
                @csrf
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Country Name</label>
                    <input type="text" class="form-control" name="edit_country" id="edit_country" readonly ="" value="{{$countries->country_name}}">
                    <input type="hidden" class="form-control" name="tour_packages_section" value="GTPS_Checks">
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Country Group Tours Slug URL</label>
                    <input type="text" class="form-control" name="country_group_slug_url" id="country_group_slug_url" value="{{$countries->country_group_slug_url}}">
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Title</label>
                    <input type="text" class="form-control" name="group_title" id="group_title" value="{{$countries->group_title}}">
                  </div>
                </div>
                <div class="col-md-8 col-lg-8 col-xl-8">
                  <div class="form-group">
                    <label>Sub Title</label>
                    <textarea class="form-control" name="group_sub_title" id="group_sub_title">{{$countries->group_sub_title}}</textarea> 
                  </div>
                </div>
                <div class="box-body">
                  <div class="col-md-12 col-lg-12 col-xl-12">
                    <div class="form-group">
                      <label>Group Country Description</label>
                      <textarea class="form-control" name="group_description" id="group_description">{{$countries->group_description}}</textarea>
                    </div>
                  </div>
                </div>
                <div class="box-body">
                  <div class="col-md-12 col-lg-12 col-sm-12">
                    <h3 class="exampleInputFile">Country Group Tours Banner Image <span style="color: #9a191e">(W:1920, H:768)</span></h3>
                    <hr style="border-bottom: 2px solid #777">
                  </div>
                  <div class="col-md-4 col-lg-4" style="margin-top: 10px">
                    <div class="form-group">
                      <label for="exampleInputFile" class="exampleInputFile">Country Group Tours Banner Image <span style="color: #9a191e">(W:1920, H:768)</span> </label>
                      <div class="input-group">
                        <input type="file" name="edit_banner_image_group" id="edit_banner_image_group" accept="image/jpeg, image/jpg, image/png, image/gif," class="errorBanner countryImages edit_banner_image" onchange="readURLBannerGroup(this);">
                        <button type="button" class="btn btn-primary">Choose Banner Images</button>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2 col-lg-2" id="ifImageBanner" style="margin-top: 10px">
                    <?php if($countries->edit_banner_image_group == null) { ?>
                      <img id="image_banner_group" onclick="triggerImageBannerGroup()" src="{{asset('images/no-image.png')}}" class="" width="120" height="60"/>
                    <?php } else {?>
                      <img id="image_banner_group" onclick="triggerImageBannerGroup()" src="{{generateSignedUrl('country/'.$countries->edit_banner_image_group)}}" class="" width="120" height="60"/>
                    <?php } ?>
                  </div>
                </div>
                
                <div class="box-body">
                  <div class="col-md-12 col-lg-12 col-sm-12">
                    <h3>Meta Informations</h3>
                    <hr style="border-bottom: 2px solid #777">
                  </div>  
                  <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label>Meta Title</label> 
                      <textarea class="form-control" name="group_meta_title" id="group_meta_title">{{$countries->group_meta_title}} </textarea>
                    </div>
                  </div>
                  <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label>Meta Keywords</label>
                      <textarea class="form-control" name="group_meta_keywords" id="group_meta_keywords">{{$countries->group_meta_keywords}}</textarea>
                    </div>
                  </div>
                  <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label>Meta Description</label>
                      <textarea class="form-control" name="group_meta_description" id="group_meta_description">{{$countries->group_meta_description}}</textarea>
                    </div>
                  </div>
                </div>
                <div class="box-body" style="margin-top: 30px;">
                  <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left">
                    <button class="btn btn-primary active" type="button" id="country_group_send_form">
                      <span class="crop_text_group"><i class="fa fa-edit"></i> Update info</span>
                      <span class="crop_wait_group" style="display: none">                      
                        <i class="fa fa-circle-o-notch fa-spin"></i> Please Wait
                      </span>
                    </button>
                    <span class="text-success" id="message_group" style="margin-left: 10px"></span>
                  </div> 
                </div>
              </form>
            </div> 

            <!-- Country Visa Tours Section -->

            <div class="responsiveTab tab-pane fade" id="countryVisa">
              <form role="form" id="countryVisaToursEditForm">
                @csrf
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Country Name</label>
                    <input type="text" class="form-control" name="edit_country" id="edit_country" readonly ="" value="{{$countries->country_name}}">
                    <input type="hidden" class="form-control" name="tour_packages_section" value="VTPS_Checks">
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Country Visa Tours Slug URL</label>
                    <input type="text" class="form-control" name="country_visa_slug_url" id="country_visa_slug_url" value="{{$countries->country_visa_slug_url}}">
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Title</label>
                    <input type="text" class="form-control" name="visa_title" id="visa_title" value="{{$countries->visa_title}}">
                  </div>
                </div>
                <div class="col-md-8 col-lg-8 col-xl-8">
                  <div class="form-group">
                    <label>Sub Title</label>
                    <textarea class="form-control" name="visa_sub_title" id="visa_sub_title">{{$countries->visa_sub_title}}</textarea> 
                  </div>
                </div>
                <div class="box-body">
                  <div class="col-md-12 col-lg-12 col-xl-12">
                    <div class="form-group">
                      <label>Country Visa Description</label>
                      <textarea class="form-control" name="visa_description" id="visa_description">{{$countries->visa_description}}</textarea>
                    </div>
                  </div>
                </div>
                <div class="box-body">
                  <div class="col-md-12 col-lg-12 col-sm-12">
                    <h3 class="exampleInputFile">Country Visa Banner Image <span style="color: #9a191e">(W:1920, H:768)</span></h3>
                    <hr style="border-bottom: 2px solid #777">
                  </div>
                  <div class="col-md-4 col-lg-4" style="margin-top: 10px">
                    <div class="form-group">
                      <label for="exampleInputFile" class="exampleInputFile">Visa Banner Image </label>
                      <div class="input-group">
                        <input type="file" name="edit_banner_image_visa" id="edit_banner_image_visa" accept="image/jpeg, image/jpg, image/png, image/gif," class="errorBanner countryImages edit_banner_image" onchange="readURLBannerVisa(this);">
                        <button type="button" class="btn btn-primary">Choose Banner Images</button>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2 col-lg-2" id="ifImageBanner" style="margin-top: 10px">
                    <?php if($countries->edit_banner_image_visa == null) { ?>
                      <img id="image_banner_visa" onclick="triggerImageBannerVisa()" src="{{asset('images/no-image.png')}}" class="" width="120" height="60"/>
                    <?php } else {?>
                      <img id="image_banner_visa" onclick="triggerImageBannerVisa()" src="{{generateSignedUrl('country/'.$countries->edit_banner_image_visa)}}" class="" width="120" height="60"/>
                    <?php } ?>
                  </div>
                </div>
                
                <div class="box-body">
                  <div class="col-md-12 col-lg-12 col-sm-12">
                    <h3>Meta Informations</h3>
                    <hr style="border-bottom: 2px solid #777">
                  </div>  
                  <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label>Meta Title</label> 
                      <textarea class="form-control" name="visa_meta_title" id="visa_meta_title">{{$countries->visa_meta_title}} </textarea>
                    </div>
                  </div>
                  <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label>Meta Keywords</label>
                      <textarea class="form-control" name="visa_meta_keywords" id="visa_meta_keywords">{{$countries->visa_meta_keywords}}</textarea>
                    </div>
                  </div>
                  <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label>Meta Description</label>
                      <textarea class="form-control" name="visa_meta_description" id="visa_meta_description">{{$countries->visa_meta_description}}</textarea>
                    </div>
                  </div>
                </div>
                <div class="box-body" style="margin-top: 30px;">
                  <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left">
                    <button class="btn btn-primary active" type="button" id="country_visa_send_form">
                      <span class="crop_text_visa"><i class="fa fa-edit"></i> Update info</span>
                      <span class="crop_wait_visa" style="display: none">                      
                        <i class="fa fa-circle-o-notch fa-spin"></i> Please Wait
                      </span>
                    </button>
                    <span class="text-success" id="message_visa" style="margin-left: 10px"></span>
                  </div> 
                </div>
              </form>
            </div> 

            <!-- Country Visa Tours Section -->

            <div class="responsiveTab tab-pane fade" id="countryExperience">
              <form role="form" id="countryExperienceToursEditForm">
                @csrf
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Country Name</label>
                    <input type="text" class="form-control" name="edit_country" id="edit_country" readonly ="" value="{{$countries->country_name}}">
                    <input type="hidden" class="form-control" name="tour_packages_section" value="ETPS_Checks">
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Country Visa Tours Slug URL</label>
                    <input type="text" class="form-control" name="country_experience_slug_url" id="country_experience_slug_url" value="{{$countries->country_experience_slug_url}}">
                  </div>
                </div>
                <div class="col-md-4 col-lg-4 col-xl-4">
                  <div class="form-group">
                    <label>Title</label>
                    <input type="text" class="form-control" name="experience_title" id="experience_title" value="{{$countries->experience_title}}">
                  </div>
                </div>
                <div class="col-md-8 col-lg-8 col-xl-8">
                  <div class="form-group">
                    <label>Sub Title</label>
                    <textarea class="form-control" name="experience_sub_title" id="experience_sub_title">{{$countries->experience_sub_title}}</textarea> 
                  </div>
                </div>
                <div class="box-body">
                  <div class="col-md-12 col-lg-12 col-xl-12">
                    <div class="form-group">
                      <label>Country experience Description</label>
                      <textarea class="form-control" name="experience_description" id="experience_description">{{$countries->experience_description}}</textarea>
                    </div>
                  </div>
                </div>
                <div class="box-body">
                  <div class="col-md-12 col-lg-12 col-sm-12">
                    <h3 class="exampleInputFile">Country Experience Banner Image <span style="color: #9a191e">(W:1920, H:768)</span></h3>
                    <hr style="border-bottom: 2px solid #777">
                  </div>
                  <div class="col-md-4 col-lg-4" style="margin-top: 10px">
                    <div class="form-group">
                      <label for="exampleInputFile" class="exampleInputFile">Experience Banner Image </label>
                      <div class="input-group">
                        <input type="file" name="edit_banner_image_experience" id="edit_banner_image_experience" accept="image/jpeg, image/jpg, image/png, image/gif," class="errorBanner countryImages edit_banner_image" onchange="readURLBannerExperience(this);">
                        <button type="button" class="btn btn-primary">Choose Banner Images</button>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2 col-lg-2" id="ifImageBannerExperience" style="margin-top: 10px">
                    <?php if($countries->edit_banner_image_experience == null) { ?>
                      <img id="image_banner_experience" onclick="triggerImageBannerExperience()" src="{{asset('images/no-image.png')}}" class="" width="120" height="60"/>
                    <?php } else {?>
                      <img id="image_banner_experience" onclick="triggerImageBannerExperience()" src="{{generateSignedUrl('country/'.$countries->edit_banner_image_experience)}}" class="" width="120" height="60"/>
                    <?php } ?>
                  </div>
                </div>
                
                <div class="box-body">
                  <div class="col-md-12 col-lg-12 col-sm-12">
                    <h3>Meta Informations</h3>
                    <hr style="border-bottom: 2px solid #777">
                  </div>  
                  <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label>Meta Title</label> 
                      <textarea class="form-control" name="experience_meta_title" id="experience_meta_title">{{$countries->experience_meta_title}} </textarea>
                    </div>
                  </div>
                  <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label>Meta Keywords</label>
                      <textarea class="form-control" name="experience_meta_keywords" id="experience_meta_keywords">{{$countries->experience_meta_keywords}}</textarea>
                    </div>
                  </div>
                  <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-group">
                      <label>Meta Description</label>
                      <textarea class="form-control" name="experience_meta_description" id="experience_meta_description">{{$countries->experience_meta_description}}</textarea>
                    </div>
                  </div>
                </div>
                <div class="box-body" style="margin-top: 30px;">
                  <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left">
                    <button class="btn btn-primary active" type="button" id="country_experience_send_form">
                      <span class="crop_text_experience"><i class="fa fa-edit"></i> Update info</span>
                      <span class="crop_wait_experience" style="display: none">                      
                        <i class="fa fa-circle-o-notch fa-spin"></i> Please Wait
                      </span>
                    </button>
                    <span class="text-success" id="message_experience" style="margin-left: 10px"></span>
                  </div> 
                </div>
              </form>
            </div>  
            <!-- Before you go Tours Section -->

            <div class="responsiveTab tab-pane fade" id="beforYouGo">
              <form role="form" id="countrybeforYouGoEditForm">
                @csrf
                <div class="box-body">
                  <div class="col-md-12 col-lg-12 col-xl-12">
                    <div class="form-group">
                      <label style="font-size: 17px;">Before You Go Description</label>
                      <textarea class="form-control" name="before_you_go" id="before_you_go">{{$countries->before_you_go}}</textarea>

                    </div>
                    <input type="hidden" class="form-control" name="tour_packages_section" value="BYGTPS_Checks">
                  </div>
                  <div class="col-md-12" style="display: inline-flex;">
                    <h3>{{$countries->country_name}} Tours</h3>
                    <label class="kt-checkbox all" style="font-size: 18px;color: #bd3131;margin-left: 50px;margin-top: 20px;">
                      <input type="checkbox" id="checkAll" @if(count($departure_bygs)>0) {{'checked'}} @endif style="height: 16px;width: 16px;">
                        Check All
                      <span></span>
                    </label>
                  </div>
                  <div class="col-md-12" style="margin-top:20px">
                    
                  </div>
                  @foreach($departures as $departure)
                  <div class="col-md-4">
                    <div class="form-group">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" class="byg_packages" name="byg_packages[]" value="{{$departure->id}}" @foreach($departure_bygs as $departure_byg) @if ($departure->id == $departure_byg->departure_id) {{'checked'}} @endif  @endforeach> {{$departure->title}}
                        </label>
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>
                <div class="box-body" style="margin-top: 30px;">
                  <div class="col-md-12 col-lg-12 col-sm-12 button-submit text-left">
                    <button class="btn btn-primary active" type="button" id="befor_you_go_send_form">
                      <span class="crop_text_bygo"><i class="fa fa-edit"></i> Update info</span>
                      <span class="crop_wait_bygo" style="display: none">                      
                        <i class="fa fa-circle-o-notch fa-spin"></i> Please Wait
                      </span>
                    </button>
                    <span class="text-success" id="message_bygo" style="margin-left: 10px"></span>
                  </div> 
                </div>
              </form>
            </div> 
          </div>
        </div>
      </div>
    </section>
  </div>
  <style>
    .responsiveTab.tab-pane {margin-top: 30px;}.TabGrid>li>a {color: #fff;background-color: #9a191e;}
    .nav-tabs>li.active>a,.nav-tabs>li.active>a:focus,.nav-tabs>li.active>a:hover{color:#fff;cursor:default;background-color:#0a884e;border:1px solid #0a884e;border-bottom-color:transparent}.nav-tabs>li{margin-right:5px}span.select2-selection.select2-selection--multiple {
    width: 521px;
}
  </style>
@endsection
@section('footerSection')
<script type="text/javascript">
    $("#checkAll").click(function () {
      $('input:checkbox').not(this).prop('checked', this.checked);
   });
 </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
  <script type="text/javascript">
    $(document).ready(function () {
      $('#country_package_send_form').click(function (e) {
        e.preventDefault();
        $(".crop_wait").show();
        $(".crop_text").hide();
        var edit_id = $('#edit_id').val();
        var formDatas = new FormData(document.getElementById('countryPackageEditForm'));
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: "{{ route('countries_update',request()->route('id')) }}",
          data: formDatas,
          contentType: false,
          processData: false,
          success: function (data) {
          $('#message').html("<span class='sussecmsg'>Successfully Update!</span>");
            window.location = data.url;
            //location.reload();
          },
          errors: function () {
            $(".crop_wait").hide();
            $(".crop_text").show();
            $('#message').html("<span class='sussecmsg'>Something went wrong!</span>");
          }

        });
      });

      // Befor You Go
      $('#befor_you_go_send_form').click(function (e) {
        e.preventDefault();
        $(".crop_wait_bygo").show();
        $(".crop_text_bygo").hide();
        var edit_id = $('#edit_id').val();
        var formDatas = new FormData(document.getElementById('countrybeforYouGoEditForm'));
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: "{{ route('countries_update',request()->route('id')) }}",
          data: formDatas,
          contentType: false,
          processData: false,
          success: function (data) {
          $('#message_bygo').html("<span class='sussecmsg'>Successfully Update!</span>");
            window.location = data.url;
            //location.reload();
          },
          errors: function () {
            $(".crop_wait_bygo").hide();
            $(".crop_text_bygo").show();
            $('#message_bygo').html("<span class='sussecmsg'>Something went wrong!</span>");
          }

        });
      });

      // About Country Ajax

      $('#about_country_send_form').click(function (e) {
        e.preventDefault();
        $(".crop_wait_about").show();
        $(".crop_text_about").hide();
        var edit_id = $('#edit_id').val();
        var formDatas = new FormData(document.getElementById('aboutCountryEditForm'));
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: "{{ route('countries_update',request()->route('id')) }}",
          data: formDatas,
          contentType: false,
          processData: false,
          success: function (data) {
          $('#message_about').html("<span class='sussecmsg'>Successfully Update!</span>");
            window.location = data.url;
            //location.reload();
          },
          errors: function () {
            $(".crop_wait_about").hide();
            $(".crop_text_about").show();
            $('#message_about').html("<span class='sussecmsg'>Something went wrong!</span>");
          }

        });
      });

      // Country Attraction Ajax

      $('#country_attraction_send_form').click(function (e) {
        e.preventDefault();
        $(".crop_wait_attraction").show();
        $(".crop_text_attraction").hide();
        var edit_id = $('#edit_id').val();
        var formDatas = new FormData(document.getElementById('countryAttractionEditForm'));
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: "{{ route('countries_update',request()->route('id')) }}",
          data: formDatas,
          contentType: false,
          processData: false,
          success: function (data) {
          $('#message_attraction').html("<span class='sussecmsg'>Successfully Update!</span>");
            window.location = data.url;
            //location.reload();
          },
          errors: function () {
            $(".crop_wait_attraction").hide();
            $(".crop_text_attraction").show();
            $('#message_attraction').html("<span class='sussecmsg'>Something went wrong!</span>");
          }

        });
      });

      // About Country Ajax

      $('#country_group_send_form').click(function (e) {
        e.preventDefault();
        $(".crop_wait_group").show();
        $(".crop_text_group").hide();
        var edit_id = $('#edit_id').val();
        var formDatas = new FormData(document.getElementById('countryGroupToursEditForm'));
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: "{{ route('countries_update',request()->route('id')) }}",
          data: formDatas,
          contentType: false,
          processData: false,
          success: function (data) {
          $('#message_group').html("<span class='sussecmsg'>Successfully Update!</span>");
            window.location = data.url;
            //location.reload();
          },
          errors: function () {
            $(".crop_wait_group").hide();
            $(".crop_text_group").show();
            $('#message_group').html("<span class='sussecmsg'>Something went wrong!</span>");
          }

        });
      });

      // About Country Ajax

      $('#country_visa_send_form').click(function (e) {
        e.preventDefault();
        $(".crop_wait_visa").show();
        $(".crop_text_visa").hide();
        var edit_id = $('#edit_id').val();
        var formDatas = new FormData(document.getElementById('countryVisaToursEditForm'));
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: "{{ route('countries_update',request()->route('id')) }}",
          data: formDatas,
          contentType: false,
          processData: false,
          success: function (data) {
          $('#message_visa').html("<span class='sussecmsg'>Successfully Update!</span>");
            window.location = data.url;
            //location.reload();
          },
          errors: function () {
            $(".crop_wait_visa").hide();
            $(".crop_text_visa").show();
            $('#message_visa').html("<span class='sussecmsg'>Something went wrong!</span>");
          }

        });
      });

      // About Country Ajax

      $('#country_experience_send_form').click(function (e) {
        e.preventDefault();
        $(".crop_wait_experience").show();
        $(".crop_text_experience").hide();
        var edit_id = $('#edit_id').val();
        var formDatas = new FormData(document.getElementById('countryExperienceToursEditForm'));
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          method: 'POST',
          url: "{{ route('countries_update',request()->route('id')) }}",
          data: formDatas,
          contentType: false,
          processData: false,
          success: function (data) {
          $('#message_experience').html("<span class='sussecmsg'>Successfully Update!</span>");
            window.location = data.url;
            //location.reload();
          },
          errors: function () {
            $(".crop_wait_experience").hide();
            $(".crop_text_experience").show();
            $('#message_experience').html("<span class='sussecmsg'>Something went wrong!</span>");
          }

        });
      });
    });
      
  </script>
  <script>
    $('#major_destinations').select2({
      placeholder: 'Search Destination(s)',
    })
    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#image_show')
          .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
    function triggerImage(){
      $('#edit_image').trigger('click');
    }
    //Banner
    function readURLBanner(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#image_banner_show')
          .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
    function triggerImageBanner(){
      $('#edit_banner_image').trigger('click');
    }
    //About Banner1
    function readURLBannerAbout(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#image_banner_about')
          .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
    function triggerImageBannerAbout(){
      $('#edit_banner_image_about').trigger('click');
    }
    //Banner Attraction
    function readURLBannerAttraction(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#image_banner_attraction')
          .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
    function triggerImageBannerAttraction(){
      $('#edit_banner_image_attraction').trigger('click');
    }

    //Banner Group
    function readURLBannerGroup(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#image_banner_group')
          .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
    function triggerImageBannerGroup(){
      $('#edit_banner_image_group').trigger('click');
    }
    //Banner Group
    function readURLBannerVisa(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#image_banner_visa')
          .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
    function triggerImageBannerVisa(){
      $('#edit_banner_image_visa').trigger('click');
    }
    //Banner experience
    function readURLBannerExperience(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#image_banner_experience')
          .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
    function triggerImageBannerExperience(){
      $('#edit_banner_image_experience').trigger('click');
    }
    //Image 1
    function readURLImage1(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#image_1_show')
          .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
    function triggerImage1(){
      $('#image_1').trigger('click');
    }

    //Image 2
    function readURLImage2(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#image_2_show')
          .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
    function triggerImage2(){
      $('#image_2').trigger('click');
    } 

    //Image 3
    function readURLImage3(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#image_3_show')
          .attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
    function triggerImage3(){
      $('#image_3').trigger('click');
    }               
  </script>
  <script>
    $(document).ready(function() {
      $('#text_1').summernote({
          toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview']]
        ],
         callbacks: {
            onPaste: function (e) {
              var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
              var bufferText1 = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
              e.preventDefault();
              var div = $('<div />');
              div.append(bufferText);
              div.find('*').removeAttr('style');
              setTimeout(function () {
              if(bufferText){
                document.execCommand('insertHtml', false, div.html());
              }else{
                document.execCommand('insertText', false, bufferText1);
              }
              }, 10);
            }
          },
        styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        height:250
      });
      $('.note-current-fontsize').css('font-size','16px');
      // $('#description').summernote({
      //   height: 220,
      //   focus: true
      // });
    });

    $(document).ready(function() {
      $('#text_2').summernote({
          toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview']]
        ],
        callbacks: {
            onPaste: function (e) {
              var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
              var bufferText1 = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
              e.preventDefault();
              var div = $('<div />');
              div.append(bufferText);
              div.find('*').removeAttr('style');
              setTimeout(function () {
              if(bufferText){
                document.execCommand('insertHtml', false, div.html());
              }else{
                document.execCommand('insertText', false, bufferText1);
              }
              }, 10);
            }
          },
        styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        height:250
      });
      $('.note-current-fontsize').css('font-size','16px');
      // $('#description').summernote({
      //   height: 220,
      //   focus: true
      // });
    });

    $(document).ready(function() {
      $('#text_3').summernote({
          toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview']]
        ],
        callbacks: {
            onPaste: function (e) {
              var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
              var bufferText1 = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
              e.preventDefault();
              var div = $('<div />');
              div.append(bufferText);
              div.find('*').removeAttr('style');
              setTimeout(function () {
              if(bufferText){
                document.execCommand('insertHtml', false, div.html());
              }else{
                document.execCommand('insertText', false, bufferText1);
              }
              }, 10);
            }
          },
        styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        height:250
      });
      $('.note-current-fontsize').css('font-size','16px');
    });
    $(document).ready(function() {
      $('#text_4').summernote({
          toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview']]
        ],
        callbacks: {
            onPaste: function (e) {
              var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
              var bufferText1 = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
              e.preventDefault();
              var div = $('<div />');
              div.append(bufferText);
              div.find('*').removeAttr('style');
              setTimeout(function () {
              if(bufferText){
                document.execCommand('insertHtml', false, div.html());
              }else{
                document.execCommand('insertText', false, bufferText1);
              }
              }, 10);
            }
          },
        styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        height:250
      });
      $('.note-current-fontsize').css('font-size','16px');
    });

    $(document).ready(function() {
      $('#group_description').summernote({
          toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview']]
        ],
        callbacks: {
            onPaste: function (e) {
              var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
              var bufferText1 = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
              e.preventDefault();
              var div = $('<div />');
              div.append(bufferText);
              div.find('*').removeAttr('style');
              setTimeout(function () {
              if(bufferText){
                document.execCommand('insertHtml', false, div.html());
              }else{
                document.execCommand('insertText', false, bufferText1);
              }
              }, 10);
            }
          },
        styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        height:250
      });
      $('.note-current-fontsize').css('font-size','16px');
      $('#description').summernote({
        height: 220,
        focus: true
      });
    });
    $(document).ready(function() {
      $('#about_description').summernote({
        toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview']]
        ],
         callbacks: {
            onPaste: function (e) {
              var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
              var bufferText1 = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
              e.preventDefault();
              var div = $('<div />');
              div.append(bufferText);
              div.find('*').removeAttr('style');
              setTimeout(function () {
              if(bufferText){
                document.execCommand('insertHtml', false, div.html());
              }else{
                document.execCommand('insertText', false, bufferText1);
              }
              }, 10);
            }
          },
        styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        height:250,
        focus: true
      });
    });
    $(document).ready(function() {
      $('#tourism_description').summernote({
        toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview']]
        ],
        callbacks: {
            onPaste: function (e) {
              var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
              var bufferText1 = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
              e.preventDefault();
              var div = $('<div />');
              div.append(bufferText);
              div.find('*').removeAttr('style');
              setTimeout(function () {
              if(bufferText){
                document.execCommand('insertHtml', false, div.html());
              }else{
                document.execCommand('insertText', false, bufferText1);
              }
              }, 10);
            }
          },
        styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        height:250,
        focus: true
      });
    });
    $(document).ready(function() {
      $('#guide_description').summernote({
        toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview']]
        ],
        callbacks: {
            onPaste: function (e) {
              var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
              var bufferText1 = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
              e.preventDefault();
              var div = $('<div />');
              div.append(bufferText);
              div.find('*').removeAttr('style');
              setTimeout(function () {
              if(bufferText){
                document.execCommand('insertHtml', false, div.html());
              }else{
                document.execCommand('insertText', false, bufferText1);
              }
              }, 10);
            }
          },
        styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        height:250,
        focus: true
      });
    });
    $(document).ready(function() {
      $('#about_visa_information').summernote({
        toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview']]
        ],
        callbacks: {
            onPaste: function (e) {
              var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
              var bufferText1 = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
              e.preventDefault();
              var div = $('<div />');
              div.append(bufferText);
              div.find('*').removeAttr('style');
              setTimeout(function () {
              if(bufferText){
                document.execCommand('insertHtml', false, div.html());
              }else{
                document.execCommand('insertText', false, bufferText1);
              }
              }, 10);
            }
          },
        styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        height:250,
        focus: true
      });
    });
    $(document).ready(function() {
      $('#land_boundaries').summernote({
        toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview']]
        ],
        callbacks: {
            onPaste: function (e) {
              var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
              var bufferText1 = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
              e.preventDefault();
              var div = $('<div />');
              div.append(bufferText);
              div.find('*').removeAttr('style');
              setTimeout(function () {
              if(bufferText){
                document.execCommand('insertHtml', false, div.html());
              }else{
                document.execCommand('insertText', false, bufferText1);
              }
              }, 10);
            }
          },
        styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        height:100,
        focus: true
      });
    });
    $(document).ready(function() {
      $('#administrative_territorial').summernote({
        toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview']]
        ],
        callbacks: {
            onPaste: function (e) {
              var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
              var bufferText1 = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
              e.preventDefault();
              var div = $('<div />');
              div.append(bufferText);
              div.find('*').removeAttr('style');
              setTimeout(function () {
              if(bufferText){
                document.execCommand('insertHtml', false, div.html());
              }else{
                document.execCommand('insertText', false, bufferText1);
              }
              }, 10);
            }
          },
        styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        height:100,
        focus: true
      });
    });
    $(document).ready(function() {
      $('#visa_description').summernote({
          toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview']]
        ],
        callbacks: {
            onPaste: function (e) {
              var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
              var bufferText1 = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
              e.preventDefault();
              var div = $('<div />');
              div.append(bufferText);
              div.find('*').removeAttr('style');
              setTimeout(function () {
              if(bufferText){
                document.execCommand('insertHtml', false, div.html());
              }else{
                document.execCommand('insertText', false, bufferText1);
              }
              }, 10);
            }
          },
        styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        height:250
      });
      $('.note-current-fontsize').css('font-size','16px');
      $('#description').summernote({
        height: 220,
        focus: true
      });
    });
    $(document).ready(function() {
      $('#experience_description').summernote({
          toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview']]
        ],
        callbacks: {
            onPaste: function (e) {
              var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
              var bufferText1 = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
              e.preventDefault();
              var div = $('<div />');
              div.append(bufferText);
              div.find('*').removeAttr('style');
              setTimeout(function () {
              if(bufferText){
                document.execCommand('insertHtml', false, div.html());
              }else{
                document.execCommand('insertText', false, bufferText1);
              }
              }, 10);
            }
          },
        styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        height:250
      });
      $('.note-current-fontsize').css('font-size','16px');
      $('#description').summernote({
        height: 220,
        focus: true
      });
    });
    //Before you go
    $(document).ready(function() {
      $('#before_you_go').summernote({
          toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview']]
        ],
        callbacks: {
            onPaste: function (e) {
              var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
              var bufferText1 = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
              e.preventDefault();
              var div = $('<div />');
              div.append(bufferText);
              div.find('*').removeAttr('style');
              setTimeout(function () {
              if(bufferText){
                document.execCommand('insertHtml', false, div.html());
              }else{
                document.execCommand('insertText', false, bufferText1);
              }
              }, 10);
            }
          },
        styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        height:250
      });
    });

    $(document).ready(function() {
      $('#attraction_description').summernote({
        toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            // ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'picture']],
            ['view', ['codeview']]
        ],
        callbacks: {
            onPaste: function (e) {
              var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('text/html');
              var bufferText1 = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
              e.preventDefault();
              var div = $('<div />');
              div.append(bufferText);
              div.find('*').removeAttr('style');
              setTimeout(function () {
              if(bufferText){
                document.execCommand('insertHtml', false, div.html());
              }else{
                document.execCommand('insertText', false, bufferText1);
              }
              }, 10);
            }
          },
        styleTags: ['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
        height:180,
        focus: true
      });
    });
  </script>
@endsection