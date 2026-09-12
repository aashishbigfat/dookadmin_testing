<?php

namespace App\Http\Controllers\Departure;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Departure;
use App\Destination;
use App\Country;
use App\DepartureDestination;
use App\Experience;
use App\DestinationExperience;
use App\Activity;
//use App\ActivityPointOfInterest;
//use App\DepartureDestinationPointOfInterest;
//use App\DepartureDestinationActivityPointOfInterest;
use App\ActivityExperience;
use App\ActivityDeparture;

class FixedDepartureActivityController extends Controller
{
    public function activityIndex(Request $request)
    {

        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;

        $get_experience = DestinationExperience::where("departure_id", $route_id)
                        ->distinct()
                        ->pluck('experience_id')
                        ->toArray();
        $experiences = Experience::whereIn("id", $get_experience)
                    ->distinct()
                    ->select('experience_name')
                    ->get();
        $get_activities = ActivityExperience::whereIn('experience_id',$get_experience)
                        ->distinct()
                        ->pluck('activity_id')  
                        ->toArray();

        $activities = Activity::whereIn('id', $get_activities)
                    ->distinct()
                    ->select('id','activity_name')
                    ->orderBy('activity_name', 'ASC')
                    ->get();
        $dep_activity = ActivityDeparture::where('departure_id',$route_id)
                        ->distinct()
                        ->select('activity_id')
                        ->get(); 

        if(count($dep_activity) > 0){
            return view('departure.activity_edit',compact('activities','dep_activity','experiences'));
        }else{
            return view('departure.activity_create',compact('activities','experiences'));
        }  
    }

    public function activityStore(Request $request)
    {
        $data = $request->all();
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $user = auth()->user(); 
        if($request->activities){
            foreach ($request->activities as $value) {
                $activity = new ActivityDeparture;
                $activity->departure_id = $route_id;
                $activity->activity_id = $value;
                $activity->save();
            }
        }
        $status = [
            'status'=> 'Success!',
        ];
        return response()->json($status); 
    }

    public function activityUpdate(Request $request)
    {
        $data = $request->all();

        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $user = auth()->user(); 

        if($request->activities){
            ActivityDeparture::where('departure_id', $route_id)->delete();
            foreach ($request->activities as $value) {
                $activity = new ActivityDeparture;
                $activity->departure_id = $route_id;
                $activity->activity_id = $value;
                $activity->save();
            }
        }
        $status = [
            'status'=> 'Success!',
        ];
        return response()->json($status); 
    }

    public function getExpActivityAjax(Request $request)
    {
        // $route_id = $request->route_id;
        // $ids = explode(',',$request->experience_id);
        // //$ids = array(1,4,5);
        // $activities = DB::table('activity_experiences')
        //             ->whereIn('experience_id',$ids)
        //             ->pluck('activity_id')
        //             ->toArray();

        // $activity =  DB::table('activities')
        //         ->whereIn("id",$activities)
        //         ->orderBy('activities.activity_name', 'ASC')
        //         ->distinct()
        //         ->select("activities.id",'activities.activity_name')
        //         ->get();
        //     return response()->json($activity);
    }
}
