<div class="box-body">
  <table id="departureListData" class="table table-bordered">
        <tbody>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>G.Dep. ID</th>
            <th>Nights/Days</th>
            <th>G.Dep. Date</th>
            <th>Peice INR</th>
            <th>Price USD</th>
            <th>Status</th>
            <th style="width: 5%">Action</th>
          </tr>
          @if(count($departures)> 0 )
            @foreach( $departures as $key => $departure )
            <tr>
              <td>{{ ($departures->currentpage()-1) * $departures->perpage() + $key + 1 }}</td>
              <td>{{$departure->title}}</td>
              <td>{{$departure->dook_id}}</td>
              <td>{{$departure->nights}}N/{{$departure->days}}D</td>
              @if($departure->date)
                <td>{{date('d-M-Y', strtotime($departure->date))}}</td>
              @else
                <td>###</td>
              @endif
              <td>{{$departure->price_inr}}</td>
              <td>{{$departure->price_usd}}</td>
              <td>
                @if($departure->status == '1')
                    Open
                @else
                    Close
                @endif
              </td>
              <td><span class="dropdown">
                  <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                    <i class="fa fa-ellipsis-h" aria-hidden="true" style="color:red"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right">
                    <a class="editDateDeparture padding10" data-toggle="modal" data-id="{{ $departure->id }}" data-title="{{ $departure->title }}" data-date="{{ date('d-M-Y', strtotime($departure->date)) }}" data-dookid="{{ $departure->dook_id }}" data-priceinr="{{ $departure->price}}"  data-priceusd="{{ $departure->price_usd }}"  data-date="{{date('d-M-Y', strtotime($departure->date)) }}" data-description="{{$departure->description }}" title="Edit details"  style="cursor: pointer;margin-right: 5px;">
                    <i class="fa fa-edit"> </i>  Edit Date Info
                    </a><br>
                    <a class="editInclusions padding10" href="{{route('group_date_inclusion',['id'=>$departure->departure_id,'date_id'=>$departure->id])}}" title="Edit Inclusions" style="cursor: pointer;margin-right: 5px;">
                    <i class="fa fa-edit"> </i>  Edit Inclusions
                    </a>
                  </div>
                  </span>
                </td>
            </tr>
          @endforeach
        @endif
    </tbody>
  </table>
</div>
<div class="box-footer clearfix text-right">
    {{ $departures->onEachSide(3)->links() }}
</div>