<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageServiceProvider;
use DB;
use Storage;
use Image;
use Auth;
use App\Departure;
use App\Destination;
use App\Country;
use App\SlugMaster;
use App\DepartureDestination;
use App\Experience;
use App\DestinationExperience;
use App\Region;
use App\RegionExperience;
use App\RegionCountry;
use App\Traits\TinyPngImageCompress;
use Google\Cloud\Storage\StorageClient;

class RegionController extends Controller
{
    use TinyPngImageCompress;
    
    public function regionIndex(Request $request){
        $keywords = $request->keyword;
        if($keywords != ''){
           $regions = Region::where('region_name', 'LIKE','%'.$keywords.'%')
                            ->orderBy('grid_number','ASC')
                            ->get();
        }else{
            $regions = Region::orderBy('grid_number','ASC')->get();              
        }
        
        $total = count($regions);
        //$urlS3 = "https://dook-international.sgp1.cdn.digitaloceanspaces.com/dook/images/region/";
        // $urlS3 = url('/dook/images/region/').'/';
        $urlS3 = 'https://dooktravels.s3.ap-south-1.amazonaws.com/com/region/';
        if($request->ajax()){
                return view('region.region_data_list',compact('regions','total','urlS3','keywords'));
            }
        return view('region.region_index',compact('regions','total','urlS3','keywords'));
    }

     public function regionEdit(Request $request, $id){

        $region = Region::where('id',$id)->first();
        //dd($id);
        $data = Country::all();
        $country = [];
        foreach ($data as $key => $value) {
            $region_arr = explode(',', $value->region_id);
            foreach ($region_arr as $region_value) {
                if($region_value == $id ){
                    $country[] = ['id' => $value->id, 'country_name' => $value->country_name];
                }
            }
        }
        $all_countries = [];
        foreach ($country as $key => $value) {
           $all_countries[] = ['id' => $value['id'], 'country_name' => $value['country_name']];
        }
        $countries = json_decode(json_encode($all_countries));
    	// $region = Region::where('id',$id)->first();
    	// $countries = Country::where('region_id', $id)->select('id','country_name')->get();
    	$region_country = RegionCountry::where('region_id', $id)->get();
        $region_experience = RegionExperience::where('region_id', $id)->get();
    	$experiences = DB::table('experiences')
    						->distinct()
            				->orderBy('experience_name','ASC')
                			->get();
        //$urlS3 = "https://dook-international.sgp1.cdn.digitaloceanspaces.com/dook/images/region/";
        // $urlS3 = url('/dook/images/region/').'/';
        $urlS3 = 'https://dooktravels.s3.ap-south-1.amazonaws.com/com/region/';
        return view('region.region_edit',compact('experiences','region','urlS3','countries','region_country','region_experience'));
    }

    public function regionUpdate(Request $request, $id)
    {   
    	$data = $request->all();
    	
    	$regions = Region::find($id);
    	$regions->label_name = $request->label_name;
        $regions->slug_url = $request->slug_url;
        $regions->grid_number = $request->grid_number;
        $regions->sub_title = $request->sub_title;
        $regions->description = $request->description;
        $regions->meta_title = $request->meta_title;
        $regions->meta_keywords = $request->meta_keywords;
        $regions->meta_description = $request->meta_description;

         if ($request->hasFile('region_image')) {
                $image = $request->file('region_image');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/region/' . $originalName;

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

                $regions->image = $originalName;
            }

        // if($request->region_image){
	    //     $image = $request->file('region_image');
	    //     // $extension = $image->getClientOriginalExtension();
	    //     // $filename = Str::random(5).time() . '.'. $extension;
	    //     // $images = Image::make($image);
	    //     // Storage::disk('s3')->put('dook/images/region/'.$filename, $images->stream(), 'public');
        //     // $imagecompress = $this->compressToLocal($image, 'dook/images/region', $filename);
	    //     // $regions->image = $filename;  

        //      $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/region/'.$fileName, $convertImage);
        //      $regions->image = $fileName; 
	    // }

           if ($request->hasFile('region_banner_image')) {
                $image = $request->file('region_banner_image');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/region/' . $originalName;

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

                $regions->banner_image = $originalName;
            }
              
        // if($request->region_banner_image){
        //     $image = $request->file('region_banner_image');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $filename = Str::random(5).time() . '.'. $extension;
        //     // $images = Image::make($image);
        //     // Storage::disk('s3')->put('dook/images/region/'.$filename, $images->stream(), 'public');
        //     // $imagecompress = $this->compressToLocal($image, 'dook/images/region', $filename);
        //     // $regions->banner_image = $filename;  

        //     $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/region/'.$fileName, $convertImage);
        //      $regions->banner_image = $fileName; 
        // }
        $regions->save();
        $last_id = $regions->id;

        $country = $request->countryId;
        if($country){
        	RegionCountry::where('region_id', $last_id)->delete();
        	foreach ($country as $key => $value) {
                $n = 1; 
                $start = strlen($value) - $n;
                $str1 = ''; 
                for ($x = $start; $x < strlen($value); $x++) { 
                    $str1 .= $value[$x]; 
                }
        		$country_regions  = new RegionCountry;
                $country_id = substr($value, 0, strlen($value)-1);
		        $country_regions->region_id = $last_id;
		        $country_regions->country_id = $country_id;
		        $country_regions->country_name = $request->countryName[$str1];
		        $country_regions->save();
        	}
        }
        $expId = $request->experiencesId;
        if($expId){
        	RegionExperience::where('region_id', $last_id)->delete();
        	foreach ($expId as $key => $values) {
                $n = 1; 
                $start = strlen($values) - $n;
                $str2 = ''; 
                for ($x = $start; $x < strlen($values); $x++) { 
                    $str2 .= $values[$x]; 
                }
        		$exp_regions  = new RegionExperience;
                $exp_id = substr($values, 0, strlen($values)-1);
                $exp_regions->region_id = $last_id;
		        $exp_regions->experience_id = $exp_id;
		        $exp_regions->experience_name = $request->experiencesName[$str2];
		        $exp_regions->save();
        	}
        }
        $slugUnique = SlugMaster::where('region_id', $last_id)
                        ->where('module_name', 'region_single_page')
                        ->first();
        if($slugUnique){
            $country  = SlugMaster::find($slugUnique->id);
            $country->slug_name = $request->slug_url;
            //$country->module_name = 'country_single_page';
            $country->save();
        }else{
            $country  = new SlugMaster;
            $country->region_id = $last_id;
            $country->slug_name = $request->slug_url;
            $country->module_name = 'region_single_page';
            $country->save();
        }
	        $status = [
	                'url'=> url('/regions'),
	            ];
        return response()->json($status);
    }

    public function getTopCountriesAjax(Request $request)
    {
        $region_id = $request->region_id;
        
        $countries = DB::table('countries')
          					->where('region_id',$region_id)
            				->select("id","country_name")
                			->get();
            return response()->json($countries);
    }

    public function regionMegaMenu(Request $request, $id)
    {
        $region  = Region::find($id);
        if($region->mega_menu == 0){
            $region->mega_menu = 1;
            $region->save();
            return redirect()->back()->with('success', 'Region added to mega menu successfully!');
        }
        else{
            $region->mega_menu = 0;
            $region->save();
            return redirect()->back()->with('success', 'Region remove from mega menu successfully!');
        }
    }

}
