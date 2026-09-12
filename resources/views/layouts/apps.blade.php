<!DOCTYPE html>
<html>
<head>
	@include('layouts.head')
</head>
<body class="hold-transition skin-blue sidebar-mini">
	<div id="loader" class="group_visa_loader" style="display: none;">
		<!-- <img src="{{asset('images/loaders.gif')}}"> -->
		<div class="cssload-container">
			<div class="cssload-whirlpool"></div>
		</div>
	</div>
<div class="wrapper">
	@include('layouts.header')
	@include('layouts.sidebar')
	@section('main-content')
		@show
	@include('layouts.footer')
</div>
</body>
<style type="text/css">
	.group_visa_loader{
		position: fixed;
	    width: 100%;
	    height: 100vh;
	    background: #00000036;
	    z-index: 10300;
	    top: 0;
	    display: flex;
	}	

.cssload-container{
	/*position:relative;*/
	margin:auto;
}
	
.cssload-whirlpool,
.cssload-whirlpool::before,
.cssload-whirlpool::after {
	position: absolute;
	top: 50%;
	left: 50%;
	border: 2px solid rgb(212 25 6);
    border-left-color: rgb(39 255 0);
	border-radius: 974px;
		-o-border-radius: 974px;
		-ms-border-radius: 974px;
		-webkit-border-radius: 974px;
		-moz-border-radius: 974px;
}

.cssload-whirlpool {
	margin: -24px 0 0 -24px;
	height: 80px;
    width: 80px;
	animation: cssload-rotate 1150ms linear infinite;
		-o-animation: cssload-rotate 1150ms linear infinite;
		-ms-animation: cssload-rotate 1150ms linear infinite;
		-webkit-animation: cssload-rotate 1150ms linear infinite;
		-moz-animation: cssload-rotate 1150ms linear infinite;
}

.cssload-whirlpool::before {
	content: "";
	margin: -22px 0 0 -22px;
	height: 43px;
	width: 43px;
	animation: cssload-rotate 1150ms linear infinite;
		-o-animation: cssload-rotate 1150ms linear infinite;
		-ms-animation: cssload-rotate 1150ms linear infinite;
		-webkit-animation: cssload-rotate 1150ms linear infinite;
		-moz-animation: cssload-rotate 1150ms linear infinite;
}

.cssload-whirlpool::after {
	content: "";
	margin: -28px 0 0 -28px;
	height: 55px;
	width: 55px;
	animation: cssload-rotate 2300ms linear infinite;
		-o-animation: cssload-rotate 2300ms linear infinite;
		-ms-animation: cssload-rotate 2300ms linear infinite;
		-webkit-animation: cssload-rotate 2300ms linear infinite;
		-moz-animation: cssload-rotate 2300ms linear infinite;
}



@keyframes cssload-rotate {
	100% {
		transform: rotate(360deg);
	}
}

@-o-keyframes cssload-rotate {
	100% {
		-o-transform: rotate(360deg);
	}
}

@-ms-keyframes cssload-rotate {
	100% {
		-ms-transform: rotate(360deg);
	}
}

@-webkit-keyframes cssload-rotate {
	100% {
		-webkit-transform: rotate(360deg);
	}
}

@-moz-keyframes cssload-rotate {
	100% {
		-moz-transform: rotate(360deg);
	}
}
</style>
</html>