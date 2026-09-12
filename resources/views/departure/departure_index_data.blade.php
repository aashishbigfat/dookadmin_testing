<div class="box-body">
            <table id="departureListData" class="table table-bordered">
                <tbody>
                  <tr>
                    <th>#</th>
                    <th>F/B.Image</th>
                    <th style="width: 10%">Package ID</th>
                    <th>Name</th>
                    <th>N/D</th>
                    <th>Start From</th>
                    <th>Ending At</th>
                    <th style="width: 9%;">Dep Type</th>
                    <th>Dates</th>
                    <th style="width: 5%;">Status</th>
                    <th style="width: 8%">Action</th>
                  </tr>
                @if(count($departures)> 0 )
                  @foreach( $departures as $key => $departure )
                    <tr>
                      <td>{{ ($departures->currentpage()-1) * $departures->perpage() + $key + 1 }}</td>
                      <td style="width: 10%"><img style="width: 30%" src="{{generateSignedUrl('package'.$departure->image)}}"> / <img style="width: 55%" src="{{$urlS3.$departure->banner_image}}"></td>
                      <td><a href="https://www.dookinternational.com/{{$departure->slug_url_pre}}{{'/'}}{{$departure->slug_url}}{{'/'}}{{$departure->dep_dook_ref_id}}" title="View Departure" target="_blank">{{$departure->dep_dook_ref_id}}</a></td>
                      <td><a href="https://www.dookinternational.com/{{$departure->slug_url_pre}}{{'/'}}{{$departure->slug_url}}{{'/'}}{{$departure->dep_dook_ref_id}}" title="View Departure" target="_blank">{{$departure->title}}</a></td>
                      <td>
                        @if($departure->no_of_days)
                          {{$departure->no_of_nights}}N/{{$departure->no_of_days}}D
                        @else
                          0N/0D
                        @endif
                      </td>

                      <td>{{$departure->from}}</td>
                      <td>{{$departure->ending_at}}</td>
                      <td>
                        @if($departure->dc_departure_type == '1')
                          Land Flight
                        @else
                        Land Only
                        @endif
                      </td>
                      
                      <td style="text-align: center;"><a href="{{route('departure_dates',$departure->id)}}">{{$departure->totalDates}}</a></td>
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
                            <a class="dropdown-item edit" href="{{route('departure_edit',$departure->id)}}">
                                <i class="fa fa-edit"></i> 
                              Edit Departure
                            </a>
                            @if($departure->featured == '0')
                              <a class="dropdown-item edit papularPackage" data-id="{{ $departure->id }}" title="Enable?" style="cursor: pointer;"><i class="fa fa-check"></i> Featured Up
                              </a>
                            @else
                              <a class="dropdown-item edit papularPackage" data-id="{{ $departure->id }}" title="Disable?" style="cursor: pointer;"><i class="fa fa-close"></i> Featured Down
                              </a>
                            @endif
                            <br>
                            <form id="exp-form-{{ $departure->id }}" method="post" action="{{route('popular_home',$departure->id)}}" style="display: none;">
                              @csrf

                              {{method_field('POST')}} <!-- posts query -->
                            </form>
                            <?php 
                                $showHide = $departure->popular_at_home == 1?'remove from home':'add to home'; 
                                $addRemove = $departure->popular_at_home == 1?'Remove from Home':'Add to Home';
                                $closeCheck = $departure->popular_at_home == 1?'fa fa-close':'fa fa-check'; 
                                $colors = $departure->popular_at_home == 1?'color:red':''; 
                            ?>
                            <a href="" class="dropdown-item edit" onclick="
                              if (confirm('Are you sure, You want to {{$showHide}} ?'))
                                {
                                  event.preventDefault();
                                  document.getElementById('exp-form-{{ $departure->id }}').submit();
                                }
                                else
                                {
                                  event.preventDefault();
                                }
                              " style="cursor: pointer;" title="{{$addRemove}}">
                                <i style="{{$colors}}" class="{{$closeCheck}}"></i> {{$addRemove}}
                            </a>


              @if($departure->emt == '0')
              <a class="dropdown-item edit addToEMT" data-id="{{ $departure->id }}" style="cursor: pointer;"><i
                  class="fa fa-check"></i> Add to EMT
              </a>
              @else
              <a class="dropdown-item edit addToEMT" data-id="{{ $departure->id }}" style="cursor: pointer;"><i
                  class="fa fa-close"></i> Remove from EMT
              </a>
              @endif
              
                        </div>
                      </span>
                      <span>@if($departure->featured == 1) <span style="color: #0ea31a;font-weight: bold;">F({{
              $departure->featured_position == 1000 ?'':$departure->featured_position}})</span>
            @endif</span><span>@if($departure->popular_at_home == 1) <span style="color: #0ea31a;font-weight: bold;">|
              PH</span> @endif</span><span>@if($departure->emt == 1) <span style="color: #0ea31a;font-weight: bold;">|
              EMT</span> @endif</span>
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

<script type="text/javascript">
        $(".disableDepartue").click(function () {
          var id = $(this).data("id");
            //console.log(id);
            var flag = status?'inactive':'active';
            var token = $("meta[name='csrf-token']").attr("content");
            if (confirm("Are you sure you want to "+flag+" this Package?"))
              $.ajax(
              {
                url: '/packages-disable/' + id,
                type: 'POST',
                data: {
                    "id": id,
                    "_token": token,
                },
                success: function (data) {
                  window.location.reload();
                }
              });
        });
    </script>
    <script type="text/javascript">
        $(".papularPackage").click(function () {
          var id = $(this).data("id");
            var token = $("meta[name='csrf-token']").attr("content");
            if (confirm("Are you sure you want to change?"))
              $.ajax(
              {
                url: '/make-featured-package/' + id,
                type: 'POST',
                data: {
                    "id": id,
                    "_token": token,
                },
                success: function (data) {
                  window.location.reload();
                }
              });
        });
    </script>