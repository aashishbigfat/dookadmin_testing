<div class="tab-pane" id="kt_tabs_6_2" role="tabpanel">
    <div class="row">
        <div class="col-xl-12 col-lg-12 order-lg-3 order-xl-1">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__body">

                    <form method="POST" action="{{route('basic-detail-update')}}" enctype="multipart/form-data" class="itinerary-setup m-t-20" id="basic-detail_page">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 headings">
                                <h5>Basic Detail of package</h5>
                                <hr>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Package Name</label>
                                <input type="text" id="package_name" name="package_name" class="form-control" value="{{$basic_detail_data->title}}" placeholder="Company name..">
                                <input type="hidden" name="basic_id" value="{{$basic_detail_data->id}}">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Total Days</label>
                                <input type="text" id="total_days" name="total_days" value="{{$basic_detail_data->total_days}}" class="form-control" placeholder="Total days..">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Total Night</label>
                                <input type="text" id="total_nights" name="total_nights" value="{{$basic_detail_data->total_nights}}" class="form-control" placeholder="Total Nights..">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Departs On</label>
                                <input type="text" id="departs_on" name="departs_on" value="{{$basic_detail_data->departs}}" class="form-control" placeholder="Enter company website..">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Start From</label>
                                <input type="text" id="start_from" name="start_from" value="{{$basic_detail_data->start_from}}" class="form-control" placeholder="Start from..">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 headings">
                                <h5>Places to Visit</h5>
                                <hr>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Place Visit 1</label>
                                <input type="text" id="place1" name="place1" value="{{$basic_detail_data->place1}}" class="form-control" placeholder="Place first..">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Place Visit 2</label>
                                <input type="text" id="place2" name="place2" value="{{$basic_detail_data->place2}}" class="form-control" placeholder="Place Second..">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Place Visit 3</label>
                                <input type="text" id="place3" name="place3" value="{{$basic_detail_data->place3}}" class="form-control" placeholder="Place Third..">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Place Visit 4</label>
                                <input type="text" id="place4" name="place4" value="{{$basic_detail_data->place4}}" class="form-control" placeholder="Place Fourth..">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 headings">
                                <h5>Top Attractions</h5>
                                <hr>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Attraction 1</label>
                                <input type="text" id="attraction1" name="attraction1" value="{{$basic_detail_data->attraction_name1}}" class="form-control" placeholder="Attraction first..">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Address</label>
                                <input type="text" id="address1" name="address1" value="{{$basic_detail_data->attraction_address1}}" class="form-control" placeholder="Address..">
                            </div>
                            <div class="col-md-3 col-lg-3 col-sm-12">
                                <div class="form-group">
                                    <label style="margin-bottom: 15px">Attraction Image1</label> <span class="validationError" id="image_error"></span> 
                                    <div class="input-group">
                                        <input type="file" name="image1" id="uploadFileBanner1" accept="image/jpeg, image/jpg, image/png, image/gif," onchange="readURL1(this);">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-3 col-sm-12">
                                  <img id="banner1" onclick="triggerImage1()" src="https://adm.dookinternational.com/dook/images/poi/{{$basic_detail_data->attraction_image1}}" class="fetured_images_view" width="80" height="80"/>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Attraction 2</label>
                                <input type="text" id="attraction2" name="attraction2" value="{{$basic_detail_data->attraction_name2}}" class="form-control" placeholder="Attraction first..">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Address</label>
                                <input type="text" id="address2" name="address2" value="{{$basic_detail_data->attraction_address2}}" class="form-control" placeholder="Address..">
                            </div>
                            <div class="col-md-3 col-lg-3 col-sm-12">
                                <div class="form-group">
                                    <label style="margin-bottom: 15px">Attraction Image2</label> <span class="validationError" id="image_error"></span> 
                                    <div class="input-group">
                                        <input type="file" name="image2" id="uploadFileBanne2r" accept="image/jpeg, image/jpg, image/png, image/gif," onchange="readURL2(this);">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-3 col-sm-12">
                                  <img id="banner2" onclick="triggerImage2()" src="https://adm.dookinternational.com/dook/images/poi/{{$basic_detail_data->attraction_image2}}" class="fetured_images_view" width="80" height="80"/>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Attraction 3</label>
                                <input type="text" id="attraction3" name="attraction3" value="{{$basic_detail_data->attraction_name3}}" class="form-control" placeholder="Attraction first..">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Address</label>
                                <input type="text" id="attraction_address3" name="address3" value="{{$basic_detail_data->attraction_address3}}" class="form-control" placeholder="Address..">
                            </div>
                            <div class="col-md-3 col-lg-3 col-sm-12">
                                <div class="form-group">
                                    <label style="margin-bottom: 15px">Attraction Image3</label> <span class="validationError" id="image_error"></span> 
                                    <div class="input-group">
                                        <input type="file" name="image3" id="uploadFileBanner3" accept="image/jpeg, image/jpg, image/png, image/gif," onchange="readURL3(this);">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-3 col-sm-12">
                                  <img id="banner3" onclick="triggerImage3()" src="https://adm.dookinternational.com/dook/images/poi/{{$basic_detail_data->attraction_image3}}" class="fetured_images_view" width="80" height="80"/>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Attraction 4</label>
                                <input type="text" id="attraction4" name="attraction4" value="{{$basic_detail_data->attraction_name4}}" class="form-control" placeholder="Attraction first..">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Address</label>
                                <input type="text" id="attraction_address4" name="address4" value="{{$basic_detail_data->attraction_address4}}" class="form-control" placeholder="Address..">
                            </div>
                            <div class="col-md-3 col-lg-3 col-sm-12">
                                <div class="form-group">
                                    <label style="margin-bottom: 15px">Attraction Image4</label> <span class="validationError" id="image_error"></span> 
                                    <div class="input-group">
                                        <input type="file" name="image4" id="uploadFileBanner4" accept="image/jpeg, image/jpg, image/png, image/gif," onchange="readURL4(this);">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-3 col-sm-12">
                                  <img id="banner4" onclick="triggerImage4()" src="https://adm.dookinternational.com/dook/images/poi/{{$basic_detail_data->attraction_image4}}" class="fetured_images_view" width="80" height="80"/>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Attraction 5</label>
                                <input type="text" id="attraction5" name="attraction5" value="{{$basic_detail_data->attraction_name5}}" class="form-control" placeholder="Attraction first..">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Address</label>
                                <input type="text" id="attraction_address5" name="address5" value="{{$basic_detail_data->attraction_address5}}" class="form-control" placeholder="Address..">
                            </div>
                            <div class="col-md-3 col-lg-3 col-sm-12">
                                <div class="form-group">
                                    <label style="margin-bottom: 15px">Attraction Image5</label> <span class="validationError" id="image_error"></span> 
                                    <div class="input-group">
                                        <input type="file" name="image5" id="uploadFileBanner5" accept="image/jpeg, image/jpg, image/png, image/gif," onchange="readURL5(this);">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-3 col-sm-12">
                                  <img id="banner5" onclick="triggerImage5()" src="https://adm.dookinternational.com/dook/images/poi/{{$basic_detail_data->attraction_image5}}" class="fetured_images_view" width="80" height="80"/>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Attraction 6</label>
                                <input type="text" id="attraction6" name="attraction6" value="{{$basic_detail_data->attraction_name6}}" class="form-control" placeholder="Attraction first..">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Address</label>
                                <input type="text" id="attraction_address6" name="address6" value="{{$basic_detail_data->attraction_address6}}" class="form-control" placeholder="Address..">
                            </div>
                            <div class="col-md-3 col-lg-3 col-sm-12">
                                <div class="form-group">
                                    <label style="margin-bottom: 15px">Attraction Image6</label> <span class="validationError" id="image_error"></span> 
                                    <div class="input-group">
                                        <input type="file" name="image6" id="uploadFileBanner6" accept="image/jpeg, image/jpg, image/png, image/gif," onchange="readURL6(this);">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-3 col-sm-12">
                                  <img id="banner6" onclick="triggerImage6()" src="https://adm.dookinternational.com/dook/images/poi/{{$basic_detail_data->attraction_image6}}" class="fetured_images_view" width="80" height="80"/>
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

        <div class="col-xl-12 col-lg-12 order-lg-12 order-xl-12">
            <form method="POST" action="{{route('add-page')}}">
                @csrf
                <input type="hidden" name="id" value="{{$basic_detail_data->id}}">
                <input type="hidden" name="page_name" value="basic_detail">
                <input type="hidden" name="package_id" value="{{$basic_detail_data->departure_id}}">
                <div size="A4" style="position: relative" id="container">
                    <div class="departure-basic-details" style="">
                        <h2 class="" style="margin:0;margin-bottom:16px;">{{$basic_detail_data->title}}</h2>
                        <div class="display:flex;flex-wrap: wrap;" class="font-change">
                            <div style="display:flex;width:50%;float:left;margin-bottom:10px;">
                                <div>
                                    <p style="display:flex;margin-right:10px;"><img src="{{asset('media/itinerary/time.png')}}" style="width:22px;margin-right:8px;"><span class="h2">Duration</span>:</p>
                                    <p style="display:flex;margin-right:10px;"><img src="{{asset('media/itinerary/route.png')}}" style="width:22px;margin-right:8px;"><span class="h2">Starts from</span>:</p>
                                </div>
                                <div>
                                    <p style="min-height:22px;">{{$basic_detail_data->total_nights}}N/{{$basic_detail_data->total_days}}D</p>
                                    <p style="min-height:22px;">{{$basic_detail_data->start_from}}</p>
                                </div>
                            </div>
                            <div style="display:flex;width:50%;float:left;margin-bottom:10px;">
                                <div>
                                    <p style="display:flex;margin-right:10px;"><img src="{{asset('media/itinerary/calendar.png')}}" style="width:22px;margin-right:8px;"><span class="h2">Departs on</span>:</p>
                                </div>
                                <div>
                                    <p style="min-height:22px;">{{$basic_detail_data->departs}}</p>
                                </div>
                            </div>
                            {{-- <p style="display:flex;align-items:center;width:50%;float:left;margin-bottom:10px;"><img src="{{asset('media/itinerary/time.png')}}" style="width:30px;margin-right:10px;"><span class="h2">Duration</span>: {{$basic_detail_data->total_nights}}N/{{$basic_detail_data->total_days}}D</p>
                            <p style="display:flex;align-items:center;width:50%;float:left;margin-bottom:10px;"><img src="{{asset('media/itinerary/calendar.png')}}" style="width:30px;margin-right:10px;"><span class="h2">Departs on</span>: {{$basic_detail_data->departs}}</p>
                            <p style="display:flex;align-items:center;width:50%;float:left;margin-bottom:10px;"><img src="{{asset('media/itinerary/route.png')}}" style="width:30px;margin-right:10px;"><span class="h2">Starts from</span>: {{$basic_detail_data->start_from}}</p> --}}
                        </div>
                    </div>
                    <div style="display: block">
                        <div class="compnayName" style="position: relative;background:#cc2127;color:#fff;display: inline-block;padding: 20px;">
                            <h1>Places to Visit</h1>
                        </div>
                        <div style="padding: 20px;display: block;float:left;width:100%;">
                            <ul class="placestovisit" style="list-style: none;padding:0;margin:0;">
                                <li>{{$basic_detail_data->place1}}</li>
                                <li>{{$basic_detail_data->place2}}</li>
                                <li>{{$basic_detail_data->place3}}</li>
                                <li>{{$basic_detail_data->place4}}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="compnayName" style="position: relative;background:#cc2127;color:#fff;display: inline-block;padding: 20px;">
                        <h1>Top Attractions</h1>
                    </div>
                    <div style="padding: 20px;display:flex;flex-wrap: wrap;" class="attraction-points">
                        <div style="display: flex;margin-bottom:10px;width: 50%;padding-right:7px;">
                            <div>
                                <div style="width:50px;height:50px;border-radius:50%;margin-right:15px;overflow:hidden;">
                                    <img src="https://adm.dookinternational.com/dook/images/poi/{{$basic_detail_data->attraction_image1}}" style="width:50px;height:50px;object-fit:cover;">
                                </div>
                            </div>
                            <div>
                                <h3>{{$basic_detail_data->attraction_name1}}</h3>
                                <p>{{$basic_detail_data->attraction_address1}}</p>
                            </div>
                        </div>

                        <div style="display: flex;margin-bottom:10px;width: 50%;padding-left:7px;">
                            <div>
                                <div style="width:50px;height:50px;border-radius:50%;margin-right:15px;overflow:hidden;">
                                    <img src="https://adm.dookinternational.com/dook/images/poi/{{$basic_detail_data->attraction_image2}}" style="width:50px;height:50px;object-fit:cover;">
                                </div>
                            </div>
                            <div>
                                <h3>{{$basic_detail_data->attraction_name2}}</h3>
                                <p>{{$basic_detail_data->attraction_address2}}</p>
                            </div>
                        </div>

                        <div style="display: flex;margin-bottom:10px;width: 50%;padding-right:7px;">
                            <div>
                                <div style="width:50px;height:50px;border-radius:50%;margin-right:15px;overflow:hidden;">
                                    <img src="https://adm.dookinternational.com/dook/images/poi/{{$basic_detail_data->attraction_image3}}" style="width:50px;height:50px;object-fit:cover;">
                                </div>
                            </div>
                            <div>
                                <h3>{{$basic_detail_data->attraction_name3}}</h3>
                                <p>{{$basic_detail_data->attraction_address3}}</p>
                            </div>
                        </div>

                        <div style="display: flex;margin-bottom:10px;width: 50%;padding-left:7px;">
                            <div>
                                <div style="width:50px;height:50px;border-radius:50%;margin-right:15px;overflow:hidden;">
                                    <img src="https://adm.dookinternational.com/dook/images/poi/{{$basic_detail_data->attraction_image4}}" style="width:50px;height:50px;object-fit:cover;">
                                </div>
                            </div>
                            <div>
                                <h3>{{$basic_detail_data->attraction_name4}}</h3>
                                <p>{{$basic_detail_data->attraction_address4}}</p>
                            </div>
                        </div>

                        <div style="display: flex;margin-bottom:10px;width: 50%;padding-right:7px;">
                            <div>
                                <div style="width:50px;height:50px;border-radius:50%;margin-right:15px;overflow:hidden;">
                                    <img src="https://adm.dookinternational.com/dook/images/poi/{{$basic_detail_data->attraction_image5}}" style="width:50px;height:50px;object-fit:cover;">
                                </div>
                            </div>
                            <div>
                                <h3>{{$basic_detail_data->attraction_name5}}</h3>
                                <p>{{$basic_detail_data->attraction_address5}}</p>
                            </div>
                        </div>

                        <div style="display: flex;margin-bottom:10px;width: 50%;padding-left:7px;">
                            <div>
                                <div style="width:50px;height:50px;border-radius:50%;margin-right:15px;overflow:hidden;">
                                    <img src="https://adm.dookinternational.com/dook/images/poi/{{$basic_detail_data->attraction_image6}}" style="width:50px;height:50px;object-fit:cover;">
                                </div>
                            </div>
                            <!-- <img src="https://adm.dookinternational.com/dook/images/poi/{{$basic_detail_data->attraction_image6}}" style="width:50px;height:50px;object-fit:cover;border-radius:50%;float:left;margin-right:15px;"> -->
                            <div>
                                <h3>{{$basic_detail_data->attraction_name6}}</h3>
                                <p>{{$basic_detail_data->attraction_address6}}</p>
                            </div>
                        </div>

                    </div>
                    
                    <img src="{{asset('media/itinerary/page2graphic.png')}}" style="width: 100%;height:auto;position: absolute;bottom: 0;left:0;">
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