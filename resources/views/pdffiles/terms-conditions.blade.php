<head>
<meta charset="utf-8">
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
        .terms-condition ul li{
            position: relative;
            font-size: 15px;
            text-align: justify;
            padding: 0 18px;
            line-height:1.3;
        }
        .terms-condition ul{
            list-style-type: none;
        }
        .terms-condition ul li:before{
            position: absolute;
            content: "\2022";
            font-size: 36px;
            left: 6px;
            top: -16px;
            color: #333;
        }
        .terms-condition ul li::marker{
            position: absolute;
            content: "";
            display: none;
        }
        .terms-condition ul li::marker{display: none !important;visibility: hidden;}
        .dayItineraryDesc_dook{
            margin-top: 13px;
        }
        .dayItineraryDesc_dook ul{
            list-style-type: none !important;
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
            top: -6px;
            color: #333;
        }
        .terms-condition.dayItineraryDesc_dook h4{margin:8px 0 5px;}
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
        .dayItineraryDesc_dook_terms ul li{
            padding: 0 22px;
        }
        .dayItineraryDesc_dook_terms ul li:before{
            left: 6px;
        }
    </style>
</head>
<body>
<div size="A4" style="position: relative" id="containerterms">
    <div class="terms-condition" style="box-shadow:0px 0px 5px 0px #ddd;margin:10px;padding:10px 18px;font-size:16px;font-weight:500;line-height:1.3">
        <h2 style="margin-bottom:3px;">Travel terms and conditions</h2>
        <div class="dayItineraryDesc_dook dayItineraryDesc_dook_terms">{!! $pdf_terms->terms !!}</div>
    </div>    
    {{--<img src="{{asset('media/itinerary/page2graphic.png')}}" style="width: 100%;height:auto;position: absolute;bottom: 0;left:0;">--}}
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
