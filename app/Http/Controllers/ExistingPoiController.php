<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageServiceProvider;
use Illuminate\Support\Str;
use DB;
use Storage;
use Image;
use Redirect;
use App\CountryExistingPoi;
use App\Country;
use App\Traits\TinyPngImageCompress;

class ExistingPoiController extends Controller
{
    use TinyPngImageCompress;
    public function index(Request $request)
    {
        $keywords = $request->keyword;

        if($keywords){
            $pois = CountryExistingPoi::join('countries','countries.id','=','country_existing_pois.country_id')
                    ->where('country_existing_pois.poi_name', 'LIKE','%'.$keywords.'%')
                    ->orWhere('countries.country_name', 'LIKE','%'.$keywords.'%')
                    ->distinct()
                    ->select('country_existing_pois.*','countries.country_name')
                    ->orderBy('created_at', 'DESC')
                    ->paginate(25);
        }
        else{
            $pois = CountryExistingPoi::join('countries','countries.id','=','country_existing_pois.country_id')
                    ->distinct()
                    ->select('country_existing_pois.*','countries.country_name')
                    ->orderBy('created_at', 'DESC')
                    ->paginate(25);
        }
        $total_poi = CountryExistingPoi::get();
        $total = count($total_poi);
        $country = Country::select('id', 'country_name')->get();
        //$s3url= "https://dook-international.sgp1.cdn.digitaloceanspaces.com/dook/images/poi/";
        // $s3url = url('/dook/images/poi/').'/';
        $s3url = 'https://dooktravels.s3.ap-south-1.amazonaws.com/com/poi/';
        if($request->ajax()){
            return view('existingpoi.data',compact('pois','s3url'));
        }
        return view('existingpoi.index', compact('pois','s3url','total','country','keywords'));
    }

    public function create(Request $request)
    {
        $country = Country::select('id', 'country_name')->get();
        return view('existingpoi.create',compact('country'));
    }

    public function store(Request $request)
    {
        $poi = new CountryExistingPoi();
        $poi->poi_name = $request->name;
        $poi->country_id = $request->country;
        $poi->latitude = $request->latitide;
        $poi->longitude = $request->longitude;
        $poi->rating = $request->rating;
        $poi->poi_type = $request->type;
        $poi->address = $request->address;
        $poi->description = $request->description;
    
        // if($request->image){ 
        //     $file = $request->file('image');
        //     $imageName = Str::random(6).time().'.'.$file->getClientOriginalExtension();
        //         $relPath = 'dook/images/poi/';
        //         if (!file_exists(public_path($relPath))) {
        //             mkdir(public_path($relPath), 777, true);
        //         }
        //     $img = Image::make($file)->fit(464, 260)->save( public_path($relPath . $imageName ) ); 
        //     $poi->image = $imageName;
        // }

         if ($request->hasFile('image')) {
            $image = $request->file('image');
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
            $poi->image = $imageName;
        }
        // if($request->image){
        //      $image = $request->file('image');
        //         $ext = 'webp';
        //         $convertImage = Image::make($image)->encode($ext, 60);
        //         $fileName = uniqid().'.'.$ext;
        //         Storage::disk('s3')->put('com/poi/'.$fileName, $convertImage);
        //         $poi->image = $fileName;

        //     // $image = $request->file('image');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $filename = Str::random(6).time() .'.'. $extension;
        //     // // $images = Image::make($image);
        //     // // Storage::disk('s3')->put('dook/images/banner/'.$filename, $images->stream(), 'public');
        //     // //Storage::disk('spaces')->putFileAs('dook/images/banner', $image, $filename,'public');
        //     // $imagecompress = $this->compressToLocal($image, 'dook/images/poi', $filename);
        //     // $poi->image = $filename;  
        // }
        // if($request->banner_image){ 
        //     $file = $request->file('banner_image');
        //     $imageName = Str::random(6).time().'.'.$file->getClientOriginalExtension();
        //         $relPath = 'dook/images/poi/';
        //         if (!file_exists(public_path($relPath))) {
        //             mkdir(public_path($relPath), 777, true);
        //         }
        //     $img = Image::make($file)->fit(1920, 768)->save( public_path($relPath . $imageName ) ); 
        //     $poi->banner_image = $imageName;
        // }

        // if($request->banner_image){
        //       $image = $request->file('banner_image');
        //         $ext = 'webp';
        //         $convertImage = Image::make($image)->encode($ext, 60);
        //         $fileName = uniqid().'.'.$ext;
        //         Storage::disk('s3')->put('com/poi/'.$fileName, $convertImage);
        //         $poi->banner_image = $fileName;
                
        //     // $image = $request->file('banner_image');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $filename = Str::random(6).time() .'.'. $extension;
        //     // // $images = Image::make($image);
        //     // // Storage::disk('s3')->put('dook/images/banner/'.$filename, $images->stream(), 'public');
        //     // //Storage::disk('spaces')->putFileAs('dook/images/banner', $image, $filename,'public');
        //     // $imagecompress = $this->compressToLocal($image, 'dook/images/poi', $filename);
        //     // $poi->banner_image = $filename;  
        // }
         if ($request->hasFile('banner_image')) {
            $image = $request->file('banner_image');
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
            $poi->banner_image = $imageName;
        }
        $poi->save();
        $status = [
                'url'=> url('/existingpoi'),
            ];
        return response()->json($status);
    }

    public function update(Request $request, $id)
    {
        $poi = CountryExistingPoi::find($id);
        $poi->poi_name = $request->edit_name;
        $poi->country_id = $request->edit_country;
        $poi->latitude = $request->edit_latitide;
        $poi->longitude = $request->edit_longitude;
        $poi->rating = $request->edit_rating;
        $poi->poi_type = $request->edit_type;
        $poi->address = $request->edit_address;
        $poi->description = $request->edit_description;
        // if($request->edit_images){
        //       $image = $request->file('edit_images');
        //         $ext = 'webp';
        //         $convertImage = Image::make($image)->encode($ext, 60);
        //         $fileName = uniqid().'.'.$ext;
        //         Storage::disk('s3')->put('com/poi/'.$fileName, $convertImage);
        //         $poi->image = $fileName;
        // }
        // if($request->edit_image_banner){ 
        //    $image = $request->file('edit_image_banner');
        //         $ext = 'webp';
        //         $convertImage = Image::make($image)->encode($ext, 60);
        //         $fileName = uniqid().'.'.$ext;
        //         Storage::disk('s3')->put('com/poi/'.$fileName, $convertImage);
        //         $poi->banner_image = $fileName;
        // }
        if ($request->hasFile('edit_images')) {
            $image = $request->file('edit_images');
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
            $poi->image = $imageName;
        }

        if ($request->hasFile('edit_image_banner')) {
            $image = $request->file('edit_image_banner');
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
            $poi->banner_image = $imageName;
        }

        $save = $poi->save();
        $status = [
                'ststus'=> "successfully!",
            ];
        return response()->json($status);
    }
    public function existingPoiDisable(Request $request, $id)
    {
        CountryExistingPoi::where('id', $id)->delete();
         return response()->json(['success'=>'POIs Delete successfully!']);
    }
}
