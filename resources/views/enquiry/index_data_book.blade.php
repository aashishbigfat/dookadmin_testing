<div class="box-body" style="overflow-x: scroll;">
  <table id="destinationListData" class="table table-bordered">
    <tbody>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Mobile</th>
        <th>Is Booked</th>
        <th style="width:1%">Action</th>
      </tr>
      @if(count($booking)> 0 )
      @foreach( $booking as $key => $job )
      <tr>
        <td></td>
        <td>{{$job->first_name}}</td>
        <td><a href="mailto:{{$job->email_id}}">{{$job->email_id}}</a></td>
        <td>{{$job->phone}}</td>
        <td></td>
     
        <td>
          <a class="deleteJob" data-id="{{ $job->id }}" title="Delete?" style="cursor: pointer; color: #F9423C;"> 
            <i class="fa fa-trash"></i> 
          </a>
        </td>
      </tr>
      @endforeach
    @endif
  </tbody>
  </table>
</div>
<div class="box-footer clearfix text-right">
    
</div>
