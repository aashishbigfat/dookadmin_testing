<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageServiceProvider;
use DB;
use Storage;
use Image;
use Auth;
use User;
use App\Departure;
use App\Destination;
use App\Country;
use App\DepartureDestination;
use App\Experience;
use App\DestinationExperience;
use App\TopDestination;
use App\CountryDeparture;
use App\Traits\TinyPngImageCompress;
use Google\Cloud\Storage\StorageClient;

class TopDestinationController extends Controller
{
    use TinyPngImageCompress;
    
    public function topDestinationCreate(Request $request){
    	$experiences = DB::table('experiences')
    						->distinct()
            				->orderBy('experience_name','ASC')
                			->get();
        $top_destination_exp = TopDestination::where('type', 'departures')
                                ->orderBy('grid_number', 'ASC')
                                ->paginate(24);
//dd($top_destination_exp);
        foreach ($top_destination_exp as $key => $value) {
            $dep_name = Destination::where('id', $value->destination_id)
                        ->select('id','dest_name', 'slug_url')
                        ->first();

            //dd($dep_name);
            //$country_name = Country::where('country_name', $value->destination_name)->select('id')->first();
            if($dep_name){
                $total_departure = DepartureDestination::where('destination_id', $dep_name->id)->distinct('departure_id')->count('departure_id');
            $value->dest_name = $dep_name->dest_name;
            $value->slug = $dep_name->slug_url;
            }
            else{
                $total_departure = 0;
                $value->dest_name = '';
                $value->slug = '';
            }
            // if($country_name != ''){
            //     $total_country = CountryDeparture::where('country_id', $country_name->id)->distinct('country_id')->count('country_id');
            // }
            // else{
            //     $total_country = 0;
            // }
           // $total_dep = $total_departure + $total_country;
            $value->total_dep = $total_departure;
        }
        //dd($top_destination_exp);
        //dd($top_destination_exp);
        $top_destination_total = TopDestination::where('type', 'departures')->get();
        $count = 24;
        $total = count($top_destination_total);
        //dd($total);
        //$urlS3 = "https://dook-international.sgp1.cdn.digitaloceanspaces.com/dook/images/destinations/";
        // $urlS3 = url('/dook/images/destinations/').'/';
         $urlS3 = 'https://dooktravels.s3.ap-south-1.amazonaws.com/com/destinations/';
        if($request->ajax()){
                return view('landingdeparture.data_list',compact('top_destination_exp','urlS3'));
            }
        return view('landingdeparture.create',compact('experiences','top_destination_exp','total','count','urlS3'));
    }

    public function topDestinationStore(Request $request)
    {   
    	$data = $request->all();
    	$user = auth()->user();
        if($request->experiencesId){
            $array_experiences_id = array();
            for($i = 0; $i < count($request->experiencesId); $i++) {
                if ($request->experiencesId[$i] != '') {
                    array_push($array_experiences_id, $request->experiencesId[$i]);
                }
            }
        }
        else{
            $array_experiences_id = [];
        }
        //Exp Name
        if($request->experiencesName){
            $array_experiences_name = array();
            for($i = 0; $i < count($request->experiencesName); $i++) {
                if ($request->experiencesName[$i] != '') {
                    array_push($array_experiences_name, $request->experiencesName[$i]);
                }
            }
        }
        else{
            $array_experiences_name = [];
        }

        $top_destinations  = new TopDestination;
        $top_destinations->destination_id = $request->destination;
        //$top_destinations->destination_name = $request->destination_name;
        //$top_destinations->slug_url = $request->slug_url;
        $top_destinations->label_name = $request->view_label;
        $top_destinations->grid_number = $request->destination_for;
        $top_destinations->type = "departures";
        $top_destinations->experience_id = implode(",", $array_experiences_id);
        $top_destinations->experience_name = implode(",", $array_experiences_name);

         if ($request->hasFile('dest_image')) {
                $image = $request->file('dest_image');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/destinations/' . $originalName;

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

                $top_destinations->image = $originalName;
            }
        // if($request->dest_image){
        //     $image = $request->file('dest_image');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $filename = Str::random(5).time() .'.'. $extension;
        //     // $images = Image::make($image);
        //     // Storage::disk('s3')->put('dook/images/destinations/'.$filename, $images->stream(), 'public');
        //     // $imagecompress = $this->compressToLocal($image, 'dook/images/destinations', $filename);
        //     // $top_destinations->image = $filename; 

        //      $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/destinations/'.$fileName, $convertImage);
        //     $top_destinations->image = $fileName; 
        // }
        $top_destinations->user_id = $user->id;
        $top_destinations->save();

        $status = [
                'status'=> "Success!",
            ];
        return response()->json($status);
    }
    public function topDestinationEdit(Request $request, $id){
        $user = auth()->user();
    	$top_destinations = TopDestination::where('id',$id)->first();
        $destination = Destination::where('id',$top_destinations->destination_id)
                        ->select('dest_name')
                        ->first();
        if($top_destinations){
            $top_destinations->dest_name = $destination->dest_name;
        }
        //dd($top_destinations);
	    if($top_destinations){
	        $data = explode(",",$top_destinations->experience_id);
	        $top_destinations['exp_id'] = $data;
	    }
	    else{
	        $top_destinations['exp_id'] = [];
	    }
	    //Exp Name
	    if($top_destinations){
            $data = explode(",",$top_destinations->experience_name);
            $top_destinations['exp_name'] = $data;
        }
        else{
            $top_destinations['exp_name'] = [];
        }
        //dd($top_destinations);
    	$experiences = DB::table('experiences')
    						->distinct()
            				->orderBy('experience_name','ASC')
                			->get();
        //$urlS3 = "https://dook-international.sgp1.cdn.digitaloceanspaces.com/dook/images/destinations/";
        // $urlS3 = url('/dook/images/destinations/').'/';
        $urlS3 = 'https://dooktravels.s3.ap-south-1.amazonaws.com/com/destinations/';
        return view('landingdeparture.edit',compact('experiences','top_destinations','urlS3'));
    }

    public function topDestinationUpdate(Request $request, $id)
    {   
    	$data = $request->all();
        $user = auth()->user();
    	//Exp Id
        if($request->experiencesId){
            $array_experiences_id = array();
            for($i = 0; $i < count($request->experiencesId); $i++) {
                if ($request->experiencesId[$i] != '') {
                    array_push($array_experiences_id, $request->experiencesId[$i]);
                }
            }
        }
        else{
            $array_experiences_id = [];
        }
        //Exp Name
        if($request->experiencesName){
            $array_experiences_name = array();
            for($i = 0; $i < count($request->experiencesName); $i++) {
                if ($request->experiencesName[$i] != '') {
                    array_push($array_experiences_name, $request->experiencesName[$i]);
                }
            }
        }
        else{
            $array_experiences_name = [];
        }
        $top_destinations  = TopDestination::find($id);
        $top_destinations->destination_id = $request->destination;
        //$top_destinations->destination_name = $request->destination;
        //$top_destinations->slug_url = $request->slug_url;
        $top_destinations->label_name = $request->view_label;
        $top_destinations->grid_number = $request->destination_for;
        $top_destinations->type = "departures";
        $top_destinations->experience_id = implode(",", $array_experiences_id);
        $top_destinations->experience_name = implode(",", $array_experiences_name);

         if ($request->hasFile('dest_image')) {
                $image = $request->file('dest_image');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/destinations/' . $originalName;

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

                $top_destinations->image = $originalName;
            }

        // if($request->dest_image){
        //     $image = $request->file('dest_image');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $filename = Str::random(5).time() .'.'. $extension;
        //     // $images = Image::make($image);
        //     // Storage::disk('s3')->put('dook/images/destinations/'.$filename, $images->stream(), 'public');
        //     // $imagecompress = $this->compressToLocal($image, 'dook/images/destinations', $filename);
        //     // $top_destinations->image = $filename;  

        //       $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/destinations/'.$fileName, $convertImage);
        //     $top_destinations->image = $fileName; 
        // }
        $top_destinations->user_id = $user->id;
        $top_destinations->save();

        $status = [
                'url'=> url('/top-destinations-departure/create'),
            ];
        return response()->json($status);
    }

    // Destination Section

    public function topDestinationDestinationCreate(Request $request){
        $experiences = DB::table('experiences')
                            ->distinct()
                            ->orderBy('experience_name','ASC')
                            ->get();
        $top_destination_exp = TopDestination::where('type', 'departures')
                                ->orderBy('grid_number', 'ASC')
                                ->paginate(24);
        foreach ($top_destination_exp as $key => $value) {
            $dep_name = Destination::where('id', $value->destination_id)
                        ->select('id','dest_name', 'slug_url')
                        ->first();

            //dd($dep_name);
            //$country_name = Country::where('country_name', $value->destination_name)->select('id')->first();
            if($dep_name){
                $total_departure = DepartureDestination::where('destination_id', $dep_name->id)->distinct('departure_id')->count('departure_id');
            $value->dest_name = $dep_name->dest_name;
            $value->slug = $dep_name->slug_url;
            }
            else{
                $total_departure = 0;
                $value->dest_name = '';
                $value->slug = '';
            }
            // if($country_name != ''){
            //     $total_country = CountryDeparture::where('country_id', $country_name->id)->distinct('country_id')->count('country_id');
            // }
            // else{
            //     $total_country = 0;
            // }
           // $total_dep = $total_departure + $total_country;
            $value->total_dep = $total_departure;
        }
        $top_destination_total = TopDestination::where('type', 'destinations')->get();
        $count = 24;
        $total = count($top_destination_total);
        //dd($total);
        //$urlS3 = "https://dook-international.sgp1.cdn.digitaloceanspaces.com/dook/images/destinations/";
        // $urlS3 = url('/dook/images/destinations/').'/';
          $urlS3 = 'https://dooktravels.s3.ap-south-1.amazonaws.com/com/destinations/';
        if($request->ajax()){
                return view('landingdestination.data_list',compact('top_destination_exp','urlS3'));
            }
        return view('landingdestination.create',compact('experiences','top_destination_exp','total','count','urlS3'));
    }

    public function topDestinationDestinationStore(Request $request)
    {   
        $data = $request->all();
        $user = auth()->user();
        //Exp Id
        if($request->experiencesId){
            $array_experiences_id = array();
            for($i = 0; $i < count($request->experiencesId); $i++) {
                if ($request->experiencesId[$i] != '') {
                    array_push($array_experiences_id, $request->experiencesId[$i]);
                }
            }
        }
        else{
            $array_experiences_id = [];
        }
        //Exp Name
        if($request->experiencesName){
            $array_experiences_name = array();
            for($i = 0; $i < count($request->experiencesName); $i++) {
                if ($request->experiencesName[$i] != '') {
                    array_push($array_experiences_name, $request->experiencesName[$i]);
                }
            }
        }
        else{
            $array_experiences_name = [];
        }

        $top_destinations  = new TopDestination;
        $top_destinations->destination_id = $request->destination;
        //$top_destinations->destination_name = $request->destination_name;
        //$top_destinations->slug_url = $request->slug_url;
        $top_destinations->label_name = $request->view_label;
        $top_destinations->grid_number = $request->destination_for;
        $top_destinations->type = "destinations";
        $top_destinations->experience_id = implode(",", $array_experiences_id);
        $top_destinations->experience_name = implode(",", $array_experiences_name);
        if ($request->hasFile('dest_image')) {
                $image = $request->file('dest_image');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/destinations/' . $originalName;

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

                $top_destinations->image = $originalName;
            }

        // if($request->dest_image){
        //     $image = $request->file('dest_image');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $filename = Str::random(5).time() .'.'. $extension;
        //     // $images = Image::make($image);
        //     // Storage::disk('s3')->put('dook/images/destinations/'.$filename, $images->stream(), 'public');
        //     // $imagecompress = $this->compressToLocal($image, 'dook/images/destinations', $filename);
        //     // $top_destinations->image = $filename;  

        //        $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/destinations/'.$fileName, $convertImage);
        //     $top_destinations->image = $fileName; 
        // }
        $top_destinations->user_id = $user->id;
        $top_destinations->save();

        $status = [
                'status'=> "Success!",
            ];
        return response()->json($status);
    }
    public function topDestinationDestinationEdit(Request $request, $id){

        $top_destinations = TopDestination::where('id',$id)->first();
        $destination = Destination::where('id',$top_destinations->destination_id)
                        ->select('dest_name')
                        ->first();
        if($top_destinations){
            $top_destinations->dest_name = $destination->dest_name;
        }
        if($top_destinations){
            $data = explode(",",$top_destinations->experience_id);
            $top_destinations['exp_id'] = $data;
        }
        else{
            $top_destinations['exp_id'] = [];
        }
        //Exp Name
        if($top_destinations){
            $data = explode(",",$top_destinations->experience_name);
            $top_destinations['exp_name'] = $data;
        }
        else{
            $top_destinations['exp_name'] = [];
        }
        //dd($top_destinations);
        $experiences = DB::table('experiences')
                            ->distinct()
                            ->orderBy('experience_name','ASC')
                            ->get();
        //$urlS3 = "https://dook-international.sgp1.cdn.digitaloceanspaces.com/dook/images/destinations/";
        // $urlS3 = url('/dook/images/destinations/').'/';
          $urlS3 = 'https://dooktravels.s3.ap-south-1.amazonaws.com/com/destinations/';
        return view('landingdestination.edit',compact('experiences','top_destinations','urlS3'));
    }

    public function topDestinationDestinationUpdate(Request $request, $id)
    {   
        $data = $request->all();
        $user = auth()->user();
        //Exp Id
        if($request->experiencesId){
            $array_experiences_id = array();
            for($i = 0; $i < count($request->experiencesId); $i++) {
                if ($request->experiencesId[$i] != '') {
                    array_push($array_experiences_id, $request->experiencesId[$i]);
                }
            }
        }
        else{
            $array_experiences_id = [];
        }
        //Exp Name
        if($request->experiencesName){
            $array_experiences_name = array();
            for($i = 0; $i < count($request->experiencesName); $i++) {
                if ($request->experiencesName[$i] != '') {
                    array_push($array_experiences_name, $request->experiencesName[$i]);
                }
            }
        }
        else{
            $array_experiences_name = [];
        }
        $top_destinations  = TopDestination::find($id);
        $top_destinations->destination_id = $request->destination;
        //$top_destinations->destination_name = $request->destination;
        //$top_destinations->slug_url = $request->slug_url;
        $top_destinations->label_name = $request->view_label;
        $top_destinations->grid_number = $request->destination_for;
        $top_destinations->type = "departures";
        $top_destinations->experience_id = implode(",", $array_experiences_id);
        $top_destinations->experience_name = implode(",", $array_experiences_name);
        if ($request->hasFile('dest_image')) {
                $image = $request->file('dest_image');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/destinations/' . $originalName;

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

                $top_destinations->image = $originalName;
            }

        // if($request->dest_image){
        //     $image = $request->file('dest_image');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $filename = Str::random(5).time() .'.'. $extension;
        //     // $images = Image::make($image);
        //     // Storage::disk('s3')->put('dook/images/destinations/'.$filename, $images->stream(), 'public');
        //     // $imagecompress = $this->compressToLocal($image, 'dook/images/destinations', $filename);
        //     // $top_destinations->image = $filename;  

        //        $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/destinations/'.$fileName, $convertImage);
        //     $top_destinations->image = $fileName; 
        // }
        $top_destinations->user_id = $user->id;
        $top_destinations->save();

        $status = [
                'url'=> url('/top-destinations/create'),
            ];
        return response()->json($status);
    }

    public function getTopDestinationAjax(Request $request)
    {
        $data = [];
        if($request->has('q')){
            $search = $request->q;
            $data = Destination::select("id","dest_name")
                        ->where('dest_name','LIKE',"%$search%")
                        ->get(20);
            // foreach ($datas as $key => $value) {
            //     if($value->slug_url == '' || $value->slug_url == null){
            //         $arr = explode(" ", $value->dest_name);
            //         $str = implode("-",$arr);
            //         $slug = Str::lower($str);
            //         $data[] = ['slug_url' =>$slug, 'dest_name' =>$value->dest_name];
            //     }
            //     else{
            //         $data[] = ['slug_url' =>$value->slug_url, 'dest_name' =>$value->dest_name];
            //     }
            // }
            // if(count($data)>0){
            //     return response()->json($data);
            // }
        //     else{
        //         $datas = Country::select("slug_url","country_name as dest_name")
        //                 ->where('country_name','LIKE',"%$search%")
        //                 ->get(10);
        //         foreach ($datas as $key => $value) {
        //             if($value->slug_url == '' || $value->slug_url == null){
        //                 $arr = explode(" ", $value->dest_name);
        //                 $str = implode("-",$arr);
        //                 $slug = Str::lower($str);
        //                 $data[] = ['slug_url' =>$slug, 'dest_name' =>$value->dest_name];
        //             }
        //             else{
        //                 $data[] = ['slug_url' =>$value->slug_url, 'dest_name' =>$value->dest_name];
        //             }
        //         }
        //         return response()->json($data);
        //     }
        // }
        // else{
        //     $data = [];
        // }
        
        }
        else{
            $data = Destination::select("id","dest_name")
                        ->limit(15)
                        ->get();
        }
        return response()->json($data);
    }

    public function getTopDestNameAjax(Request $request)
    {
        $dest_ids = $request->dest_id;
        
        $experiences = DB::table('destinations')
          					->where('destinations.id',$dest_ids)
            				->select("destinations.dest_name")
                			->get();
            return response()->json($experiences);
    }

}
