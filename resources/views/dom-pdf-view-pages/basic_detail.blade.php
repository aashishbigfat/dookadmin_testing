<div class="design_1 pdf_container basic_detail">
    <h2 class="h_title">{{$departure->title}}</h2>
    <table class="a_t_poi t_basic">
        <tr>
            <td>
                <table>
                    <tr>
                        <td>
                            <div class="img"><img src="{{asset('media/itinerary/package.png')}}" alt="icon"></div>
                        </td>
                        <td><p><strong>Package Id:</strong> {{$departure->dep_dook_ref_id}}</p></td>
                    </tr>
                </table>
            </td>
            <td>
                <table>
                    <tr>
                        <td>
                            <div class="img"><img src="{{asset('media/itinerary/time.png')}}" alt="icon"></div>
                        </td>
                        <td><p><strong>Duration:</strong> {{$departure->no_of_nights}}Night/{{$departure->no_of_days}}Day</p></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table>
                    <tr>
                        <td>
                            <div class="img"><img src="{{asset('media/itinerary/calendar.png')}}" alt="icon"></div>
                        </td>
                        <td><p><strong>Departs on:</strong> @if($departure->departs!="NA"){{date('d-M-Y', strtotime($departure->departs))}}@else{{$departure->departs}}@endif</p></td>
                    </tr>
                </table>
            </td>
            <td>
                <table>
                    <tr>
                        <td>
                            <div class="img"><img src="{{asset('media/itinerary/route.png')}}" alt="icon"></div>
                        </td>
                        <td><p><strong>Starts from:</strong> {{$departure->from}}</p></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    @if(count($departure->dates)>0)
    <div class="bg_heading">
        <h3>Travel Dates</h3>
    </div>
    <table>
        <tr>
            <td style="padding-left: 40px;padding-right: 40px;"><p>
                @foreach($departure->dates as $key => $row)
                    @if($key < count($departure->dates) -1)
                    <span><strong>{{date('d-M-Y', strtotime($row->date))}}</strong> | </span>
                    @else
                    <span><strong>{{date('d-M-Y', strtotime($row->date))}}</strong></span>
                    @endif
                @endforeach
                </p>
            </td>
        </tr>
    </table>
    @endif
    <div class="bg_heading">
        <h3>Places to Visit</h3>
    </div>
    <?php $basic_d_count = count($destinations) / 2;$basic_d_start = 0;$basic_d_end = 2; ?>
    <table class="a_t_poi t_place">
        @for($i=0; $i<$basic_d_count; $i++)
        <tr>
            @foreach($destinations as $key => $destination)
                @if($key == $basic_d_start && $basic_d_start < $basic_d_end)
                <td><p>{{$destination->dest_name}}</p></td>
                <?php $basic_d_start = $basic_d_start + 1; ?>
                @endif
            @endforeach
        </tr>
        <?php $basic_d_end = $basic_d_start + 2;?>
        @endfor
    </table>

    <div class="bg_heading">
        <h3>Top Attractions</h3>
    </div>
    <?php $basic_p_count = count($pkg_pois) / 2;$basic_p_start = 0;$basic_p_end = 2; ?>
    <table class="t_attraction a_t_poi">
        @for($i=0; $i < $basic_p_count; $i++)
        <tr>
            @foreach($pkg_pois as $key => $pkg_poi) 
                @if($key == $basic_p_start && $basic_p_start < $basic_p_end)
                <td class="top">
                    <table>
                        <tr>
                            <td class="top">
                                <div class="img t_img" style="background-image: url('{{$pkg_poi->image}}');"></div>
                            </td>
                            <td class="top">
                                <h3 class="t_title">{{$pkg_poi->poi_name}}</h3>
                                <p class="t_decs">{{$pkg_poi->address}}</p>
                            </td>
                        </tr>
                    </table>
                </td>
                <?php $basic_p_start = $basic_p_start + 1; ?>
                @endif
            @endforeach
        </tr>
        <?php $basic_p_end = $basic_p_start + 2; ?>
        @endfor
    </table>
</div>