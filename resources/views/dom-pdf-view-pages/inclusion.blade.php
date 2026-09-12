@if(count($inclusion_data)>0)
<div class="design_1 pdf_container inclusion_detail">
    <div class="bg_heading">
        <h3>Inclusions</h3>
    </div>
    <?php $inclusion_p_count = count($inclusion_data) / 2;$inclusion_p_start = 0;$inclusion_p_end = 2; ?>
    <table class="a_t_poi t_inclusion">
        @for($i=0; $i < $inclusion_p_count; $i++)
        <tr>
            @foreach($inclusion_data as $key => $inclusion)
                @if($key == $inclusion_p_start && $inclusion_p_start < $inclusion_p_end)
                <td class="top">
                    <table>
                        <tr>
                            <td class="top">
                                <img src="{{asset('media/itinerary/check-mark.png')}}" alt="icon" class="t_icon">
                            </td>
                            <td><p>{{$inclusion->name}}</p></td>
                        </tr>
                    </table>
                </td>
                <?php $inclusion_p_start = $inclusion_p_start + 1; ?>
                @endif
            @endforeach
        </tr>
        <?php $inclusion_p_end = $inclusion_p_start + 2; ?>
        @endfor
    </table>

    {{-- <table class="t_inclusion">
        @foreach($inclusion_data as $inc)
        <tr>
            <td>
                <img src="{{asset('media/itinerary/check-mark.png')}}" alt="icon" class="t_icon">
            </td>
            <td><p>{{$inc->name}}</p></td>
        </tr>
        @endforeach
    </table>--}}
</div>
@endif