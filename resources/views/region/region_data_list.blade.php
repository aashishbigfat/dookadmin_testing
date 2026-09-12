<div class="box-body">
  <table id="topDestData" class="table table-bordered">
    <tbody>
      
        <tr>
          <th style="width: 5%">#</th>
          <th style="width: 5%">Grid Number</th>
          <th style="width: 5%">Image</th>
          <th>Region Name</th>
          <th>Label Name</th>
          <th>Slug URL</th>
          <th>Mega Menu</th>
          <th>Edit</th>
        </tr>
      @if(count($regions) > 0)
        @foreach($regions as $key => $topRegion)
          <tr>
            <td>{{ $loop->index +1}}</td>
            <td>{{ $topRegion->grid_number}}</td>
            <td><img  style="width: 100%;" src="{{generateSignedUrl('region/'.$topRegion->image)}}"></td>
            <td>{{$topRegion->region_name}}</td>
            <td>{{$topRegion->label_name}}</td>
            <td>{{$topRegion->slug_url}}</td>
            <td>
              <form id="megha-form-{{ $topRegion->id }}" method="post" action="{{route('region_megamenu',$topRegion->id)}}" style="display: none;">
                @csrf

                {{method_field('POST')}}
              </form>
              @if($topRegion->mega_menu ==0)
              <a class="dropdown-item" href="" onclick="
              if (confirm('Are you sure, You want to Add {{$topRegion->region_name}} to mega menu?')){
                  event.preventDefault();
                  document.getElementById('megha-form-{{ $topRegion->id }}').submit();
                }
                else
                {
                  event.preventDefault();
                }
              " title="Add to Mega Menu" style="cursor: pointer;">
              <i class="fa fa-upload" aria-hidden="true"></i> Add
              </a>
              @else
              <a class="dropdown-item" href="" onclick="
              if (confirm('Are you sure, You want to remove {{$topRegion->region_name}} from mega menu?')){
                  event.preventDefault();
                  document.getElementById('megha-form-{{ $topRegion->id }}').submit();
                }
                else
                {
                  event.preventDefault();
                }
              " title="Remove from Mega Menu" style="cursor: pointer;">
              <i class="fa fa-download" style="color:#ad2104;"></i> Remove
              </a>
              @endif
            </td>
            <td>
             <a class="dropdown-item edit" href="{{route('region_edit',$topRegion->id)}}">
                <i class="fa fa-edit"></i> 
              </a>
            </td>
          </tr>
        @endforeach
      @endif
    </tbody>
  </table>
</div>