<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Departure;
use App\Destination;

class PublishItineraryToFinderController extends Controller
{
    public function publishItinerary(Request $request, $id){
    	$user = auth()->user();

    	$basic_detail = DB::table('departures')
    					->where('id', $id)
    					->select('title','no_of_nights','no_of_days','tenant_id','banner_image','description')
    					->first();
		if($basic_detail){
			$basic_detail->banner_images = $basic_detail->banner_image;
			$basic_detail->banner_image = 'https://adm.dookinternational.com/dook/images/package/'.$basic_detail->banner_image;

			$destination_id = DB::table('departure_destinations')
							->where('departure_id', $id)
							->distinct()
							->pluck('destination_id')
							->toArray();
			$destination_name = DB::table('destinations')
								->whereIn('id', $destination_id)
								->select('reference_id','dest_name','geonameid','country_iso_3','country_iso_2','country_name','latitude','longitude')
								->get();
			$basic_detail->destinations = $destination_name;

			$experience_id = DB::table('destination_experiences')
						->where('departure_id', $id)
						->distinct()
						->pluck('experience_id')
						->toArray();
			$experience = DB::table('experiences')
						->whereIn('id', $experience_id)
						->select('experience_name')
						->get();
			$basic_detail->experiences = $experience;
						
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

				$day_destinations = DB::table('destinations')
								->whereIn('id', $iti_destination_id)
								->select('dest_name')
								->get();
				$itinearay->destinations = $day_destinations;

				$poi_id = DB::table('destination_itinerary_point_of_interests')
						->where('itinerary_id', $itinearay->id)
						->pluck('point_of_interest_id')
						->toArray();

				$poi_img = DB::table('departure_destination_point_of_interests')
						->whereIn('reference_id', $poi_id)
						->whereNotNull('banner_image')
						->value('banner_image');
				if($poi_img){
					$itinearay->image = 'https://adm.dookinternational.com/dook/images/poi/'.$poi_img;
				}else{
					$itinearay->image = '';
				}

				// Fetch pois
				$pois = DB::table('departure_destination_point_of_interests')
						->whereIn('reference_id', $poi_id)
						->where('departure_id', $id)
						->select('destination_id', 'poi_name', 'latitude', 'longitude', 'image', 'address', 'rating', 'reviews', 'poi_type', 'phone', 'website', 'openhours', 'description', 'reviews', 'poi_type','reference_id')
						->get();
						
				if(count($pois)){
					foreach ($pois as $key => $value) {
						$value->image = 'https://adm.dookinternational.com/dook/images/poi/'.$value->image;
						
						$poi_dest = Destination::where('id', $value->destination_id)
								->select('id as dest_id','dest_name','country_name','country_iso_3','country_iso_2')
								->first();
						if($poi_dest){
							$value->destination = $poi_dest->dest_name;
							$value->country = $poi_dest->country_name;
							$value->dest_id = $poi_dest->dest_id;
							$value->country_iso_2 = $poi_dest->country_iso_2;
							$value->country_iso_3 = $poi_dest->country_iso_3;
						}else{
							$value->destination = "";
							$value->country = "";
							$value->dest_id = "";
							$value->country_iso_2 = "";
							$value->country_iso_3 = "";
						}

					}
					$itinearay->pois = $pois;
				}
				$itinearay->pois = $pois;

			}
			$pois = DB::table('departure_destination_point_of_interests')
					->where('departure_id', $id)
					->select('destination_id', 'poi_name', 'latitude', 'longitude', 'image', 'address', 'rating', 'reviews', 'poi_type', 'phone', 'website', 'openhours', 'description', 'reviews','reference_id')
					->get();
				if(count($pois)){
					foreach ($pois as $key => $pvalue) {
						$pvalue->image = 'https://adm.dookinternational.com/dook/images/poi/'.$pvalue->image;
						$poi_dest = Destination::where('id', $pvalue->destination_id)
								->select('id as dest_id','dest_name','country_name','country_iso_3','country_iso_2')
								->first();
						if($poi_dest){
							$pvalue->destination = $poi_dest->dest_name;
							$pvalue->country = $poi_dest->country_name;
							$pvalue->dest_id = $poi_dest->dest_id;
							$pvalue->country_iso_2 = $poi_dest->country_iso_2;
							$pvalue->country_iso_3 = $poi_dest->country_iso_3;
						}else{
							$pvalue->destination = "";
							$pvalue->country = "";
							$pvalue->dest_id = "";
							$pvalue->country_iso_2 = "";
							$pvalue->country_iso_3 = "";
						}

					}
				}
			$user_data = DB::table('users')
						->where('id', $user->id)
						->select('id','name','email','phone','password','tenant_id')
						->first();

            $post = array(
	            'basic_detail' => $basic_detail,
	            'itineraries' => $itineraries,
	           	'user' => $user_data,
	           	'pois' => $pois,
            );
            //dd($itineraries);
            $data_string = json_encode($post);                                                                                   
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://www.itineraryfinder.com/api/dook-itinerary-publish');
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
				$departure->itinerary_status = 1;
				$departure->save();
				return response()->json(['msg_s'=>"Itinerary has been published!"]);
			}
			else{
				return response()->json(['msg'=>"Something went Wrong!"]);
			}
		}
    }
}
