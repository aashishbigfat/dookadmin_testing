<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageServiceProvider;
use App\CountryWisePackage;
use DB;
use Storage;
use Image;
use App\Country;
use App\SlugMaster;
use App\Departure;
use App\Destination;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Log;

class CountryWisePackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = CountryWisePackage::all();
        $s3url = generateSignedUrl('com/');
        return view('country_wise_packages.index', compact('packages','s3url'));
    }

    public function create()
    {
        $countries = Country::pluck('country_name', 'id');
        $destination = Destination::pluck('dest_name', 'id');
         $departureOptions = Departure::where('status','1')->select('id', 'title', 'dep_dook_ref_id')->get();

         return view('country_wise_packages.create', compact('countries','departureOptions','destination'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|unique:country_wise_packages|string|max:255',
            'header_title' => 'nullable|string|max:255',
            'header_sub_title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png',
            'meta_title' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'departure_ids' => 'nullable|array',
            'departure_ids.*' => 'exists:departures,id',
            'destination_id' => 'nullable|string|max:255',
        ]);

        $package = new CountryWisePackage();
        $package->name = $request->name;
        $package->slug = Str::slug($request->slug);
        $package->header_title = $request->header_title;
        $package->header_sub_title = $request->header_sub_title;
        $package->description = $request->description;
        $package->meta_title = $request->meta_title;
        $package->meta_keywords = $request->meta_keywords;
        $package->meta_description = $request->meta_description;
        $package->country_id = $request->input('country_id');
        $package->destination_id = $request->input('destination_id'); 

         if ($request->hasFile('featured_image')) {
                $image = $request->file('featured_image');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/' . $originalName;

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

                $package->featured_image = $originalName;
            }
            if ($request->hasFile('banner_image')) {
                $image = $request->file('banner_image');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/' . $originalName;

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

                $package->banner_image = $originalName;
            }
         $package->departure_ids = implode(',', $request->input('departure_ids', []));

         if(isset($request->slug)){
            $slugUnique = SlugMaster::where('country_id', $package->country_id)
                        ->where('module_name', 'country_wise_package')
                        ->first(); 
            if($slugUnique){
                $country  = SlugMaster::find($slugUnique->id);
                $country->slug_name = $request->slug;
   
                $country->save();
            }else{
                $country  = new SlugMaster;
                $country->country_id = $package->country_id;
                $country->slug_name = $request->slug;
                $country->module_name = 'country_wise_package';
                $country->save();
            } 
        }

          $package->save();

        return redirect()->route('country-wise-packages.index')->with('success', 'Package created successfully.');
    }

    public function show(CountryWisePackage $countryWisePackage)
    {
        return view('country_wise_packages.show', compact('countryWisePackage'));
    }

    public function edit(CountryWisePackage $countryWisePackage)
    {
        $countries = Country::pluck('country_name', 'id'); // for dropdown
        $destination = Destination::pluck('dest_name', 'id');
        $departureOptions = Departure::where('status','1')
        ->select('id', 'title', 'dep_dook_ref_id')
        ->get();
        return view('country_wise_packages.edit', compact('countryWisePackage', 'countries','departureOptions','destination'));
    }

    public function update(Request $request, CountryWisePackage $countryWisePackage)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:country_wise_packages,slug,' . $countryWisePackage->id,
            'header_title' => 'nullable|string|max:255',
            'header_sub_title' => 'nullable|string',
            'description' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png',
            'meta_title' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'meta_description' => 'nullable|string',
             'departure_ids' => 'nullable|array',
            'departure_ids.*' => 'exists:departures,id',
            'destination_id' => 'nullable|string|max:255',
        ]);

        $countryWisePackage->name = $request->name;
        $countryWisePackage->slug = Str::slug($request->slug);
        $countryWisePackage->header_title = $request->header_title;
        $countryWisePackage->header_sub_title = $request->header_sub_title;
        $countryWisePackage->description = $request->description;
        $countryWisePackage->meta_title = $request->meta_title;
        $countryWisePackage->meta_keywords = $request->meta_keywords;
        $countryWisePackage->meta_description = $request->meta_description;
        $countryWisePackage->country_id = $request->input('country_id');
        $countryWisePackage->destination_id = $request->input('destination_id'); 
        $countryWisePackage->departure_ids = implode(',', $request->input('departure_ids', []));

        if ($request->hasFile('featured_image')) {
                $image = $request->file('featured_image');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/' . $originalName;

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

                $countryWisePackage->featured_image = $originalName;
            }
            if ($request->hasFile('banner_image')) {
                $image = $request->file('banner_image');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/' . $originalName;

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

                $countryWisePackage->banner_image = $originalName;
            }
        $countryWisePackage->departure_ids = implode(',', $request->input('departure_ids', []));
        $countryWisePackage->save();

        return redirect()->route('country-wise-packages.index')->with('success', 'Package updated successfully.');
    }

    public function destroy(CountryWisePackage $countryWisePackage)
    {
        $countryWisePackage->delete();
        return redirect()->route('country-wise-packages.index')->with('success', 'Package deleted successfully.');
    }
}
