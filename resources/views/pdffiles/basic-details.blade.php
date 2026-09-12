<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Itinerary</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Redressed&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Darker+Grotesque:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <style>
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
        *, ::after, ::before {
            box-sizing: border-box;
            margin:0;padding:0;
        }

        * {
            margin: 0;
            padding: 0;
        }
        body{font-family: 'Open Sans', sans-serif;}
        h1,h2,h3,h3,h5,h6{font-family: 'Dancing Script', cursive;font-weight:bold}

        div#container1 {
            background: white;
            display: block;
            margin: 0 auto;
        }
        div#container1 {
            width: 210mm;
            height: 297mm;
            overflow: hidden;
        }

        .compnayName:after {
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            top: 0;
            right: -60px;
            left: 100%;
            border-top: 72px solid #cc2127;
            border-right: 60px solid transparent;
        }
        .compnayName h1{
            margin:0;line-height:1;
        }
        .toprightleftcornor::before{
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            top: 0;
            right: -60px;
            left: 100%;
            border-bottom: 77px solid #cc2127;
            border-left: 60px solid transparent;
        }
        .lefttoright::before{
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            bottom: 0;
            border-bottom: 180px solid #ffffff;
            border-right: 400px solid transparent;
        }
        .lefttoright::after{
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            bottom: 0;
             right: 0px;
            border-bottom: 267px solid #ffffff;
            border-left: 400px solid transparent;
        }
        .departure-basic-details{padding:20px;display:block;width:100%;float: left;}
        .departure-basic-details h2{font-size:1.3rem;line-height:1;margin-bottom:25px;}
        .departure-basic-details p{font-size:1rem;line-height: 1.5}
        .departure-basic-details p span{color:#b52818;}
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
        .attraction-points p, .attraction-points h3{margin:0;}


        
        #container, .pdf_container_di{
            background: #fff;
            display: block;
            margin: 0 auto;
            width: 210mm;
            height: 297mm;
            overflow: hidden;
        }
        body #container, .pdf_container_di{
            /* font-family: sans-serif; */
            font-family: 'Open Sans', sans-serif;
        }
        #container .font-change p, .pdf_container_di .font-change p{
            font-family: 'Open Sans', sans-serif;
        }
        #container h1, #container h2, #container h3, #container h4, #container h5, #container h6,
        .pdf_container_di h1, .pdf_container_di h2, .pdf_container_di h3, .pdf_container_di h4,
        .pdf_container_di h5, .pdf_container_dih6{font-family: 'Dancing Script', cursive;font-weight:bold;margin:0;line-height: 1;}
        .leftcornor:before{
            width: 0;
            content: "";
            position: absolute;
            left:-60px;;
            height: 0;
            border-bottom: 60px solid #b14e51;
            border-left: 60px solid transparent;
        }
        .toprightleftcornor::before{
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            top: 0;
            right: -60px;
            left: 100%;
            border-bottom: 77px solid #cc2127;
            border-left: 60px solid transparent;
        }
        .lefttoright::before{
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            bottom: 0;
            /* right: -60px; */
            border-bottom: 180px solid #ffffff;
            border-right: 400px solid transparent;
        }
        .lefttoright::after{
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            bottom: 0;
             right: 0px;
            border-bottom: 267px solid #ffffff;
            border-left: 400px solid transparent;
        }
        .departure-basic-details{padding:20px;display:block;width:100%;float: left;}
        .departure-basic-details h2{font-size:2.3rem;line-height:1;margin-bottom:25px;}
        .departure-basic-details p{font-size:1rem;line-height: 1;font-family: 'Open Sans', sans-serif;margin-bottom:10px;}
        .departure-basic-details p span{color:#b52818;font-family: 'Open Sans', sans-serif;margin:0;line-height:1;}
        ul.placestovisit li{
            width: 50%;
            float: left;
            display: flex;
            line-height: 1.5;
            font-size: 20px;
            align-items: center;
            margin:0;
            margin-bottom: 10px;
            font-family: 'Open Sans', sans-serif;
        }
        ul.placestovisit li img{width:50px;height:50px;object-fit:cover;margin-right:10px}
        .attraction-points p{font-family: 'Open Sans', sans-serif;font-size:13px;}
        .watermark{
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
        .DaywiseTopbg{
            height: 20%;width:100%;
            background:#484848;
            position: relative;
            padding:50px;margin-bottom: 14%;
        }
        .Daywisebottomrightbg{height: 71%;width:50%;background:#e16a61;position:relative;right:0;float: right;padding:20px;color:#fff;display: flex;align-items: center;}
        .Daywisebottomrightbg::before{
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            bottom:100%;
            right: 0px;
            border-bottom: 94px solid #e16a61;
            border-left: 10.5cm solid transparent;
        }
        .Daywisebottomrightbg p{line-height:1.1;font-size:16px;}
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
            padding: 0 22px;
            text-align: justify;
            margin:0;
        }
        .dayItineraryDesc_dook ul li:not(:last-child){margin-bottom:4px;}
        .dayItineraryDesc_dook ul li:before{
            position: absolute;
            content: "\2022";
            font-size: 20px;
            left: 7px;
            top: -9px;
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
<div size="A4" style="position: relative" id="container">
    <div class="departure-basic-details" style="">
        <h2 class="">{{$pdf_basic_detail->package_name}}</h2>
        <div style="display:flex;flex-wrap: wrap;">
            <div style="display:flex;width:50%;float:left;margin-bottom:10px;">
                <div>
                    <p style="display:flex;margin-right:10px;"><img src="{{asset('media/itinerary/package.png')}}" style="width:22px;margin-right:8px;"><span class="h2">Package Id</span>:</p>
                    <p style="display:flex;margin-right:10px;"><img src="{{asset('media/itinerary/calendar.png')}}" style="width:22px;margin-right:8px;"><span class="h2">Departs on</span>:</p>
                </div>
                <div>
                    <p style="min-height:22px;">{{$pdf_basic_detail->packageId->dep_dook_ref_id}}</p>
                    <p style="min-height:22px;">{{$pdf_basic_detail->departs}}</p>
                </div>
            </div>
            <div style="display:flex;width:50%;float:left;margin-bottom:10px;">
                <div>
                    <p style="display:flex;margin-right:10px;"><img src="{{asset('media/itinerary/time.png')}}" style="width:22px;margin-right:8px;"><span class="h2">Duration</span>:</p>
                    <p style="display:flex;margin-right:10px;"><img src="{{asset('media/itinerary/route.png')}}" style="width:22px;margin-right:8px;"><span class="h2">Starts from</span>:</p>
                </div>
                <div>
                    <p style="min-height:22px;">{{$pdf_basic_detail->total_nights}}N/{{$pdf_basic_detail->total_days}}D</p>
                    <p style="min-height:22px;">{{$pdf_basic_detail->start_from}}</p>
                </div>
            </div>
            {{-- <p style="display:flex;align-items:center;width:50%;float:left;margin-bottom:10px;"><strong style="font-size: 30px;color: #9a191e;padding:0;margin:0;margin-right:10px;">#</strong><span class="h2">Pkg Id</span>: {{$pdf_basic_detail->packageId->dep_dook_ref_id}}</p>
            <p style="display:flex;align-items:center;width:50%;float:left;margin-bottom:10px;"><img src="{{asset('media/itinerary/time.png')}}" style="width:30px;margin-right:10px;"><span class="h2">Duration</span>: {{$pdf_basic_detail->total_nights}}N/{{$pdf_basic_detail->total_days}}D</p>
            <p style="display:flex;align-items:center;width:50%;float:left;margin-bottom:10px;"><img src="{{asset('media/itinerary/calendar.png')}}" style="width:30px;margin-right:10px;"><span class="h2">Departs on</span>: {{$pdf_basic_detail->departs}}</p>
            <p style="display:flex;align-items:center;width:50%;float:left;margin-bottom:10px;"><img src="{{asset('media/itinerary/route.png')}}" style="width:30px;margin-right:10px;"><span class="h2">Starts from</span>: {{$pdf_basic_detail->start_from}}</p> --}}
        </div>
    </div>
    <div style="display: block">
        <div class="compnayName" style="position: relative;background:#cc2127;color:#fff;display: inline-block;padding: 20px;">
            <h1>Places to Visit</h1>
        </div>
        <div style="padding: 20px;display: block;float:left;width:100%;">
            <ul class="placestovisit" style="list-style: none;padding:0;margin:0;">
                <li> {{$pdf_basic_detail->place1}}</li>
                <li> {{$pdf_basic_detail->place2}}</li>
                <li> {{$pdf_basic_detail->place3}}</li>
                <li> {{$pdf_basic_detail->place4}}</li>
            </ul>
        </div>
    </div>
    <div class="compnayName" style="position: relative;background:#cc2127;color:#fff;display: inline-block;padding: 20px;">
        <h1>Top Attractions</h1>
    </div>
    <div style="padding: 20px;display:flex;flex-wrap: wrap;" class="attraction-points">

        <div style="display: flex;margin-bottom:10px;width: 376px;padding-right:7px;">
            <div>
                <div style="width:50px;height:50px;border-radius:50%;margin-right:15px;overflow:hidden;">
                    <img src="https://adm.dookinternational.com/dook/images/poi/{{$pdf_basic_detail->attraction_image1}}" style="width:50px;height:50px;object-fit:cover;">
                </div>
            </div>
            <!-- <img src="https://adm.dookinternational.com/dook/images/poi/{{$pdf_basic_detail->attraction_image1}}" style="width:50px;height:50px;object-fit:cover;border-radius:50%;float:left;margin-right:15px;"> -->
            <div style="width:304px;">
                <h3>{{$pdf_basic_detail->attraction_name1}}</h3>
                <p>{{$pdf_basic_detail->attraction_address1}}</p>
            </div>
        </div>

        <div style="display: flex;margin-bottom:10px;width: 376px;padding-left:7px;">
            <div>
                <div style="width:50px;height:50px;border-radius:50%;margin-right:15px;overflow:hidden;">
                    <img src="https://adm.dookinternational.com/dook/images/poi/{{$pdf_basic_detail->attraction_image2}}" style="width:50px;height:50px;object-fit:cover;">
                </div>
            </div>
            <div style="width:304px;">
                <h3>{{$pdf_basic_detail->attraction_name2}}</h3>
                <p>{{$pdf_basic_detail->attraction_address2}}</p>
            </div>
        </div>

        <div style="display: flex;margin-bottom:10px;width: 376px;padding-right:7px;">
            <div>
                <div style="width:50px;height:50px;border-radius:50%;margin-right:15px;overflow:hidden;">
                    <img src="https://adm.dookinternational.com/dook/images/poi/{{$pdf_basic_detail->attraction_image3}}" style="width:50px;height:50px;object-fit:cover;">
                </div>
            </div>
            <div style="width:304px;">
                <h3>{{$pdf_basic_detail->attraction_name3}}</h3>
                <p>{{$pdf_basic_detail->attraction_address3}}</p>
            </div>
        </div>

        <div style="display: flex;margin-bottom:10px;width: 376px;padding-left:7px;">
            <div>
                <div style="width:50px;height:50px;border-radius:50%;margin-right:15px;overflow:hidden;">
                    <img src="https://adm.dookinternational.com/dook/images/poi/{{$pdf_basic_detail->attraction_image4}}" style="width:50px;height:50px;object-fit:cover;">
                </div>
            </div>
            <div style="width:304px;">
                <h3>{{$pdf_basic_detail->attraction_name4}}</h3>
                <p>{{$pdf_basic_detail->attraction_address4}}</p>
            </div>
        </div>

        <div style="display: flex;margin-bottom:10px;width: 376px;padding-right:7px;">
            <div>
                <div style="width:50px;height:50px;border-radius:50%;margin-right:15px;overflow:hidden;">
                    <img src="https://adm.dookinternational.com/dook/images/poi/{{$pdf_basic_detail->attraction_image5}}" style="width:50px;height:50px;object-fit:cover;">
                </div>
            </div>
            <div style="width:304px;">
                <h3>{{$pdf_basic_detail->attraction_name5}}</h3>
                <p>{{$pdf_basic_detail->attraction_address5}}</p>
            </div>
        </div>

        <div style="display: flex;margin-bottom:10px;width: 376px;padding-left:7px;">
            <div>
                <div style="width:50px;height:50px;border-radius:50%;margin-right:15px;overflow:hidden;">
                    <img src="https://adm.dookinternational.com/dook/images/poi/{{$pdf_basic_detail->attraction_image6}}" style="width:50px;height:50px;object-fit:cover;">
                </div>
            </div>
            <div style="width:304px;">
                <h3>{{$pdf_basic_detail->attraction_name6}}</h3>
                <p>{{$pdf_basic_detail->attraction_address6}}</p>
            </div>
        </div>

    </div>
    
    <img src="{{asset('media/itinerary/page2graphic.png')}}" style="width: 100%;height:auto;position: absolute;bottom: 0;left:0;">
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