<div class="card-box">
  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th style="width: 5%;">#</th>
          <th>DLT</th>
          <th>Html</th>
          <th style="width: 5%">Image</th>
          <th>Month/Year</th>
          <th>Created By</th>
          <th>Created Date</th>
        </tr>
      </thead>
      <tbody>
        @if(count($datas) > 0)
        @foreach($datas as $key => $data)
          <tr>
            <td>{{ ($datas->currentpage()-1) * $datas->perpage() + $key + 1 }}</td>
            <td>
              <form id="posts-form{{$data->id}}" method="post" action="{{route('mailer_delete',$data->id)}}" style="display: none;">
                @csrf
                {{method_field('POST')}}
              </form>
              <a href="" onclick="
              if (confirm('Are you sure, You want to delete?'))
                {
                  event.preventDefault();
                  document.getElementById('posts-form{{$data->id}}').submit();
                }
                else
                {
                  event.preventDefault();
                }
              " style="cursor: pointer;" title="Delete">
                  <i class="fa fa-trash" style="color: #69204b;cursor: pointer;"></i>
              </a>
            </td>
            <td>@if($data->html_file !="")<a href="{{asset($data->html_file)}}" target="_blank">{{asset($data->html_file)}}</a>
            @endif</td>
            <td>@if($data->image !="")<a href="{{asset($data->image)}}" target="_blank"> {{$data->image}}</a> @endif</td>
            <td>{{$data->month}}/{{$data->year}}</td>
            <td>{{$data->user_name}}</td>
            <td>{{date('d M, Y', strtotime($data->created_at))}}</td>
          </tr>
        @endforeach
      @endif
      </tbody>
    </table>
  </div>

</div>
<div class="box-footer clearfix">
  {{ $datas->links() }}
</div>