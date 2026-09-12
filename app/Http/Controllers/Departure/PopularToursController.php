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
use App\Destination;
use App\Country;
use App\DepartureDestination;
use App\Experience;
use App\DestinationExperience;
use App\AgentItinerary;
use App\DepartureOptionalActivity;
use App\IconInclusion;
use App\DepartureIconInclusion;

class PopularToursController extends Controller
{
    public function popularPackagesIndex(Request $request)
    {
        $from_date = $request->from_date;
        $from = date("yy-m-d", strtotime($from_date));
        $to_date = $request->to_date;
        $to = date("yy-m-d", strtotime($to_date));

        if($request->from_date){
            $departures = Departure::where('most_popular','1')
                                    ->whereBetween('date',[$from, $to])
                                    ->orderBy('id', 'DESC')
                                    ->paginate(25);
        }else{
            $departures = Departure::where('most_popular','1')
                                    ->orderBy('id', 'DESC')
                                    ->paginate(25);
        }
        $departureCount = Departure::where('most_popular','1')->get();
        $total = count($departureCount);
        if($request->ajax()){
                return view('popularPkg.popular_pkg_data',compact('departures'));
            }
        return view('popularPkg.popular_pkg_index',compact('departures','total'));
    }

    public function makePopularPackages(Request $request, $id)
    {
        $package  = Departure::find($id);
        if($package->most_popular == 1){
            $package->most_popular = 0;
            $package->save();
        }
        else{
            $package->most_popular = 1;
            $package->save();
        }
        return response()->json(['success'=>'Success!']);
    }
    public function popularPackagesDisable(Request $request, $id)
    {
        $package  = Departure::find($id);
        if($package->status == 1){
            $package->status = 0;
            $package->save();
        }
        else{
            $package->status = 1;
            $package->save();
        }
        
        return response()->json(['success'=>'Success!']);
    }
}
