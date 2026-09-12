<div class="box-body">
            <table id="SectionListData3" class="table table-bordered">
                <tbody>
                  <tr>
                    <th style="width: 5%">#</th>
                    <th>Videos</th>
                    <th>Description</th>
                    <th style="width: 5%">Action</th>
                  </tr>
                @if(count($sectionImages3)> 0 )
                  @foreach( $sectionImages3 as $section_image )
                    <tr class="ABC_XYZ">
                      <td>{{ $loop->index +1 }}</td>
                      <td>{!!$section_image->description!!}</td>
                      <td>{{$section_image->description}}</td>
                      <td>
                        <a class="editSection4"  data-toggle="modal" data-id="{{ $section_image->id }}" data-description="{{$section_image->description}}" title="Edit details" style="cursor: pointer;">
                        <i class="fa fa-edit"></i> 
                        </a>
                      </td>
                    </tr>
                  @endforeach
                @endif
              </tbody>
          </table>
</div>
<style type="text/css">
  .ABC_XYZ td img{
    width: 100px;
  }
</style>
<!-- <div class="box-footer clearfix text-right">
</div> -->

