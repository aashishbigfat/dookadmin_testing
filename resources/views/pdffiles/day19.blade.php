<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Darker+Grotesque:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <style>
        *, ::after, ::before {
            box-sizing: border-box;
        }

        * {
            margin: 0;
            padding: 0;
        }
        body{font-family: 'Open Sans', sans-serif;}
        h1,h2,h3,h3,h5,h6{font-family: 'Dancing Script', cursive;font-weight:bold}
        div#containerday {
            background: white;
            display: block;
            margin: 0 auto;
        }
        div#containerday{
            width: 210mm;
            height: 297mm;
            overflow: hidden;
        }
        .DaywiseTopbg{
            height: 20%;width:100%;
            background:#484848;
            position: relative;
            padding:50px;margin-bottom: 14%;
        }
        .Daywisebottomrightbg_a{height: 71%;width:50%;background:#e16a61;position:relative;right:0;float: right;padding:20px;color:#fff;display: flex;align-items: center;}
        .Daywisebottomrightbg_a::before{
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            bottom:100%;
            right: 0px;
            border-bottom: 94px solid #e16a61;
            border-left: 10.5cm solid transparent;
        }
        .Daywisebottomrightbg_a p{line-height:1.1;font-size:13px;}
        .DaywiseTopbg::after{
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            top:100%;
            right: 0px;
            border-top: 185px solid #484848;
            border-right: 21cm solid transparent;
        }
        .DaywiseTopbg h1{font-size:2.5rem;color:#fff;}
        .DaywiseTopbg p{font-size:2rem;color:#fff;}
        .Poi-imgDaywise{width:90%;height:400px;border:10px solid #fff;margin:auto;box-shadow: 0px 1px 5px 0px #ddd;position: relative;}
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
        .dayItineraryDesc_dook{
            margin-top: 13px;
        }
        .dayItineraryDesc_dook ul{
            list-style-type: none;
        }
        .dayItineraryDesc_dook ul li{
            position: relative;
            font-size: 11px;
            line-height:1.3;
            padding: 0 22px 0 34px;
            text-align: justify;
            margin:0;
        }
        .dayItineraryDesc_dook ul li:not(:last-child){margin-bottom:4px;}
        .dayItineraryDesc_dook ul li:before{
            position: absolute;
            content: "\2022";
            font-size: 20px;
            left: 20px;
            top: -6px;
            color: #333;
        }
        .btm_powered{
            position: absolute;
            bottom: 0;
            right: 0;
            color: #fff !important;
            background-color: #023F75;
            padding: 7px 16px 12px;
            font-size: 10px;
            display: flex;
            align-items: flex-end;
            box-shadow: 0 0 6px 0 rgb(255 255 255 / 42%);
            border-radius: 12px 0 0 0;
            line-height: 1;
            margin: 0;
            text-decoration: none !important;
        }
        .btm_powered img{
            width: 24px;
            margin: 0 3px 0 6px;
        }
        .btm_powered strong{
            font-size: 12px;
            line-height: 1;
        }
    </style>
</head>
<body>
<div size="A4" style="position: relative" id="containerday">
    @foreach($itinerary as $itineraries)
    @if($itineraries->day_number == 19)
    <div class="DaywiseTopbg">
        <h1>Day {{$itineraries->day_number}}</h1>
        <h2 style="color:#fff;">{{$itineraries->name}}</h2>
        @foreach($itineraries->poi as $top_pois)
            @if ($loop->first)
                <div style="position: absolute;bottom:-50%;z-index:10;right:5%;border:10px solid #fff;border-radius:50%;overflow: hidden;width:200px;height:200px;">
                    @if($pdf_itineraries->status == 0 && $pdf_itineraries->pac_image_sts == 1)
                    <img src="https://adm.dookinternational.com/dook/images/package/{{$pdf_itineraries->image}}" style="width:100%;height:100%;object-fit:cover;">
                    @else
                    <img src="https://adm.dookinternational.com/dook/images/poi/{{$pdf_itineraries->image}}" style="width:100%;height:100%;object-fit:cover;">
                    @endif
                </div>
            @endif
        @endforeach
    </div>
    <div style="width:50%;position:relative;float:left;">
        <div style="position: absolute;top: -130px;">
            <div class="Poi-imgDaywise">
                @if($pdf_itineraries->status == 0 && $pdf_itineraries->pac_image_sts == 1)
                <img src="https://adm.dookinternational.com/dook/images/package/{{$pdf_itineraries->banner_image}}" style="width:100%;height:100%;object-fit:cover;">
                @else
                <img src="https://adm.dookinternational.com/dook/images/poi/{{$pdf_itineraries->banner_image}}" style="width:100%;height:100%;object-fit:cover;">
                @endif
            </div>
            <div class="dayItineraryDesc_dook">{!!$itineraries->description!!}</div>
        </div>
    </div>
    <div class="Daywisebottomrightbg_a">
        @if($pdf_itineraries->status == 0)
        <div>
            <img src="https://adm.dookinternational.com/promotions/{{$pdf_itineraries->itinerary_image}}" style="width:100%;height:auto;object-fit:cover;">
        </div>
        @else
        <div>
            <h1 style="margin-bottom:20px;">Highlights of the day</h1>
            @foreach($pdf_itineraries->pois as $top_pois)
            <div style="display:block;margin-bottom:10px;width:350px;">
                <div style="display:inline-block;width:50px;margin-right:5px">
                    <div style="width:50px;height:50px;border-radius:50%;overflow: hidden;">
                        <img src="https://adm.dookinternational.com/dook/images/poi/{{$top_pois->image}}" style="width:50px;height:50px;object-fit:cover;">
                    </div>
                </div>
                <div style="display:inline-block;width:285px;vertical-align: top;">
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
    <div class="watermark">Dook International</div>
    <a href="https://www.tutterflycrm.com/" class="btm_powered">Powered By:
        <div style="margin: 0;display:flex;align-items:flex-end;">
            <img src="{{asset('images/tutterfly_logo.png')}}">
            <strong>Tutterfly CRM</strong>
        </div>
    </a>
</div>
</body>
</html>