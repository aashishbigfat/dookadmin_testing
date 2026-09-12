<div class="box-body">
    <table id="departureListData" class="table table-bordered">
        <tbody>
          <tr>
            <th>#</th>
            <th>F.Image</th>
            <th>B.Image</th>
            <th style="width: 10%">Package ID</th>
            <th>Name</th>
            <th>N/D</th>
            <th>From</th>
            <th>Ending-At</th>
            <th>Popular</th>
            <th>View</th>
            <th>Status</th>
            <th style="width: 5%">Action</th>
          </tr>
        @if(count($departures)> 0 )
          @foreach( $departures as $key => $departure )
            <tr>
              <td>{{ ($departures->currentpage()-1) * $departures->perpage() + $key + 1 }}</td>
              <td style="width: 5%"><img style="width: 50%" src="{{$urlS3.$departure->image}}"></td>
              <td style="width: 5%"><img style="width: 100%" src="{{$urlS3.$departure->banner_image}}"></td>
              <td>{{$departure->dep_dook_ref_id}}</td>
              <td>{{$departure->title}}</td>
              <!-- <td><a data-toggle="modal" data-target="#edit-optional" data-id="{{ $departure->id }}" data-activityId="{{JSON_encode($departure->optional_activity_id) }}" data-activityName="{{JSON_encode($departure->optional_activity_title) }}" class="edit-item btn btn-sm btn-info btn-icon btn-icon-md" title="Edit optional" style="margin-left: 5px;">Update</a></td> -->
              
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
                @if($departure->featured == '1')
                  Featured
                @endif
              </td>
              <td><a href="https://www.dookinternational.com/{{$departure->slug_url_pre}}{{'/'}}{{$departure->slug_url}}{{'/'}}{{$departure->dep_dook_ref_id}}" title="View" target="_blank">View</a></td>
              <td>
                @if($departure->status == '1')
                    <a class="dropdown-item edit disableDepartue" data-id="{{ $departure->id }}" data-status="{{ $departure->status }}"  style="cursor: pointer; color: #2f8263;"> Active
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
                    <a class="dropdown-item edit" href="{{route('group_packages_edit',$departure->id)}}">
                        <i class="fa fa-edit"></i> 
                      Edit Group Tours
                    </a>
                    <a class="dropdown-item edit copyPackage" data-id="{{ $departure->id }}" data-slug1="{{ $departure->slug_url_pre  }}" data-slug2="{{ $departure->slug_url }}" data-title="{{ $departure->title }}" title="Copy?" style="cursor: pointer;"><i class="fa fa-clone" aria-hidden="true"></i> Copy Group Tour
                    </a>
                    @if($departure->featured == '0')
                      <a class="dropdown-item edit papularPackage" data-id="{{ $departure->id }}" title="Featured Up?" style="cursor: pointer;"><i class="fa fa-check"></i> Featured Up
                      </a>
                    @else
                      <a class="dropdown-item edit papularPackage" data-id="{{ $departure->id }}" title="Featured Down?" style="cursor: pointer;"><i class="fa fa-close"></i> Featured Down
                      </a>
                    @endif
                     
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

<script type="text/javascript">
        $(".disableDepartue").click(function () {
          var id = $(this).data("id");
            var flag = status?'inactive':'active';
            var token = $("meta[name='csrf-token']").attr("content");
            if (confirm("Are you sure you want to "+flag+" this Tour?"))
              $.ajax(
              {
                url: '/group-package-disable/' + id,
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
            //console.log(id);
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