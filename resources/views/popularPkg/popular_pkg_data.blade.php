<div class="box-body">
            <table id="departureListData" class="table table-bordered">
                <tbody>
                  <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th style="width: 10%">Package ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Days/Nights</th>
                    <th>Departure Type</th>
                    <th>Status</th>
                    <th style="width: 5%">Action</th>
                  </tr>
                @if(count($departures)> 0 )
                  @foreach( $departures as $key => $departure )
                    <tr>
                      <td>{{ ($departures->currentpage()-1) * $departures->perpage() + $key + 1 }}</td>
                      <td style="width: 5%"><img style="width: 100%" src="{{asset('images/uploads/departure/'.$departure->image)}}"></td>
                      <td>{{$departure->dep_dook_ref_id}}</td>
                      <td>{{$departure->title}}</td>
                      <td>
                        @if($departure->price)
                          {{$departure->price_currency}} {{$departure->price}}
                        @endif
                      </td>
                      <td>
                        @if($departure->no_of_days)
                          {{$departure->no_of_days}}D/{{$departure->no_of_nights}}N
                        @else
                          0D/0N
                        @endif
                      </td>
                      <td>
                        @if($departure->dep_type == 'main')
                          Fixed Departure
                        @elseif($departure->dep_type == 'group')
                          Group Tour
                        @else
                          Package
                        @endif
                      </td>
                      <td>
                        @if($departure->status == '1')
                            <a class="dropdown-item edit disableDepartue" data-id="{{ $departure->id }}" data-status="{{ $departure->status }}" title="Inactive?" style="cursor: pointer; color: #2f8263;"> Active
                            </a>
                        @else
                          <a class="dropdown-item edit disableDepartue" data-id="{{ $departure->id }}" data-status="{{ $departure->status }}" title="Active?" style="cursor: pointer; color: #F9423C;"> Inactive
                          </a>
                        @endif
                      </td>
                      
                      <td>
                        <span class="dropdown">
                          <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                            <i class="fa fa-ellipsis-h" aria-hidden="true" style="color:red"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-right">
                            @if($departure->dep_type == 'package')
                            <a class="dropdown-item edit" href="{{route('packages_edit',$departure->id)}}">
                                <i class="fa fa-edit"></i> 
                              Edit Package
                              </a>
                              
                            @elseif($departure->dep_type == 'group')
                              <a class="dropdown-item edit" href="{{route('group_packages_edit',$departure->id)}}">
                                <i class="fa fa-edit"></i> 
                              Edit Group Tour
                              </a>
                              
                            @else
                              <a class="dropdown-item edit" href="{{route('departure_edit',$departure->id)}}">
                                  <i class="fa fa-edit"></i> 
                                Edit Departure
                              </a>
                            @endif
                            <a class="dropdown-item edit removePapularPackage" data-id="{{ $departure->id }}" title="Remove?" style="cursor: pointer;"><i class="fa fa-close"></i> Remove
                            </a>
                        </div>
                      </span>
                      </td>
                    </tr>
                  @endforeach
                @endif
              </tbody>
          </table>
</div>
<div class="box-footer clearfix text-right">
    {{ $departures->onEachSide(3)->links() }}
</div>

