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
use App\Departure;
use App\Destination;
use App\Country;
use App\Region;
use App\CountryDeparture;
use App\Itinerary;
use App\Inclusion;
use App\DepartureImage;
use App\DepartureDestination;
use App\Experience;
use App\DestinationExperience;
use App\AgentItinerary;
use App\DepartureOptionalActivity;
use App\IconInclusion;
use App\DepartureIconInclusion;
use App\DestinationItineraryPointOfInterest;
use App\DepartureDestinationPointOfInterest;
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
use App\DepartureCloudDestination;
use App\Tag;
use App\DepartureTag;
use App\DepartureDate;

class FixedDepartureController extends Controller
{
    public function departureIndex(Request $request)
    {
        //$urlS3 = "https://dook-international.sgp1.cdn.digitaloceanspaces.com/dook/images/package/";
        $urlS3 = url('/dook/images/package/').'/';
        $keywords = $request->keyword;
        $status = $request->status;
        if($request->status == '' && $request->keyword){
            $departures = Departure::join('departure_destinations','departure_destinations.departure_id','=','departures.id')
                        ->join('destinations','destinations.id','=','departure_destinations.destination_id')
                        ->join('country_departures','country_departures.departure_id','=','departures.id')
                        ->join('countries','countries.id','=','country_departures.country_id')
                        ->join('departure_dates','departure_dates.departure_id','=','departures.id')
                        //->where(['dep_type'=>'main', 'tenant_id'=> auth()->user()->tenant_id])
                        ->where('departures.dep_type','main')
                        ->where(function($query)use($keywords){
                            $query->where('departures.title', 'LIKE','%'.$keywords.'%')
                                ->orWhere('destinations.dest_name', 'LIKE','%'.$keywords.'%')
                                ->orWhere('countries.country_name', 'LIKE','%'.$keywords.'%')
                                ->orWhere('departures.dep_dook_ref_id', 'LIKE','%'.$keywords.'%')
                                ->orWhere('departure_dates.title', 'LIKE','%'.$keywords.'%')
                                ->orWhere('departure_dates.dep_id', 'LIKE','%'.$keywords.'%')
                                ->orWhere('departure_dates.from', 'LIKE','%'.$keywords.'%')
                                ->orWhere('departure_dates.ending_at', 'LIKE','%'.$keywords.'%');
                        })
                        ->distinct('departures.created_at')
                        ->select('departures.*')
                        ->orderBy('id', 'DESC')
                        ->paginate(25);
            if(count($departures)<=0){
                $departures = Departure::join('departure_dates','departure_dates.departure_id','=','departures.id')
                        ->where('departures.dep_type','main')
                        ->where(function($query)use($keywords){
                            $query->where('departures.title', 'LIKE','%'.$keywords.'%')
                                ->orWhere('departures.dep_dook_ref_id', 'LIKE','%'.$keywords.'%')
                                ->orWhere('departure_dates.title', 'LIKE','%'.$keywords.'%')
                                ->orWhere('departure_dates.dep_id', 'LIKE','%'.$keywords.'%')
                                ->orWhere('departure_dates.from', 'LIKE','%'.$keywords.'%')
                                ->orWhere('departure_dates.ending_at', 'LIKE','%'.$keywords.'%');
                        })
                        ->distinct('departures.created_at')
                        ->select('departures.*')
                        ->orderBy('id', 'DESC')
                        ->paginate(25);

            }
        }elseif($request->status == 'in' && $request->keyword == ''){
            $departures = Departure::join('departure_destinations','departure_destinations.departure_id','=','departures.id')
                        ->join('destinations','destinations.id','=','departure_destinations.destination_id')
                        ->join('country_departures','country_departures.departure_id','=','departures.id')
                        ->join('countries','countries.id','=','country_departures.country_id')
                        //->where(['dep_type'=>'main', 'tenant_id'=> auth()->user()->tenant_id])
                        ->where('departures.dep_type','main')
                        ->where('departures.status', 0)
                        ->distinct('departures.created_at')
                        ->select('departures.*')
                        ->orderBy('id', 'DESC')
                        ->paginate(25);
            if(count($departures)<=0){
                $departures = Departure::join('departure_dates','departure_dates.departure_id','=','departures.id')
                        ->where('departures.dep_type','main')
                        ->where('departures.status', 0)
                        ->distinct('departures.created_at')
                        ->select('departures.*')
                        ->orderBy('id', 'DESC')
                        ->paginate(25);
            }
        }elseif($request->status == 1 && $request->keyword == ''){
            $departures = Departure::join('departure_destinations','departure_destinations.departure_id','=','departures.id')
                        ->join('destinations','destinations.id','=','departure_destinations.destination_id')
                        ->join('country_departures','country_departures.departure_id','=','departures.id')
                        ->join('countries','countries.id','=','country_departures.country_id')
                        //->where(['dep_type'=>'main', 'tenant_id'=> auth()->user()->tenant_id])
                        ->where('departures.dep_type','main')
                        ->where('departures.status', 1)
                        ->distinct('departures.created_at')
                        ->select('departures.*')
                        ->orderBy('id', 'DESC')
                        ->paginate(25);
            if(count($departures)<=0){
                $departures = Departure::join('departure_dates','departure_dates.departure_id','=','departures.id')
                        ->where('departures.dep_type','main')
                        ->where('departures.status', 1)
                        ->distinct('departures.created_at')
                        ->select('departures.*')
                        ->orderBy('id', 'DESC')
                        ->paginate(25);
            }
        }elseif($request->status == 1 && $request->keyword){
            $departures = Departure::join('departure_destinations','departure_destinations.departure_id','=','departures.id')
                        ->join('destinations','destinations.id','=','departure_destinations.destination_id')
                        ->join('country_departures','country_departures.departure_id','=','departures.id')
                        ->join('countries','countries.id','=','country_departures.country_id')
                        //->where(['dep_type'=>'main', 'tenant_id'=> auth()->user()->tenant_id])
                        ->where('departures.dep_type','main')
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
            if(count($departures)<=0){
                $departures = Departure::join('departure_dates','departure_dates.departure_id','=','departures.id')
                        ->where('departures.dep_type','main')
                        ->where(function($query)use($keywords){
                            $query->where('departures.title', 'LIKE','%'.$keywords.'%')
                                ->orWhere('departures.dep_dook_ref_id', 'LIKE','%'.$keywords.'%')
                                ->orWhere('departure_dates.title', 'LIKE','%'.$keywords.'%')
                                ->orWhere('departure_dates.dep_id', 'LIKE','%'.$keywords.'%')
                                ->orWhere('departure_dates.from', 'LIKE','%'.$keywords.'%')
                                ->orWhere('departure_dates.ending_at', 'LIKE','%'.$keywords.'%');
                        })
                        ->where('departures.status', 1)
                        ->distinct('departures.created_at')
                        ->select('departures.*')
                        ->orderBy('id', 'DESC')
                        ->paginate(25);
            }
        }elseif($request->status == 'in' && $request->keyword){

            $departures = Departure::join('departure_destinations','departure_destinations.departure_id','=','departures.id')
                        ->join('destinations','destinations.id','=','departure_destinations.destination_id')
                        ->join('country_departures','country_departures.departure_id','=','departures.id')
                        ->join('countries','countries.id','=','country_departures.country_id')
                        //->where(['dep_type'=>'main', 'tenant_id'=> auth()->user()->tenant_id])
                        ->where('departures.dep_type','main')
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
            if(count($departures)<=0){
                $departures = Departure::join('departure_dates','departure_dates.departure_id','=','departures.id')
                        ->where('departures.dep_type','main')
                        ->where(function($query)use($keywords){
                            $query->where('departures.title', 'LIKE','%'.$keywords.'%')
                                ->orWhere('departures.dep_dook_ref_id', 'LIKE','%'.$keywords.'%')
                                ->orWhere('departure_dates.title', 'LIKE','%'.$keywords.'%')
                                ->orWhere('departure_dates.dep_id', 'LIKE','%'.$keywords.'%')
                                ->orWhere('departure_dates.from', 'LIKE','%'.$keywords.'%')
                                ->orWhere('departure_dates.ending_at', 'LIKE','%'.$keywords.'%');
                        })
                        ->where('departures.status', 0)
                        ->distinct('departures.created_at')
                        ->select('departures.*')
                        ->orderBy('id', 'DESC')
                        ->paginate(25);

            }
        }else{
            $departures = Departure::where('dep_type','main')
                    ->orderBy('id', 'DESC')
                    ->paginate(25);
        }
        foreach ($departures as $key => $totalDates) {
            $totalDate = DepartureDate::where('departure_id',$totalDates->id)->get();
            $totalDates->totalDates = count($totalDate);
        }

        $departureCount = Departure::where('dep_type','main')
                        //->where('tenant_id',auth()->user()->tenant_id)
                        ->get();
        $status = ($status == null)?'no':$status;
        $total = count($departureCount);
        if($request->ajax()){
                return view('departure.departure_index_data',compact('departures','urlS3','keywords','status'));
            }
        return view('departure.departure_index',compact('departures','total','urlS3','keywords','status'));
    }

    public function departureEdit(Request $request, $id)
    {
        //$urlS3 = "https://dook-international.sgp1.cdn.digitaloceanspaces.com/dook/images/package/";
        $urlS3 = url('/dook/images/package/').'/';
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $departures  = Departure::where('id',$id)
                    //->where('tenant_id',auth()->user()->tenant_id)
                    ->first();
        if($departures){
            $data = explode(",",$departures->transport_type);
            $departures['transport_type'] = $data;
        }
        else{
            $departures['transport_type'] = [];
        }
        if($departures){
            $data = explode(",",$departures->meal_type);
            $departures['meal_type'] = $data;
        }
        else{
            $departures['meal_type'] = [];
        }
        $cloud_destination = DepartureCloudDestination::where('departure_id', $departures->id)->get();
        $destinations = Destination::join('departure_destinations','departure_destinations.destination_id','=','destinations.id')
                        ->join('countries','countries.id','=','destinations.country_id')
                        ->join('regions','regions.id','=','countries.region_id')
                        ->where('departure_destinations.departure_id',$route_id)
                        ->select('destinations.dest_name as name','destinations.actualname','destinations.country_name as country','destinations.reference_id as id','destinations.latitude as lat','destinations.longitude as long','destinations.feature_class as f_class','destinations.feature_code as f_codes','destinations.geonameid','destinations.region','destinations.region_code as regioncode','destinations.region_id as dest_region_id','destinations.country_iso_2 as iso2','destinations.country_iso_3 as iso3','destinations.image','destinations.banner_image','destinations.description','countries.reference_id as count_id','countries.official_name','countries.capital','countries.largest_city','countries.continent','countries.description as count_des','countries.sub_continent','countries.iso_2','countries.iso_3','countries.isd_code','countries.latitude as count_lat','countries.longitude as count_long','countries.internet_tld','countries.currency','countries.currency_symbol','countries.currency_code','countries.drives_on','countries.area','countries.area_unit','countries.population','countries.image as count_image','countries.flag','countries.region_id as regionIds')
                            //,'regions.id as sub_cont_id','regions.region_name as sub_cont_name')
                        ->get();
            foreach ($destinations as $key => $value) {
                $descriptionS = str_replace("'", "", $value->description);
                $descriptionS = str_replace("’", "", $descriptionS);
                $descriptionS= trim(preg_replace('/\s\s+/', ' ', $descriptionS));
                $value->description = preg_replace('/"/','',$descriptionS);

                $country_des = str_replace("'", "", $value->count_des);
                $country_des = str_replace("’", "", $country_des);
                $country_des= trim(preg_replace('/\s\s+/', ' ', $country_des));
                $value->count_des = preg_replace('/"/','',$country_des);
            }
            $destinationsss = Destination::join('departure_destinations','departure_destinations.destination_id','=','destinations.id')
                        ->join('countries','countries.id','=','destinations.country_id')
                        ->join('regions','regions.id','=','countries.region_id')
                        ->where('departure_destinations.departure_id',$route_id)
                        ->pluck('destinations.dest_name as name')
                        ->toArray();
            // $IconInclusions = IconInclusion::select("id","name","icon")->get();
            // $depinclusions = DepartureIconInclusion::join('icon_inclusions','icon_inclusions.id','=','departure_icon_inclusions.icon_inclusion_id')
            //             ->where('departure_icon_inclusions.departure_id',$route_id)
            //             ->select("icon_inclusions.id","icon_inclusions.name","icon_inclusions.icon")
            //             ->get();  
            //$symbols = DB::table('currency_symbols')->where('iso_3', 'IND')->first();
            //$symboldollar = DB::table('currency_symbols')->where('iso_3', 'USA')->first();      
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
        return view('departure.basic_details_edit',compact('departures','destinations','urlS3','imagePath','departureimages','departure_difficulties','difficulties','tour_types','departure_tour_types','tour_classes','cloud_destination','tags','departure_tags','destinationsss'));
    }

    public function departureUpdate(Request $request, $id)
    {
        $data = $request->all();
        $user = auth()->user();

        $departure = Departure::find($id);   
        $departure->title = $request->title;
        $departure->sub_title = $request->sub_title;
        $departure->no_of_days = $request->days;
        $departure->no_of_nights = $request->nights;
        
        $departure->price_hide_show = $request->price_hide_show;
        $departure->book_online = $request->book_online;
       
        $departure->dep_dook_ref_id = $request->dep_dook_ref_id;
        $departure->slug_url = $request->slug_url;
        $departure->slug_url_pre = $request->slug_url_pre;
        $departure->meta_title = $request->meta_title;
        $departure->meta_keywords = $request->meta_keywords;
        $departure->meta_description = $request->meta_description;
        $departure->description = $request->description;
        $departure->from = $request->starting_from;
        $departure->ending_at = $request->ending_at;
        $departure->tenant_id = $user->tenant_id;
        $departure->user_id = $user->id;
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
        $someArrays = json_decode($request->package_multi_img);
        if(empty($someArrays)){
        }
        else{
            DepartureImage::where('departure_id', '=', $last_id)->delete();
            foreach ($someArrays as $mimg) {
                if($mimg != null){
                    $image = $mimg->Content;
                    $imagebase = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$image));
                    $imageName = Str::random(5).Str::random(5).time() . '.jpg';
                    //$imageFile = Image::make($imagebase)->fit(500, 500)->stream();
                    //$imageFile = $imageFile->__toString();
                    //$imagebase->fit(500, 500);
                    //$storagePath = Storage::disk('spaces')->put('dook/images/package/'.$imageName, $imagebase, 'public');
                    $relPath = 'dook/images/package/';
                        if (!file_exists(public_path($relPath))) {
                            mkdir(public_path($relPath), 777, true);
                        }
                    $img = Image::make($imagebase)->save( public_path($relPath . $imageName ) );
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
                        $regionId = $value->sub_continent_id;
                        $regionId_array = explode(",",$regionId);
                        $region_id_array = array();
                        foreach ($regionId_array as $key => $r_value) {
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
                        $region_id_str = implode(",", $region_id_dook);

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
                            $country->flag = $value->flag;
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
                            // $array = explode(' ', $value->destimg);
                            //    //$valid_img = strrpos($value->destimg, " ");
                            //    if(count($array) <=1)
                            //    {
                            //     $image_url = "https://s3-pullit-bucket.s3.us-west-2.amazonaws.com/destination/".$value->destimg;
                            //     $desti_image = Destination::where('reference_id',$value->id)
                            //             ->select('image')
                            //             ->first();
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
                            $destination->drives_on = $value->drive_on;
                            $destination->currency_symbol = $value->currency_symbol;
                            $destination->currency_code = $value->currency_code;
                            // if($value->destimg != "undefined")
                            // {
                            // if($value->destimg != ''){
                            //    $array = explode(' ', $value->destimg);
                            //    //$valid_img = strrpos($value->destimg, " ");
                            //    if(count($array) <=1)
                            //    {
                            //     $image_url = "https://s3-pullit-bucket.s3.us-west-2.amazonaws.com/destination/".$value->destimg;
                            //         $desti_image = Destination::where('reference_id',$value->id)
                            //                 ->select('image')
                            //                 ->first();
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
                            //    }     
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
                       //       'destination_id' => $value->id
                       //  );
                       //  $curl = curl_init();
                       //  curl_setopt($curl, CURLOPT_URL, env("pullIt_BaseUrl").'api/get_desti_image');
                       //  curl_setopt($curl, CURLOPT_TIMEOUT, 30);
                       //  curl_setopt($curl, CURLOPT_POST, 1);
                       //  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                       //  curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
                       //  $response = curl_exec($curl);
                       //  $dest_images = json_decode($response);
                       //  //dd($dest_images->image);
                       //  if(isset($dest_images->image) || isset($dest_images->image) != '' || isset($dest_images->image) != null)
                       //  {
                       //      $array = explode(' ', $dest_images->image);
                       //         //$valid_img = strrpos($value->destimg, " ");
                       //      if(count($array) <=1)
                       //      {
                       //          $image_url = "https://s3-pullit-bucket.s3.us-west-2.amazonaws.com/destination/".$dest_images->image;
                       //          $desti_image = Destination::where('id',$dest_last_id)
                       //                      ->select('banner_image')
                       //                      ->first();
                       //          if($desti_image->banner_image == '' || $desti_image->banner_image == null){
                       //              $destination_image = Destination::find($dest_last_id);

                       //              $type = pathinfo($image_url, PATHINFO_EXTENSION);
                       //              $data = file_get_contents($image_url);
                       //              $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                       //              $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$base64));
                       //              $imageName = Str::random(5).time() . '.jpg';
                       //              $imageFile = Image::make($image)->fit(1920, 768)->stream();
                       //              $imageFile = $imageFile->__toString();
                       //              $img = Storage::disk('s3')->put('dook/images/poi/'.$imageName, $imageFile, 'public');
                       //              $destination_image->banner_image =  $imageName;
                       //              $destination_image->save();
                       //          }
                       //      }
                       //  }
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
        // $status = [
        //         'url'=> url('/departure/inclusion',$last_id),
        //     ];
        $status = [
            'url'=> url('/departure/poi',$last_id),
        ];
        return response()->json($status);
    }

    public function departureDisable(Request $request, $id)
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
        
        return response()->json(['success'=>'Departure disabled successfully!']);
    }
    // Term & Conditions Function

    public function termConditionIndex(Request $request)
    {   
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $termconditionDefault = DB::table('term_and_conditions')->where('type','group-tours')->first();
       // dd($termconditionDefault);
        $termconditions = Departure::select("conditions")
                        ->where('id',$route_id)
                        ->first();
        return view('departure.term_conditions',compact('termconditions','termconditionDefault'));
    }

    public function termConditionUpdate(Request $request)
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

    public function visaInformationIndex(Request $request)
    {   
        $route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $country = DB::table('departure_destinations')->join('destinations','destinations.id','=','departure_destinations.destination_id')
                    ->where('departure_destinations.departure_id','=',$route_id)
                    ->distinct()
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
        // print_r($obj);
        // die();
        return view('departure.visa_information',compact('visainformation','countries','obj','new_data'));
    }

    public function visaInformationUpdate(Request $request)
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
}
