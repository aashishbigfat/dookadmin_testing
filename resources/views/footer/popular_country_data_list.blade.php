<div class="box-body">
  <table id="countryListData" class="table table-bordered">
    <thead>
      <tr>
        <th>#</th>
        <th>Country Name</th>
        <th style="width: 5%">Action</th>
      </tr>
    </thead>
    <tbody class="row_position_country">
      @if(count($popular_country)> 0 )
        @foreach( $popular_country as $key => $value )
          <tr id="{{ $value->id }}">
            <td>{{ $key + 1 }}</td>
            <td>{{$value->country_name}}</td>
            <td>
              <a class="popularCountry" data-id="{{ $value->id }}" title="Delete Popular Country?" style="cursor: pointer;">
                <i class="fa fa-trash" style="color: #9a191e;"></i>
              </a>
            </td>
          </tr>
        @endforeach
      @endif
    </tbody>
  </table>
</div>
{{--<div class="box-footer clearfix text-right">
  {{ $popular_country->links() }}
</div> --}}