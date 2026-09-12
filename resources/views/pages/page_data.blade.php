<div class="box-body">
  <table id="departureListData" class="table table-bordered">
        <tbody>
          <tr>
            <th>#</th>
            <th>Banner</th>
            <th>Page Name</th>
            <th>Title</th>
            <th>Sub Title</th>
            <th style="width: 13%">Slug URL</th>
            <th style="width: 5%">Action</th>
          </tr>
        @if(count($pages)> 0 )
          @foreach( $pages as $key => $page )
            <tr>
              <td>{{$loop->index +1}}</td>
              <td style="width: 6%"><img style="width: 100%" src="{{generateSignedUrl('landing/'.$page->banner_image)}}"></td>
              <td>{{$page->page_name}}</td>
              <td>{{$page->title}}</td>
              <td>{{$page->sub_title}}</td>
              <td>{{$page->slug_url}}</td>
              <td>
                <a class="edit" href="{{route('landing_pages_edit',$page->id)}}" title="Edit Landing Page" style="cursor: pointer;">
                  <i class="fa fa-edit" style="color: #9a191e"></i> 
                </a>
              </td>
            </tr>
          @endforeach
        @endif
      </tbody>
  </table>
</div>

