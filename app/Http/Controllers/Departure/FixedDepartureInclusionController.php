<?php

namespace App\Http\Controllers\Departure;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Departure;
use App\Destination;
use App\DepartureDestination;
use App\Inclusion;

class FixedDepartureInclusionController extends Controller
{
    public function departureInclusionIndex(Request $request)
    {
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $inclusions = DB::table('inclusion_masters')->select('name','description')->get();
        $inclusione = Inclusion::where(["departure_id" => $route_id, "dep_type" => "main"])
                    ->select('icon_inclusion_id','name','description')
                    ->get();

        $array_1 = json_decode(json_encode($inclusions, true),true);
        $array_2 = json_decode(json_encode($inclusione, true),true);
        $array = array_merge($array_1, $array_2);

        //$inclusionedits = array_map("unserialize", array_unique(array_map("serialize", $array)));
        foreach($array as $element) {
            $hash = $element['name'];
            $inclusionedit[$hash] = $element;
        }
        foreach ($inclusionedit as $value) {
            $arr[] = $value;
        }
        $inclusionedits = $arr;
        //dd($inclusionedits);
        $inclusion_icons = DB::table('icon_inclusions')->select('id','name','icon')->get();
        if(count($inclusione) > 0){
            return view('departure.inclusion_edit_old',compact('inclusionedits','inclusione','inclusion_icons'));
        }
        else{
            $inclusion_masters = DB::table('inclusion_masters')->get();
            return view('departure.inclusion_create_old',compact('inclusion_masters','inclusion_icons'));
        }       
    }

    public function departureStoreInclusion(Request $request)
    {
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $user = auth()->user(); 
        $array_check = array();
        for($i = 0; $i < count($request->name); $i++) {
            if ($request->name[$i] != '') {
                array_push($array_check, $request->name[$i]);
            }
        }
        if(count($array_check) > 0){ 
            foreach ($request->names as $key => $value) {
                $n = 1; 
                $start = strlen($value) - $n;
                $str1 = ''; 
                for ($x = $start; $x < strlen($value); $x++) { 
                    $str1 .= $value[$x]; 
                }
                $inc  = Inclusion::where('name',$value)
                        ->where('departure_id',$route_id)
                        ->first();
                if(is_null($inc)){
                    $inclusion  = new Inclusion;
                    $incName = substr($value, 0, strlen($value)-1);
                    $inclusion->name = $incName; 
                    if($request->descriptions[$str1] != ''){   
                        $inclusion->description = $request->descriptions[$str1];
                    }
                    else{
                        $inclusion->description = '';
                    }
                    $inclusion->departure_id = $route_id;
                    $inclusion->dep_type = "main";
                    $inclusion->tenant_id = $user->tenant_id;
                    $inclusion->user_id = $user->id;
                    $inclusion->save();
                }
            }
            foreach ($array_check as $key => $ext_name) {
                $inc  = Inclusion::where('name',$ext_name)
                        ->where('departure_id',$route_id)
                        ->first();
                if(is_null($inc)){
                    $inclusion  = new Inclusion;
                    $inclusion->name = $ext_name;   
                    if($request->description[$key] != ''){   
                        $inclusion->description = $request->description[$key];
                    }
                    else{
                        $inclusion->description = '';
                    }
                    $inclusion->departure_id = $route_id;
                    $inclusion->dep_type = "main"; 
                    $inclusion->tenant_id = $user->tenant_id;
                    $inclusion->user_id = $user->id;
                    $inclusion->save();
                }
            } 
        }
        else{
            foreach ($request->names as $key => $value) {
                $n = 1; 
                $start = strlen($value) - $n;
                $str1 = ''; 
                for ($x = $start; $x < strlen($value); $x++) { 
                    $str1 .= $value[$x]; 
                }

                $inc  = Inclusion::where('name',$value)
                        ->where('departure_id',$route_id)
                        ->first();
                if(is_null($inc)){
                    $inclusion  = new Inclusion;
                    $incName = substr($value, 0, strlen($value)-1);
                    $inclusion->name = $incName; 
                    if($request->descriptions[$str1] != ''){   
                        $inclusion->description = $request->descriptions[$str1];
                    }
                    else{
                        $inclusion->description = '';
                    }
                    $inclusion->departure_id = $route_id;
                    $inclusion->dep_type = "main";
                    $inclusion->tenant_id = $user->tenant_id;
                    $inclusion->user_id = $user->id;
                    $inclusion->save();
                }
            }
        }
        $status = [
                'url'=> url('/departure/poi',$route_id),
            ];
        return response()->json($status);  
    }

    public function departureUpdateInclusion(Request $request)
    {
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $user = auth()->user(); 
        $array_check = array();
        for($i = 0; $i < count($request->name); $i++) {
            if ($request->name[$i] != '') {
                array_push($array_check, $request->name[$i]);
            }
        }
        if(count($array_check) > 0){ 
            Inclusion::where('departure_id', $route_id)->delete();
            foreach ($request->names as $key => $value) {
                $n = 1; 
                $start = strlen($value) - $n;
                $str1 = ''; 
                for ($x = $start; $x < strlen($value); $x++) { 
                    $str1 .= $value[$x]; 
                }
                $inc  = Inclusion::where('name',$value)
                        ->where('departure_id',$route_id)
                        ->first();
                if(is_null($inc)){
                    $inclusion  = new Inclusion;
                    $incName = substr($value, 0, strlen($value)-1);
                    $inclusion->name = $incName; 
                    if($request->descriptions[$str1] != ''){   
                        $inclusion->description = $request->descriptions[$str1];
                    }
                    else{
                        $inclusion->description = '';
                    }
                    $inclusion->departure_id = $route_id;
                    $inclusion->dep_type = "main";
                    $inclusion->tenant_id = $user->tenant_id;
                    $inclusion->user_id = $user->id;
                    $inclusion->save();
                }
            }
            foreach ($array_check as $key => $ext_name) {
                $inc  = Inclusion::where('name',$ext_name)
                        ->where('departure_id',$route_id)
                        ->first();
                if(is_null($inc)){
                    $inclusion  = new Inclusion;
                    $inclusion->name = $ext_name;   
                    if($request->description[$key] != ''){   
                        $inclusion->description = $request->description[$key];
                    }
                    else{
                        $inclusion->description = '';
                    }
                    $inclusion->departure_id = $route_id; 
                    $inclusion->dep_type = "main";
                    $inclusion->tenant_id = $user->tenant_id;
                    $inclusion->user_id = $user->id;
                    $inclusion->save();
                }
            } 
        }
        else{
            Inclusion::where('departure_id', $route_id)->delete();
            foreach ($request->names as $key => $value) {
                $n = 1; 
                $start = strlen($value) - $n;
                $str1 = ''; 
                for ($x = $start; $x < strlen($value); $x++) { 
                    $str1 .= $value[$x]; 
                }
                $inc  = Inclusion::where('name',$value)
                        ->where('departure_id',$route_id)
                        ->first();
                if(is_null($inc)){
                    $inclusion  = new Inclusion;
                    $incName = substr($value, 0, strlen($value)-1);
                    $inclusion->name = $incName;  
                    if($request->descriptions[$str1] != ''){   
                        $inclusion->description = $request->descriptions[$str1];
                    }
                    else{
                        $inclusion->description = '';
                    }
                    $inclusion->departure_id = $route_id;
                    $inclusion->dep_type = "main";
                    $inclusion->tenant_id = $user->tenant_id;
                    $inclusion->user_id = $user->id;
                    $inclusion->save();
                }
            }
        }
        $status = [
                'url'=> url('/departure/poi',$route_id),
            ];
        return response()->json($status);  
    }


    // Date wise functions

    public function departureDateInclusionIndex(Request $request)
    {
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        //date id
        $date_route_ids = $request->route('date_id'); 
        $date_route_id = (int)$date_route_ids;
        $dep_main = Departure::where('id', $route_id)
                ->select('no_of_nights','no_of_days','dep_dook_ref_id','title')
                ->first();

        $inclu_master = DB::table('inclusion_masters')->pluck('name')->toArray();
        $inclu_all = DB::table('icon_inclusions')->pluck('name')->toArray();
        $array = array_diff($inclu_all, $inclu_master);
        $inclusion_icon = DB::table('icon_inclusions')
                ->whereIn('name', $array)
                ->get();
        $inclusions = DB::table('inclusion_masters')->select('name','description','icon')->get();
        $inclusione = Inclusion::where(["departure_id" => $route_id, "departure_date_id" => $date_route_id, "dep_type" => "main"])->select('name','description','icon')->get();

        $array_1 = json_decode(json_encode($inclusions, true),true);
        $array_2 = json_decode(json_encode($inclusione, true),true);
        $array = array_merge($array_1, $array_2);

        //$inclusionedits = array_map("unserialize", array_unique(array_map("serialize", $array)));
        foreach($array as $element) {
            $hash = $element['name'];
            $inclusionedit[$hash] = $element;
        }
        foreach ($inclusionedit as $value) {
            $arr[] = $value;
        }
        $inclusionedits = $arr;
        //dd($inclusionedits);
        $inclusion_icons = DB::table('icon_inclusions')->select('id','name','icon')->get();
        if(count($inclusione) > 0){
            return view('departure.inclusion_edit',compact('inclusionedits','inclusione','inclusion_icons','dep_main','inclusion_icon'));
        }
        else{
            $inclusion_masters = DB::table('inclusion_masters')->get();
            return view('departure.inclusion_create',compact('inclusion_masters','inclusion_icons','dep_main','inclusion_icon'));
        }
            
    }

    public function departureDateStoreInclusion(Request $request)
    {
        $route_ids = $request->route('date_id'); 
        $route_id = (int)$route_ids;
        $user = auth()->user(); 
        $array_check = array();
        for($i = 0; $i < count($request->name); $i++) {
            if ($request->name[$i] != '') {
                array_push($array_check, $request->name[$i]);
            }
        }
        if(count($array_check) > 0){ 
            foreach ($request->names as $key => $value) {
                $n = 1; 
                $start = strlen($value) - $n;
                $str1 = ''; 
                for ($x = $start; $x < strlen($value); $x++) { 
                    $str1 .= $value[$x]; 
                }
                $inc  = Inclusion::where('name',$value)
                        ->where('departure_date_id',$route_id)
                        ->first();
                if(is_null($inc)){
                    $inclusion  = new Inclusion;
                    $incName = substr($value, 0, strlen($value)-1);
                    $inclusion->name = $incName; 
                    if($request->descriptions[$str1] != ''){   
                        $inclusion->description = $request->descriptions[$str1];
                    }
                    else{
                        $inclusion->description = '';
                    }
                    $inclusion->icon = $request->icons[$str1];
                    $inclusion->departure_id = $request->departure_id;
                    $inclusion->departure_date_id = $route_id;
                    $inclusion->dep_type = "main";
                    $inclusion->tenant_id = $user->tenant_id;
                    $inclusion->user_id = $user->id;
                    $inclusion->save();
                }
            }
            foreach ($array_check as $key => $ext_name) {
                $inc  = Inclusion::where('name',$ext_name)
                        ->where('departure_date_id',$route_id)
                        ->first();
                if(is_null($inc)){
                    $inclusion  = new Inclusion;
                    $inclusion->name = $ext_name;   
                    if($request->description[$key] != ''){   
                        $inclusion->description = $request->description[$key];
                    }
                    else{
                        $inclusion->description = '';
                    }
                    $inclusion->icon = $request->icon[$key];
                    $inclusion->departure_id = $request->departure_id;
                    $inclusion->departure_date_id = $route_id;
                    $inclusion->dep_type = "main"; 
                    $inclusion->tenant_id = $user->tenant_id;
                    $inclusion->user_id = $user->id;
                    $inclusion->save();
                }
            } 
        }
        else{
            foreach ($request->names as $key => $value) {
                $n = 1; 
                $start = strlen($value) - $n;
                $str1 = ''; 
                for ($x = $start; $x < strlen($value); $x++) { 
                    $str1 .= $value[$x]; 
                }

                $inc  = Inclusion::where('name',$value)
                        ->where('departure_date_id',$route_id)
                        ->first();
                if(is_null($inc)){
                    $inclusion  = new Inclusion;
                    $incName = substr($value, 0, strlen($value)-1);
                    $inclusion->name = $incName; 
                    if($request->descriptions[$str1] != ''){   
                        $inclusion->description = $request->descriptions[$str1];
                    }
                    else{
                        $inclusion->description = '';
                    }
                    $inclusion->icon = $request->icons[$str1];
                    $inclusion->departure_id = $request->departure_id;
                    $inclusion->departure_date_id = $route_id;
                    $inclusion->dep_type = "main";
                    $inclusion->tenant_id = $user->tenant_id;
                    $inclusion->user_id = $user->id;
                    $inclusion->save();
                }
            }
        }
        $status = [
                'url'=> url('/departure_dates',$request->departure_id),
            ];
        return response()->json($status);  
    }

    public function departureDateUpdateInclusion(Request $request)
    {
        $route_ids = $request->route('date_id'); 
        $route_id = (int)$route_ids;
        $user = auth()->user(); 
        $array_check = array();
        for($i = 0; $i < count($request->name); $i++) {
            if ($request->name[$i] != '') {
                array_push($array_check, $request->name[$i]);
            }
        }
        if(count($array_check) > 0){ 
            Inclusion::where('departure_date_id', $route_id)->delete();
            foreach ($request->names as $key => $value) {
                $n = 1; 
                $start = strlen($value) - $n;
                $str1 = ''; 
                for ($x = $start; $x < strlen($value); $x++) { 
                    $str1 .= $value[$x]; 
                }
                $inc  = Inclusion::where('name',$value)
                        ->where('departure_date_id',$route_id)
                        ->first();
                if(is_null($inc)){
                    $inclusion  = new Inclusion;
                    $incName = substr($value, 0, strlen($value)-1);
                    $inclusion->name = $incName; 
                    if($request->descriptions[$str1] != ''){   
                        $inclusion->description = $request->descriptions[$str1];
                    }
                    else{
                        $inclusion->description = '';
                    }
                    $inclusion->icon = $request->icons[$str1];
                    $inclusion->departure_id = $request->departure_id;
                    $inclusion->departure_date_id = $route_id;
                    $inclusion->dep_type = "main";
                    $inclusion->tenant_id = $user->tenant_id;
                    $inclusion->user_id = $user->id;
                    $inclusion->save();
                }
            }
            foreach ($array_check as $key => $ext_name) {
                $inc  = Inclusion::where('name',$ext_name)
                        ->where('departure_date_id',$route_id)
                        ->first();
                if(is_null($inc)){
                    $inclusion  = new Inclusion;
                    $inclusion->name = $ext_name;   
                    if($request->description[$key] != ''){   
                        $inclusion->description = $request->description[$key];
                    }
                    else{
                        $inclusion->description = '';
                    }
                    $inclusion->icon = $request->icon[$key];
                    $inclusion->departure_id = $request->departure_id;
                    $inclusion->departure_date_id = $route_id; 
                    $inclusion->dep_type = "main";
                    $inclusion->tenant_id = $user->tenant_id;
                    $inclusion->user_id = $user->id;
                    $inclusion->save();
                }
            } 
        }
        else{
            Inclusion::where('departure_date_id', $route_id)->delete();
            foreach ($request->names as $key => $value) {
                $n = 1; 
                $start = strlen($value) - $n;
                $str1 = ''; 
                for ($x = $start; $x < strlen($value); $x++) { 
                    $str1 .= $value[$x]; 
                }
                $inc  = Inclusion::where('name',$value)
                        ->where('departure_date_id',$route_id)
                        ->first();
                if(is_null($inc)){
                    $inclusion  = new Inclusion;
                    $incName = substr($value, 0, strlen($value)-1);
                    $inclusion->name = $incName;  
                    if($request->descriptions[$str1] != ''){   
                        $inclusion->description = $request->descriptions[$str1];
                    }
                    else{
                        $inclusion->description = '';
                    }
                    $inclusion->icon = $request->icons[$str1];
                    $inclusion->departure_id = $request->departure_id;
                    $inclusion->departure_date_id = $route_id; 
                    $inclusion->dep_type = "main";
                    $inclusion->tenant_id = $user->tenant_id;
                    $inclusion->user_id = $user->id;
                    $inclusion->save();
                }
            }
        }
        $status = [
                'url'=> url('/departure_dates',$request->departure_id),
            ];
        return response()->json($status);  
    }
}

