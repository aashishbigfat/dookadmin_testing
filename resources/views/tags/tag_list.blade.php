<div class="box-body">
    <table id="departureListData" class="table table-bordered">
      <tbody>
        <tr>
          <th style="width: 1%">S.NO.</th>
          <th style="width: 20%">Title</th>
          <th style="width: 8%;" colspan="2">Action</th>
        </tr>
        @if(count($tags)> 0 )
          @foreach( $tags as $key => $tag )
            <tr>
              <td>{{($tags->currentpage()-1) * $tags->perpage() + $key + 1}}</td>
              <td>{{$tag->name}}</td>
              <td>
                <a class="edit-tags" data-id="{{ $tag->id }}" data-name="{{ $tag->name }}" title="Edit Tag Name" style="cursor: pointer;">
                  <i class="fa fa-edit" style="color: #00a65a"></i> 
                </a>
              </td>
              <td>
                <a class="tagDelete" data-id="{{ $tag->id }}"  title="Delete Tag" style="cursor: pointer;">
                  <i class="fa fa-trash-o" style="color: #db2321"></i> 
                </a>
              </td>
              
            </tr>
          @endforeach
        @endif
      </tbody>
    </table>
</div>
<div class="box-footer clearfix text-right">
    {{ $tags->links() }}
</div>

