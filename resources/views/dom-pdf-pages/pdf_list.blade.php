<div class="col-md-12">
  <h3>Pdf List</h3>
  <table class="table table-striped- table-hover table-checkable">
      <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Preview</th>
            <th>Download</th>
            <th>Generated Date</th>
        </tr>
      </thead>
      <tbody>
        @foreach($datas as $value)
        <tr>
          <td>{{$loop->index +1}}</td>
          <td>{{$value->file_name}}</td>
          <td>
          <a style="color: #5867dd; cursor: pointer;" href="{{asset('dook/pdf')}}/{{$value->file_name}}" target="_blank"><i class="fa fa-eye"></i></i> Preview</a>
          </td>

          <td>
            <a href="{{asset('dook/pdf')}}/{{$value->file_name}}" download><i class="fa fa-download" aria-hidden="true"></i> Download</a>
          </td>
          <td>{{date('d M, Y g:i:s A', strtotime($value->created_at))}}</td>
        </tr>
        @endforeach
      </tbody>
  </table>
</div>