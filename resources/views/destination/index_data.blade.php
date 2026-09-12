<div class="box-body">
            <table id="destinationListData" class="table table-bordered">
                <tbody>
                  <tr>
                    <th style="width:1%">#</th>
                    <th style="width:3%">Image</th>
                    <th style="width:3%">Banner</th>
                    <th style="width:13%">Destination</th>
                    <th style="width:12%">Country</th>
                    <th style="width:12%">Slug URL</th>
                    <!-- <th style="width: 4%;">Events</th> -->
                    <!-- <th style="width: 5%;">Restaurants</th> -->
                    <!-- <th style="width: 4%;">Hotels</th> -->
                    <th style="width: 7%;">Created Date</th>
                    <!-- <th style="width: 7%;">Mega Menu</th> -->
                    <th style="width:1%">Status</th>
                    <th style="width:1%">Action</th>
                  </tr>
                @if(count($destinations)> 0 )
                  @foreach( $destinations as $key => $destination )
                    <tr>
                      <td>{{ ($destinations->currentpage()-1) * $destinations->perpage() + $key + 1 }}</td>
                      <td>
                        @if($destination->image)
                          <img src="{{generateSignedUrl('poi/'.$destination->image)}}" alt="" style="width: 100%;">
                        @else
                          <img src="{{asset('images/no-image.png')}}" alt="" style="width: 100%;">
                        @endif
                      </td>
                      <td>
                        @if($destination->banner_image)
                          <img src="{{generateSignedUrl('poi/'.$destination->banner_image)}}" alt="" style="width: 100%;">
                        @else
                          <img src="{{asset('images/no-image-banner.jpg')}}" alt="" style="width: 100%;">
                        @endif
                      </td>
                      <td>{{$destination->dest_name}}</td>
                      <td>{{$destination->country_name}}</td>
                      <td>{{$destination->slug_url}}</td>
                      <!-- <td>
                        <a class="addEvents" data-id="{{ $destination->id }}" data-lat="{{ $destination->latitude }}" data-long="{{ $destination->longitude }}" title="Click to add Events" style="cursor: pointer; color: #ef21d7;"> Events
                        </a> | {{$destination->events}}
                      </td> -->
                      <!-- <td>
                        <a class="addRestaurants" data-id="{{ $destination->id }}" data-destination="{{ $destination->dest_name }}" data-lat="{{ $destination->latitude }}" data-long="{{ $destination->longitude }}" title="Click to add Restaurants" style="cursor: pointer; color: #1777ca;"> Restaurants
                        </a> | {{$destination->restaurants}}
                      </td> -->
                      <!-- <td>
                        <a class="addHotels" data-id="{{ $destination->id }}" data-destination="{{ $destination->dest_name }}" data-lat="{{ $destination->latitude }}" data-long="{{ $destination->longitude }}" title="Click to add Hotels" style="cursor: pointer; color: #1777ca;"> Hotels
                        </a> | {{$destination->hotels}}
                      </td> -->
                       <!-- <td>
                        @if($destination->most_popular == '1')
                          <p style="color: #c29212">Top Destination</p>
                        @else
                          <p style="color: #000">Regular Destination</p>
                        @endif
                      </td> -->
                      <td>{{date('d-M-Y', strtotime($destination->created_at))}}</td>
                      {{-- <td>
                        <form id="megha-form-{{ $destination->id }}" method="post" action="{{route('destination_megamenu',$destination->id)}}" style="display: none;">
                          @csrf

                          {{method_field('POST')}}
                        </form>
                        @if($destination->mega_menu ==0)
                        <a class="dropdown-item" href="" onclick="
                        if (confirm('Are you sure, You want to Add {{$destination->dest_name}} to mega menu?')){
                            event.preventDefault();
                            document.getElementById('megha-form-{{ $destination->id }}').submit();
                          }
                          else
                          {
                            event.preventDefault();
                          }
                        " title="Add to Mega Menu" style="cursor: pointer;">
                        <i class="fa fa-upload" aria-hidden="true"></i> Add
                        </a>
                        @else
                        <a class="dropdown-item" href="" onclick="
                        if (confirm('Are you sure, You want to remove {{$destination->dest_name}} from mega menu?')){
                            event.preventDefault();
                            document.getElementById('megha-form-{{ $destination->id }}').submit();
                          }
                          else
                          {
                            event.preventDefault();
                          }
                        " title="Remove from Mega Menu" style="cursor: pointer;">
                        <i class="fa fa-download" style="color:#ad2104;"></i> Remove
                        </a>
                        @endif
                      </td> --}}
                      <td>
                        @if($destination->status == '1')
                          <a class="dropdown-item edit disableDestination" data-id="{{ $destination->id }}" data-status="{{ $destination->status }}" title="Inactive?" style="cursor: pointer; color: #2f8263;"> Active
                          </a>
                        @else
                          <a class="dropdown-item edit disableDestination" data-id="{{ $destination->id }}" data-status="{{ $destination->status }}" title="Active?" style="cursor: pointer; color: #F9423C;"> Inactive
                          </a>
                        @endif
                      </td>

                     
                      <td style="text-align: center;">
                        <span class="dropdown">
                          <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                            <i class="fa fa-ellipsis-h" aria-hidden="true" style="color:red"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-right">
                           @php
                                $signedMultiImages = [];
                                if (!empty($destination->dest_images)) {
                                    foreach ($destination->dest_images as $img) {
                                        $signedMultiImages[] = generateSignedUrl('poi/' . $img);
                                    }
                                }
                            @endphp
                            <a 
                                class="dropdown-item destinationView"  
                                data-toggle="modal" 
                                data-id="{{ $destination->id }}" 
                                data-name="{{ $destination->dest_name }}" 
                                data-description="{!! $destination->description !!}" 
                                data-image="{{ generateSignedUrl('poi/' . $destination->banner_image) }}" 
                                data-country="{{ $destination->country_name }}" 
                                data-region="{{ $destination->region }}" 
                                data-view-multipleimg='@json($signedMultiImages)' 
                                title="View {{ $destination->dest_name }}" 
                                style="cursor: pointer;">
                                <i class="fa fa-eye"></i> View Destination
                            </a>
                            | 
                            <a class="dropdown-item edit" 
                                href="{{ route('destination_edit', $destination->id) }}" 
                                title="Edit {{ $destination->dest_name }}">
                                <i class="fa fa-edit"></i> Edit Destination
                            </a>

                            <!-- @if($destination->most_popular == '0')
                              <a class="dropdown-item edit papularDestination" data-id="{{ $destination->id }}" data-mostPopular="{{ $destination->most_popular }}" title="Enable?" style="cursor: pointer;"><i class="fa fa-check"></i> Top Destination
                              </a>
                            @else
                              <a class="dropdown-item edit papularDestination" data-id="{{ $destination->id }}" title="Disable?" style="cursor: pointer;"><i class="fa fa-close"></i> Regular Destination
                              </a>
                            @endif -->
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
    {{ $destinations->withQueryString()->links() }}
</div>
@section('footerSection')
<script>
    $('.destinationView').on('click', function () {
        $('#viewModal').modal('show');

        var id = $(this).data('id');
        var dest_name = $(this).data('name');
        var description = $(this).data('description');
        var country = $(this).data('country');
        var region = $(this).data('region');
        var image = $(this).data('image');
        var mulimages = $(this).attr("data-view-multipleimg");

        let dest_multi_image = [];
        try {
            dest_multi_image = JSON.parse(mulimages);
        } catch (e) {
            console.error("Invalid JSON in data-view-multipleimg:", e);
        }

        // Fill modal
        $("#act_id").val(id);
        $("#view_destination").val(dest_name);
        $("#view_region").val(region);
        $("#view_country").val(country);
        $('#view_description').html(description);

        // Main image
        $("#ifImage").html('<img id="image_show" src="' + image + '" class="" style="width: 100%;" />');

        // Multiple images
        var imageHtmlArray = dest_multi_image.map(url =>
            '<img class="thumb zoommodels" title="" src="' + url + '" style="margin: 5px; max-width: 100px;">'
        );

        $('#imgList').html(imageHtmlArray.join(''));
    });
</script>

  <script type="text/javascript">
        $(".disableDestination").click(function () {
          var status = $(this).data("status");
          var flag = status?'inactive':'active';
          if (confirm("Are you sure you want to "+flag+" this Destination?"))
          
          var id = $(this).data("id");
          var token = $("meta[name='csrf-token']").attr("content");
          if(id){
            $.ajax(
            {
              url: '/destinations-disable/' + id,
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
  <!-- Add events -->
  <script type="text/javascript">
        $(".addEvents").click(function () {
          if (confirm("Are you sure you want to add Events for this Destination?"))
          $("#getLoaderModal").modal('show');
          
          var id = $(this).data("id");
          var lat = $(this).data("lat");
          var long = $(this).data("long");
          var token = $("meta[name='csrf-token']").attr("content");
          if(id){
            $.ajax(
            {
              url: '/destination/store-events/' + id,
              type: 'POST',
              data: {
                  "id": id,
                  "lat": lat,
                  "long": long,
                  "_token": token,
              },
              success: function (data) {
                console.log(data);
                $("#getMsg").html(data);
                $("#getLoaderModal").modal('hide');
                jQuery("#getMsgModal").modal('show');
                setTimeout(function() {$('#getMsgModal').modal('hide');}, 1500);
                window.location.reload();
              }
            });
          }
        });
    </script>
    <!-- Add Restaurants -->
    <script type="text/javascript">
        $(".addRestaurants").click(function () {
          if (confirm("Are you sure you want to add Restaurants for this Destination?"))
          
          
          var id = $(this).data("id");
          var destination = $(this).data("destination");
          var lat = $(this).data("lat");
          var long = $(this).data("long");
          var token = $("meta[name='csrf-token']").attr("content");
          if(id){
            $("#getLoaderModal").modal('show');
            $.ajax(
            {
              url: '/destination/store-restaurants/' + id,
              type: 'POST',
              data: {
                  "id": id,
                  "destination": destination,
                  "lat": lat,
                  "long": long,
                  "_token": token,
              },
              success: function (data) {
                console.log(data);
                $("#getMsg").html(data);
                $("#getLoaderModal").modal('hide');
                jQuery("#getMsgModal").modal('show');
                setTimeout(function() {$('#getMsgModal').modal('hide');}, 1500);
                window.location.reload();
              }
            });
          }
        });
    </script>
    <script type="text/javascript">
        $(".papularDestination").click(function () {
          if (confirm("Are you sure you want to change?"))
          var id = $(this).data("id");
            //console.log(id);
            var token = $("meta[name='csrf-token']").attr("content");
            if(id){
              $.ajax(
              {
                url: '/top-destination/' + id,
                type: 'POST',
                data: {
                    "id": id,
                    "_token": token,
                },
                success: function (data) {
                  alert("Changed successfully!!");
                  window.location.reload();
                }
              });
            }
        });
    </script>
@endsection