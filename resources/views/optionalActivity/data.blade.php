<div class="box-body">
            <table id="optionalitiListData" class="table table-bordered">
                <tbody>
                  <tr>
                    <th style="width: 5%">#</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th style="width: 17%">Destination</th>
                    <th style="width: 10%">Duration</th>
                    <th style="width: 10%">Status</th>
                    <th style="width: 10%">Action</th>
                  </tr>
                @if(count($optional_activity)> 0 )
                  @foreach( $optional_activity as $key => $value )
                    <tr>
                      <td>{{ ($optional_activity->currentpage()-1) * $optional_activity->perpage() + $key + 1 }}</td>
                      <td style="width: 5%"><img style="width: 100%" src="{{asset('images/uploads/optionalactivity/'.$value->image)}}" style="width: 45%"> </td>
                      <td>{{$value->title}}</td>
                       <td>{{$value->dest_name}}</td>
                      <td>{{$value->duration}} </td>
                      <td>
                        @if($value->status == '1')
                            <a class="dropdown-item edit disableOptionalActivity" data-id="{{ $value->id }}" data-status="{{ $value->id }}" title="Inactive?" style="cursor: pointer; color: #2f8263;"> Active
                            </a>
                          @else
                          <a class="dropdown-item edit disableOptionalActivity" data-id="{{ $value->id }}" data-status="{{ $value->id }}" title="Active?" style="cursor: pointer; color: #F9423C;"> Inactive
                          </a>
                          @endif
                      </td>
                      <td>
                        <a class="dropdown-item edit" href="{{route('optional_activity_edit',$value->id)}}">
                          <i class="fa fa-edit"></i> 
                        </a> 
                        
                      </td>
                    </tr>
                  @endforeach
                @endif
              </tbody>
          </table>
</div>
<div class="box-footer clearfix text-right">
    {{ $optional_activity->onEachSide(3)->links() }}
</div>

