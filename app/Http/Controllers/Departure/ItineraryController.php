<?php

namespace App\Http\Controllers\Departure;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use DB;
use App\Departure;
use App\Destination;
use App\Itinerary;
use App\DestinationItineraryPointOfInterest;
use App\DepartureDestination;
use App\PointOfInterest;
use App\DepartureDestinationPointOfInterest;
use App\Inclusion;

class ItineraryController extends Controller
{
    //packageing Departures
    public function packagesItineraryIndex(Request $request)
    {
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $tour = Departure::where('id',$route_id)->value('no_of_days');
        $itineraries = Itinerary::where(['departure_id' => $route_id, 'dep_type'=>'package'])->orderBy('day_number', 'ASC')->get();

        if(count($itineraries)>0){
            
            $i=0;
            foreach ($itineraries as $key => $value) {
                $data = explode(",",$value->included);
                $itineraries[$i]['inclusion'] = $data;
                $i++;
                if($value->included != ''){
                    $inclusion_name = array();
                    foreach ($data as $key => $values) {
                        $itineary_row = Inclusion::where('id',$values)
                                            ->first();
                        if($itineary_row){
                            array_push($inclusion_name, $itineary_row->name);
                        }
                    }
                    $value['inclusion_name'] = $inclusion_name;
                }else{
                     $value['inclusion_name'] = [];
                }
            }
        }
        // echo "<pre>";
        // print_r($itineraries);
        // exit;
        $inclusions = Inclusion::where('departure_id',$route_id)
                     ->get();
        $dd = count($itineraries);
           if($dd > 0 || $dd != '' || $dd != null){
                    $itineary_dest = [];
                    foreach ($itineraries as $value) {
                        $itineary_dest[] = DestinationItineraryPointOfInterest::join('destinations','destinations.id','=','destination_itinerary_point_of_interests.destination_id')
                            ->distinct()
                            ->select('destinations.dest_name','destination_itinerary_point_of_interests.itinerary_id')
                            ->where('destination_itinerary_point_of_interests.itinerary_id', $value->id)
                            ->get();
                    }
                    $data_dest = Arr::flatten($itineary_dest);
                }

            else{
                $data_dest = [];
            }

            if($dd > 0 || $dd != '' || $dd != null){
                $itineary_pois = [];
                foreach ($itineraries as $value) {
                    $itineary_pois[] = DestinationItineraryPointOfInterest::join('departure_destination_point_of_interests','departure_destination_point_of_interests.reference_id','=','destination_itinerary_point_of_interests.point_of_interest_id')
                        ->distinct()
                        ->select('departure_destination_point_of_interests.poi_name','destination_itinerary_point_of_interests.itinerary_id')
                        ->where('destination_itinerary_point_of_interests.itinerary_id',$value->id)
                        ->get();
                         //dd($itineary_pois);
                }
                    $data_poi = Arr::flatten($itineary_pois);
            }
            else{
                $data_poi = [];
            }

            foreach ($itineraries as $key => $itinerary){
                $itineary_row = DestinationItineraryPointOfInterest::where('itinerary_id',$itinerary->id)
                    ->distinct()
                    ->pluck('destination_id')->toArray();
                $itinerary['destination_id'] = $itineary_row;

                $destination_name = array();
                    foreach ($itineary_row as $key => $value) {
                        $dest_name = Destination::where('id', $value)->first();
                        array_push($destination_name, $dest_name->dest_name);
                    }
                $itinerary['destination_name'] = $destination_name;
            } 
        //POI
            foreach ($itineraries as $key => $itinerary){
                $poi_rows = DestinationItineraryPointOfInterest::join('departure_destination_point_of_interests','departure_destination_point_of_interests.reference_id','=','destination_itinerary_point_of_interests.point_of_interest_id')
                    ->join('destinations','destinations.id','=','destination_itinerary_point_of_interests.destination_id')
                    ->where('destination_itinerary_point_of_interests.itinerary_id',$itinerary->id)
                    ->distinct()
                    ->select('departure_destination_point_of_interests.poi_name','departure_destination_point_of_interests.reference_id as poi_id','destinations.dest_name','destinations.id as dest_id','departure_destination_point_of_interests.id as loc_id')->get()->toArray();
                $itinerary['poi_id'] = $poi_rows;

                $poi_row = DestinationItineraryPointOfInterest::where('itinerary_id',$itinerary->id)
                    ->distinct()
                    ->pluck('point_of_interest_id')->toArray();
                //$itinerary['poi_id'] = $poi_row;

                $poi_name = array();
                    foreach ($poi_row as $key => $value) {
                        $poiint_name = DepartureDestinationPointOfInterest::where('reference_id', $value)->first();
                        if($poiint_name){
                            array_push($poi_name, $poiint_name->poi_name);
                        }
                    }
                $itinerary['poi_name'] = $poi_name;
            } 

        $itinerary = Itinerary::where('departure_id',$route_id)->count()+1;
        $destinations = Destination::join('departure_destinations','departure_destinations.destination_id','=','destinations.id')
                        ->where('departure_destinations.departure_id',$route_id)
                        ->distinct()
                        ->select("destinations.id","destinations.dest_name")
                        ->get();
        $iti_destinations = DestinationItineraryPointOfInterest::join('departure_destination_point_of_interests','departure_destination_point_of_interests.destination_id','=','destination_itinerary_point_of_interests.destination_id')
                        ->join('destinations','destinations.id','=','destination_itinerary_point_of_interests.destination_id')
                        //->where(['destination_itinerary_point_of_interests.departure_id' => $route_id, 'tenant_id' => auth()->user()->tenant_id])
                        ->where('destination_itinerary_point_of_interests.departure_id', $route_id)
                        ->select('departure_destination_point_of_interests.poi_name','departure_destination_point_of_interests.reference_id as poi_id','destinations.dest_name','destinations.id as dest_id','departure_destination_point_of_interests.id as loc_id')
                        ->get();
            //$iti_destination = json_decode($iti_destinations);  
            //dd($iti_destination) ;
        return view('packages.itinerary_create',compact('tour','itinerary','destinations','itineraries','data_dest','data_poi','iti_destinations','inclusions'));
    }

    public function packagesItineraryStore(Request $request)
    {
        $data = $request->all();

        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $user = auth()->user(); 
        if($request->Inclusions){
            $array_check = array();
            for($i = 0; $i < count($request->Inclusions); $i++) {
                if ($request->Inclusions[$i] != '') {
                    array_push($array_check, $request->Inclusions[$i]);
                }
            }
        }
        else{
            $array_check = [];
        }

        $descriptions = preg_replace('/<!--\[if gte mso 9\]>.*<!\[endif\]-->/s', '', $request->description);
        $descriptions = str_replace("\r\n",'', $descriptions);
        $descriptions = str_replace('<!--StartFragment--><span lang="VI">','', $descriptions);
        $descriptions = str_replace('<o:p></o:p>','', $descriptions);

        $itinerary = new Itinerary;
        $itinerary->day_number = $request->day;
        $itinerary->day_heading = $request->heading;
        $itinerary->included = $request->inclusion;
        $itinerary->excluded = $request->exclusion;
        $itinerary->description = $descriptions;
        $itinerary->departure_id=$route_id;
        $itinerary->tenant_id = $user->tenant_id;
        $itinerary->user_id = $user->id;
        $itinerary->included = implode(",",$array_check);
        $itinerary->unique_key = Str::random(10).time();
        $itinerary->dep_type = "package";
        $itinerary->save();
        $last_id = $itinerary->id;
        if(isset($request->pois))
        {
            $destpoi=json_encode($request->pois);
            $destpoi1=json_decode($destpoi);
            $length = count($destpoi1);
            $destinations =$request->destinations;
                if($destinations){                
                    foreach ($destinations as $value) { 
                        for ($i = 0; $i < $length; $i++) {                 
                            $n= json_decode($destpoi1[$i]);
                            $abc=$n->poi_id;
                            if($value==$n->dest_id){
                                $destinationpoi  = new DestinationItineraryPointOfInterest;
                                $destinationpoi->departure_id=$route_id;
                                $destinationpoi->itinerary_id=$last_id;
                                $destinationpoi->destination_id=$value;
                                $destinationpoi->point_of_interest_id=$abc;
                                $destinationpoi->save();
                             
                            }                         
                        }
                    }
                }
        }
        else{
            if($request->destinations){
                
                foreach ($request->destinations as $value) {
                    $destinationpoi  = new DestinationItineraryPointOfInterest;
                    $destinationpoi->departure_id=$route_id;
                    $destinationpoi->itinerary_id=$last_id;
                    $destinationpoi->destination_id=$value;
                    //$destinationpoi->point_of_interest_id=$abc;
                    $destinationpoi->save();
                }
            }
        }

        $status = [
                'url'=> url('/packages/itinerary/create',$route_id),
            ];
        return response()->json($status);
    }

    public function packagesItineraryUpdate(Request $request, $id)
    {
        // $data = $request->all();

        $route_id = $request->route_id; 
        if($request->Edit_Inclusions){
            $array_check = array();
            for($i = 0; $i < count($request->Edit_Inclusions); $i++) {
                if ($request->Edit_Inclusions[$i] != '') {
                    array_push($array_check, $request->Edit_Inclusions[$i]);
                }
            }
        }
        else{
            $array_check = [];
        }

        $edit_descriptions = preg_replace('/<!--\[if gte mso 9\]>.*<!\[endif\]-->/s', '', $request->edit_description);
        $edit_descriptions = str_replace("\r\n",'', $edit_descriptions);
        $edit_descriptions = str_replace('<!--StartFragment--><span lang="VI">','', $edit_descriptions);
        $edit_descriptions = str_replace('<o:p></o:p>','', $edit_descriptions);

        $user = auth()->user(); 
        $itinerary       = Itinerary::find($id);
        $itinerary->day_number = $request->edit_day;
        $itinerary->day_heading = $request->edit_heading;
        // $itinerary->included = $request->edit_inclusion;
        // $itinerary->excluded = $request->edit_exclusion;
        $itinerary->description = $edit_descriptions;
        $itinerary->included = implode(",",$array_check);
        $itinerary->user_id = $user->id;
        $itinerary->save();
        $last_id = $itinerary->id;
        if(isset($request->edit_pois))
        {
            $destpoi=json_encode($request->edit_pois);
            $destpoi1=json_decode($destpoi);
            $length = count($destpoi1);
            $destinations =$request->edit_destinations;
            if($destinations){ 
                DestinationItineraryPointOfInterest::where('departure_id',$route_id)->where('itinerary_id',$last_id)->delete(); 
                //print_r($aa);        
                foreach ($destinations as $value) { 
                    for ($i = 0; $i < $length; $i++) {                 
                        $n= json_decode($destpoi1[$i]);
                        $abc=$n->poi_id;
                        if($value==$n->dest_id){
                            $destinationpoi  = new DestinationItineraryPointOfInterest;
                            $destinationpoi->departure_id=$route_id;
                            $destinationpoi->itinerary_id=$last_id;
                            $destinationpoi->destination_id=$value;
                            $destinationpoi->point_of_interest_id=$abc;
                            $destinationpoi->save();
                             
                        }                         
                    }
                }
            }
        }
        else{
            DestinationItineraryPointOfInterest::where('departure_id',$route_id)->where('itinerary_id',$last_id)->delete(); 
            if($request->edit_destinations){
                
                foreach ($request->edit_destinations as $value) {
                    $destinationpoi  = new DestinationItineraryPointOfInterest;
                    $destinationpoi->departure_id=$route_id;
                    $destinationpoi->itinerary_id=$last_id;
                    $destinationpoi->destination_id=$value;
                    //$destinationpoi->point_of_interest_id=$abc;
                    $destinationpoi->save();
                }
            }
        }

        $status = [
            'url'=> url('/packages/itinerary/create',$route_id),
            ];
        return response()->json($status);
    }

    public function packagesItinerayDisable(Request $request, $id)
    {
        $day_n = Itinerary::where('id',$id)->select('day_number','departure_id')->first();
        $iti_t = DB::table('itineraries')->where('departure_id',$day_n->departure_id)
                ->where('day_number', '>', $day_n->day_number)
                ->select('id','day_number')
                ->get()->toArray();
        foreach ($iti_t as $key => $value) {
            $day_update = Itinerary::find($value->id);
            $day_update->day_number = $value->day_number -1;
            $day_update->save();
        }
        Itinerary::find($id)->delete();
        DestinationItineraryPointOfInterest::where('itinerary_id',$id)->delete();

        // $itineraries = Itinerary::find($id);
        // if($itineraries->status == 1){
        //     $itineraries->status = 0;
        //     $itineraries->save();
        // }
        // else{
        //     $itineraries->status = 1;
        //     $itineraries->save();
        // }
        return response()->json(['success'=>'Itinerary deleted successfully!']);
    }

    public function getDestinationPoiAjax(Request $request)
    {
        $route_id = $request->route_id;
        $ids = explode(',',$request->destination_id);
        //$ids = array(11110,3186);
        $pois = DB::table('departure_destination_point_of_interests')
          ->join('destinations','destinations.id','=','departure_destination_point_of_interests.destination_id')
          ->where('departure_destination_point_of_interests.departure_id',$route_id)
              ->where(function ($query) use ($ids) {
                  $query->whereIn("departure_destination_point_of_interests.destination_id",$ids);
              })
            ->orderBy('departure_destination_point_of_interests.id', 'ASC')
            ->select("departure_destination_point_of_interests.poi_name","departure_destination_point_of_interests.reference_id as poi_id","destinations.dest_name","destinations.id as dest_id","departure_destination_point_of_interests.id as loc_id")
                ->get();
            return response()->json($pois);
    }
}