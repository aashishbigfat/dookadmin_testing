<div class="box-body">
  <table class="table table-bordered">
    <tbody>
      
        <tr>
          <th>#</th>
          <th>Day</th>
          <th>Heading</th>
          <th>Destinations</th>
          <th>POIs</th>
          <th>Action</th>
        </tr>
      @if(count($itineraries) > 0)
        @foreach($itineraries as $itinerary)
          <tr>
            <td>{{$loop->index +1}}</td>
            <td>{{$itinerary->day_number}}</td>
            <td>{{$itinerary->day_heading}}</td>
            <td>@if(count($data_dest) > 0 )
                  @foreach($data_dest as $dest)
                    @if($itinerary->id == $dest->itinerary_id)
                      {{$dest->dest_name}} ,
                    @endif
                  @endforeach
                @endif 
            </td>
            <td>
              @if(count($data_poi) > 0 )
                @foreach($data_poi as $poi)
                  @if($itinerary->id == $poi->itinerary_id)
                    {{$poi->poi_name}} ,
                  @endif
                @endforeach
              @endif 
            </td>
            <td>
              <a class="edit-item"  data-toggle="modal" data-id="{{ $itinerary->id }}" data-daynumber="{{ $itinerary->day_number }}" data-dayheading="{{ $itinerary->day_heading }}" data-description="{{ $itinerary->description }}" data-inclusionID="{{ JSON_encode($itinerary->inclusion) }}"  data-inclusionName="{{ JSON_encode($itinerary->inclusion_name) }}" data-destinationid="{{JSON_encode($itinerary->destination_id) }}" data-destinationname="{{JSON_encode($itinerary->destination_name) }}" data-poiid="{{JSON_encode($itinerary->poi_id) }}" data-poiname="{{JSON_encode($itinerary->poi_name) }}" title="Edit details" style="cursor: pointer;margin-right: 5px;">
                  <i class="fa fa-edit"></i> 
                </a> | <a class="disableItinerary" data-id="{{ $itinerary->id }}" style="cursor: pointer; color: #dc0b0b;margin-left: 5px;">
                    <i class="fa fa-trash"></i>
              </a>
            </td>
          </tr>
        @endforeach
      @endif
    </tbody>
  </table>
</div>
<div class="box-footer clearfix">
  <ul class="pagination pagination-sm no-margin pull-right">
  </ul>
</div>