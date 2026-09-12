@include('pdffiles.banner')
@include('pdffiles.basic-details')
@foreach($itinerary as $daywise)
	@php $day_number = "pdffiles.day".$daywise->day_number; @endphp
	@include($day_number)
@endforeach
@foreach($hotels as $hotelwise)
	@if($loop->index == 0)
		@include('pdffiles.hotel1')
	@endif
	@if($loop->index == 2)
		@include('pdffiles.hotel2')
	@endif
	@if($loop->index == 4)
		@include('pdffiles.hotel3')
	@endif
	@if($loop->index == 6)
		@include('pdffiles.hotel4')
	@endif
	@if($loop->index == 8)
		@include('pdffiles.hotel5')
	@endif
	@if($loop->index == 10)
		@include('pdffiles.hotel6')
	@endif
	@if($loop->index == 12)
		@include('pdffiles.hotel7')
	@endif
	@if($loop->index == 14)
		@include('pdffiles.hotel8')
	@endif
	@if($loop->index == 16)
		@include('pdffiles.hotel9')
	@endif
	@if($loop->index == 18)
		@include('pdffiles.hotel10')
	@endif
	@if($loop->index == 20)
		@include('pdffiles.hotel11')
	@endif
@endforeach
@foreach($flights as $flightwise)
	@if($loop->index == 0)
		@include('pdffiles.flight1')
	@endif
	@if($loop->index == 4)
		@include('pdffiles.flight2')
	@endif
	@if($loop->index == 8)
		@include('pdffiles.flight3')
	@endif
	@if($loop->index == 12)
		@include('pdffiles.flight4')
	@endif
	@if($loop->index == 16)
		@include('pdffiles.flight5')
	@endif
@endforeach
@if(!$inclusion->isEmpty() && $exclusion !== null)
	@include('pdffiles.inclusion')
@endif
@if($terms !== null)
	@include('pdffiles.terms-conditions')
@endif
@include('pdffiles.contact')