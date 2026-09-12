<?php

namespace App\Http\Controllers\Departure;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Intervention\Image\ImageServiceProvider;
use DB;
use Storage;
use Image;
use Auth;
use App\User;
use App\Departure;
use App\HotelCategory;
use App\RsDollor;
use App\Destination;

class HotelCategoryController extends Controller
{
    public function packagesHotelCategoryCreate(Request $request)
    {	$route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $package = Departure::where('id', $route_id)->value('title');
        $inr = RsDollor::value('inr');
    	$hotel_categories = HotelCategory::where('departure_id', $route_id)
	    					->distinct()
                            ->orderBy('id','DESC')
	    					->get();
          $destinations = Destination::join('departure_destinations','departure_destinations.destination_id','=','destinations.id')
            ->where('departure_destinations.departure_id',$route_id)
            ->distinct()
            ->select("destinations.id","destinations.dest_name as dest_name")
            ->get();

        return view('packages.hotel_category_create',compact('hotel_categories','package','inr','destinations','route_id'));
    }
    public function packagesHotelCategoryStore(Request $request)
    {
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids; 

        $hotel_category  = new HotelCategory;
        $hotel_category->departure_id = $route_id;
        $hotel_category->hotel_category_name = $request->hotel_category_name; 
        $hotel_category->price_inr = $request->price_inr; 
        $hotel_category->price_usd = $request->price_usd; 
        $hotel_category->destination = $request->destination; 
        $hotel_category->hotel_name = $request->hotel; 
        $hotel_category->save();
        $status = [
            'url'=> url('/package/hotelcategory',$route_id),
        ];
        return response()->json($status);  
    }

    public function packagesHotelCategoryUpdate(Request $request, $id)
    {
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids; 

        $hotel_category  = HotelCategory::find($id);
        //$hotel_category->departure_id = $route_id;
        $hotel_category->hotel_category_name = $request->edit_hotel_category_name; 
        $hotel_category->price_inr = $request->edit_price_inr; 
        $hotel_category->price_usd = $request->edit_price_usd; 
        $hotel_category->destination = $request->edit_destination; 
        $hotel_category->hotel_name = $request->edit_hotel; 
        $hotel_category->save();
        $status = [
            'url'=> url('/package/hotelcategory',$route_id),
        ];
        return response()->json($status);  
    }

    public function packagesHotelCategoryDelete(Request $request, $id)
    {
        HotelCategory::find($id)->delete();
        return response()->json(['success'=>'Hotel category deleted successfully!']);
    }

    //Rupee Dollor

    public function rupeeDollorUpdate(Request $request, $id)
    {
        $dep_data = Departure::where('price_usd','!=','')->get();
        $rs_dollor = RsDollor::find($id);
        $rs_dollor->inr = $request->rupies;
        $rs_dollor->save();
        foreach ($dep_data as $key => $value) {
            $dep_update = Departure::find($value->id);
            if($value->price_usd != ""){
                $dep_update->price = $value->price_usd*$request->rupies;
                $dep_update->save();
            }
        }
        $status = [
            'msg'=> "Price Successfully Updated!",
        ];
        return response()->json($status); 
    }
}
