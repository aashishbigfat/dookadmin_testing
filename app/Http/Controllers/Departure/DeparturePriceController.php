<?php

namespace App\Http\Controllers\Departure;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DeparturePrice;

class DeparturePriceController extends Controller
{
    public function index($id, $d_id){
        $departure_pricing = DeparturePrice::where('departure_id',$id)
                           ->where('d_date_id',$d_id)
                           ->get();
        return view('departurePrice.create',compact('departure_pricing'));
    }
    public function update(Request $request){
        
        $update = DeparturePrice::find($request->id);
        $update->dook_price_inr = $request->price_inr;
        $update->dook_price_usd = $request->price_usd;
        $update->save();
        
        $data = DeparturePrice::where('departure_id',$request->departure_id)
              ->where('d_date_id',$request->d_date_id)
              ->where('id',$request->id)
              ->select('id','dook_price_inr','dook_price_usd')
              ->first();
        return response()->json($data);
    }
}
