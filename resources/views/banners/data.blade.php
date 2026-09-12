<div class="box-body">
            <table id="departureListData" class="table table-bordered">
                <tbody>
                  <tr>
                    <th>#</th>
                    <th>Banner Image</th>
                    <th>Title</th>
                    <th>URL</th>
                    <th>Days</th>
                    <th>Where to Show</th>
                    <th style="width: 5%">Action</th>
                  </tr>
                @if(count($banners)> 0 )
                  @foreach( $banners as $key => $banner )
                    <tr>
                      <td>{{($banners->currentpage()-1) * $banners->perpage() + $key + 1}}</td>
                      <td style="width: 6%"><img style="width: 100%" src="{{generateSignedUrl('banner/'.$banner->image)}}"></td>
                      <td>{{$banner->title}}</td>
                      <td>{{$banner->slug_url}}</td>
                      <td>{{$banner->days}}</td>
                      <td>{{$banner->where_to_show}}</td>
                      <td>
                       <a class="dropdown-item edit bannerEdit"  
                           data-toggle="modal" 
                           data-id="{{ $banner->id }}" 
                           data-title="{{ $banner->title }}" 
                           data-sub_title="{{ $banner->sub_title }}" 
                           data-days="{{ $banner->days }}" 
                           data-whereshow="{{ $banner->where_to_show }}" 
                           data-slug="{{ $banner->slug_url }}" 
                           data-description="{{ $banner->description }}" 
                           data-image="{{ generateSignedUrl('banner/'.$banner->image) }}" 
                           title="Edit Banner" 
                           style="cursor: pointer;">
                           <i class="fa fa-edit" style="color: #9a191e"></i> 
                        </a>

                      </td>
                    </tr>
                  @endforeach
                @endif
              </tbody>
          </table>
</div>
<div class="box-footer clearfix text-right">
    {{ $banners->links() }}
</div>

