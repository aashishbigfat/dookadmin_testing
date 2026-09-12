<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use DB;
use Image;
use Auth;
use finfo;
use Storage;
use App\MegaMenuDestination;
use App\MegaMenuCountry;

class MegaMenuController extends Controller
{
    public function megaMenuDestination(Request $request)
    {
        $destinations = MegaMenuDestination::select('id','name','country','order')
                            ->orderBy('order','ASC')
                            ->get();
        $total = count($destinations);
        return view('megamenu.mega_destination',compact('destinations','total'));
    }

    public function megaMenuDestinationStore(Request $request)
    {
        $data = $request->all();
        if($request->destination){
            foreach($request->destination as $value) {
                $dest_data = DB::table('destinations')
                    ->where('id', $value)
                    ->select('id','dest_name','country_name')
                    ->first();
                $order_last = MegaMenuDestination::orderBy('order','DESC')->value('order');
                $mega_destination = new MegaMenuDestination;
                $mega_destination->destination_id = $dest_data->id;
                $mega_destination->name = $dest_data->dest_name;
                $mega_destination->order = $order_last + 1;
                $mega_destination->country = $dest_data->country_name;
                $mega_destination->save();
            }
        }
        return response()->json(['success'=>'Success!']);
    }

    public function megaMenuDestinationDelete(Request $request, $id)
    {
        
        $destination = MegaMenuDestination::where('id',$id)->delete();
        return response()->json(['success'=>'Success!']);
    }

    public function positionShifting(Request $request){

        if($request->position)
        {
            $pos = $request->position;
            $i = 0;
            foreach($pos as $k=>$v)
            {
                $i++;
                $item = MegaMenuDestination::find($v);
                $item->order = $i;
                $item->save();
            }
            exit;
            return response()->json([
               'success' => "success"
           ]);
        }
        else
        {
            return response()->json([
               'success' => "false"
           ]);
        }
     }

    public function megaMenuCountry(Request $request)
    {
        $countries = MegaMenuCountry::select('id','name','destination','order')
                            ->orderBy('order','ASC')
                            ->get();
        $total = count($countries);
        return view('megamenu.mega_country',compact('countries','total'));
    }

    public function megaMenuCountryStore(Request $request)
    {
        $data = $request->all();
        if($request->country){
            foreach($request->country as $value) {
                $country_data = DB::table('countries')
                    ->where('id', $value)
                    ->select('id','country_name')
                    ->first();
                $order_last = MegaMenuCountry::orderBy('order','DESC')
                        ->value('order');
                $mega_country = new MegaMenuCountry;
                $mega_country->country_id = $country_data->id;
                $mega_country->order = $order_last + 1;
                $mega_country->name = $country_data->country_name;
                $mega_country->save();
            }
        }
        return response()->json(['success'=>'Success!']);
    }

    public function megaMenuCountryDelete(Request $request, $id)
    {
        
        $countries = MegaMenuCountry::where('id',$id)->delete();
        return response()->json(['success'=>'Success!']);
    }

    public function positionShiftingCountry(Request $request){

        if($request->position)
        {
            $pos = $request->position;
            $i = 0;
            foreach($pos as $k=>$v)
            {
                $i++;
                $item = MegaMenuCountry::find($v);
                $item->order = $i;
                $item->save();
            }
            exit;
            return response()->json([
               'success' => "success"
           ]);
        }
        else
        {
            return response()->json([
               'success' => "false"
           ]);
        }
     }
}
