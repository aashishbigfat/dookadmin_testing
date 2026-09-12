
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
        div#containerterms {
            background: white;
            display: block;
            margin: 0 auto;
        }
        
        
        div#containerterms {
            width: 210mm;
            height: 297mm;
            overflow: hidden;
        }
        ul.placestovisit li{
            width: 50%;
            float: left;
            display: flex;
            line-height: 1.5;
            font-size: 20px;
            align-items: center;
            margin-bottom: 10px;
        }
        ul.placestovisit li img{width:50px;height:50px;object-fit:cover;margin-right:10px}
        .terms-condition p{margin-bottom:10px;}
        .terms-condition h2{margin-bottom:10px;}
        .card-text ul li{
            font-size: 15px;
            text-align: justify;
            padding: 0 18px;
            line-height:1.3;
        } */
        .dayItineraryDesc_dook_terms ul li{
            padding: 0 22px;
        }
        .dayItineraryDesc_dook_terms ul li:before{
            left: 6px;
        }
    </style>
<div class="tab-pane" id="kt_tabs_6_12" role="tabpanel">
    <div class="row">
        <div class="col-xl-12 col-lg-12 order-lg-3 order-xl-1">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__body">

                    <form method="POST" action="{{route('terms-update')}}" enctype="multipart/form-data" class="itinerary-setup m-t-20" id="banner_page">
                        @csrf
                        <div class="floating-label1 form-group">
                            <label>Terms & Conditions</label>
                            @if($term_data->term != '')
                                <textarea name="terms" id="terms_condition" class=" floating-input1 floating-textarea">{!! $term_data->terms !!}</textarea>
                            @else
                                <textarea name="terms" id="terms_condition" class=" floating-input1 floating-textarea">{!! $devDeparture->conditions !!}</textarea>
                            @endif
                            <input type="hidden" name="terms_id" value="{{$term_data->id}}">
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
                <input type="hidden" name="id" value="{{$term_data->id}}">
                <input type="hidden" name="page_name" value="terms">
                <input type="hidden" name="package_id" value="{{$term_data->departure_id}}">
                <div size="A4" style="position: relative" id="containerterms" class="pdf_container_di">
                    <div class="terms-condition dayItineraryDesc_dook dayItineraryDesc_dook_terms" style="box-shadow:0px 0px 5px 0px #ddd;margin:10px;padding:15px 20px;font-size:14px;font-weight:500;line-height: 1.4;max-height:1068px;overflow:hidden;">
                        <h2 style="margin-bottom:3px;">Travel terms and conditions</h2>
                        <div class="card-text">{!! $term_data->terms !!}</div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/summernote.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
      $('#terms_condition').summernote({
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