<div class="box-body table-responsive" >
  <table id="destinationListData" class="table table-bordered">
    <tbody>
      @if(count($enquiries)> 0 )
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Mobile</th>
          <th>TravelDate</th>
          <th>Traveler</th>
          <th style="width: 50px">Url</th>
          <th>Enquiry Date</th>
          <th>TFC Lead</th>
          <th>Source</th>
          <th style="width:1%">Action</th>
        </tr>
      
        @foreach( $enquiries as $key => $enquiry )
        <tr>
          <td>{{ ($enquiries->currentpage()-1) * $enquiries->perpage() + $key + 1 }}</td>
          <td>{{$enquiry->name}}</td>
          <td style="width: 120px;"><a style="word-break: break-all;" href="mailto:{{$enquiry->email}}">{{$enquiry->email}}</a></td>
          <td>{{$enquiry->mob_no}}</td>
          @php
              $cleanDate = str_replace('*', '', $enquiry->travel_date);
          @endphp
          <td>{{ date('d-M-Y', strtotime($cleanDate)) }}</td>
          <td>{{$enquiry->no_of_traveler}}</td>
          <td style="width: 350px;"><a style="word-break: break-all;" href="{{$enquiry->url}}" target="_blank">{{$enquiry->url}}</a></td>
          <!-- <td>{{$enquiry->origin}}</td> -->
          <td>{{date('d-M-Y', strtotime($enquiry->created_at))}}</td>
          <td>@if($enquiry->tfc_lead == 0) <span style="color:red;">No </span> @else <span style="color:green;"> Yes </span> @endif</td>
          <td>@if($enquiry->source == "") Dook @else {{$enquiry->source}} @endif</td>
          <td>
            <a class="deleteEnquiry" data-id="{{ $enquiry->id }}" title="Delete?" style="cursor: pointer; color: #F9423C;"> 
              <i class="fa fa-trash"></i> 
            </a>
          </td>
        </tr>
        @endforeach
      @else
        <h3 style="text-align:center;">Data not found for this date</h3>
      @endif
    </tbody>
  </table>
</div>
<div class="box-footer clearfix text-right">
    {{ $enquiries->withQueryString()->links() }}
</div>
