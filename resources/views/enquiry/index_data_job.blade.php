<div class="box-body" style="overflow-x: scroll;">
  <table id="destinationListData" class="table table-bordered">
    <tbody>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Mobile</th>
        <th>EnquiryDate</th>
        <th>Resume</th>
        <th style="width:1%">Action</th>
      </tr>
      @if(count($jobs)> 0 )
      @foreach( $jobs as $key => $job )
      <tr>
        <td>{{ ($jobs->currentpage()-1) * $jobs->perpage() + $key + 1 }}</td>
        <td>{{$job->name}}</td>
        <td><a href="mailto:{{$job->email}}">{{$job->email}}</a></td>
        <td>{{$job->mob_no}}</td>
        <td>{{date('d-M-Y', strtotime($job->created_at))}}</td>
        <td><a href="{{$job->file}}">Download</a></td>
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
    {{ $jobs->onEachSide(1)->links() }}
</div>
