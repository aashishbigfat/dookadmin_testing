<?php
    $promote_img = array('Departure-Cloud.jpg','Agent-Connect-Banner.jpg','DookEdu-Banner.jpg');
    $promote_start = 0;
    $promote_end = 2;
?>
@foreach($itinerary_data as $key => $itinerary)
<div class="design_1 pdf_container daywise_detail">
    <table class="day_banner">
        <tr>
            <td>
                
                @if(count($itinerary->day_pois)>0)
                    <div style="background-image:url('dook/{{$itinerary->day_image}}');" class="img banner_img"></div>
                @else
                    <div style="background-image:url('dook/{{$departure->poi_image}}');" class="img banner_img"></div>
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
    @if(count($itinerary->day_pois)>0)
    <h2 class="p_day">Highlights of the day:</h2>
    <?php $day_p_count = count($itinerary->day_pois) / 2;$day_p_start = 0;$day_p_end = 2; ?>
    <table class="a_t_poi t_pois">
        @for($i=0; $i<$day_p_count; $i++)
        <tr>
            @foreach($itinerary->day_pois as $key => $top_pois)
                @if($key == $day_p_start && $day_p_start < $day_p_end)
                <td class="top">
                    <table>
                        <td class="top">
                            <div class="img t_img" style="background-image:url('dook/{{$top_pois->image}}')"></div>
                        </td>
                        <td class="top">
                            <h3 class="t_title" id="{{$top_pois->image}}">{{$top_pois->poi_name}}</h3>
                            <p class="t_decs">{{$top_pois->address}}</p>
                        </td>
                    </table>
                </td>
                <?php $day_p_start = $day_p_start + 1; ?>
                @endif
            @endforeach
        </tr>
        <?php $day_p_end = $day_p_start + 2;?>
        @endfor
    </table>
    @endif
</div>
@endforeach