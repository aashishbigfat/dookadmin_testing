<div class="box-body">
            <table id="SectionListData1" class="table table-bordered">
                <tbody>
                  <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Sub Title</th>
                    <th>Counter</th>
                    <th>Sub Counter</th>
                    <th>Slug Title</th>
                    <th>Slug Sub Title</th>
                    <th style="width: 5%">Action</th>
                  </tr>
                @if(count($sectionImages)> 0 )
                  @foreach( $sectionImages as $section_image )
                    <tr>
                      <td>{{ $loop->index +1 }}</td>
                      <td style="width: 5%"><img style="width: 50%" src="{{$urlS3.$section_image->image}}"></td>
                      <td>{{$section_image->title}}</td>
                      <td>{{$section_image->subtitle}}</td>
                      <td>{{$section_image->counter_no}}</td>
                      <td>{{$section_image->sub_counter_no}}</td>
                      <td>{{$section_image->redirect_url}}</td>
                      <td>{{$section_image->redirect_url2}}</td>
                      <td>
                        <a class="editSection1"  data-toggle="modal" data-id="{{ $section_image->id }}" data-title="{{ $section_image->title }}"  data-subtitle="{{$section_image->subtitle}}" data-counterno="{{$section_image->counter_no}}" data-subcounterno="{{$section_image->sub_counter_no}}" data-image="{{$section_image->image}}" data-redirecturl="{{$section_image->redirect_url}}" data-redirecturl2="{{ $section_image->redirect_url2 }}" title="Edit details" style="cursor: pointer;">
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

