<div class="box-body">
  <table id="topDestData" class="table table-bordered">
    <tbody>
      
        <tr>
          <th>#</th>
          <th style="width: 5%">Grid Number</th>
          <th>Image</th>
          <th>Destination</th>
          <th>Experiences</th>
          <th>Total Departure</th>
          <!-- <th>Status</th> -->
          <th>Edit</th>
        </tr>
      @if(count($top_destination_exp) > 0)
        @foreach($top_destination_exp as $key => $topDest)
          <tr>
            <td>{{ ($top_destination_exp->currentpage()-1) * $top_destination_exp->perpage() + $key + 1 }}</td>
            <td>{{$topDest->grid_number}}</td>
            <td style="width: 5%"><img style="width: 100%" src="{{generateSignedUrl('destinations/'.$topDest->image)}}"></td>
            <td>{{$topDest->dest_name}}</td>
            <td>
              <?php 
                $arr = explode(',',$topDest->experience_name);
                $str = implode(', ', $arr);
                echo $str;
               ?>
              </td>
            <td>
              {{$topDest->total_dep}}
            </td>
            <!-- <td>
              @if($topDest->status == 1)
                <span style="color:#0c880c">Active</span>
              @else
                <span style="color:#dc0b0b">Inactive</span>
              @endif
            </td> -->
            <td>
             <a class="dropdown-item edit" href="{{route('top_destinations_destinations_edit',$topDest->id)}}">
                <i class="fa fa-edit"></i> 
              </a>
            </td>
          </tr>
        @endforeach
      @endif
    </tbody>
  </table>
</div>
<div class="box-footer clearfix text-right">
    {{ $top_destination_exp->links() }}
</div>