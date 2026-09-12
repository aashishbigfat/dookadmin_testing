<div class="box-body">
  <table class="table table-bordered">
        <tbody>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Dook ID</th>
            <th>Dep ID</th>
            <th>Nights/Days</th>
            <th>Departure Date</th>
            <th>Day</th>
            <th>DC INR</th>
            <th>Peice INR</th>
            <th>Price USD</th>
            <th>Total Seat</th>
            <th>Ava. Seat</th>
            <th>Status</th>
            <th style="width: 8%">Action</th>
          </tr>
          @if(count($departures)> 0 )
            @foreach( $departures as $key => $departure )
            <tr>
              <td>{{ ($departures->currentpage()-1) * $departures->perpage() + $key + 1 }}</td>
              <td>{{$departure->title}}</td>
              <td>{{$departure->dook_id}}</td>
              <td>{{$departure->dep_id}}</td>
              <td>{{$departure->nights}}N/{{$departure->days}}D</td>
              @if($departure->date)
                <td>{{date('d-M-Y', strtotime($departure->date))}}</td>
                <td>{{date('l', strtotime($departure->date))}}</td>
              @else
                <td>###</td>
                <td>###</td>
              @endif
              
              <td>{{$departure->dc_currency}} {{$departure->dc_price}}</td>
              <td>{{$departure->price_currency}} {{$departure->price}}</td>
              <td>{{$departure->price_currency_usd}} {{$departure->price_usd}}</td>
              <td>{{$departure->total_seat}}</td>
              <td>{{$departure->available_seat}}</td>
              <td>
                @if($departure->date >= date("Y-m-d"))
                    Open
                @else
                    Close
                @endif
              </td>
              <td>
                <a href="{{route('departure_date_edit',['id'=>$departure->departure_id,'date_id'=>$departure->id])}}"><i class="fa fa-edit"> </i></a> |
                 <a class="editInclusions padding10" href="{{route('departure_date_inclusion',['id'=>$departure->departure_id,'date_id'=>$departure->id])}}" target="_blank" title="Edit Inclusions" style="cursor: pointer;margin-right: 5px;">
                    <i class="fa fa-info-circle"> </i>
                </a>
                {{-- <span class="dropdown">
                  <a href="#" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown" aria-expanded="true">
                    <i class="fa fa-ellipsis-h" aria-hidden="true" style="color:red"></i>
                  </a> 
                 <div class="dropdown-menu dropdown-menu-right" style="min-width: 200px;">
                    <a class="editInclusions padding10" href="{{route('departure_date_inclusion',['id'=>$departure->departure_id,'date_id'=>$departure->id])}}" target="_blank" title="Edit Inclusions" style="cursor: pointer;margin-right: 5px;">
                    <i class="fa fa-info-circle"> </i>
                    </a>
                    <br><a class="editTerms padding10" href="{{route('departure_date_terms',['id'=>$departure->departure_id,'date_id'=>$departure->id])}}" target="_blank" title="Edit Terms of Payment" style="cursor: pointer;margin-right: 5px;">
                    <i class="fa fa-edit"> </i>  Edit Terms Of Conditions
                    </a> 
                    <br>
                    <a class="editTerms padding10" href="{{route('departure_pricing',['id'=>$departure->departure_id,'d_id'=>$departure->id])}}" title="Edit Pricing" style="cursor: pointer;margin-right: 5px;">
                    <i class="fa fa-edit"> </i>  Edit Pricing
                    </a>
                  </div> 
                  </span>--}}
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