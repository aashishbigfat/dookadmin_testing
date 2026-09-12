<?php

namespace App\Http\Controllers\Departure;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Departure;
use App\Destination;
use App\DepartureDestination;
use App\Inclusion;

class GroupInclusionController extends Controller
{
    public function groupPackagesInclusionIndex(Request $request)
    {
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $inclusions = DB::table('inclusion_masters')->select('name','description')->get();
        $inclusione = Inclusion::where(["departure_id" => $route_id, "dep_type" => "group"])
                    ->select('name','description')
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

        if(count($inclusione) > 0){
            return view('grouppackages.inclusion_edit',compact('inclusionedits','inclusione'));
        }
        else{
            $inclusion_masters = DB::table('inclusion_masters')->get();
            return view('grouppackages.inclusion_create',compact('inclusion_masters'));
        }
            
    }

    public function groupPackagesStoreInclusion(Request $request)
    {
        // print_r($request->all());
        // die();
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
                    $inclusion->dep_type = "group";
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
                    $inclusion->dep_type = "group"; 
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
                    $inclusion->dep_type = "group";
                    $inclusion->tenant_id = $user->tenant_id;
                    $inclusion->user_id = $user->id;
                    $inclusion->save();
                }
            }
        }
        $status = [
                'url'=> url('/group-package/poi',$route_id),
            ];
        return response()->json($status);  
    }

    public function groupPackagesUpdateInclusion(Request $request)
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
                    $inclusion->dep_type = "group";
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
                    $inclusion->dep_type = "group";
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
                    $inclusion->dep_type = "group";
                    $inclusion->tenant_id = $user->tenant_id;
                    $inclusion->user_id = $user->id;
                    $inclusion->save();
                }
            }
        }
        $status = [
                'url'=> url('/group-package/poi',$route_id),
            ];
        return response()->json($status);  
    }

    public function groupDateInclusionIndex(Request $request)
    {
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        //date id
        $date_route_ids = $request->route('date_id'); 
        $date_route_id = (int)$date_route_ids;

        $inclusions = DB::table('inclusion_masters')->select('name','description')->get();
        $inclusione = Inclusion::where(["departure_id" => $route_id,"departure_date_id" => $date_route_id, "dep_type" => "group"])
                    ->select('name','description')
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
            return view('grouppackages.inclusion_edit',compact('inclusionedits','inclusione','inclusion_icons'));
        }
        else{
            $inclusion_masters = DB::table('inclusion_masters')->get();
            return view('grouppackages.inclusion_create',compact('inclusion_masters','inclusion_icons'));
        }
            
    }

    public function groupDateStoreInclusion(Request $request)
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
                    $inclusion->departure_id = $request->departure_id;
                    $inclusion->departure_date_id = $route_id;
                    $inclusion->dep_type = "group";
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
                    $inclusion->departure_id = $request->departure_id;
                    $inclusion->departure_date_id = $route_id;
                    $inclusion->dep_type = "group"; 
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
                    $inclusion->departure_id = $request->departure_id;
                    $inclusion->departure_date_id = $route_id;
                    $inclusion->dep_type = "group";
                    $inclusion->tenant_id = $user->tenant_id;
                    $inclusion->user_id = $user->id;
                    $inclusion->save();
                }
            }
        }
        $status = [
                'url'=> url('/group-tours-dates',$request->departure_id),
            ];
        return response()->json($status);  
    }

    public function groupDateUpdateInclusion(Request $request)
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
                    $inclusion->departure_id = $request->departure_id;
                    $inclusion->departure_date_id = $route_id;
                    $inclusion->dep_type = "group";
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
                    $inclusion->departure_id = $request->departure_id;
                    $inclusion->departure_date_id = $route_id; 
                    $inclusion->dep_type = "group";
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
                    $inclusion->departure_id = $request->departure_id;
                    $inclusion->departure_date_id = $route_id; 
                    $inclusion->dep_type = "group";
                    $inclusion->tenant_id = $user->tenant_id;
                    $inclusion->user_id = $user->id;
                    $inclusion->save();
                }
            }
        }
        $status = [
                'url'=> url('/group-tours-dates',$request->departure_id),
            ];
        return response()->json($status);  
    }
}
