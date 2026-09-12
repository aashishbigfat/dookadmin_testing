@extends('layouts.apps')
@section('headSection')
@section('title', 'Home Settings')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('css/customCSS/pages.css')}}">
@endsection
@section('main-content')
<div class="content-wrapper">
    <section class="content-header">
      <h1>Home Settings</h1>
      <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Home Settings</li>
      </ol>
    </section>
    <hr class="TopHeaderBorder" style="border-bottom: 2px solid #777">
    <section class="content"> 
      <div class="box-body" style="margin-top: -23px;">
          <form role="form" id="homeBannerSetingPage">
          @csrf
            <div class="row">
              <input type="hidden" name="data_id" value="{{$settings->id}}">
              <div class="col-md-12" style="margin-bottom1:15px;"><h3 style="border-bottom: 2px solid #645552;">Slider Section</h3></div>
              <div class="col-md-6 sss">
               <div class="form-group edit_dest">
                 <label>Banner Title</label> <span class="validationError" id="slider_error"></span><br>
                 <input class="form-control" type="text" name="banner_title" id="banner_title" value="{{$settings->banner_title}}">
               </div>
              </div>

              <div class="col-md-6 sss">
               <div class="form-group edit_dest">
                 <label>Banner Subtitle</label>
                 <input class="form-control" type="text" name="banner_sub_title" id="banner_sub_title" value="{{$settings->banner_sub_title}}">
               </div>
              </div>
             
             <div class="col-md-3 sss">
               <div class="form-group edit_dest">
                 <label>Counter1</label>
                 <input class="form-control" type="text" name="counter1" id="counter1" value="{{$settings->counter1}}">
               </div>
               <div class="form-group edit_dest">
                 <label>Text</label>
                 <input class="form-control" type="text" name="text1" id="text1" value="{{$settings->text1}}">
               </div>
              </div>
              <div class="col-md-3 sss">
               <div class="form-group edit_dest">
                 <label>Counter2</label>
                 <input class="form-control" type="text" name="counter2" id="counter2" value="{{$settings->counter2}}">
               </div>
               <div class="form-group edit_dest">
                 <label>Text</label>
                 <input class="form-control" type="text" name="text2" id="text2" value="{{$settings->text2}}">
               </div>
             </div>

             <div class="col-md-3 sss">
               <div class="form-group edit_dest">
                 <label>Counter3</label> <span class="validationError" id="slider_sub_error"></span><br>
                 <input class="form-control" type="text" name="counter3" id="counter3" value="{{$settings->counter3}}">
               </div>
               <div class="form-group edit_dest">
                 <label>Text</label>
                 <input class="form-control" type="text" name="text3" id="text3" value="{{$settings->text3}}">
               </div>
              </div>
              <div class="col-md-3 sss">
               <div class="form-group edit_dest">
                 <label>Counter4</label> <span class="validationError" id="slider_sub_error"></span><br>
                 <input class="form-control" type="text" name="counter4" id="counter4" value="{{$settings->counter4}}">
               </div>
               <div class="form-group edit_dest">
                 <label>Text</label>
                 <input class="form-control" type="text" name="text4" id="text4" value="{{$settings->text4}}">
               </div>
              </div>
               <div class="col-md-3">
                <div class="form-group">
                  <label>Size(1920px*768px)</label>
                  <div class="input-group">
                    <input type="file" name="slider_image" id="uploadFile" accept="image/jpeg, image/jpg, image/png," onchange="readURL(this);">
                    <button type="button" class="btn btn-primary">Choose Banner Image</button>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                @if($settings->image == "")
                  <img id="blah" onclick="triggerImage()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="130" height="60"/>
                @else
                  <img id="blah" onclick="triggerImage()" src="{{generateSignedUrl('home/'.$settings->image)}}" class="fetured_images_view" width="130" height="60"/>
                @endif
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Size(500px*1000px)</label>
                  <div class="input-group">
                    <input type="file" name="mobile_image" id="uploadFileM" accept="image/jpeg, image/jpg, image/png," onchange="readURLM(this);">
                    <button type="button" class="btn btn-primary">Choose Mobile Banner</button>
                  </div>
                </div>
              </div>
              <div class="col-md-3" style="margin-bottom: 40px;">
                @if($settings->mobile_image == "")
                  <img id="blahM" onclick="triggerImageM()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="40" height="80"/>
                @else
                  <img id="blahM" onclick="triggerImageM()" src="{{generateSignedUrl('home/'.$settings->mobile_image)}}" class="fetured_images_view" width="40" height="80"/>
                @endif
              </div>
            </div>
            <div class="row">
              <div class="col-md-12" style="margin-top: 24px;">
               <button class="btn btn-danger active mt-2" type="button" id="banner_store_form"><i class="fa fa-save"></i> Update Banner</button>
              <span class="text-success" id="mesegese1" style="margin-left: 10px"></span>
              </div>
            </div>
          </form>
          <form role="form" id="homeExperienceSetingPage">
            @csrf
            <!-- experiences packages -->
            <div class="row">
              <input type="hidden" name="data_id" value="{{$settings->id}}">
              <div class="col-md-12" style="margin-bottom1:15px;"><h3 style="border-bottom: 2px solid #645552;">Experiences Section</h3></div>
              {{--<div class="col-md-12">
                <div class="form-group">
                <label style="margin-bottom: 10px">Experiences (Honeymoon) Packages</label><span class="validationError" id="experiences_error"></span>
                  <select class="form-control experiences" name="experiences[]" id="experiences" multiple>
                    <option value="">Select Honeymoon Packages</option>
                    @foreach($honeymonnPkg as $value)
                      <option value="{{$value->id}}" @if(in_array($value->id , $pkg_array)) selected @endif>{{$value->title}}</option>
                    @endforeach
                  </select>
                </div>
              </div> --}}
            </div>
            <!-- experiences packages End-->
            <div class="row">
              <div class="col-md-6" style="border-right: 1px solid #b13c3c;">
                <h3 style="margin-bottom: 10px;border-bottom: 2px solid gray;">Experiences</h3>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                    <label style="margin-bottom: 10px">Experience Grid 1</label><span class="validationError" id="experience_error"></span>
                      <select class="form-control experience" name="experience1" id="experience1">
                      <option value="">Select Experience</option>
                      @foreach($experiences as $experience)
                        <option value="{{$experience->id}}" @if($experience->id == $settings->experience1) selected @endif>{{$experience->experience_name}}</option>
                      @endforeach
                    </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label> Size(300px*300px)</label>
                      <div class="input-group">
                        <input type="file" name="exp_image1" id="uploadFileE1" class="uploadFile" accept="image/jpeg, image/jpg, image/png," onchange="readURLE1(this);">
                        <button type="button" class="btn btn-primary">Choose Exp. Image</button>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    @if($settings->exp_image1 == "")
                     <img id="blahE1" onclick="triggerImageE1()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="60" height="60"/>
                      @else
                    <img id="blahE1" onclick="triggerImageE1()" src="{{generateSignedUrl('home/'.$settings->exp_image1)}}" class="fetured_images_view" width="60" height="60"/>
                   @endif
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                    <label style="margin-bottom: 10px">Experience Grid 2</label>
                      <select class="form-control experience" name="experience2" id="experience2">
                      <option value="">Select Experience</option>
                      @foreach($experiences as $experience)
                        <option value="{{$experience->id}}" @if($experience->id == $settings->experience2) selected @endif>{{$experience->experience_name}}</option>
                      @endforeach
                    </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label> Size(300px*300px)</label>
                      <div class="input-group">
                        <input type="file" name="exp_image2" id="uploadFileE2" class="uploadFile" accept="image/jpeg, image/jpg, image/png," onchange="readURLE2(this);">
                        <button type="button" class="btn btn-primary">Choose Exp. Image</button>
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-md-3">
                   @if($settings->exp_image2 == "")
                     <img id="blahE2" onclick="triggerImageE2()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="60" height="60"/>
                     @else
                     <img id="blahE2" onclick="triggerImageE2()" src="{{generateSignedUrl('home/'.$settings->exp_image2)}}" class="fetured_images_view" width="60" height="60"/>
                   @endif
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                    <label style="margin-bottom: 10px">Experience Grid 3</label>
                      <select class="form-control experience" name="experience3" id="experience3">
                      <option value="">Select Experience</option>
                      @foreach($experiences as $experience)
                        <option value="{{$experience->id}}" @if($experience->id == $settings->experience3) selected @endif>{{$experience->experience_name}}</option>
                      @endforeach
                    </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label> Size(300px*300px)</label>
                      <div class="input-group">
                        <input type="file" name="exp_image3" id="uploadFileE3" class="uploadFile" accept="image/jpeg, image/jpg, image/png," onchange="readURLE3(this);">
                        <button type="button" class="btn btn-primary">Choose Exp. Image</button>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                  @if($settings->exp_image3 == "")
                     <img id="blahE3" onclick="triggerImageE3()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="60" height="60"/>
                   @else
                    <img id="blahE3" onclick="triggerImageE3()" src="{{generateSignedUrl('home/'.$settings->exp_image3)}}" class="fetured_images_view" width="60" height="60"/>
                   @endif
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                    <label style="margin-bottom: 10px">Experience Grid 4</label>
                      <select class="form-control experience" name="experience4" id="experience4">
                      <option value="">Select Experience</option>
                      @foreach($experiences as $experience)
                        <option value="{{$experience->id}}" @if($experience->id == $settings->experience4) selected @endif>{{$experience->experience_name}}</option>
                      @endforeach
                    </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label> Size(300px*300px)</label>
                      <div class="input-group">
                        <input type="file" name="exp_image4" id="uploadFileE4" class="uploadFile" accept="image/jpeg, image/jpg, image/png," onchange="readURLE4(this);">
                        <button type="button" class="btn btn-primary">Choose Exp. Image</button>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                  @if($settings->exp_image4 == "")
                     <img id="blahE4" onclick="triggerImageE4()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="60" height="60"/>
                   @else
                    <img id="blahE4" onclick="triggerImageE4()" src="{{generateSignedUrl('home/'.$settings->exp_image4)}}" class="fetured_images_view" width="60" height="60"/>
                   @endif
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                    <label style="margin-bottom: 10px">Experience Grid 5</label>
                      <select class="form-control experience" name="experience4" id="experience4">
                      <option value="">Select Experience</option>
                      @foreach($experiences as $experience)
                        <option value="{{$experience->id}}" @if($experience->id == $settings->experience5) selected @endif>{{$experience->experience_name}}</option>
                      @endforeach
                    </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label> Size(300px*300px)</label>
                      <div class="input-group">
                        <input type="file" name="exp_image5" id="uploadFileE4" class="uploadFile" accept="image/jpeg, image/jpg, image/png," onchange="readURLE4(this);">
                        <button type="button" class="btn btn-primary">Choose Exp. Image</button>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                  @if($settings->exp_image5 == "")
                     <img id="blahE4" onclick="triggerImageE4()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="60" height="60"/>
                   @else
                    <img id="blahE4" onclick="triggerImageE4()" src="{{generateSignedUrl('home/'.$settings->exp_image5)}}" class="fetured_images_view" width="60" height="60"/>
                   @endif
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                    </div>
                  </div>
                  <div class="col-md-3">
                  </div>
                  <div class="col-md-3">
                  </div>
                </div>
              </div>
              
              <div class="col-md-6">
                <h3 style="margin-bottom: 10px;border-bottom: 2px solid gray;">Honeymoon Packages</h3>
                @foreach($fivePkgExp as $key => $value)
                <div class="col-md-6">
                  <input type="hidden" name="pkg_id[]" value="{{$value->id}}">
                  <div class="form-group">
                  <label style="margin-bottom: 10px">Honeymoon Packages</label><span class="validationError" id="experiences_error"></span>
                    <select class="form-control experiences" name="packages[]" id="packages{{$value->id}}">
                      <option value="">Select Honeymoon Package</option>
                      @foreach($honeymonnPkg as $values)
                        <option value="{{$values->id}}" @if($values->id == $value->package) selected @endif>{{$values->title}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label> Size(560px*560px)</label>
                    <div class="input-group">
                      <input type="file" name="package_image[]" id="package_image{{$value->id}}" class="uploadFile" accept="image/jpeg, image/jpg, image/png," onchange="readURLP{{$value->id}}(this);">
                      <button type="button" class="btn btn-primary">Choose Image</button>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  @if($value->package_image == "")
                    <img id="blahP{{$value->id}}" onclick="triggerImageP{{$value->id}}()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="60" height="60"/>
                  @else
                    <img id="blahP{{$value->id}}" onclick="triggerImageP{{$value->id}}()" src="{{generateSignedUrl('home/'.$value->package_image)}}" class="fetured_images_view" width="60" height="60"/>
                  @endif
                </div>
                @endforeach
              </div>
              
            </div>
            <div class="row">
              <div class="col-md-12" style="margin-top: 24px;">
               <button class="btn btn-danger active mt-2" type="button" id="experience_store_form"><i class="fa fa-save"></i> Update Experiences</button>
              <span class="text-success" id="mesegese2" style="margin-left: 10px"></span>
              </div>
            </div>
          </form>
          <form role="form" id="homeCountrySetingPage">
            @csrf
            <div class="row">
              <input type="hidden" name="data_id" value="{{$settings->id}}">
              <div class="col-md-12" style="margin-bottom1:15px;"><h3 style="border-bottom: 2px solid #645552;">Countries Section</h3></div>
            </div>
            <div class="row" style="margin-top: 10px;">
              <div class="col-md-3">
                <div class="form-group">
                <label style="margin-bottom: 10px">Country Grid 1</label><span class="validationError" id="country_error"></span>
                  <select class="form-control countries" name="country1" id="countries1">
                  <option value="">Select Countries</option>
                  @foreach($countries as $value)
                    <option value="{{$value->id}}" @if($value->id == $settings->country1) selected @endif>{{$value->country_name}}</option>
                  @endforeach
                </select>
                </div>
              </div>
              <div class="col-md-3">
              <div class="form-group edit_dest">
                 <label>Label Name</label>
                 <input class="form-control" type="text" name="label1" id="label1" value="{{$settings->label1}}">
              </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label> Size(500px*500px)</label>
                  <div class="input-group">
                    <input type="file" name="country_image1" id="uploadFile1" class="uploadFile" accept="image/jpeg, image/jpg, image/png," onchange="readURL1(this);">
                    <button type="button" class="btn btn-primary">Choose Country Image</button>
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                 @if($settings->country_image1 == "")
                 <img id="blah1" onclick="triggerImage1()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="60" height="60"/>
              
                 @else
                  <img id="blah1" onclick="triggerImage1()" src="{{generateSignedUrl('home/'.$settings->country_image1)}}" class="fetured_images_view" width="60" height="60"/>
                 @endif
              </div>
              
            </div>
            <div class="row" style="margin-top: 10px;">
              <div class="col-md-3">
                <div class="form-group">
                <label style="margin-bottom: 10px">Country Grid 2</label>
                  <select class="form-control countries" name="country2" id="countries2">
                  <option value="">Select Countries</option>
                  @foreach($countries as $value)
                    <option value="{{$value->id}}" @if($value->id == $settings->country2) selected @endif>{{$value->country_name}}</option>
                  @endforeach
                </select>
                </div>
              </div>
              <div class="col-md-3">
              <div class="form-group edit_dest">
                 <label>Label Name</label>
                 <input class="form-control" type="text" name="label2" id="label2" value="{{$settings->label2}}">
              </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label> Size(300px*300px)</label>
                  <div class="input-group">
                    <input type="file" name="country_image2" id="uploadFile2" class="uploadFile" accept="image/jpeg, image/jpg, image/png," onchange="readURL2(this);">
                    <button type="button" class="btn btn-primary">Choose Country Image</button>
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                 @if($settings->country_image2 == "")
                 <img id="blah2" onclick="triggerImage2()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="60" height="60"/>
              
                 @else
                  <img id="blah2" onclick="triggerImage2()" src="{{generateSignedUrl('home/'.$settings->country_image2)}}" class="fetured_images_view" width="60" height="60"/>
                 @endif
              </div>
              
            </div>
            <div class="row" style="margin-top: 10px;">
              <div class="col-md-3">
                <div class="form-group">
                <label style="margin-bottom: 10px">Country Grid 3</label>
                  <select class="form-control countries" name="country3" id="countries3">
                  <option value="">Select Countries</option>
                  @foreach($countries as $value)
                    <option value="{{$value->id}}" @if($value->id == $settings->country3) selected @endif>{{$value->country_name}}</option>
                  @endforeach
                </select>
                </div>
              </div>
              <div class="col-md-3">
              <div class="form-group edit_dest">
                 <label>Label Name</label>
                 <input class="form-control" type="text" name="label3" id="label3" value="{{$settings->label3}}">
              </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label> Size(300px*300px)</label>
                  <div class="input-group">
                    <input type="file" name="country_image3" id="uploadFile3" class="uploadFile" accept="image/jpeg, image/jpg, image/png," onchange="readURL3(this);">
                    <button type="button" class="btn btn-primary">Choose Country Image</button>
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                 @if($settings->country_image3 == "")
                 <img id="blah3" onclick="triggerImage3()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="60" height="60"/>
              
                 @else
                  <img id="blah3" onclick="triggerImage3()" src="{{generateSignedUrl('home/'.$settings->country_image3)}}" class="fetured_images_view" width="60" height="60"/>
                 @endif
              </div>
              
            </div>
            <div class="row" style="margin-top: 10px;">
              <div class="col-md-3">
                <div class="form-group">
                <label style="margin-bottom: 10px">Country Grid 4</label>
                  <select class="form-control countries" name="country4" id="countries4">
                  <option value="">Select Countries</option>
                  @foreach($countries as $value)
                    <option value="{{$value->id}}" @if($value->id == $settings->country4) selected @endif>{{$value->country_name}}</option>
                  @endforeach
                </select>
                </div>
              </div>
              <div class="col-md-3">
              <div class="form-group edit_dest">
                 <label>Label Name</label>
                 <input class="form-control" type="text" name="label4" id="label4" value="{{$settings->label4}}">
              </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label> Size(300px*300px)</label>
                  <div class="input-group">
                    <input type="file" name="country_image4" id="uploadFile4" class="uploadFile" accept="image/jpeg, image/jpg, image/png," onchange="readURL4(this);">
                    <button type="button" class="btn btn-primary">Choose Country Image</button>
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                 @if($settings->country_image4 == "")
                 <img id="blah4" onclick="triggerImage4()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="60" height="60"/>
              
                 @else
                  <img id="blah4" onclick="triggerImage4()" src="{{generateSignedUrl('home/'.$settings->country_image4)}}" class="fetured_images_view" width="60" height="60"/>
                 @endif
              </div>
              
            </div>
            <div class="row" style="margin-top: 10px;">
              <div class="col-md-3">
                <div class="form-group">
                <label style="margin-bottom: 10px">Country Grid 5</label>
                  <select class="form-control countries" name="country5" id="countries5">
                  <option value="">Select Countries</option>
                  @foreach($countries as $value)
                    <option value="{{$value->id}}" @if($value->id == $settings->country5) selected @endif>{{$value->country_name}}</option>
                  @endforeach
                </select>
                </div>
              </div>
              <div class="col-md-3">
              <div class="form-group edit_dest">
                 <label>Label Name</label>
                 <input class="form-control" type="text" name="label5" id="label5" value="{{$settings->label5}}">
              </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label> Size(300px*300px)</label>
                  <div class="input-group">
                    <input type="file" name="country_image5" id="uploadFile5" class="uploadFile" accept="image/jpeg, image/jpg, image/png," onchange="readURL5(this);">
                    <button type="button" class="btn btn-primary">Choose Country Image</button>
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                 @if($settings->country_image5 == "")
                 <img id="blah5" onclick="triggerImage5()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="60" height="60"/>
              
                 @else
                  <img id="blah5" onclick="triggerImage5()" src="{{generateSignedUrl('home/'.$settings->country_image5)}}" class="fetured_images_view" width="60" height="60"/>
                 @endif
              </div>
              
            </div>
            <div class="row">
              <div class="col-md-12" style="margin-top: 24px;">
               <button class="btn btn-danger active mt-2" type="button" id="country_store_form"><i class="fa fa-save"></i> Update Countries</button>
              <span class="text-success" id="mesegese3" style="margin-left: 10px"></span>
              </div>
            </div>
          </form>
          <form role="form" id="homeActivitySetingPage">
            @csrf
            <div class="row">
              <input type="hidden" name="data_id" value="{{$settings->id}}">
              <div class="col-md-12" style="margin-bottom1:15px;"><h3 style="border-bottom: 2px solid #645552;">Activities Section</h3></div>
              <div class="col-md-3">
               <div class="form-group edit_dest">
                 <label>Title</label>
                 <input class="form-control" type="text" name="act_heading" id="act_heading" value="{{$settings->act_heading}}">
               </div>
             </div>
             <div class="col-md-5 sss">
               <div class="form-group edit_dest">
                 <label>Subtitle</label>
                 <input class="form-control" type="text" name="act_sub_heading" id="act_sub_heading" value="{{$settings->act_sub_heading}}">
               </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label> Size(1200px*300px)</label>
                  <div class="input-group">
                    <input type="file" name="activity_banner" id="activityUploadBanner" class="uploadFile" accept="image/jpeg, image/jpg, image/png," onchange="readURLAB(this);">
                    <button type="button" class="btn btn-primary">Choose Activity Image</button>
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                @if($settings->activity_banner == "")
                <img id="blahActB" onclick="triggerImageAB()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="100" height="70"/>
                @else
                  <img id="blahActB" onclick="triggerImageAB()" src="{{generateSignedUrl('home/'.$settings->activity_banner)}}" class="fetured_images_view" width="130" height="60"/>
                @endif
                 
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                <label style="margin-bottom: 10px">Activity</label><span class="validationError" id="activity_error"></span>
                  <select class="form-control activities" name="activity" id="activities">
                  </select>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label> Size(460px*260px)</label>
                  <div class="input-group">
                    <input type="file" name="activity_image" id="activityUpload" class="uploadFile" accept="image/jpeg, image/jpg, image/png," onchange="readURLA(this);">
                    <button type="button" class="btn btn-primary">Choose Activity Image</button>
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                 <img id="blahAct" onclick="triggerImageA()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="100" height="60"/>
              </div>
              <div class="col-md-4" style="margin-top: 24px;">
               <button class="btn btn-danger active mt-2" type="button" id="activity_store_form"><i class="fa fa-save"></i> Add Activity</button>
              <span class="text-success" id="mesegese6" style="margin-left: 10px"></span>
              </div>
            </div>
          </form>
          
          <div class="col-md-12" style="margin-bottom1:15px;"><h3 style="border-bottom: 2px solid #645552;">Activities List</h3></div>
          <div class="TopActivityListing" id="TopActivityListing">
          <div class="box-body">
            <table id="activityeListData" class="table table-bordered">
              <thead>
                <tr>
                  <th style="width: 3px;">S.No</th>
                  <th>Image</th>
                  <th>Activity</th>
                  <th style="width: 5%">Delete</th>
                  <th style="width: 5%">Choose Image</th>
                </tr>
              </thead>
                <tbody class="row_position" id="dynamicRefresingActivity">
                @if(count($activities)> 0 )
                  @foreach( $activities as $key => $activity )
                    <tr id="{{ $activity->id }}">
                      <form role="form" id="homeActivitySetingPageUpdate{{$activity->id}}">
                      @csrf
                      <input type="hidden" name="act_id" id="act_id{{$activity->id}}" value="{{$activity->id}}">
                      <td>{{ $loop->index +1 }}</td>
                      <td><img src="{{generateSignedUrl('home/'.$activity->image)}}" style="width: 30px;"></td>
                      <td>{{$activity->name}}</td>
                      <td>
                        <a class="popularAct" onclick="myFunctionDelete({{$activity->id}})" title="Delete Activity?" style="cursor: pointer;">
                          <i class="fa fa-trash" style="color: #d72c20;"></i>
                        </a>
                      </td>
                      <td>
                        <div class="form-group RR"><div class="input-group"><input type="file" name="activityImg" id="activityImg{{$activity->id}}" class="uploadFile" accept="image/jpeg, image/jpg, image/png," onchange="readURLAct{{$activity->id}}(this);"><button type="button" class="btn btn-primary">Choose Image</button></div></div>
                      </td>
                      <td>
                        <img id="blahAct{{$activity->id}}" onclick="triggerImageAct{{$activity->id}}()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="30" height="15"/>
                      </td>
                      <td><button class="btn btn-danger active mt-2" id="activity_update_form{{$activity->id}}" onclick="myFunction({{$activity->id}})"><i class="fa fa-save"></i> Update</button></td>
                    </form>
                    </tr>
                  @endforeach
                @endif
              </tbody>
            </table>
          </div>
          </div>
          <form role="form" id="homeRegionsSetingPage">
            @csrf
            <div class="row">
              <input type="hidden" name="data_id" value="{{$settings->id}}">
              <div class="col-md-12" style="margin-bottom1:15px;"><h3 style="border-bottom: 2px solid #645552;">Regions Section</h3></div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                <label style="margin-bottom: 10px">Region Grid 1</label><span class="validationError" id="region1_error"></span>
                  <select class="form-control region" name="region1" id="region1">
                  <option value="">Select Region</option>
                  @foreach($regions as $region)
                    <option value="{{$region->id}}" @if($region->id == $settings->region1) selected @endif>{{$region->region_name}}</option>
                  @endforeach
                </select>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <label>Region Description</label>
                  <textarea class="form-control" name="region_des1" id="region_des1"  maxlength="70">{{$settings->region_des1}}</textarea>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label> Size (230px*330px)</label>
                  <div class="input-group">
                    <input type="file" name="region_image1" id="uploadFileA1" class="uploadFile" accept="image/jpeg, image/jpg, image/png," onchange="readURLR1(this);">
                    <button type="button" class="btn btn-primary">Choose Image</button>
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                @if($settings->region_image1 == "")
                 <img id="blahR1" onclick="triggerImageR1()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="60" height="70"/>
                  @else
                <img id="blahR1" onclick="triggerImageR1()" src="{{generateSignedUrl('home/'.$settings->region_image1)}}" class="fetured_images_view" width="60" height="70"/>
               @endif
              </div>
              
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                <label style="margin-bottom: 10px">Region Grid 2</label><span class="validationError" id="region_error"></span>
                  <select class="form-control region" name="region2" id="region2">
                  <option value="">Select Region</option>
                  @foreach($regions as $region)
                    <option value="{{$region->id}}" @if($region->id == $settings->region2) selected @endif>{{$region->region_name}}</option>
                  @endforeach
                </select>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <label>Activity Description</label>
                  <textarea class="form-control" name="region_des2" id="region_des2"  maxlength="70">{{$settings->region_des2}}</textarea>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label> Size (230px*330px)</label>
                  <div class="input-group">
                    <input type="file" name="region_image2" id="uploadFileR2" class="uploadFile" accept="image/jpeg, image/jpg, image/png," onchange="readURLR2(this);">
                    <button type="button" class="btn btn-primary">Choose Image</button>
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                @if($settings->region_image2 == "")
                 <img id="blahR2" onclick="triggerImageR2()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="60" height="70"/>
                  @else
                <img id="blahR2" onclick="triggerImageR2()" src="{{generateSignedUrl('home/'.$settings->region_image2)}}" class="fetured_images_view" width="60" height="70"/>
               @endif
              </div>
              
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                <label style="margin-bottom: 10px">Region Grid 3</label><span class="validationError" id="region_error"></span>
                  <select class="form-control region" name="region3" id="region3">
                  <option value="">Select Region</option>
                  @foreach($regions as $region)
                    <option value="{{$region->id}}" @if($region->id == $settings->region3) selected @endif>{{$region->region_name}}</option>
                  @endforeach
                </select>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <label>Region Description</label>
                  <textarea class="form-control" name="region_des3" id="region_des3"  maxlength="70">{{$settings->region_des3}}</textarea>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label> Size (230px*330px)</label>
                  <div class="input-group">
                    <input type="file" name="region_image3" id="uploadFileR3" class="uploadFile" accept="image/jpeg, image/jpg, image/png," onchange="readURLR3(this);">
                    <button type="button" class="btn btn-primary">Choose Image</button>
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                @if($settings->region_image3 == "")
                 <img id="blahR3" onclick="triggerImageR3()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="60" height="70"/>
                  @else
                <img id="blahR3" onclick="triggerImageR3()" src="{{generateSignedUrl('home/'.$settings->region_image3)}}" class="fetured_images_view" width="60" height="70"/>
               @endif
              </div>
              
            </div>
             <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                <label style="margin-bottom: 10px">Region Grid 4</label><span class="validationError" id="region_error"></span>
                  <select class="form-control region" name="region4" id="region4">
                  <option value="">Select Region</option>
                  @foreach($regions as $region)
                    <option value="{{$region->id}}" @if($region->id == $settings->region4) selected @endif>{{$region->region_name}}</option>
                  @endforeach
                </select>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <label>Region Description</label>
                  <textarea class="form-control" name="region_des4" id="region_des4"  maxlength="70">{{$settings->region_des4}}</textarea>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label> Size (230px*330px)</label>
                  <div class="input-group">
                    <input type="file" name="region_image4" id="uploadFileR4" class="uploadFile" accept="image/jpeg, image/jpg, image/png," onchange="readURLR4(this);">
                    <button type="button" class="btn btn-primary">Choose Image</button>
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                @if($settings->region_image4 == "")
                 <img id="blahR4" onclick="triggerImageR4()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="60" height="70"/>
                  @else
                <img id="blahR4" onclick="triggerImageR4()" src="{{generateSignedUrl('home/'.$settings->region_image4)}}" class="fetured_images_view" width="60" height="70"/>
               @endif
              </div>
              
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                <label style="margin-bottom: 10px">Region Grid 5</label><span class="validationError" id="region_error"></span>
                  <select class="form-control region" name="region5" id="region5">
                  <option value="">Select Region</option>
                  @foreach($regions as $region)
                    <option value="{{$region->id}}" @if($region->id == $settings->region5) selected @endif>{{$region->region_name}}</option>
                  @endforeach
                </select>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <label>Region Description</label>
                  <textarea class="form-control" name="region_des5" id="region_des5"  maxlength="70">{{$settings->region_des5}}</textarea>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label> Size (230px*330px)</label>
                  <div class="input-group">
                    <input type="file" name="region_image5" id="uploadFileR5" class="uploadFile" accept="image/jpeg, image/jpg, image/png," onchange="readURLR5(this);">
                    <button type="button" class="btn btn-primary">Choose Image</button>
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                @if($settings->region_image5 == "")
                 <img id="blahR5" onclick="triggerImageR5()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="60" height="70"/>
                  @else
                <img id="blahR5" onclick="triggerImageR5()" src="{{generateSignedUrl('home/'.$settings->region_image5)}}" class="fetured_images_view" width="60" height="70"/>
               @endif
              </div>
              
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                <label style="margin-bottom: 10px">Region Grid 6</label><span class="validationError" id="region_error"></span>
                  <select class="form-control region" name="region6" id="region6">
                  <option value="">Select Region</option>
                  @foreach($regions as $region)
                    <option value="{{$region->id}}" @if($region->id == $settings->region6) selected @endif>{{$region->region_name}}</option>
                  @endforeach
                </select>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <label>Region Description</label>
                  <textarea class="form-control" name="region_des6" id="region_des6"  maxlength="70">{{$settings->region_des6}}</textarea>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <label> Size (230px*330px)</label>
                  <div class="input-group">
                    <input type="file" name="region_image6" id="uploadFileR6" class="uploadFile" accept="image/jpeg, image/jpg, image/png," onchange="readURLR6(this);">
                    <button type="button" class="btn btn-primary">Choose Image</button>
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                @if($settings->region_image6 == "")
                 <img id="blahR6" onclick="triggerImageR6()" src="{{asset('images/no-image.png')}}" class="fetured_images_view" width="60" height="70"/>
                  @else
                <img id="blahR6" onclick="triggerImageR6()" src="{{generateSignedUrl('home/'.$settings->region_image6)}}" class="fetured_images_view" width="60" height="70"/>
               @endif
              </div>
              
            </div>
            <div class="row">
              <div class="col-md-4 sss">
               <div class="form-group edit_dest">
                 <label>Left Region Heading</label>
                 <input class="form-control" type="text" name="region_heading" id="region_heading" value="{{$settings->region_heading}}">
               </div>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <label>Left Region Description</label>
                  <textarea class="form-control" name="region_description" id="region_description"  maxlength="450">{{$settings->region_description}}</textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12" style="margin-top: 24px;">
               <button class="btn btn-danger active mt-2" type="button" id="region_store_form"><i class="fa fa-save"></i> Update Regions</button>
              <span class="text-success" id="mesegeseR" style="margin-left: 10px"></span>
              </div>
            </div>
          </form>
          <form role="form" id="homeWhyDookSetingPage">
            @csrf
            <div class="row">
              <input type="hidden" name="data_id" value="{{$settings->id}}">
              <div class="col-md-12" style="margin-bottom1:15px;"><h3 style="border-bottom: 2px solid #645552;">Why Choose Dook Section</h3></div>
              <div class="col-md-4 sss">
               <div class="form-group edit_dest">
                 <label>Title</label>
                 <input class="form-control" type="text" name="text_heading" id="text_heading" value="{{$settings->text_heading}}">
               </div>
               <div class="form-group edit_dest">
                 <label>Subtitle</label>
                 <input class="form-control" type="text" name="text_sub_heading" id="text_sub_heading" value="{{$settings->text_sub_heading}}">
               </div>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <label>Description</label>
                  <textarea class="form-control" name="description" id="description">{{$settings->description}}</textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12" style="margin-top: 24px;">
               <button class="btn btn-danger active mt-2" type="button" id="why_dook_store_form"><i class="fa fa-save"></i> Update</button>
              <span class="text-success" id="mesegese4" style="margin-left: 10px"></span>
              </div>
            </div>
          </form>
          <form role="form" id="homeMetaSetingPage">
            @csrf
          <div class="row">
            <input type="hidden" name="data_id" value="{{$settings->id}}">
            <div class="col-md-12" style="margin-bottom1:15px;"><h3 style="border-bottom: 2px solid #645552;">Meta Tag Section</h3></div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Meta Title</label> 
                <textarea class="form-control" name="meta_title" id="meta_title">{{$settings->meta_title}}</textarea>
              </div>
              <div class="form-group">
                <label>Meta Keywords</label>
                <textarea class="form-control" name="meta_keywords" id="meta_keywords"rows="5">{{$settings->meta_keywords}}</textarea>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Meta Description</label>
                <textarea class="form-control" name="meta_description" id="meta_description" rows="10">{{$settings->meta_description}}</textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12" style="margin-top: 24px;">
             <button class="btn btn-danger active mt-2" type="button" id="store_meta_form"><i class="fa fa-save"></i> Update Meta Data</button>
            <span class="text-success" id="mesegese5" style="margin-left: 10px"></span>
            </div>
          </div>
        </form>
      </div>
    </section>
  </div>
 
<style type="text/css">
  input#uploadFileM {
      opacity: 0;
      position: absolute;
      width: 100%;
      height: 100%;
  }
    .col-md-3.sss {
      background: radial-gradient(#58545478, transparent);
      border-right: 1px solid #d1adad;
      margin-bottom: 30px;
    }

    .select2-selection__rendered 
    {
      margin-left: -10px;
    }
    .select2.select2-container {
      width: 100% !important;
    }
    .uploadFile{
      opacity: 0;
      position: absolute;
      width: 100%;
      height: 100%;
    }
    .form-group.RR{
      margin-bottom: 0 !important;
    }
    .select2-container .select2-selection__rendered {
      margin-top: -7px !important;
    }

    span.select2-selection.select2-selection--multiple .select2-selection__rendered {
        margin-top: 0px !important;
    }
</style>
  @endsection
  @section('footerSection')
  <script type="text/javascript">
    function readURLM(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
          $('#blahM')
            .attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
      }
    }
    function triggerImageM(){
      $('#uploadFileM').trigger('click');
    } 
  </script>
  <script>
    <?php for($i=1;$i<=5;$i++){ ?>
    $('#countries{{$i}}').select2({
      placeholder:'Select Country',
    });

    $('#packages{{$i}}').select2({
      placeholder:'Select Package',
    });

  <?php } ?>
  <?php for($i=1;$i<=4;$i++){ ?>
    $('#experience{{$i}}').select2({
      placeholder:'Select Experience',
    });
  <?php } ?>
    $('#experiences').select2({
      maximumSelectionLength: 5,
      placeholder:'Select Honeymonn Packages',
    });
    $('#regions').select2({
      placeholder:'Select Regions',
    });
    $('#edit_country').select2();
  </script>
  <script>
    $('.activities').select2(
      {
        maximumSelectionLength: 11,
        placeholder: 'Select Activities',
        ajax: {
            url: "/home-activities-ajax",
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.activity_name,
                            id: item.id
                        }
                    })
                };
            },
          cache: true
        }
      });
  </script>
  <script type="text/javascript">
    $(document).ready(function () {
      $('#banner_store_form').click(function (e) {
          e.preventDefault();
          var slideTitle = $('#banner_title').val();
          if (slideTitle == "") {
             $("span#slider_error").html('This field is required!');
             $("input#banner_title").focus();
             return false;
          }
          $('#banner_store_form').html('Please wait...')
          $('#banner_store_form').prop('disabled', true);
          var formDatas = new FormData(document.getElementById('homeBannerSetingPage'));
          $.ajax({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              method: 'POST',
              url: "{{ route('home_banner_update') }}",
              data: formDatas,
              contentType: false,
              processData: false,
              success: function (data) {
                console.log(data);
                $('#banner_store_form').html('Update Banner')
                $('#banner_store_form').prop('disabled', false);
                $('#mesegese1').html("<span class='sussecmsg'>Banners updated successfully!</span>");
                setTimeout(function () {
                    $('#mesegese1').html("");
                 }, 2500);
                
                //window.location = data.url;
              }

          });
      });
      //Experience
      $('#experience_store_form').click(function (e) {
          e.preventDefault();
           var experience = $('#experiences').val();
          if (experience == "") {
             $("span#experiences_error").html('This field is required!');
             $("select#experiences").focus();
             return false;
          }
          var experience1 = $('#experience1').val();
          if (experience1 == "") {
             $("span#experience_error").html('This field is required!');
             $("select#experience1").focus();
             return false;
          }
          $('#experience_store_form').html('Please wait...')
          $('#experience_store_form').prop('disabled', true);
          var formDatas = new FormData(document.getElementById('homeExperienceSetingPage'));
          $.ajax({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              method: 'POST',
              url: "{{ route('home_exp_store') }}",
              data: formDatas,
              contentType: false,
              processData: false,
              success: function (data) {
                console.log(data);
                $('#experience_store_form').html('Update Experiences')
                $('#experience_store_form').prop('disabled', false);
                $('#mesegese2').html("<span class='sussecmsg'>Experience updated successfully!</span>");
                setTimeout(function () {
                  $('#mesegese2').html("");
                }, 2500);
                //window.location = data.url;
              }

          });
      });
      //Countries
      $('#country_store_form').click(function (e) {
          e.preventDefault();
          var country1 = $('#country1').val();
          if (country1 == "") {
             $("span#countries1_error").html('This field is required!');
             $("select#experiences").focus();
             return false;
          }
          $('#country_store_form').html('Please wait...')
          $('#country_store_form').prop('disabled', true);
          var formDatas = new FormData(document.getElementById('homeCountrySetingPage'));
          $.ajax({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              method: 'POST',
              url: "{{ route('home_country_store') }}",
              data: formDatas,
              contentType: false,
              processData: false,
              success: function (data) {
                console.log(data);
                $('#country_store_form').html('Update Countries')
                $('#country_store_form').prop('disabled', false);
                $('#mesegese3').html("<span class='sussecmsg'>Countries updated successfully!</span>");
                setTimeout(function () {
                  $('#mesegese3').html("");
                }, 2500);
                //window.location = data.url;
              }

          });
      });
      //Activity
      $('#activity_store_form').click(function (e) {
        e.preventDefault();
        $('#activity_store_form').html('Please wait...')
        $('#activity_store_form').prop('disabled', true);
        var formDatas = new FormData(document.getElementById('homeActivitySetingPage'));
        $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            method: 'POST',
            url: "{{ route('home_act_store') }}",
            data: formDatas,
            contentType: false,
            processData: false,
            success: function (data) {
              console.log(data);
              $('#activity_store_form').html('Add Activity')
              $('#activity_store_form').prop('disabled', false);
              $('#mesegese6').html("<span class='sussecmsg'>Updated successfully!</span>");
            var tableData = '<tr id="'+data.msg.id+'"><form role="form" id="homeActivitySetingPageUpdate'+data.msg.id+'">@csrf <input type="hidden" name="act_id" value="'+data.msg.id+'"><td>'+data.count+'</td><td><img src="{{asset("dook/images/home")}}/'+data.msg.image+'" style="width: 30px;"></td><td>'+data.msg.name+'</td><td><a class="popularAct" title="Edit Activity Image?" style="cursor: pointer;"><i class="fa fa-edit" style="color: #9a191e;"></i></a></td><td><div class="form-group RR"><div class="input-group"><input type="file" name="activityImg" id="activityImg'+data.msg.id+'" class="uploadFile" accept="image/jpeg, image/jpg, image/png," onchange="readURLAct'+data.msg.id+'(this);"><button type="button" class="btn btn-primary">Choose Image</button></div></div></td><td><img id="blahAct'+data.msg.id+'" onclick="triggerImageAct'+data.msg.id+'()" src="{{asset("images/no-image.png")}}" class="fetured_images_view" width="30" height="15"/></td><td><button class="btn btn-danger active mt-2" type="button" id="activity_update_form'+data.msg.id+'"><i class="fa fa-save"></i> Update</button></td></form></tr>';

              $('#dynamicRefresingActivity').append(tableData)
              setTimeout(function () {
                $('#mesegese6').html("");
              }, 2500);
              //window.location = data.url;
            }

        });
      });
      //Regions
      $('#region_store_form').click(function (e) {
          e.preventDefault();
          var region = $('#region1').val();
          if (region == "") {
             $("span#region1_error").html('This field is required!');
             $("select#region1").focus();
             return false;
          }
          $('#region_store_form').html('Please wait...')
          $('#region_store_form').prop('disabled', true);
          var formDatas = new FormData(document.getElementById('homeRegionsSetingPage'));
          $.ajax({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              method: 'POST',
              url: "{{ route('home_region_store') }}",
              data: formDatas,
              contentType: false,
              processData: false,
              success: function (data) {
                console.log(data);
                $('#region_store_form').html('Update Regions')
                $('#region_store_form').prop('disabled', false);
                $('#mesegeseR').html("<span class='sussecmsg'>Regions updated successfully!</span>");
                setTimeout(function () {
                  $('#mesegeseR').html("");
                }, 2500);
                //window.location = data.url;
              }

          });
      });
      //why
      $('#why_dook_store_form').click(function (e) {
          e.preventDefault();
          $('#why_dook_store_form').html('Please wait...')
          $('#why_dook_store_form').prop('disabled', true);
          var formDatas = new FormData(document.getElementById('homeWhyDookSetingPage'));
          $.ajax({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              method: 'POST',
              url: "{{ route('home_why_store') }}",
              data: formDatas,
              contentType: false,
              processData: false,
              success: function (data) {
                console.log(data);
                $('#why_dook_store_form').html('Update')
                $('#why_dook_store_form').prop('disabled', false);
                $('#mesegese4').html("<span class='sussecmsg'>Updated successfully!</span>");
                setTimeout(function () {
                  $('#mesegese4').html("");
                }, 2500);
                //window.location = data.url;
              }

          });
      });

      //Countries
      $('#store_meta_form').click(function (e) {
          e.preventDefault();
          $('#store_meta_form').html('Please wait...')
          $('#store_meta_form').prop('disabled', true);
          var formDatas = new FormData(document.getElementById('homeMetaSetingPage'));
          $.ajax({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              method: 'POST',
              url: "{{ route('home_meta_store') }}",
              data: formDatas,
              contentType: false,
              processData: false,
              success: function (data) {
                console.log(data);
                $('#store_meta_form').html('Update Meta Data')
                $('#store_meta_form').prop('disabled', true);
                $('#mesegese5').html("<span class='sussecmsg'>Meta data updated successfully!</span>");
                setTimeout(function () {
                  window.location.reload();
                }, 2500);
              }

          });
      });
    });
  </script>
  <script type="text/javascript">
    
   <?php for($i=1;$i<=5;$i++){ ?>
      function readURL{{$i}}(input) {
       if (input.files && input.files[0]) {
           var reader = new FileReader();

           reader.onload = function (e) {
               $('#blah{{$i}}')
                   .attr('src', e.target.result);
           };

           reader.readAsDataURL(input.files[0]);
         }
       }
      function triggerImage{{$i}}(){
        $('#uploadFile{{$i}}').trigger('click');
      } 

      function readURLP{{$i}}(input) {
       if (input.files && input.files[0]) {
           var reader = new FileReader();

           reader.onload = function (e) {
               $('#blahP{{$i}}')
                   .attr('src', e.target.result);
           };

           reader.readAsDataURL(input.files[0]);
         }
       }
      function triggerImageP{{$i}}(){
        $('#package_image{{$i}}').trigger('click');
      } 
    <?php } ?>
    <?php for($i=1;$i<=4;$i++){ ?>
      function readURLE{{$i}}(input) {
       if (input.files && input.files[0]) {
           var reader = new FileReader();

           reader.onload = function (e) {
               $('#blahE{{$i}}')
                   .attr('src', e.target.result);
           };

           reader.readAsDataURL(input.files[0]);
         }
       }
      function triggerImageE{{$i}}(){
        $('#uploadFileE{{$i}}').trigger('click');
      } 
    <?php } ?>
    <?php for($i=1;$i<=3;$i++){ ?>
      $('#region{{$i}}').select2({
        placeholder:'Select Region',
      });
      function readURLR{{$i}}(input) {
       if (input.files && input.files[0]) {
           var reader = new FileReader();

           reader.onload = function (e) {
               $('#blahR{{$i}}')
                   .attr('src', e.target.result);
           };

           reader.readAsDataURL(input.files[0]);
         }
       }
      function triggerImageR{{$i}}(){
        $('#uploadFileR{{$i}}').trigger('click');
      } 
    <?php } ?>
    <?php foreach($activities as $activity){ ?>
      
      function readURLAct{{$activity->id}}(input) {

       if (input.files && input.files[0]) {
           var reader = new FileReader();

           reader.onload = function (e) {
               $('#blahAct{{$activity->id}}')
                   .attr('src', e.target.result);
           };

           reader.readAsDataURL(input.files[0]);
         }
       }
      function triggerImageAct{{$activity->id}}(){
        $('#activityImg{{$activity->id}}').trigger('click');
      } 
    <?php } ?>
    function myFunction(id){
      //alert(id);
      $('#activity_update_form'+id).html('Please wait...');
      $('#activity_update_form'+id).prop('disabled', true);
      var formDatas = new FormData(document.getElementById('homeActivitySetingPageUpdate'+id));
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        method: 'POST',
        url: "{{ route('home_act_update') }}",
        data: formDatas,
        contentType: false,
        processData: false,
        success: function (data) {
          $('#activity_update_form'+id).html('Update Image');
          $('#activity_update_form'+id).prop('disabled', false);
          window.location.reload();
        }
      });
    }
    function myFunctionDelete(id){
      if (confirm("Are you sure you want to delete this?"))
      var token = $("meta[name='csrf-token']").attr("content");
      if(id){
      $.ajax(
        {
          url: '/home_activity_delete/' + id,
          type: 'POST',
          data: {
              "id": id,
              "_token": token,
          },
          success: function (data) {
            window.location.reload();
          }
        });
      }
    }
    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
             $('#blah')
                 .attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
      }
    }
    function triggerImage(){
      $('#uploadFile').trigger('click');
    } 

     

      function readURLAB(input) {
       if (input.files && input.files[0]) {
           var reader = new FileReader();

           reader.onload = function (e) {
               $('#blahActB')
                   .attr('src', e.target.result);
           };

           reader.readAsDataURL(input.files[0]);
         }
       }
      function triggerImageAB(){
        $('#activityUploadBanner').trigger('click');
      }  
      function readURLA(input) {
        if (input.files && input.files[0]) {
           var reader = new FileReader();

           reader.onload = function (e) {
               $('#blahAct')
                   .attr('src', e.target.result);
           };

           reader.readAsDataURL(input.files[0]);
         }
      }

      function triggerImageA(){
        $('#activityUpload').trigger('click');
      }
  </script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
  <script>
     $(document).ready(function() {
      $('#description').summernote({
         toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline']],
            //['fontname', ['fontname']],
            //['fontsize', ['fontsize']],
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

    $("li a").each(function() {   
      //alert(this.href);
        if (this.href == window.location.href) {
            $(this).addClass("active");
        }
    })

  </script>
  <script type="text/javascript">
    $( ".row_position" ).sortable({
        delay: 150,
        stop: function() {
            var selectedData = new Array();
            $('.row_position>tr').each(function() {
                selectedData.push($(this).attr("id"));
            });
            updateOrder(selectedData);
        }
    });


    function updateOrder(data) {
      console.log(data);
        $.ajax({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:"{{ route('homeActivitypositionShifting') }}",
            type:'post',
            data:{position:data},
            success:function(){
                alert('Your changes successfully saved');
                window.location.reload();
            }
        })
    }
</script>
@endsection