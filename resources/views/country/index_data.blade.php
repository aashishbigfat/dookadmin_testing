<div class="box-body">
            <table id="destinationListData" class="table table-bordered">
                <tbody>
                  <tr>
                    <th style="width:5%">#</th>
                    <th style="width:10%">Image</th>
                    <th style="width:10%">Banner Image</th>
                    <th style="width:10%">Mobile Banner Image</th>
                    <th style="width:15%">Country</th>
                    <th>Title</th>
                    <th>Slug Url</th>
                    <!-- <th>Mega Menu</th> -->
                    <th style="width:8%">Status</th>
                    <th style="width:10%">Action</th>
                  </tr>
                @if(count($countries)> 0 )
                  @foreach( $countries as $key => $country )
                    <tr>
                      <td>{{ ($countries->currentpage()-1) * $countries->perpage() + $key + 1 }}</td>
                      <td><img src="{{generateSignedUrl('country/'.$country->image)}}" style="width: 60%;height: 30px"></td>
                      <td><img src="{{generateSignedUrl('country/'.$country->banner_image)}}" style="width: 60%;height: 30px"></td>
                      <td><img src="{{generateSignedUrl('country/'.$country->mobile_banner_image)}}" style="width: 60%;height: 30px"></td>
                      <td>{{$country->country_name}}</td>
                      <td>{{$country->title}}</td>
                      <td>{{$country->slug_url}}</td>

                      {{--<td>
                        <form id="megha-form-{{ $country->id }}" method="post" action="{{route('countries_meghamenu',$country->id)}}" style="display: none;">
                          @csrf

                          {{method_field('POST')}}
                        </form>
                        @if($country->megha_menu ==0)
                        <a class="dropdown-item" href="" onclick="
                        if (confirm('Are you sure, You want to Add {{$country->country_name}} to mega menu?')){
                            event.preventDefault();
                            document.getElementById('megha-form-{{ $country->id }}').submit();
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
                        if (confirm('Are you sure, You want to remove {{$country->country_name}} from mega menu?')){
                            event.preventDefault();
                            document.getElementById('megha-form-{{ $country->id }}').submit();
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
                        @if($country->status == '1')
                            <a class="dropdown-item edit disableCountry" data-id="{{ $country->id }}" data-status="{{ $country->status }}" title="Inactive?" style="cursor: pointer; color: #2f8263;"> Active
                            </a>
                          @else
                          <a class="dropdown-item edit disableCountry" data-id="{{ $country->id }}" data-status="{{ $country->status }}" title="Active?" style="cursor: pointer; color: #F9423C;"> Inactive
                          </a>
                          @endif
                      </td>
                      <td>
                        @php
                            $signedImage = $country->image ? generateSignedUrl('country/' . $country->image) : '';
                            $signedBanner = $country->banner_image ? generateSignedUrl('country/' . $country->banner_image) : '';
                        @endphp

                        <a class="dropdown-item edit countryView"  
                           data-toggle="modal" 
                           data-id="{{ $country->id }}" 
                           data-name="{{ $country->country_name }}" 
                           data-title="{{ $country->title }}" 
                           data-slug="{{ $country->slug_url }}" 
                           data-description="{{ $country->description }}" 
                           data-visa="{{ $country->visa_information }}" 
                           data-about="{{ $country->about_description }}" 
                           data-guide="{{ $country->guide_description }}" 
                           data-tourism="{{ $country->tourism_description }}" 
                           data-image="{{ $signedImage }}" 
                           data-banner="{{ $signedBanner }}" 
                           title="View Country" 
                           style="cursor: pointer;">
                            <i class="fa fa-eye"></i> 
                        </a> 
                        | 
                        <a class="dropdown-item edit" href="{{ route('countries_edit', $country->id) }}" title="Edit Country">
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
    {{ $countries->onEachSide(4)->links() }}
</div>

<script type="text/javascript">
  $(".disableCountry").click(function () {
    var id = $(this).data("id");
    var status = $(this).data("status");
    var flag = status?'inactive':'active';
    var token = $("meta[name='csrf-token']").attr("content");
    if (confirm("Are you sure you want to "+flag+" this Country?"))
      $.ajax(
      {
        url: '/countries-disable/' + id,
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
<!-- <script>
     $('.countryView').on('click', function() {
        $('#editModal').modal('show');
        var id = $(this).data('id');
        var country_name = $(this).data('name');
        var description = $(this).data('description');
        var visa = $(this).data('visa');
        var guide = $(this).data('guide');
        //console.log(guide);
        var about = $(this).data('about');
        var tourism = $(this).data('tourism');
        
        var title = $(this).data('title');
        var slug_url = $(this).data('slug');
        //console.log(slug_url);
        var image = $(this).data('image');
        var banner_image = $(this).data('banner');
        //alert(dest_multi_image);
        var path = "<?php echo $s3url; ?>";
        var pathBanner = "<?php echo $s3url; ?>";
        var urlpath = path+image;
        
        $("#edit_id").val(id);
        $("#edit_country").val(country_name);
        $("#edit_title").val(title);
        $("#edit_slug_url").val(slug_url);
        $('#edit_description').html(description);
        $('#edit_visa').html(visa);
        $('#edit_guide').html(guide);
        $('#edit_about').html(about);
        $('#edit_tourism').html(tourism);
        //$('#edit_description').summernote().summernote('code', description);
        $("#ifImage").html('<img id="image_show" src="'+urlpath+'" class="" width="80" height="80"/>');
        if(banner_image){
          var banner_path = pathBanner+banner_image;
          $("#ifImageBanner").html('<img id="image_banner_show" src="'+banner_path+'" class="" width="120" height="80"/>');
        }
        
    });  
  </script> -->