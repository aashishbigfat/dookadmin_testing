<?php

namespace App\Http\Controllers\Departure;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageServiceProvider;
use Illuminate\Support\Str;
use DB;
use Storage;
use Image;
use App\Departure;
use App\Destination;
use App\DepartureDestinationPointOfInterest;
use App\Experience;
use App\Activity;
use App\DestinationExperience;
use App\DestinationPoiImage;
use Google\Cloud\Storage\StorageClient;


class PointOfInterestController extends Controller
{

    public function getExperiences(Request $request){
        $expActivity = [];
        if($request->has('q')){
            $search = $request->q;
            $expActivity = Experience::select("id","experience_name")
                        ->where('experience_name','LIKE',"%$search%")
                        ->get();
            
        }else{
            $expActivity = Experience::select("id","experience_name")
                        ->get();
        }
        return response()->json($expActivity);
    }

    public function pointofInterestGet(Request $request){

            $destid = $request->destination_id;
            $post = array(
            'destination_id' => $destid
            );

            $curl = curl_init();
             curl_setopt($curl, CURLOPT_URL, env("pullIt_BaseUrl").'api/get_poi_related_destination');
             curl_setopt($curl, CURLOPT_TIMEOUT, 30);
             curl_setopt($curl, CURLOPT_POST, 1);
             curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
             curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
             curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
             curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
             $response = curl_exec($curl);
             $data_pullit = json_decode($response);
             // print_r($response);
             // die;
            //  if ($response) {
            //     echo "ok";
            // } else {
            //     echo 'Curl error: ' . curl_error($ch);
            // }
             curl_close ($curl);
           return Response()->json(json_decode($response));
    }
    public function packagesPoiCreate(Request $request)
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
                        ->where(['departure_destination_point_of_interests.departure_id' => $route_id, 'departure_destination_point_of_interests.dep_type' => 'package'])
                        ->distinct()
                        ->select("destinations.id as dest_id","destinations.dest_name","departure_destination_point_of_interests.id","departure_destination_point_of_interests.poi_name","departure_destination_point_of_interests.reference_id as poi_id","departure_destination_point_of_interests.poi_type","departure_destination_point_of_interests.rating","departure_destination_point_of_interests.image","departure_destination_point_of_interests.banner_image","departure_destination_point_of_interests.description","departure_destination_point_of_interests.address","departure_destination_point_of_interests.status","countries.country_name")
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
                    $dest_rows= DB::table('departure_destination_point_of_interests')
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
            }
            else{
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
            //$urlS3 = "https://dook-international.sgp1.cdn.digitaloceanspaces.com/dook/images/poi/";
            // $urlS3 = url('/dook/images/poi').'/';
            $urlS3 = 'https://dooktravels.s3.ap-south-1.amazonaws.com/com/poi/';
            if($request->ajax()){
                return view('packages.destination_poi_list',compact('poi_list','exp_dest','urlS3'));
            }
            return view('packages.destination_poi_create',compact('destinations','poi_list','exp_dest','urlS3'));
    }

    public function packagesPoiStore(Request $request)
    {

        $data = $request->all();
        //dd($data['poiName']);
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $user = auth()->user(); 
        $destination_id = $request->destinations;
        // $poiss = json_encode($request->poiName);
        // $pois = json_decode($poiss);
        // $length = $count_poiName;
        $poiName = isset($data['poiName'])?$data['poiName']:array();
        if(count($poiName)>0){

            foreach ($poiName as $value_json)
            {
                $value = json_decode($value_json);

                $unique = DepartureDestinationPointOfInterest::where(['departure_id' => $route_id,'destination_id' => $destination_id, 'reference_id' => $value->id,'dep_type'=>'package'])->first();
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
                    
                    $depdestpoi->dep_type = "package";
                    $depdestpoi->tenant_id = $user->tenant_id;
                    $depdestpoi->user_id = $user->id;
                    $depdestpoi->save();
                    // image save 
                    $last_reff_id = $depdestpoi->reference_id;
                    $last_id = $depdestpoi->id;
                    $poi_img = DepartureDestinationPointOfInterest::where('reference_id',$last_reff_id)->where('image','!=','')->select('image')->first();
                    $poi_Bimg = DepartureDestinationPointOfInterest::where('reference_id',$last_reff_id)->where('banner_image','!=','')->select('banner_image')->first();
                    $img = DepartureDestinationPointOfInterest::find($last_id);
                    if($poi_img){
                        $img->image = $poi_img->image;
                    }
                    
                    if($poi_Bimg){
                        $img->banner_image = $poi_Bimg->banner_image;
                    }
                    $img->save();
                //image save end
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
                    // $desti_poi_image = DestinationPoiImage::where('destination_id', $destination_id)
                    //                 ->where('point_of_interest_reff_id', $value->id)
                    //                 ->first();
                    // if($desti_poi_image == null || $desti_poi_image == '')
                    // {
                    //     foreach ($poi_images as $key => $imgName) {
                    //         $dest_poi_images = new DestinationPoiImage;
                    //         $dest_poi_images->point_of_interest_reff_id = $value->id;
                    //         $dest_poi_images->destination_id = $destination_id;
                    //         //$image_url = "https://s3-pullit-bucket.s3.us-west-2.amazonaws.com/bing/".$imgName->image_name;
                    //         if($imgName->image_path){
                    //             $image_url = $imgName->image_path;
                    //             $type = pathinfo($image_url, PATHINFO_EXTENSION);
                    //             $data = file_get_contents($image_url);
                    //             $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    //             $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64));
                    //             $imageName = Str::random(5).time() . '.jpg';
                    //             $imageFile = Image::make($image)->fit(464, 260)->stream();
                    //             $imageFile = $imageFile->__toString();
                    //             $img = Storage::disk('s3')->put('dook/images/poi/'.$imageName, $imageFile, 'public');
                    //             $dest_poi_images->image =  $imageName;
                    //             $dest_poi_images->save();
                    //             $first_image = $imageName;
                    //             $last_id_img = $dest_poi_images->id;
                    //         }
                            ////Isko comment hi rahna hai
                            //     else{
                            //         $first_image = '';
                            //         $last_id_img = '';
                            //     }
                                
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
                            //     }
                            // } ////Isko comment hi rahna hai till
                        //}
                    //}
                    $pois = DepartureDestinationPointOfInterest::where('departure_id',$route_id)->where('reference_id',$value->id)->first();
                    if($pois && $pois->image == ''){
                        $dest_poi_image = DestinationPoiImage::where('destination_id', $destination_id)
                                        ->where('point_of_interest_reff_id', $value->id)
                                        ->first();
                        $poi = DepartureDestinationPointOfInterest::findOrFail($pois->id);
                        if(isset($dest_poi_image->image)){
                            $poi->image = $dest_poi_image->image;
                            //$poi->banner_image = $dest_poi_image->banner_image;
                            $poi->save();
                        }
                    }
                }
                else{
                    $pois = DepartureDestinationPointOfInterest::where('departure_id',$route_id)->where('reference_id',$value->id)->first();
                    if($pois && $pois->image == '' ){
                        $dest_poi_image = DestinationPoiImage::where('destination_id', $destination_id)
                                        ->where('point_of_interest_reff_id', $value->id)
                                        ->first();
                        $poi = DepartureDestinationPointOfInterest::findOrFail($pois->id);
                        if(isset($dest_poi_image->image)){
                            $poi->image = $dest_poi_image->image;
                            //$poi->banner_image = $dest_poi_image->banner_image;
                            $poi->save();
                        }  
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
                'url'=> url('/packages/poi',$route_id),
            ];
        return response()->json($status);
    }
    public function packagesPoiUpdate(Request $request, $id)
    {
        $data = $request->all();
        $route_id = $request->route_id;
        $user = auth()->user(); 
        $poi = DepartureDestinationPointOfInterest::where('reference_id', $request->ref_id)
                        ->pluck('id');
        $destination_id = $request->edit_destinations;
        $imageName = '';
        $bannerImageName = '';
        if($request->add_image_edit){
            // $base64String= $request->add_image_edit; 
            // $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64String));
            // $imageName =  Str::random(5).time() . '.jpg';
            //$imageFile = Image::make($image)->fit(464, 260)->stream();
            //Storage::disk('spaces')->put('dook/images/poi/'.$imageName, $imageFile, 'public');
            // $relPath = 'dook/images/poi/';
            //     if (!file_exists(public_path($relPath))) {
            //         mkdir(public_path($relPath), 777, true);
            //     }
            // $img = Image::make($image)->fit(464, 260)->save( public_path($relPath . $imageName) );
            // $imageFile = Image::make($image)->fit(464, 260)->stream();
            // $imageFile = $imageFile->__toString();
            // $p = Storage::disk('s3')->put('dook/images/poi/'.$imageName, $imageFile, 'public'); 

            // $ext = 'webp';
            // $convertImage = Image::make($image)->encode($ext, 60);
            // $imageName = uniqid().'.'.$ext;
            // Storage::disk('s3')->put('com/poi/'.$imageName, $convertImage);


             $image = $request->file('add_image_edit');
                $extension = $image->getClientOriginalExtension();
                $imageName = Str::random(5) . time() . '.' . $extension;
                $path = 'com/poi/' . $imageName;
                $storage = new StorageClient([
                    'projectId' => env('GOOGLE_CLOUD_PROJECT_ID'),
                    'keyFilePath' => env('GOOGLE_CLOUD_KEY_FILE'),
                ]);
                $bucket = $storage->bucket(env('BUCKET_NAME'));
                $object = $bucket->upload(
                    fopen($image->getRealPath(), 'r'), // open stream to ensure binary is read correctly
                    [
                        'name' => $path,
                        'metadata' => [
                            'contentType' => $image->getMimeType(), // set correct format
                        ],
                    ]
                );
                $request->add_image_edit = $imageName;
        }
        //Banner image
        if($request->add_image_edit_banner){
            // $base64String= $request->add_image_edit_banner; //64 bit code    
            // $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64String));
            // $bannerImageName =  Str::random(6).time() . '.jpg';
            //$imageFile = Image::make($image)->fit(1920, 768)->stream();
            //Storage::disk('spaces')->put('dook/images/poi/'.$bannerImageName, $imageFile, 'public');
            // $relPath = 'dook/images/poi/';
            //     if (!file_exists(public_path($relPath))) {
            //         mkdir(public_path($relPath), 777, true);
            //     }
            // $img = Image::make($image)->fit(1920, 768)->save( public_path($relPath . $bannerImageName) );
            // $imageFile = Image::make($image)->stream();
            // $imageFile = $imageFile->__toString();
            // $p = Storage::disk('s3')->put('dook/images/poi/'.$bannerImageName, $imageFile, 'public');

            //  $ext = 'webp';
            // $convertImage = Image::make($image)->encode($ext, 60);
            // $bannerImageName = uniqid().'.'.$ext;
            // Storage::disk('s3')->put('com/poi/'.$bannerImageName, $convertImage); 


             $image = $request->file('add_image_edit_banner');
                $extension = $image->getClientOriginalExtension();
                $imageName = Str::random(5) . time() . '.' . $extension;
                $path = 'com/poi/' . $imageName;
                $storage = new StorageClient([
                    'projectId' => env('GOOGLE_CLOUD_PROJECT_ID'),
                    'keyFilePath' => env('GOOGLE_CLOUD_KEY_FILE'),
                ]);
                $bucket = $storage->bucket(env('BUCKET_NAME'));
                $object = $bucket->upload(
                    fopen($image->getRealPath(), 'r'), // open stream to ensure binary is read correctly
                    [
                        'name' => $path,
                        'metadata' => [
                            'contentType' => $image->getMimeType(), // set correct format
                        ],
                    ]
                );
                $request->add_image_edit_banner = $imageName;

        }
        foreach ($poi as $key => $value) {
            $depdestpoi = DepartureDestinationPointOfInterest::find($value);
            $depdestpoi->poi_name = $request->edit_poi;
            $depdestpoi->destination_id = $request->edit_destinations;
            $depdestpoi->address = $request->edit_address;
            $depdestpoi->description = $request->edit_description;
            if($imageName != ''){
                $depdestpoi->image = $imageName;
            }
            if($bannerImageName != ''){
                $depdestpoi->banner_image = $bannerImageName;
            }
            
            $depdestpoi->save();
            $last_dest_id = $depdestpoi->destination_id;
            $poiImages = DestinationPoiImage::where(['point_of_interest_reff_id'=>$request->ref_id, 'destination_id'=>$last_dest_id])->first();
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

    // Poi Edit from module
    public function poiCreateInOnePlace(Request $request)
    {  
        $keywords = $request->keyword;

        if($keywords){
            $poi_list = DepartureDestinationPointOfInterest::join('destinations','destinations.id','=','departure_destination_point_of_interests.destination_id')
                        ->join('countries','countries.id','=','destinations.country_id')
                        ->where('departure_destination_point_of_interests.poi_name', 'LIKE','%'.$keywords.'%')
                        ->orWhere('countries.country_name', 'LIKE','%'.$keywords.'%')
                        ->orWhere('destinations.dest_name', 'LIKE','%'.$keywords.'%')
                        ->distinct('departure_destination_point_of_interests.reference_id')
                        ->select("destinations.id as dest_id","destinations.dest_name","departure_destination_point_of_interests.poi_name","departure_destination_point_of_interests.reference_id as poi_id","departure_destination_point_of_interests.poi_type","departure_destination_point_of_interests.rating","departure_destination_point_of_interests.image","departure_destination_point_of_interests.banner_image","departure_destination_point_of_interests.description","departure_destination_point_of_interests.address","departure_destination_point_of_interests.status","countries.country_name")
                        ->orderBy('departure_destination_point_of_interests.image', 'ASC')
                        ->paginate(30);
            }
            else{
                $poi_list = DepartureDestinationPointOfInterest::join('destinations','destinations.id','=','departure_destination_point_of_interests.destination_id')
                        ->join('countries','countries.id','=','destinations.country_id')
                        ->distinct('departure_destination_point_of_interests.reference_id')
                        ->select("destinations.id as dest_id","destinations.dest_name","departure_destination_point_of_interests.poi_name","departure_destination_point_of_interests.reference_id as poi_id","departure_destination_point_of_interests.poi_type","departure_destination_point_of_interests.rating","departure_destination_point_of_interests.image","departure_destination_point_of_interests.banner_image","departure_destination_point_of_interests.description","departure_destination_point_of_interests.address","departure_destination_point_of_interests.status","countries.country_name")
                        ->orderBy('departure_destination_point_of_interests.image', 'ASC')
                        ->paginate(30);
            }
            $total_poi = DepartureDestinationPointOfInterest::pluck('reference_id')
                         ->toArray(); 
            $uniquePoi = array_unique($total_poi);
            $totalpois = count($uniquePoi);
            if(count($poi_list) > 0){
                foreach ($poi_list as $key => $images){
                    $images_row = DestinationPoiImage::where('point_of_interest_reff_id', $images->poi_id)->pluck('image')->toArray();
                    $images->poi_images = $images_row;
                }
            }
            //+++++++++++++++For Edit Poi +++++++++++++++++++++++++++//

            //$urlS3 = "https://dook-international.sgp1.cdn.digitaloceanspaces.com/dook/images/poi/";
            // $urlS3 = url('/dook/images/poi').'/';
            $urlS3 = 'https://dooktravels.s3.ap-south-1.amazonaws.com/com/poi/';
            if($request->ajax()){
                return view('packages.pointofinterest_list',compact('poi_list','urlS3'));
            }
            return view('packages.pointofinterest_create',compact('poi_list','urlS3','totalpois','keywords'));
    }
    public function poiUpdateInOnePlace(Request $request, $id)
    { 
        $poi = DepartureDestinationPointOfInterest::where('reference_id', $request->ref_id)
                        ->pluck('id');
        $counter = 0;
        foreach ($poi as $key => $value) {
            $destination_id = $request->edit_destinations;
            $depdestpoi = DepartureDestinationPointOfInterest::find($value);
            $depdestpoi->poi_name = $request->edit_poi;

            $depdestpoi->address = $request->edit_address;
            $depdestpoi->description = $request->edit_description;
            //$poi_id = DepartureDestinationPointOfInterest::where('reference_id', $request->ref_id)->first('id');
            if( $counter == 0 ) {
                // if($request->add_image_edit){
                //     $base64String= $request->add_image_edit; //64 bit code    
                //     //dd($base64String);
                //     $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64String));
                //     $imageName =  Str::random(8).time() . '.jpg';
                //     //$imageFile = Image::make($image)->fit(464, 260)->stream();
                //     //Storage::disk('spaces')->put('dook/images/poi/'.$imageName, $imageFile, 'public');
                //     $relPath = 'dook/images/poi/';
                //         if (!file_exists(public_path($relPath))) {
                //             mkdir(public_path($relPath), 777, true);
                //         }
                //     $img = Image::make($image)->fit(464, 260)->save( public_path($relPath . $imageName) );
                //     // $imageFile = Image::make($image)->fit(464, 260)->stream();
                //     // $imageFile = $imageFile->__toString();
                //     // $p = Storage::disk('s3')->put('dook/images/poi/'.$imageName, $imageFile, 'public'); 
                //     $depdestpoi->image = $imageName;
                // }
                 if ($request->hasFile('add_image_edit')) {
                    $image = $request->file('add_image_edit');
                    $extension = $image->getClientOriginalExtension();
                    $imageName = Str::random(5) . time() . '.' . $extension;
                    $path = 'com/poi/' . $imageName;
                    $storage = new StorageClient([
                        'projectId' => env('GOOGLE_CLOUD_PROJECT_ID'),
                        'keyFilePath' => env('GOOGLE_CLOUD_KEY_FILE'),
                    ]);
                    $bucket = $storage->bucket(env('BUCKET_NAME'));
                    $object = $bucket->upload(
                        fopen($image->getRealPath(), 'r'), // open stream to ensure binary is read correctly
                        [
                            'name' => $path,
                            'metadata' => [
                                'contentType' => $image->getMimeType(), // set correct format
                            ],
                        ]
                    );
                    $depdestpoi->image = $imageName;
                }
                // if($request->add_image_edit){
                //     $base64String= $request->add_image_edit; 
                //     $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64String));
                //     // $imageName =  Str::random(5).time() . '.jpg';
                //     //$imageFile = Image::make($image)->fit(464, 260)->stream();
                //     //Storage::disk('spaces')->put('dook/images/poi/'.$imageName, $imageFile, 'public');
                //     // $relPath = 'dook/images/poi/';
                //     //     if (!file_exists(public_path($relPath))) {
                //     //         mkdir(public_path($relPath), 777, true);
                //     //     }
                //     // $img = Image::make($image)->fit(464, 260)->save( public_path($relPath . $imageName) );
                //     // $imageFile = Image::make($image)->fit(464, 260)->stream();
                //     // $imageFile = $imageFile->__toString();
                //     // $p = Storage::disk('s3')->put('dook/images/poi/'.$imageName, $imageFile, 'public'); 

                //     $ext = 'webp';
                //     $convertImage = Image::make($image)->encode($ext, 60);
                //     $imageName = uniqid().'.'.$ext;
                //     Storage::disk('s3')->put('com/poi/'.$imageName, $convertImage);
                //     $depdestpoi->image = $imageName;
                // }


                //Banner image
                if ($request->hasFile('add_image_edit_banner')) {
                    $image = $request->file('add_image_edit_banner');
                    $extension = $image->getClientOriginalExtension();
                    $imageName = Str::random(5) . time() . '.' . $extension;
                    $path = 'com/poi/' . $imageName;
                    $storage = new StorageClient([
                        'projectId' => env('GOOGLE_CLOUD_PROJECT_ID'),
                        'keyFilePath' => env('GOOGLE_CLOUD_KEY_FILE'),
                    ]);
                    $bucket = $storage->bucket(env('BUCKET_NAME'));
                    $object = $bucket->upload(
                        fopen($image->getRealPath(), 'r'), // open stream to ensure binary is read correctly
                        [
                            'name' => $path,
                            'metadata' => [
                                'contentType' => $image->getMimeType(), // set correct format
                            ],
                        ]
                    );
                    $depdestpoi->banner_image = $imageName;
                }
                // if($request->add_image_edit_banner){
                //     $base64String= $request->add_image_edit_banner; 
                //     $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64String));
                //     $bannerImageName =  Str::random(6).time() . '.jpg';
                    
                //      $ext = 'webp';
                //     $convertImage = Image::make($image)->encode($ext, 60);
                //     $bannerImageName = uniqid().'.'.$ext;
                //     Storage::disk('s3')->put('com/poi/'.$bannerImageName, $convertImage); 
                //     $depdestpoi->banner_image = $bannerImageName;
                // }
                // if($request->add_image_edit_banner){
                //     $base64String= $request->add_image_edit_banner; //64 bit code    
                //     //dd($base64String);
                //     $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64String));
                //     $imageName =  Str::random(9).time() . '.jpg';
                //     //spaces
                //     //$imageFile = Image::make($image)->fit(1920, 768)->stream();
                //     //Storage::disk('spaces')->put('dook/images/poi/'.$imageName, $imageFile, 'public');
                //     //aws
                //     // $imageFile = Image::make($image)->stream();
                //     // $imageFile = $imageFile->__toString();
                //     // $p = Storage::disk('s3')->put('dook/images/poi/'.$imageName, $imageFile, 'public'); 
                //     $relPath = 'dook/images/poi/';
                //         if (!file_exists(public_path($relPath))) {
                //             mkdir(public_path($relPath), 777, true);
                //         }
                //     $img = Image::make($image)->fit(1920, 768)->save( public_path($relPath . $imageName) );
                    
                //     $depdestpoi->banner_image = $imageName;
                // }
            }
            // else{
            //     $poi_existing_image = DepartureDestinationPointOfInterest::where('reference_id', $request->ref_id)->select('image','banner_image')->first();
            //     //dd($poi_existing_image);
            //     $depdestpoi->image = $poi_existing_image->image;
            //     $depdestpoi->banner_image = $poi_existing_image->banner_image;
            // }
            $depdestpoi->save();

            $last_image = $depdestpoi->image;
            $last_banner_image = $depdestpoi->banner_image;
            $last_dest_id = $depdestpoi->destination_id;
            $poiImages = DestinationPoiImage::where(['point_of_interest_reff_id'=>$request->ref_id, 'destination_id'=>$last_dest_id])->first();
            if($poiImages){
                $poi_id = $poiImages->id;
                $depdestpoiImages = DestinationPoiImage::find($poi_id);
                $depdestpoiImages->destination_id = $last_dest_id;
                $depdestpoiImages->point_of_interest_reff_id = $request->ref_id;
                $depdestpoiImages->image = $last_image;
                $depdestpoiImages->banner_image = $last_banner_image;
                $depdestpoiImages->save();
            }
            else{
                $poiImageSingle = DepartureDestinationPointOfInterest::where(['reference_id'=>$request->ref_id, 'destination_id'=>$request->edit_destinations])->first();
                if($poiImageSingle){
                    $depdestpoiImages = new DestinationPoiImage;
                    $depdestpoiImages->destination_id = $poiImageSingle->destination_id;
                    $depdestpoiImages->point_of_interest_reff_id = $request->ref_id;
                    $depdestpoiImages->image = $poiImageSingle->image;
                    $depdestpoiImages->banner_image = $poiImageSingle->banner_image;
                    $depdestpoiImages->save();
                }
            }
            $counter++;
        }
        $status = [
                'status'=> 'Success',
            ];
        return response()->json($status);
    }
    public function packagesPoiDisable(Request $request, $id)
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
    public function packagesPoiDelete(Request $request)
    {
    //dd($request->route_id);
        $data = DestinationExperience::where('destination_id', $request->id)
                ->where('departure_id',$request->route_id)
                ->pluck('id')
                ->toArray();
        $poidisableenable = DestinationExperience::whereIn('id', $data)->delete();
        return response()->json(['success'=>'POI deleted successfully!']);
    }

    // public function addMorePoi(Request $request)
    // {
    //    $data = $request->all();
    //    return response()->json(['data'=>$data]);
    // }

   public function addMorePoiSaveInPull(Request $request){

            //$destid = $request->destination_id;
            $post = array(
            'place_id' => $request->add_place_id,
            'poi_type' => $request->add_poi_type,
            'hours' => $request->add_hours,
            'lat' => $request->add_lat,
            'long' => $request->add_long,
            'rating' => $request->add_rating,
            'reviews' => $request->add_reviews,
            'website' => $request->add_web_url,
            'map_url' => $request->add_poi_url,
            'mobile' => $request->add_phone,
            'poi' => $request->add_poi,
            'destination' => $request->add_destination,
            'country' => $request->add_country,
            'address' => $request->add_address,
            'description' => $request->add_description,
            'region_id' => $request->add_regionid,
            'geoname_id' => $request->add_geonameid,
            'image' => $request->add_image,
            
            'country_name' => $request->add_country_name,
            'destination_name' => $request->add_destination_name,
            'dest_lat' => $request->add_dest_lat,
            'dest_long' => $request->add_dest_long,
            'iso' => $request->add_iso_3,
            'region' => $request->add_dest_region,
            'fclass' => $request->add_fclass,
            'fcode' => $request->add_fcode,
            );

            $curl = curl_init();
             curl_setopt($curl, CURLOPT_URL, env("pullIt_BaseUrl").'api/add-more-pois');
             curl_setopt($curl, CURLOPT_TIMEOUT, 30);
             curl_setopt($curl, CURLOPT_POST, 1);
             curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
             curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
             curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
             curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
             $response = curl_exec($curl);
             $pois = json_decode($response);
            $route_id = $request->add_route_id; 
            $user = auth()->user();
            //dd($pois); 
            if($pois->data && $route_id){
                $unique = DepartureDestinationPointOfInterest::where(['destination_id' => $request->add_dook_dest_id, 'reference_id' => $pois->data])->first();
                if($unique || $unique != ''){   
                    $uniquePoi = DepartureDestinationPointOfInterest::where(['departure_id' => $route_id,'destination_id' => $request->add_dook_dest_id, 'reference_id' => $pois->data, 'dep_type'=>$request->dep_type])->first(); 
                    if($uniquePoi){

                    }else{
                        $depdestpoi = new DepartureDestinationPointOfInterest;
                        $depdestpoi->departure_id = $route_id;
                        $depdestpoi->destination_id = $unique->destination_id;
                        $depdestpoi->reference_id = $unique->reference_id;
                        $depdestpoi->poi_name = $unique->poi_name;
                        $depdestpoi->latitude = $unique->latitude;
                        $depdestpoi->longitude = $unique->longitude;
                        $depdestpoi->phone = $unique->phone;
                        $depdestpoi->website = $unique->website;
                        $depdestpoi->openhours = $unique->openhours;
                        $depdestpoi->rating = $unique->rating;
                        $depdestpoi->reviews = $unique->reviews;
                        $depdestpoi->poi_type = $unique->poi_type;
                        $depdestpoi->address = $unique->address;
                        $depdestpoi->description = $unique->description;
                        $depdestpoi->dep_type = $request->dep_type;
                        $depdestpoi->tenant_id = $user->tenant_id;
                        $depdestpoi->user_id = $user->id;
                        // if($unique->banner_image != '' || $unique->banner_image != null){ 
                        //     $depdestpoi->banner_image =  $unique->banner_image;
                        // }
                        // else{
                        //     if($request->add_image){
                        //         $base64String= $request->add_image; //64 bit code
                        //         $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64String));
                        //         $imageName = Str::random(7).time() . '.jpg';
                        //         $imageFile = Image::make($image)->fit(1920, 768)->stream();
                        //         $imageFile = $imageFile->__toString();
                        //         $img = Storage::disk('s3')->put('dook/images/poi/'.$imageName, $imageFile, 'public');
                        //         $depdestpoi->banner_image =  $imageName; 
                        //     }
                        // }
                        if($unique->image != '' || $unique->image != null){ 
                            $depdestpoi->image =  $unique->image;
                        }else{
                            if($request->add_image){
                                $base64String= $request->add_image; //64 bit code
                                $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64String));
                                $imageName = Str::random(5).time() . '.jpg';
                                //$imageFile = Image::make($image)->fit(464, 260)->stream();
                                //Storage::disk('spaces')->put('dook/images/poi/'.$imageName, $imageFile, 'public');
                                $relPath = 'dook/images/poi/';
                                    if (!file_exists(public_path($relPath))) {
                                        mkdir(public_path($relPath), 777, true);
                                    }
                                $img = Image::make($image)->fit(464, 260)->save( public_path($relPath . $imageName) );
                                // $imageFile = Image::make($image)->stream();
                                // $imageFile = $imageFile->__toString();
                                // $img = Storage::disk('s3')->put('dook/images/poi/'.$imageName, $imageFile, 'public');
                                $depdestpoi->image =  $imageName; 
                            } 
                        }
                        $depdestpoi->save();               
                        $uniqueImag = DestinationPoiImage::where(['destination_id' => $request->add_dook_dest_id, 'point_of_interest_reff_id' => $pois->data])
                        ->first();
                        if($uniqueImag){
                        
                        }else{
                            $poiImages = new DestinationPoiImage;
                            $poiImages->point_of_interest_reff_id = $pois->data;
                            $poiImages->destination_id = $request->add_dook_dest_id;
                            //$poiImages->banner_image = $unique->banner_image;
                            $poiImages->image = $unique->image;
                            $poiImages->save();
                        }
                    }
                }else{
                    $depdestpoi = new DepartureDestinationPointOfInterest;
                    $depdestpoi->departure_id = $route_id;
                    $depdestpoi->destination_id = $request->add_dook_dest_id;
                    $depdestpoi->reference_id = $pois->data;
                    $depdestpoi->poi_name = $request->add_poi;
                    $depdestpoi->latitude = $request->add_lat;
                    $depdestpoi->longitude = $request->add_long;
                    $depdestpoi->phone = $request->add_phone;
                    $depdestpoi->website = $request->add_web_url;
                    $depdestpoi->openhours = $request->add_hours;
                    if($request->add_rating == ''){
                        $depdestpoi->rating = 4;
                    }else{
                        $depdestpoi->rating = $request->add_rating;
                    }
                    if($request->add_reviews == ''){
                       $depdestpoi->reviews = 1250;
                    }else{
                        $depdestpoi->reviews = $request->add_reviews;
                    }
                    $depdestpoi->poi_type = $request->add_poi_type;
                    $depdestpoi->address = $request->add_address;
                    $depdestpoi->description = $request->add_description;
                    
                   $depdestpoi->dep_type = $request->dep_type;
                    $depdestpoi->tenant_id = $user->tenant_id;
                    $depdestpoi->user_id = $user->id;
                   
                    if($request->add_image){
                        $base64String= $request->add_image; //64 bit code
                        $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64String));
                        $imageName = Str::random(5).time() . '.jpg';
                        //$imageFile = Image::make($image)->fit(464, 260)->stream();
                        //Storage::disk('spaces')->put('dook/images/poi/'.$imageName, $imageFile, 'public');
                        // $imageFile = Image::make($image)->fit(464, 260)->stream();
                        // $imageFile = $imageFile->__toString();
                        // $img = Storage::disk('s3')->put('dook/images/poi/'.$imageName, $imageFile, 'public');
                        $relPath = 'dook/images/poi/';
                            if (!file_exists(public_path($relPath))) {
                                mkdir(public_path($relPath), 777, true);
                            }
                        $img = Image::make($image)->fit(464, 260)->save( public_path($relPath . $imageName) );
                        $depdestpoi->image =  $imageName; 
                    }
                    // if($request->add_image){
                    //     $base64String= $request->add_image; //64 bit code
                    //     $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64String));
                    //     $imageName = Str::random(7).time() . '.jpg';
                    //     $imageFile = Image::make($image)->fit(1920, 768)->stream();
                    //     $imageFile = $imageFile->__toString();
                    //     $img = Storage::disk('s3')->put('dook/images/poi/'.$imageName, $imageFile, 'public');
                    //     $depdestpoi->banner_image =  $imageName; 
                    // }
                    $depdestpoi->save();
                    $lastId = $depdestpoi->id;
                    $last_banner = $depdestpoi->banner_image;
                    $last_image = $depdestpoi->image;
                    if($lastId){
                        $desti_poi_image = DestinationPoiImage::where('destination_id', $request->add_dook_dest_id)->where('point_of_interest_reff_id', $pois->data)
                            ->first();
                        if($desti_poi_image == null || $desti_poi_image == '')
                        {
                            $poiImages = new DestinationPoiImage;
                            $poiImages->point_of_interest_reff_id = $pois->data;
                            $poiImages->destination_id = $request->add_dook_dest_id;
                            //$poiImages->banner_image = $last_banner;
                            $poiImages->image = $last_image;
                            $poiImages->save();
                        }
                    }
                }
            }
        }
}