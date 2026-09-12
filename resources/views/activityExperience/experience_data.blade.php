<div class="box-body">
            <table id="departureListData" class="table table-bordered">
                <thead>
                  <tr>
                    <th style="width: 2%">#</th>
                    <th style="width: 5%;">Featured Image</th>
                    <th style="width: 5%;">Banner Image</th>
                    <th style="width: 10%;">Experiences</th>
                    <th style="width: 10%;">Slug URL</th>
                    <th style="width: 5%;">Order</th>
                    <th style="width: 5%;">Status</th>
                    <th style="width: 15%; text-align: center;">Action</th>
                  </tr>
                </thead>
                <tbody class="row_position">
                @if(count($experiences)> 0 )
                  @foreach( $experiences as $key => $experience )
                    <tr id="{{ $experience->id }}">
                      <td>{{ ($experiences->currentpage()-1) * $experiences->perpage() + $key + 1 }}</td>
                      <td style="width: 6%"><img style="width: 35%" src="{{generateSignedUrl('experience/'.$experience->image)}}"></td>
                      <td style="width: 8%"><img style="width: 33%" src="{{generateSignedUrl('experience/'.$experience->banner_image)}}"></td>
                      <td>{{$experience->experience_name}}</td>
                      <td>{{$experience->slug_url}}</td>
                      <td>{{$experience->sorting}}</td>
                      <td>
                        @if($experience->status == '1')
                            <a class="dropdown-item edit disableActivity" data-id="{{ $experience->id }}" data-status="{{ $experience->status }}" title="Inactive?" style="cursor: pointer; color: #2f8263;"> Active
                            </a>
                        @else
                          <a class="dropdown-item edit disableActivity" data-id="{{ $experience->id }}" data-status="{{ $experience->status }}" title="Active?" style="cursor: pointer; color: #F9423C;"> Inactive
                          </a>
                        @endif
                      </td>
                      <td style="text-align: center;">
                        <a class="dropdown-item edit" href="{{route('experience_edit',$experience->id)}}">
                          <i class="fa fa-edit"></i> 
                        </a>   
                        {{-- |<form id="exp-form-{{ $experience->id }}" method="post" action="{{route('exp_show_at_home',$experience->id)}}" style="display: none;">
                              @csrf

                              {{method_field('POST')}} <!-- posts query -->
                        </form>
                        <?php 
                            $showHide = $experience->show_at_home == 1?'hide from home':'show at home'; 
                            $addRemove = $experience->show_at_home == 1?'Remove':'Add';
                            $closeCheck = $experience->show_at_home == 1?'fa fa-close':'fa fa-check'; 
                            $colors = $experience->show_at_home == 1?'color:red':''; 
                        ?>
                        <a href="" style="cursor: pointer;background: #d130ac36;padding: 2px;}" onclick="
                          if (confirm('Are you sure, You want to {{$showHide}} ?'))
                            {
                              event.preventDefault();
                              document.getElementById('exp-form-{{ $experience->id }}').submit();
                            }
                            else
                            {
                              event.preventDefault();
                            }
                          " style="cursor: pointer;" title="{{$addRemove}}">
                            <i class="fa fa-home"></i> <i style="{{$colors}}" class="{{$closeCheck}}"></i>
                        </a>--}}
                       <!--  <a class="dropdown-item edit experienceEdit"  data-toggle="modal" data-id="{{ $experience->id }}" data-name="{{ $experience->experience_name }}" data-description="{{ $experience->description }}" data-image="{{ $experience->image }}" data-banner="{{ $experience->banner_image }}" title="Edit Experience" style="cursor: pointer;">
                          <i class="fa fa-edit"></i> 
                        </a> -->
                      </td>
                    </tr>
                  @endforeach
                @endif
              </tbody>
          </table>
</div>
<div class="box-footer clearfix text-right">
    {{ $experiences->links() }}
</div>

