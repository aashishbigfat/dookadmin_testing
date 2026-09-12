<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageServiceProvider;
use DB;
use Storage;
use Image;
use Auth;
use App\User;
use App\Departure;
use App\Destination;
use App\Country;
use App\SlugMaster;
use App\DepartureDestination;
use App\Experience;
use App\DestinationExperience;
use App\DestinationImage;
use App\BeforeYougoCountryDeparture;
use App\Traits\TinyPngImageCompress;
use Google\Cloud\Storage\StorageClient;

class CountryController extends Controller
{
    use TinyPngImageCompress;
    public function countryIndex(Request $request){
       // $status = $request->status;
        //$keywords = $request->keyword;
        $status = isset($request->status)?$request->status:'';
        $keywords = isset($request->keyword)?$request->keyword:'';
        if($keywords){
           $countries = Country::where('country_name', 'LIKE','%'.$keywords.'%')
                        // ->orderBy('megha_menu','DESC')
                        ->paginate(25);
        }elseif($status == 'in'){
            $countries = Country::where('status', 0)
                        // ->orderBy('megha_menu','DESC')
                        ->paginate(25);
        }elseif($status == 1){
            $countries = Country::where('status', 1)
                        // ->orderBy('megha_menu','DESC')
                        ->paginate(25);
        }elseif($status == 'in' && $keywords){
            $countries = Country::where('country_name', 'LIKE','%'.$keywords.'%')
                        ->orWhere('status',0)
                        // ->orderBy('megha_menu','DESC')
                        ->paginate(25);
        }elseif($status == 1 && $keywords){
            $countries = Country::where('country_name', 'LIKE','%'.$keywords.'%')
                        ->orWhere('status',1)
                        // ->orderBy('megha_menu','DESC')
                        ->paginate(25);
        }else{
            $countries = Country::paginate(25);                
        }
    	$countryCount = Country::get();
        //$s3url= "https://dook-international.sgp1.cdn.digitaloceanspaces.com/dook/images/country/";
        // $s3url = url('/dook/images/country/').'/';
        $s3url = 'https://dooktravels.s3.ap-south-1.amazonaws.com/com/country/';
    	$total = count($countryCount);
        $status = ($status == null)?'no':$status;
    	if($request->ajax()){
                return view('country.index_data',compact('countries','s3url','status','keywords'));
            }
        return view('country.index',compact('countries','total','s3url','status','keywords'));
    }

    public function countryEdit(Request $request, $id){

        $countries = Country::where('id', $id)->first(); 
        $major_destinations = Destination::where('country_id', $countries->id)
                        ->select('id','dest_name')
                        ->get();
        if($countries){
            $data = explode(",",$countries->major_destinations);
            $selected_destination = Destination::whereIn('id', $data)
                        ->select('id')
                        ->get();
        }
        else{
            $selected_destination = new \stdClass();
        }

        $departure_ids = DB::table('before_yougo_country_departures')
                        ->where('country_id', $id)
                        ->distinct()
                        ->pluck('departure_id')
                        ->toArray();
        $departure_id = DB::table('country_departures')
                        ->where('country_id', $id)
                        ->distinct()
                        ->pluck('departure_id')
                        ->toArray();
        $array_departure_id = array_merge($departure_ids, $departure_id);
        $departures = DB::table('departures')
                        ->whereIn('id', $array_departure_id)
                        ->distinct()
                        ->select('id', 'title')
                        ->get();
        $departure_bygs = DB::table('before_yougo_country_departures')
                        ->where('country_id', $id)
                        ->distinct()
                        ->select('departure_id')
                        ->get();
        //$s3url = "https://dook-international.sgp1.cdn.digitaloceanspaces.com/dook/images/country/";
        // $s3url = url('/dook/images/country/').'/';
         $s3url = 'https://dooktravels.s3.ap-south-1.amazonaws.com/com/country/';
        return view('country.edit',compact('countries','s3url','major_destinations','selected_destination','departures','departure_bygs'));
    }

    public function countryUpdate(Request $request, $id)
    {
        $country  = Country::find($id);
        if($request->tour_packages_section == "TPS_Checks"){
            $country->title = $request->edit_title;
            $country->sub_title = $request->edit_sub_title;
            $country->slug_url = $request->edit_slug_url;
            if(isset($request->country_exist)){
                $country->country_exist = $request->country_exist;
            }
            $country->text_1 = $request->text_1;
            $country->text_2 = $request->text_2;
            $country->text_3 = $request->text_3;
            $country->text_4 = $request->text_4;
            $country->meta_title = $request->meta_title;
            $country->meta_keywords = $request->meta_keywords;
            $country->meta_description = $request->meta_description;

             if ($request->hasFile('edit_image')) {
                $image = $request->file('edit_image');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/country/' . $originalName;

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

                $country->image = $originalName;
            }
             if ($request->hasFile('edit_banner_image')) {
                $image = $request->file('edit_banner_image');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/country/' . $originalName;

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

                $country->banner_image = $originalName;
            }
             if ($request->hasFile('edit_mobile_banner_image')) {
                $image = $request->file('edit_mobile_banner_image');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/country/' . $originalName;

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

                $country->mobile_banner_image = $originalName;
            }
              if ($request->hasFile('image_1')) {
                $image = $request->file('image_1');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/country/' . $originalName;

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

                $country->image_1 = $originalName;
            }
              if ($request->hasFile('image_2')) {
                $image = $request->file('image_2');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/country/' . $originalName;

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

                $country->image_2 = $originalName;
            }
              if ($request->hasFile('image_3')) {
                $image = $request->file('image_3');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/country/' . $originalName;

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

                $country->image_3 = $originalName;
            }
        }
        elseif($request->tour_packages_section == "ATPS_Checks"){
            $country->about_title = $request->about_title;
            $country->about_sub_title = $request->about_sub_title;
            $country->about_country_slug_url = $request->about_country_slug_url;
            $country->about_description = $request->about_description;
            $country->guide_description = $request->guide_description;
            $country->about_visa_information = $request->about_visa_information;
            $country->tourism_description = $request->tourism_description;
            if(isset($request->visa_on_arrival)){
                $country->visa_on_arrival = $request->visa_on_arrival;
            }
            
            $country->administrative_territorial = $request->administrative_territorial;
            $country->land_boundaries = $request->land_boundaries;
            
            $country->about_meta_title = $request->about_meta_title;
            $country->about_meta_keywords = $request->about_meta_keywords;
            $country->about_meta_description = $request->about_meta_description;
            if($request->major_destinations){
                $array_destination_id = array();
                for($i = 0; $i < count($request->major_destinations); $i++) {
                    if ($request->major_destinations[$i] != '') {
                        array_push($array_destination_id, $request->major_destinations[$i]);
                    }
                }
            }
            else{
                $array_destination_id = [];
            }
            $country->major_destinations = implode(",", $array_destination_id);
            if ($request->hasFile('edit_banner_image_about')) {
                $image = $request->file('edit_banner_image_about');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/country/' . $originalName;

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

                $country->banner_image_about = $originalName;
            }
        //     if($request->edit_banner_image_about){
        //         $image = $request->file('edit_banner_image_about');
        //         // $extension = $image->getClientOriginalExtension();
        //         // $filename = Str::random(7).time() .'.'. $extension;
        //         // $images = Image::make($image);
        //         // Storage::disk('s3')->put('dook/images/country/'.$filename, $images->stream(), 'public');
        //         // $imagecompress = $this->compressToLocal($image, 'dook/images/country', $filename);
        //         // $country->banner_image_about = $filename;  

        //          $ext = 'webp';
        //         $convertImage = Image::make($image)->encode($ext, 60);
        //         $fileName = uniqid().'.'.$ext;
        //         Storage::disk('s3')->put('com/country/'.$fileName, $convertImage);
        //         $country->banner_image_about = $fileName; 
        //     }
        }
        elseif($request->tour_packages_section == "TTPS_Checks"){
            $country->attraction_title = $request->attraction_title;
            $country->attraction_sub_title = $request->attraction_sub_title;
            $country->attraction_heading = $request->attraction_heading;
            $country->attraction_description = $request->attraction_description;
            $country->country_attraction_slug_url = $request->country_attraction_slug_url;
            
            //$country->description = $request->description;
            $country->attraction_meta_title = $request->attraction_meta_title;
            $country->attraction_meta_keywords = $request->attraction_meta_keywords;
            $country->attraction_meta_description = $request->attraction_meta_description;
            
             if ($request->hasFile('edit_banner_image_attraction')) {
                $image = $request->file('edit_banner_image_attraction');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/country/' . $originalName;

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

                $country->banner_image_attraction = $originalName;
            }
            // if($request->edit_banner_image_attraction){
            //     $image = $request->file('edit_banner_image_attraction');
            //     // $extension = $image->getClientOriginalExtension();
            //     // $filename = Str::random(8).time() .'.'. $extension;
            //     // $images = Image::make($image);
            //     // Storage::disk('s3')->put('dook/images/country/'.$filename, $images->stream(), 'public');
            //     // $imagecompress = $this->compressToLocal($image, 'dook/images/country', $filename);
            //     // $country->banner_image_attraction = $filename; 

            //     $ext = 'webp';
            //     $convertImage = Image::make($image)->encode($ext, 60);
            //     $fileName = uniqid().'.'.$ext;
            //     Storage::disk('s3')->put('com/country/'.$fileName, $convertImage);
            //     $country->banner_image_attraction = $fileName;  
            // }
        }
        elseif($request->tour_packages_section == "GTPS_Checks"){
            $country->group_title = $request->group_title;
            $country->group_sub_title = $request->group_sub_title;
            $country->country_group_slug_url = $request->country_group_slug_url;
            
            $country->group_description = $request->group_description;
            $country->group_meta_title = $request->group_meta_title;
            $country->group_meta_keywords = $request->group_meta_keywords;
            $country->group_meta_description = $request->group_meta_description;
            
             if ($request->hasFile('edit_banner_image_group')) {
                $image = $request->file('edit_banner_image_group');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/country/' . $originalName;

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

                $country->edit_banner_image_group = $originalName;
            }
            
            // if($request->edit_banner_image_group){
            //     $image = $request->file('edit_banner_image_group');
            //     // $extension = $image->getClientOriginalExtension();
            //     // $filename = Str::random(8).time() .'.'. $extension;
            //     // $images = Image::make($image);
            //     // Storage::disk('s3')->put('dook/images/country/'.$filename, $images->stream(), 'public');
            //     // $imagecompress = $this->compressToLocal($image, 'dook/images/country', $filename);
            //     // $country->edit_banner_image_group = $filename;  

            //      $ext = 'webp';
            //     $convertImage = Image::make($image)->encode($ext, 60);
            //     $fileName = uniqid().'.'.$ext;
            //     Storage::disk('s3')->put('com/country/'.$fileName, $convertImage);
            //     $country->edit_banner_image_group = $fileName;  
            // }
        }
        elseif($request->tour_packages_section == "VTPS_Checks"){
            $country->visa_title = $request->visa_title;
            $country->visa_sub_title = $request->visa_sub_title;
            $country->country_visa_slug_url = $request->country_visa_slug_url;
            
            $country->visa_description = $request->visa_description;
            $country->visa_meta_title = $request->visa_meta_title;
            $country->visa_meta_keywords = $request->visa_meta_keywords;
            $country->visa_meta_description = $request->visa_meta_description;
            
             if ($request->hasFile('edit_banner_image_visa')) {
                $image = $request->file('edit_banner_image_visa');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/country/' . $originalName;

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

                $country->edit_banner_image_visa = $originalName;
            }
            
            // if($request->edit_banner_image_visa){
            //     $image = $request->file('edit_banner_image_visa');
            //     // $extension = $image->getClientOriginalExtension();
            //     // $filename = Str::random(8).time() .'.'. $extension;
            //     // $images = Image::make($image);
            //     // Storage::disk('s3')->put('dook/images/country/'.$filename, $images->stream(), 'public');
            //     // $imagecompress = $this->compressToLocal($image, 'dook/images/country', $filename);
            //     // $country->edit_banner_image_visa = $filename;  

            //       $ext = 'webp';
            //     $convertImage = Image::make($image)->encode($ext, 60);
            //     $fileName = uniqid().'.'.$ext;
            //     Storage::disk('s3')->put('com/country/'.$fileName, $convertImage);
            //     $country->edit_banner_image_visa = $fileName;  
            // }
        }
        elseif($request->tour_packages_section == "BYGTPS_Checks"){
            $country->before_you_go = $request->before_you_go;
        }
        else{
            $country->experience_title = $request->experience_title;
            $country->experience_sub_title = $request->experience_sub_title;
            $country->country_experience_slug_url = $request->country_experience_slug_url;
            
            $country->experience_description = $request->experience_description;
            $country->experience_meta_title = $request->experience_meta_title;
            $country->experience_meta_keywords = $request->experience_meta_keywords;
            $country->experience_meta_description = $request->experience_meta_description;
            
            if ($request->hasFile('edit_banner_image_experience')) {
                $image = $request->file('edit_banner_image_experience');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/country/' . $originalName;

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

                $country->edit_banner_image_experience = $originalName;
            }

            // if($request->edit_banner_image_experience){
            //     $image = $request->file('edit_banner_image_experience');
            //     // $extension = $image->getClientOriginalExtension();
            //     // $filename = Str::random(8).time() .'.'. $extension;
            //     // $images = Image::make($image);
            //     // Storage::disk('s3')->put('dook/images/country/'.$filename, $images->stream(), 'public');
            //     // $imagecompress = $this->compressToLocal($image, 'dook/images/country', $filename);
            //     // $country->edit_banner_image_experience = $filename;  

            //        $ext = 'webp';
            //     $convertImage = Image::make($image)->encode($ext, 60);
            //     $fileName = uniqid().'.'.$ext;
            //     Storage::disk('s3')->put('com/country/'.$fileName, $convertImage);
            //     $country->edit_banner_image_experience = $fileName;  
            // }
        }
        $country->save();
        $last_id = $country->id;

        //Before you go country Pkg Relation
        if($request->tour_packages_section == "BYGTPS_Checks"){
            if($request->byg_packages || $request->byg_packages != ''){
                BeforeYougoCountryDeparture::where('country_id', $last_id)->delete();
                foreach ($request->byg_packages as $value) {
                    $beforeYouGoRelation = new BeforeYougoCountryDeparture;
                    $beforeYouGoRelation->country_id = $last_id;
                    $beforeYouGoRelation->departure_id = $value;
                    $beforeYouGoRelation->save();
                }
            }
            else{
                BeforeYougoCountryDeparture::where('country_id', $last_id)->delete();
            }
        }

        // Master Slug For Slug Routing
        if(isset($request->edit_slug_url)){
            $slugUnique = SlugMaster::where('country_id', $last_id)
                        ->where('module_name', 'country_tour_page')
                        ->first();
            if($slugUnique){
                $country  = SlugMaster::find($slugUnique->id);
                $country->slug_name = $request->edit_slug_url;
                //$country->module_name = 'country_tour_page';
                $country->save();
            }else{
                $country  = new SlugMaster;
                $country->country_id = $last_id;
                $country->slug_name = $request->edit_slug_url;
                $country->module_name = 'country_tour_page';
                $country->save();
            } 
        }
        if(isset($request->country_attraction_slug_url)){   
            $slugAttrUnique = SlugMaster::where('country_id', $last_id)
                            ->where('module_name', 'country_attraction_page')
                            ->first();
            if($slugAttrUnique){
                $country  = SlugMaster::find($slugAttrUnique->id);
                $country->slug_name = $request->country_attraction_slug_url;
                //$country->module_name = 'country_single_page';
                $country->save();
            }else{
                $country  = new SlugMaster;
                $country->country_id = $last_id;
                $country->slug_name = $request->country_attraction_slug_url;
                $country->module_name = 'country_attraction_page';
                $country->save();
            }
        }
        if(isset($request->country_group_slug_url)){   
            $slugAttrUnique = SlugMaster::where('country_id', $last_id)
                            ->where('module_name', 'country_group_page')
                            ->first();
            if($slugAttrUnique){
                $country  = SlugMaster::find($slugAttrUnique->id);
                $country->slug_name = $request->country_group_slug_url;
                //$country->module_name = 'country_single_page';
                $country->save();
            }else{
                $country  = new SlugMaster;
                $country->country_id = $last_id;
                $country->slug_name = $request->country_group_slug_url;
                $country->module_name = 'country_group_page';
                $country->save();
            }
        }
        if(isset($request->country_experience_slug_url)){   
            $slugAttrUnique = SlugMaster::where('country_id', $last_id)
                            ->where('module_name', 'country_experience_page')
                            ->first();
            if($slugAttrUnique){
                $country  = SlugMaster::find($slugAttrUnique->id);
                $country->slug_name = $request->country_experience_slug_url;
                //$country->module_name = 'country_single_page';
                $country->save();
            }else{
                $country  = new SlugMaster;
                $country->country_id = $last_id;
                $country->slug_name = $request->country_experience_slug_url;
                $country->module_name = 'country_experience_page';
                $country->save();
            }
        }
        $status = [
                'url'=> url('/countries'),
            ];
        return response()->json($status);
    }

    public function countryDisable(Request $request, $id)
    {
        $destination  = Country::find($id);
        if($destination->status == 1){
            $destination->status = 0;
            $destination->save();
        }
        else{
            $destination->status = 1;
            $destination->save();
        }
        
        return response()->json(['success'=>'Success!']);
    }

    public function countryMeghaMenu(Request $request, $id)
    {
        $country  = Country::find($id);
        if($country->megha_menu == 0){
            $country->megha_menu = 1;
            $country->save();
            return redirect()->back()->with('success', 'Country added to mega menu successfully!');
        }
        else{
            $country->megha_menu = 0;
            $country->save();
            return redirect()->back()->with('success', 'Country remove from mega menu successfully!');
        }
    }

    public function makeTopCountry(Request $request, $id)
    {
        $destination  = Destination::find($id);
        if($destination->most_popular == 1){
            $destination->most_popular = 0;
            $destination->save();
        }
        else{
            $destination->most_popular = 1;
            $destination->save();
        }
        
        return response()->json(['success'=>'Success!']);
    }
}
