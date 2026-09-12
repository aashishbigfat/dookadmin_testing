<div class="box-body">
  <table class="table table-bordered" id="DeparturePoisData">
    <tbody>
      @if(count($poi_list) > 0)
      <tr>
          <th>#</th>
          <th style="width: 8%">Image</th>
          <th>POIs Name</th>
          <th>Destination</th>
          <th>Experiences</th>
          <th>Country</th>
          <th>Rating</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
        @foreach($poi_list as $key => $poi)
          <tr>
            <td>{{ ($poi_list->currentpage()-1) * $poi_list->perpage() + $key + 1 }}</td>
           
            <td>
              @if($poi->image)
                <img src="{{$urlS3.$poi->image}}" alt="" style="width: 75%;">
              @else
                <img src="{{asset('images/464X260.png')}}" alt="" style="width: 75%;">
              @endif
            </td>
            <td>{{$poi->poi_name}}</td>
            <td>{{$poi->dest_name}}</td>
            <td>@if(count($exp_dest) > 0 )
                  @foreach($exp_dest as $exp)
                    @if($poi->dest_id == $exp->destination_id)
                      {{$exp->experience_name}},
                    @endif
                  @endforeach
                @endif 
            </td>
            <td>{{$poi->country_name}}</td>
            <td>{{$poi->rating}}</td>
            <td>
              @if($poi->status == '1')
                <a class="disablepoi" data-id="{{ $poi->id }}" data-status="{{ $poi->status }}" style="cursor: pointer; color: #2f8263;"> Active
                </a>
              @else
                <a class="disablepoi" data-id="{{ $poi->id }}" data-status="{{ $poi->status }}" style="cursor: pointer; color: #F9423C;"> Inactive
                </a>
              @endif
            </td>
            <td>
              <span class="dropdown">
                  <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                    <i class="fa fa-ellipsis-h" aria-hidden="true" style="color:red"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item edit editPoi"  data-toggle="modal" data-id="{{ $poi->id }}" data-routeId="{{ request()->route('id') }}" data-poiId="{{ $poi->poi_id }}"  data-poiname="{{$poi->poi_name}}" data-address="{{$poi->address}}" data-description="{{$poi->description}}" data-image="{{$poi->image}}" data-bannerimage="{{$poi->banner_image}}" data-destinationid="{{ $poi->dest_id }}" data-destinationname="{{ $poi->dest_name }}" data-expid="{{JSON_encode($poi->experiences_id) }}" data-expname="{{JSON_encode($poi->experience_name) }}" title="Edit details" style="cursor: pointer;">
                      <i class="fa fa-edit"></i> 
                        Edit POI
                    </a>
                  </div>
              </span>
            </td>
          </tr>
        @endforeach
      @else
      <tr>
          <th>#</th>
          <th>Destination</th>
          <th>Experiences</th>
          <th>Action</th>
        </tr>
        @foreach($exp_dest as $key => $dest)
          <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{$dest->dest_name}}</td>
            <td>@if(count($dest->experiences) > 0 )
                  @foreach($dest->experiences as $exp)
                    @if($dest->id == $exp->destination_id)
                      {{$exp->experience_name}},
                    @endif
                  @endforeach
                @endif 
            </td>
            <td>
              <a class="deleteExp"  data-toggle="modal" data-id="{{ $dest->id }}" data-routeId="{{ request()->route('id') }}" title="Delete Row" style="cursor: pointer;">
                <i class="fa fa-trash"></i> 
              </a>
            </td>
          </tr>
        @endforeach
      @endif
    </tbody>
  </table>
</div>
<div class="box-footer clearfix text-right">
    {{ $poi_list->onEachSide(3)->links() }}
</div>