<?php

namespace App\Http\Controllers\Departure;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Intervention\Image\ImageServiceProvider;
use DB;
use Storage;
use Image;
use Auth;
use App\User;
use App\Region;
use App\Departure;
use App\Destination;
use App\Country;
use App\CountryDeparture;
use App\DepartureImage;
use App\DepartureDestination;
use App\Experience;
use App\DestinationExperience;
use App\AgentItinerary;
use App\DepartureOptionalActivity;
use App\IconInclusion;
use App\DepartureIconInclusion;
use App\DepartureDifficulty;
use App\Difficulty;
use App\DepartureTourType;
use App\TourType;
use App\TourClass;
use App\CountryDepartureDestinationRegion;
use App\CountryBestTimeToVisit;
use App\CountryClimateType;
use App\CountryDemonym;
use App\CountryElectricalSocket;
use App\CountryEthnicities;
use App\CountryOfficialLanguage;
use App\CountryReligion;
use App\DestinationBestTimeToVisit;
use App\DestinationClimateType;
use App\DestinationAirport;
use App\Tag;
use App\DepartureTag;
use App\DepartureDate;

class GroupDepartureController extends Controller
{
    // Group Packages start

    public function groupPackagesIndex(Request $request)
    {
        //$urlS3 = "https://dook-international.sgp1.cdn.digitaloceanspaces.com/dook/images/package/";
        $urlS3 = url('/dook/images/package/').'/';
        //$from_date = $request->from_date;
        //$from = date("yy-m-d", strtotime($from_date));
        //$to_date = $request->to_date;
        //$to = date("yy-m-d", strtotime($to_date));
        //$keywords = $request->keyword;
        //$date = [$from, $to];
        //$status = $request->status;
        //dd($to);
        $keywords = $request->keyword;
        $status = $request->status;
        //dd($keywords);
        if($request->status == '' && $request->keyword){
            $departures = Departure::join('departure_destinations','departure_destinations.departure_id','=','departures.id')
                        ->join('destinations','destinations.id','=','departure_destinations.destination_id')
                        ->join('country_departures','country_departures.departure_id','=','departures.id')
                        ->join('countries','countries.id','=','country_departures.country_id')
                        //->where(['dep_type'=>'group', 'tenant_id'=> auth()->user()->tenant_id])
                        ->where('dep_type', 'group')
                        ->where(function($query)use($keywords){
                            $query->where('departures.title', 'LIKE','%'.$keywords.'%')
                                    ->orWhere('destinations.dest_name', 'LIKE','%'.$keywords.'%')
                                    ->orWhere('countries.country_name', 'LIKE','%'.$keywords.'%')
                                    ->orWhere('departures.dep_dook_ref_id', 'LIKE','%'.$keywords.'%');
                        })
                        ->distinct('departures.created_at')
                        ->select('departures.*')
                        ->orderBy('id', 'DESC')
                        ->paginate(25);
                //dd($departures);
        }elseif($request->status == 'in' && $request->keyword == ''){
            $departures = Departure::join('departure_destinations','departure_destinations.departure_id','=','departures.id')
                        ->join('destinations','destinations.id','=','departure_destinations.destination_id')
                        ->join('country_departures','country_departures.departure_id','=','departures.id')
                        ->join('countries','countries.id','=','country_departures.country_id')
                        //->where(['dep_type'=>'group', 'tenant_id'=> auth()->user()->tenant_id])
                        ->where('dep_type', 'group')
                        ->where('departures.status', 0)
                        ->distinct('departures.created_at')
                        ->select('departures.*')
                        ->orderBy('id', 'DESC')
                        ->paginate(25);
                //dd($departures);
        }elseif($request->status == 1 && $request->keyword == ''){
            $departures = Departure::join('departure_destinations','departure_destinations.departure_id','=','departures.id')
                        ->join('destinations','destinations.id','=','departure_destinations.destination_id')
                        ->join('country_departures','country_departures.departure_id','=','departures.id')
                        ->join('countries','countries.id','=','country_departures.country_id')
                        //->where(['dep_type'=>'group', 'tenant_id'=> auth()->user()->tenant_id])
                        ->where('dep_type', 'group')
                        ->where('departures.status', 1)
                        ->distinct('departures.created_at')
                        ->select('departures.*')
                        ->orderBy('id', 'DESC')
                        ->paginate(25);
                //dd($departures);
        }elseif($request->status == 1 && $request->keyword){
            $departures = Departure::join('departure_destinations','departure_destinations.departure_id','=','departures.id')
                        ->join('destinations','destinations.id','=','departure_destinations.destination_id')
                        ->join('country_departures','country_departures.departure_id','=','departures.id')
                        ->join('countries','countries.id','=','country_departures.country_id')
                        //->where(['dep_type'=>'group', 'tenant_id'=> auth()->user()->tenant_id])
                        ->where('dep_type', 'group')
                        ->where('departures.status', 1)
                        ->where(function($query)use($keywords){
                            $query->where('departures.title', 'LIKE','%'.$keywords.'%')
                                    ->orWhere('destinations.dest_name', 'LIKE','%'.$keywords.'%')
                                    ->orWhere('countries.country_name', 'LIKE','%'.$keywords.'%')
                                    ->orWhere('departures.dep_dook_ref_id', 'LIKE','%'.$keywords.'%');
                        })
                        ->distinct('departures.created_at')
                        ->select('departures.*')
                        ->orderBy('id', 'DESC')
                        ->paginate(25);
        }elseif($request->status == 'in' && $request->keyword){

            $departures = Departure::join('departure_destinations','departure_destinations.departure_id','=','departures.id')
                        ->join('destinations','destinations.id','=','departure_destinations.destination_id')
                        ->join('country_departures','country_departures.departure_id','=','departures.id')
                        ->join('countries','countries.id','=','country_departures.country_id')
                        //->where(['dep_type'=>'group', 'tenant_id'=> auth()->user()->tenant_id])
                        ->where('dep_type', 'group')
                        ->where('departures.status', 0)
                        ->where(function($query)use($keywords){
                            $query->where('departures.title', 'LIKE','%'.$keywords.'%')
                                    ->orWhere('destinations.dest_name', 'LIKE','%'.$keywords.'%')
                                    ->orWhere('countries.country_name', 'LIKE','%'.$keywords.'%')
                                    ->orWhere('departures.dep_dook_ref_id', 'LIKE','%'.$keywords.'%');
                        })
                        ->distinct('departures.created_at')
                        ->select('departures.*')
                        ->orderBy('id', 'DESC')
                        ->paginate(25);
        }else{
            $departures = Departure::where('dep_type','group')
                    //->where('tenant_id',auth()->user()->tenant_id)
                    ->orderBy('id', 'DESC')
                    //->distinct('departures.created_at')
                    ->paginate(25);
        }
        $departureCount = Departure::where('dep_type','group')
                        //->where('tenant_id',auth()->user()->tenant_id)
                        ->get();
        $total = count($departureCount);
        $status = ($status == null)?'no':$status;
        //dd($status);
        if($request->ajax()){
                return view('grouppackages.departure_index_data',compact('departures','urlS3','keywords','status'));
            }
        return view('grouppackages.departure_index',compact('departures','total','urlS3','keywords','status'));
    }

    public function groupPackagesCreate()
    {   
        $symbols = DB::table('currency_symbols')->where('iso_3', 'IND')->first();
        $symboldollar = DB::table('currency_symbols')->where('iso_3', 'USA')->first();
        $IconInclusions = IconInclusion::select("id","name","icon")->get();
        $difficulty = Difficulty::get();
        $tour_types = TourType::get();
        $tour_classes = TourClass::get();
        $tags = Tag::get();
        return view('grouppackages.basic_details_create',compact('IconInclusions','symbols','difficulty','tour_types','tour_classes','symboldollar','tags'));
    }

    public function groupPackagesStore(Request $request)
    {
        $data = $request->all();
        $user = auth()->user();
        // if($request->difficulty_tags){
        //     $array_difficulty_tags = array();
        //     for($i = 0; $i < count($request->difficulty_tags); $i++) {
        //         if ($request->difficulty_tags[$i] != '') {
        //             array_push($array_difficulty_tags, $request->difficulty_tags[$i]);
        //         }
        //     }
        // }
        // else{
        //     $array_difficulty_tags = [];
        // }
        $startFormat = $request->start_date;
        $start_date = date("yy-m-d", strtotime($startFormat));
        $unique = Departure::where('dep_dook_ref_id',$request->dep_dook_ref_id)
                ->first();
        if($unique){
            $departure = Departure::find($unique->id);
            $departure->title = $request->title;
            $departure->sub_title = $request->sub_title;
            $departure->no_of_days = $request->days;
            $departure->no_of_nights = $request->nights;
            $departure->description = $request->description;
            $departure->price_hide_show = $request->price_hide_show;
            $departure->book_online = $request->book_online;
            $departure->price_currency = $request->currency_symbol;
            $departure->price_currency_usd = $request->currency_symbol_usd;
            //$departure->price = $request->price;
            //$departure->price_usd = $request->price_usd;
            $departure->dep_dook_ref_id = $request->dep_dook_ref_id;
            $departure->slug_url_pre = $request->slug_url_pre;
            $departure->slug_url = $request->slug_url;
            //$departure->tour_class_tag = implode(",",$array_difficulty_tags);
            //$departure->tour_class_id = $request->tour_class;
            //$departure->team_size = $request->group_size;
            $departure->from = $request->starting_from;
            $departure->ending_at = $request->ending_at;
            //$departure->date = $start_date;

            $departure->meta_title = $request->meta_title;
            $departure->meta_keywords = $request->meta_keywords;
            $departure->meta_description = $request->meta_description;
            $departure->tenant_id = $user->tenant_id;
            $departure->user_id = $user->id;
            $departure->dep_type = "group";
            if($request->image_name){
                $image = $request->file('image_name');
                $extension = $image->getClientOriginalExtension();
                $imageName = Str::random(5).time() .'.'. $extension;
                $relPath = 'dook/images/package/';
                    if (!file_exists(public_path($relPath))) {
                        mkdir(public_path($relPath), 777, true);
                    }
                $img = Image::make($image)->save( public_path($relPath . $imageName ) );
                //$images = Image::make($image);
                //Storage::disk('spaces')->putFileAs('dook/images/package',$image, $imageName, 'public');
                $departure->image = $imageName;  
            }
            if($request->banner_image){
                $image = $request->file('banner_image');
                $extension = $image->getClientOriginalExtension();
                $imageName = Str::random(5).time() .'.'. $extension;
                $relPath = 'dook/images/package/';
                    if (!file_exists(public_path($relPath))) {
                        mkdir(public_path($relPath), 777, true);
                    }
                $img = Image::make($image)->save( public_path($relPath . $imageName ) );
                //$images = Image::make($image);
               // Storage::disk('spaces')->putFileAs('dook/images/package', $image, $imageName, 'public');
                $departure->banner_image = $imageName;  
            }

            // if($request->image_name){ 
            //     $file = $request->file('image_name');
            //     $imageName = Str::random(5).time().'.'.$file->getClientOriginalExtension();
            //         $relPath = 'dook/images/package/';
            //         if (!file_exists(public_path($relPath))) {
            //             mkdir(public_path($relPath), 777, true);
            //         }
            //     $img = Image::make($file)->save( public_path($relPath . $imageName ) ); 
            //     $departure->image = $imageName;
            // }
            // if($request->banner_image){ 
            //     $file = $request->file('banner_image');
            //     $imageName = Str::random(5).time().'.'.$file->getClientOriginalExtension();
            //         $relPath = 'dook/images/package/';
            //         if (!file_exists(public_path($relPath))) {
            //             mkdir(public_path($relPath), 777, true);
            //         }
            //     $img = Image::make($file)->save( public_path($relPath . $imageName ) ); 
            //     $departure->banner_image = $imageName;
            // }
            $departure->save();
            $last_id = $departure->id;
        }
        else{
            $departure = new Departure;
            $departure->title = $request->title;
            $departure->sub_title = $request->sub_title;
            $departure->no_of_days = $request->days;
            $departure->no_of_nights = $request->nights;
            $departure->description = $request->description;
            $departure->price_hide_show = $request->price_hide_show;
            $departure->book_online = $request->book_online;
            $departure->price_currency = $request->currency_symbol;
            $departure->price_currency_usd = $request->currency_symbol_usd;
            // $departure->price = $request->price;
            // $departure->price_usd = $request->price_usd;
            $departure->dep_dook_ref_id = $request->dep_dook_ref_id;
            $departure->slug_url_pre = $request->slug_url_pre;
            $departure->slug_url = $request->slug_url;
            //$departure->tour_class_tag = implode(",",$array_difficulty_tags);
            // $departure->tour_class_id = $request->tour_class;
            // $departure->team_size = $request->group_size;
            $departure->from = $request->starting_from;
            $departure->ending_at = $request->ending_at;
            //$departure->date = $start_date;

            $departure->meta_title = $request->meta_title;
            $departure->meta_keywords = $request->meta_keywords;
            $departure->meta_description = $request->meta_description;
            $departure->tenant_id = $user->tenant_id;
            $departure->user_id = $user->id;
            $departure->dep_type = "group";
            $departure->unique_key = Str::random(10).time();
            if($request->image_name){
                $image = $request->file('image_name');
                $extension = $image->getClientOriginalExtension();
                $imageName = Str::random(5).time() .'.'. $extension;
                $relPath = 'dook/images/package/';
                    if (!file_exists(public_path($relPath))) {
                        mkdir(public_path($relPath), 777, true);
                    }
                $img = Image::make($image)->save( public_path($relPath . $imageName ) );
                //$images = Image::make($image);
                //Storage::disk('spaces')->putFileAs('dook/images/package',$image, $imageName, 'public');
                $departure->image = $imageName;  
            }
            if($request->banner_image){
                $image = $request->file('banner_image');
                $extension = $image->getClientOriginalExtension();
                $imageName = Str::random(5).time() .'.'. $extension;
                $relPath = 'dook/images/package/';
                    if (!file_exists(public_path($relPath))) {
                        mkdir(public_path($relPath), 777, true);
                    }
                $img = Image::make($image)->save( public_path($relPath . $imageName ) );
                //$images = Image::make($image);
                //Storage::disk('spaces')->putFileAs('dook/images/package', $image, $imageName, 'public');
                $departure->banner_image = $imageName;  
            }

            // if($request->image_name){ 
            //     $file = $request->file('image_name');
            //     $imageName = Str::random(5).time().'.'.$file->getClientOriginalExtension();
            //         $relPath = 'dook/images/package/';
            //         if (!file_exists(public_path($relPath))) {
            //             mkdir(public_path($relPath), 777, true);
            //         }
            //     $img = Image::make($file)->save( public_path($relPath . $imageName ) ); 
            //     $departure->image = $imageName;
            // }
            // if($request->banner_image){ 
            //     $file = $request->file('banner_image');
            //     $imageName = Str::random(5).time().'.'.$file->getClientOriginalExtension();
            //         $relPath = 'dook/images/package/';
            //         if (!file_exists(public_path($relPath))) {
            //             mkdir(public_path($relPath), 777, true);
            //         }
            //     $img = Image::make($file)->save( public_path($relPath . $imageName ) ); 
            //     $departure->banner_image = $imageName;
            // }
            $departure->save();
            $last_id = $departure->id;
        }

        $someArray = json_decode($request->package_multi_img);
        if($someArray){
            foreach ($someArray as $mimages) {
                if($mimages != null){
                    $image = $mimages->Content;
                    $imagebase = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$image));
                    $imageName = Str::random(5).time() . '.jpg';
                    $relPath = 'dook/images/package/';
                        if (!file_exists(public_path($relPath))) {
                            mkdir(public_path($relPath), 777, true);
                        }
                    $img = Image::make($imagebase)->save( public_path($relPath . $imageName ) );
                    //Storage::disk('spaces')->put('dook/images/package/'.$imageName, $imagebase, 'public');
                   //  $relPath = 'dook/images/package/';
                   //      if (!file_exists(public_path($relPath))) {
                   //          mkdir(public_path($relPath), 777, true);
                   //      }
                   // // $path = public_path($relPath . $imageName);
                   //  $img = Image::make($imagebase)->save( public_path($relPath . $imageName) );
                    $departureImage= new DepartureImage;
                    $departureImage->departure_id = $last_id;
                    $departureImage->image = $imageName;
                    $departureImage->save(); 
                }               
            } 
        }
        $tags = $request->tags;
        if($tags){
            foreach ($tags as $tag) {
                $departure_tag= new DepartureTag;
                $departure_tag->departure_id = $last_id;
                $departure_tag->tag_id = $tag;
                $departure_tag->save(); 
            }               
        }
        $difficulty_tags = $request->difficulty_tags;
        if($difficulty_tags){
            foreach ($difficulty_tags as $d_tage) {
                $departure_difficulties= new DepartureDifficulty;
                $departure_difficulties->departure_id = $last_id;
                $departure_difficulties->difficulty_id = $d_tage;
                $departure_difficulties->save(); 
            }               
        } 

        $tour_type_tags = $request->type_tags;
        if($tour_type_tags){
            foreach ($tour_type_tags as $tt_tage) {
                $departure_difficulties= new DepartureTourType;
                $departure_difficulties->departure_id = $last_id;
                $departure_difficulties->tour_type_id = $tt_tage;
                $departure_difficulties->save(); 
            }               
        } 

        $destArrays = json_decode($request->destinations);
        if($destArrays){
            foreach ($destArrays as $value) {
                if($value != null){
                    $post = array(
                        'country_id' => $value->sub_continent_id
                    );

                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, env("pullIt_BaseUrl").'api/get_country_bys_regions');
                    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                    curl_setopt($curl, CURLOPT_POST, 1);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                    $response = curl_exec($curl);
                    $country_regions = json_decode($response);
                    //dd($country_regions);

                    $region_id_dook = array();
                    if(count($country_regions->regions) > 0){
                        foreach ($country_regions->regions as $region_value) {
                            $country_region_unique = Region::where('region_name',$region_value->region_name)->where('reference_id', $region_value->id)->first();
                            if($country_region_unique)
                            {
                                $r_count_id = Region::where('id',$country_region_unique->id)
                                            ->value('id');
                                $region  = Region::find($r_count_id);
                                $region->region_name = $region_value->region_name;
                                $region->reference_id = $region_value->id;
                                $region->save();
                                $region_last_id = $region->id;
                                array_push($region_id_dook, $region_last_id);
                            }
                            else{
                                $region  = new Region;
                                $region->region_name = $region_value->region_name;
                                $region->reference_id = $region_value->id;
                                $region->save();
                                $region_last_id = $region->id;
                                array_push($region_id_dook, $region_last_id);
                            }
                        }
                    }
                    
                    // $regionCheck = Region::where('region_name',$value->sub_continent_name)
                    //         ->where('reference_id', $value->sub_continent_id)
                    //         ->first();
                    // if($regionCheck == null)
                    // {
                    //     $region  = new Region;
                    //     $region->region_name = $value->sub_continent_name;
                    //     $region->reference_id = $value->sub_continent_id;
                    //     $region->save();
                    //     $region_last_id = $region->id;
                    //     $region_last_name = $region->region_name;
                    // }else
                    // {
                    //     $r_count_id = Region::where('id',$regionCheck->id)->value('id');
                    //     $region  = Region::find($r_count_id);
                    //     $region->region_name = $value->sub_continent_name;
                    //     $region->reference_id = $value->sub_continent_id;
                    //     $region->save();
                    //     $region_last_id = $region->id;
                    //     $region_last_name = $region->region_name;
                    // }
                    $region_id_str = implode(",",$region_id_dook);
                    //dd($region_id_str);
                    $countryCheck = Country::where('country_name',$value->country)
                            ->where('reference_id', $value->country_id)
                            ->first();
                    if($countryCheck == null)
                    {
                        $country  = new Country;
                        $country->country_name = $value->country;
                        $country->reference_id = $value->country_id;
                        $country->region_id = $region_id_str;
                        $country->latitude = $value->country_lat;
                        $country->longitude = $value->country_long;
                        $country->official_name = $value->official_name;
                        $country->capital = $value->capital;
                        $country->largest_city = $value->largest_city;
                        $country->continent = $value->continent;
                        $country->description = $value->count_description;
                        $country->sub_continent = $value->sub_continent;
                        $country->iso_2 = $value->count_iso2;
                        $country->iso_3 = $value->count_iso3;
                        $country->isd_code = $value->isd_code;

                        $country->internet_tld = $value->internet_tld;
                        $country->currency = $value->currency;
                        $country->currency_symbol = $value->currency_symbol;
                        $country->currency_code = $value->currency_code;
                        $country->drives_on = $value->drive_on;
                        $country->area = $value->area;
                        $country->area_unit = $value->area_unit;
                        $country->population = $value->population;
            
                        //$country->image = $value->country_image;
                        
                        $country->flag = $value->flag;
                        // Image crop FIT
                        $post = array(
                         'country_id' => $value->country_id
                         );

                         $curl = curl_init();
                          curl_setopt($curl, CURLOPT_URL, env("pullIt_BaseUrl").'api/get_national_lang_official_religens');
                          curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                          curl_setopt($curl, CURLOPT_POST, 1);
                          curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                          curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                          $response = curl_exec($curl);
                          $lang_religions = json_decode($response);
                          $country->national_language = $lang_religions->national_langs;
                          $country->official_religions = $lang_religions->official_rel;

                        // Country Image
                        // if($value->country_image || $value->country_image != '' || $value->country_image != null){ 
                        //     $image_url = "https://s3-pullit-bucket.s3.us-west-2.amazonaws.com/country/".$value->country_image;
                        
                        //     $type = pathinfo($image_url, PATHINFO_EXTENSION);
                        //     $data = file_get_contents($image_url);
                        //     $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        //     $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64));
                        //     $imageName = Str::random(5).time() . '.jpg';
                        //     $imageFile = Image::make($image)->fit(464, 260)->stream();
                        //     $imageFile = $imageFile->__toString();
                        //     $img = Storage::disk('s3')->put('dook/images/country/'.$imageName, $imageFile, 'public');
                        //     $country->image =  $imageName;
                        // }

                        $post = array(
                         'country_id' => $value->country_id
                         );

                         $curl = curl_init();
                          curl_setopt($curl, CURLOPT_URL, env("pullIt_BaseUrl").'api/get_country_visa_information');
                          curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                          curl_setopt($curl, CURLOPT_POST, 1);
                          curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                          curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                          $response = curl_exec($curl);
                          $visa_info = json_decode($response);
                          $country->visa_information = $visa_info->visa_informations;

                        $country->save();
                        $country_last_id = $country->id;
                        $country_last_name = $country->country_name;
                        $country_last_iso = $country->iso_3;
                       }
                       else{
                        $count_id = Country::where('id',$countryCheck->id)->value('id');
                        $country = Country::find($count_id);
                        $country->country_name = $value->country;
                        $country->reference_id = $value->country_id;
                        $country->region_id = $region_id_str;
                        $country->latitude = $value->country_lat;
                        $country->longitude = $value->country_long;
                        $country->official_name = $value->official_name;
                        $country->capital = $value->capital;
                        $country->largest_city = $value->largest_city;
                        $country->continent = $value->continent;
                        //$country->description = $value->count_description;
                        $country->sub_continent = $value->sub_continent;
                        $country->iso_2 = $value->count_iso2;
                        $country->iso_3 = $value->count_iso3;
                        $country->isd_code = $value->isd_code;

                        $country->internet_tld = $value->internet_tld;
                        $country->currency = $value->currency;
                        $country->currency_symbol = $value->currency_symbol;
                        $country->currency_code = $value->currency_code;
                        $country->drives_on = $value->drive_on;
                        $country->area = $value->area;
                        $country->area_unit = $value->area_unit;
                        $country->population = $value->population;
            
                        //$country->image = $value->country_image;
                        $country->flag = $value->flag;
                        //Official religion and national language
                        $post = array(
                         'country_id' => $value->country_id
                         );

                         $curl = curl_init();
                          curl_setopt($curl, CURLOPT_URL, env("pullIt_BaseUrl").'api/get_national_lang_official_religens');
                          curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                          curl_setopt($curl, CURLOPT_POST, 1);
                          curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                          curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                          $response = curl_exec($curl);
                          $lang_religions = json_decode($response);
                          $country->national_language = $lang_religions->national_langs;
                          $country->official_religions = $lang_religions->official_rel;

                        //Country Image
                        $post = array(
                         'country_id' => $value->country_id
                         );

                         $curl = curl_init();
                          curl_setopt($curl, CURLOPT_URL, env("pullIt_BaseUrl").'api/get_country_visa_information');
                          curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                          curl_setopt($curl, CURLOPT_POST, 1);
                          curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                          curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                          $response = curl_exec($curl);
                          $visa_info = json_decode($response);
                        $country->visa_information = $visa_info->visa_informations;
                        $country->save();
                        $country_last_id = $country->id;
                        $country_last_name = $country->country_name;
                        $country_last_iso = $country->iso_3;
                    }
                        $post = array(
                         'country_id' => $value->country_id
                         );

                         $curl = curl_init();
                          curl_setopt($curl, CURLOPT_URL, env("pullIt_BaseUrl").'api/get_countries_related_data');
                          curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                          curl_setopt($curl, CURLOPT_POST, 1);
                          curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                          curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                          $response = curl_exec($curl);
                          $country_datas = json_decode($response);
                            if(count($country_datas->best_time_to_visit) > 0){
                                foreach ($country_datas->best_time_to_visit as $btt_visit) {
                                    $country_visit = CountryBestTimeToVisit::where('name',$btt_visit->name)->where('country_id', $country_last_id)->first();
                                    if($country_visit == null)
                                    {
                                        $country_visits =new CountryBestTimeToVisit;
                                        $country_visits->country_id = $country_last_id;
                                        $country_visits->name = $btt_visit->name;
                                        $country_visits->save();
                                    }
                                }
                            }
                            //climate
                            if(count($country_datas->climate_type) > 0){
                                foreach ($country_datas->climate_type as $climate) {
                                    $climatetype = CountryClimateType::where('climate_type_name',$climate->name)->where('country_id', $country_last_id)->first();
                                    if($climatetype == null)
                                    {
                                        $climate_types =new CountryClimateType;
                                        $climate_types->country_id = $country_last_id;
                                        $climate_types->climate_type_name = $climate->name;
                                        $climate_types->save();
                                    }
                                }
                            }
                            //demonym
                            if(count($country_datas->demonym) > 0){
                                foreach ($country_datas->demonym as $demonymss) {
                                    $demonyem = CountryDemonym::where('demonym_name',$demonymss->name)->where('country_id', $country_last_id)->first();
                                    if($demonyem == null)
                                    {
                                        $country_demonymss =new CountryDemonym;
                                        $country_demonymss->country_id = $country_last_id;
                                        $country_demonymss->demonym_name = $demonymss->name;
                                        $country_demonymss->save();
                                    }
                                }
                            }
                            //electric_socket
                            if(count($country_datas->electric_socket) > 0){
                                foreach ($country_datas->electric_socket as $socket) {
                                    $sockets = CountryElectricalSocket::where('socket_name',$socket->name)->where('country_id', $country_last_id)->first();
                                    if($sockets == null)
                                    {
                                        $country_sockets =new CountryElectricalSocket;
                                        $country_sockets->country_id = $country_last_id;
                                        $country_sockets->socket_name = $socket->name;
                                        $country_sockets->description = $socket->description;
                                        $country_sockets->image = $socket->image;
                                        $country_sockets->save();
                                    }
                                }
                            }
                            //ethnicities
                            if(count($country_datas->ethnicitie) > 0){
                                foreach ($country_datas->ethnicitie as $ethnic) {
                                    $CountryEthnic = CountryEthnicities::where('ethnicity_name',$ethnic->name)->where('country_id', $country_last_id)->first();
                                    if($CountryEthnic == null)
                                    {
                                        $country_ethnics =new CountryEthnicities;
                                        $country_ethnics->country_id = $country_last_id;
                                        $country_ethnics->ethnicity_name = $ethnic->name;
                                        $country_ethnics->save();
                                    }
                                }
                            }
                            //Official language
                            if(count($country_datas->off_language) > 0){
                                foreach ($country_datas->off_language as $language) {
                                    $CountryLang = CountryOfficialLanguage::where('language_name',$language->name)->where('country_id', $country_last_id)->first();
                                    if($CountryLang == null)
                                    {
                                        $country_langs =new CountryOfficialLanguage;
                                        $country_langs->country_id = $country_last_id;
                                        $country_langs->language_name = $language->name;
                                        $country_langs->lang_code = $language->lang_code;
                                        $country_langs->save();
                                    }
                                }
                            }
                            //religions
                            if(count($country_datas->religion) > 0){
                                foreach ($country_datas->religion as $count_religion) {
                                    $country_religion = CountryReligion::where('religion_name',$count_religion->name)->where('country_id', $country_last_id)->first();
                                    if($country_religion == null)
                                    {
                                        $country_religions =new CountryReligion;
                                        $country_religions->country_id = $country_last_id;
                                        $country_religions->religion_name = $count_religion->name;
                                        $country_religions->percentage = $count_religion->percentage;
                                        $country_religions->save();
                                    }
                                }
                            }
                    $country_dep_unique = CountryDeparture::where('departure_id',$last_id)
                                            ->where('country_id', $country_last_id)
                                            ->first();
                        if($country_dep_unique == null || $country_dep_unique == '')
                        {
                            //$a=print_r($country_last_id);
                            $countryDepUpdate = new CountryDeparture;
                            $countryDepUpdate->departure_id = $last_id;
                            $countryDepUpdate->country_id = $country_last_id;
                            $countryDepUpdate->save();

                        }
                    //destination
                    $desti = Destination::where('dest_name',$value->name)
                            ->where('reference_id', $value->id)
                            ->first();
                    if($desti == null)
                    {
                        $destination  = new Destination;
                        $destination->dest_name = $value->name;
                        $destination->actualname = $value->actual_name;
                        $destination->country_name = $value->country;
                        $destination->region_id = $value->regionid;
                        $destination->country_id = $country_last_id;
                        $destination->reference_id = $value->id;
                        $destination->latitude = $value->lat;
                        $destination->longitude = $value->long;
                        $destination->feature_class = $value->fclass;
                        $destination->feature_code = $value->fcodes;
                        $destination->country_iso_2 = $value->iso2;
                        $destination->country_iso_3 = $value->iso3;
                        $destination->region_code = $value->regioncode;
                        $destination->region = $value->region;
                        $destination->geonameid = $value->geonameid;
                        //$destination->image = $value->destimg;
                        $destination->description = $value->description;
                        $destination->drives_on = $value->drive_on;
                        $destination->currency_symbol = $value->currency_symbol;
                        $destination->currency_code = $value->currency_code;
                        // if($value->destimg != "undefined")
                        // {
                        // $array = explode(' ', $value->destimg);
                        //    //$valid_img = strrpos($value->destimg, " ");
                        //    if(count($array) <=1)
                        //     {
                        //         $image_url = "https://s3-pullit-bucket.s3.us-west-2.amazonaws.com/destination/".$value->destimg;
                        //         $type = pathinfo($image_url, PATHINFO_EXTENSION);
                        //         $data = file_get_contents($image_url);
                        //         $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        //         $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64));
                        //         $imageName = Str::random(5).time() . '.jpg';
                        //         $imageFile = Image::make($image)->fit(464, 260)->stream();
                        //         $imageFile = $imageFile->__toString();
                        //         $img = Storage::disk('s3')->put('dook/images/poi/'.$imageName, $imageFile, 'public');
                        //         $destination->image =  $imageName;
                        //     }
                        // }
                        $destination->save();
                        $dest_last_id = $destination->id;
                        $dest_last_name = $destination->dest_name;
                    }
                    else{
                        $dest = Destination::where('id',$desti->id)->value('id');
                        $destination = Destination::find($dest);
                        $destination->dest_name = $value->name;
                        $destination->country_name = $value->country;
                        $destination->country_id = $country_last_id;
                        $destination->region_id = $value->regionid;
                        $destination->reference_id = $value->id;
                        $destination->latitude = $value->lat;
                        $destination->longitude = $value->long;
                        $destination->feature_class = $value->fclass;
                        $destination->feature_code = $value->fcodes;
                        $destination->country_iso_2 = $value->iso2;
                        $destination->country_iso_3 = $value->iso3;
                        $destination->region_code = $value->regioncode;
                        $destination->region = $value->region;
                        $destination->geonameid = $value->geonameid;
                        //$destination->image = $value->destimg;
                        //$destination->description = $value->description;
                        $destination->drives_on = $value->drive_on;
                        $destination->currency_symbol = $value->currency_symbol;
                        $destination->currency_code = $value->currency_code;
                        // if($value->destimg != "undefined")
                        // {
                        // if($value->destimg != "")
                        // {  
                        // $array = explode(' ', $value->destimg);
                        //    //$valid_img = strrpos($value->destimg, " ");
                        //    if(count($array) <=1)
                        //    {
                        //         $image_url = "https://s3-pullit-bucket.s3.us-west-2.amazonaws.com/destination/".$value->destimg;
                        //         $desti_image = Destination::where('reference_id',$value->id)
                        //                 ->select('image')
                        //                 ->first();
                        //         if(isset($desti_image->image) == '' || isset($desti_image->image) == null){
                        //             $type = pathinfo($image_url, PATHINFO_EXTENSION);
                        //             $data = file_get_contents($image_url);
                        //             $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        //             $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64));
                        //             $imageName = Str::random(5).time() . '.jpg';
                        //             $imageFile = Image::make($image)->fit(464, 260)->stream();
                        //             $imageFile = $imageFile->__toString();
                        //             $img = Storage::disk('s3')->put('dook/images/poi/'.$imageName, $imageFile, 'public');
                        //             $destination->image =  $imageName;
                        //         }
                        //     }
                        // }
                        // }
                        $destination->save();
                        $dest_last_id = $destination->id;
                        $dest_last_name = $destination->dest_name;
                    }

                    $dep_unique = DepartureDestination::where('departure_id',$last_id)
                                ->where('destination_id', $dest_last_id)
                                ->first();
                    if($dep_unique == null || $dep_unique == '')
                    {
                        $depdestination  = new DepartureDestination;
                        $depdestination->departure_id=$last_id;
                        $depdestination->destination_id=$dest_last_id;
                        $depdestination->user_id = $user->id;
                        $depdestination->save();
                    }

                    // $post = array(
                    //      'destination_id' => $value->id
                    // );
                    // $curl = curl_init();
                    // curl_setopt($curl, CURLOPT_URL, env("pullIt_BaseUrl").'api/get_desti_image');
                    // curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                    // curl_setopt($curl, CURLOPT_POST, 1);
                    // curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                    // curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                    // $response = curl_exec($curl);
                    // $dest_images = json_decode($response);
                    // //dd($dest_images);
                    // if(isset($dest_images->image) || isset($dest_images->image) != '' || isset($dest_images->image) != null)
                    // {
                    //     $array = explode(' ', $dest_images->image);
                    //        //$valid_img = strrpos($value->destimg, " ");
                    //     if(count($array) <=1)
                    //     {
                    //         $image_url = "https://s3-pullit-bucket.s3.us-west-2.amazonaws.com/destination/".$dest_images->image;
                    //         $desti_image = Destination::where('id',$dest_last_id)
                    //                     ->select('banner_image')
                    //                     ->first();
                    //         if($desti_image->banner_image == '' || $desti_image->banner_image == null){
                    //             $destination_image = Destination::find($dest_last_id);

                    //             $type = pathinfo($image_url, PATHINFO_EXTENSION);
                    //             $data = file_get_contents($image_url);
                    //             $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    //             $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64));
                    //             $imageName = Str::random(5).time() . '.jpg';
                    //             $imageFile = Image::make($image)->fit(1920, 768)->stream();
                    //             $imageFile = $imageFile->__toString();
                    //             $img = Storage::disk('s3')->put('dook/images/poi/'.$imageName, $imageFile, 'public');
                    //             $destination_image->banner_image =  $imageName;
                    //             $destination_image->save();
                    //         }
                    //     }
                    // }

                    //departure country region destination
                        
                    foreach ($region_id_dook as $key => $value_rid) {
                        $country_dep_unique = CountryDepartureDestinationRegion::where(['departure_id' => $last_id, 'country_id'=> $country_last_id, 'region_id'=>$value_rid, 'destination_id'=>$dest_last_id])
                                ->first();
                        if($country_dep_unique == null || $country_dep_unique == '')
                        {
                            $region_name = Region::where('id', $value_rid)
                                            ->select('region_name')
                                            ->first();
                            $countryDepUpdate = new CountryDepartureDestinationRegion;
                            $countryDepUpdate->country_id = $country_last_id;
                            $countryDepUpdate->country_name = $country_last_name;
                            $countryDepUpdate->iso_3 = $country_last_iso;
                            $countryDepUpdate->departure_id = $last_id;
                            $countryDepUpdate->region_id = $value_rid;
                            $countryDepUpdate->region_name = $region_name->region_name;
                            $countryDepUpdate->destination_id = $dest_last_id;
                            $countryDepUpdate->destination_name = $dest_last_name;
                            $countryDepUpdate->save();       
                        }
                    }
                    // Destination guide data
                        $post = array(
                            'destination_id' => $value->id
                        );

                        $curl = curl_init();
                        curl_setopt($curl, CURLOPT_URL, env("pullIt_BaseUrl").'api/get_destination_travel_data');
                        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                        curl_setopt($curl, CURLOPT_POST, 1);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                        $response = curl_exec($curl);
                        $destination_datas = json_decode($response);
                            if(count($destination_datas->best_time_to_visit) > 0){
                                foreach ($destination_datas->best_time_to_visit as $btt_visit) {
                                    $dest_visit = DestinationBestTimeToVisit::where('name',$btt_visit->name)->where('destination_id', $dest_last_id)->first();
                                    if($dest_visit == null)
                                    {
                                        $destination_visits = new DestinationBestTimeToVisit;
                                        $destination_visits->destination_id = $dest_last_id;
                                        $destination_visits->name = $btt_visit->name;
                                        $destination_visits->save();
                                    }
                                }
                            }
                    //climate
                        if(count($destination_datas->climate_type) > 0){
                            foreach ($destination_datas->climate_type as $climate) {
                                $climatetype = DestinationClimateType::where('name',$climate->name)->where('destination_id', $dest_last_id)->first();
                                if($climatetype == null)
                                {
                                    $climate_types = new DestinationClimateType;
                                    $climate_types->destination_id = $dest_last_id;
                                    $climate_types->name = $climate->name;
                                    $climate_types->save();
                                }
                            }
                        }
                    //climate
                        if(count($destination_datas->airport) > 0){
                            foreach ($destination_datas->airport as $air) {
                                $airports = DestinationAirport::where('airport_name',$air->name)->where('destination_id', $dest_last_id)->first();
                                if($airports == null)
                                {
                                    $airport_data = new DestinationAirport;
                                    $airport_data->destination_id = $dest_last_id;
                                    $airport_data->airport_name = $air->name;
                                    $airport_data->save();
                                }
                            }
                        }  
                }
            }   
        }
        $facilities = $request->icon_inclusion;
        if($facilities){
            foreach ($facilities as $value) {
                $facility  = new DepartureIconInclusion;
                $facility->departure_id=$last_id;
                $facility->icon_inclusion_id=$value;
                $facility->save();
            }   
        }
        $status = [
                'url'=> url('/group-package/poi',$last_id),
            ];
        return response()->json($status);
    }
    public function groupPackagesEdit(Request $request, $id)
    {
        //$urlS3 = "https://dook-international.sgp1.cdn.digitaloceanspaces.com/dook/images/package/";
        $urlS3 = url('/dook/images/package/').'/';
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $departures  = Departure::where('id',$id)
                    //->where('tenant_id',auth()->user()->tenant_id)
                    ->first();
        if($departures){
            $data = explode(",",$departures->tags);
            $departures['tags'] = $data;
        }
        else{
            $departures['tags'] = [];
        }
        $agent_iti_match = AgentItinerary::join('departures','departures.agent_itinerary_id','=','agent_itineraries.id')
                        ->select('agent_itineraries.id','agent_itineraries.title')
                        ->where('departures.id',$id)
                        ->first();
        $destinations = Destination::join('departure_destinations','departure_destinations.destination_id','=','destinations.id')
                        ->join('countries','countries.id','=','destinations.country_id')
                        //->join('regions','regions.id','=','countries.region_id')
                        ->where('departure_destinations.departure_id',$route_id)
                        ->select('destinations.dest_name as name','destinations.actualname','destinations.country_name as country','destinations.reference_id as id','destinations.latitude as lat','destinations.longitude as long','destinations.feature_class as f_class','destinations.feature_code as f_codes','destinations.geonameid','destinations.region','destinations.region_code as regioncode','destinations.region_id as dest_region_id','destinations.country_iso_2 as iso2','destinations.country_iso_3 as iso3','destinations.image','destinations.banner_image','destinations.description','countries.reference_id as count_id','countries.official_name','countries.capital','countries.largest_city','countries.continent','countries.description as count_des','countries.sub_continent','countries.iso_2','countries.iso_3','countries.isd_code','countries.latitude as count_lat','countries.longitude as count_long','countries.internet_tld','countries.currency','countries.currency_symbol','countries.currency_code','countries.drives_on','countries.area','countries.area_unit','countries.population','countries.image as count_image','countries.flag','countries.region_id as regionIds')
                            //,'regions.id as sub_cont_id','regions.region_name as sub_cont_name')
                        ->get();
                        //dd($destinations);
            $IconInclusions = IconInclusion::select("id","name","icon")->get();
            $depinclusions = DepartureIconInclusion::join('icon_inclusions','icon_inclusions.id','=','departure_icon_inclusions.icon_inclusion_id')
                        ->where('departure_icon_inclusions.departure_id',$route_id)
                        ->select("icon_inclusions.id","icon_inclusions.name","icon_inclusions.icon")
                        ->get();  
            $symbols = DB::table('currency_symbols')->where('iso_3', 'IND')->first();
            $symboldollar = DB::table('currency_symbols')->where('iso_3', 'USA')->first();
            $departureimages = DepartureImage::where('departure_id',$route_id)->get(); 
            $imagePath = [];
            if($departureimages){
              $i = 0;
              foreach ($departureimages as $value) {

                $imagePath[$i]['link'] = $urlS3.$value->image;
                $imagePath[$i]['name'] = $value->image;
                $i++;
              }
            }    
        $tags = Tag::get();
        $departure_tags = DepartureTag::where('departure_id', $id)->get();
        $difficulties = Difficulty::get();
        $departure_difficulties = DepartureDifficulty::where('departure_id', $id)->get();
        $tour_types = TourType::get();
        $departure_tour_types = DepartureTourType::where('departure_id', $id)->get();
        $tour_classes = TourClass::get();
            //dd($imagePath_banner);
        return view('grouppackages.basic_details_edit',compact('departures','destinations','agent_iti_match','IconInclusions','depinclusions','symbols','urlS3','imagePath','departureimages','departure_difficulties','difficulties','tour_types','departure_tour_types','tour_classes','symboldollar','tags','departure_tags'));
    }

    public function groupPackagesUpdate(Request $request, $id)
    {
        $data = $request->all();
        //dd($request->destCheckUncheck);
        $user = auth()->user();
        $startFormat = $request->start_date;
        // if($request->tags){
        //     $array_tags = array();
        //     for($i = 0; $i < count($request->tags); $i++) {
        //         if ($request->tags[$i] != '') {
        //             array_push($array_tags, $request->tags[$i]);
        //         }
        //     }
        // }
        // else{
        //     $array_tags = [];
        // }
        $start_date = date("yy-m-d", strtotime($startFormat));
        $departure = Departure::find($id);   
        $departure->title = $request->title;
        $departure->sub_title = $request->sub_title;
        $departure->no_of_days = $request->days;
        $departure->no_of_nights = $request->nights;
        $departure->from = $request->starting_from;
        $departure->ending_at = $request->ending_at;
        //$departure->date = $start_date;
        //$departure->tags = implode(",",$array_tags);
        //$departure->tour_class_id = $request->tour_class;
        $departure->description = $request->description;
        $departure->price_hide_show = $request->price_hide_show;
        $departure->book_online = $request->book_online;
        // $departure->price_currency = $request->currency_symbol;
        // $departure->price_currency_usd = $request->currency_symbol_usd;
        // $departure->price = $request->price;
        // $departure->price_usd = $request->price_usd;
        $departure->dep_dook_ref_id = $request->dep_dook_ref_id;
        $departure->slug_url = $request->slug_url;
        $departure->slug_url_pre = $request->slug_url_pre;
        $departure->meta_title = $request->meta_title;
        $departure->meta_keywords = $request->meta_keywords;
        $departure->meta_description = $request->meta_description;
        //$departure->user_id = $user->id;
        // $departure->tool_tip = $request->tool_tip;
        // $departure->agent_itinerary_id = $request->itinerary;
        if($request->image_name){
            $image = $request->file('image_name');
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::random(5).time() .'.'. $extension;
            $relPath = 'dook/images/package/';
                if (!file_exists(public_path($relPath))) {
                    mkdir(public_path($relPath), 777, true);
                }
            $img = Image::make($image)->save( public_path($relPath . $imageName ) );
            //$images = Image::make($image);
            //Storage::disk('spaces')->putFileAs('dook/images/package',$image, $imageName, 'public');
            $departure->image = $imageName;  
        }
        if($request->banner_image){
            $image = $request->file('banner_image');
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::random(5).time() .'.'. $extension;
            $relPath = 'dook/images/package/';
                if (!file_exists(public_path($relPath))) {
                    mkdir(public_path($relPath), 777, true);
                }
            $img = Image::make($image)->save( public_path($relPath . $imageName ) );
            //$images = Image::make($image);
            //Storage::disk('spaces')->putFileAs('dook/images/package', $image, $imageName, 'public');
            $departure->banner_image = $imageName;  
        }

        // if($request->image_name){ 
        //     $file = $request->file('image_name');
        //     $imageName = Str::random(5).time().'.'.$file->getClientOriginalExtension();
        //         $relPath = 'dook/images/package/';
        //         if (!file_exists(public_path($relPath))) {
        //             mkdir(public_path($relPath), 777, true);
        //         }
        //     $img = Image::make($file)->save( public_path($relPath . $imageName ) ); 
        //     $departure->image = $imageName;
        // }
        // if($request->banner_image){ 
        //     $file = $request->file('banner_image');
        //     $imageName = Str::random(5).time().'.'.$file->getClientOriginalExtension();
        //         $relPath = 'dook/images/package/';
        //         if (!file_exists(public_path($relPath))) {
        //             mkdir(public_path($relPath), 777, true);
        //         }
        //     $img = Image::make($file)->save( public_path($relPath . $imageName ) ); 
        //     $departure->banner_image = $imageName;
        // }
        $departure->save();
        $last_id = $departure->id;
        $last_id_dook = $departure->dep_dook_ref_id;

        $get_date_id = DepartureDate::where('departure_id', $last_id)->pluck('id')->toArray();
        if(count($get_date_id)){
            foreach ($get_date_id as $key => $value_id) {
                $departure_date = DepartureDate::find($value_id);
                $departure_date->dook_id = $last_id_dook;
                $departure_date->save();
            }
        }
        //Multiple images
        $someArray = json_decode($request->package_multi_img);
        if(empty($someArray)){
            //DepartureImage::where('departure_id', '=', $last_id)->delete();
        }
        else{
            DepartureImage::where('departure_id', '=', $last_id)->delete();
            foreach ($someArray as $mimages) {
                if($mimages != null){
                    $image = $mimages->Content;
                    $imagebase = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$image));
                    $imageName = Str::random(5).time() . '.jpg';
                    //$imageFile = Image::make($imagebase)->fit(500, 500)->stream();
                    //$imageFile = $imageFile->__toString();
                    //$imagebase->fit(500, 500);
                    $relPath = 'dook/images/package/';
                        if (!file_exists(public_path($relPath))) {
                            mkdir(public_path($relPath), 777, true);
                        }
                    $img = Image::make($imagebase)->save( public_path($relPath . $imageName ) );
                    //Storage::disk('spaces')->put('dook/images/package/'.$imageName, $imagebase, 'public');
                   //  $relPath = 'dook/images/package/';
                   //      if (!file_exists(public_path($relPath))) {
                   //          mkdir(public_path($relPath), 777, true);
                   //      }
                   // // $path = public_path($relPath . $imageName);
                   //  $img = Image::make($imagebase)->save( public_path($relPath . $imageName) );
                    $departureImage= new DepartureImage;
                    $departureImage->departure_id = $last_id;
                    $departureImage->image = $imageName;
                    $departureImage->save(); 
                }               
            } 
        }

        $tags = $request->tags;
        if($tags){
            DepartureTag::where('departure_id', $last_id)->delete();
            foreach ($tags as $tag) {
                $departure_tag= new DepartureTag;
                $departure_tag->departure_id = $last_id;
                $departure_tag->tag_id = $tag;
                $departure_tag->save(); 
            }               
        }
        //Difficulty Tags
        $difficulty_tags = $request->difficulty_tags;
        if($difficulty_tags){
            DepartureDifficulty::where('departure_id', $last_id)->delete();
            foreach ($difficulty_tags as $d_tage) {
                $departure_difficulties= new DepartureDifficulty;
                $departure_difficulties->departure_id = $last_id;
                $departure_difficulties->difficulty_id = $d_tage;
                $departure_difficulties->save(); 
            }               
        }
        //Tour type Tags
        $tour_type_tags = $request->type_tags;
        if($tour_type_tags){
            foreach ($tour_type_tags as $tt_tage) {
                $departure_difficulties= new DepartureTourType;
                $departure_difficulties->departure_id = $last_id;
                $departure_difficulties->tour_type_id = $tt_tage;
                $departure_difficulties->save(); 
            }               
        }
        if($request->destCheckUncheck == "YesUpdate"){
            $destArrays = json_decode($request->destinations);
            if($destArrays){
                DepartureDestination::where('departure_id', $last_id)->delete();
                CountryDeparture::where('departure_id', '=', $last_id)->delete();
                foreach ($destArrays as $value) {
                    if($value != null){

                        //Get regions
                        $regionId = $value->sub_continent_id;
                        $regionId_array = explode(",",$regionId);
                        //dd($regionId_array);
                        $region_id_array = array();
                        foreach ($regionId_array as $key => $r_value) {
                            //dd($r_value);
                            $region_id = Region::where('id', $r_value)
                                        ->select('reference_id')
                                        ->first();
                            if($region_id){
                                array_push($region_id_array, $region_id->reference_id);
                            }
                        }
                        $region_reff_ids_string = implode(",", $region_id_array);
                        //dd($region_ids_string);
                        $post = array(
                            'country_id' => $region_reff_ids_string
                        );

                        $curl = curl_init();
                        curl_setopt($curl, CURLOPT_URL, env("pullIt_BaseUrl").'api/get_country_bys_regions');
                        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                        curl_setopt($curl, CURLOPT_POST, 1);
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                        $response = curl_exec($curl);
                        $country_regions = json_decode($response);
                        // print_r($country_regions);
                        // die;

                        $region_id_dook = array();
                        if(count($country_regions->regions) > 0){
                            foreach ($country_regions->regions as $region_value) {
                                $country_region_unique = Region::where('region_name',$region_value->region_name)->where('reference_id', $region_value->id)->first();
                                if($country_region_unique)
                                {
                                    $r_count_id = Region::where('id',$country_region_unique->id)
                                                ->value('id');
                                    $region  = Region::find($r_count_id);
                                    $region->region_name = $region_value->region_name;
                                    $region->reference_id = $region_value->id;
                                    $region->save();
                                    $region_last_id = $region->id;
                                    array_push($region_id_dook, $region_last_id);
                                }
                                else{
                                    $region  = new Region;
                                    $region->region_name = $region_value->region_name;
                                    $region->reference_id = $region_value->id;
                                    $region->save();
                                    $region_last_id = $region->id;
                                    array_push($region_id_dook, $region_last_id);
                                }
                            }
                        }
                        $region_id_str = implode(",", $region_id_dook);
                        // $regionCheck = Region::where('region_name',$value->sub_continent_name)
                        //         ->where('reference_id', $value->sub_continent_id)
                        //         ->first();
                        // if($regionCheck == null)
                        // {
                        //     $region  = new Region;
                        //     $region->region_name = $value->sub_continent_name;
                        //     $region->reference_id = $value->sub_continent_id;
                        //     $region->save();
                        //     $region_last_id = $region->id;
                        //     $region_last_name = $region->region_name;
                        // }else
                        // {
                        //     $r_count_id = Region::where('id',$regionCheck->id)->value('id');
                        //     $region  = Region::find($r_count_id);
                        //     $region->region_name = $value->sub_continent_name;
                        //     $region->reference_id = $value->sub_continent_id;
                        //     $region->save();
                        //     $region_last_id = $region->id;
                        //     $region_last_name = $region->region_name;
                        // }

                        $countryCheck = Country::where('country_name',$value->country)
                                ->where('reference_id', $value->country_id)
                                ->first();
                        if($countryCheck == null || $countryCheck == '')
                        {
                            $country  = new Country;
                            $country->country_name = $value->country;
                            $country->reference_id = $value->country_id;
                            $country->region_id = $region_id_str;
                            $country->latitude = $value->country_lat;
                            $country->longitude = $value->country_long;
                            $country->official_name = $value->official_name;
                            $country->capital = $value->capital;
                            $country->largest_city = $value->largest_city;
                            $country->continent = $value->continent;
                            $country->description = $value->count_description;
                            $country->sub_continent = $value->sub_continent;
                            $country->iso_2 = $value->count_iso2;
                            $country->iso_3 = $value->count_iso3;
                            $country->isd_code = $value->isd_code;

                            $country->internet_tld = $value->internet_tld;
                            $country->currency = $value->currency;
                            $country->currency_symbol = $value->currency_symbol;
                            $country->currency_code = $value->currency_code;
                            $country->drives_on = $value->drive_on;
                            $country->area = $value->area;
                            $country->area_unit = $value->area_unit;
                            $country->population = $value->population;
                
                            //$country->image = $value->country_image;
                            $country->flag = $value->flag;
                            //Official religion and national language
                            $post = array(
                             'country_id' => $value->country_id
                             );

                             $curl = curl_init();
                              curl_setopt($curl, CURLOPT_URL, env("pullIt_BaseUrl").'api/get_national_lang_official_religens');
                              curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                              curl_setopt($curl, CURLOPT_POST, 1);
                              curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                              curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                              $response = curl_exec($curl);
                              $lang_religions = json_decode($response);
                              $country->national_language = $lang_religions->national_langs;
                              $country->official_religions = $lang_religions->official_rel;

                            // Country Images
                            // if($value->country_image || $value->country_image != '' || $value->country_image != null){
                            //     $image_url = "https://s3-pullit-bucket.s3.us-west-2.amazonaws.com/country/".$value->country_image;
                            //     $type = pathinfo($image_url, PATHINFO_EXTENSION);
                            //     $data = file_get_contents($image_url);
                            //     $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                            //     $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64));
                            //     $imageName = Str::random(5).time() . '.jpg';
                            //     $imageFile = Image::make($image)->fit(464, 260)->stream();
                            //     $imageFile = $imageFile->__toString();
                            //     $img = Storage::disk('s3')->put('dook/images/country/'.$imageName, $imageFile, 'public');
                            //     $country->image =  $imageName;
                            // }
                            // Curl to get visa information
                             $post = array(
                             'country_id' => $value->country_id
                             );

                             $curl = curl_init();
                              curl_setopt($curl, CURLOPT_URL, env("pullIt_BaseUrl").'api/get_country_visa_information');
                              curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                              curl_setopt($curl, CURLOPT_POST, 1);
                              curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                              curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                              $response = curl_exec($curl);
                              $visa_info = json_decode($response);
                            $country->visa_information = $visa_info->visa_informations;
                            $country->save();
                            $country_last_id = $country->id;
                            $country_last_name = $country->country_name;
                            $country_last_iso = $country->iso_3;
                        }
                        else{
                            $count_id = Country::where('id',$countryCheck->id)->value('id');
                            $country = Country::find($count_id);
                            $country->country_name = $value->country;
                            $country->reference_id = $value->country_id;
                            $country->region_id = $region_id_str;
                            $country->latitude = $value->country_lat;
                            $country->longitude = $value->country_long;
                            $country->official_name = $value->official_name;
                            $country->capital = $value->capital;
                            $country->largest_city = $value->largest_city;
                            $country->continent = $value->continent;
                            //$country->description = $value->count_description;
                            $country->sub_continent = $value->sub_continent;
                            $country->iso_2 = $value->count_iso2;
                            $country->iso_3 = $value->count_iso3;
                            $country->isd_code = $value->isd_code;

                            $country->internet_tld = $value->internet_tld;
                            $country->currency = $value->currency;
                            $country->currency_symbol = $value->currency_symbol;
                            $country->currency_code = $value->currency_code;
                            $country->drives_on = $value->drive_on;
                            $country->area = $value->area;
                            $country->area_unit = $value->area_unit;
                            $country->population = $value->population;
                
                            //$country->image = $value->country_image;
                            $country->flag = $value->flag;
                            //Official religion and national language
                            $post = array(
                             'country_id' => $value->country_id
                             );

                             $curl = curl_init();
                              curl_setopt($curl, CURLOPT_URL, env("pullIt_BaseUrl").'api/get_national_lang_official_religens');
                              curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                              curl_setopt($curl, CURLOPT_POST, 1);
                              curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                              curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                              $response = curl_exec($curl);
                              $lang_religions = json_decode($response);
                              $country->national_language = $lang_religions->national_langs;
                              $country->official_religions = $lang_religions->official_rel;

                            //Country Image
                            $post = array(
                             'country_id' => $value->country_id
                             );

                             $curl = curl_init();
                              curl_setopt($curl, CURLOPT_URL, env("pullIt_BaseUrl").'api/get_country_visa_information');
                              curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                              curl_setopt($curl, CURLOPT_POST, 1);
                              curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                              curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                              $response = curl_exec($curl);
                              $visa_info = json_decode($response);
                            $country->visa_information = $visa_info->visa_informations;
                            $country->save();
                            $country_last_id = $country->id;
                            $country_last_name = $country->country_name;
                            $country_last_iso = $country->iso_3;
                        }
                            $post = array(
                             'country_id' => $value->country_id
                             );

                             $curl = curl_init();
                              curl_setopt($curl, CURLOPT_URL, env("pullIt_BaseUrl").'api/get_countries_related_data');
                              curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                              curl_setopt($curl, CURLOPT_POST, 1);
                              curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                              curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                              $response = curl_exec($curl);
                              $country_datas = json_decode($response);
                                if(count($country_datas->best_time_to_visit) > 0){
                                    foreach ($country_datas->best_time_to_visit as $btt_visit) {
                                        $country_visit = CountryBestTimeToVisit::where('name',$btt_visit->name)->where('country_id', $country_last_id)->first();
                                        if($country_visit == null)
                                        {
                                            $country_visits =new CountryBestTimeToVisit;
                                            $country_visits->country_id = $country_last_id;
                                            $country_visits->name = $btt_visit->name;
                                            $country_visits->save();
                                        }
                                    }
                                }
                                //climate
                                if(count($country_datas->climate_type) > 0){
                                    foreach ($country_datas->climate_type as $climate) {
                                        $climatetype = CountryClimateType::where('climate_type_name',$climate->name)->where('country_id', $country_last_id)->first();
                                        if($climatetype == null)
                                        {
                                            $climate_types =new CountryClimateType;
                                            $climate_types->country_id = $country_last_id;
                                            $climate_types->climate_type_name = $climate->name;
                                            $climate_types->save();
                                        }
                                    }
                                }
                                //demonym
                                if(count($country_datas->demonym) > 0){
                                    foreach ($country_datas->demonym as $demonymss) {
                                        $demonyem = CountryDemonym::where('demonym_name',$demonymss->name)->where('country_id', $country_last_id)->first();
                                        if($demonyem == null)
                                        {
                                            $country_demonymss =new CountryDemonym;
                                            $country_demonymss->country_id = $country_last_id;
                                            $country_demonymss->demonym_name = $demonymss->name;
                                            $country_demonymss->save();
                                        }
                                    }
                                }
                                //electric_socket
                                if(count($country_datas->electric_socket) > 0){
                                    foreach ($country_datas->electric_socket as $socket) {
                                        $sockets = CountryElectricalSocket::where('socket_name',$socket->name)->where('country_id', $country_last_id)->first();
                                        if($sockets == null)
                                        {
                                            $country_sockets =new CountryElectricalSocket;
                                            $country_sockets->country_id = $country_last_id;
                                            $country_sockets->socket_name = $socket->name;
                                            $country_sockets->description = $socket->description;
                                            $country_sockets->image = $socket->image;
                                            $country_sockets->save();
                                        }
                                    }
                                }
                                //ethnicities
                                if(count($country_datas->ethnicitie) > 0){
                                    foreach ($country_datas->ethnicitie as $ethnic) {
                                        $CountryEthnic = CountryEthnicities::where('ethnicity_name',$ethnic->name)->where('country_id', $country_last_id)->first();
                                        if($CountryEthnic == null)
                                        {
                                            $country_ethnics =new CountryEthnicities;
                                            $country_ethnics->country_id = $country_last_id;
                                            $country_ethnics->ethnicity_name = $ethnic->name;
                                            $country_ethnics->save();
                                        }
                                    }
                                }
                                //Official language
                                if(count($country_datas->off_language) > 0){
                                    foreach ($country_datas->off_language as $language) {
                                        $CountryLang = CountryOfficialLanguage::where('language_name',$language->name)->where('country_id', $country_last_id)->first();
                                        if($CountryLang == null)
                                        {
                                            $country_langs =new CountryOfficialLanguage;
                                            $country_langs->country_id = $country_last_id;
                                            $country_langs->language_name = $language->name;
                                            $country_langs->lang_code = $language->lang_code;
                                            $country_langs->save();
                                        }
                                    }
                                }
                                //religions
                                if(count($country_datas->religion) > 0){
                                    foreach ($country_datas->religion as $count_religion) {
                                        $country_religion = CountryReligion::where('religion_name',$count_religion->name)->where('country_id', $country_last_id)->first();
                                        if($country_religion == null)
                                        {
                                            $country_religions =new CountryReligion;
                                            $country_religions->country_id = $country_last_id;
                                            $country_religions->religion_name = $count_religion->name;
                                            $country_religions->percentage = $count_religion->percentage;
                                            $country_religions->save();
                                        }
                                    }
                                }
                        $country_dep_unique = CountryDeparture::where('departure_id',$last_id)
                                                ->where('country_id', $country_last_id)
                                                ->first();
                            if($country_dep_unique == null || $country_dep_unique == '')
                            {
                                //$a=print_r($country_last_id);
                                $countryDepUpdate = new CountryDeparture;
                                $countryDepUpdate->departure_id = $last_id;
                                $countryDepUpdate->country_id = $country_last_id;
                                $countryDepUpdate->save();

                            }
                        //Departure
                        $desti = Destination::where('dest_name',$value->name)
                                ->where('reference_id', $value->id)
                                ->first();
                        if($desti == null || $desti == '')
                        {
                            $destination  = new Destination;
                            $destination->dest_name = $value->name;
                            $destination->actualname = $value->actual_name;
                            $destination->country_name = $value->country;
                            $destination->region_id = $value->regionid;
                            $destination->country_id = $country_last_id;
                            $destination->reference_id = $value->id;
                            $destination->latitude = $value->lat;
                            $destination->longitude = $value->long;
                            $destination->feature_class = $value->fclass;
                            $destination->feature_code = $value->fcodes;
                            $destination->country_iso_2 = $value->iso2;
                            $destination->country_iso_3 = $value->iso3;
                            $destination->region_code = $value->regioncode;
                            $destination->region = $value->region;
                            $destination->geonameid = $value->geonameid;
                            //$destination->image = $value->destimg;
                            $destination->description = $value->description;
                            $destination->drives_on = $value->drive_on;
                            $destination->currency_symbol = $value->currency_symbol;
                            $destination->currency_code = $value->currency_code;
                            // if($value->destimg != "undefined")
                            // {
                            //     if($value->destimg != "")
                            //     {
                            // $array = explode(' ', $value->destimg);
                            //    //$valid_img = strrpos($value->destimg, " ");
                            //    if(count($array) <=1)
                            //    {
                            //     $image_url = "https://s3-pullit-bucket.s3.us-west-2.amazonaws.com/destination/".$value->destimg;
                            //     $desti_image = Destination::where('reference_id',$value->id)
                            //             ->select('image')
                            //             ->first();
                            //         if($desti_image->image == '' || $desti_image->image == null){
                            //             $type = pathinfo($image_url, PATHINFO_EXTENSION);
                            //             $data = file_get_contents($image_url);
                            //             $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                            //             $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64));
                            //             $imageName = Str::random(5).time() . '.jpg';
                            //             $imageFile = Image::make($image)->fit(464, 260)->stream();
                            //             $imageFile = $imageFile->__toString();
                            //             $img = Storage::disk('s3')->put('dook/images/poi/'.$imageName, $imageFile, 'public');
                            //             $destination->image =  $imageName;
                            //         }
                            //     }
                            // }
                            // }
                            $destination->save();
                            $dest_last_id = $destination->id;
                            $dest_last_name = $destination->dest_name;
                        }
                        else{
                            //$dest = Destination::where('id',$desti->id)->value('id');

                            $destination = Destination::find($desti->id);
                            $destination->dest_name = $value->name;
                            $destination->actualname = $value->actual_name;
                            $destination->country_name = $value->country;
                            $destination->region_id = $value->regionid;
                            $destination->country_id = $country_last_id;
                            $destination->reference_id = $value->id;
                            $destination->latitude = $value->lat;
                            $destination->longitude = $value->long;
                            $destination->feature_class = $value->fclass;
                            $destination->feature_code = $value->fcodes;
                            $destination->country_iso_2 = $value->iso2;
                            $destination->country_iso_3 = $value->iso3;
                            $destination->region_code = $value->regioncode;
                            $destination->region = $value->region;
                            $destination->geonameid = $value->geonameid;
                            //$destination->image = $value->destimg;
                            //$destination->description = $value->description;
                            $destination->drives_on = $value->drive_on;
                            $destination->currency_symbol = $value->currency_symbol;
                            $destination->currency_code = $value->currency_code;
                            // if($value->destimg != "undefined")
                            // {
                            //     if($value->destimg != "")
                            //     {
                            //    $array = explode(' ', $value->destimg);
                            //    //$valid_img = strrpos($value->destimg, " ");
                            //    if(count($array) <=1)
                            //    {
                            //     $image_url = "https://s3-pullit-bucket.s3.us-west-2.amazonaws.com/destination/".$value->destimg;
                            //         $desti_image = Destination::where('reference_id',$value->id)
                            //                 ->select('image')
                            //                 ->first();
                            //         if(isset($desti_image->image) == '' || isset($desti_image->image) == null){
                            //             $type = pathinfo($image_url, PATHINFO_EXTENSION);
                            //             $data = file_get_contents($image_url);
                            //             $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                            //             $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64));
                            //             $imageName = Str::random(5).time() . '.jpg';
                            //             $imageFile = Image::make($image)->fit(464, 260)->stream();
                            //             $imageFile = $imageFile->__toString();
                            //             $img = Storage::disk('s3')->put('dook/images/poi/'.$imageName, $imageFile, 'public');
                            //             $destination->image =  $imageName;
                            //         }
                            //    } 
                            // }    
                            // }
                            $destination->save();
                            $dest_last_id = $destination->id;
                            $dest_last_name = $destination->dest_name;
                        }

                        $dep_unique = DepartureDestination::where('departure_id',$last_id)
                                    ->where('destination_id', $dest_last_id)
                                    ->first();
                        if($dep_unique == null || $dep_unique == '')
                        {
                            $depdestination  = new DepartureDestination;
                            $depdestination->departure_id=$last_id;
                            $depdestination->destination_id=$dest_last_id;
                            $depdestination->user_id = $user->id;
                            $depdestination->save();
                        }
                        // $post = array(
                        //      'destination_id' => $value->id
                        // );
                        // $curl = curl_init();
                        // curl_setopt($curl, CURLOPT_URL, env("pullIt_BaseUrl").'api/get_desti_image');
                        // curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                        // curl_setopt($curl, CURLOPT_POST, 1);
                        // curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                        // curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                        // $response = curl_exec($curl);
                        // $dest_images = json_decode($response);
                        
                        // if(isset($dest_images->image) || isset($dest_images->image) != '' || isset($dest_images->image) != null)
                        // {
                        //     $array = explode(' ', $dest_images->image);
                        //        //$valid_img = strrpos($value->destimg, " ");
                        //     if(count($array) <=1)
                        //     {
                        //         $image_url = "https://s3-pullit-bucket.s3.us-west-2.amazonaws.com/destination/".$dest_images->image;
                        //         $desti_image = Destination::where('id',$dest_last_id)
                        //                     ->select('banner_image')
                        //                     ->first();
                        //         if($desti_image->banner_image == '' || $desti_image->banner_image == null){
                        //             $destination_image = Destination::find($dest_last_id);

                        //             $type = pathinfo($image_url, PATHINFO_EXTENSION);
                        //             $data = file_get_contents($image_url);
                        //             $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        //             $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64));
                        //             $imageName = Str::random(5).time() . '.jpg';
                        //             $imageFile = Image::make($image)->fit(1920, 768)->stream();
                        //             $imageFile = $imageFile->__toString();
                        //             $img = Storage::disk('s3')->put('dook/images/poi/'.$imageName, $imageFile, 'public');
                        //             $destination_image->banner_image =  $imageName;
                        //             $destination_image->save();
                        //         }
                        //     }
                        // }
                        //departure country region destination
                        foreach ($region_id_dook as $key => $value_rid) {
                            $country_dep_unique = CountryDepartureDestinationRegion::where(['departure_id' => $last_id, 'country_id'=> $country_last_id, 'region_id'=>$value_rid, 'destination_id'=>$dest_last_id])
                                    ->first();
                            if($country_dep_unique == null || $country_dep_unique == '')
                            {
                                $region_name = Region::where('id', $value_rid)
                                                ->select('region_name')
                                                ->first();
                                $countryDepUpdate = new CountryDepartureDestinationRegion;
                                $countryDepUpdate->country_id = $country_last_id;
                                $countryDepUpdate->country_name = $country_last_name;
                                $countryDepUpdate->iso_3 = $country_last_iso;
                                $countryDepUpdate->departure_id = $last_id;
                                $countryDepUpdate->region_id = $value_rid;
                                $countryDepUpdate->region_name = $region_name->region_name;
                                $countryDepUpdate->destination_id = $dest_last_id;
                                $countryDepUpdate->destination_name = $dest_last_name;
                                $countryDepUpdate->save();       
                            }
                        }
                        // $country_dep_unique = CountryDepartureDestinationRegion::where(['departure_id' => $last_id, 'country_id'=> $country_last_id, 'region_id'=>$region_last_id, 'destination_id'=>$dest_last_id])->first();

                        //     if($country_dep_unique == null || $country_dep_unique == '')
                        //     {
                        //         $countryDepUpdate = new CountryDepartureDestinationRegion;
                        //         $countryDepUpdate->country_id = $country_last_id;
                        //         $countryDepUpdate->country_name = $country_last_name;
                        //         $countryDepUpdate->iso_3 = $country_last_iso;
                        //         $countryDepUpdate->departure_id = $last_id;
                        //         $countryDepUpdate->region_id = $region_last_id;
                        //         $countryDepUpdate->region_name = $region_last_name;
                        //         $countryDepUpdate->destination_id = $dest_last_id;
                        //         $countryDepUpdate->destination_name = $dest_last_name;
                        //         $countryDepUpdate->save();
                        //     }
                        // Destination guide data
                            $post = array(
                                'destination_id' => $value->id
                            );

                            $curl = curl_init();
                            curl_setopt($curl, CURLOPT_URL, env("pullIt_BaseUrl").'api/get_destination_travel_data');
                            curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                            curl_setopt($curl, CURLOPT_POST, 1);
                            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                            $response = curl_exec($curl);
                            $destination_datas = json_decode($response);
                                if(count($destination_datas->best_time_to_visit) > 0){
                                    foreach ($destination_datas->best_time_to_visit as $btt_visit) {
                                        $dest_visit = DestinationBestTimeToVisit::where('name',$btt_visit->name)->where('destination_id', $dest_last_id)->first();
                                        if($dest_visit == null)
                                        {
                                            $destination_visits = new DestinationBestTimeToVisit;
                                            $destination_visits->destination_id = $dest_last_id;
                                            $destination_visits->name = $btt_visit->name;
                                            $destination_visits->save();
                                        }
                                    }
                                }
                        //climate
                            if(count($destination_datas->climate_type) > 0){
                                foreach ($destination_datas->climate_type as $climate) {
                                    $climatetype = DestinationClimateType::where('name',$climate->name)->where('destination_id', $dest_last_id)->first();
                                    if($climatetype == null)
                                    {
                                        $climate_types = new DestinationClimateType;
                                        $climate_types->destination_id = $dest_last_id;
                                        $climate_types->name = $climate->name;
                                        $climate_types->save();
                                    }
                                }
                            }
                        //climate
                            if(count($destination_datas->airport) > 0){
                                foreach ($destination_datas->airport as $air) {
                                    $airports = DestinationAirport::where('airport_name',$air->name)->where('destination_id', $dest_last_id)->first();
                                    if($airports == null)
                                    {
                                        $airport_data = new DestinationAirport;
                                        $airport_data->destination_id = $dest_last_id;
                                        $airport_data->airport_name = $air->name;
                                        $airport_data->save();
                                    }
                                }
                            }
                    }
                }   
            }
        }

        $facilities = $request->icon_inclusion;
        if($facilities){
            DepartureIconInclusion::where('departure_id', $last_id)->delete();
            foreach ($facilities as $value) {
                $facility  = new DepartureIconInclusion;
                $facility->departure_id=$last_id;
                $facility->icon_inclusion_id=$value;
                $facility->save();
            }   
        }
        $status = [
                'url'=> url('/group-package/poi',$last_id),
            ];
        return response()->json($status);
    }

    public function groupPackagesDisable(Request $request, $id)
    {
        $departures  = Departure::find($id);
        if($departures->status == 1){
            $departures->status = 0;
            $departures->save();
        }
        else{
            $departures->status = 1;
            $departures->save();
        }
        
        return response()->json(['success'=>'Group Tour disabled successfully!']);
    }

    public function makePopularPackages(Request $request, $id)
    {
        $package  = Departure::find($id);
        if($package->featured == 1){
            $package->featured = 0;
            $package->featured_position = 1000;
            $package->save();

            $featured_position_ids  = Departure::where('slug_url_pre','group-tours')
            ->where('dep_type','main')
            ->where('featured',1)
            ->where('featured_position', '<', 999)
            ->orderBy('featured_position')
            ->pluck('id');

            foreach($featured_position_ids as $key => $featured_position_id){
                $featured_position_data = Departure::find($featured_position_id);
                $featured_position_data->featured_position = $key + 1;
                $featured_position_data->save();
            } 
        }
        else{
            $maxFeaturedPosition  = Departure::where('slug_url_pre','group-tours')
                ->where('dep_type','main')
                ->where('featured',1)
                ->where('featured_position', '<', 999)
                ->max('featured_position') ?? 0;

            $package->featured = 1;
            $package->featured_position = $maxFeaturedPosition + 1;
            $package->save();

            $featured_position_ids  = Departure::where('slug_url_pre','group-tours')
                ->where('dep_type','main')
                ->where('featured',1)
                ->where('featured_position', '<', 999)
                ->orderBy('featured_position')
                ->pluck('id');

            foreach($featured_position_ids as $key => $featured_position_id){
                $featured_position_data = Departure::find($featured_position_id);
                $featured_position_data->featured_position = $key + 1;
                $featured_position_data->save();
            }    
        }
        
        return response()->json(['success'=>'Success!']);
    }

    public function addToEMT(Request $request, $id)
    {
        $package  = Departure::find($id);
        if($package->emt == 1){
            $package->emt = 0;
            $package->save();
        }
        else{
            $package->emt = 1;
            $package->save();
        }
        
        return response()->json(['success'=>'Success!']);
    }

    // Term & Conditions Function

    public function groupTermConditionIndex(Request $request)
    {   
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $termconditionDefault = DB::table('term_and_conditions')->first();
       // dd($termconditionDefault);
        $termconditions = Departure::select("conditions")
                        ->where('id',$route_id)
                        ->first();
        return view('grouppackages.term_conditions',compact('termconditions','termconditionDefault'));
    }

    public function groupTermConditionUpdate(Request $request)
    {   
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $termconditions  = Departure::find($route_id);

        $termconditions->conditions = $request->conditions;
        $termconditions->save();
        $status = [
                'status'=> "Success!",
            ];
        return response()->json($status);
    }

    //Term & Conditions Function End

    // Visa Informations Function

    public function groupVisaInformationIndex(Request $request)
    {   
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $country = DB::table('departure_destinations')->join('destinations','destinations.id','=','departure_destinations.destination_id')
                    ->where('departure_destinations.departure_id','=',$route_id)
                    ->pluck('destinations.country_id');
        //dd($country);
        if(count($country)>0){
            foreach ($country as $key => $value) {
                $countries[] = DB::table('countries')
                            ->where('id',$value)
                            ->select('country_name','visa_information')
                            ->first();
            }
        }
        else{
            $countries[] = [];
        }
        // departure data
        $visainformation = Departure::select("country_name", "visa_information")
                        ->where('id',$route_id)
                        ->first();

        if($visainformation){
            $data = explode("groupDook",$visainformation->visa_information);
            $visainformation['visa_information'] = $data;
        }
        else{
            $visainformation['visa_information'] = [];
        }

        //Country

        if($visainformation){
            $data = explode(",",$visainformation->country_name);
            $visainformation['country_name'] = $data;
        }
        else{
            $visainformation['country_name'] = [];
        }

        $i = 0;
        foreach ($visainformation->country_name as $value) {
            $visa_info = (array_key_exists($i, $visainformation['visa_information']))?$visainformation['visa_information'][$i]:'';

            $new_data[] = ['country' => $value, 'visa' =>$visa_info];
            $i++;
        }
        $obj = (object) $new_data;

        return view('grouppackages.visa_information',compact('visainformation','countries','obj','new_data'));
    }

    public function groupVisaInformationUpdate(Request $request)
    {   
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        //Visa
        if($request->visa_information){
            $array_visa_information = array();
            for($i = 0; $i < count($request->visa_information); $i++) {
                if ($request->visa_information[$i] != '') {
                    array_push($array_visa_information, $request->visa_information[$i]);
                }
            }
        }
        else{
            $array_visa_information = [];
        }
        //Country
        if($request->country){
            $array_country = array();
            for($i = 0; $i < count($request->country); $i++) {
                if ($request->country[$i] != '') {
                    array_push($array_country, $request->country[$i]);
                }
            }
        }
        else{
            $array_country = [];
        }
        $visainformation  = Departure::find($route_id);
        $visainformation->visa_information = implode("groupDook", $array_visa_information);
        $visainformation->country_name = implode(",", $array_country);
        $visainformation->save();
        $status = [
                'status'=> "Success!",
            ];
        return response()->json($status);
    }
    //Visa Informations Function End
}
