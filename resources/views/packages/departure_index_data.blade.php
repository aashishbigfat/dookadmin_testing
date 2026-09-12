<div class="box-body">
            <table id="departureListData" class="table table-bordered">
                <tbody>
                  <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th style="width: 8%">Package ID</th>
                    <th style="width: 25%">Name</th>
                    <th style="width: 8%">Price</th>
                    <th>N/D</th>
                    <!-- <th>Country</th>-->
                    <th>View</th>
                    <th>Created Date</th>
                    <th>PDF Status</th>
                    <th>Publish to IF</th>
                    <th>Publish to Appolyte</th>
                    <th>Status</th>
                    <th>GCC Server</th>

                    <th style="width: 8%">Action</th>
                  </tr>
                @if(count($departures)> 0 )
                  @foreach( $departures as $key => $departure )
                 
                    <tr>
                      <td>{{ ($departures->currentpage()-1) * $departures->perpage() + $key + 1 }}</td>
                      <td style="width: 5%"><img style="width: 70%" src="{{generateSignedUrl('package/'.$departure->image)}}"></td>
                      <!-- <td style="width: 5%"><img style="width: 100%" src="{{$urlS3.$departure->banner_image}}"></td> -->
                      <td>{{$departure->dep_dook_ref_id}}</td>
                      <td>{{$departure->title}}</td>
                      <!-- <td><a data-toggle="modal" data-target="#edit-optional" data-id="{{ $departure->id }}" data-activityId="{{JSON_encode($departure->optional_activity_id) }}" data-activityName="{{JSON_encode($departure->optional_activity_title) }}" class="edit-item btn btn-sm btn-info btn-icon btn-icon-md" title="Edit optional" style="margin-left: 5px;">Update</a></td> -->
                      <td>
                        @if($departure->price)
                          {{$departure->price_currency}} {{$departure->price}}
                        @endif
                      </td>
                      <td>
                        @if($departure->no_of_days)
                          {{$departure->no_of_nights}}N/{{$departure->no_of_days}}D
                        @else
                          0N/0D
                        @endif
                      </td>
                      <td><a href="https://www.dookinternational.com/{{$departure->slug_url_pre}}{{'/'}}{{$departure->slug_url}}{{'/'}}{{$departure->dep_dook_ref_id}}" title="view Package" target="_blank">View </a>{{$departure->country_name}}</td>
                      <td style="color: #23a65a;">
                        {{date('d M, Y', strtotime($departure->created_at))}}
                      </td>
                      <td>
                        <i class="fa fa-file-pdf-o" aria-hidden="true" style="color: #F9423C"></i> {{$departure->pdf_status}}
                      </td>
                      <td>
                        @if($departure->itinerary_status == '1')
                          <a class="dropdown-item edit" title="" style="cursor: no-drop !important;color: darkgreen" title="Itinerary has been published."><i class="fa fa-upload"></i> Published </a>
                        @else
                          @if($departure->iti_days == $departure->no_of_days)
                            <a class="dropdown-item edit publishItinerary" data-id="{{ $departure->id }}" title="Click to Publish Itinerary!" style="cursor: pointer;"><i class="fa fa-upload"></i> Draft</a>
                          @else
                            <a class="dropdown-item edit" style="color: #db2321;" title="Complete the itinerary before publishing."><i class="fa fa-upload"></i> Draft</a>
                          @endif
                        @endif
                      </td>
                      <td>
                        @if($departure->appolyte_status == 1)
                          <a class="dropdown-item edit" title="" style="cursor: no-drop !important;color: darkgreen" title="Itinerary has been published."><i class="fa fa-upload"></i> Published </a>
                        @else
                          @if($departure->iti_days == $departure->no_of_days && $departure->appolyte_status == 0)
                            <a class="dropdown-item edit publishAppolyte" data-id="{{ $departure->id }}" title="Click to Publish Itinerary!" style="cursor: pointer;"><i class="fa fa-upload"></i> Draft</a>
                          @else
                            <a class="dropdown-item edit" style="color: #db2321;" title="Complete the itinerary before publishing."><i class="fa fa-upload"></i> Draft</a>
                          @endif
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
                        @if($departure->gcc_server == '1')
                            <a class="dropdown-item edit gcccDepartue" data-id="{{ $departure->id }}" data-status="{{ $departure->gcc_server }}" title="Inactive?" style="cursor: pointer; color: #2f8263;"> Active
                            </a>
                          @else
                          <a class="dropdown-item edit gcccDepartue" data-id="{{ $departure->id }}" data-status="{{ $departure->gcc_server }}" title="Active?" style="cursor: pointer; color: #F9423C;"> Inactive
                          </a>
                          @endif
                      </td>
                      
                      <td>
                        <span class="dropdown">
                          <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                            <i class="fa fa-ellipsis-h" aria-hidden="true" style="color:red"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item edit" href="{{route('packages_edit',$departure->id)}}">
                                <i class="fa fa-edit"></i> 
                              Edit Package
                            </a>
                            <a class="dropdown-item edit copyPackage" data-id="{{ $departure->id }}" data-slug1="{{ $departure->slug_url_pre  }}" data-slug2="{{ $departure->slug_url }}" data-title="{{ $departure->title }}" title="Copy?" style="cursor: pointer;"><i class="fa fa-clone" aria-hidden="true"></i> Copy Package
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
              
                            <a class="dropdown-item edit" href="{{route('pdf_create',$departure->id)}}" target="_blank">
                              <i class="fa fa-link" aria-hidden="true"></i> Pdf Create
                            </a>
                        </div>
                      </span>
                    <span>@if($departure->featured == 1) <span style="color: #0ea31a;font-weight: bold;">F</span>
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
          var flag = status?'inactive':'active';
            
          if (confirm("Are you sure you want to "+flag+" this Package?"))
          var token = $("meta[name='csrf-token']").attr("content");
          var id = $(this).data("id");
            //console.log(id);
            if(id){
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
            }
        });
    </script>
    <script type="text/javascript">
        $(".papularPackage").click(function () {
          if (confirm("Are you sure you want to change?"))
          var id = $(this).data("id");
            var token = $("meta[name='csrf-token']").attr("content");
            if(id){
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
            }
        });
    </script>
    <script>
    $('.copyPackage').click(function(){
      $('#copyModal').modal('show');
      var id = $(this).data('id');
     // alert(id);
      var title = $(this).data('title');
      var slug_url_pre = $(this).data('slug1');
      var slug_url = $(this).data('slug2');

      $("#copy_id").val(id);
      $("#title").val(title);
      $("#slug_url_pre").val(slug_url_pre);
      $("#slug_url").val(slug_url);
    })    
  </script>
  <script type="text/javascript">
    //Publish to IF
      $(".publishItinerary").click(function () {
        //var flag = status?'inactive':'active';
        //if (confirm("Are you sure you want to Publish this Itinerary?"))
        $("#myModal").modal('show');
        var id = $(this).data("id");
        $("#publish_id").val(id);
      });
      //Publish to Appolyte
      $(".publishAppolyte").click(function () {
        $("#myModalAppolyte").modal('show');
        var id = $(this).data("id");
        $("#publish_id").val(id);
      });
     
  </script>