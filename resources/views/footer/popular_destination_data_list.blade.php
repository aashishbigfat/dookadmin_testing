<div class="box-body">
  <table id="departureListData" class="table table-bordered">
    <thead>
      <tr>
        <th>#</th>
        <th>Destination Name</th>
        <th style="width: 5%">Action</th>
      </tr>
    </thead>
    <tbody class="row_position_dest">
      @if(count($popular_destination)> 0 )
        @foreach( $popular_destination as $key => $value )
          <tr id="{{ $value->id }}">
            <td>{{ $key + 1 }}</td>
            <td>{{$value->dest_name}}</td>
            <td>
              <a class="popularDest" data-id="{{ $value->id }}" title="Delete Popular Destination?" style="cursor: pointer;">
                <i class="fa fa-trash" style="color: #9a191e;"></i>
              </a>
            </td>
          </tr>
        @endforeach
      @endif
    </tbody>
  </table>
</div>
{{--<div class="box-footer clearfix text-right">
  {{ $popular_destination->links() }}
</div> --}}
