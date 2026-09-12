<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DB;
use Image;
use Auth;
use finfo;
use Storage;
use App\Departure;
use App\Destination;
use App\Country;
use App\SlugMaster;
use App\DepartureDestination;
use App\Experience;
use App\DestinationExperience;
use App\DestinationPoiImage;
use App\DestinationEvent;
use App\DestinationRestaurent;
use App\DestinationHotel;
use App\Traits\TinyPngImageCompress;
use Google\Cloud\Storage\StorageClient;


class DestinationController extends Controller
{
  use TinyPngImageCompress;

    public function destinationIndex(Request $request){
        $status = $request->status;
        $keywords = $request->keyword;
        if($keywords){
            $destinations = Destination::join('countries','countries.id','=','destinations.country_id')
                        //->where('destinations.status',$status)
                        ->where('destinations.dest_name', 'LIKE','%'.$keywords.'%')
                        ->orWhere('countries.country_name', 'LIKE','%'.$keywords.'%')
                        // ->orderBy('destinations.created_at', 'ASC')
                        // ->orderBy('destinations.mega_menu', 'DESC')
                        ->select('destinations.*','countries.country_name')
                        ->paginate(25);
        }elseif($status == 'in'){
            $destinations = Destination::join('countries','countries.id','=','destinations.country_id')
                        ->where('destinations.status',0)
                        //->where(function($query)use($keywords, $status){
                            //$query->where('destinations.dest_name', 'LIKE','%'.$keywords.'%')
                                        //->orWhere('countries.country_name', 'LIKE','%'.$keywords.'%');
                            //})
                        // ->orderBy('destinations.created_at', 'ASC')
                        // ->orderBy('destinations.mega_menu', 'DESC')
                        ->select('destinations.*','countries.country_name')
                        ->paginate(25);
        }elseif($status == 1){
            $destinations = Destination::join('countries','countries.id','=','destinations.country_id')
                        ->where('destinations.status',1)
                        //->where(function($query)use($keywords, $status){
                            //$query->where('destinations.dest_name', 'LIKE','%'.$keywords.'%')
                                        //->orWhere('countries.country_name', 'LIKE','%'.$keywords.'%');
                            //})
                        // ->orderBy('destinations.created_at', 'ASC')
                        // ->orderBy('destinations.mega_menu', 'DESC')
                        ->select('destinations.*','countries.country_name')
                        ->paginate(25);
        }
        elseif($status == 'in' && $keywords){
            $destinations = Destination::join('countries','countries.id','=','destinations.country_id')
                        ->where('destinations.status', 0)
                        ->where(function($query)use($keywords){
                            $query->where('destinations.dest_name', 'LIKE','%'.$keywords.'%')
                                    ->where('countries.country_name', 'LIKE','%'.$keywords.'%');
                        })
                        // ->orderBy('destinations.created_at', 'ASC')
                        // ->orderBy('destinations.mega_menu', 'DESC')
                        ->select('destinations.*','countries.country_name')
                        ->paginate(25);
        }elseif($status == 1 && $keywords){
            $destinations = Destination::join('countries','countries.id','=','destinations.country_id')
                        ->where('destinations.status', 1)
                        ->where(function($query)use($keywords){
                            $query->where('destinations.dest_name', 'LIKE','%'.$keywords.'%')
                                    ->where('countries.country_name', 'LIKE','%'.$keywords.'%');
                        })
                        // ->orderBy('destinations.created_at', 'ASC')
                        // ->orderBy('destinations.mega_menu', 'DESC')
                        ->select('destinations.*','countries.country_name')
                        ->paginate(25);
        }else{
            $destinations = Destination::join('countries','countries.id','=','destinations.country_id')
                        // ->orderBy('destinations.created_at', 'ASC')
                        // ->orderBy('destinations.mega_menu', 'DESC')
                        ->select('destinations.*','countries.country_name')
                        ->paginate(25);
        }
        foreach ($destinations as $key => $value) {
            $descriptionS = str_replace("'", "", $value->description);
            $value->description = preg_replace('/"/','',$descriptionS);
            
            $events = DestinationEvent::where('destination_id',$value->id)->get();
            $count = count($events);
            $value->events = $count;
        }
        foreach ($destinations as $key => $restaurant) {
            $restaurant_data = DestinationRestaurent::where('destination_id',$restaurant->id)->get();
            $count = count($restaurant_data);
            $restaurant->restaurants = $count;
        }

        foreach ($destinations as $key => $hotel) {
            $hotel_data = DestinationHotel::where('destination_id',$hotel->id)->get();
            $count = count($hotel_data);
            $hotel->hotels = $count;
        }
        
        foreach ($destinations as $key => $images){
          $images_row = DestinationPoiImage::where('destination_id', $images->id)
                        ->pluck('image')->toArray();
          $images->dest_images = $images_row;

        }
          //dd($destinations);
      $destinationCount = Destination::get();
      //$s3url = "https://dook-international.sgp1.cdn.digitaloceanspaces.com/dook/images/poi/";
      // $s3url = url('/dook/images/poi/').'/';
         $s3url = 'https://dooktravels.s3.ap-south-1.amazonaws.com/com/poi/';
      $total = count($destinationCount);
      $status = ($status == null)?'no':$status;
      if($request->ajax()){
        return view('destination.index_data',compact('destinations','s3url','keywords','status'));
      }
      return view('destination.index',compact('destinations','total','s3url','keywords','status'));
    }

    public function destinationEdit(Request $request, $id)
    {
      $destination = Destination::find($id);
      //$s3url = "https://dook-international.sgp1.cdn.digitaloceanspaces.com/dook/images/poi/";
      // $s3url = url('/dook/images/poi/').'/';
      $s3url = 'https://dooktravels.s3.ap-south-1.amazonaws.com/com/poi/';
      return view('destination.edit',compact('destination','s3url'));
    }
    public function destinationUpdate(Request $request, $id)
    {
      $data = $request->all();
      //dd($data);
      $destination = Destination::find($id);
      $destination->dest_name = $request->edit_destination;
      $destination->slug_url = $request->edit_slug_url;
      $destination->title = $request->edit_title;
      $destination->sub_title = $request->edit_sub_title;
      $destination->header_title = $request->edit_header_title;
      $destination->header_sub_title = $request->edit_header_sub_title;
      $destination->description = $request->edit_description;
      $destination->tour_sub_title = $request->edit_tour_sub_title;
      $destination->experience_sub_title = $request->edit_experience_sub_title;
      $destination->attraction_sub_title = $request->edit_attraction_sub_title;
      $destination->event_sub_title = $request->edit_event_sub_title;
      $destination->restaurant_sub_title = $request->edit_restaurant_sub_title;
      $destination->trip_sub_title = $request->edit_trip_sub_title;
      $destination->trip_description = $request->edit_trip_description;
      $destination->meta_title = $request->meta_title;
      $destination->meta_keywords = $request->meta_keywords;
      $destination->meta_description = $request->meta_description;
      if ($request->hasFile('edit_image')) {
                $image = $request->file('edit_image');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/poi/' . $originalName;

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

                $destination->image = $originalName;
            }

      // if($request->edit_image){
      //   $image = $request->file('edit_image');
      //   // $extension = $image->getClientOriginalExtension();
      //   // $filename = Str::random(9).time() .'.'. $extension;
      //   //$imageFile = Image::make($image)->fit(720, 720)->stream();
      //   //$imageFile = $imageFile->__toString();
      //   //Storage::disk('s3')->put('dook/images/poi/'.$filename, $imageFile, 'public');
      //   // $imagecompress = $this->compressToLocal($image, 'dook/images/poi', $filename);
      //   // $destination->image = $filename;  

      //   $ext = 'webp';
      //   $convertImage = Image::make($image)->encode($ext, 60);
      //   $fileName = uniqid().'.'.$ext;
      //   Storage::disk('s3')->put('com/poi/'.$fileName, $convertImage);
      //   $destination->image = $fileName; 
      // }
          if ($request->hasFile('edit_banner_image')) {
                $image = $request->file('edit_banner_image');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/poi/' . $originalName;

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

                $destination->banner_image = $originalName;
            }
      // if($request->edit_banner_image){
      //   $image = $request->file('edit_banner_image');
      //   // $extension = $image->getClientOriginalExtension();
      //   // $filename = Str::random(10).time() .'.'. $extension;
      //   //$imageFile = Image::make($image)->fit(1920, 768)->stream();
      //   //$imageFile = $imageFile->__toString();
      //   //Storage::disk('s3')->put('dook/images/poi/'.$filename, $imageFile, 'public');
      //   // $imagecompress = $this->compressToLocal($image, 'dook/images/poi', $filename);
      //   // $destination->banner_image = $filename;  

      //   $ext = 'webp';
      //   $convertImage = Image::make($image)->encode($ext, 60);
      //   $fileName = uniqid().'.'.$ext;
      //   Storage::disk('s3')->put('com/poi/'.$fileName, $convertImage);
      //    $destination->banner_image = $fileName;  
      // }
      $destination->save();
      $last_id = $destination->id;

      $slugUnique = SlugMaster::where('destination_id', $last_id)
                  ->where('module_name', 'destination_detail_page')
                  ->first();
      if($slugUnique){
          $destinationSlug  = SlugMaster::find($slugUnique->id);
          $destinationSlug->slug_name = $request->edit_slug_url;
          $destinationSlug->save();
      }else{
          $destinationSlug  = new SlugMaster;
          $destinationSlug->destination_id = $last_id;
          $destinationSlug->slug_name = $request->edit_slug_url;
          $destinationSlug->module_name = 'destination_detail_page';
          $destinationSlug->save();
      }
      $status = [
          'url'=> url('/destinations'),
      ];
      return response()->json($status);
    }

    public function destinationDisable(Request $request, $id)
    {
        $destination  = Destination::find($id);
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
    public function destinationMegaMenu(Request $request, $id)
    {
        $destination  = Destination::find($id);
        if($destination->mega_menu == 0){
            $destination->mega_menu = 1;
            $destination->save();
            return redirect()->back()->with('success', 'Destination added to mega menu successfully!');
        }
        else{
            $destination->mega_menu = 0;
            $destination->save();
            return redirect()->back()->with('success', 'Destination remove from mega menu successfully!');
        }
    }

    public function addEvents(Request $request)
    {
        $data = $request->all();
        $dest_id = $request->id;
        $jsonfile = file_get_contents("http://api.eventful.com/json/events/search?app_key=QRWnrWzZjQRMfZXm&where=".$request->lat.",".$request->long);
            $event_data = json_decode($jsonfile,TRUE);
            $data = $event_data['events']['event'];
            if(!is_null($data)){
                foreach ($data as $key => $ev_data) {
                    if($ev_data['image'] == '' || $ev_data['image'] == null){
                        $image = '';
                    }
                    else{
                        if(!str_contains($ev_data['image']['medium']['url'], 'http')){
                            $image = 'http:'.$ev_data['image']['medium']['url'];
                        }
                        else{
                            $image = $ev_data['image']['medium']['url'];
                        }
                    }
                    $title = preg_replace('/\\\\/','_',$ev_data['title']);
                    $description = preg_replace('/\\\\/','_',$ev_data['description']);

                    if($image != ''){
                        $events = DestinationEvent::where('destination_id', $dest_id)
                                ->where('event_name', $title)
                                ->first();
                        if(is_null($events)){
                            $destination  = new DestinationEvent;
                            $destination->destination_id = $dest_id;
                            $destination->event_name = $title;
                            if(isset($ev_data['venue_name'])){
                                $destination->venue_name = $ev_data['venue_name'];
                            }
                            if(isset($ev_data['latitude'])){
                               $destination->latitude = $ev_data['latitude']; 
                            }
                            if(isset($ev_data['longitude'])){
                               $destination->longitude = $ev_data['longitude']; 
                            }
                            if(isset($ev_data['start_time'])){
                               $destination->start_time = $ev_data['start_time']; 
                            }
                            if(isset($ev_data['country_name'])){
                               $destination->country_name = $ev_data['country_name']; 
                            }
                            if(isset($ev_data['venue_address'])){
                               $destination->venue_address = $ev_data['venue_address']; 
                            }
                            $destination->description = $description;
                            $destination->image = $image;
                            $destination->save();
                        }
                        else{
                            $destination  = DestinationEvent::find($events->id);
                            $destination->destination_id = $dest_id;
                            $destination->event_name = $title;
                            if(isset($ev_data['venue_name'])){
                                $destination->venue_name = $ev_data['venue_name'];
                            }
                            if(isset($ev_data['latitude'])){
                               $destination->latitude = $ev_data['latitude']; 
                            }
                            if(isset($ev_data['longitude'])){
                               $destination->longitude = $ev_data['longitude']; 
                            }
                            if(isset($ev_data['start_time'])){
                               $destination->start_time = $ev_data['start_time']; 
                            }
                            if(isset($ev_data['country_name'])){
                               $destination->country_name = $ev_data['country_name']; 
                            }
                            if(isset($ev_data['venue_address'])){
                               $destination->venue_address = $ev_data['venue_address']; 
                            }
                            $destination->description = $description;
                            $destination->image = $image;
                            $destination->save();
                        }
                    }
                }
            }
        return response()->json(['Event added Successfully!']);
    }

    // Restaurants
    public function addRestaurants(Request $request)
    {
        $data = $request->all();
        $dest_id = $request->id;
        $url = file_get_contents('https://api.tomtom.com/search/2/search/restaurant.json?language=en-US&limit=25&lat='.$request->lat.'&lon='.$request->long.'&categorySet=7315&key=6vdxVANLJketgjeoT3dvURPnu4ny3VWy');
       $jsondata = json_decode($url);
       $i = 1;
        foreach ($jsondata->results as $key => $value) {
            if($value->poi->name != ''){
                $restaurants_unique = DestinationRestaurent::where('destination_id', $dest_id)
                                ->where('place_id', $value->id)
                                ->first();
                if(is_null($restaurants_unique)){
                   $restaurant = new DestinationRestaurent();
                   if((array_key_exists('phone', $value->poi))){
                       $restaurant->phone = $value->poi->phone;
                   }
                   if((array_key_exists('municipalitySubdivision', $value->address))){
                       $restaurant->municipality_subdivision = $value->address->municipalitySubdivision;
                   }
                   if((array_key_exists('municipality', $value->address))){
                       $restaurant->municipality = $value->address->municipality;
                   }

                   $categories = $value->poi->categories[0];
                   $res_image = $categories.'_'.$i.'.jpg';
                   $restaurant->destination_id = $dest_id;
                   $restaurant->destination_name = $request->destination;
                   $restaurant->name = $value->poi->name;
                   $restaurant->latitude = $value->position->lat;
                   $restaurant->longitude = $value->position->lon;
                   if(isset($value->address->postalCode)){
                        $restaurant->postal_code = $value->address->postalCode;
                   }
                   $restaurant->country = $value->address->country;
                   $restaurant->address = $value->address->freeformAddress;
                   $restaurant->destination_id = $dest_id;
                   $restaurant->country_iso2 = $value->address->countryCode;
                   $restaurant->restaurant_type = $value->poi->categories[0];
                   $restaurant->place_id = $value->id;
                   $restaurant->image = $res_image;
                   $restaurant->save();
                   if($i > 3){
                       $i = 0;
                   }
                   $i++;
                }
            }
        }
        return response()->json(['Restaurant added Successfully!']);
    }

    // Restaurants
    // public function addHotels(Request $request)
    // {
    //     $data = $request->all();
    //     $dest_id = $request->id;

    //     $url = file_get_contents('http://engine.hotellook.com/api/v2/lookup.json?lang=en&lookFor=hotel&limit=20&query='.$request->lat.','.$request->long.'&token=3ab817b8117c83d3ab36b9f6015c988d');
    //    $jsondata = json_decode($url);
    //   foreach ($jsondata->results->hotels as $key => $value) {
    //     $img_name = 'https://photo.hotellook.com/image_v2/limit/h'.$value->id.'_1/800/360.auto';
    //     $hotels_unique = DestinationHotel::where('destination_id', $dest_id)
    //                     ->where('hotel_name', $value->name)
    //                     ->first();
    //     if(is_null($hotels_unique)){
    //       $urls = file_get_contents('https://api.tomtom.com/search/2/search/poi.json?language=en-US&limit=1&lat='.$value->location->lat.'&lon='.$value->location->lon.'&categorySet=7314&key=6vdxVANLJketgjeoT3dvURPnu4ny3VWy');
    //       $jsondatas = json_decode($urls);
    //       foreach ($jsondatas->results as $key => $add) {
    //         $hotel = new DestinationHotel();
    //         $hotel->destination_id = $dest_id;
    //         $hotel->destination_name = $request->destination;
    //         $hotel->hotel_name = $value->name;
    //         $hotel->latitude = $value->location->lat;
    //         $hotel->longitude = $value->location->lon;
    //         $hotel->country = $add->address->country;
    //         $hotel->address = $add->address->freeformAddress;
    //         if($img_name != ''){
    //           $type = pathinfo($img_name, PATHINFO_EXTENSION);
    //           $data = file_get_contents($img_name);
    //           $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    //           $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64));
    //           $imageName = Str::random(5).time() . '.png';
    //           $imageFile = Image::make($image)->fit(450, 299)->stream();
    //           $imageFile = $imageFile->__toString();
    //           $img = Storage::disk('s3')->put('dook/images/hotel/'.$imageName, $imageFile, 'public');
    //           $hotel->image =  $imageName;
    //         } 
    //         $hotel->save();
    //       }
    //     }
    //   }
       
    //     return response()->json($jsondata);
    // }

    public function makeTopDestination(Request $request, $id)
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

    // Visa popular destinations

    public function visaPopularDestination(Request $request)
    {
        $visa_destination = Destination::where('visa_popular_destination',1)
                            ->select('id','dest_name','country_name')
                            ->get();
        $total = count($visa_destination);
        return view('visaDestination.visa_destination',compact('visa_destination','total'));
    }

    public function visaPopularDestinationStore(Request $request)
    {
        $data = $request->all();
        if($request->destination){
            foreach($request->destination as $value) {
                $visa_destination = Destination::find($value);
                $visa_destination->visa_popular_destination = "1";
                $visa_destination->save();
            }
        }
        return response()->json(['success'=>'Success!']);
    }

    public function visaPopularDestinationDelete(Request $request, $id)
    {
        
        $destination = Destination::find($id);
        $destination->visa_popular_destination = "0";
        $destination->save();
        return response()->json(['success'=>'Success!']);
    }
}
