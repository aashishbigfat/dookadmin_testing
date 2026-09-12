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
        h1,h2,h3,h3,h5,h6{font-family: 'Dancing Script', cursive;font-weight:bold;margin:0;line-height:1;}
        div#container {
            background: white;
            display: block;
            margin: 0 auto;
        }
        div#container {
            width: 210mm;
            height: 297mm;
            overflow: hidden;
        }
        .inclusions{position: relative;}
        .inclusions-img{width: 60%;height: 450px;margin-left: auto;display: flex;}
        .inclusions-img img{width:100%;height:100%;object-fit:cover;}
        .inclusions ul.inclusions-list{min-height: 300px;left: 50px;bottom: 50px;padding:20px;width:100%}
        ul.inclusions-list li{width:100%;
            /* font-size:22px; */
            font-size:16px;
            /* font-weight:bold; */
            display:block;/*color:#fff;*/}
        ul.inclusions-list li::before{content:"\21D2";position: relative;color: #da7270;margin-right: 5px;}
        ul.inclusions-list li img{width:20px;margin-right:10px;top: 2px;position: relative;}
        .compnayName:after {
            width: 0;
            content: "";
            position: absolute;
            height: 0;
            top: 0;
            right: -60px;
            left: 100%;
            border-top: 64px solid #cc2127;
            border-right: 60px solid transparent;
        }
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
    @if(!$pdf_inclusion->isEmpty())
    <div style="display: block">
        <div class="compnayName" style="position: relative;background:#cc2127;color:#fff;display: inline-block;padding: 16px;">
            <h1>Inclusions</h1>
        </div>
        <div class="inclusions">
            <ul class="inclusions-list">
                @foreach($pdf_inclusion as $inc)
                    <li>{{$inc->name}}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
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
