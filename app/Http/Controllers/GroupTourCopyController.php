<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
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
use App\ActivityDeparture;
use App\BeforeYougoCountryDeparture;
use App\DepartureRecommendExperience;
use App\DepartureTag;
use App\DepartureDate;

class GroupTourCopyController extends Controller
{
    public function copyTour(Request $request)
    {
        $data = $request->all();
        //dd($data);
        $package_id = $request->copy_id;

        $user = auth()->user();
        $package_data = Departure::where(['id'=> $package_id, 'dep_type'=> 'group'])->first();
        $unique = Departure::where('dep_dook_ref_id',$request->dep_dook_ref_id)
                ->first();
                //dd($request->title);
        if($unique){
            $package = Departure::find($unique->id);
            $package->title = $request->title;
            $package->slug_url_pre = $request->slug_url_pre;
            $package->slug_url = $request->slug_url;
            $package->dep_dook_ref_id = $request->dep_dook_ref_id;
            $package->sub_title = $package_data->sub_title;
            $package->no_of_days = $package_data->no_of_days;
            $package->no_of_nights = $package_data->no_of_nights;
            $package->description = $package_data->description;
            $package->price_hide_show = $package_data->price_hide_show;
            $package->book_online = $package_data->book_online;
            $package->price_currency = $package_data->price_currency;
            $package->price_currency_usd = $package_data->price_currency_usd;
            $package->from = $package_data->starting_from;
            $package->ending_at = $package_data->ending_at;
            $package->meta_title = $package_data->meta_title;
            $package->meta_keywords = $package_data->meta_keywords;
            $package->meta_description = $package_data->meta_description;
            $package->tenant_id = $user->tenant_id;
            $package->user_id = $user->id;
            $package->dep_type = "group";
            $package->image = $package_data->image;
            $package->banner_image = $package_data->banner_image;
            $package->save();
            $last_id = $package->id;
        }else{
            $package = new Departure;
            $package->title = $request->title;
            $package->slug_url_pre = $request->slug_url_pre;
            $package->slug_url = $request->slug_url;
            $package->dep_dook_ref_id = $request->dep_dook_ref_id;
            $package->sub_title = $package_data->sub_title;
            $package->no_of_days = $package_data->no_of_days;
            $package->no_of_nights = $package_data->no_of_nights;
            $package->description = $package_data->description;
            $package->price_hide_show = $package_data->price_hide_show;
            $package->book_online = $package_data->book_online;
            $package->price_currency = $package_data->price_currency;
            $package->price_currency_usd = $package_data->price_currency_usd;
            $package->meta_title = $package_data->meta_title;
            $package->meta_keywords = $package_data->meta_keywords;
            $package->meta_description = $package_data->meta_description;
            $package->tenant_id = $user->tenant_id;
            $package->user_id = $user->id;
            $package->dep_type = "group";
            $package->image = $package_data->image;
            $package->banner_image = $package_data->banner_image;
            $package->unique_key = Str::random(10).time();
            $package->save();
            $last_id = $package->id;
        }
        $package_images = DepartureImage::where('departure_id', $package_id)
                        ->get();
        if(count($package_images)>0){
            DepartureImage::where('departure_id', $last_id)->delete();
            foreach ($package_images as $key => $value) {
                $package_images = new DepartureImage;
                $package_images->departure_id = $last_id;
                $package_images->image = $value->image;
                $package_images->image_compress = $value->image_compress;
                $package_images->save();
            }
        }
        $dates = DepartureDate::where('departure_id', $package_id)
                            ->get();
        if(count($dates)>0){
            DepartureDate::where('departure_id', $last_id)->delete();
            foreach ($dates as $date) {
                $dep_date= new DepartureDate;
                $dep_date->departure_id = $last_id;
                $dep_date->dook_id = $date->dook_id;
                $dep_date->reference_id = $date->reference_id;
                $dep_date->dep_id = $date->dep_id;
                $dep_date->title = $date->title;
                $dep_date->date = $date->date;
                $dep_date->from = $date->from;
                $dep_date->ending_at = $date->ending_at;
                $dep_date->tenant_id = $date->tenant_id;
                $dep_date->tenant_id_reff = $date->tenant_id_reff;
                $dep_date->user_id = $date->user_id;
                $dep_date->description = $date->description;
                $dep_date->price = $date->price;
                $dep_date->price_usd = $date->price_usd;
                $dep_date->price_currency = $date->price_currency;
                $dep_date->price_currency_usd = $date->price_currency_usd;
                $dep_date->dep_type = $date->dep_type;
                $dep_date->status = $date->status;
                $dep_date->termspayment = $date->termspayment;
                $dep_date->save(); 

                $last_date_id = $dep_date->id;
                $inclusions = Inclusion::where(['departure_id'=> $last_id, 'departure_date_id'=> $last_date_id])->get();

                if(count($inclusions)>0){
                    Inclusion::where('departure_date_id', $last_date_id)->delete();
                    foreach ($inclusions as $inclusion) {
                        $dep_inclusion= new Inclusion;
                        $dep_inclusion->departure_id = $last_id;
                        $dep_inclusion->departure_date_id = $last_date_id;
                        $dep_inclusion->name = $inclusion->name;
                        $dep_inclusion->description = $inclusion->description;
                        $dep_inclusion->dep_type = $inclusion->dep_type;
                        $dep_inclusion->tenant_id = $inclusion->tenant_id;
                        $dep_inclusion->user_id = $inclusion->user_id;
                        $dep_inclusion->save(); 
                    }               
                }
            }               
        }
        // $package_difficulties = DepartureDifficulty::where('departure_id', $package_id)
        //                     ->get();
        // if(count($package_difficulties)>0){
        //     DepartureDifficulty::where('departure_id', $last_id)->delete();
        //     foreach ($package_difficulties as $d_tag) {
        //         $difficulties= new DepartureDifficulty;
        //         $difficulties->departure_id = $last_id;
        //         $difficulties->difficulty_id = $d_tag->difficulty_id;
        //         $difficulties->save(); 
        //     }               
        // }
        $tags = DepartureTag::where('departure_id', $package_id)
                            ->get();
        if(count($tags)>0){
            DepartureTag::where('departure_id', $last_id)->delete();
            foreach ($tags as $tag) {
                $dep_tag= new DepartureTag;
                $dep_tag->departure_id = $last_id;
                $dep_tag->tag_id = $tag->tag_id;
                $dep_tag->save(); 
            }               
        }
        $package_tour_types = DepartureTourType::where('departure_id', $package_id)
                            ->get();
        if(count($package_tour_types)>0){
            DepartureTourType::where('departure_id', $last_id)->delete();
            foreach ($package_tour_types as $tt_tag) {
                $tour_type= new DepartureTourType;
                $tour_type->departure_id = $last_id;
                $tour_type->tour_type_id = $tt_tag->tour_type_id;
                $tour_type->save(); 
            }               
        } 
        $countries_departure = CountryDeparture::where('departure_id', $package_id)
                            ->get();
        if(count($countries_departure)>0){
            CountryDeparture::where('departure_id', $last_id)->delete();
            foreach ($countries_departure as $key => $country_id) {
                $country_dep = new CountryDeparture;
                $country_dep->country_id = $country_id->country_id;
                $country_dep->departure_id = $last_id;
                $country_dep->save();
            }
        } 
        $destination_departure = DepartureDestination::where('departure_id', $package_id)
                            ->get();
        if(count($destination_departure)>0){
            DepartureDestination::where('departure_id', $last_id)->delete();
            foreach ($destination_departure as $key => $destination_id) {
                $country_dep = new DepartureDestination;
                $country_dep->destination_id = $destination_id->destination_id;
                $country_dep->departure_id = $last_id;
                $country_dep->user_id = $user->id;
                $country_dep->save();
            }
        } 

        $destination_experiences = DestinationExperience::where('departure_id', $package_id)
                            ->get();
        if(count($destination_experiences)>0){
            DestinationExperience::where('departure_id', $last_id)->delete();
            foreach ($destination_experiences as $key => $dest_exp_id) {
                $country_dep = new DestinationExperience;
                $country_dep->destination_id = $dest_exp_id->destination_id;
                $country_dep->departure_id = $last_id;
                $country_dep->experience_id = $dest_exp_id->experience_id;
                $country_dep->save();
            }
        }  

        // $inclusions = Inclusion::where('departure_id', $package_id)
        //                     ->get();
        // if(count($inclusions)>0){
        //     Inclusion::where('departure_id', $last_id)->delete();
        //     foreach ($inclusions as $inclusion) {
        //         $dep_inclusions= new Inclusion;
        //         $dep_inclusions->departure_id = $last_id;
        //         $dep_inclusions->name = $inclusion->name;
        //         $dep_inclusions->description = $inclusion->description;
        //         $dep_inclusions->dep_type = $inclusion->dep_type;
        //         $dep_inclusions->user_id = $user->id;
        //         $dep_inclusions->tenant_id = $user->tenant_id;
        //         $dep_inclusions->tenant_id_reff = $inclusion->tenant_id_reff;
        //         $dep_inclusions->save(); 
        //     }               
        // }

        $destination_departure_poi = DepartureDestinationPointOfInterest::where('departure_id', $package_id)->get();
        if(count($destination_departure_poi)>0){
            DepartureDestinationPointOfInterest::where('departure_id', $last_id)
                ->delete();
            foreach ($destination_departure_poi as $key => $dep_dest_pois) {
                $dest_dep_poi = new DepartureDestinationPointOfInterest;
                $dest_dep_poi->destination_id = $dep_dest_pois->destination_id;
                $dest_dep_poi->departure_id = $last_id;
                $dest_dep_poi->reference_id = $dep_dest_pois->reference_id;
                $dest_dep_poi->poi_name = $dep_dest_pois->poi_name;
                $dest_dep_poi->latitude = $dep_dest_pois->latitude;
                $dest_dep_poi->longitude = $dep_dest_pois->longitude;
                $dest_dep_poi->image = $dep_dest_pois->image;
                $dest_dep_poi->banner_image = $dep_dest_pois->banner_image;
                $dest_dep_poi->address = $dep_dest_pois->address;
                $dest_dep_poi->rating = $dep_dest_pois->rating;
                $dest_dep_poi->reviews = $dep_dest_pois->reviews;
                $dest_dep_poi->poi_type = $dep_dest_pois->poi_type;

                $dest_dep_poi->phone = $dep_dest_pois->phone;
                $dest_dep_poi->website = $dep_dest_pois->website;
                $dest_dep_poi->openhours = $dep_dest_pois->openhours;
                $dest_dep_poi->height = $dep_dest_pois->height;
                $dest_dep_poi->width = $dep_dest_pois->width;
                $dest_dep_poi->length = $dep_dest_pois->length;
                $dest_dep_poi->depth = $dep_dest_pois->depth;
                $dest_dep_poi->description = $dep_dest_pois->description;

                $dest_dep_poi->dep_type = $dep_dest_pois->dep_type;
                $dest_dep_poi->tenant_id = $dep_dest_pois->tenant_id;
                $dest_dep_poi->tenant_id_reff = $dep_dest_pois->tenant_id_reff;
                $dest_dep_poi->user_id = $dep_dest_pois->user_id;
                $dest_dep_poi->save();
            }
        }  

        $itinerary = Itinerary::where('departure_id', $package_id)
                    ->distinct()
                    ->get();
        if(count($itinerary)>0){
            Itinerary::where('departure_id', $last_id)->delete();
            foreach ($itinerary as $key => $itinerary_id) {
                $itinerary = new Itinerary;
                $itinerary->day_number = $itinerary_id->day_number;
                $itinerary->day_heading = $itinerary_id->day_heading;
                $itinerary->departure_id = $last_id;
                $itinerary->included = $itinerary_id->included;
                $itinerary->description = $itinerary_id->description;
                $itinerary->tenant_id = $user->tenant_id;
                $itinerary->user_id = $user->id;
                $itinerary->dep_type = "group";
                $itinerary->unique_key = Str::random(10).time();
                $itinerary->save();
                $itinerary_last_id = $itinerary->id;

                $destination_Itinerary = DestinationItineraryPointOfInterest::where('itinerary_id', $itinerary_id->id)
                        ->where('departure_id', $package_id)
                        ->get();
                if(count($destination_Itinerary)>0){   
                    DestinationItineraryPointOfInterest::where('itinerary_id', $itinerary_last_id)->delete();          
                    foreach ($destination_Itinerary as $dest_iti_value) {
                         $itinerary_locations = new DestinationItineraryPointOfInterest;
                         $itinerary_locations->itinerary_id=$itinerary_last_id;
                         $itinerary_locations->destination_id=$dest_iti_value->destination_id;
                         $itinerary_locations->departure_id=$last_id;
                         $itinerary_locations->point_of_interest_id = $dest_iti_value->point_of_interest_id;
                         $itinerary_locations->save();
                    }   
                }
            }
        }  
        $departure_activity = ActivityDeparture::where('departure_id', $package_id)
                            ->get();
        if(count($departure_activity)>0){
            ActivityDeparture::where('departure_id', $last_id)->delete();
            foreach ($departure_activity as $key => $dep_activity) {
                $country_dep = new ActivityDeparture;
                $country_dep->activity_id = $dep_activity->activity_id;
                $country_dep->departure_id = $last_id;
                $country_dep->save();
            }
        }
        $country_dep_dest_R = CountryDepartureDestinationRegion::where('departure_id', $package_id)->distinct()->get();

        if(count($country_dep_dest_R)>0){
            CountryDepartureDestinationRegion::where('departure_id', $last_id)->delete();
            foreach ($country_dep_dest_R as $key => $dep_dest_c_R) {
                $dep_dest_country_R = new CountryDepartureDestinationRegion;
                $dep_dest_country_R->region_id = $dep_dest_c_R->region_id;
                $dep_dest_country_R->departure_id = $last_id;
                $dep_dest_country_R->region_name = $dep_dest_c_R->region_name;
                $dep_dest_country_R->country_id = $dep_dest_c_R->country_id;
                $dep_dest_country_R->country_name = $dep_dest_c_R->country_name;
                $dep_dest_country_R->iso_3 = $dep_dest_c_R->iso_3;
                $dep_dest_country_R->destination_id = $dep_dest_c_R->destination_id;
                $dep_dest_country_R->destination_name = $dep_dest_c_R->destination_name;
                $dep_dest_country_R->save();
            }
        }   

        $beforyou_go = BeforeYougoCountryDeparture::where('departure_id', $package_id)
                    ->distinct()
                    ->get();

        if(count($beforyou_go)>0){
            BeforeYougoCountryDeparture::where('departure_id', $last_id)->delete();
            foreach ($beforyou_go as $key => $you_go) {
                $before_you_go = new BeforeYougoCountryDeparture;
                $before_you_go->departure_id = $last_id;
                $before_you_go->country_id = $you_go->country_id;
                $before_you_go->save();
            }
        }   
    }
}
