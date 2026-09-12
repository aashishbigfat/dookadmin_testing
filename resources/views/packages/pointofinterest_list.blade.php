<div class="box-body">
  <table class="table table-bordered" id="DeparturePoisData">
    <tbody>
      
        <tr>
          <th>#</th>
          <th style="width: 8%">Image</th>
          <th style="width: 8%">BannerImage</th>
          <th>POIs Name</th>
          <th>Destination</th>
          <th>Country</th>
          <th>Rating</th>
          <th>Action</th>
        </tr>
      @if(count($poi_list) > 0)
        @foreach($poi_list as $key => $poi)
          <tr>
            <td>{{ ($poi_list->currentpage()-1) * $poi_list->perpage() + $key + 1 }}</td>
           
            <td>
              @if($poi->image)
                <img src="{{generateSignedUrl('poi/'.$poi->image)}}" alt="" style="width: 75%;">
              @else
                <img src="{{asset('images/464X260.png')}}" alt="" style="width: 75%;">
              @endif
            </td>
            <td>
              @if($poi->banner_image)
                <img src="{{generateSignedUrl('poi/'.$poi->banner_image)}}" alt="" style="width: 75%;">
              @else
                <img src="{{asset('images/no-image.png')}}" alt="" style="width: 75%;">
              @endif
            </td>
            <td>{{$poi->poi_name}}</td>
            <td>{{$poi->dest_name}}</td>
            <td>{{$poi->country_name}}</td>
            <td>{{$poi->rating}}</td>
            <td>

           <a class="dropdown-item edit editPoi"
             data-toggle="modal"
             data-id="{{ $poi->id }}"
             data-routeId="{{ request()->route('id') }}"
             data-poiId="{{ $poi->poi_id }}"
             data-poiname="{{ $poi->poi_name }}"
             data-address="{{ $poi->address }}"
             data-description="{{ $poi->description }}"
             data-image="{{ generateSignedUrl('poi/'.$poi->image) }}"
             data-bannerimage="{{ generateSignedUrl('poi/'.$poi->banner_image) }}"
             data-destinationid="{{ $poi->dest_id }}"
             data-destinationname="{{ $poi->dest_name }}"
             title="Edit details"
             style="cursor: pointer;">
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
    {{ $poi_list->onEachSide(3)->links() }}
</div>

 <script>
     $('.editPoi').on('click', function(e) {
      e.preventDefault();
      console.log('hh');
      $('#editPoiModel').modal('show');
            var id = $(this).data('id');
            var poi_reff_id = $(this).attr("data-poiId");
            var route_id = $(this).attr("data-routeId");
            var poiNames = $(this).data('poiname');

            var address = $(this).data('address');
        //console.log(address);
            var description = $(this).data('description');

            var destination_id = $(this).data("destinationid");
            //var dest_id = JSON.parse(destination_id);
          //console.log(destination_id);
            var destination_name = $(this).data("destinationname");
            //var dest_name = JSON.parse(destination_name);

            // var exp_id = $(this).attr("data-expid");
            // var experiences_id = JSON.parse(exp_id);

            // var exp_name = $(this).attr("data-expname");
            // var experiences_name = JSON.parse(exp_name);

            var image = $(this).attr("data-image");
            var basepath = "<?php echo $urlS3; ?>";
            var imagePath = basepath+image;
            var banner_image = $(this).attr("data-bannerimage");
            var bannerImagePath = basepath+banner_image;
            
            $("#editPoiModel").find("img[name='edit_default_image']").attr('src', imagePath);
            $("#editPoiModel").find("img[name='edit_default_image_banner']").attr('src', bannerImagePath);
            $("#edit_id").val(id);
            $("#edit_poi").val(poiNames);
            $("#ref_id").val(poi_reff_id);
            $("#route_id").val(route_id);
            $("#editPoiModel").find("select[name='edit_destinations'] option[value='"+destination_id+"']").attr('selected', 'selected');

             $("#edit_address_poi").html('<textarea class="form-control" name="edit_address" id="edit_address" style="height: 100px">'+address+'</textarea>');
            $("#edit_description_poi").html('<textarea class="form-control" name="edit_description" id="edit_description" style="height: 100px">'+description+'</textarea>');      

            // var dataExp = [];
            // $('#edit_experiences').val('').trigger('change');

            // for (var i = 0; i < experiences_id.length; i++) {
            //     var $select = $("#edit_experiences");
            //     var items = {id: experiences_id[i], text: experiences_name[i]};
            //     //console.log(items);
            //     var dataExp = $select.val() || [];
            //     $(items).each(function () {
            //         if (!$select.find("option[value='" + this.id + "']").length) {
            //             $select.append(new Option(this.text, this.id, true, true));
            //         }
            //         dataExp.push(this.id);
            //     });
            //     $select.val(dataExp).trigger('change');
            // }         
        });

</script>