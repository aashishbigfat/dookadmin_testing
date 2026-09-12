<div class="box-body">
   <table id="SectionListData1" class="table table-bordered">
       <tbody>
         <tr>
           <th>#</th>
           <th>Image</th>
           <th>Title</th>
           <th>Sub Title</th>
           <th style="width: 5%">Action</th>
         </tr>
       @if(count($data)> 0 )
         @foreach( $data as $slider )          
           <tr>
             <td>{{ $loop->index +1 }}</td>
             <td style="width: 5%"><img style="width: 50%" src="{{$urlS3.$slider->image}}"></td>
             <td>{{$slider->title}}</td>
             <td>{{$slider->sub_title}}</td>
             <td>
               <a class="editSlider"  data-toggle="modal" data-id="{{ $slider->id }}" data-title="{{ $slider->title }}"  data-subtitle="{{$slider->sub_title}}" data-image="{{$slider->image}}" data-country_id="{{$slider->country_id }}" data-departureId="{{JSON_encode($slider->departure_id) }}" data-departureName="{{JSON_encode($slider->departure_name) }}" title="Edit details" style="cursor: pointer;">
               <i class="fa fa-edit"></i>
               </a>
             </td>
           </tr>
         @endforeach
       @endif
     </tbody>
 </table>
</div>
<!-- <div class="box-footer clearfix text-right">
</div> -->