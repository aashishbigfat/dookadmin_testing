<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageServiceProvider;
use DB;
use Storage;
use Image;
use Auth;
use App\LandingDeparturePage;
use App\Contact;
use App\ContactAddress;
use App\Traits\TinyPngImageCompress;
use Google\Cloud\Storage\StorageClient;


class LandingPageController extends Controller
{
    use TinyPngImageCompress;

    function landingPages(Request $request){
        
        //$urlS3 = "https://dook-international.sgp1.cdn.digitaloceanspaces.com/dook/images/landing/";

        $pages = LandingDeparturePage::get();
        // $urlS3 = url('/dook/images/landing/').'/';
        $urlS3 = 'https://dooktravels.s3.ap-south-1.amazonaws.com/com/landing/';
        $total = count($pages);
        return view('pages.page_index', compact('pages','urlS3','total')); 
    }
    function landingPagesCreate(Request $request){
        return view('pages.page_create');
         
    }

    function landingPageStore(Request $request){
        $data = $request->all();
        $user = auth()->user();
        $landings = new LandingDeparturePage;
        $landings->title = $request->title;
        $landings->sub_title = $request->sub_title;
        $landings->slug_url = $request->slug_url;
        $landings->description = $request->description;
        $landings->meta_title = $request->meta_title;
        $landings->meta_keywords = $request->meta_keywords;
        $landings->meta_description = $request->meta_description;
        $landings->user_id = $user->id;
        $landings->type = $request->pageTypes;
         if ($request->hasFile('page_banner')) {
                $image = $request->file('page_banner');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/landing/' . $originalName;

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

                $landings->banner_image = $originalName;
            }
        // if($request->page_banner){
        //     $image = $request->file('page_banner');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $filename = Str::random(5).time() . '.' . $extension;
        //     // $images = Image::make($image);
        //     // Storage::disk('s3')->put('dook/images/landing/'.$filename, $images->stream(), 'public');
        //     // $imagecompress = $this->compressToLocal($image, 'dook/images/landing', $filename);
        //     // $landings->banner_image = $filename;  

        //     $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/landing/'.$fileName, $convertImage);
        //     $contact->banner_image = $fileName;
        // }
        $landings->save();
        $status = [
                'url'=> url('/landing-pages'),
            ];
        return response()->json($status);
    }

    function landingPagesEdit(Request $request, $id){
        $page = LandingDeparturePage::where('id', $id)->first();
        $urlS3 = 'https://dooktravels.s3.ap-south-1.amazonaws.com/com/landing/';
        // $urlS3 = url('/dook/images/landing/').'/';
        return view('pages.page_edit', compact('page','urlS3'));
         
    }

    function landingPageUpdate(Request $request, $id){
        $data = $request->all();
        $user = auth()->user();
        $landings = LandingDeparturePage::find($id);
        $landings->title = $request->title;
        $landings->sub_title = $request->sub_title;
        $landings->slug_url = $request->slug_url;
        $landings->description = $request->description;
        $landings->meta_title = $request->meta_title;
        $landings->meta_keywords = $request->meta_keywords;
        $landings->meta_description = $request->meta_description;
        $landings->user_id = $user->id;
        $landings->type = $request->pageTypes;
        if ($request->hasFile('page_banner')) {
                $image = $request->file('page_banner');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/landing/' . $originalName;

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

                $landings->banner_image = $originalName;
            }
        // if($request->page_banner){
        //     $image = $request->file('page_banner');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $filename = Str::random(5).time() . '.' . $extension;
        //     // $images = Image::make($image);
        //     // Storage::disk('s3')->put('dook/images/landing/'.$filename, $images->stream(), 'public');
        //     // $imagecompress = $this->compressToLocal($image, 'dook/images/landing', $filename);
        //     // $landings->banner_image = $filename;  

        //     $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/landing/'.$fileName, $convertImage);
        //     $landings->banner_image = $fileName;
        // }
        $landings->save();
        $status = [
            'url'=> url('/landing-pages'),
        ];
        return response()->json($status);
    }

    function lanndingContactIndex(Request $request)
    {
        // $urlS3 = url('/dook/images/landing/').'/';
          $urlS3 = 'https://dooktravels.s3.ap-south-1.amazonaws.com/com/landing/';
        $contact = Contact::first();
        $address = ContactAddress::get();
        if($contact){
            return view('pages.contact_edit', compact('contact','address','urlS3'));
        }else{
            return view('pages.contact_create');
        }
    }
    function lanndingContactStore(Request $request){
        $data = $request->all();
        $user = auth()->user();
        $contact = new Contact;
        $contact->title = $request->title;
        $contact->sub_title = $request->sub_title;
        $contact->header_title = $request->header_title;
        $contact->header_subtitle = $request->header_subtitle;
        $contact->phone = $request->phone;
        $contact->whatsapp = $request->whatsapp;
        $contact->email = $request->email;
        $contact->facebook = $request->facebook;
        $contact->twitter = $request->twitter;
        $contact->instagram = $request->instagram;
        $contact->youtube = $request->youtube;
        $contact->meta_title = $request->meta_title;
        $contact->meta_keywords = $request->meta_keywords;
        $contact->meta_description = $request->meta_description;
        $contact->user_id = $user->id;
           if ($request->hasFile('page_banner')) {
                $image = $request->file('page_banner');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/landing/' . $originalName;

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

                $contact->banner_image = $originalName;
            }
        // if($request->page_banner){
        //     $image = $request->file('page_banner');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $filename = Str::random(5).time() . '.' . $extension;
        //     // $imagecompress = $this->compressToLocal($image, 'dook/images/landing', $filename);
        //     // $contact->banner_image = $filename;  

        //     $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/landing/'.$fileName, $convertImage);
        //     $contact->banner_image = $fileName;
        // }
        $contact->save();
        $lastId = $contact->id;
        if($request->addressTitle){
            foreach ($request->addressTitle as $key => $value) {
                if($value != null || $value != ""){
                    $contact_address = new ContactAddress;
                    $contact_address->contact_id = $lastId;
                    $contact_address->title = $value;
                    $contact_address->address = $request->address[$key];
                    $contact_address->save();
                }
            }
        }
        $status = [
            'url'=> url('/contact-us'),
        ];
        return response()->json($status);
    }

    function lanndingContactUpdate(Request $request, $id){
        $data = $request->all();
        $user = auth()->user();
        $contact = Contact::find($id);
        $contact->title = $request->title;
        $contact->sub_title = $request->sub_title;
        $contact->header_title = $request->header_title;
        $contact->header_subtitle = $request->header_subtitle;
        $contact->phone = $request->phone;
        $contact->whatsapp = $request->whatsapp;
        $contact->email = $request->email;
        $contact->facebook = $request->facebook;
        $contact->twitter = $request->twitter;
        $contact->instagram = $request->instagram;
        $contact->youtube = $request->youtube;
        $contact->meta_title = $request->meta_title;
        $contact->meta_keywords = $request->meta_keywords;
        $contact->meta_description = $request->meta_description;
        $contact->user_id = $user->id;
         if ($request->hasFile('page_banner')) {
                $image = $request->file('page_banner');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/landing/' . $originalName;

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

                $contact->banner_image = $originalName;
            }
        // if($request->page_banner){
        //     $image = $request->file('page_banner');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $filename = Str::random(5).time() . '.' . $extension;
        //     // $imagecompress = $this->compressToLocal($image, 'dook/images/landing', $filename);
        //     // $contact->banner_image = $filename;  

        //     $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/landing/'.$fileName, $convertImage);
        //     $contact->banner_image = $fileName;
        // }
        $contact->save();
        $lastId = $contact->id;
        if($request->addressTitle){
            ContactAddress::where('contact_id', $lastId)->delete();
            foreach ($request->addressTitle as $key => $value) {
                if($value != null || $value != ""){
                    $contact_address = new ContactAddress;
                    $contact_address->contact_id = $lastId;
                    $contact_address->title = $value;
                    $contact_address->address = $request->address[$key];
                    $contact_address->save();
                }
            }
        }
        $status = [
            'url'=> url('/contact-us'),
        ];
        return response()->json($status);
    }
}
