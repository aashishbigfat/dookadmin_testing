<div class="box-body">
            <table id="SectionListData3" class="table table-bordered">
                <tbody>
                  <tr>
                    <th style="width: 5%">#</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th style="width: 5%">Action</th>
                  </tr>
                @if(count($sectionImages2)> 0 )
                  @foreach( $sectionImages2 as $section_image )
                    <tr>
                      <td>{{ $loop->index +1 }}</td>
                      <td style="width: 5%"><img style="width: 50%" src="{{$urlS3.$section_image->image}}"></td>
                      <td>{{$section_image->title}}</td>
                      <td>{{$section_image->description}}</td>
                      <td>
                        <a class="editSection3"  data-toggle="modal" data-id="{{ $section_image->id }}" data-title="{{ $section_image->title }}"  data-description="{{$section_image->description}}" data-image="{{$section_image->image}}" title="Edit details" style="cursor: pointer;">
                        <i class="fa fa-edit"></i> 
                        </a>
                      </td>
                    </tr>
                  @endforeach
                @endif
              </tbody>
          </table>
</div>
<!-- <div class="box-footer clearfix text-right">
</div> -->

