<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>Itinerary</title>
		<link href='http://fonts.googleapis.com/css?family=Raleway:400,500,700,800,900,300 | Lato:400,700,300italic| Playfair+Display:400,700,900,400italic,700italic,900italic' rel='stylesheet' type='text/css' />
		<link href='http://fonts.googleapis.com/css?family=Raleway:400,500,700,800,900,300 | Lato:400,700,300italic| Playfair+Display:400,700,900,400italic,700italic,900italic' rel='stylesheet' type='text/css' />
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Darker+Grotesque:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Redressed&display=swap" rel="stylesheet">
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
		<style>
			*, ::after, ::before {
				box-sizing: border-box;
				margin:0;padding:0;
			}
			div#container{
				background: #fff;
				display: block;
				margin: 0 auto;
				width: 210mm;
				height: 297mm;
				overflow: hidden;
			}
			body div#container{
				font-family: 'Open Sans', sans-serif;
			}
			#container h1, #container h2, #container h3,
			#container h4, #container h5, #container h6{font-family: 'Dancing Script', cursive;font-weight:bold;margin:0;}
			.compnayName:after{
				width: 0;
				content: "";
				position: absolute;
				height: 0;
				top: 0;
				right: -60px;
				left: 100%;
				border-top: 77px solid #cc2127;
				border-right: 60px solid transparent;
			}
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
			.departure-basic-details p{font-size:1.5rem;line-height: 1.5}
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
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script src="{{asset('js/html2canvas.js')}}"></script>

	</head>
	<body>
		<div size="A4" style="position: relative" id="container">
			<div class="" style="position: relative;width: 100%;height: 100%">
				<div class="leftcornor" style="background: #b14e51;height: 50px;width: 80%;position: absolute;bottom:0;right: 0;border:0;outline:0;"></div>
				<div style="width:100%;height:622px;overflow:hidden;">
					<img src="https://adm.dookinternational.com/dook/images/package/{{$pdf_banner->banner_image}}" alt="" style="width: 100%;height:100%;object-fit: cover;">
				</div>
				<img src="{{asset('media/itinerary/cover_graphic.png')}}" alt="" style="max-width: 100%;position: absolute;top: 0;left: 0;width: 100%;">
				<div style="position: absolute;bottom:50px;right: 80px;width: 370px;text-align: center;color: #fff;">
					<img src="https://www.dookinternational.com/images/logo.png" alt="" style="max-width:100%;height: auto;margin:0 auto 12px;">
					<h1 style="font-size:52px;line-height:1;margin:0;margin-bottom:8px;">{{$pdf_banner->company_name}}</h1>
				</div>
				<div style="position: absolute;bottom:60px;left:25px;width: 270px;color: #000;">
					@if($pdf_banner->phone != '')
						<p style="line-height: 2;display:flex;align-items:center;font-size:14px;"><img src="{{asset('media/itinerary/phone.png')}}" alt="" style="width: 20px;"> &nbsp;<span style="margin-left: 3px;line-height:1;">{{$pdf_banner->phone}}</span></p>
					@endif
					@if($pdf_banner->w_mobile != '')
						<p style="line-height: 2;display:flex;align-items:center;font-size:14px;"><img src="{{asset('media/itinerary/phone.png')}}" alt="" style="width: 20px;"> &nbsp;<span style="margin-left: 3px;line-height:1;">{{$pdf_banner->w_mobile}}</span></p>
					@endif
					@if($pdf_banner->email != '')
						<p style="line-height: 2;display:flex;align-items:center;font-size:14px;"><img src="{{asset('media/itinerary/email.png')}}" alt="" style="width: 20px;"> &nbsp;<span style="margin-left: 3px;line-height:1;">{{$pdf_banner->email}}</span></p>
					@endif
					@if($pdf_banner->website != '')
						<p style="line-height: 2;display:flex;align-items:center;font-size:14px;"><img src="{{asset('media/itinerary/website.png')}}" alt="" style="width: 20px;"> &nbsp;<span style="margin-left: 3px;line-height:1;">{{$pdf_banner->website}}</span></p>
					@endif
				</div>
			</div>
			<div class="watermark">
				Dook International
			</div>
			<a href="https://www.tutterflycrm.com/" class="btm_powered">Powered By:
				<div style="margin: 0;display:flex;align-items:flex-end;">
					<img src="{{asset('images/tutterfly_logo.png')}}">
					<strong>Tutterfly CRM</strong>
				</div>
			</a>
		</div>
	</body>
</html>