<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;
use App\Departure;
use App\Destination;
use App\Country;
use App\Itinerary;
use App\Inclusion;
use App\DepartureDestination;
use App\Experience;
use App\DestinationExperience;
use App\DestinationItineraryPointOfInterest;
use App\DepartureDestinationPointOfInterest;
use App\AgentItinerary;
use App\DepartureCloudDestination;
use App\DepartureDate;
use App\OriginatingFlight;
use App\ReturningFlight;
use App\DeparturePrice;

class PullController extends Controller
{
	public function pullIndex()
    {
    	$url = "https://www.departurecloud.com/api/get_pull_status";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		$result = curl_exec($ch);
		//echo $result;
		$data = json_decode($result);

        return view('pull.pull',compact('data'));
    }

    // agent pull departure
    public function getAgentDepartureAjax(Request $request){

    $tenant = auth()->user();
    $post = array(
        'text' => $request->DCPull
    );

    $headerArray = array(
        'Username: ABCDE1234T',
        'Password: 2064562'
    );

    $baseUrl = 'https://agent.dookinternational.com/api';
    $url = $baseUrl . '/departure/list';
    $method = 'GET';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArray);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post);

    $result = curl_exec($curl);
    $jsons = json_decode($result);

    foreach ($jsons->Result as $key => $value) {

        $destinationNames = $value->Destination;
        sort($destinationNames);

        $destinationIds = [];
        foreach ($destinationNames as $destinationName) {
            $destinationId = Destination::where('dest_name', $destinationName)->value('id');
            $destinationIds[] = $destinationId;
        }
        $destinationIdString = implode(',', $destinationIds);
        $urlPreDest = reset($destinationNames);
        
        $nameParts = explode('-', $value->Name, 2);
        $trimmedName = trim($nameParts[0]);
        $noOfDaysNights = explode(' ', $value->DayNight);
        $noOfDays = (int) $noOfDaysNights[0];
        $noOfNights = (int) $noOfDaysNights[2];

        $lastDookRefId = Departure::where('dep_type', 'main')
            ->orderBy('dep_dook_ref_id', 'DESC')
            ->first();
        $lastDookRefId = (int) $lastDookRefId->dep_dook_ref_id;

        if ($value->DealBy == 'Dook Special') {
            $departureCheck = Departure::where([
                'destination_str' => $destinationIdString,
                'no_of_days' => $noOfDays,
                'no_of_nights' => $noOfNights,
                'tenant_id_reff' => 'oxwtvlnriqs1634705279',
                'from' => 'Delhi',
                'dep_type' => 'main',
                'return_to' => $value->EndingAt
            ])->first();
        } else {
            $departureCheck = Departure::where([
                'destination_str' => $destinationIdString,
                'no_of_days' => $noOfDays,
                'no_of_nights' => $noOfNights,
                'tenant_id_reff' => 'oxwtvlnriqs1634705279',
                'from' => 'Delhi',
                'dep_type' => 'main',
                'return_to' => $value->EndingAt,
                'other_name' => ''
            ])->first();
        }

        if ($departureCheck) {
            $depId = ($value->DealBy == 'Dook Special') ?
                Departure::where([
                    'destination_str' => $destinationIdString,
                    'no_of_days' => $noOfDays,
                    'no_of_nights' => $noOfNights,
                    'tenant_id_reff' => 'oxwtvlnriqs1634705279',
                    'from' => 'Delhi',
                    'dep_type' => 'main',
                    'return_to' => $value->EndingAt
                ])->value('id') :
                Departure::where([
                    'destination_str' => $destinationIdString,
                    'no_of_days' => $noOfDays,
                    'no_of_nights' => $noOfNights,
                    'tenant_id_reff' => 'oxwtvlnriqs1634705279',
                    'from' => 'Delhi',
                    'dep_type' => 'main',
                    'return_to' => $value->EndingAt,
                    'other_name' => ''
                ])->value('id');

            $departure = Departure::find($depId);
            $departure->from = 'Delhi';
            $departure->return_to = $value->EndingAt;
            $departure->ending_at = '';
            $departure->total_seat = $value->AvailableSeats;
            $departure->save();
            $lastId = $departure->id;
            $lastDookId = $departure->dep_dook_ref_id;
        } else {
            $departure = new Departure;
            $titleName = (!empty($value->other_name)) ? $value->Name.' ('.$value->Name.')' : $value->Name;
            $departure->title = $titleName;
            $departure->from = 'Delhi';
            $departure->return_to = $value->EndingAt;
            $departure->ending_at = '';
            $departure->no_of_days = $noOfDays;
            $departure->no_of_nights = $noOfNights;
            $departure->tenant_id_reff = 'oxwtvlnriqs1634705279';
            $departure->tenant_id = 'oxwtvlnriqs1634705279';
            $departure->user_id = $tenant->id;
            $departure->dep_type = "main";
            $departure->departure_ownner = 'Dook Travels';
            $departure->destination_str = $destinationIdString;
            $departure->description = $value->Description;
            $departure->dc_departure_type = '1';
            $departure->owener_type = ($departure->tenant_id == 'oxwtvlnriqs1634705279') ? "dook" : "other";
            $departure->status = 0;
            $departure->book_online = 0;
            $departure->slug_url_pre = "group-tours";
            $departure->slug_url = $urlPreDest.'-'.$noOfNights.'-nights-package-'.$value->Id;
            $departure->dep_dook_ref_id = ($lastDookRefId) ? $lastDookRefId + 1 : '1000001';
            $departure->other_name = '';
            $departure->save();
            $lastId = $departure->id;
            $lastDookId = $departure->dep_dook_ref_id;
        }

        foreach ($destinationNames as $destinationName) {
            $destinationId = Destination::where('dest_name', $destinationName)->value('id');
            $depDestCheck = DepartureDestination::where(['departure_id' => $lastId, 'destination_id' => $destinationId])->first();
            if ($depDestCheck) {
                $depDestCheck->destination_id = $destinationId;
                $depDestCheck->save();
                $lastDepDestId = $depDestCheck->id;
            } else {
                $departureDestination = new DepartureDestination;
                $departureDestination->departure_id = $lastId;
                $departureDestination->destination_id = $destinationId;
                $departureDestination->user_id = $tenant->id;
                $departureDestination->save();
                $lastDepDestId = $departureDestination->id;
            }
        }

        $depDateCheck = DepartureDate::where(['departure_id' => $lastId, 'reference_id' => $value->Id])->first();
        if ($depDateCheck) {
            $departureDate = DepartureDate::find($depDateCheck->id);
            $departureDate->title = $value->Name;
            $departureDate->reference_id = $value->Id;
            $departureDate->dep_id = $value->Id;
            $departureDate->date = date("Y-m-d", strtotime($value->DepartureDate));
            $departureDate->end_date = date("Y-m-d", strtotime($value->ReturnDate));
            $departureDate->departure_ownner = 'Dook Travels';
            $departureDate->dep_type = '';
            $departureDate->dook_id = $lastDookId;
            $departureDate->dc_price = $value->Price;
            $departureDate->dc_currency = '₹';
            $departureDate->save();
            $lastDepDateId = $departureDate->id;
            $lastUniKey = $departureDate->unique_key;
        } else {
            $departureDate = new DepartureDate;
            $departureDate->title = $value->Name;
            $departureDate->reference_id = $value->Id;
            $departureDate->departure_id = $lastId;
            $departureDate->dep_id = $value->Id;
            $departureDate->date = date("Y-m-d", strtotime($value->DepartureDate));
            $departureDate->end_date = date("Y-m-d", strtotime($value->ReturnDate));
            $departureDate->description = $value->Description;
            $departureDate->departure_ownner =  'Dook Travels';
            $departureDate->unique_key = Str::random(10).time();
            $departureDate->tenant_id_reff = 'oxwtvlnriqs1634705279';
            $departureDate->tenant_id = 'oxwtvlnriqs1634705279';
            $departureDate->user_id = $tenant->id;
            $departureDate->dep_type = '';
            $departureDate->dook_id = $lastDookId;
            $departureDate->dc_price = $value->Price;
            $departureDate->dc_currency = '₹';
            $departureDate->save();
            $lastDepDateId = $departureDate->id;
            $lastUniKey = $departureDate->unique_key;
        }
    }

    $status = [
        'status' => 'Departners pulled successfully.',
    ];
    return response()->json($status);  
}
public function agentsyncDepartureAjax(Request $request){
    $tenant = auth()->user();
    $post = array(
        'text' => $request->Sync
    );

    $headerArray = array(
        'Username: ABCDE1234T',
        'Password: 2064562'
    );

    $baseUrl = 'https://agent.dookinternational.com/api';
    $url = $baseUrl . '/departure/list';
    $method = 'GET';
    
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headerArray);

    $result = curl_exec($curl);
    curl_close($curl);

    $jsons = json_decode($result);
    
    $arr = [];

    foreach($jsons->Result as $key => $value) {
        $destinationNames = $value->Destination;
        sort($destinationNames);

        $destinationIds = [];
        foreach ($destinationNames as $destinationName) {
            $destinationId = Destination::where('dest_name', $destinationName)->value('id');
            $destinationIds[] = $destinationId;
        }

        $destinationIdString = implode(',', $destinationIds);
        $value->destination_str = $destinationIdString;

        $nameParts = explode('-', $value->Name, 2);
        $trimmedName = trim($nameParts[0]);
        $value->Name = $trimmedName;

        $parts = explode(' ', $value->DayNight);
        $no_of_days = (int) $parts[0];
        $no_of_nights = (int) $parts[2];

        if($value->DealBy == 'Dook Special'){
            $departureCheck = Departure::where([
                'destination_str' => $value->destination_str,
                'no_of_days' => $no_of_days,
                'no_of_nights' => $no_of_nights,
                'tenant_id_reff' => 'oxwtvlnriqs1634705279',
                'from' => 'Delhi',
                'dep_type' => 'main',
                'return_to' => $value->EndingAt
            ])->first();
        } else {
            $departureCheck = Departure::where([
                'destination_str' => $value->destination_str,
                'no_of_days' => $no_of_days,
                'no_of_nights' => $no_of_nights,
                'tenant_id_reff' => 'oxwtvlnriqs1634705279',
                'from' => 'Delhi',
                'dep_type' => 'main',
                'return_to' => $value->EndingAt,
                'other_name' => ''
            ])->first();
        }

        if($departureCheck) {
				  // Update existing departure
				  $departure = Departure::find($departureCheck->id);
				  $departure->from = 'Delhi';
				  $departure->return_to = $value->EndingAt;
				  $departure->departure_ownner = 'Dook Travels';
				  $departure->destination_str = $value->destination_str;
				  $departure->save();
				  $last_id = $departure->id;
				} else {
				  // Create new departure
				  $departure = new Departure();
				  $departure->from = 'Delhi';
				  $departure->return_to = $value->EndingAt;
				  $departure->departure_ownner = 'Dook Travels';
				  $departure->destination_str = $value->destination_str;
				  $departure->tenant_id_reff = 'oxwtvlnriqs1634705279';
				  $departure->dep_type = 'main';
				  $departure->save();
				  $last_id = $departure->id;
				}

				$dep_date_Check = DepartureDate::where(['departure_id' => $last_id, 'reference_id' => $value->Id])->first();
				if($dep_date_Check) {
				  // Update existing departure date
				  $departure_date = $dep_date_Check;
				  $departure_date->title = $value->Name;
				  $departure_date->date = date("Y-m-d", strtotime($value->DepartureDate));
				  $departure_date->end_date = date("Y-m-d", strtotime($value->ReturnDate));
				  $departure_date->departure_ownner = 'Dook Travels';
				  $departure_date->dc_price = $value->Price;
				  $departure_date->dc_currency = '₹';

				  if (!empty($value->FareInfo)) {
				      $d_price = $value->FareInfo[0];
				      $departure_date->sharing = $d_price->RoomShare;
				      $departure_date->flight_class = $d_price->FlightClass;
				      $departure_date->airport_transfers = $d_price->AirportTransfer;
				      $departure_date->transport_type = $d_price->Transport;
				      $departure_date->meal_type = $d_price->Meal;
				      $departure_date->group_size = $d_price->MinPax;
				      // Check if 5000 has already been added
				      if ($departure_date->price != $d_price->Price->PublishedPrice + 5000) {
				          $departure_date->price = $d_price->Price->PublishedPrice + 5000;
				      }
				  }

				  $departure_date->save();
				  $last_depdate_id = $departure_date->id;
				} else {
				  // Create new departure date
				  $departure_date = new DepartureDate();
				  $departure_date->departure_id = $last_id;
				  $departure_date->title = $value->Name;
				  $departure_date->date = date("Y-m-d", strtotime($value->DepartureDate));
				  $departure_date->end_date = date("Y-m-d", strtotime($value->ReturnDate));
				  $departure_date->departure_ownner = 'Dook Travels';
				  $departure_date->dc_price = $value->Price;
				  $departure_date->dc_currency = '₹';

				  if (!empty($value->FareInfo)) {
				      $d_price = $value->FareInfo[0];
				      $departure_date->sharing = $d_price->RoomShare;
				      $departure_date->flight_class = $d_price->FlightClass;
				      $departure_date->airport_transfers = $d_price->AirportTransfer;
				      $departure_date->transport_type = $d_price->Transport;
				      $departure_date->meal_type = $d_price->Meal;
				      $departure_date->group_size = $d_price->MinPax;
				      $departure_date->price = $d_price->Price->PublishedPrice + 5000;
				  }

				  $departure_date->save();
				  $last_depdate_id = $departure_date->id;
				}


        // if(count($value->Itinerary) > 0) {
        //     // Create or update itinerary
        //     foreach($value->Itinerary as $day_s) {
        //         $dayScheduleI = new Itinerary;
        //         $dayScheduleI->departure_id = $last_id;
        //         $dayScheduleI->day_number = $day_s->Day;
        //         $dayScheduleI->day_heading = $day_s->Title;
        //         $dayScheduleI->description = $day_s->Description;
        //         $dayScheduleI->unique_key = Str::random(10).time();
        //         $dayScheduleI->tenant_id_reff ='oxwtvlnriqs1634705279';
        //         $dayScheduleI->tenant_id = 'R2LQAAimZuB1601464567';
        //         $dayScheduleI->user_id = $tenant->id;
        //         $dayScheduleI->dep_type = "main";
        //         $dayScheduleI->save();
        //         $lastDayS = $dayScheduleI->id;
        //     }
        // }

             if(count($value->Itinerary)>0){
             $itiDate = Itinerary::where('departure_id', $last_id)->count();
             //Itinerary::where('departure_id',$last_id)->delete(); 
             if($itiDate<=0){
                 foreach($value->Itinerary as $day_s){
                    $dayScheduleI = new Itinerary;
                    $dayScheduleI->departure_id = $last_id;
                    $dayScheduleI->day_number = $day_s->Day;
                       $dayScheduleI->day_heading = $day_s->Title;
                       $dayScheduleI->description = $day_s->Description;
                       $dayScheduleI->unique_key = Str::random(10).time();
                       $dayScheduleI->tenant_id_reff ='oxwtvlnriqs1634705279';
                       $dayScheduleI->tenant_id = 'R2LQAAimZuB1601464567';
                       $dayScheduleI->user_id = $tenant->id;
                       $dayScheduleI->dep_type = "main";
                       $dayScheduleI->save();
                       $lastDayS = $dayScheduleI->id;
                       DestinationItineraryPointOfInterest::where('itinerary_id',$lastDayS)->delete();

                         // foreach ($day_s->City as $key => $destI) {


                             $data = Destination::where('dest_name',$day_s->City)->select('id')->first();

                             $itiDest  = new DestinationItineraryPointOfInterest;
                         $itiDest->departure_id = $last_id;
                         $itiDest->itinerary_id = $lastDayS;
                         if($data){
                             $itiDest->destination_id = $data->id;
                         }
                         $itiDest->save();
                         // }
                 }
             }
         }

        // Inclusions
            if(count($value->Inclusion)>0){
                Inclusion::where(['departure_id'=> $last_id, 'departure_date_id' =>$last_depdate_id])->delete();
                foreach ($value->Inclusion as $key => $inclu) {
                    $inclusion  = new Inclusion;
                    $inclusion->name = $inclu;             
                     // $inclusion->description = $inclu->description;
                    // $inclusion->icon = $inclu->icon;
                    $inclusion->departure_id = $last_id;
                    $inclusion->departure_date_id = $last_depdate_id;
                    $inclusion->dep_type = '1';
                     $inclusion->tenant_id_reff = 'oxwtvlnriqs1634705279';
                    $inclusion->tenant_id = 'R2LQAAimZuB1601464567';
                    $inclusion->user_id = $tenant->id;
                    $inclusion->save();
                }
            }  

    $count = count($arr);            
    $status = [
        'status' => $count . ' Departures synced successfully.',
    ];
    return response()->json($status);  
}
}


    public function getDepartureAjax(Request $request){

		$tenant = auth()->user();
		$post = array(
			'text' => $request->DCPull
		);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'https://www.departurecloud.com/api/get_dc_departures_to_dook');
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
		$response = curl_exec($curl);
		curl_close ($curl);
        $jsons = json_decode($response); 
        // echo "<pre>";
        // print_r($jsons);
        // die;
       	foreach($jsons->data as $key => $value)
       	{

       		$last_dook_reff_id = Departure::where('dep_type','main')
       				->orderBy('dep_dook_ref_id','DESC')->first();
       		$last_dook_ref_id = (int) $last_dook_reff_id->dep_dook_ref_id;

			if($value->other_name == ''){
				$departureCheck = Departure::where(['destination_str'=>$value->destination_str, 'no_of_days'=>$value->no_of_days, 'no_of_nights'=>$value->no_of_nights, 'tenant_id_reff'=>$value->tenant_id,'from'=>$value->from,'dep_type'=>'main','return_to'=>$value->return_to])->first();
			}else{
				$departureCheck = Departure::where(['destination_str'=>$value->destination_str, 'no_of_days'=>$value->no_of_days, 'no_of_nights'=>$value->no_of_nights, 'tenant_id_reff'=>$value->tenant_id,'from'=>$value->from,'dep_type'=>'main','return_to'=>$value->return_to,'other_name'=>$value->other_name])->first();
			}
       	
	        if($departureCheck)
	        {
				if($value->other_name == ''){
					$dep_id = Departure::where(['destination_str'=>$value->destination_str, 'no_of_days'=>$value->no_of_days, 'no_of_nights'=>$value->no_of_nights,'tenant_id_reff'=>$value->tenant_id,'from'=>$value->from,'dep_type'=>'main','return_to'=>$value->return_to])->value('id');
				}else{
					$dep_id = Departure::where(['destination_str'=>$value->destination_str, 'no_of_days'=>$value->no_of_days, 'no_of_nights'=>$value->no_of_nights,'tenant_id_reff'=>$value->tenant_id,'from'=>$value->from,'dep_type'=>'main','return_to'=>$value->return_to,'other_name'=>$value->other_name])->value('id');
				}
	        	

		        $departure = Departure::find($dep_id);
		        //$departure->title = $value->title;
		        $departure->from = $value->from;
		        $departure->return_to = $value->return_to;
		        $departure->ending_at = $value->ending_at;
		        //$departure->tenant_id_reff = $value->tenant_id;
		        //$departure->tenant_id = $tenant->tenant_id;
		        //$departure->user_id = $tenant->id;
		        $departure->save();
		        $last_id = $departure->id;
		        $last_dook_id = $departure->dep_dook_ref_id;
		    }
		    else{
		    	$departure = new Departure;
		    	$usword = ucwords($value->url_pre_dest);
		    	$titleName = ($value->other_name)?$value->title.' ('.$value->other_name.')':$value->title; //modify by Anirudh
		    	//$usword.' '.$value->no_of_nights.' Nights Tour';
		        $departure->title = $titleName;
		        $departure->from = $value->from;
		        $departure->return_to = $value->return_to;
		        $departure->ending_at = $value->ending_at;
		        $departure->no_of_days = $value->no_of_days;
		        $departure->no_of_nights = $value->no_of_nights;
		        $departure->tenant_id_reff = $value->tenant_id;
		        $departure->tenant_id = $tenant->tenant_id;
		        $departure->user_id = $tenant->id;
		        $departure->dep_type = "main";
		        $departure->departure_ownner = $value->departure_ownner;
		        $departure->destination_str = $value->destination_str;
		        $departure->description = $value->description;
		        $departure->dc_departure_type = $value->departure_type;
		        if($value->tenant_id == 'oxwtvlnriqs1634705279'){
		        	$departure->owener_type = "dook";
		        }else{
		        	$departure->owener_type = "other";
		        }
		        $departure->status = 0;
		        $departure->book_online = 0;
		        $departure->slug_url_pre = "group-tours";
		        $departure->slug_url = $value->url_pre_dest.'-'.$value->no_of_nights.'-nights-package-'.$value->dep_id;
		        if($last_dook_ref_id){
		        	$departure->dep_dook_ref_id = $last_dook_ref_id +1;
		        }else{
		        	$departure->dep_dook_ref_id = '1000001';
		        }
		        $departure->other_name = $value->other_name;
		        $departure->save();
		        $last_id = $departure->id;
		        $last_dook_id = $departure->dep_dook_ref_id;
		    }

		    $dep_date_Check = DepartureDate::where(['departure_id'=> $last_id, 'unique_key'=> $value->unique_key, 'reference_id'=> $value->id])->first();
		    if($dep_date_Check){
		    	$dep_date_id = DepartureDate::where(['departure_id'=> $last_id, 'unique_key'=> $value->unique_key, 'reference_id'=> $value->id])->value('id');
		    	
		    	$departure_date = DepartureDate::find($dep_date_id);
		        $departure_date->title = $value->title;
		        $departure_date->reference_id = $value->id;
		        $departure_date->dep_id = $value->dep_id;
		        $departure_date->date = $value->start_date;
		        $departure_date->end_date = $value->end_date;
		        //$departure_date->description = $value->description;
		        $departure_date->departure_ownner = $value->departure_ownner;
		        //$departure_date->termspayment = $value->termspayment;
		        
		        $departure_date->dep_type = $value->dep_type;
		        $departure_date->dook_id = $last_dook_id;
		        $departure_date->dc_price = $value->departure_price;
		        $departure_date->dc_currency = $value->departure_cs;
		        $departure_date->save();
		        $last_depdate_id = $departure_date->id;
		        $last_uni_key = $departure_date->unique_key;
		    }
		    else{
		    	$departure_date = new DepartureDate;
		        $departure_date->title = $value->title;
		        $departure_date->reference_id = $value->id;
		        $departure_date->departure_id = $last_id;
		        $departure_date->dep_id = $value->dep_id;
		        $departure_date->date = $value->start_date;
		        $departure_date->end_date = $value->end_date;
		        $departure_date->description = $value->description;
		        $departure_date->departure_ownner = $value->departure_ownner;
		        $departure_date->termspayment = $value->termspayment;
		        $departure_date->unique_key = $value->unique_key;
		        $departure_date->tenant_id_reff = $value->tenant_id;
		        $departure_date->tenant_id = $tenant->tenant_id;
		        $departure_date->user_id = $tenant->id;
		        $departure_date->dep_type = $value->dep_type;
		        $departure_date->dook_id = $last_dook_id;
		        $departure_date->dc_price = $value->departure_price;
		        $departure_date->dc_currency = $value->departure_cs;
		        dd($value->pricingFirst);
		        if($value->pricingFirst){
			        $departure_date->sharing = $value->pricingFirst->sharing;
					$departure_date->flight_class = $value->pricingFirst->flight_class;
					$departure_date->age_bracket = $value->pricingFirst->passenger;
					$departure_date->airport_transfers =$value->pricingFirst->airport_transfers;
					$departure_date->transport_type = $value->pricingFirst->transport_type;
					$departure_date->hotel_type = $value->pricingFirst->hotel_type; 
					$departure_date->meal_type = $value->pricingFirst->meal_type;
					$departure_date->group_size = $value->pricingFirst->group_size;
					$departure_date->other = $value->pricingFirst->other;
				}
		        $departure_date->save();

		        $last_depdate_id = $departure_date->id;
		        $last_uni_key = $departure_date->unique_key;
		    }
		    if(count($value->departure_pricing)){
		    	DeparturePrice::where(['departure_id'=> $last_id, 'd_date_id'=> $last_depdate_id])->delete();
		    	foreach($value->departure_pricing as $d_price){
		    	   $price = new DeparturePrice;
		    	   $price->d_date_id = $last_depdate_id;
			       $price->departure_id = $last_id;
			       $price->unique_key = $last_uni_key;
                   $price->sharing = $d_price->sharing;
                   $price->flight_class = $d_price->flight_class;
                   $price->age_bracket = $d_price->passenger;
                   $price->airport_transfers =$d_price->airport_transfers;
                   $price->transport_type = $d_price->transport_type;
                   $price->hotel_type = $d_price->hotel_type; 
                   $price->meal_type = $d_price->meal_type;
                   $price->group_size = $d_price->group_size;
                   $price->other = $d_price->other;
                   $price->price = $d_price->price;
                   $price->currency_code = $d_price->currency_code;
                   $price->currency_symbol = $d_price->currency_symbol;
                   $price->save();
		    	}
		    }
		    if(count($value->origin_flights)>0){
		    	$i = 1;
			    OriginatingFlight::where(['departure_id'=> $last_id, 'd_date_id'=> $last_depdate_id])->delete();
			    foreach ($value->origin_flights as $key => $value_o) {
			    	$origin_flights = new OriginatingFlight;
			        $origin_flights->d_date_id = $last_depdate_id;
			        $origin_flights->departure_id = $last_id;
			        $origin_flights->unique_key = $last_uni_key;
			        $origin_flights->airline_code = $value_o->code;
			        $origin_flights->flight = $value_o->flight_name;
			        $origin_flights->origin_flight_no = $value_o->flight_no;
			        $origin_flights->origin_flight_date = $value_o->flight_date;
			        $origin_flights->origin_flight_dep_time = $value_o->flight_dep_time;
			        $origin_flights->origin_flight_arrival_time = $value_o->flight_arrival_time;
			        $origin_flights->origin_flight_dep_airport = $value_o->flight_dep_airport;
			        $origin_flights->origin_flight_arriving_airport =$value_o->flight_arrival_airport;
			        $origin_flights->baggage = $value_o->baggage;
			        $origin_flights->termination = $i;
			        $origin_flights->save();
			        $i++;
			    }
			}
			if(count($value->return_flights)>0){
				$j = 1;
			    ReturningFlight::where(['departure_id'=> $last_id, 'd_date_id'=> $last_depdate_id])->delete();
			    foreach ($value->return_flights as $key => $value_r) {
			    	$return_flights = new ReturningFlight;
			    	$return_flights->d_date_id = $last_depdate_id;
			        $return_flights->departure_id = $last_id;
			        $return_flights->unique_key = $last_uni_key;
			        $return_flights->airline_code = $value_o->code;
			        $return_flights->return_flight = $value_r->flight_name;
			        $return_flights->return_flight_no = $value_r->flight_no;
			        $return_flights->return_flight_date = $value_r->flight_date;
			        $return_flights->return_flight_dep_time = $value_r->flight_dep_time;
			        $return_flights->return_flight_arrival_time = $value_r->flight_arrival_time;
			        $return_flights->return_flight_dep_airport = $value_r->flight_dep_airport;
			        $return_flights->return_flight_arriving_airport = $value_r->flight_arrival_airport;
			        $return_flights->baggage = $value_r->baggage_arriving;
			        $return_flights->termination = $j;
			        $return_flights->save();
			        $j++;
			    }
		    }
		    	
		    // Itineraries Pdf
            if($value->itinerary_pdf || $value->itinerary_url)
            {
            	$itinerary_pdf  = DepartureDate::find($last_depdate_id);
				$itinerary_pdf->itinerary_pdf = $value->itinerary_pdf;
				$itinerary_pdf->itinerary_title = $value->itinerary_url;
				$itinerary_pdf->save();
			}

			if(count($value->day_schedule)>0){
		    	Itinerary::where('departure_id',$last_id)->delete(); 
		    	foreach($value->day_schedule as $day_s){
		    	   $dayScheduleI = new Itinerary;
			       $dayScheduleI->departure_id = $last_id;
			       $dayScheduleI->day_number = $day_s->day_number;
                   $dayScheduleI->day_heading = $day_s->day_heading;
                   $dayScheduleI->description = $day_s->description;
                   $dayScheduleI->unique_key = Str::random(10).time();
                   $dayScheduleI->tenant_id_reff =$day_s->tenant_id;
                   $dayScheduleI->tenant_id = $tenant->tenant_id;
                   $dayScheduleI->user_id = $tenant->id;
                   $dayScheduleI->dep_type = "main";
                   $dayScheduleI->save();
                   $lastDayS = $dayScheduleI->id;
                   DestinationItineraryPointOfInterest::where('itinerary_id',$lastDayS)->delete(); 
                   	foreach ($day_s->destinations as $key => $destI) {
                   		$data = Destination::where('dest_name',$destI->dest_name)->orWhere('reference_id',$destI->reference_id)
                   			->select('id')->first();

                   	  	$itiDest  = new DestinationItineraryPointOfInterest;
                    	$itiDest->departure_id = $last_id;
                    	$itiDest->itinerary_id = $lastDayS;
                    	if($data){
                    		$itiDest->destination_id = $data->id;
                    	}
                    	$itiDest->save();
                   	}
		    	}
		    }
	        
		    // Inclusions
		    if(count($value->inclusions)>0){
			    Inclusion::where(['departure_id'=> $last_id, 'departure_date_id' =>$last_depdate_id])->delete();
		    	foreach ($value->inclusions as $key => $inclu) {
	            	$inclusion  = new Inclusion;
					$inclusion->name = $inclu->name;
					$inclusion->description = $inclu->description;
					$inclusion->icon = $inclu->icon;
					$inclusion->departure_id = $last_id;
					$inclusion->departure_date_id = $last_depdate_id;
					$inclusion->dep_type = $value->dep_type;
					$inclusion->tenant_id_reff = $value->tenant_id;
			        $inclusion->tenant_id = $tenant->tenant_id;
			        $inclusion->user_id = $tenant->id;
					$inclusion->save();
				}
	        }

	    	// Country Destination Added
	        
        	$cloude_dep_id = DepartureCloudDestination::where('departure_id',$last_id)->delete();
	    	foreach ($value->destinations as $key => $destination) {
				$destinationss  = new DepartureCloudDestination;
				$destinationss->departure_reference_id = $value->id;
				$destinationss->name = $destination->dest_name;
				$destinationss->country_name = $destination->country_name;
				$destinationss->destination_reference_id = $destination->dest_id;
				$destinationss->departure_id = $last_id;
				$destinationss->save();
	    	} 
	    }                
	    $status = [
	        'status'=> 'Departners pulled successfully.',
	    ];
	    return response()->json($status);  
	}

	public function syncDepartureAjax(Request $request){
		$tenant = auth()->user();
		$post = array(
			'text' => $request->Sync
		);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'https://www.departurecloud.com/api/get_dc_sync_departures_to_dook');
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
		$response = curl_exec($curl);
		curl_close ($curl);
        $jsons = json_decode($response); 
        // echo "<pre>";
        // print_r($jsons);
        // die;
        $arr = [];
       	foreach($jsons->data as $key => $value)
       	{

			if($value->other_name == ''){
				$departureCheck = Departure::where(['destination_str'=>$value->destination_str, 'no_of_days'=>$value->no_of_days, 'no_of_nights'=>$value->no_of_nights, 'tenant_id_reff'=>$value->tenant_id,'from'=>$value->from,'dep_type'=>'main','return_to'=>$value->return_to])->first();
			}else{
				$departureCheck = Departure::where(['destination_str'=>$value->destination_str, 'no_of_days'=>$value->no_of_days, 'no_of_nights'=>$value->no_of_nights, 'tenant_id_reff'=>$value->tenant_id,'from'=>$value->from,'dep_type'=>'main','return_to'=>$value->return_to,'other_name'=>$value->other_name])->first();
			}
       		
			
	        if($departureCheck)
	        {
				if($value->other_name == ''){
					$dep_id = Departure::where(['destination_str'=>$value->destination_str, 'no_of_days'=>$value->no_of_days, 'no_of_nights'=>$value->no_of_nights,'tenant_id_reff'=>$value->tenant_id,'from'=>$value->from,'dep_type'=>'main','return_to'=>$value->return_to])->value('id');
				}else{
					$dep_id = Departure::where(['destination_str'=>$value->destination_str, 'no_of_days'=>$value->no_of_days, 'no_of_nights'=>$value->no_of_nights,'tenant_id_reff'=>$value->tenant_id,'from'=>$value->from,'dep_type'=>'main','return_to'=>$value->return_to,'other_name'=>$value->other_name])->value('id');
				}
	        

		        $departure = Departure::find($dep_id);
		        //$departure->title = $value->title;
		        $departure->from = $value->from;
		        $departure->ending_at = $value->ending_at;
		        $departure->return_to = $value->return_to;
		        $departure->departure_ownner = $value->departure_ownner;
		        $departure->destination_str = $value->destination_str;
		        $departure->save();
		        $last_id = $departure->id;
		        $last_dook_id = $departure->dep_dook_ref_id;
		    }

		    $dep_date_Check = DepartureDate::where(['departure_id'=> $last_id, 'unique_key'=> $value->unique_key, 'reference_id'=> $value->id])->first();
		    if($dep_date_Check){
		    	$dep_date_id = DepartureDate::where(['departure_id'=> $last_id, 'unique_key'=> $value->unique_key, 'reference_id'=> $value->id])->value('id');
		    	
		    	$departure_date = DepartureDate::find($dep_date_id);
		        $departure_date->title = $value->title;
		        $departure_date->date = $value->start_date;
		        $departure_date->end_date = $value->end_date;
		        //$departure_date->description = $value->description;
		        $departure_date->departure_ownner = $value->departure_ownner;
		        //$departure_date->termspayment = $value->termspayment;
		        $departure_date->dc_price = $value->departure_price;
		        $departure_date->dc_currency = $value->departure_cs;
		        $departure_date->save();
		        $last_depdate_id = $departure_date->id;
		        $last_uni_key = $departure_date->unique_key;
		    }

		    if(count($value->departure_pricing)){
		    	DeparturePrice::where(['departure_id'=> $last_id, 'd_date_id'=> $last_depdate_id])->delete();
		    	foreach($value->departure_pricing as $d_price){
		    	   $price = new DeparturePrice;
		    	   $price->d_date_id = $last_depdate_id;
			       $price->departure_id = $last_id;
			       $price->unique_key = $last_uni_key;
                   $price->sharing = $d_price->sharing;
                   $price->flight_class = $d_price->flight_class;
                   $price->age_bracket = $d_price->passenger;
                   $price->airport_transfers =$d_price->airport_transfers;
                   $price->transport_type = $d_price->transport_type;
                   $price->hotel_type = $d_price->hotel_type; 
                   $price->meal_type = $d_price->meal_type;
                   $price->group_size = $d_price->group_size;
                   $price->other = $d_price->other;
                   $price->price = $d_price->price;
                   $price->currency_code = $d_price->currency_code;
                   $price->currency_symbol = $d_price->currency_symbol;
                   $price->save();
		    	}
		    }
		    if(count($value->origin_flights)>0){
		    	$i = 1;
			    OriginatingFlight::where(['departure_id'=> $last_id, 'd_date_id'=> $last_depdate_id])->delete();
			    foreach ($value->origin_flights as $key => $value_o) {
			    	$origin_flights = new OriginatingFlight;
			        $origin_flights->d_date_id = $last_depdate_id;
			        $origin_flights->departure_id = $last_id;
			        $origin_flights->unique_key = $last_uni_key;
			        $origin_flights->airline_code = $value_o->code;
			        $origin_flights->flight = $value_o->flight_name;
			        $origin_flights->origin_flight_no = $value_o->flight_no;
			        $origin_flights->origin_flight_date = $value_o->flight_date;
			        $origin_flights->origin_flight_dep_time = $value_o->flight_dep_time;
			        $origin_flights->origin_flight_arrival_time = $value_o->flight_arrival_time;
			        $origin_flights->origin_flight_dep_airport = $value_o->flight_dep_airport;
			        $origin_flights->origin_flight_arriving_airport =$value_o->flight_arrival_airport;
			        $origin_flights->baggage = $value_o->baggage;
			        $origin_flights->termination = $i;
			        $origin_flights->save();
			        $i++;
			    }
			}
			if(count($value->return_flights)>0){
				$j = 1;
			    ReturningFlight::where(['departure_id'=> $last_id, 'd_date_id'=> $last_depdate_id])->delete();
			    foreach ($value->return_flights as $key => $value_r) {
			    	$return_flights = new ReturningFlight;
			    	$return_flights->d_date_id = $last_depdate_id;
			        $return_flights->departure_id = $last_id;
			        $return_flights->unique_key = $last_uni_key;
			        $return_flights->airline_code = $value_o->code;
			        $return_flights->return_flight = $value_r->flight_name;
			        $return_flights->return_flight_no = $value_r->flight_no;
			        $return_flights->return_flight_date = $value_r->flight_date;
			        $return_flights->return_flight_dep_time = $value_r->flight_dep_time;
			        $return_flights->return_flight_arrival_time = $value_r->flight_arrival_time;
			        $return_flights->return_flight_dep_airport = $value_r->flight_dep_airport;
			        $return_flights->return_flight_arriving_airport = $value_r->flight_arrival_airport;
			        $return_flights->baggage = $value_r->baggage_arriving;
			        $return_flights->termination = $j;
			        $return_flights->save();
			        $j++;
			    }
		    }
		    	
		    // Itineraries Pdf
            if($value->itinerary_pdf || $value->itinerary_url)
            {
            	$itinerary_pdf  = DepartureDate::find($last_depdate_id);
				$itinerary_pdf->itinerary_pdf = $value->itinerary_pdf;
				$itinerary_pdf->itinerary_title = $value->itinerary_url;
				$itinerary_pdf->save();
			}
	        if(count($value->day_schedule)>0){
		    	$itiDate = Itinerary::where('departure_id', $last_id)->count();
		    	//Itinerary::where('departure_id',$last_id)->delete(); 
		    	if($itiDate<=0){
			    	foreach($value->day_schedule as $day_s){
			    	   $dayScheduleI = new Itinerary;
				       $dayScheduleI->departure_id = $last_id;
				       $dayScheduleI->day_number = $day_s->day_number;
	                   $dayScheduleI->day_heading = $day_s->day_heading;
	                   $dayScheduleI->description = $day_s->description;
	                   $dayScheduleI->unique_key = Str::random(10).time();
	                   $dayScheduleI->tenant_id_reff =$day_s->tenant_id;
	                   $dayScheduleI->tenant_id = $tenant->tenant_id;
	                   $dayScheduleI->user_id = $tenant->id;
	                   $dayScheduleI->dep_type = "main";
	                   $dayScheduleI->save();
	                   $lastDayS = $dayScheduleI->id;
	                   DestinationItineraryPointOfInterest::where('itinerary_id',$lastDayS)->delete();
	                   	foreach ($day_s->destinations as $key => $destI) {
	                   		$data = Destination::where('dest_name',$destI->dest_name)->orWhere('reference_id',$destI->reference_id)
	                   			->select('id')->first();

	                   	  	$itiDest  = new DestinationItineraryPointOfInterest;
	                    	$itiDest->departure_id = $last_id;
	                    	$itiDest->itinerary_id = $lastDayS;
	                    	if($data){
	                    		$itiDest->destination_id = $data->id;
	                    	}
	                    	$itiDest->save();
	                   	}
			    	}
			    }
		    }
		    // Inclusions
		    if(count($value->inclusions)>0){
			    Inclusion::where(['departure_id'=> $last_id, 'departure_date_id' =>$last_depdate_id])->delete();
		    	foreach ($value->inclusions as $key => $inclu) {
	            	$inclusion  = new Inclusion;
					$inclusion->name = $inclu->name;
					$inclusion->description = $inclu->description;
					$inclusion->icon = $inclu->icon;
					$inclusion->departure_id = $last_id;
					$inclusion->departure_date_id = $last_depdate_id;
					$inclusion->dep_type = $value->dep_type;
					$inclusion->tenant_id_reff = $value->tenant_id;
			        $inclusion->tenant_id = $tenant->tenant_id;
			        $inclusion->user_id = $tenant->id;
					$inclusion->save();
				}
	        } 
	        array_push($arr,$key);
	    }  
	    $count = count($arr);            
	    $status = [
	        'status'=> $count.' Departners synced successfully.',
	    ];
	    return response()->json($status);  
	}
}
