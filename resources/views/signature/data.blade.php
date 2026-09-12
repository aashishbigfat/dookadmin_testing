<div class="box-body">
    <table id="agentitiListData" class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 5%">#</th>
                <th>Employee Id</th>
                <th>Employee Name</th>
                <th>Designation</th>
                <th>Employee Mobile</th>
             
                <th>Image</th>
                <th>Signature</th>
                <th style="text-align: center;">Action</th>
            </tr>
        </thead>
        <tbody>
            @if(count($jobs) > 0)
            @foreach($jobs as $key => $job)
            <tr>
                <td>{{ ($jobs->currentpage() - 1) * $jobs->perpage() + $key + 1 }}</td>
                <td>{{$job->emp_id}}</td>
                <td>{{$job->emp_name}}</td>
                <td>{{$job->designation}}</td>
                <td>{{$job->emp_mobile}}</td>
                <td>
                    <img src="{{ asset('signature/images/'.$job->emp_image_old) }}" style="width: 30%" alt="Employee Image">
                </td>
                <td>
                    <img src="{{ asset('signature/images/emp_sign/'.$job->emp_signature_old) }}" style="width: 30%"
                        alt="Employee Signature">
                </td>
                <td style="text-align: center;">
                    <a class="jobEdit" data-toggle="modal" data-id="{{ $job->id }}" data-emp_id="{{ $job->emp_id }}"
                        data-email="{{ $job->email }}" data-emp_name="{{ $job->emp_name }}"
                        data-designation="{{ $job->designation }}" data-emp_mobile="{{ $job->emp_mobile }}"
                        data-extension_no="{{ $job->extension_no }}" data-emp_image_old="{{ $job->emp_image_old }}"
                        data-emp_signature_old="{{ $job->emp_signature_old }}" style="cursor: pointer;">
                        <i class="fa fa-edit"></i>
                    </a>

                    <a class="deletejob" data-id="{{ $job->id }}" title="Delete Job"
                        style="cursor: pointer; color: #F9423C;"> <i class="fa fa-trash" aria-hidden="true"></i>
                    </a>
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="9" style="text-align: center;">No Employee Found</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

<div class="box-footer clearfix text-right">
    {{ $jobs->onEachSide(3)->links() }}
</div>