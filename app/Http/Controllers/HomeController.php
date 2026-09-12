<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Departure;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        if($user && $user->email == 'signature@dooktravels.com'){
            Auth::logout();
            abort(403,'Unauthorized');
        }

        $package = Departure::where('dep_type','package')->where('status',1)->count();
        $totalpackage = Departure::where('dep_type','package')->count();
        $group = Departure::where('dep_type','main')->where('status',1)->count();
        $regions = DB::table('regions')->count();
        $countries = DB::table('countries')->where('status',1)->count();
        $destination = DB::table('destinations')->where('status',1)->count();

        $pois = DB::table('departure_destination_point_of_interests')->distinct()
                ->pluck('reference_id')
                ->toArray();
        $unique_pois = array_unique($pois);
        $poi = count($unique_pois);
        $experience = DB::table('experiences')->where('status',1)->count();
        $activities = DB::table('activities')->where('status',1)->count();


        return view('home',compact('package','destination','countries','regions','poi','experience','activities','group','totalpackage'));
    }
}
