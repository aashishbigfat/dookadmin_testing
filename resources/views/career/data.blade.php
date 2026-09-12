<div class="box-body">
  <table id="agentitiListData" class="table table-bordered">
    <tbody>
      <tr>
        <th style="width: 5%">#</th>
        <th>Title</th>
        <th>Role</th>
        <th>Location</th>
        <th>Position</th>
        <th>Experience</th>
        <th>Job For</th>
        <th>Status</th>
        <th colspan="2">Action</th>
      </tr>
      @if(count($jobs)> 0 )
        @foreach( $jobs as $key => $job )
          <tr>
            <td>{{ ($jobs->currentpage()-1) * $jobs->perpage() + $key + 1 }}</td>
            <td>{{$job->title}}</td>
            <td>{{$job->role}}</td>
            <td>{{$job->location}}</td>
            <td>{{$job->position}}</td>
            <td>{{$job->exp}}</td>
            <td>{{$job->type}}</td>
            <td>
              @if($job->status == '1')
                <a class="disablejob" data-id="{{ $job->id }}" data-status="{{ $job->status }}" title="Close?" style="cursor: pointer; color: #2f8263;"> Open
                </a>
              @else
                <a class="disablejob" data-id="{{ $job->id }}" data-status="{{ $job->status }}" title="Open?" style="cursor: pointer; color: #F9423C;"> Close
                </a>
              @endif
            </td>
            <td style="text-align: center;">
              <a class="jobEdit"  data-toggle="modal" data-id="{{ $job->id }}" data-role="{{ $job->role }}" data-title="{{ $job->title }}" data-description="{{ $job->description }}" data-location="{{ $job->location }}" data-position="{{ $job->position }}" data-exp="{{ $job->exp }}" data-status="{{ $job->status }}" data-slug="{{ $job->slug_url }}" data-meta_title="{{ $job->meta_title }}" data-meta_keywords="{{ $job->meta_keywords }}" data-meta_description="{{ $job->meta_description }}" data-type="{{ $job->type }}" title="Edit {{$job->title}}" style="cursor: pointer;">
                <i class="fa fa-edit"></i> 
              </a>
            </td>
            <td>
              <a class="deletejob" data-id="{{ $job->id }}" title="Delete Job" style="cursor: pointer; color: #F9423C;"> <i class="fa fa-trash" aria-hidden="true"></i>
              </a>
            </td>
          </tr>
        @endforeach
      @endif
    </tbody>
  </table>
</div>
<div class="box-footer clearfix text-right">
    {{ $jobs->onEachSide(3)->links() }}
</div>

