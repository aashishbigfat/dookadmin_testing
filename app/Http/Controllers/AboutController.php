<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Image;
use Storage;
use App\About;
use App\Departure;
use App\Country;
use App\Destination;
use App\Experience;
use App\Activity;
use App\Region;
use DB;
use App\Traits\TinyPngImageCompress;
use Google\Cloud\Storage\StorageClient;


class AboutController extends Controller
{
    use TinyPngImageCompress;
    public function index(Request $request)
    {
        // $urlS3 = url('/dook/images/about').'/';
        $urlS3 = 'https://dooktravels.s3.ap-south-1.amazonaws.com/com/about/';
    	$about = About::latest()->first();
    	return view('about_us.index',compact('about','urlS3'));
    }

    public function aboutUpdate(Request $request)
    {
        $about  = About::find($request->edit_id);
        $about->banner_title = $request->banner_title;
        $about->banner_sub_title = $request->banner_sub_title;
        $about->heading = $request->heading;
        $about->sub_heading = $request->sub_heading;
        $about->description = $request->description;
        $about->box1_title = $request->box1_title;
        $about->box1_description = $request->box1_description;
        $about->box2_title = $request->box2_title;
        $about->box2_description = $request->box2_description;
        $about->box3_title = $request->box3_title;
        $about->box3_description = $request->box3_description;
        $about->box4_title = $request->box4_title;
        $about->box4_description = $request->box4_description;
        $about->meta_title = $request->meta_title;
        $about->meta_keywords = $request->meta_keywords;
        $about->meta_description = $request->meta_description;

         if ($request->hasFile('image')) {
                $image = $request->file('image');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/about/' . $originalName;

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

                $about->image = $originalName;
            }

        // if($request->image){
        //     $image = $request->file('image');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $filename = Str::random(5).time() .'.'. $extension;

        //     // $imagecompress = $this->compressToLocal($image, 'dook/images/about', $filename);
        //     // $about->image = $filename;  
        //     //Storage::disk('s3')->put('dook/images/about/'.$imageName, $images->stream(), 'public'); 

        //      $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/about/'.$fileName, $convertImage);
        //     $about->image = $fileName;
        // }
            if ($request->hasFile('banner_image')) {
                $image = $request->file('banner_image');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/about/' . $originalName;

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

                $about->banner_image = $originalName;
            }
        // if($request->banner_image){
        //     $image = $request->file('banner_image');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $filename = Str::random(5).time() .'.'. $extension;
        //     // $images = Image::make($image);
        //     // $imagecompress = $this->compressToLocal($image, 'dook/images/about', $filename);
        //     // $about->banner_image = $filename;  

        //     $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/about/'.$fileName, $convertImage);
        //     $about->banner_image = $fileName;
        // }
        $about->save();
        $last_id = $about->id;
        
        $status = [
            'url'=> url('/about-us'),
        ];
        return response()->json($status);
    }
    public function departureUrlMake(Request $request)
    {
        $dep  = Departure::select('id','slug_url_pre','slug_url','dep_dook_ref_id','title','no_of_nights','meta_title','meta_keywords','meta_description')->get();
        foreach ($dep as $value) {
            $about  = New About;
            $about->url = "https://www.dookinternational.com/".$value->slug_url_pre.'/'.$value->slug_url.'/'.$value->dep_dook_ref_id;
            $about->name = $value->title;
            $about->nights = $value->no_of_nights;
            $about->meta_title = $value->meta_title;
            $about->meta_keywords = $value->meta_keywords;
            $about->meta_description = $value->meta_description;
            $about->dep_id = $value->dep_dook_ref_id;
            $about->save();
        }
        
        
        $status = [
            'url'=> url('/about'),
        ];
        return response()->json($status);
    }

    // public function countryUrlMake(Request $request)
    // {
    //     $dep  = Country::select('country_name','slug_url','about_country_slug_url','country_attraction_slug_url','country_visa_slug_url','country_experience_slug_url','country_group_slug_url')->get();
    //     foreach ($dep as $value) {
    //         $about  = New About;
    //          $about->country = $value->country_name;
    //         $about->country_url = "https://www.dookinternational.com/".$value->slug_url;
    //         $about->about_country_url = "https://www.dookinternational.com/about/".$value->about_country_slug_url;
    //         $about->country_attraction_url = "https://www.dookinternational.com/".$value->country_attraction_slug_url;
    //         $about->country_visa_url = "https://www.dookinternational.com/visa/".$value->country_visa_slug_url;
    //         $about->country_experience_url = "https://www.dookinternational.com/".$value->country_experience_slug_url;
    //         $about->country_group_url = "https://www.dookinternational.com/".$value->country_group_slug_url;
    //         $about->save();
    //     }
        
        
    //     $status = [
    //         'url'=> url('/about'),
    //     ];
    //     return response()->json($status);
    // }

    // public function destUrlMake(Request $request)
    // {
    //     $dep  = Destination::select('dest_name','slug_url')->get();
    //     foreach ($dep as $value) {
    //         $about  = New About;
    //         $about->destination = $value->dest_name;
    //         $about->departure_url = "https://www.dookinternational.com/destinations/".$value->slug_url;

    //         $about->save();
    //     }
        
        
    //     $status = [
    //         'url'=> url('/about'),
    //     ];
    //     return response()->json($status);
    // }
    // public function expUrlMake(Request $request)
    // {
    //     $dep  = Experience::select('slug_url')->get();
    //     foreach ($dep as $value) {
    //         $about  = New About;
    //         $about->departure_url = "https://www.dookinternational.com/".$value->slug_url;

    //         $about->save();
    //     }
        
        
    //     $status = [
    //         'url'=> url('/about'),
    //     ];
    //     return response()->json($status);
    // }

    // public function actUrlMake(Request $request)
    // {
    //     $dep  = Activity::select('slug_url')->get();
    //     foreach ($dep as $value) {
    //         $about  = New About;
    //         $about->departure_url = "https://www.dookinternational.com/activities/".$value->slug_url;

    //         $about->save();
    //     }
        
        
    //     $status = [
    //         'url'=> url('/about'),
    //     ];
    //     return response()->json($status);
    // }

    // public function regionUrlMake(Request $request)
    // {
    //     $dep  = Region::select('slug_url')->get();
    //     foreach ($dep as $value) {
    //         $about  = New About;
    //         $about->departure_url = "https://www.dookinternational.com/".$value->slug_url;

    //         $about->save();
    //     }
        
        
    //     $status = [
    //         'url'=> url('/about'),
    //     ];
    //     return response()->json($status);
    // }

    public function makePoiUrls(Request $request)
    {
        $poi  = DB::table('departure_destination_point_of_interests')
                ->distinct()
                ->select('reference_id','poi_name')
                ->get();
            foreach ($poi as $value) {
                $mainstrs = str_replace( array('\'', '"',',' , ';', '<', '>','&','$','(',')','}','{','[',']','%','+','_','.','^','#','@','*','’'), '', $value->poi_name);
                $strlower = Str::lower($mainstrs);
                $arr = explode(' ', $strlower);
                $str = implode('-', $arr);
                $mainstr = str_replace( array('--', '---','----'), '-', $str);

                $about  = New About;
                $about->name = $value->poi_name;
                $about->poi_id = $value->reference_id;
                $about->url = "https://www.dookinternational.com/poi/".$mainstr.'/'.$value->reference_id;
                $about->save();
            }
        $status = [
            'url'=> "Success!.",
        ];
        return response()->json($status);
    }
}
