<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageServiceProvider;
use App\HomeSetting;
use App\HomeActivitySetting;
use App\HomeExpPackageSetting;
use DB;
use Storage;
use Image;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Log;

class HomeSettingController extends Controller
{
    public function getActivities(Request $request)
    {
        $data = [];
        if($request->has('q')){
            $search = $request->q;
            $data = DB::table('activities')->select("id","activity_name")
                        ->where('activity_name','LIKE',"%$search%")
                        ->get(20);
            }
        else{
            $data = DB::table('activities')
                        ->select("id","activity_name")
                        ->limit(10)
                        ->get();
        }
        return response()->json($data);
    }

    public function create(Request $request){
        $settings = HomeSetting::first();
        $regions = DB::table('regions')->get();
        $experiences = DB::table('experiences')->get();
        $countries = DB::table('countries')->get();
        $activities = DB::table('home_activity_settings')->orderBy('orders','ASC')->get();
        foreach ($activities as $key => $value) {
            $activity = DB::table('activities')->where('id',$value->activity_id)->first();
            $value->name = $activity->activity_name;
        }
        $honeymonnPkgId = DB::table('destination_experiences')
                ->where('experience_id', 3)
                ->distinct()
                ->pluck('departure_id')
                ->toArray();
        $honeymonnPkg = DB::table('departures')
                ->whereIn('id',$honeymonnPkgId)
                ->select('id','title')
                ->get();
        $pkg_array = explode(',',$settings->experiences);
        $fivePkgExp = HomeExpPackageSetting::select('id','package','package_image')->get();
        $s3url = generateSignedUrl('home/');
        if($settings){
            return view('home_setting.edit',compact('regions','experiences','countries','settings','activities','honeymonnPkg','pkg_array','fivePkgExp','s3url'));
        }else{
            return view('home_setting.create',compact('regions','experiences','countries'));
        }
    }
    public function homeActivitypositionShifting(Request $request){
        if($request->position)
        {
            $pos = $request->position;
            $i = 0;
            foreach($pos as $k=>$v)
            {
                $i++;
                $item = HomeActivitySetting::find($v);
                $item->orders = $i;
                $item->save();
            }
            exit;
            return response()->json([
               'success' => "success"
           ]);
        }
        else
        {
            return response()->json([
               'success' => "false"
           ]);
        }
     }
    public function homeBannerStore(Request $request)
    {   
        $store = new HomeSetting;
        $store->banner_title = $request->banner_title;
        $store->banner_sub_title = $request->banner_sub_title;
        $store->counter1 = $request->counter1;
        $store->text1 = $request->text1;
        $store->counter2 = $request->counter2;

        $store->text2 = $request->text2;
        $store->counter3 = $request->counter3;
        $store->text3 = $request->text3;
        $store->counter4 = $request->counter4;
        $store->text4 = $request->text4;
         if ($request->hasFile('slider_image')) {
                $image = $request->file('slider_image');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/home/' . $originalName;

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

                $store->image = $originalName;
            }
        // if($request->slider_image){
        //     $image = $request->file('slider_image');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $imageName = Str::random(5).time() .'.'. $extension;
        //     // $relPath = 'dook/images/home/';
        //     //     if (!file_exists(public_path($relPath))) {
        //     //         mkdir(public_path($relPath), 777, true);
        //     //     }
        //     // $img = Image::make($image)->save( public_path($relPath . $imageName ) ); 
        //     // $store->image = $imageName; 


        //     $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/home/'.$fileName, $convertImage);
        //     $store->image = $imageName; 
        // }

         if ($request->hasFile('mobile_image')) {
                $image = $request->file('mobile_image');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/home/' . $originalName;

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

                $store->mobile_image = $originalName;
            }

        // if($request->mobile_image){
        //     $image = $request->file('mobile_image');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $imageName = Str::random(5).time() .'.'. $extension;
        //     // $relPath = 'dook/images/home/';
        //     //     if (!file_exists(public_path($relPath))) {
        //     //         mkdir(public_path($relPath), 777, true);
        //     //     }
        //     // $img = Image::make($image)->save( public_path($relPath . $imageName ) ); 
        //     // $store->mobile_image = $imageName; 


        //      $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 100);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/home/'.$fileName, $convertImage);
        //     $store->mobile_image = $imageName; 
        // }
        
        $store->save();
        return redirect()->back();
    }
    public function homeBannerUpdate(Request $request)
    {   
        $store = HomeSetting::find($request->data_id);
        $store->banner_title = $request->banner_title;
        $store->banner_sub_title = $request->banner_sub_title;
        $store->counter1 = $request->counter1;
        $store->text1 = $request->text1;
        $store->counter2 = $request->counter2;

        $store->text2 = $request->text2;
        $store->counter3 = $request->counter3;
        $store->text3 = $request->text3;
        $store->counter4 = $request->counter4;
        $store->text4 = $request->text4;
         if ($request->hasFile('slider_image')) {
                $image = $request->file('slider_image');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/home/' . $originalName;

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

                $store->image = $originalName;
            }
              if ($request->hasFile('mobile_image')) {
                $image = $request->file('mobile_image');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/home/' . $originalName;

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

                $store->mobile_image = $originalName;
            }
        // if($request->slider_image){
        //     $image = $request->file('slider_image');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $imageName = Str::random(5).time() .'.'. $extension;
        //     // $relPath = 'dook/images/home/';
        //     //     if (!file_exists(public_path($relPath))) {
        //     //         mkdir(public_path($relPath), 777, true);
        //     //     }
        //     // $img = Image::make($image)->save( public_path($relPath . $imageName ) ); 
        //     // $store->image = $imageName; 

        //       $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/home/'.$fileName, $convertImage);
        //     $store->image = $fileName; 
        // }
        // if($request->mobile_image){
        //     $image = $request->file('mobile_image');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $imageName = Str::random(5).time() .'.'. $extension;
        //     // $relPath = 'dook/images/home/';
        //     //     if (!file_exists(public_path($relPath))) {
        //     //         mkdir(public_path($relPath), 777, true);
        //     //     }
        //     // $img = Image::make($image)->save( public_path($relPath . $imageName ) ); 
        //     // $store->mobile_image = $imageName; 

        //     $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/home/'.$fileName, $convertImage);
        //     $store->mobile_image = $fileName; 
        // }
        
        $store->save();
        $status = [
            'msg'=> "Success",
        ];
        return response()->json($status);
    }
    public function homeExperienceUpdate(Request $request)
    {   

        $store = HomeSetting::find($request->data_id);
        //$store->experiences = $packages;
        $store->experience1 = $request->experience1;
         if ($request->hasFile('exp_image1')) {
                $image = $request->file('exp_image1');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/home/' . $originalName;

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

                $store->exp_image1 = $originalName;
            }
        // if($request->exp_image1){
        //     $image = $request->file('exp_image1');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $imageName = Str::random(5).time() .'.'. $extension;
        //     // $relPath = 'dook/images/home/';
        //     //     if (!file_exists(public_path($relPath))) {
        //     //         mkdir(public_path($relPath), 777, true);
        //     //     }
        //     // $img = Image::make($image)->save( public_path($relPath . $imageName ) ); 
        //     // $store->exp_image1 = $imageName; 

        //     $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/home/'.$fileName, $convertImage);
        //       $store->exp_image1 = $fileName;
        // }
        $store->experience2 = $request->experience2;
         if ($request->hasFile('exp_image2')) {
                $image = $request->file('exp_image2');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/home/' . $originalName;

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

                $store->exp_image2 = $originalName;
            }
        // if($request->exp_image2){
        //     $image = $request->file('exp_image2');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $imageName = Str::random(5).time() .'.'. $extension;
        //     // $relPath = 'dook/images/home/';
        //     //     if (!file_exists(public_path($relPath))) {
        //     //         mkdir(public_path($relPath), 777, true);
        //     //     }
        //     // $img = Image::make($image)->save( public_path($relPath . $imageName ) ); 
        //     // $store->exp_image2 = $imageName; 


        //      $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/home/'.$fileName, $convertImage);
        //      $store->exp_image2 = $fileName; 

        // }
        $store->experience3 = $request->experience3;
         if ($request->hasFile('exp_image3')) {
                $image = $request->file('exp_image3');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/home/' . $originalName;

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

                $store->exp_image3 = $originalName;
            }
        // if($request->exp_image3){
        //     $image = $request->file('exp_image3');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $imageName = Str::random(5).time() .'.'. $extension;
        //     // $relPath = 'dook/images/home/';
        //     //     if (!file_exists(public_path($relPath))) {
        //     //         mkdir(public_path($relPath), 777, true);
        //     //     }
        //     // $img = Image::make($image)->save( public_path($relPath . $imageName ) ); 
        //     // $store->exp_image3 = $imageName; 

        //       $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/home/'.$fileName, $convertImage);
        //    $store->exp_image3 = $fileName;
        // }
        $store->experience4 = $request->experience4;
         if ($request->hasFile('exp_image4')) {
                $image = $request->file('exp_image4');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/home/' . $originalName;

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

                $store->exp_image4 = $originalName;
            }
             $store->experience5 = $request->experience5;
         if ($request->hasFile('exp_image5')) {
                $image = $request->file('exp_image5');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/home/' . $originalName;

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

                $store->exp_image5 = $originalName;
            }
        // if($request->exp_image4){
        //     $image = $request->file('exp_image4');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $imageName = Str::random(5).time() .'.'. $extension;
        //     // $relPath = 'dook/images/home/';
        //     //     if (!file_exists(public_path($relPath))) {
        //     //         mkdir(public_path($relPath), 777, true);
        //     //     }
        //     // $img = Image::make($image)->save( public_path($relPath . $imageName ) ); 
        //     // $store->exp_image4 = $imageName; 


        //     $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/home/'.$fileName, $convertImage);
        //    $store->exp_image4 = $fileName;
        // }
        $store->save();

        if($request->packages){
            foreach ($request->pkg_id as $key => $value) {
                if(isset($request->packages[$key]) != ""){
                    $storePkg = HomeExpPackageSetting::find($value);
                    $storePkg->package = $request->packages[$key];
                    if(isset($request->package_image[$key])){
                        $image = $request->package_image[$key];
                        // $extension = $image->getClientOriginalExtension();
                        // $imageName = Str::random(5).time() .'.'. $extension;
                        // $relPath = 'dook/images/home/';
                        //     if (!file_exists(public_path($relPath))) {
                        //         mkdir(public_path($relPath), 777, true);
                        //     }
                        // $img = Image::make($image)->save( public_path($relPath . $imageName ) ); 
                        // $storePkg->package_image = $imageName; 

                         $ext = 'webp';
                        $convertImage = Image::make($image)->encode($ext, 60);
                        $fileName = uniqid().'.'.$ext;
                        Storage::disk('s3')->put('com/home/'.$fileName, $convertImage);
                       $storePkg->package_image = $fileName; 
                    }
                    $storePkg->save();
                }
            }
        }
        $status = [
            'msg'=> "Success",
        ];
        return response()->json($status);
    }
    public function homeActivityUpdate(Request $request)
    {   
        if($request->act_heading){
            $storeAct = HomeSetting::find($request->data_id);
            $storeAct->act_heading = $request->act_heading;
            $storeAct->act_sub_heading = $request->act_sub_heading;
            if ($request->hasFile('activity_banner')) {
                $image = $request->file('activity_banner');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/home/' . $originalName;

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

                $storeAct->activity_banner = $originalName;
            }
            // if($request->activity_banner){
            //     $image = $request->file('activity_banner');
            //     // $extension = $image->getClientOriginalExtension();
            //     // $imageName = Str::random(5).time() .'.'. $extension;
            //     // $relPath = 'dook/images/home/';
            //     //     if (!file_exists(public_path($relPath))) {
            //     //         mkdir(public_path($relPath), 777, true);
            //     //     }
            //     // $img = Image::make($image)->save( public_path($relPath . $imageName ) ); 
            //     // $storeAct->activity_banner = $imageName; 

            //       $ext = 'webp';
            //     $convertImage = Image::make($image)->encode($ext, 60);
            //     $fileName = uniqid().'.'.$ext;
            //     Storage::disk('s3')->put('com/home/'.$fileName, $convertImage);
            //    $storeAct->activity_banner = $fileName; 
            // }

            $storeAct->save();
        }
        if($request->activity == ""){

        }else{
            $store = new HomeActivitySetting;
            $store->activity_id = $request->activity;
             if ($request->hasFile('activity_image')) {
                $image = $request->file('activity_image');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/home/' . $originalName;

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

                $storeAct->activity_image = $originalName;
            }
            // if($request->activity_image){
            //     $image = $request->file('activity_image');
            //     // $extension = $image->getClientOriginalExtension();
            //     // $imageName = Str::random(5).time() .'.'. $extension;
            //     // $relPath = 'dook/images/home/';
            //     //     if (!file_exists(public_path($relPath))) {
            //     //         mkdir(public_path($relPath), 777, true);
            //     //     }
            //     // $img = Image::make($image)->save( public_path($relPath . $imageName ) ); 
            //     // $store->image = $imageName; 

            //     $ext = 'webp';
            //     $convertImage = Image::make($image)->encode($ext, 60);
            //     $fileName = uniqid().'.'.$ext;
            //     Storage::disk('s3')->put('com/home/'.$fileName, $convertImage);
            //     $store->image = $fileName; 
            // }
            $store->save();
            $activity = DB::table('activities')->where('id',$store->activity_id)->select('id','activity_name')->first();
            $activity->name = $activity->activity_name;
            $activity->image = $store->image;
            $count = DB::table('home_activity_settings')->count();
        }
        $activity ='';
        $count = 0;
        $status = [
            'msg'=> $activity,
            'count' =>$count,
        ];
        return response()->json($status);
    }
    public function homeActivityUpdateImg(Request $request)
    {   
        $store = HomeActivitySetting::find($request->act_id);
        //dd($request->act_id);
         if ($request->hasFile('activityImg')) {
                $image = $request->file('activityImg');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/home/' . $originalName;

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

                $store->image = $originalName;
            }
        // if($request->activityImg){
        //     $image = $request->file('activityImg');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $imageName = Str::random(5).time() .'.'. $extension;
        //     // $relPath = 'dook/images/home/';
        //     //     if (!file_exists(public_path($relPath))) {
        //     //         mkdir(public_path($relPath), 777, true);
        //     //     }
        //     // $img = Image::make($image)->save( public_path($relPath . $imageName ) ); 
        //     // $store->image = $imageName; 

        //        $ext = 'webp';
        //         $convertImage = Image::make($image)->encode($ext, 60);
        //         $fileName = uniqid().'.'.$ext;
        //         Storage::disk('s3')->put('com/home/'.$fileName, $convertImage);
        //         $store->image = $fileName; 
        // }
        $store->save();
       
        
        $status = [
            'msg'=> 'Success',
        ];
        return response()->json($status);
    }
  public function homeCountryUpdate(Request $request)
{   
    $store = HomeSetting::find($request->data_id);
    
    $store->country1 = $request->country1;
    $store->label1 = $request->label1;
     if ($request->hasFile('country_image1')) {
        $image = $request->file('country_image1');
        $originalName = $image->getClientOriginalName(); // original file name with extension
        $path = 'com/home/' . $originalName;

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

        $store->country_image1 = $originalName;
    }

    // if ($request->hasFile('country_image1')) {
    //     $image = $request->file('country_image1');
    //     $fileName = uniqid() . '.' . $image->getClientOriginalExtension(); // Retain original extension
    //     $gcsPath = 'home/' . $fileName; // GCS path

    //     // Upload image to Google Cloud Storage
    //     $uploadResult = uploadToGoogleCloud($image->getRealPath(), $gcsPath);
    //     if ($uploadResult) {
    //         $store->country_image1 = $fileName; // Store the file name in the database
    //     }
    // }

    $store->country2 = $request->country2;
    $store->label2 = $request->label2;

      if ($request->hasFile('country_image2')) {
        $image = $request->file('country_image2');
        $originalName = $image->getClientOriginalName(); // original file name with extension
        $path = 'com/home/' . $originalName;

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

        $store->country_image2 = $originalName;
    }


    // if ($request->hasFile('country_image2')) {
    //     $image = $request->file('country_image2');
    //     $fileName = uniqid() . '.' . $image->getClientOriginalExtension(); // Retain original extension
    //     $gcsPath = 'home/' . $fileName; // GCS path

    //     // Upload image to Google Cloud Storage
    //     $uploadResult = uploadToGoogleCloud($image->getRealPath(), $gcsPath);  
    //     if ($uploadResult) {
    //         $store->country_image2 = $fileName; // Store the file name in the database
    //     }
    // }

    $store->country3 = $request->country3;
    $store->label3 = $request->label3;

    if ($request->hasFile('country_image3')) {
        $image = $request->file('country_image3');
        $originalName = $image->getClientOriginalName(); // original file name with extension
        $path = 'com/home/' . $originalName;

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

        $store->country_image3 = $originalName;
    }

    // if ($request->hasFile('country_image3')) {
    //     $image = $request->file('country_image3');
    //     $fileName = uniqid() . '.' . $image->getClientOriginalExtension(); // Retain original extension
    //     $gcsPath = 'com/home/' . $fileName; // GCS path

    //     // Upload image to Google Cloud Storage
    //     $uploadResult = uploadToGoogleCloud($image->getRealPath(), $gcsPath);
    //     if ($uploadResult) {
    //         $store->country_image3 = $fileName; // Store the file name in the database
    //     }
    // }

    $store->country4 = $request->country4;
    $store->label4 = $request->label4;
    if ($request->hasFile('country_image4')) {
        $image = $request->file('country_image4');
        $originalName = $image->getClientOriginalName(); // original file name with extension
        $path = 'com/home/' . $originalName;

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

        $store->country_image4 = $originalName;
    }

    // if ($request->hasFile('country_image4')) {
    //     $image = $request->file('country_image4');
    //     $fileName = uniqid() . '.' . $image->getClientOriginalExtension(); // Retain original extension
    //     $gcsPath = 'com/home/' . $fileName; // GCS path

    //     // Upload image to Google Cloud Storage
    //     $uploadResult = uploadToGoogleCloud($image->getRealPath(), $gcsPath);
    //     if ($uploadResult) {
    //         $store->country_image4 = $fileName; // Store the file name in the database
    //     }
    // }

    $store->country5 = $request->country5;
    $store->label5 = $request->label5;
    if ($request->hasFile('country_image5')) {
        $image = $request->file('country_image5');
        $originalName = $image->getClientOriginalName(); // original file name with extension
        $path = 'com/home/' . $originalName;

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

        $store->country_image5 = $originalName;
    }
    // if ($request->hasFile('country_image5')) {
    //     $image = $request->file('country_image5');
    //     $fileName = uniqid() . '.' . $image->getClientOriginalExtension(); // Retain original extension
    //     $gcsPath = 'com/home/' . $fileName; // GCS path

    //     // Upload image to Google Cloud Storage
    //     $uploadResult = uploadToGoogleCloud($image->getRealPath(), $gcsPath);
    //     if ($uploadResult) {
    //         $store->country_image5 = $fileName; // Store the file name in the database
    //     }
    // }

    $store->save();

    $status = [
        'msg' => "Success",
    ];

    return response()->json($status);
}
    public function homeRegionUpdate(Request $request)
    {   
        $store = HomeSetting::find($request->data_id);
        $store->region_heading = $request->region_heading;
        $store->region_description = $request->region_description;
        $store->region1 = $request->region1;
        $store->region_des1 = $request->region_des1;

        if ($request->hasFile('region_image1')) {
            $image = $request->file('region_image1');
            $originalName = $image->getClientOriginalName(); // original file name with extension
            $path = 'com/home/' . $originalName;

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

            $store->region_image1 = $originalName;
        }

        // if($request->region_image1){
        //     $image = $request->file('region_image1');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $imageName = Str::random(5).time() .'.'. $extension;
        //     // $relPath = 'dook/images/home/';
        //     //     if (!file_exists(public_path($relPath))) {
        //     //         mkdir(public_path($relPath), 777, true);
        //     //     }
        //     // $img = Image::make($image)->save( public_path($relPath . $imageName ) ); 
        //     // $store->region_image1 = $imageName; 


        //     $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/home/'.$fileName, $convertImage);
        //    $store->region_image1 = $fileName; 
        // }
        $store->region2 = $request->region2;
        $store->region_des2 = $request->region_des2;

        if ($request->hasFile('region_image2')) {
            $image = $request->file('region_image2');
            $originalName = $image->getClientOriginalName(); // original file name with extension
            $path = 'com/home/' . $originalName;

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

            $store->region_image2 = $originalName;
        }
        // if($request->region_image2){
        //     $image = $request->file('region_image2');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $imageName = Str::random(5).time() .'.'. $extension;
        //     // $relPath = 'dook/images/home/';
        //     //     if (!file_exists(public_path($relPath))) {
        //     //         mkdir(public_path($relPath), 777, true);
        //     //     }
        //     // $img = Image::make($image)->save( public_path($relPath . $imageName ) ); 
        //     // $store->region_image2 = $imageName; 

        //      $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/home/'.$fileName, $convertImage);
        //    $store->region_image2 = $fileName; 
        // }
        $store->region3 = $request->region3;
        $store->region_des3 = $request->region_des3;
        if ($request->hasFile('region_image3')) {
            $image = $request->file('region_image3');
            $originalName = $image->getClientOriginalName(); // original file name with extension
            $path = 'com/home/' . $originalName;

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

            $store->region_image3 = $originalName;
        }
        // if($request->region_image3){
        //     $image = $request->file('region_image3');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $imageName = Str::random(5).time() .'.'. $extension;
        //     // $relPath = 'dook/images/home/';
        //     //     if (!file_exists(public_path($relPath))) {
        //     //         mkdir(public_path($relPath), 777, true);
        //     //     }
        //     // $img = Image::make($image)->save( public_path($relPath . $imageName ) ); 
        //     // $store->region_image3 = $imageName; 

        //      $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/home/'.$fileName, $convertImage);
        //    $store->region_image3 = $fileName; 
        // }
        $store->region4 = $request->region4;
        $store->region_des4 = $request->region_des4;
         if ($request->hasFile('region_image4')) {
            $image = $request->file('region_image4');
            $originalName = $image->getClientOriginalName(); // original file name with extension
            $path = 'com/home/' . $originalName;

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

            $store->region_image4 = $originalName;
        }
        // if($request->region_image4){
        //     $image = $request->file('region_image4');
        //      $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/home/'.$fileName, $convertImage);
        //    $store->region_image4 = $fileName; 
        // }
         $store->region5 = $request->region5;
        $store->region_des5 = $request->region_des5;
          if ($request->hasFile('region_image5')) {
            $image = $request->file('region_image5');
            $originalName = $image->getClientOriginalName(); // original file name with extension
            $path = 'com/home/' . $originalName;

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

            $store->region_image5 = $originalName;
        }
        // if($request->region_image5){
        //     $image = $request->file('region_image5');
        //     $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/home/'.$fileName, $convertImage);
        //    $store->region_image5 = $fileName; 
        // }
         $store->region6 = $request->region6;
        $store->region_des6 = $request->region_des6;
          if ($request->hasFile('region_image6')) {
            $image = $request->file('region_image6');
            $originalName = $image->getClientOriginalName(); // original file name with extension
            $path = 'com/home/' . $originalName;

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

            $store->region_image6 = $originalName;
        }
        // if($request->region_image6){
        //     $image = $request->file('region_image6');
        //     $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/home/'.$fileName, $convertImage);
        //    $store->region_image6 = $fileName; 
        // }
        $store->save();
        $status = [
            'msg'=> "Success",
        ];
        return response()->json($status);
    }
    public function whyChooseUpdate(Request $request)
    {   
        $store = HomeSetting::find($request->data_id);
        $store->text_heading = $request->text_heading;
        $store->text_sub_heading = $request->text_sub_heading;
        $store->description = $request->description;
        $store->save();
        $status = [
            'msg'=> 'Success',
        ];
        return response()->json($status);
    }

    public function homeMetaDataUpdate(Request $request)
    {   
        $store = HomeSetting::find($request->data_id);
        $store->meta_title = $request->meta_title;
        $store->meta_keywords = $request->meta_keywords;
        $store->meta_description = $request->meta_description;

        $store->save();
        $status = [
            'msg'=> "Success",
        ];
        return response()->json($status);
    }   

    public function homeActivityDelete(Request $request,$id)
    {   
        HomeActivitySetting::where('id',$id)->delete();
        $status = [
            'msg'=> "Success",
        ];
        return response()->json($status);
    }  
}
