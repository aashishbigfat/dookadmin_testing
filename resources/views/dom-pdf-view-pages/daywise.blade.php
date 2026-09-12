<?php
    $promote_img = array('Departure-Cloud.jpg','Agent-Connect-Banner.jpg','DookEdu-Banner.jpg');
    $promote_start = 0;
    $promote_end = 2;
?>
@foreach($itinerary_data as $key => $itinerary) 
<div class="design_1 pdf_container daywise_detail">
    <table class="a_t_poi day_banner">
        <tr>
            <td>
                @if(count($itinerary->day_pois)>0)
                    <img class="img banner_img" src="{{$itinerary->day_image}}">
                @else
                    <img class="img banner_img" src="{{$departure->banner_imageD}}">
                @endif
            </td>
            <td>
                <h2 class="day_title">Day {{$itinerary->day_number}}: <strong>{{$itinerary->day_heading}}</strong></h2>
                <div class="desc">
                    {!! $itinerary->description !!}
                </div>
            </td>
        </tr>
    </table>

   
    <h2 class="p_day">Highlights of the day:</h2>
    <table class="a_t_poi t_pois">
        <tr>
            @foreach($itinerary->day_pois as $key => $top_pois)
                <td class="top">
                    <table>
                        <td class="top">
                            <img class="img t_img" src="{{$top_pois->image}}">
                        </td>
                        <td class="top">
                            <h3 class="t_title" id="{{$top_pois->image}}">{{$top_pois->poi_name}}</h3>
                            <p class="t_decs">{{$top_pois->address}}</p>
                        </td>
                    </table>
                </td>
            @endforeach
        </tr>
    </table>
    
</div>
@endforeach