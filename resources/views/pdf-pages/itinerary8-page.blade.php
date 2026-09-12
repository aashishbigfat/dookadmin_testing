<div class="tab-pane" id="kt_tabs_6_itinerary7" role="tabpanel">
    <div class="row">
        <div class="col-xl-12 col-lg-12 order-lg-3 order-xl-1">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__body">

                    <form method="POST" action="{{route('itinerary-update')}}" enctype="multipart/form-data" class="itinerary-setup m-t-20" id="banner_page">
                        @csrf
                        @foreach($itinerary_data as $iti_data)
                        @if($loop->index == 7)
                        <div class="form-group">
                            <label>Day Title</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{$iti_data->name}}" placeholder="Day title..">
                            <input type="hidden" name="itinerary_id" value="{{$iti_data->id}}">
                        </div>
                        <div class="floating-label1 form-group">
                            <label>Description</label>
                            <textarea name="description" id="description{{$loop->index}}" class=" floating-input1 floating-textarea">{!!$iti_data->description!!}</textarea>
                            <input type="hidden" name="itinerary_id" value="{{$iti_data->id}}">
                        </div>
                        <div class="col-md-12 headings">
                            <h5>Images</h5>
                            <hr>
                            <div class="row">
                              <div class="col-md-3 col-lg-3 col-sm-12">
                                <div class="form-group">
                                 <label style="margin-bottom: 15px">Banner Image</label> <span class="validationError" id="image_error"></span> 
                                 <div class="input-group">
                                    <input type="file" name="banner_image" id="uploadFileBannerIti0{{$loop->index}}" accept="image/jpeg, image/jpg, image/png, image/gif," onchange="readURLBannerIti0{{$loop->index}}(this);">
                                </div>
                                </div>
                              </div>
                              <div class="col-md-3 col-lg-3 col-sm-12">
                                  <img id="itiBannerImage0{{$loop->index}}" onclick="triggerImageBanner0{{$loop->index}}()" src="https://adm.dookinternational.com/dook/images/poi/{{$iti_data->banner_image}}" class="fetured_images_view" width="80" height="80"/>
                              </div>
                              <div class="col-md-3 col-lg-3 col-sm-12">
                                <div class="form-group">
                                 <label style="margin-bottom: 15px">Image</label> <span class="validationError" id="image_error"></span> 
                                 <div class="input-group">
                                    <input type="file" name="poi_image" id="uploadFileItiImage0{{$loop->index}}" accept="image/jpeg, image/jpg, image/png, image/gif," onchange="readURLItiImage0{{$loop->index}}(this);">
                                </div>
                                </div>
                              </div>
                              <div class="col-md-3 col-lg-3 col-sm-12">
                                  <img id="imagePoi0{{$loop->index}}" onclick="triggerImagePoi0{{$loop->index}}()" src="https://adm.dookinternational.com/dook/images/poi/{{$iti_data->image}}" class="fetured_images_view" width="80" height="80"/>
                              </div>
                            </div>
                        </div>
                        <div class="col-md-12 headings">
                            <h5>Highlights of the day</h5>
                            <hr>
                        </div>
                        <div class="row">
                        @foreach($iti_data->pois as $poi)
                        <div class="form-group col-md-3">
                            <label>Poi name</label>
                            <input type="text" id="poi_name" name="poi_name[]" value="{{$poi->name}}" class="form-control" placeholder="Enter poi name..">
                            <input type="hidden" name="poi_id[]" value="{{$poi->id}}">
                        </div>
                        <div class="form-group col-md-3">
                            <label>Address</label>
                            <input type="text" id="address" name="address[]" value="{{$poi->address}}" class="form-control" placeholder="Address..">
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12">
                            <div class="form-group">
                                <label style="margin-bottom: 15px">Image</label> <span class="validationError" id="image_error"></span> 
                                <div class="input-group">
                                    <input type="file" name="image[]" id="uploadFileItinerary0{{$loop->index}}" accept="image/jpeg, image/jpg, image/png, image/gif," onchange="readURLItinerary0{{ $loop->index }}(this);">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12">
                              <img id="itinerary_img0{{$loop->index}}" onclick="triggerImageItinerary0{{ $loop->index }}()" src="https://adm.dookinternational.com/dook/images/poi/{{$poi->image}}" class="fetured_images_view" width="80" height="80"/>
                        </div>
                        @endforeach
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-info m-t-20 pull-right" type="button"><i class="fas fa-save"></i>Update</button>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </form>

                </div>
            </div>

        </div>

        <div class="col-xl-12 col-lg-12 order-lg-12 order-xl-12">
            <form method="POST" action="{{route('add-page')}}">
                @csrf
                @foreach($itinerary_data as $itineraries)
                @if($loop->index == 7)
                <input type="hidden" name="id" value="{{$itineraries->id}}">
                <input type="hidden" name="page_name" value="itinerary">
                <input type="hidden" name="day_number" value="{{$itineraries->day_number}}">
                <input type="hidden" name="package_id" value="{{$basic_detail_data->departure_id}}">
                @endif
                @endforeach
                <div size="A4" style="position: relative" id="containerday1" class="pdf_container_di">
                    @foreach($itinerary_data as $itineraries)
                    @if($loop->index == 7)
                    <div class="DaywiseTopbg DaywiseTopbg8">
                        <h1>Day{{$itineraries->day_number}}</h1>
                        <h2 style="color: #fff;font-size: 1.8rem;margin-top: 5px;">{{$itineraries->name}}</h2>
                        <div style="position: absolute;bottom:-50%;z-index:10;right:5%;border:10px solid #fff;border-radius:50%;overflow: hidden;width:200px;height:200px;">
                            @if($itineraries->status == 0 && $itineraries->pac_image_sts == 1)
                            <img src="https://adm.dookinternational.com/dook/images/package/{{$itineraries->image}}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                            <img src="https://adm.dookinternational.com/dook/images/poi/{{$itineraries->image}}" style="width:100%;height:100%;object-fit:cover;">
                            @endif
                        </div>
                    </div>
                    <div style="width:50%;position:relative;float:left;">
                        <div style="position: absolute;top: -130px;">
                            <div class="Poi-imgDaywise Poi-imgDaywise8">
                                @if($itineraries->status == 0 && $itineraries->pac_image_sts == 1)
                                <img src="https://adm.dookinternational.com/dook/images/package/{{$itineraries->banner_image}}" style="width:100%;height:100%;object-fit:cover;">
                                @else
                                <img src="https://adm.dookinternational.com/dook/images/poi/{{$itineraries->banner_image}}" style="width:100%;height:100%;object-fit:cover;">
                                @endif
                            </div>
                            <div class="dayItineraryDesc_dook">{!!$itineraries->description!!}</div>
                        </div>
                    </div>
                    <div class="Daywisebottomrightbg Daywisebottomrightbg8">
                        @if($itineraries->status == 0)
                        <div>
                            <img src="https://adm.dookinternational.com/promotions/{{$itineraries->itinerary_image}}" style="width:100%;height:auto;object-fit:cover;">
                        </div>
                        @else
                        <div>
                            <h1 style="margin-bottom:20px;">Highlights of the day</h1>
                            @foreach($itineraries->pois as $top_pois)
                            <div style="display:block;margin-bottom:10px;width:350px;">
                                <div style="display:inline-block;width:65px;">
                                    <div style="width:50px;height:50px;border-radius:50%;overflow: hidden;">
                                        <img src="https://adm.dookinternational.com/dook/images/poi/{{$top_pois->image}}" style="width:50px;height:50px;object-fit:cover;">
                                    </div>
                                </div>
                                <div style="display:inline-block;width:280px;">
                                    <h3 style="width:280px;">{{$top_pois->name}}</h3>
                                    <p style="width:280px;">{{$top_pois->address}}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endif
                    @endforeach
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
        .DaywiseTopbg8{
            height: 20%;width:100%;
            background:#484848;
            position: relative;
            padding:50px;margin-bottom: 14%;
        }
        .Daywisebottomrightbg8{height: 71%;width:50%;background:#e16a61;position:relative;right:0;float: right;padding:20px;color:#fff;display: flex;align-items: center;}
        .Daywisebottomrightbg8::before{
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            bottom:100%;
            right: 0px;
            border-bottom: 94px solid #e16a61;
            border-left: 10.5cm solid transparent;
        }
        .Daywisebottomrightbg8 p{line-height:1.1;font-size:16px;}
        .DaywiseTopbg8::after{
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            top:100%;
            right: 0px;
            border-top: 185px solid #484848;
            border-right: 21cm solid transparent;
        }
        .DaywiseTopbg8 h1{font-size:2.5rem;color:#fff;}
        .DaywiseTopbg8 p{font-size:2rem;color:#fff;}
        .Poi-imgDaywise8{width:90%;height:400px;border:10px solid #fff;margin:auto;box-shadow: 0px 1px 5px 0px #ddd;position: relative;} */
    </style>
</head>

@foreach($itinerary_data as $iti_data)
@if($loop->index == 7)
@foreach($iti_data->pois as $poi)
<script type="text/javascript">    
    function readURLItinerary0{{ $loop->index }}(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#itinerary_img0{{ $loop->index }}')
                    .attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function triggerImageItinerary0{{ $loop->index }}(){
        $('#uploadFileItinerary0{{ $loop->index }}').trigger('click');
    }
</script>
@endforeach

<script type="text/javascript">    
    function readURLBannerIti0{{$loop->parent->index}}(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#itiBannerImage0{{$loop->parent->index}}')
                    .attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function triggerImageBanner0{{$loop->parent->index}}(){
        $('#uploadFileBannerIti0{{$loop->parent->index}}').trigger('click');
    }
  
    function readURLItiImage0{{$loop->parent->index}}(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#imagePoi0{{$loop->parent->index}}')
                    .attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function triggerImagePoi0{{$loop->parent->index}}(){
        $('#uploadFileItiImage0{{$loop->parent->index}}').trigger('click');
    }
</script>
<script>
    $(document).ready(function() {
     $('#description7').summernote({
        toolbar: [
           ['style', ['style']],
           ['style', ['bold', 'italic', 'underline']],
           //['fontname', ['fontname']],
           //['fontsize', ['fontsize']],
           ['color', ['color']],
           ['para', ['ul', 'ol', 'paragraph']],
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
 </script>
@endif
@endforeach