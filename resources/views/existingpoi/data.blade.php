<div class="box-body">
  <table id="ExistingPoisData" class="table table-bordered">
    <tbody>
      <tr>
        <th style="width: 5%">#</th>
        <th>Image</th>
        <!-- <th>Banner Image</th> -->
        <th>POIs Name</th>
        <th>Type</th>
        <th>Rating</th>
        <th>Country</th>
        <th>Description</th>
        <th style="width: 10%; text-align: center;">Action</th>
      </tr>
      @if(count($pois)> 0 )
        @foreach( $pois as $key => $poi )
          <tr>
            <td>{{ ($pois->currentpage()-1) * $pois->perpage() + $key + 1 }}</td>
            <td style="width: 6%"><img style="width: 100%;" src="{{generateSignedUrl('poi/'.$poi->image)}}"></td> 
           {{--  <td style="width: 8%"><img style="width: 100%" src="{{generateSignedUrl('poi/'.$poi->banner_image)}}"></td> --}}
            <td>{{$poi->poi_name}}</td>
            <td>{{$poi->poi_type}}</td>
            <td>{{$poi->rating}}</td>
            <td>{{$poi->country_name}}</td>
            <td>{{\Illuminate\Support\Str::limit($poi->description, 50)}} </td>
            <td style="text-align: center;">
              <a class="existingPoiEdit"  data-toggle="modal" data-id="{{ $poi->id }}" data-countryid="{{ $poi->country_id }}" data-name="{{ $poi->poi_name }}" data-description="{{ $poi->description }}" data-rating="{{ $poi->rating }}" data-address="{{ $poi->address }}" data-type="{{ $poi->poi_type }}" data-latitude="{{ $poi->latitude }}" data-longitude="{{ $poi->longitude }}" data-image="{{ generateSignedUrl('poi/'.$poi->image) }}" data-banner="{{ generateSignedUrl('poi/'.$poi->banner_image) }}" title="Edit {{$poi->poi_name}}" style="cursor: pointer;">
                <i class="fa fa-edit"></i> 
              </a> | <a class="existingPoiDisable" data-id="{{ $poi->id }}" title="Delete {{ $poi->poi_name }}?" style="cursor: pointer; color: #F9423C;"> 
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
    {{ $pois->onEachSide(3)->links() }}
</div>
<script>
  $('.existingPoiEdit').on('click', function() {
    $('#editModal').modal('show');
    var id = $(this).data('id');
    var country_id = $(this).data('countryid');
    var name = $(this).data('name');
    var address = $(this).data('address');
    var description = $(this).data('description');
    var rating = $(this).data('rating');
    var type = $(this).data('type');
    var latitude = $(this).data('latitude');
    var longitude = $(this).data('longitude');
    var image = $(this).data('image');
    var banner = $(this).data('banner');
    var path = "<?php echo $s3url; ?>";
    var urlpath = path+image;
    var urlpathb = path+banner;
    $("#edit_id").val(id);
    $("#edit_name").val(name);
    $("#edit_address").val(address);
    $("#edit_rating").val(rating);
    $("#edit_type").val(type);
    $("#edit_description").val(description);
    $("#edit_latitide").val(latitude);
    $("#edit_longitude").val(longitude);
    $("#editModal").find("select[name='edit_country'] option[value='"+country_id+"']").attr('selected', 'selected');
    $("#ifImage").html('<img id="blah" onclick="triggerImage()" src="'+urlpath+'" class="" width="80" height="60"/>');
    $("#ifImageBanner").html('<img id="blah" onclick="triggerImageBanner()" src="'+urlpathb+'" class="" width="120" height="80"/>');
  });

  // Disable poi
  $(".existingPoiDisable").click(function () {
      var id = $(this).data("id");
      var token = $("meta[name='csrf-token']").attr("content");
        if (confirm("Are you sure you want to Delete this POI?"))
        $.ajax(
        {
          url: '/existingpoi/delete/' + id,
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
