<div class="table-responsive">
    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Passport Holder Country</th>
                <th>Country Of Residence</th>
                <th>Visa Type</th>
                <th>Visiting Country</th>
                <th>Visa Category</th>

                <th>Status</th>
                <th colspan="3">Action</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 0 ; @endphp
            @foreach ($visas as $visa)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $visa->passport_holder_country }}</td>
                <td>{{ $visa->country_of_residence }}</td>
                <td>{{ $visa->visa_type }}</td>
                <td>{{ $visa->visiting_country }}</td>
                <td>{{ $visa->visa_category }}</td>
                <td>
                    <input data-id="{{$visa->id}}" class="toggle-class" type="checkbox" data-onstyle="success"
                        data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" {{
                        $visa->status
                    ? 'checked' : '' }}>
                </td>

                <td>
                    <a class="" href="{{ route('visa.show', ['visa' => $visa->id]) }}">
                        <i class="fa fa-eye"></i>
                    </a>
                </td>

                <td>
                    <a href="{{ $url = route('visa.edit', [$visa->id])}}" class="pull-left"><i
                            class="fa fa-edit"></i></a>
                </td>
                <td>
                    <form action="{{ url('visa/'.$visa->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete the point of airport?');">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="submit" style="background: none;border:none"><i class="fa fa-trash"
                                style="color: red;"></i> </button>

                    </form>


                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="abcx">{{ $visas->links() }}</div>
</div>