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
use App\DepartureDate;
use App\OriginatingFlight;
use App\ReturningFlight;
use App\RsDollor;

class DepartureDateController extends Controller
{
    public function index(Request $request){
    	$route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $dep_main = Departure::where('id', $route_id)
                ->select('no_of_nights','no_of_days','dep_dook_ref_id','title')
                ->first();
    	$departures = DepartureDate::where('departure_id', $route_id)
                    ->orderBy('date', 'ASC')
                    ->paginate(30);
        foreach ($departures as $key => $value) {
        	$day_night = Departure::where('id', $route_id)
        				->select('no_of_nights','no_of_days','dep_dook_ref_id')
        				->first();
        	$value->nights = $day_night->no_of_nights;
        	$value->days = $day_night->no_of_days;
        	$value->dook_id = $day_night->dep_dook_ref_id;

            $post = array(
                'departure_id' => $value->reference_id,
                'unique_key' => $value->unique_key
            );

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://departurecloud.com/api/get_departure_seats');
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
            $response = curl_exec($curl);
            $departure_seat = json_decode($response);
            //dd($departure_seat);
            if($departure_seat != '' || $departure_seat != null){
                $value->total_seat = $departure_seat->manage_seat;
                if($departure_seat->available_seat == null){
                    $value->available_seat = $departure_seat->manage_seat;
                }
                else{
                    $value->available_seat = $departure_seat->available_seat;
                }
                //$value->price_inr = $departure_seat->price_inr;
                //$value->price_usd = $departure_seat->price_usd;
                //$value->single_sp_inr = $departure_seat->single_sup_price_inr;
                //$value->single_sp_usd = $departure_seat->single_sup_price_usd;
            }
            else{
                //dd('kk');
                // $value->total_seat = '###';
                $value->available_seat = '###';
                $value->price_inr = '###';
                $value->price_usd = '###';
                $value->single_sp_inr = '###';
                $value->single_sp_usd = '###';
            }
        }

        $total = count($departures);
        return view('departure.departure_date_index',compact('departures','total','dep_main'));
    }

    public function edit(Request $request){
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        //date id
        $date_route_ids = $request->route('date_id'); 
        $date_route_id = (int)$date_route_ids;
        $departure = Departure::where('id', $route_id)
                    ->first();
        $departureDate = DepartureDate::where('id', $date_route_id)
                    ->first();
        
        $indian_currency=RsDollor::first();
        $inr = $indian_currency->inr;
        $columns = DB::table('departure_column_types')->where('departure_type_id', $departure->dc_departure_type)->get()->pluck('column_name_id');
        $columns = json_encode($columns);
        return view('departure.departure_date_edit',compact('departure','inr','departureDate','columns'));
    }
    public function update(Request $request, $id){
        //dd($id);
        $user = auth()->user(); 
        $get_departure_id = DepartureDate::where('id', $id)
                        ->select('departure_id','unique_key')
                        ->first();
        $get_dook_id = Departure::where('id',$get_departure_id->departure_id)
                    ->value('dep_dook_ref_id');

        $departure_date = DepartureDate::find($id);
        $departure_date->price = $request->price_inr;
        $departure_date->price_usd = $request->price_usd;
        $departure_date->price_currency = $request->price_inr_currency;
        $departure_date->price_currency_usd = $request->price_usd_currency;
        $departure_date->description = $request->edit_description;

        $departure_date->sharing = $request->room_sharing;
        $departure_date->flight_class = $request->flight_class;
        $departure_date->age_bracket = $request->age_bracket;
        $departure_date->hotel_type = $request->hotel_type;
        $departure_date->transport_type = $request->transport_type;

        $departure_date->airport_transfers = $request->airport_transfers;
        $departure_date->meal_type = $request->meal_type;

        $departure_date->save();
        $last_id = $departure_date->id;

        $status = [
            'url'=> url('/departure_dates',$get_departure_id->departure_id),
        ];
        return response()->json($status);
    }

    //For Group

    public function groupIndex(Request $request){
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $departures = DepartureDate::where('departure_id', $route_id)
                    ->orderBy('date', 'ASC')
                    ->paginate(30);
        foreach ($departures as $key => $value) {
            $day_night = Departure::where('id', $route_id)
                        ->select('no_of_nights','no_of_days','dep_dook_ref_id')
                        ->first();
            $value->nights = $day_night->no_of_nights;
            $value->days = $day_night->no_of_days;
            $value->dook_id = $day_night->dep_dook_ref_id;
            $value->price_inr = $value->price;
            $value->price_usd = $value->price_usd;
        }

        $total = count($departures);
        return view('grouppackages.departure_date_index',compact('departures','total'));
    }

    public function groupCreate(Request $request, $id){
        $get_departure = Departure::where('id', $request->create_id)
                        ->select('title','dep_dook_ref_id')
                        ->first();
        $date_formate = $request->create_date;
        $date = date("Y-m-d", strtotime($date_formate));
        $user = auth()->user();

        $departure_date = new DepartureDate;
        $departure_date->title = $get_departure->title;
        $departure_date->price = $request->create_price_inr;
        $departure_date->price_usd = $request->create_price_usd;
        $departure_date->departure_id = $request->create_id;
        $departure_date->dep_id = $get_departure->dep_dook_ref_id;
        $departure_date->dook_id = $get_departure->dep_dook_ref_id;
        $departure_date->date = $date;
        
        $departure_date->tenant_id = $user->tenant_id;
        $departure_date->user_id = $user->id;
        $departure_date->description = $request->create_description;
        $departure_date->dep_type = "group";
        $departure_date->unique_key = Str::random(10).time();
        $departure_date->save();
        
        $status = [
            'message'=> 'Success.',
        ];
        return response()->json($status);
    }

    public function groupUpdate(Request $request, $id){
        //dd($id);
        $get_departure_id = DepartureDate::where('id', $id)->value('departure_id');
        $get_dook_id = Departure::where('id',$get_departure_id)->value('dep_dook_ref_id');

        $oDateFormat = $request->edit_date;
        $date = date("Y-m-d", strtotime($oDateFormat));
        $rDateFormat = $request->edit_rdep_date;
        $r_date = date("Y-m-d", strtotime($rDateFormat));
        $user = auth()->user(); 

        $departure_date = DepartureDate::find($id); 
        $departure_date->price = $request->edit_price_inr;
        $departure_date->price_usd = $request->edit_price_usd;
        $departure_date->date = $date;
        $departure_date->dook_id = $get_dook_id;
        $departure_date->description = $request->edit_description;
        $departure_date->save();
        $status = [
            'message'=> 'Success.',
        ];
        return response()->json($status);
    }

    public function departureDateTermsIndex(Request $request)
    {

        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        //date id
        $date_route_ids = $request->route('date_id'); 
        $date_route_id = (int)$date_route_ids;
        $dep_main = Departure::where('id', $route_id)
                ->select('no_of_nights','no_of_days','dep_dook_ref_id','title')
                ->first();
        $terms = DB::table('departure_dates')
                    ->where('id', $date_route_id)
                    ->select('id','termspayment')
                    ->first();

        return view('departure.terms_payment',compact('terms','dep_main'));       
    }

    public function departureDateTermsUpdate(Request $request)
    {
        $route_ids = $request->route('date_id'); 
        $route_id = (int)$route_ids;
        $user = auth()->user(); 
        $date_terms = DepartureDate::find($route_id);
        $date_terms->termspayment= $request->terms_payment;
        $date_terms->save();
        $status = [
            'url'=> url('/departure_dates',$request->departure_id),
        ];
        return response()->json($status);  
    }

}
