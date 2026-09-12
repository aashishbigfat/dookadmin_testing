<?php

namespace App\Http\Controllers;
ini_set('max_execution_time', '720');
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use DB;
use Storage;
use Image;
use App\Departure;
use App\PdfPage;
use App\PdfBanner;
use App\PdfBasicDetail;
use App\PdfContact;
use App\PdfTerm;
use App\Inclusion;
use App\Itinerary;
use App\PdfInclusion;
use App\PdfDayWiseItinerary;
use App\PdfItineraryPoi;
use App\Destination;
use App\DepartureDestination;
use App\DepartureDestinationPointOfInterest;
use App\DestinationItineraryPointOfInterest;
use App\PdfFile;
use App\DepartureImage;
use App\User;
use \PDF;
use Google\Cloud\Storage\StorageClient;

class PdfController extends Controller
{
    public function pdfCreate(Request $request, $id)
	{
		$data = PdfFile::where('tenant_id', Auth()->user()->tenant_id)->where('departure_id', $id)->get();
		return view('pdf-pages/create', compact('data'));
	}

	public function storeModule(Request $request, $id)
	{
		$data = $request->all();
        $file_name = $request->file_name;
        // $file_name = str_slug($file_names, '_').time();
        $route_ids = $request->route('id');
        $route_id = (int)$route_ids;

        $package = Departure::where('id', $route_id)->where('tenant_id', Auth()->user()->tenant_id)->first();
        if(PdfPage::where('departure_id', $route_id )->exists()){
        	$banner = $basic_detail = $itinerary = $inclusion = $terms = $contact = 0;
		 	foreach ($request->itinerary as $key => $value) 
			{
				if($value=='banner'){
					$banner = 1;
				}
				if($value=='basic_detail'){
					$basic_detail = 1;
				}
				if($value=='day_wise'){
					$itinerary = 1;
				}
				if($value=='inclusions'){
					$inclusion = 1;
				}
				if($value=='terms'){
					$terms = 1;
				}
				if($value=='contact'){
					$contact = 1;
				}
			}
			PdfPage::where('departure_id', $route_id)->update(['file_name'=>$file_name,'banner' => $banner, 'basic_detail' => $basic_detail, 'itinerary' => $itinerary, 'inclusion' => $inclusion, 'terms' => $terms, 'contact' => $contact]);
			return \Redirect::route('pdf-pages.index', $route_id);
		}
		else{
       		$user = auth()->user();
       		$random_id = md5(uniqid(rand(), true));

			$pdf_pages = new PdfPage();
			$pdf_pages->departure_id = $route_id;
			$pdf_pages->itinerary_id = $random_id;
			$pdf_pages->file_name = $file_name;
			foreach ($request->itinerary as $key => $value)
			{
			if($value=='banner'){
			$pdf_pages->banner = 1;
			}
			if($value=='basic_detail'){
			$pdf_pages->basic_detail = 1;
			}
			if($value=='day_wise'){
			$pdf_pages->itinerary = 1;
			}
			if($value=='inclusions'){
			$pdf_pages->inclusion = 1;
			}
			if($value=='terms'){
			$pdf_pages->terms = 1;
			}
			if($value=='contact'){
			$pdf_pages->contact = 1;
			}
			}
			$pdf_pages->save();
			$modules = PdfPage::where('departure_id', $route_id)->first();
			$departure_dest = DepartureDestination::where('departure_id', $route_id)->first();
			$start_from = Destination::where('id', $departure_dest->destination_id)->first();
			$all_dest = DepartureDestination::where('departure_id', $route_id)->get();
			$top_attraction = DepartureDestinationPointOfInterest::where('departure_id', $route_id)->get();
			$inclusions = Inclusion::where('departure_id', $route_id)->get();
			$itineraries = Itinerary::where('departure_id', $route_id)->get();
			$package_image = DepartureImage::where('departure_id', $route_id)->get();

			if($modules->banner == 1){
			       $pdf_banner = new PdfBanner();
			       $pdf_banner->departure_id = $route_id;
			       $pdf_banner->itinerary_id = $modules->itinerary_id;
			       $pdf_banner->company_name = "Dook Travels";
			       $pdf_banner->phone = "+91 40001000";
			       $pdf_banner->w_mobile = "+91 8368513675";
			       $pdf_banner->email = "sales@dooktravels.com";
			       $pdf_banner->website = "https://www.dookinternational.com/";
			       $pdf_banner->company_logo = "https://www.dookinternational.com/_nuxt/img/logo.22d47c3.png";
			       $pdf_banner->banner_image = $package->banner_image;
			       $pdf_banner->save();
			}

			if($modules->basic_detail == 1)
			{
			$basic_detail = new PdfBasicDetail();
			       $basic_detail->departure_id = $route_id;
			       $basic_detail->itinerary_id = $modules->itinerary_id;
			       $basic_detail->title = $package->title;
			       $basic_detail->total_days = $package->no_of_days;
			       $basic_detail->total_nights = $package->no_of_nights;
			       $basic_detail->departs = 'NA';
			       $basic_detail->start_from = $start_from->dest_name;
			       $loop = 1;
			       foreach($all_dest as $destination_data){
			           if($loop < 5){
			            $field_name = 'place'.$loop;
			            $destination_name = Destination::where('id', $destination_data->destination_id)->first();
			               $basic_detail->$field_name = $destination_name->dest_name;
			           }
			           $loop++;
			       }
			       $attr_loop = 1;
			       foreach ($top_attraction as $key => $value) {
			               if($attr_loop < 7){
			                $attr_name = 'attraction_name'.$attr_loop;
			                $attr_address = 'attraction_address'.$attr_loop;
			                $attr_image = 'attraction_image'.$attr_loop;

			                   $basic_detail->$attr_name = $value->poi_name;
			                   $basic_detail->$attr_address = $value->address;
			                   $basic_detail->$attr_image = $value->image;
			                   $attr_loop++;
			               }
			       }
			       $basic_detail->save();
			}

			if($modules->itinerary == 1)
			{
			$agent_connect_banner = 'Agent-Connect-Banner.jpg';
			$depature_cloud_banner = 'Departure-Cloud.jpg';
			$dook_edu_banner = 'DookEdu-Banner.jpg';
			$dook_visa_banner = 'Dook-VISA.jpg';

			$first_image = $second_image = $third_image = $fourth_image = '';
			$i = 0;
			foreach ($package_image as $key => $value) {
			if($i == 0) $first_image = $value->image;
			else if($i == 1) $second_image = $value->image;
			else if($i == 2) $third_image = $value->image;
			else $fourth_image = $value->image;
			$i++;
			}

			$n = 1;
			$o = 1;
			foreach ($itineraries as $key => $itinerary)
			{
			$banner_image = null;
			        $dest_ids = DestinationItineraryPointOfInterest::where('itinerary_id', $itinerary->id)->where('departure_id', $route_id)->groupBy('destination_id')->pluck('destination_id')->toArray();
			        if(empty($dest_ids)){
			        if($n%2 != 0){
			        $b_image = $first_image;
			        $img = $second_image;
			        }
			        else{
			        $b_image = $third_image;
			        $img = $fourth_image;
			        }
			        if($o == 1) $iti_iamge = $agent_connect_banner;
			        elseif($o == 2) $iti_iamge = $depature_cloud_banner;
			        elseif($o == 3) $iti_iamge = $dook_edu_banner;
			        else{
			        $iti_iamge = $dook_visa_banner;
			        $o = 0;
			        }

			        $day_wise_itinerary = new PdfDayWiseItinerary();
			           $day_wise_itinerary->departure_id = $route_id;
			           $day_wise_itinerary->itinerary_id = $modules->itinerary_id;
			           $day_wise_itinerary->day_number = $itinerary->day_number;
			           $day_wise_itinerary->name = $itinerary->day_heading;
			           $day_wise_itinerary->description = $itinerary->description;
			           $day_wise_itinerary->banner_image = $b_image;
			           $day_wise_itinerary->image = $img;
			           $day_wise_itinerary->itinerary_image = $iti_iamge;
			           $day_wise_itinerary->status = 0;
			           $day_wise_itinerary->pac_image_sts = 1;
			           $day_wise_itinerary->save();
			           $n++;
			           $o++;
			        }
			        else{
			        $poi_id = [];
			        foreach ($dest_ids as $value) {
			        $banner_image = Destination::where('id', $value)->first();
			        $poi_id = DestinationItineraryPointOfInterest::where('itinerary_id', $itinerary->id)->whereNotNull('point_of_interest_id')->groupBy('point_of_interest_id')->pluck('point_of_interest_id')->toArray();
			        }
			        if(empty($poi_id))
			        {
			        if($o == 1) $iti_iamge = $agent_connect_banner;
			        elseif($o == 2) $iti_iamge = $depature_cloud_banner;
			        elseif($o == 3) $iti_iamge = $dook_edu_banner;
			        else{
			        $iti_iamge = $dook_visa_banner;
			        $o = 0;
			        }

			        $day_wise_itinerary = new PdfDayWiseItinerary();
			           $day_wise_itinerary->departure_id = $route_id;
			           $day_wise_itinerary->itinerary_id = $modules->itinerary_id;
			           $day_wise_itinerary->day_number = $itinerary->day_number;
			           $day_wise_itinerary->name = $itinerary->day_heading;
			           $day_wise_itinerary->description = $itinerary->description;
			           $day_wise_itinerary->banner_image = $banner_image->banner_image;
			           $day_wise_itinerary->image = $banner_image->image;
			           $day_wise_itinerary->itinerary_image = $iti_iamge;
			           $day_wise_itinerary->status = 0;
			           $day_wise_itinerary->save();
			           $o++;
			        }
			        else
			        {
			           $day_wise_itinerary = new PdfDayWiseItinerary();
			           $day_wise_itinerary->departure_id = $route_id;
			           $day_wise_itinerary->itinerary_id = $modules->itinerary_id;
			           $day_wise_itinerary->day_number = $itinerary->day_number;
			           $day_wise_itinerary->name = $itinerary->day_heading;
			           $day_wise_itinerary->description = $itinerary->description;
			           $day_wise_itinerary->banner_image = $banner_image->banner_image;
			           $day_wise_itinerary->image = $banner_image->image;
			           $day_wise_itinerary->status = 1;
			           $day_wise_itinerary->save();

			           $pois = DestinationItineraryPointOfInterest::join('departure_destination_point_of_interests', 'departure_destination_point_of_interests.reference_id','point_of_interest_id')
			            ->where('destination_itinerary_point_of_interests.itinerary_id',$itinerary->id)
			            ->where('departure_destination_point_of_interests.departure_id',$route_id)
			            ->select('departure_destination_point_of_interests.*')
			            ->get();

			           foreach ($pois as $key => $poi)
			           {
			           $pdf_itinerary_poi = new PdfItineraryPoi();
			           $pdf_itinerary_poi->departure_id = $route_id;
			           $pdf_itinerary_poi->itinerary_id = $modules->itinerary_id;
			           $pdf_itinerary_poi->refrence_id = $day_wise_itinerary->id;
			           $pdf_itinerary_poi->name = $poi->poi_name;
			           $pdf_itinerary_poi->address = $poi->address;
			           $pdf_itinerary_poi->image = $poi->image;
			           $pdf_itinerary_poi->save();
			           }
			        }
			        }
			       }
			}

			if($modules->inclusion == 1)
			{
			foreach ($inclusions as $key => $inclusion) {
			$pdf_inclusion = new PdfInclusion();
			$pdf_inclusion->departure_id = $route_id;
			$pdf_inclusion->itinerary_id = $modules->itinerary_id;
			$pdf_inclusion->name = $inclusion->name;
			$pdf_inclusion->description = $inclusion->description;
			$pdf_inclusion->save();
			}
			}

			if($modules->terms == 1){
			$pdf_terms = new PdfTerm();
			       $pdf_terms->departure_id = $route_id;
			       $pdf_terms->itinerary_id = $modules->itinerary_id;
			       if($package->conditions !== null || $package->conditions != '')
			       {
			        $pdf_terms->terms = $package->conditions;
			       }
			       $pdf_terms->save();
			}

			if($modules->contact == 1)
			{
			       $pdf_contact = new PdfContact();
			       $pdf_contact->departure_id = $route_id;
			       $pdf_contact->itinerary_id = $modules->itinerary_id;
			       $pdf_contact->company_name = "Dook Travels";
			       $pdf_contact->name = "Dook";
			       $pdf_contact->phone = "+91 40001000";
			       $pdf_contact->mobile = "+91 8368513675";
			       $pdf_contact->email = "sales@dookinternational.com";
			       $pdf_contact->address = "905, 906, Kanchenjunga Building,18, Barakhamba Rd, Connaught Place,New Delhi, Delhi 110001";
			       $pdf_contact->save();
			}





        // $banner_image = ($package->banner_image == '' || $package->banner_image == '')?'https://s3.us-west-2.amazonaws.com/s3-tlak-bucket/banner_image/AjyYe1610100911.jpg':'https://s3.us-west-2.amazonaws.com/s3-tlak-bucket/banner_image'.$package->banner_image;

        // $headerFooter = Tenant::where('tenant_id',Auth()->user()->tenant_id)->first();

        // $company_logo = ($headerFooter->company_logo == '' || $headerFooter->company_logo == null)?'https://account.tlakapp.com/images/your-logo-png.png':'https://s3-tlak-bucket.s3-us-west-2.amazonaws.com/company/'.$headerFooter->company_logo;
        // $company_name = ($headerFooter->company_name == '' || $headerFooter->company_name == null)?'':$headerFooter->company_name; 
        // $users = User::where('tenant_id', Auth()->user()->tenant_id)->first();

        // $start_location = Itinerary::join('itinerary_locations','itinerary_locations.itinerary_id' ,'=', 'itineraries.id')
        //             ->select('location_id')
        //             ->where('itineraries.tour_package_id', $route_id)->where('itineraries.day_number', 1)
        //             ->first();
        // if ($start_location !== null) {
        //    $start_from = Location::where('id', $start_location->location_id)->first();
        //    $start_destination = $start_from->name;
        // }
        // else{
        //     $start_destination = '';
        // }

        // $itinerary = Itinerary::where('itineraries.tour_package_id', $route_id)->get();
        // foreach ($itinerary as $key => $value) {
        //     $location_id = ItineraryLocation::where('itinerary_id', $value->id)->get();
        //     $name = '';
        //     $i = 0;
        //     foreach($location_id as $key => $loc){
        //         $loc_name = Location::where('id', $loc->location_id)->first();
        //         if($i>0){
        //             $name = $name.', '.$loc_name->name;
        //         }
        //         else{
        //             $name = $name.$loc_name->name;
        //         }
        //         $i++;
        //     }
        //     $value->location_name = $name;

        //     $location_ids = ItineraryLocation::where('itinerary_id', $value->id)->pluck('location_id')->toArray();
        //     $topAttr = LocationPointOfInterest::join('point_of_interests','point_of_interests.id','=','location_point_of_interests.point_of_interest_id')
        //         ->select('point_of_interests.name as poiName','point_of_interests.address as poiAddress','point_of_interests.banner_image as poiImage')
        //         ->whereIn('location_point_of_interests.location_id', $location_ids)
        //         ->where('location_point_of_interests.tour_package_id', $route_id)
        //         ->inRandomOrder()
        //         ->limit(8)
        //         ->get();
        //     $value->poi = $topAttr;
        // }

        // $topAttraction = ItineraryLocation::join('itineraries','itineraries.id','=','itinerary_locations.itinerary_id')
        //           ->join('locations','locations.id','=','itinerary_locations.location_id')
        //           ->select('itineraries.id as itinearyId','itineraries.day_number as dayNumber','locations.id as locationId','locations.name as locacionName')
        //           ->distinct()
        //           ->where(['itinerary_locations.tour_package_id' => $route_id])
        //           ->get();
        // if(count($topAttraction)){
        //     foreach($topAttraction as $locPoi){ 
        //         $locPoi->poi =LocationPointOfInterest::join('point_of_interests','point_of_interests.id','=','location_point_of_interests.point_of_interest_id')
        //               ->join('locations','locations.id','=','location_point_of_interests.location_id')
        //               ->join('point_of_interest_icons','point_of_interest_icons.id','=','point_of_interests.point_of_interest_icon_id')
        //               ->select('point_of_interests.id as poiId','locations.name as locationName','point_of_interests.name as poiName','point_of_interests.address as poiAddress','point_of_interests.description as poiDescription','point_of_interests.banner_image as poiImage')
        //               ->distinct()
        //               ->where('location_point_of_interests.location_id',$locPoi->locationId)
        //               ->where(function($q) {
        //                         $q->where('location_point_of_interests.status','=',1);
        //                     })

        //               ->get();
        //     }
        // }
        // else{
        //     $topAttraction=[];
        // }


        // $terms = TermAndCondition::where('tour_package_id', $route_id)->first();

        // $manager = DepartureManager::where('tour_package_id', $route_id)->first();
        // $guide = DepartureGuide::where('tour_package_id', $route_id)->first();

        // $inclusion = InclusionTourPckage::where('tour_package_id', $route_id)->get();
        // foreach ($inclusion as $key => $inc) {
        //     if($inc->name == 'Visa')
        //         $inc->image = 'https://stage.tlakapp.com/media/itinerary/passport.png';
        //     else if($inc->name == 'Accommodation')
        //         $inc->image = 'https://stage.tlakapp.com/media/itinerary/hotel.png';
        //     else if($inc->name == 'Air Ticket')
        //         $inc->image = 'https://stage.tlakapp.com/media/itinerary/flight.png';
        //     else if($inc->name == 'Breakfast')
        //         $inc->image = 'https://stage.tlakapp.com/media/itinerary/coffee.png';
        //     else if($inc->name == 'Lunch')
        //         $inc->image = 'https://stage.tlakapp.com/media/itinerary/food.png';
        //     else if($inc->name == 'Dinner')
        //         $inc->image = 'https://stage.tlakapp.com/media/itinerary/dinner.png';
        //     else{
        //         $inc->image = '';
        //     }
        // }
        // $exclusion = ExclusionTourPackage::where('tour_package_id', $route_id)->first();

        // $flights = Flight::where('tour_package_id', $route_id)->get();

        // $hotels = Hotel::where('tour_package_id', $route_id)->get();
        // foreach ($hotels as $key => $hotel_data) {
        //     $hotel_loc_name = Location::where('id', $hotel_data->location)->first();
        //     $hotel_data->location_name = $hotel_loc_name->name;
        //     $amenity = HotelAmenity::join('amenities','amenities.id','=','hotel_amenities.amenity_id')
        //                         ->where('hotel_amenities.hotel_id','=',$hotel_data->id)
        //                         ->select('amenities.name as amenityName','amenities.icon as amenityIcon')
        //                         ->get();
        //     $hotel_data->amenity = $amenity;
        // }

        
		// 	if($modules->banner == 1){
		//         $pdf_banner = new pdfBanner();
		//         $pdf_banner->tour_package_id = $route_id;
		//         $pdf_banner->itinerary_id = $modules->itinerary_id;
		//         $pdf_banner->company_name = $headerFooter->company_name;
		//         $pdf_banner->phone = $users->phone;
		//         $pdf_banner->email = $users->email;
		//         $pdf_banner->website = $users->website;
		//         $pdf_banner->banner_image = $package->banner_image;
		//         $pdf_banner->save();
		// 	}

		// 	if($modules->basic_detail == 1)
		// 	{
		// 		$basic_detail = new pdfBasicDetail();
		//         $basic_detail->tour_package_id = $route_id;
		//         $basic_detail->itinerary_id = $modules->itinerary_id;
		//         $basic_detail->package_name = $package->pname;
		//         $basic_detail->total_days = $package->total_days;
		//         $basic_detail->total_nights = $package->total_nights;
		//         $basic_detail->departs = 'NA';
		//         $basic_detail->start_from = $start_destination;
		//         $loop = 1;
		//         foreach($itinerary as $data){
		//             if($loop < 5){
		//             	$field_name = 'place'.$loop;
		//                 $basic_detail->$field_name = $data->location_name;
		//             }
		//             $loop++;
		//         }
		//         $attr_loop = 1;
		//         foreach ($topAttraction as $key => $value) {
		//             foreach($value->poi as $poi){
		//                 if($attr_loop < 7){
		//                 	$attr_name = 'attraction_name'.$attr_loop;
		//                 	$attr_address = 'attraction_address'.$attr_loop;
		//                 	$attr_image = 'attraction_image'.$attr_loop;

		//                     $basic_detail->$attr_name = $poi->poiName;
		//                     $basic_detail->$attr_address = $poi->poiAddress;
		//                     $basic_detail->$attr_image = $poi->poiImage;
		//                     $attr_loop++;
		//                 }
		//             }
		//         }
		//         $basic_detail->save();
		// 	}

		// 	if($modules->itinerary == 1)
		// 	{
		// 		foreach($itinerary as $daywise)
		// 		{
		//             $day_wise_itinerary = new pdfDayWiseItinerary();
		//             $day_wise_itinerary->tour_package_id = $route_id;
		//             $day_wise_itinerary->itinerary_id = $modules->itinerary_id;
		//             $day_wise_itinerary->day_number = $daywise->day_number;
		//             $day_wise_itinerary->name = $daywise->name;
		//             $day_wise_itinerary->description = $daywise->description;
		//             $day_wise_itinerary->banner_image = $daywise->banner_image;
		//             $img_loop = 1;
		//             foreach($daywise->poi as $top_pois){
		//                 if($img_loop < 2){
		//                     $day_wise_itinerary->poi_image = $top_pois->poiImage;
		//                 }
		//             }
		//             $day_wise_itinerary->save();
		//             foreach($daywise->poi as $top_pois){
		//                 $pdf_itinerary_poi = new pdfItineraryPoi();
		//                 $pdf_itinerary_poi->tour_package_id = $route_id;
		//                 $pdf_itinerary_poi->itinerary_id = $modules->itinerary_id;
		//                 $pdf_itinerary_poi->refrence_id = $day_wise_itinerary->id;
		//                 $pdf_itinerary_poi->name = $top_pois->poiName;
		//                 $pdf_itinerary_poi->address = $top_pois->poiAddress;
		//                 $pdf_itinerary_poi->image = $top_pois->poiImage;
		//                 $pdf_itinerary_poi->save();
		//             }
		//         }
		// 	}

		// 	if($modules->inclusion == 1)
		// 	{
		// 		$pdf_inclusion_exclusion = new pdfInclusionExclusion();
		//         $pdf_inclusion_exclusion->tour_package_id = $route_id;
		//         $pdf_inclusion_exclusion->itinerary_id = $modules->itinerary_id;
		//         $inc_image = '';
		//         $exc_image = '';
		//         $attr_index = 1;
		//         foreach($topAttraction as $last_image){
		//             foreach($last_image->poi as $poi_mage){
		//                 if($attr_index == 1){
		//                     $exc_image = $poi_mage->poiImage;
		//                 }
		//                 $inc_image = $poi_mage->poiImage;
		//                 $attr_index++;
		//             }
		//         }
		//         $pdf_inclusion_exclusion->inc_image = $inc_image;
		//         $pdf_inclusion_exclusion->exc_image = $exc_image;
		//         if($exclusion !== null){
		//         	$pdf_inclusion_exclusion->exclusion = $exclusion->exclusion;
		//         }
		//         $inc_last_id = $pdf_inclusion_exclusion->save();
		//         foreach($inclusion as $inc){
		//             $pdf_inclusion = new pdfInclusion();
		//             $pdf_inclusion->tour_package_id = $route_id;
		//             $pdf_inclusion->itinerary_id = $modules->itinerary_id;
		//             $pdf_inclusion->refrence_id = $inc_last_id;
		//             $pdf_inclusion->name = $inc->name;
		//             $pdf_inclusion->save();
		//         }
		// 	}

		// 	if($modules->terms == 1){
		// 		$pdf_terms = new pdfTerm();
		//         $pdf_terms->tour_package_id = $route_id;
		//         $pdf_terms->itinerary_id = $modules->itinerary_id;
		//         if($terms !== null)
		//         {
		//         	$pdf_terms->terms = $terms->terms;
		//         }
		//         $pdf_terms->save();
		// 	}

		// 	if($modules->contact == 1)
		// 	{
		// 		$con_address = '';
		//         if ($users->address_street != '' || $users->address_street != null) {
		//             $con_address = $users->address_street;
		//         }
		//         if ($users->address_city != '' || $users->address_city != null) {
		//             $con_address = $con_address.', '.$users->address_city;
		//         }
		//         if ($users->address_zip != '' || $users->address_zip != null) {
		//             $con_address = $con_address.' - '.$users->address_zip;
		//         }
		//         if ($users->address_country != '' || $users->address_country != null) {
		//             $con_address = $con_address.', '.$users->address_country;
		//         }
		//         $pdf_contact = new pdfContact();
		//         $pdf_contact->tour_package_id = $route_id;
		//         $pdf_contact->itinerary_id = $modules->itinerary_id;
		//         $pdf_contact->company_name = $company_name;
		//         $pdf_contact->name = $users->name;
		//         $pdf_contact->mobile = $users->mobile;
		//         $pdf_contact->email = $users->email;
		//         $pdf_contact->address = $con_address;
		//         if($guide !== null){
		//             $pdf_contact->guide_name = $guide->name;
		//             $pdf_contact->guide_contact = $guide->phone;
		//         }

		//         if($manager !== null){
		//             $pdf_contact->manager_name = $manager->name;
		//             $pdf_contact->manager_phone = $manager->phone;
		//             $pdf_contact->manager_email = $manager->email;
		//         }
		//         $pdf_contact->save();
		// 	}

			return \Redirect::route('pdf-pages.index', $route_id);
		}
	}

	public function pdfPages(Request $request, $id)
    {
    	$route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;

        $devDeparture  = Departure::where('id',$route_id)->first();
        $tenant_id  = $devDeparture->tenant_id;
        
    	$banner_data = PdfBanner::where('departure_id',$route_id)->first();
    	$basic_detail_data = PdfBasicDetail::where('departure_id',$route_id)->first();
    	$contact_data = PdfContact::where('departure_id',$route_id)->first();
    	$term_data = PdfTerm::where('departure_id',$route_id)->first();
		$inclusion_data = PdfInclusion::where('departure_id',$route_id)->get();
    	$itinerary_data = PdfDayWiseItinerary::where('departure_id',$route_id)->get();
    	foreach ($itinerary_data as $key => $value) {
    		$poi = PdfItineraryPoi::where('departure_id', $route_id)->where('refrence_id',$value->id)->get();
    		$value->pois = $poi;
    	}
    	return view('pdf-pages/index',compact('route_id','tenant_id','banner_data','basic_detail_data','contact_data','term_data','inclusion_data','itinerary_data','devDeparture'));
    }

  //   public function bannerUpdate(Request $request)
  //   {
  //   	$data = $request->all();
  //   	$pdf_banner = PdfBanner::findOrFail($request->id);
  //   	$pdf_banner->company_name = $request->company_name;
  //   	$pdf_banner->phone = $request->phone;
  //   	$pdf_banner->w_mobile = $request->w_mobile;
  //   	$pdf_banner->email = $request->email;
		// $pdf_banner->website = $request->website;
		
		// // S3 bucket upload image
  //       // if($request->file('banner_image'))
  //       // { 
  //       //     $file1 = $request->file('banner_image');
  //       //     $imageName1 = str_random(5).time().'.'.$file1->getClientOriginalExtension();
  //       //     $image1 = Image::make($file1);
  //       //     Storage::disk('s3')->put('banner_image/'.$imageName1, $image1->stream(), 'public');
  //       //     $pdf_banner->banner_image = $imageName1;             
		// // }
		
		// if($request->banner_image){ 
		// 	$file = $request->file('banner_image');
		// 	$imageName = Str::random(5).time().'.'.$file->getClientOriginalExtension();
		// 		$relPath = 'dook/images/package/';
		// 		if (!file_exists(public_path($relPath))) {
		// 			mkdir(public_path($relPath), 777, true);
		// 		}
		// 	$img = Image::make($file)->save( public_path($relPath . $imageName ) ); 
		// 	//Storage::disk('spaces')->putFileAs('dook/images/package/', $file, $imageName, 'public');
		// 	$pdf_banner->banner_image = $imageName;
		// }

  //   	$pdf_banner->save();
  //   	return redirect()->back()->with('message', 'Banner Details Updates successfully!');
  //   }

    public function basicPageUpdate(Request $request)
    {
    	$data = $request->all();
    	$pdf_basic = PdfBasicDetail::findOrFail($request->basic_id);
    	$pdf_basic->title = $request->package_name;
    	$pdf_basic->total_days = $request->total_days;
    	$pdf_basic->total_nights = $request->total_nights;
    	$pdf_basic->departs = $request->departs_on;
    	$pdf_basic->start_from = $request->start_from;
    	$pdf_basic->place1 = $request->place1;
    	$pdf_basic->place2 = $request->place2;
    	$pdf_basic->place3 = $request->place3;
    	$pdf_basic->place4 = $request->place4;
    	$pdf_basic->attraction_name1 = $request->attraction1;
    	$pdf_basic->attraction_name2 = $request->attraction2;
    	$pdf_basic->attraction_name3 = $request->attraction3;
    	$pdf_basic->attraction_name4 = $request->attraction4;
    	$pdf_basic->attraction_name5 = $request->attraction5;
    	$pdf_basic->attraction_name6 = $request->attraction6;
    	$pdf_basic->attraction_address1 = $request->address1;
    	$pdf_basic->attraction_address2 = $request->address2;
    	$pdf_basic->attraction_address3 = $request->address3;
    	$pdf_basic->attraction_address4 = $request->address4;
    	$pdf_basic->attraction_address5 = $request->address5;
		$pdf_basic->attraction_address6 = $request->address6;
		
		if($request->image1){ 
			$file = $request->file('image1');
			$imageName1 = Str::random(5).time().'.'.$file->getClientOriginalExtension();
				$relPath = 'dook/images/poi/';
				if (!file_exists(public_path($relPath))) {
					mkdir(public_path($relPath), 777, true);
				}
			$img = Image::make($file)->save( public_path($relPath . $imageName1 ) ); 
			//Storage::disk('spaces')->putFileAs('dook/images/poi/', $file, $imageName1, 'public');
			$pdf_basic->attraction_image1 = $imageName1;
		}

		if($request->image2){ 
			$file = $request->file('image2');
			$imageName2 = Str::random(5).time().'.'.$file->getClientOriginalExtension();
				$relPath = 'dook/images/poi/';
				if (!file_exists(public_path($relPath))) {
					mkdir(public_path($relPath), 777, true);
				}
			$img = Image::make($file)->save( public_path($relPath . $imageName2 ) ); 
			//Storage::disk('spaces')->putFileAs('dook/images/poi/', $file, $imageName2, 'public');
			$pdf_basic->attraction_image2 = $imageName2;
		}

		if($request->image3){ 
			$file = $request->file('image3');
			$imageName3 = Str::random(5).time().'.'.$file->getClientOriginalExtension();
				$relPath = 'dook/images/poi/';
				if (!file_exists(public_path($relPath))) {
					mkdir(public_path($relPath), 777, true);
				}
			$img = Image::make($file)->save( public_path($relPath . $imageName3 ) ); 
			//Storage::disk('spaces')->putFileAs('dook/images/poi/', $file, $imageName3, 'public');
			$pdf_basic->attraction_image3 = $imageName3;
		}

		if($request->image4){ 
			$file = $request->file('image4');
			$imageName4 = Str::random(5).time().'.'.$file->getClientOriginalExtension();
				$relPath = 'dook/images/poi/';
				if (!file_exists(public_path($relPath))) {
					mkdir(public_path($relPath), 777, true);
				}
			$img = Image::make($file)->save( public_path($relPath . $imageName4 ) ); 
			//Storage::disk('spaces')->putFileAs('dook/images/poi/', $file, $imageName4, 'public');
			$pdf_basic->attraction_image4 = $imageName4;
		}

		if($request->image5){ 
			$file = $request->file('image5');
			$imageName5 = Str::random(5).time().'.'.$file->getClientOriginalExtension();
				$relPath = 'dook/images/poi/';
				if (!file_exists(public_path($relPath))) {
					mkdir(public_path($relPath), 777, true);
				}
			$img = Image::make($file)->save( public_path($relPath . $imageName5 ) ); 
			//Storage::disk('spaces')->putFileAs('dook/images/poi/', $file, $imageName5, 'public');
			$pdf_basic->attraction_image5 = $imageName5;
		}

		if($request->image6){ 
			$file = $request->file('image6');
			$imageName6 = Str::random(5).time().'.'.$file->getClientOriginalExtension();
				$relPath = 'dook/images/poi/';
				if (!file_exists(public_path($relPath))) {
					mkdir(public_path($relPath), 777, true);
				}
			$img = Image::make($file)->save( public_path($relPath . $imageName6 ) ); 
			//Storage::disk('spaces')->putFileAs('dook/images/poi/', $file, $imageName6, 'public');
			$pdf_basic->attraction_image6 = $imageName6;
		}

    	// if($request->hasFile('image1')){
        //     $file1 = $request->file('image1');
        //     $imageName1 = str_random(5).time().'.'.$file1->getClientOriginalExtension();
        //     $image1 = Image::make($file1);
        //     Storage::disk('s3')->put('poi/'.$imageName1, $image1->stream(), 'public');
        //     $pdf_basic->attraction_image1 = $imageName1;
		// }
		
        // if($request->hasFile('image2')){
        //     $file2 = $request->file('image2');
        //     $imageName2 = str_random(5).time().'.'.$file2->getClientOriginalExtension();
        //     $image2 = Image::make($file2);
        //     Storage::disk('s3')->put('poi/'.$imageName2, $image2->stream(), 'public');
        //     $pdf_basic->attraction_image2 = $imageName2;
        // }
        // if($request->hasFile('image3')){
        //     $file3 = $request->file('image3');
        //     $imageName3 = str_random(5).time().'.'.$file3->getClientOriginalExtension();
        //     $image3 = Image::make($file3);
        //     Storage::disk('s3')->put('poi/'.$imageName3, $image3->stream(), 'public');
        //     $pdf_basic->attraction_image3 = $imageName3;
        // }
        // if($request->hasFile('image4')){
        //     $file4 = $request->file('image4');
        //     $imageName4 = str_random(5).time().'.'.$file4->getClientOriginalExtension();
        //     $image4 = Image::make($file4);
        //     Storage::disk('s3')->put('poi/'.$imageName4, $image4->stream(), 'public');
        //     $pdf_basic->attraction_image4 = $imageName4;
        // }
        // if($request->hasFile('image5')){
        //     $file5 = $request->file('image5');
        //     $imageName5 = str_random(5).time().'.'.$file5->getClientOriginalExtension();
        //     $image5 = Image::make($file5);
        //     Storage::disk('s3')->put('poi/'.$imageName5, $image5->stream(), 'public');
        //     $pdf_basic->attraction_image5 = $imageName5;
        // }
        // if($request->hasFile('image6')){
        //     $file6 = $request->file('image6');
        //     $imageName6 = str_random(5).time().'.'.$file6->getClientOriginalExtension();
        //     $image6 = Image::make($file6);
        //     Storage::disk('s3')->put('poi/'.$imageName6, $image6->stream(), 'public');
        //     $pdf_basic->attraction_image6 = $imageName6;
        // }
    	$pdf_basic->save();
    	return redirect()->back()->with('message', 'Basic Details Updates successfully!');
    }

    // public function contactUpdate(Request $request)
    // {
    // 	$pdf_contact = PdfContact::findOrFail($request->contact_id);
    // 	$pdf_contact->company_name = $request->company_name;
    // 	$pdf_contact->name = $request->name;
    // 	$pdf_contact->phone = $request->phone;
    // 	$pdf_contact->mobile = $request->mobile;
    // 	$pdf_contact->email = $request->email;
    // 	$pdf_contact->address = $request->address;
    // 	$pdf_contact->manager_name = $request->manager_name;
    // 	$pdf_contact->manager_phone = $request->manager_phone;
    // 	$pdf_contact->manager_email = $request->manager_email;
    // 	$pdf_contact->save();
    // 	return redirect()->back()->with('message', 'Contact details Updates successfully!');
    // }

    public function termsUpdate(Request $request)
    {
    	$pdf_terms = pdfTerm::findOrFail($request->terms_id);
    	$pdf_terms->terms = $request->terms;
    	$pdf_terms->save();
    	return redirect()->back()->with('message', 'Terms & Conditions Updates successfully!');
    }

    public function incExcUpdate(Request $request)
    {
    	$i = 0;
    	foreach ($request->inclusion_id as $value) {
    		$pdf_inclucion = pdfInclusion::findOrFail($value);
    		$pdf_inclucion->name = $request->inclusion_name[$i];
    		$pdf_inclucion->save();
    		$i++;
    	}
    	return redirect()->back()->with('message', 'Inclucion Updates successfully!');
    }

    public function itineraryUpdate(Request $request)
    {
    	$pdf_itinerary = PdfDayWiseItinerary::findOrFail($request->itinerary_id);
    	$pdf_itinerary->name = $request->name;
		$pdf_itinerary->description = $request->description;
		if($request->banner_image){ 
			$file = $request->file('banner_image');
			$imageName = Str::random(5).time().'.'.$file->getClientOriginalExtension();
			$relPath = 'dook/images/poi/';
				if (!file_exists(public_path($relPath))) {
					mkdir(public_path($relPath), 777, true);
				}
			$img = Image::make($file)->save( public_path($relPath . $imageName ) ); 
			//Storage::disk('spaces')->putFileAs('dook/images/poi/', $file, $imageName, 'public');
			$pdf_itinerary->banner_image = $imageName;
		}
		if($request->poi_image){ 
			$file = $request->file('poi_image');
			$imageName1 = Str::random(5).time().'.'.$file->getClientOriginalExtension();
				$relPath = 'dook/images/poi/';
				if (!file_exists(public_path($relPath))) {
					mkdir(public_path($relPath), 777, true);
				}
			$img = Image::make($file)->save( public_path($relPath . $imageName1 ) ); 
			//Storage::disk('spaces')->putFileAs('dook/images/poi/', $file, $imageName1, 'public');
			$pdf_itinerary->image = $imageName1;
		}
    	// if($request->hasFile('banner_image')){
        //     $file = $request->file('banner_image');
        //     $imageName = str_random(5).time().'.'.$file->getClientOriginalExtension();
        //     $image = Image::make($file);
        //     Storage::disk('s3')->put('itineary/'.$imageName, $image->stream(), 'public');
        //     $pdf_itinerary->banner_image = $imageName;
        // }
        // if($request->hasFile('poi_image')){
        //     $file1 = $request->file('poi_image');
        //     $imageName1 = str_random(5).time().'.'.$file1->getClientOriginalExtension();
        //     $image1 = Image::make($file1);
        //     Storage::disk('s3')->put('poi/'.$imageName1, $image1->stream(), 'public');
        //     $pdf_itinerary->poi_image = $imageName1;
        // }
        $pdf_itinerary->save();
        if($request->poi_id != ''){
	        foreach ($request->poi_id as $key => $value) {
	        	$pdf_itinerary_poi = PdfItineraryPoi::findOrFail($value);
	        	$pdf_itinerary_poi->name = $request->poi_name[$key];
	        	$pdf_itinerary_poi->address = $request->address[$key];
	        	//$arrImg = explode('/images/poi/', $request->image[$key]);
        		if(isset($request->image[$key])){
        			$file = $request->image[$key];
	        		$img = $request->image[$key]->getClientOriginalName();
					
					$poi_img = Str::random(5).time().'.jpg';
					$relPath = 'dook/images/poi/';
						if (!file_exists(public_path($relPath))) {
							mkdir(public_path($relPath), 775, true);
						}
					$img = Image::make($file)->save( public_path($relPath . $poi_img ) ); 
					//Storage::disk('spaces')->putFileAs('dook/images/poi/', $file[$key], $poi_img, 'public');
					$pdf_itinerary_poi->image = $poi_img;
				}
	        	$pdf_itinerary_poi->save();
	        }
	    }
        return redirect()->back()->with('message', 'Itinerary Updated successfully!');
    }

    public function addPage(Request $request)
    {
        $page_name = $request->page_name;
        $filename = "pdffiles/" .Auth()->user()->tenant_id. "/" .$request->package_id;
        if (!file_exists(public_path($filename,0777,true))) {
            File::makeDirectory(public_path().'/'.$filename,0777,true);
        }
        if($page_name == 'banner'){
            $pdf_banner = pdfBanner::where('id', $request->id)->first();
            if(file_exists('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id.'/banner.html')){
                unlink('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id.'/banner.html');
            }
            if (!file_exists('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id)) {
                mkdir('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id, 0777, true);
            }
   
            $public_path = public_path('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id.'/banner.html');
            $return_html_file = View('pdffiles/banner', compact('pdf_banner'))->render();
            $file = File::put($public_path,$return_html_file);
            return redirect()->back()->with('message', 'Banner page addedd succefully!!');
        }

        if($page_name == 'basic_detail'){
            $pdf_basic_detail = pdfBasicDetail::where('id', $request->id)->first();
			$pdf_basic_detail->packageId = Departure::where('id',$pdf_basic_detail->departure_id)->first();
            if(file_exists('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id.'/basic-details.html')){
                unlink('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id.'/basic-details.html');
            }
            if (!file_exists('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id)) {
                mkdir('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id, 0777, true);
            }
   
            $public_path = public_path('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id.'/basic-details.html');
            $return_html_file = View('pdffiles/basic-details', compact('pdf_basic_detail'))->render();
            $file = File::put($public_path,$return_html_file);
            return redirect()->back()->with('message', 'Basic detail page addedd succefully!!');
        }

        if($page_name == 'contact'){
            $pdf_contact = pdfContact::where('id', $request->id)->first();
            $pdf_banner = pdfBanner::where('departure_id', $request->package_id)->first();
            if(file_exists('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id.'/contact.html')){
                unlink('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id.'/contact.html');
            }
            if (!file_exists('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id)) {
                mkdir('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id, 0777, true);
            }
   
            $public_path = public_path('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id.'/contact.html');
            $return_html_file = View('pdffiles/contact', compact('pdf_contact','pdf_banner'))->render();
            $file = File::put($public_path,$return_html_file);
            return redirect()->back()->with('message', 'Contact detail page addedd succefully!!');
        }

        if($page_name == 'terms'){
            $pdf_terms = pdfTerm::where('id', $request->id)->first();
            if(file_exists('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id.'/terms.html')){
                unlink('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id.'/terms.html');
            }
            if (!file_exists('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id)) {
                mkdir('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id, 0777, true);
            }
   
            $public_path = public_path('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id.'/terms.html');
            $return_html_file = View('pdffiles/terms-conditions', compact('pdf_terms'))->render();
            $file = File::put($public_path,$return_html_file);
            return redirect()->back()->with('message', 'Terms & Conditions page addedd succefully!!');
        }

        if($page_name == 'inclusion'){
            $pdf_inclusion = PdfInclusion::where('departure_id', $request->package_id)->get();
            foreach ($pdf_inclusion as $key => $inc) {
                if($inc->name == 'Visa')
                    $inc->image = 'https://stage.tlakapp.com/media/itinerary/passport.png';
                else if($inc->name == 'Accommodation')
                    $inc->image = 'https://stage.tlakapp.com/media/itinerary/hotel.png';
                else if($inc->name == 'Air Ticket')
                    $inc->image = 'https://stage.tlakapp.com/media/itinerary/flight.png';
                else if($inc->name == 'Breakfast')
                    $inc->image = 'https://stage.tlakapp.com/media/itinerary/coffee.png';
                else if($inc->name == 'Lunch')
                    $inc->image = 'https://stage.tlakapp.com/media/itinerary/food.png';
                else if($inc->name == 'Dinner')
                    $inc->image = 'https://stage.tlakapp.com/media/itinerary/dinner.png';
                else{
                    $inc->image = '';
                }
            }
            if(file_exists('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id.'/inclusion.html')){
                unlink('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id.'/inclusion.html');
            }
            if (!file_exists('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id)) {
                mkdir('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id, 0777, true);
            }
   
            $public_path = public_path('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id.'/inclusion.html');
            $return_html_file = View('pdffiles/inclusion', compact('pdf_inclusion'))->render();
            $file = File::put($public_path,$return_html_file);
            return redirect()->back()->with('message', 'Inclusion/Exclusion page addedd succefully!!');
        }

        if($page_name == 'itinerary'){
        	$day_number = $request->day_number;
        	$file = 'day'.$day_number.'.html';
            $pdf_itineraries = PdfDayWiseItinerary::where('id', $request->id)->first();
            $pdf_itineraries->pois = PdfItineraryPoi::where('refrence_id', $request->id)->where('departure_id', $request->package_id)->get();

            if(file_exists('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id.'/'.$file)){
                unlink('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id.'/'.$file);
            }
            if (!file_exists('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id)) {
                mkdir('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id, 0777, true);
            }
   
            $public_path = public_path('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id.'/'.$file);
            $return_html_file = View('pdffiles/day1', compact('pdf_itineraries'))->render();
            $file = File::put($public_path,$return_html_file);
            return redirect()->back()->with('message', 'Day'.$day_number.' page addedd succefully!!');
        }
        if($page_name == 'hotel'){
        	$id = $request->id;
        	$data = pdfHotel::whereIn('id', $id)->get();
        	$file = 'hotel'.$request->page_number.'.html';
            if(file_exists('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id.'/'.$file)){
                unlink('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id.'/'.$file);
            }
            if (!file_exists('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id)) {
                mkdir('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id, 0777, true);
            }
   
            $public_path = public_path('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id.'/'.$file);
            $return_html_file = View('pdffiles/hotel1', compact('data'))->render();
            $file = File::put($public_path,$return_html_file);
            return redirect()->back()->with('message', 'Hotel Page'.$request->page_number.' addedd succefully!!');
        }
        if($page_name == 'flight'){
        	$id = $request->id;
        	$data = pdfFlight::whereIn('id', $id)->get();
        	$file = 'flight'.$request->page_number.'.html';
            if(file_exists('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id.'/'.$file)){
                unlink('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id.'/'.$file);
            }
            if (!file_exists('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id)) {
                mkdir('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id, 0777, true);
            }
   
            $public_path = public_path('pdffiles/'.Auth()->user()->tenant_id.'/'.$request->package_id.'/'.$file);
            $return_html_file = View('pdffiles/flight1', compact('data'))->render();
            $file = File::put($public_path,$return_html_file);
            return redirect()->back()->with('message', 'Flight Page'.$request->page_number.' addedd succefully!!');
        }
    }

    public function generatePdf(Request $request)
    {
    	$data = $request->all();
    	$route_id = $request->route_id;
    	$pkg_name = Departure::where('id',$request->route_id)
    				->value('title');
    	$array_pkg = explode(" ",$pkg_name);
    	$str_pkg = implode("-",$array_pkg);
    	//dd($str_pkg);	
    	$user_code = mt_rand(10,100000);	
    	$file_names = $str_pkg.'-'.$user_code;
    	$file_name_crt = str_replace(' ', '', $file_names);
    	$file_name_d =  $this->generateSlug($file_name_crt, 1000);
		$file_name = $file_name_d.'.pdf';

		$url='https://serv.itineraryfinder.com/api/pdf/verification?url=https://adm.dookinternational.com/pdffiles/'.$request->tenant_id.'/'.$route_id.'/&returnurl=https://pdfs.tlakapp.com/pdfhtml/response.php&usercode='.$user_code.'&pdfname='.$file_name;
    	$response_data = file_get_contents($url);

    	// print_r($response_data);
    	// die;
    	$response = json_decode($response_data, true);
    	if($response['status'] == 'true')
    	{
    		$file = new PdfFile();
    		$file->departure_id = $request->route_id;
    		$file->tenant_id = $request->tenant_id;
    		$file->itinerary_id = $request->itinerary_id;
    		$file->file_name = $response['file_name'];
    		$file->status = "false";
			$file->code = $response['code'];
    		$file->save();
    		$last_file = $file->file_name;
    	}
    	//$this->callPdf($user_code); 

    	$status = [
            'file_name'=> $file_name,
            'user_cade'=> $user_code,
        ];
        return response()->json($status);	
    }

    function callPdf(Request $request)
    {

    	$file = $request->fileName;
    	$userCode = $request->userCodes;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://serv.itineraryfinder.com/api/pdf?code='.$userCode.'');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, 'GET' );
        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        //$file = pdfFile::where('code', $userCode)->value('status');
        if($httpcode == 200){
        	$status = [
        	'error' =>false,
            'file_name'=> $request->fileName,
            'user_cade'=> $request->userCodes,
            'http_Code' => 200
	        ];	
        }
        elseif($httpcode == 504){
        	$status = [
        	'error' =>false,
            'file_name'=> $request->fileName,
            'user_cade'=> $request->userCodes,
            'http_Code' => 504
	        ];
        }
        elseif($httpcode == 500){
        	$status = [
        	'error' =>false,
            'file_name'=> $request->fileName,
            'user_cade'=> $request->userCodes,
            'http_Code' => $httpcode
	        ];	
        }
		else{
        	$status = [
        	'error' =>true,
            'file_name'=> $request->fileName,
            'user_cade'=> $request->userCodes,
            'http_Code' => $httpcode
	        ];	
        }
        return response()->json($status);
    }

    public function checkStatusPdf(Request $request)
    {
    	$pdf_file = PdfFile::where('code', $request->user_code)->first();
    	return response()->json($pdf_file->status);
    }

    function generateSlug($phrase, $maxLength)
	{
	    $result = strtolower($phrase);

	    $result = preg_replace("/[^a-z0-9\s-]/", "", $result);
	    $result = trim(preg_replace("/[\s-]+/", " ", $result));
	    $result = trim(substr($result, 0, $maxLength));
	    $result = preg_replace("/\s/", "-", $result);

	    return $result;
	}
 	//////////////old pdf end //////////////////
	// Pdf Dom Funtionality
	 public function bannerUpdate(Request $request)
    {
    	$data = $request->all();
    	$route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
    	$pdf_banners = PdfBanner::where('id',$request->id)->first();
    	if($pdf_banners){
    		$pdf_banner = PdfBanner::find($request->id);
    		$pdf_banner->company_name = $request->company_name;
	    	$pdf_banner->phone = $request->contact_no;
	    	$pdf_banner->w_mobile = $request->whatsapp_no;
	    	$pdf_banner->email = $request->email;
			$pdf_banner->website = $request->website;
			$pdf_banner->company_logo = "https://www.dookinternational.com/assets/images/logo.png";

			  if ($request->hasFile('banner_image')) {
		            $image = $request->file('banner_image');
		            $originalName = $image->getClientOriginalName(); // original file name with extension
		            $path = 'com/package/' . $originalName;

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

		            $pdf_banner->banner_image = $originalName;
		        }
			// if($request->banner_image){ 
			// 	$file = $request->file('banner_image');
			// 	$imageName = Str::random(5).time();
			// 		$relPath = 'dook/images/package/';
			// 		if (!file_exists(public_path($relPath))) {
			// 			mkdir(public_path($relPath), 777, true);
			// 		}
			// 	$img = Image::make($file)->save( public_path($relPath . $imageName ) ); 
			// 	$pdf_banner->banner_image = $imageName;
			// }

	    	$pdf_banner->save();
    	}else{
    		$pdf_banner = new PdfBanner;
    		$pdf_banner->company_name = $request->company_name;
	    	$pdf_banner->phone = $request->contact_no;
	    	$pdf_banner->w_mobile = $request->whatsapp_no;
	    	$pdf_banner->email = $request->email;
			$pdf_banner->website = $request->website;
			$pdf_banner->departure_id = $request->departureid;
			$pdf_banner->company_logo = "https://www.dookinternational.com/assets/images/logo.png";
			if ($request->hasFile('banner_image')) {
		            $image = $request->file('banner_image');
		            $originalName = $image->getClientOriginalName(); // original file name with extension
		            $path = 'com/package/' . $originalName;

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

		            $pdf_banner->banner_image = $originalName;
		        }
			// if($request->banner_image){ 
			// 	$file = $request->file('banner_image');
			// 	$imageName = Str::random(5).time();
			// 		$relPath = 'dook/images/package/';
			// 		if (!file_exists(public_path($relPath))) {
			// 			mkdir(public_path($relPath), 777, true);
			// 		}
			// 	$img = Image::make($file)->save( public_path($relPath . $imageName ) ); 
			// 	$pdf_banner->banner_image = $imageName;
			// }
	    	$pdf_banner->save();
    	}
    	return redirect()->back()->with('message', 'Banner detail updated successfully!');
    }
    public function contactUpdate(Request $request)
    {
    	$data = $request->all();
    	$route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
    	$pdf_contacts = PdfContact::where('id',$request->contact_id)->first();
    	if($pdf_contacts){
    		$pdf_contact = PdfContact::find($request->contact_id);
	    	$pdf_contact->company_name = $request->company_name;
	    	$pdf_contact->name = $request->name;
	    	$pdf_contact->phone = $request->contact_no;
	    	$pdf_contact->mobile = $request->whatsapp_no;
	    	$pdf_contact->email = $request->email;
	    	$pdf_contact->address = $request->address;
	    	$pdf_contact->manager_name = $request->manager_name;
	    	$pdf_contact->manager_phone = $request->manager_phone;
	    	$pdf_contact->manager_email = $request->manager_email;
	    	$pdf_contact->save();
    	}else{
    		$pdf_contact = new PdfContact;
    		$pdf_contact->company_name = $request->company_name;
	    	$pdf_contact->name = $request->name;
	    	$pdf_contact->phone = $request->contact_no;
	    	$pdf_contact->mobile = $request->whatsapp_no;
	    	$pdf_contact->email = $request->email;
	    	$pdf_contact->address = $request->address;
	    	$pdf_contact->manager_name = $request->manager_name;
	    	$pdf_contact->manager_phone = $request->manager_phone;
	    	$pdf_contact->manager_email = $request->manager_email;
	    	$pdf_contact->departure_id = $request->departureid;
	    	$pdf_contact->save();
    	}
    	
    	return redirect()->back()->with('message', 'Contact detail updated successfully!');
    }
	public function pdfList(Request $request, $id)
	{
		$data = PdfFile::where('tenant_id', Auth()->user()->tenant_id)->where('departure_id', $id)->get();
		return view('dome-pdf-pages/pdf_list', compact('data'));
	}
	//latest pdf page raj
    public function domPdfPages(Request $request, $id)
    {
    	$urlS3 = 'https://dooktravels.s3.ap-south-1.amazonaws.com/com/package/';
    	$urlS2 = 'https://dooktravels.s3.ap-south-1.amazonaws.com/com/poi/';
    	$route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        
        $current_date = date('Y-m-d');
        $devDeparture  = Departure::where('id',$route_id)->first();

        $tenant_id  = $devDeparture->tenant_id;
        $conatctMaster = DB::table('pdf_master_contacts')
        	->select('company_name','name','whatsapp_no','contact_no','email','address','manager_name','manager_email','manager_phone')
    		->first();
    	
    	$contact_datas = PdfContact::where('departure_id',$route_id)
    		->select('id','company_name','name','mobile as whatsapp_no','phone as contact_no','email','address','manager_name','manager_email','manager_phone')
    		->first();

    	if($contact_datas){
    		$contact_data = $contact_datas;
    	}else{
    		$contact_data = $conatctMaster;
    	}
    	$banner_master = DB::table('pdf_master_banners')
    		->select('company_name','whatsapp_no','contact_no','email','company_logo','banner_image','website')
    		->first();
    	$banner_datas = PdfBanner::where('departure_id',$route_id)
    		->select('id','company_name','w_mobile as whatsapp_no','phone as contact_no','email','banner_image','website')
    		->first();
    	
    	if($banner_datas){
    		$banner_datas->banner_image = $banner_datas->banner_image;
    		$banner_data = $banner_datas;
    	}else{
    		$banner_master->banner_image = $banner_master->banner_image;
    		$banner_data = $banner_master;
    	}

    	$departure  = Departure::where('id',$id)->first();
        
    	if($departure){
    		$departureDate = DB::table('departure_dates')
	        	->where(['departure_id'=>$departure->id,'status'=>1])
	            ->orderBy('date', 'ASC')
	            ->where('date','>=',$current_date)
	            ->select('date')->get();
	        $departure->dates = $departureDate;
    		$firstDate = DB::table('departure_dates')
	        	->where(['departure_id'=>$departure->id,'status'=>1])
	            ->orderBy('date', 'ASC')
	            ->where('date','>=',$current_date)
	            ->select('date')->first();
	        if($firstDate){
	        	$departure->departs = $firstDate->date;
	        }else{
	        	$departure->departs = "NA";
	        }
	        if($banner_data){
	        	$departure->banner_imageD = $urlS3.$departure->banner_image;
	        	if($banner_data->banner_image == "" || $banner_data->banner_image == null){
					$banner_data->banner_image = $departure->banner_image;
	        	}
	        }
			$destination_id = DB::table('departure_destinations')
							->where('departure_id', $id)
							->distinct()
							->pluck('destination_id')
							->toArray();
			$destinations = DB::table('destinations')
							->whereIn('id', $destination_id)
							->select('dest_name')
							->get();
			$pkg_pois = DB::table('departure_destination_point_of_interests')
						->where('departure_id',$id)
						->select('destination_id','poi_name','address','image')
						->get();

			foreach ($pkg_pois as $key => $pkg_poi) {
				if($pkg_poi->image != ""){
					$pkg_poi->image = $urlS2.$pkg_poi->image;
				}else{
					$pkg_poi->image = url('dook/images').'/'."poi_day_img.png";
				}
				if($pkg_poi->address == ""){
					$destCountrys = DB::table('destinations')
							->where('id',$pkg_poi->destination_id)
							->select('dest_name','country_name')
							->first();
					$pkg_pois->address = $destCountrys->dest_name.', '.$destCountrys->country_name;
				}
			}
							
			$itinerary_data = DB::table('itineraries')
						->where('departure_id', $id)
						->where('status',1)
						->distinct()
						->select('id','day_number','day_heading','description')
						->get();
			foreach ($itinerary_data as $key => $itinearay) {
				$description = strip_tags($itinearay->description, '<ul><li>');
				$itinearay->description = str_replace("\r\n",'', $description);
				$iti_destination_id = DB::table('destination_itinerary_point_of_interests')
						->where('itinerary_id', $itinearay->id)
						->where('departure_id', $id)
						->distinct()
						->pluck('point_of_interest_id')
						->toArray();

				$day_pois = DB::table('departure_destination_point_of_interests')
						->whereIn('reference_id', $iti_destination_id)
						->where('departure_id',$id)
						->select('destination_id','poi_name','address','image','banner_image','reference_id')
						->get();
				foreach ($day_pois as $key => $day_poi) {
					$randomPOI = DB::table('departure_destination_point_of_interests')
						->whereIn('destination_id', $iti_destination_id)
						->where('image','!=','')
						->select('destination_id','poi_name','address','image','banner_image')
						->inRandomOrder()
						->first();
					if(isset($randomPOI->image)){
						// pushing to itinerary
						$itinearay->day_image = $urlS2.$randomPOI->image;
						$itinearay->day_banner = $urlS2.$day_poi->banner_image;

						// pushing to pois

						$day_poi->image = $urlS2.$day_poi->image;
					}else{
						// pushing to itinerary
						$itinearay->day_image = $urlS2.$day_poi->image;
						$itinearay->day_banner = $urlS2.$day_poi->banner_image;

						// pushing to pois
						if($day_poi->image != ""){
							$day_poi->image = $urlS2.$day_poi->image;
						}else{
							$day_poi->image = url('dook/images').'/'."poi_day_img.png";
						}
					}

					if($day_poi->address == ""){
						$destCountry = DB::table('destinations')
								->where('id',$day_poi->destination_id)
								->select('dest_name','country_name')
								->first();
						$day_poi->address = $destCountry->dest_name.', '.$destCountry->country_name;
					}
				}
				//dd($randomPOI);
				$itinearay->day_pois = $day_pois;
				//$itinearay->pois = $day_pois;
			}
			$inclusion_data = Inclusion::where('departure_id',$id)
                    ->select('name', 'description')
                    ->distinct()
                    ->get();
        }
        $datas = PdfFile::where('tenant_id', Auth()->user()->tenant_id)->where('departure_id', $id)->orderBy('created_at','DESC')->get();
    	return view('dom-pdf-pages/index',compact('route_id','tenant_id','banner_data','contact_data','departure','destinations','inclusion_data','itinerary_data','pkg_pois','datas','urlS3'));
    }
    public function generatePDFDom(Request $request, $id)
    {
    	$urlS3 = 'https://dooktravels.s3.ap-south-1.amazonaws.com/com/package/';
    	$urlS2 = 'https://dooktravels.s3.ap-south-1.amazonaws.com/com/poi/';
    	$route_ids = $request->route('id'); 
        $route_id = (int)$route_ids;
        $current_date = date('Y-m-d');
        $devDeparture  = Departure::where('id',$route_id)->first();
        $tenant_id  = $devDeparture->tenant_id;
        $conatctMaster = DB::table('pdf_master_contacts')
        	->select('company_name','name','whatsapp_no','contact_no','email','address','manager_name','manager_email','manager_phone')
    		->first();
    	
    	$contact_datas = PdfContact::where('departure_id',$route_id)
    		->select('id','company_name','name','mobile as whatsapp_no','phone as contact_no','email','address','manager_name','manager_email','manager_phone')
    		->first();

    	if(isset($contact_datas)){
    		$contact_data = $contact_datas;
    	}else{
    		$contact_data = $conatctMaster;
    	}
    	$banner_master = DB::table('pdf_master_banners')
    		->select('company_name','whatsapp_no','contact_no','email','company_logo','banner_image')
    		->first();
    	$banner_datas = PdfBanner::where('departure_id',$route_id)
    		->select('id','company_name','w_mobile as whatsapp_no','phone as contact_no','email','banner_image','website')
    		->first();
    	
    	if($banner_datas){
    		$banner_datas->banner_image = $banner_datas->banner_image;

    		$banner_data = $banner_datas;
    	}else{
    		$banner_master->banner_image = $banner_master->banner_image;
    		$banner_data = $banner_master;
    	} 
        $departure  = Departure::where('id',$id)->first();
        
    	if($departure){
    		$departureDate = DB::table('departure_dates')
	        	->where(['departure_id'=>$departure->id,'status'=>1])
	            ->orderBy('date', 'ASC')
	            ->where('date','>=',$current_date)
	            ->select('date')->get();
	        $departure->dates = $departureDate;
	        $firstDate = DB::table('departure_dates')
	        	->where(['departure_id'=>$departure->id,'status'=>1])
	            ->orderBy('date', 'ASC')
	            ->where('date','>=',$current_date)
	            ->select('date')->first();
	        if($firstDate){
	        	$departure->departs = $firstDate->date;
	        }else{
	        	$departure->departs = "NA";
	        }
    		
    		$departure->poi_banner = $urlS3.$departure->image;
    		$departure->poi_image = $urlS3.$departure->banner_image; 
			if($banner_data){
	        	if($banner_data->banner_image == "" || $banner_data->banner_image == null){
					$banner_data->banner_image = $urlS3.$departure->banner_image;
	        	}
	        } 
			$departure->image = $urlS3.$departure->image;
			
			$destination_id = DB::table('departure_destinations')
							->where('departure_id', $id)
							->distinct()
							->pluck('destination_id')
							->toArray();
			$destinations = DB::table('destinations')
							->whereIn('id', $destination_id)
							->select('dest_name')
							->get();
			$pkg_pois = DB::table('departure_destination_point_of_interests')
						->where('departure_id',$id)
						->select('destination_id','poi_name','address','image')
						->get();
			foreach ($pkg_pois as $key => $pkg_poi) {
				if($pkg_poi->image != ""){
					$pkg_poi->image = $urlS2.$pkg_poi->image;
				}else{
					$pkg_poi->image = "/images/poi_day_img.png";
				}
				if($pkg_poi->address == ""){
					$destCountrys = DB::table('destinations')
							->where('id',$pkg_poi->destination_id)
							->select('dest_name','country_name')
							->first();
					$pkg_pois->address = $destCountrys->dest_name.', '.$destCountrys->country_name;
				}
			}
							
			$itinerary_data = DB::table('itineraries')
						->where('departure_id', $id)
						->where('status',1)
						->distinct()
						->select('id','day_number','day_heading','description')
						->get();
			foreach ($itinerary_data as $key => $itinearay) {
				$description = strip_tags($itinearay->description, '<ul><li>');
				$itinearay->description = str_replace("\r\n",'', $description);
				$iti_destination_id = DB::table('destination_itinerary_point_of_interests')
						->where('itinerary_id', $itinearay->id)
						->where('departure_id', $id)
						->distinct()
						->pluck('point_of_interest_id')
						->toArray();
				
				$day_pois = DB::table('departure_destination_point_of_interests')
						->whereIn('reference_id', $iti_destination_id)
						->where('departure_id',$id)
						->select('destination_id','poi_name','address','image','banner_image','reference_id')
						->get();
				if(count($day_pois)>0){
					foreach ($day_pois as $key => $day_poi) {
						$randomPOI = DB::table('departure_destination_point_of_interests')
							->whereIn('destination_id', $iti_destination_id)
							->where('image','!=','')
							->select('destination_id','poi_name','address','image','banner_image')
							->inRandomOrder()
							->first();
						$randomPOIs = DB::table('departure_destination_point_of_interests')
							->whereIn('destination_id', $iti_destination_id)
							->where('image','!=','')
							->select('destination_id','poi_name','address','image','banner_image')
							->inRandomOrder()
							->first();
						if(isset($randomPOI->image)){
							// pushing to itinerary
							$itinearay->day_image = $urlS2.$randomPOI->image;
							$itinearay->day_banner =$urlS2.$day_poi->banner_image;
							// pushing to pois
							$day_poi->image = $urlS2.$randomPOIs->image;
						}else{
							$itinearay->day_image = $urlS2.$day_poi->image;
							$itinearay->day_banner = $urlS2.$day_poi->banner_image;
							$day_poi->image = $urlS2.$day_poi->image;
						}

						if($day_poi->address == ""){
							$destCountry = DB::table('destinations')
									->where('id',$day_poi->destination_id)
									->select('dest_name','country_name')
									->first();
							$day_poi->address = $destCountry->dest_name.', '.$destCountry->country_name;
						}
					}
				}else{
					$itinearay->day_image = $departure->poi_image;
					$itinearay->day_banner = $departure->poi_banner;
				}
				$itinearay->day_pois = $day_pois;
				//$itinearay->pois = $day_pois;
			}
			$inclusion_data = Inclusion::where('departure_id',$id)
                    ->select('name', 'description')
                    ->get();
            // $banner_data = User::where('id',auth()->user()->id)->first();
            // $banner_data->website = "https://www.dookinternational.com";
            // $banner_data->whatsApp = "+91 8368513675";
            // $banner_data->contactNo = "+91 40001000";
            // $banner_data->email = "sales@dooktravels.com";
            // $banner_data->company_name = "Dook Travels";


            // $contact_data = User::where('id',auth()->user()->id)->first();
            // $contact_data->name = "Dook";
            // $contact_data->whatsApp = "+91 8368513675";
            // $contact_data->contactNo = "+91 40001000";
            // $contact_data->email = "sales@dooktravels.com";
            // $contact_data->guide = "Tour Manager";
            // $contact_data->guidePhone = "Tour Manager";
            // $contact_data->guideEmail = "Tour Manager";

		}
		$random_code = rand(10,100000);
        $data = compact('route_id','tenant_id','banner_data','contact_data','departure','destinations','inclusion_data','itinerary_data','pkg_pois');
        //return view('itinerary_p',compact('route_id','tenant_id','banner_data','contact_data','departure','destinations','inclusion_data','itinerary_data','pkg_pois'));
        $pdf = PDF::loadView('itinerary_p',$data);
        $pdf->getDomPDF()->set_option("enable_php", true);
        $publicPath = public_path('dook/pdf/');
    	
 		$savePdf = $pdf->save($publicPath.$departure->slug_url.'-'.$random_code.'.pdf',$data);
 		if($savePdf){
 			$pdfUpload = new PdfFile;
	 		$pdfUpload->departure_id  = $route_id;
	 		$pdfUpload->file_name  = $departure->slug_url.'-'.$random_code.'.pdf';
	 		$pdfUpload->tenant_id  = $tenant_id;
	 		$pdfUpload->code  = $random_code;
	 		$pdfUpload->status  = 'true';
	 		$pdfUpload->save();
	 	}else{
	 		$pdfUpload = new PdfFile;
	 		$pdfUpload->departure_id  = $route_id;
	 		$pdfUpload->file_name  = $departure->slug_url.'-'.$random_code.'.pdf';
	 		$pdfUpload->tenant_id  = $tenant_id;
	 		$pdfUpload->code  = $random_code;
	 		$pdfUpload->status  = 'false';
	 		$pdfUpload->save();
	 	}

	 	$status = [
	 		"msg" =>'Success'
	 	];
	 	return response()->json($status);	

    }
}
