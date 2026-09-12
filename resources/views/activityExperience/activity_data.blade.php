<div class="box-body">
          <table id="departureListData" class="table table-bordered">
                <tbody>
                  <tr>
                    <th style="width: 5%">#</th>
                    <th>Image</th>
                    <th>Banner Image</th>
                    <th>Activities</th>
                    <th>Slug Url</th>
                    <th>Status</th>
                    <th style="width: 10%; text-align: center;">Action</th>
                  </tr>
                @if(count($activities)> 0 )
                  @foreach($activities as $key => $activity)
                    <tr>
                      <td>{{ ($activities->currentpage()-1) * $activities->perpage() + $key + 1 }}</td>
                      <td style="width: 6%"><img style="width: 100%" src="{{generateSignedUrl('activities/'.$activity->image)}}"></td>
                      <td style="width: 8%"><img style="width: 100%" src="{{generateSignedUrl('activities/'.$activity->banner_image)}}"></td>
                      <td>{{$activity->activity_name}}</td>
                      <td>{{$activity->slug_url}}</td>
                      <td>
                        @if($activity->status == '1')
                            <a class="dropdown-item edit disableActivity" data-id="{{ $activity->id }}" data-status="{{ $activity->status }}" title="Inactive?" style="cursor: pointer; color: #2f8263;"> Active
                            </a>
                          @else
                          <a class="dropdown-item edit disableActivity" data-id="{{ $activity->id }}" data-status="{{ $activity->status }}" title="Active?" style="cursor: pointer; color: #F9423C;"> Inactive
                          </a>
                          @endif
                      </td>
                      
                      <td style="text-align: center;">
                      <a href="javascript:void(0);"
                           class="activityEdit"
                           data-id="{{ $activity->id }}"
                           data-name="{{ $activity->activity_name }}"
                           data-description="{{ $activity->description }}"
                           data-header="{{ $activity->header_title }}"
                           data-subheader="{{ $activity->header_sub_title }}"
                           data-mtitle="{{ $activity->meta_title }}"
                           data-metakey="{{ $activity->meta_keywords }}"
                           data-metadescription="{{ $activity->meta_description }}"
                           data-slug="{{ $activity->slug_url }}"
                           data-image="{{ generateSignedUrl('activities/' . $activity->image) }}"
                           data-banner="{{ generateSignedUrl('activities/' . $activity->banner_image) }}"
                           title="Edit Activities"
                           style="cursor: pointer;">
                           <i class="fa fa-edit"></i>
                        </a>

                        <!-- <span class="dropdown">
                          <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                            <i class="fa fa-ellipsis-h" aria-hidden="true" style="color:red"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-right">
                           
                        </div>
                      </span> -->
                      </td>
                    </tr>
                  @endforeach
                @endif
              </tbody>
          </table>
</div>
<div class="box-footer clearfix text-right">
    {{ $activities->onEachSide(3)->links() }}
</div>
<script>
   $('.activityEdit').on('click', function() {
        $('#editModal').modal('show');
        var id = $(this).data('id');
        var act_name = $(this).data('name');
        var slug = $(this).data('slug');
        //alert(slug);
        var description = $(this).data('description');
        var mTitle = $(this).attr('data-mTitle');
        //alert(mTitle);
        var mKey = $(this).data('metakey');
        //alert(mKey);
        var mDes = $(this).data('metadescription');
        var image = $(this).data('image');
        var banner_image = $(this).data('banner');
        var path = "<?php echo $urlS3; ?>";
        var urlpath = path+image;
        var banner_path = path+banner_image;
        $("#act_id").val(id);
        $("#edit_name").val(act_name);
        $("#edit_slug").val(slug);
        $("#meta_title").val(mTitle);
        $("#meta_keywords").val(mKey);
        $("#meta_description").val(mDes);
        $('#edit_description').summernote().summernote('code', description);
        $("#ifImage").html('<img id="image_show" onclick="triggerImage()" src="'+urlpath+'" class="" width="80" height="80"/>');
        $("#ifImageBanner").html('<img id="image_banner_show" onclick="triggerImageBanner()" src="'+banner_path+'" class="" width="100" height="60"/>');
    });

</script>

<script type="text/javascript">
    $(".disableActivity").click(function () {
      var id = $(this).data("id");
      var status = $(this).data("status");

      var flag = status?'inactive':'active';
      var token = $("meta[name='csrf-token']").attr("content");

        if (confirm("Are you sure you want to "+flag+" this Activity?"))
          $.ajax(
          {
            url: '/activities-disable/' + id,
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