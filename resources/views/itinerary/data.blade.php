<div class="box-body">
            <table id="agentitiListData" class="table table-bordered">
                <tbody>
                  <tr>
                    <th style="width: 5%">#</th>
                    <th>Title</th>
                    <th style="width: 10%">View</th>
                    <th style="width: 15%">Status</th>
                    <th style="width: 10%">Action</th>
                  </tr>
                @if(count($agent_itinerary)> 0 )
                  @foreach( $agent_itinerary as $key => $agentitinerary )
                    <tr>
                      <td>{{ ($agent_itinerary->currentpage()-1) * $agent_itinerary->perpage() + $key + 1 }}</td>
                      <td>{{$agentitinerary->title}}</td>
                      <td><a class="view_pdf" target="_blank" href="{{asset('agentitinerary/'.$agentitinerary->pdf_file)}}">
                          View
                        </a></td>
                      <td>
                        @if($agentitinerary->status == '1')
                            <a class="dropdown-item edit disableAgentItinerary" data-id="{{ $agentitinerary->id }}" data-status="{{ $agentitinerary->id }}" title="Inactive?" style="cursor: pointer; color: #2f8263;"> Open
                            </a>
                          @else
                          <a class="dropdown-item edit disableAgentItinerary" data-id="{{ $agentitinerary->id }}" data-status="{{ $agentitinerary->id }}" title="Active?" style="cursor: pointer; color: #F9423C;"> Close
                          </a>
                          @endif
                      </td>
                      <td>
                        <a class="dropdown-item edit" href="{{route('agent_itinerary_edit',$agentitinerary->id)}}">
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
    {{ $agent_itinerary->onEachSide(3)->links() }}
</div>

