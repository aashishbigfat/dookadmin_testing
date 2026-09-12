<?php

namespace App\Http\Controllers\Departure;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HotelMaster;
use DB;
use App\Destination;

class HotelController extends Controller
{
    public function hotelIndex()
    {
        $countries = DB::table('all_countries')
            ->select('id','country_name')
            ->orderBy('country_name')
            ->get();

        $destinations = DB::table('destinations')   
            ->select('id','country_id','dest_name','country_name')
            ->orderBy('dest_name')
            ->get(); 

        return view('hotel.hotel_create',compact('countries','destinations'));
    }

    public function hotelStore(Request $request)
    {
        $data = $request->all();

        $hotel_master = new HotelMaster();
        $hotel_master->hotel_name     = $request->hotel_name;
        $hotel_master->hotel_category = $request->hotel_category;
        $hotel_master->country        = $request->country;
        $hotel_master->city           = $request->city;
        $hotel_master->state          = $request->state;
        $hotel_master->zip            = $request->zip;
        $hotel_master->save();

        $status = [
            'url'=> url('hotel-create'),
        ];
        
        return response()->json($status);
    }

    public function getDestCountry(Request $request)
    {
        $dest_name = $request->destination_name;

        $country_name = DB::table('destinations')->where('dest_name',$dest_name)->value('country_name');

        $post = array(
			'country_name' => $country_name
		);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'https://pullit.watconsultingservices.com/api/state');
		//curl_setopt($curl, CURLOPT_URL, 'http://127.0.0.1:8030/api/state');
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
		$response = curl_exec($curl);
		curl_close ($curl);

        $jsons = json_decode($response); 

        $status = [
            'country'=> $country_name,
            'states' => $jsons->states
        ];

        return response()->json($status);
    }

    public function getHotelDetails(Request $request)
    {
        $dest_name = $request->destination_name;
        $hotel_category = $request->hotel_category;

        $hotels = DB::table('hotel_masters')
            ->where('city',$dest_name)
            ->where('hotel_category',$hotel_category)
            ->pluck('hotel_name');

        $status = [
            'hotel'=> $hotels,
        ];

        return response()->json($status);
    }

    public function getDestinationDetails(Request $request)
    {
        $route_id = (int)$request->route('id'); 
        $hotel_category = $request->hotel_category;
    
        $hotel_master_dest = DB::table('hotel_masters')->where('hotel_category', $hotel_category)->pluck('city');
    
        $destinations = Destination::join('departure_destinations', 'departure_destinations.destination_id', '=', 'destinations.id')
            ->where('departure_destinations.departure_id', $route_id)
            ->whereIn('destinations.dest_name', $hotel_master_dest)
            ->distinct()
            ->pluck('destinations.dest_name');

        $status = [
            'destinations' => $destinations,
        ];
        return response()->json($status);
    }
    
}
