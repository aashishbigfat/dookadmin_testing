<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Departure;
use App\Destination;
use App\Inclusion;

class PublishItineraryToAppolyteController extends Controller
{
    public function publishItineraryToAppolyte(Request $request, $id){
        $user = auth()->user();

        $basic_detail = DB::table('departures')
                        ->where('id', $id)
                        ->select('title','no_of_nights','no_of_days','tenant_id','description','conditions','dep_dook_ref_id as package_id','image as fimage')
                        ->first();
        if($basic_detail){
            $basic_detail->image = "https://adm.dookinternational.com/dook/images/package/".$basic_detail->fimage;
            $destination_id = DB::table('departure_destinations')
                            ->where('departure_id', $id)
                            ->distinct()
                            ->pluck('destination_id')
                            ->toArray();
            $destination_name = DB::table('destinations')
                                ->whereIn('id', $destination_id)
                                ->select('reference_id','dest_name','geonameid','country_iso_3','country_iso_2','country_name','latitude','longitude','region')
                                ->get();
            $basic_detail->destinations = $destination_name;
                        
            $itineraries = DB::table('itineraries')
                        ->where('departure_id', $id)
                        ->distinct()
                        ->select('id','day_number','day_heading','description')
                        ->get();
            foreach ($itineraries as $key => $itinearay) {
                $description = strip_tags($itinearay->description, '<ul><li>');
                $itinearay->description = str_replace("\r\n",'', $description);
                $iti_destination_id = DB::table('destination_itinerary_point_of_interests')
                                ->where('itinerary_id', $itinearay->id)
                                ->distinct()
                                ->pluck('destination_id')
                                ->toArray();

                $day_destinations = Destination::whereIn('id', $iti_destination_id)
                                ->pluck('dest_name')
                                ->toArray();
                $dest_str = implode('@#',  $day_destinations);
                $itinearay->destinations = $dest_str;
            }

            $inclusions = Inclusion::where('departure_id',$id)
                        ->select('name', 'description')
                        ->get();

            $post = array(
                'basic_detail' => $basic_detail,
                'itineraries' => $itineraries,
                'inclusions' => $inclusions,
            );
            // echo "<pre>";
            // print_r($post);
            // exit;
            $data_string = json_encode($post);                                                                                
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://customer.appolyte.com/api/v1/dook-itinerary-publish');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);  
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
                'Content-Type: application/json',                                                                                
                'Content-Length: ' . strlen($data_string))                                                                       
            ); 
            $output = curl_exec($ch);
            curl_close($ch);
            $responce = json_decode($output);
            // print_r($responce);
            // exit;
            // die;
            if($responce->data == 'Success'){
                $departure = Departure::find($id);
                $departure->appolyte_status = 1;
                $departure->save();
                return response()->json(['msg_s'=>"Itinerary has been published!"]);
            }
            else{
                return response()->json(['msg'=>"Something went Wrong!"]);
            }
        }
    }
}
