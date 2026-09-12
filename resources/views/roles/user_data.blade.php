<div class="box-body">
  <table id="departureListData" class="table table-bordered">
    <tbody>
      <tr>
        <th>#</th>
        <th>User Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Role</th>
        <th>Status</th>
        <th style="width: 5%">Action</th>
      </tr>
      @if(count($users)> 0 )
        @foreach($users as $key => $user)
          <tr>
            <td>{{$loop->index +1}}</td>
            <td>{{$user->name}}</td>
            <td>{{$user->email}}</td>
            <td>{{$user->phone}}</td>
            <td>
              <span class="userRole">{{$user->role_name}}</span>
            </td>
            <td>
              @if($user->status == 1)
                <a class="UserActive disableUsers" data-id="{{ $user->id }}" data-status="{{ $user->status }}" title="Inactive?" style="cursor: pointer;"> Active
                </a>
              @else
                <a class="UserInActive disableUsers" data-id="{{ $user->id }}" data-status="{{ $user->status }}" title="Active?" style="cursor: pointer;"> Inactive
                </a>
              @endif
            </td>
            <td>
              <a class="ediUsers" data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}" data-phone="{{ $user->phone }}" data-role_id="{{ $user->role_id }}" data-role_name="{{ $user->role_name }}" title="Edit?" style="cursor: pointer;"> <i class="fa fa-edit" aria-hidden="true" style="color: #dd4b39"></i>
                </a>
            </td>
          </tr>
        @endforeach
      @endif
    </tbody>
  </table>
</div>

