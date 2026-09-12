<?php

namespace App\Http\Controllers\Departure;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use DB;
use Storage;
use Image;
use User;
use App\Departure;
use App\Destination;
use App\DepartureDestinationPointOfInterest;
use App\Experience;
use App\Activity;
use App\DestinationExperience;
use App\DestinationPoiImage;

class GroupPointOfInterestController extends Controller
{
    public function groupPackagesPoiCreate(Request $request)
    {
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $destinations = Destination::join('departure_destinations','departure_destinations.destination_id','=','destinations.id')
                        ->join('countries','countries.id','=','destinations.country_id')
                        ->where('departure_destinations.departure_id',$route_id)
                        ->distinct()
                        ->select("destinations.id","destinations.dest_name","destinations.geonameid","destinations.region_id","destinations.region","destinations.country_iso_3","destinations.country_name","destinations.reference_id","countries.reference_id as country_reference_id","destinations.latitude","destinations.longitude","destinations.feature_class","destinations.feature_code")
                        ->get();
        $poi_list = DepartureDestinationPointOfInterest::join('destinations','destinations.id','=','departure_destination_point_of_interests.destination_id')
                        ->join('countries','countries.id','=','destinations.country_id')
                        ->where(['departure_destination_point_of_interests.departure_id' => $route_id, 'departure_destination_point_of_interests.dep_type' => 'group'])
                        ->distinct()
                        ->select("destinations.id as dest_id","destinations.dest_name","departure_destination_point_of_interests.id","departure_destination_point_of_interests.poi_name","departure_destination_point_of_interests.reference_id as poi_id","departure_destination_point_of_interests.poi_type","departure_destination_point_of_interests.rating","departure_destination_point_of_interests.image","departure_destination_point_of_interests.banner_image","departure_destination_point_of_interests.status","departure_destination_point_of_interests.description","departure_destination_point_of_interests.address","countries.country_name")
                        ->paginate(25);
            if(count($poi_list) > 0){
                foreach ($poi_list as $key => $images){
                    $images_row = DestinationPoiImage::where('point_of_interest_reff_id', $images->poi_id)->pluck('image')->toArray();
                    $images->poi_images = $images_row;
                }
            }
            
            //+++++++++++++++For Edit Poi +++++++++++++++++++++++++++//

            foreach ($poi_list as $key => $dest_exp){
                $dest_row = DB::table('destination_experiences')
                            ->join('experiences','experiences.id','=','destination_experiences.experience_id')
                            ->where('destination_experiences.destination_id',$dest_exp->dest_id)
                            ->where('destination_experiences.departure_id',$route_id)
                            ->distinct()
                            ->pluck('destination_experiences.experience_id');

                $dest_exp['experiences_id'] = $dest_row;

                $experiences_name = array();
                    foreach ($dest_row as $key => $value) {
                        $exp_name = Experience::where('id', $value)->first();
                        array_push($experiences_name, $exp_name->experience_name);
                    }
                $dest_exp['experience_name'] = $experiences_name;
            }  
            if(count($poi_list)>0){
                foreach ($poi_list as $key => $dest_pois){
                    $dest_rows = DB::table('departure_destination_point_of_interests')
                                ->join('destinations','destinations.id','=','departure_destination_point_of_interests.destination_id')
                                ->where('departure_destination_point_of_interests.departure_id',$route_id)
                                ->distinct()
                                ->pluck('destinations.id')->toArray();
                }
                $unique_dest_id = array_unique($dest_rows);
                if(count($unique_dest_id) > 0 ){
                        $exp_dest = DestinationExperience::join('experiences','experiences.id','=','destination_experiences.experience_id')
                            ->whereIn('destination_experiences.destination_id',$unique_dest_id)
                            ->where('destination_experiences.departure_id',$route_id)
                            ->select('experiences.*','destination_experiences.destination_id')
                            ->get();
                }
                else{
                    $exp_dest = [];
                }
            }else{
                $dest_id = DestinationExperience::where('departure_id',$route_id)
                            ->pluck('destination_id')
                            ->toArray();
                $exp_dest = Destination::whereIn('id',$dest_id)
                                ->select('id','dest_name')
                                ->get();
                foreach ($exp_dest as $key => $value) {
                    $exp_dest_row = DestinationExperience::join('experiences','experiences.id','=','destination_experiences.experience_id')
                            ->where('destination_experiences.departure_id',$route_id)
                            ->where('destination_experiences.destination_id',$value->id)
                            ->select('experiences.experience_name','destination_experiences.destination_id')
                            ->get();
                    $value->experiences = $exp_dest_row;
                }
            }
            $urlS3 = "https://dook-international.sgp1.cdn.digitaloceanspaces.com/dook/images/poi/";
            //$urlS3 = url('/dook/images/poi').'/';
            if($request->ajax()){
                return view('grouppackages.destination_poi_list',compact('poi_list','exp_dest','urlS3'));
            }
            return view('grouppackages.destination_poi_create',compact('destinations','poi_list','exp_dest','urlS3'));
    }

    public function groupPackagesPoiStore(Request $request)
    {
        $data = $request->all();
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $user = auth()->user(); 
        $destination_id = $request->destinations;
        // $poiss = json_encode($request->poiName);
        // $pois = json_decode($poiss);
        // $length = count($pois);
        $poiName = isset($data['poiName'])?$data['poiName']:array();
        if(count($poiName)>0){

            foreach ($poiName as $value_json)
            {
                $value = json_decode($value_json);
                $unique = DepartureDestinationPointOfInterest::where(['departure_id' => $route_id,'destination_id' => $destination_id, 'reference_id' => $value->id,'dep_type'=>'group'])->first();
                 //$aa = $unique;
                if($unique == null){
                    $depdestpoi = new DepartureDestinationPointOfInterest;
                    $depdestpoi->departure_id = $route_id;
                    $depdestpoi->destination_id = $destination_id;
                    $depdestpoi->reference_id = $value->id;
                    $depdestpoi->poi_name = $value->point_name;
                    $depdestpoi->latitude = $value->latitude;
                    $depdestpoi->longitude = $value->longitude;
                    $depdestpoi->phone = $value->phone;
                    $depdestpoi->website = $value->website;
                    $depdestpoi->openhours = $value->openhours;
                    $depdestpoi->height = $value->height;
                    $depdestpoi->width = $value->width;
                    $depdestpoi->length = $value->length;
                    $depdestpoi->depth = $value->depth;

                    if($value->rating == ''){
                        $depdestpoi->rating = 4;
                    }else{
                        $depdestpoi->rating = $value->rating;
                    }
                    if($value->rating == ''){
                       $depdestpoi->reviews = 250;
                    }else{
                        $depdestpoi->reviews = $value->total_reviews;
                    }
                    $depdestpoi->poi_type = $value->poi_type;
                    //$depdestpoi->image = $value->image;
                    $depdestpoi->address = $value->address;
                    $depdestpoi->description = $value->description;
                    $depdestpoi->dep_type = "group";
                    $depdestpoi->tenant_id = $user->tenant_id;
                    $depdestpoi->user_id = $user->id;
                    $depdestpoi->save();

                    // $post = array(
                    //      'poi_id' => $value->id
                    //      );

                    // $curl = curl_init();
                    // curl_setopt($curl, CURLOPT_URL, env("pullIt_BaseUrl").'api/get_poi_image');
                    // curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                    // curl_setopt($curl, CURLOPT_POST, 1);
                    // curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    // curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                    // $response = curl_exec($curl);
                    // $poi_images = json_decode($response);
                    // // print_r($poi_images);
                    // // exit;
                    // $desti_poi_image = DestinationPoiImage::where('destination_id', $destination_id)
                    //                 ->where('point_of_interest_reff_id', $value->id)
                    //                 ->first();
                    // if($desti_poi_image == null || $desti_poi_image == '')
                    // {
                    //     foreach ($poi_images as $key => $imgName) {
                    //             $dest_poi_images = new DestinationPoiImage;
                    //             $dest_poi_images->point_of_interest_reff_id = $value->id;
                    //             $dest_poi_images->destination_id = $destination_id;
                    //             //$image_url = "https://s3-pullit-bucket.s3.us-west-2.amazonaws.com/bing/".$imgName->image_name;
                    //             if($imgName->image_path){
                    //                 $image_url = $imgName->image_path;
                    //                 $type = pathinfo($image_url, PATHINFO_EXTENSION);
                    //                 $data = file_get_contents($image_url);
                    //                 $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    //                 $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64));
                    //                 $imageName = Str::random(5).time() . '.jpg';
                    //                 $imageFile = Image::make($image)->fit(464, 260)->stream();
                    //                 $imageFile = $imageFile->__toString();
                    //                 $img = Storage::disk('s3')->put('dook/images/poi/'.$imageName, $imageFile, 'public');
                    //                 $dest_poi_images->image =  $imageName;
                    //                 $dest_poi_images->save();
                    //                 $first_image = $imageName;
                    //                 $last_id_img = $dest_poi_images->id;
                    //             }
                    //yah se niche comment raheg
                                // else{
                                //     $first_image = '';
                                //     $last_id_img = '';
                                // }
                                
                            // if($first_image != ''){    
                            //     if($key == 0){
                            //     $pois = DepartureDestinationPointOfInterest::where('departure_id',$route_id)->where('reference_id',$value->id)->first();
                            //     if($pois && $pois->image == ''){
                            //         if($image_url){
                            //             $type = pathinfo($image_url, PATHINFO_EXTENSION);
                            //             $data = file_get_contents($image_url);
                            //             $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                            //             $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64));
                            //             $imageNames = Str::random(7).time() . '.jpg';
                            //             $imageFile = Image::make($image)->fit(1920, 760)->stream();
                            //             $imageFile = $imageFile->__toString();
                            //             $img = Storage::disk('s3')->put('dook/images/poi/'.$imageNames, $imageFile, 'public');
                            //             $poi = DepartureDestinationPointOfInterest::findOrFail($pois->id);
                            //             $poi->image = $first_image;
                            //             $poi->banner_image = $imageNames;
                            //             $poi->save();

                            //             $poi = DestinationPoiImage::findOrFail($last_id_img);
                            //             $poi->banner_image = $imageNames;
                            //             $poi->save();
                            //         }
                            //     }
                            // }
                    //     } 
                    // }
                    $pois = DepartureDestinationPointOfInterest::where('departure_id',$route_id)->where('reference_id',$value->id)->first();
                    if($pois && ($pois->image == '')){
                        $dest_poi_image = DestinationPoiImage::where('destination_id', $destination_id)
                                        ->where('point_of_interest_reff_id', $value->id)
                                        ->first();
                        $poi = DepartureDestinationPointOfInterest::findOrFail($pois->id);
                        if(isset($dest_poi_image->image)){
                            $poi->image = $dest_poi_image->image;
                            //$poi->banner_image = $dest_poi_image->banner_image;
                        }
                        $poi->save();
                    }
                }
                else{
                    $pois = DepartureDestinationPointOfInterest::where('departure_id',$route_id)->where('reference_id',$value->id)->first();
                    if($pois && ($pois->image == '' )){
                        $dest_poi_image = DestinationPoiImage::where('destination_id', $destination_id)
                                        ->where('point_of_interest_reff_id', $value->id)
                                        ->first();
                        $poi = DepartureDestinationPointOfInterest::findOrFail($pois->id);
                        if(isset($dest_poi_image->image)){
                            $poi->image = $dest_poi_image->image;
                            //$poi->banner_image = $dest_poi_image->banner_image;
                        }
                        $poi->save();
                    }
                }
            }
        }
        $experiences = $request->experiences;
        if($experiences){
            foreach ($experiences as $value) {
                $destexp = DestinationExperience::where(['destination_id'=>$destination_id, 'experience_id'=>$value,'departure_id'=>$route_id])->first();

                if($destexp == null){                                
                    $experience_destination  = new DestinationExperience;
                    $experience_destination->departure_id = $route_id;
                    $experience_destination->destination_id = $destination_id;
                    $experience_destination->experience_id = $value;
                    $experience_destination->save();
                }
            }  
        }
        $status = [
                'url'=> url('/group-package/poi',$route_id),
            ];
        return response()->json($status);
    }

    public function groupPackagesPoiUpdate(Request $request, $id)
    {
        $data = $request->all();
        $route_id = $request->route_id;
        $user = auth()->user(); 
        $destination_id = $request->edit_destinations;
        $poi = DepartureDestinationPointOfInterest::where('reference_id', $request->ref_id)
                        ->pluck('id');
        foreach ($poi as $key => $value) {
            $depdestpoi = DepartureDestinationPointOfInterest::find($value);
            //dd($depdestpoi);
            $depdestpoi->poi_name = $request->edit_poi;
            $depdestpoi->address = $request->edit_address;
            $depdestpoi->destination_id = $request->edit_destinations;
            $depdestpoi->description = $request->edit_description;
            if($request->add_image_edit){
                $base64String= $request->add_image_edit; //64 bit code    
                //dd($base64String);
                $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64String));
                $imageName =  Str::random(5).time() . '.jpg';
                // $relPath = 'dook/images/poi/';
                //     if (!file_exists(public_path($relPath))) {
                //         mkdir(public_path($relPath), 777, true);
                //     }
                //$img = Image::make($image)->fit(464, 260)->save( public_path($relPath . $imageName) );
                $imageFile = Image::make($image)->fit(464, 260)->stream();
                // $imageFile = $imageFile->__toString();
                Storage::disk('spaces')->put('dook/images/poi/'.$imageName, $imageFile, 'public');
                // $imageFile = Image::make($image)->fit(464, 260)->stream();
                // $imageFile = $imageFile->__toString();
                // $p = Storage::disk('s3')->put('dook/images/poi/'.$imageName, $imageFile, 'public'); 
                $depdestpoi->image = $imageName;
            }
            //Banner image
            if($request->add_image_edit_banner){
                $base64String= $request->add_image_edit_banner; //64 bit code    
                //dd($base64String);
                $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64String));
                $imageName =  Str::random(6).time() . '.jpg';
                $imageFile = Image::make($image)->fit(1920, 768)->stream();
                Storage::disk('spaces')->put('dook/images/poi/'.$imageName, $imageFile, 'public');
                // $relPath = 'dook/images/poi/';
                //     if (!file_exists(public_path($relPath))) {
                //         mkdir(public_path($relPath), 777, true);
                //     }
                // $img = Image::make($image)->fit(1920, 768)->save( public_path($relPath . $imageName) );
                // $imageFile = Image::make($image)->stream();
                // $imageFile = $imageFile->__toString();
                // $p = Storage::disk('s3')->put('dook/images/poi/'.$imageName, $imageFile, 'public'); 
                $depdestpoi->banner_image = $imageName;
            }
            $depdestpoi->save();
            // DestinationPoiImage
            $poiImages = DestinationPoiImage::where(['point_of_interest_reff_id'=>$request->ref_id, 'destination_id'=>$request->edit_destinations])->first();
            if($poiImages){

            }
            else{
                $poiImageSingle = DepartureDestinationPointOfInterest::where(['reference_id'=>$request->ref_id, 'destination_id'=>$request->edit_destinations, 'departure_id'=>$route_id])->first();
                if($poiImageSingle){
                    $depdestpoiImages = new DestinationPoiImage;
                    $depdestpoiImages->destination_id = $request->edit_destinations;
                    $depdestpoiImages->point_of_interest_reff_id = $request->ref_id;
                    $depdestpoiImages->image = $poiImageSingle->image;
                    $depdestpoiImages->banner_image = $poiImageSingle->banner_image;
                    $depdestpoiImages->save();
                }
            }
        }
        $experiences = $request->edit_experiences;
        if($experiences){
            DestinationExperience::where('departure_id',$route_id)
                                ->where('destination_id',$destination_id)
                                ->delete();
            foreach($experiences as $value){                               
                $experience_destination  = new DestinationExperience;
                $experience_destination->departure_id = $route_id;
                $experience_destination->destination_id = $destination_id;
                $experience_destination->experience_id = $value;
                $experience_destination->save();
            }  
        }
        $status = [
                'status'=> 'Success',
            ];
        return response()->json($status);
    }

    public function groupPackagesPoiDisable(Request $request, $id)
    {
        $poidisableenable = DepartureDestinationPointOfInterest::find($id);
        if($poidisableenable->status == 1){
            $poidisableenable->status = 0;
            $poidisableenable->save();
        }
        else{
            $poidisableenable->status = 1;
            $poidisableenable->save();
        }
        return response()->json(['success'=>'POI disabled successfully!']);
    }
}
