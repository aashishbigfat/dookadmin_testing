<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VisaController;
use App\Http\Controllers\CountryWisePackageController;
// Add these routes to routes/web.php

use App\Http\Controllers\MetaLeadController;
use App\Post;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function (){
  return view('auth.login');
});

Route::get('/info',function(){
return phpinfo();
});

Auth::routes();

Route::group(['middleware' => 'auth'], function () {
//Admin Routes
	Route::get('/dashboard', 'HomeController@moveimageOneTwoAnother')->name('home');
	
	Route::get('/dashboard', 'HomeController@index')->name('home');
	//Home Setting Start
	Route::get('/home-page-settings', 'HomeSettingController@create')->name('home_setting');
	Route::post('/home-banner-store', 'HomeSettingController@homeBannerStore')->name('home_banner_store');
	Route::post('/home-banner-update', 'HomeSettingController@homeBannerUpdate')->name('home_banner_update');
	Route::post('/home-experiences-update', 'HomeSettingController@homeExperienceUpdate')->name('home_exp_store');
	Route::post('/home-country-update', 'HomeSettingController@homeCountryUpdate')->name('home_country_store');
	Route::post('/home-activity-update', 'HomeSettingController@homeActivityUpdate')->name('home_act_store');
	Route::post('/home-activity-img-update', 'HomeSettingController@homeActivityUpdateImg')->name('home_act_update');
	Route::post('/home-region-update', 'HomeSettingController@homeRegionUpdate')->name('home_region_store');
	Route::post('/home-why-update', 'HomeSettingController@whyChooseUpdate')->name('home_why_store');
	Route::post('/home-meta-update', 'HomeSettingController@homeMetaDataUpdate')->name('home_meta_store');
	Route::post('/home-page-update', 'HomeSettingController@update')->name('home_update');
	Route::get('home-activities-ajax', 'HomeSettingController@getActivities');
	Route::post('home-activity-position-reshifting','HomeSettingController@homeActivitypositionShifting')->name('homeActivitypositionShifting');
	Route::post('home_activity_delete/{id}','HomeSettingController@homeActivityDelete')->name('home_activity_delete');

	//Home Seting End
	Route::get('/group-tours', 'Departure\FixedDepartureController@departureIndex')->name('departures');
	Route::get('/departure/basic-detail/edit/{id}', 'Departure\FixedDepartureController@departureEdit')->name('departure_edit');
	Route::post('/departure/basic-detail/update/{id}', 'Departure\FixedDepartureController@departureUpdate')->name('departure_update');
	Route::post('/departure-disable/{id}', 'Departure\FixedDepartureController@departureDisable')->name('departure_disable');
	Route::get('get-destination-ajax', 'Departure\DepartureController@getDestinationAjax');
	//Inclusion Routes
	Route::get('/departure/inclusion/{id}', 'Departure\FixedDepartureInclusionController@departureInclusionIndex')->name('departure_inclusion');
	Route::post('/departure/inclusion/store/{id}', 'Departure\FixedDepartureInclusionController@departureStoreInclusion')->name('departure_inclusion_store');
	Route::post('/departure/inclusion/update/{id}', 'Departure\FixedDepartureInclusionController@departureUpdateInclusion')->name('departure_inclusion_update');

	//Departure POI Routes
	Route::get('/departure/poi/{id}', 'Departure\FixedDeparturePointOfInterestController@departurePoiCreate')->name('departure_poi_create');
	Route::post('/departure/poi/store/{id}', 'Departure\FixedDeparturePointOfInterestController@departurePoiStore')->name('departure_poi_store');
	Route::post('/departure/poi/update/{id}', 'Departure\FixedDeparturePointOfInterestController@departurePoiUpdate')->name('departure_poi_update');
	Route::post('/departure-poi-disable/{id}', 'Departure\FixedDeparturePointOfInterestController@departurePoiDisable')->name('departure_poi_disable');

	//Itinerary routes
	Route::get('/departure/itinerary/create/{id}', 'Departure\FixedDepartureItineraryController@departureItineraryIndex')->name('departure_itinerary_create');
	Route::post('/departure/itinerary/store/{id}', 'Departure\FixedDepartureItineraryController@departureItineraryStore')->name('departure_itinerary_store');
	Route::post('/departure/itinerary/update/{id}', 'Departure\FixedDepartureItineraryController@departureItineraryUpdate')->name('departure_itinerary_update');
	Route::post('/departure-itinerary-disable/{id}', 'Departure\FixedDepartureItineraryController@departureItinerayDisable')->name('departure_itinerary_disable');

	//Term & Conditions Routes

	Route::get('/departure/terms-conditions/{id}', 'Departure\FixedDepartureController@termConditionIndex')->name('departure_term_conditions');
	Route::post('/departure/terms-conditions/update/{id}', 'Departure\FixedDepartureController@termConditionUpdate')->name('departure_term_conditions_update');

	//Term & Conditions Routes Ends

	//Visa Informations Routes

	Route::get('/departure/visa-informations/{id}', 'Departure\FixedDepartureController@visaInformationIndex')->name('departure_visa_informations');
	Route::post('/departure/visa-informations/update/{id}', 'Departure\FixedDepartureController@visaInformationUpdate')->name('departure_visa_informations_update');

	//Activity Routes
	Route::get('/departure/activity-ajax', 'Departure\FixedDepartureActivityController@getExpActivityAjax');
	Route::get('/departure/activities/{id}', 'Departure\FixedDepartureActivityController@activityIndex')->name('departure_activities');
	Route::post('/departure/activities/store/{id}', 'Departure\FixedDepartureActivityController@activityStore')->name('departure_activities_store');
	Route::post('/departure/activities/update/{id}', 'Departure\FixedDepartureActivityController@activityUpdate')->name('departure_activities_update');

	//Visa Informations Routes Ends
	//Departure Dates Start

	Route::get('/departure_dates/{id}', 'Departure\DepartureDateController@index')->name('departure_dates');
	Route::get('/departure_date/edit/{id}/date_id/{date_id}', 'Departure\DepartureDateController@edit')->name('departure_date_edit');
	Route::post('/departure_dates/update/{id}', 'Departure\DepartureDateController@update')->name('departure_date_update');

	//Departure Dates end routes
	//Departure Date wise inclusion edits
	Route::get('/departure-date-inclusion-edit/{id}/date_id/{date_id}', 'Departure\FixedDepartureInclusionController@departureDateInclusionIndex')->name('departure_date_inclusion');
	Route::post('/departure-date-inclusion-store/{date_id}', 'Departure\FixedDepartureInclusionController@departureDateStoreInclusion')->name('departure_date_inclusion_store');
	Route::post('/departure-date-inclusion-update/{date_id}', 'Departure\FixedDepartureInclusionController@departureDateUpdateInclusion')->name('departure_date_inclusion_update');

// agent departure pull
Route::post('/pull-agent-departure-ajax', 'PullController@getagentDepartureAjax')->name('pull_agentstore');
Route::post('/agent-syncing-departure-ajax', 'PullController@agentsyncDepartureAjax')->name('agent_sync_store');

	//Departure Date wise inclusion edit end

	//Departure Date wise inclusion edits
	Route::get('/departure-date-termofpayment-edit/{id}/date_id/{date_id}', 'Departure\DepartureDateController@departureDateTermsIndex')->name('departure_date_terms');
	Route::post('/departure-date-termofpayment-update/{date_id}', 'Departure\DepartureDateController@departureDateTermsUpdate')->name('departure_date_terms_update');
	//Departure Date wise inclusion edit end
	//Ititberary PDF
	Route::get('/pdf-itinerary/create/{id}', 'Departure\ItineraryPdfController@pdfItinerayCreate')->name('pdf_itinerary');
	Route::post('/pdf-itinerary/store/{id}', 'Departure\ItineraryPdfController@pdfItinerayStore')->name('pdf_itinerary_store');
	Route::post('/pdf-itinerary/update/{id}', 'Departure\ItineraryPdfController@pdfItinerayUpdate')->name('pdf_itinerary_update');
	// Departure Cloud Routes End

	// +++++++++++++++Package Routes Start++++++++++++++++++++++++++++++//

	Route::get('/destination-experience-ajax', 'Departure\PointOfInterestController@experiencesGet');
	Route::get('/destination-pois-ajax', 'Departure\PointOfInterestController@pointofInterestGet');
	Route::get('get-itinerary-destination-pois-ajax', 'Departure\ItineraryController@getDestinationPoiAjax');
	Route::get('/agent-itinerary-ajax', 'Departure\DepartureController@agentItinearayGet');

	//Land Departure Routes Starts
	Route::get('/packages', 'Departure\DepartureController@packagesIndex')->name('packages');
	Route::get('/packages/basic-detail/create', 'Departure\DepartureController@packagesCreate')->name('packages_create');
	Route::get('/packages/basic-detail/edit/{id}', 'Departure\DepartureController@packagesEdit')->name('packages_edit');
	Route::post('/packages/basic-detail/store', 'Departure\DepartureController@packagesStore')->name('packages_store');
	Route::post('/packages/basic-detail/update/{id}', 'Departure\DepartureController@packagesUpdate')->name('packages_update');
	Route::post('/packages-disable/{id}', 'Departure\DepartureController@packagesDisable')->name('packages_disable');
	Route::post('/gccpackages-disable/{id}', 'Departure\DepartureController@gccpackagesDisable')->name('gccpackages_disable');
	Route::post('/make-featured-package/{id}', 'Departure\DepartureController@makePopularPackages')->name('popular_packages');

	Route::post('/popular-home/{id}', 'Departure\DepartureController@packageShowAtHome')->name('popular_home');
	// Land departure inclusion
	Route::get('/packages/inclusion/{id}', 'Departure\InclusionController@packagesInclusionIndex')->name('packages_inclusion');
	Route::post('/lpackages/inclusion/store/{id}', 'Departure\InclusionController@packagesStoreInclusion')->name('packages_inclusion_store');
	Route::post('/packages/inclusion/update/{id}', 'Departure\InclusionController@packagesUpdateInclusion')->name('packages_inclusion_update');
	//POI Routes
	Route::get('/packages/poi/{id}', 'Departure\PointOfInterestController@packagesPoiCreate')->name('packages_poi_create');
	Route::post('/packages/poi/store/{id}', 'Departure\PointOfInterestController@packagesPoiStore')->name('packages_poi_store');
	Route::post('/packages/poi/update/{id}', 'Departure\PointOfInterestController@packagesPoiUpdate')->name('packages_poi_update');
	Route::post('/packages-poi-disable/{id}', 'Departure\PointOfInterestController@packagesPoiDisable')->name('packages_poi_disable');
	Route::post('/packages_poi_delete', 'Departure\PointOfInterestController@packagesPoiDelete')->name('packages_poi_delete');

	Route::post('/add-more-poi', 'Departure\PointOfInterestController@addMorePoiSaveInPull')->name('add_more_pois');
	Route::get('/point-of-interest/edit', 'Departure\PointOfInterestController@poiCreateInOnePlace')->name('point_of_interest_edit');
	Route::post('/point-of-interest/update/{id}', 'Departure\PointOfInterestController@poiUpdateInOnePlace')->name('point_of_interest_update');
	//Itinerary routes
	Route::get('/packages/itinerary/create/{id}', 'Departure\ItineraryController@packagesItineraryIndex')->name('packages_itinerary_create');
	Route::post('/packages/itinerary/store/{id}', 'Departure\ItineraryController@packagesItineraryStore')->name('packages_itinerary_store');
	Route::post('/packages/itinerary/update/{id}', 'Departure\ItineraryController@packagesItineraryUpdate')->name('packages_itinerary_update');
	

	Route::post('/packages-itinerary-disable/{id}', 'Departure\ItineraryController@packagesItinerayDisable')->name('packages_itinerary_disable');

	//Term & Conditions Routes

	Route::get('/packages/terms-conditions/{id}', 'Departure\DepartureController@termConditionIndex')->name('term_conditions');
	Route::post('/packages/terms-conditions/update/{id}', 'Departure\DepartureController@termConditionUpdate')->name('term_conditions_update');

	//Term & Conditions Routes Ends

	//Visa Informations Routes

	Route::get('/packages/visa-informations/{id}', 'Departure\DepartureController@visaInformationIndex')->name('visa_informations');
	Route::post('/packages/visa-informations/update/{id}', 'Departure\DepartureController@visaInformationUpdate')->name('visa_informations_update');

	//Activity Routes

	Route::get('/packages/activities/{id}', 'Departure\ActivityController@activityIndex')->name('packages_activities');
	Route::post('/packages/activities/store/{id}', 'Departure\ActivityController@activityStore')->name('packages_activities_store');
	Route::post('/packages/activities/update/{id}', 'Departure\ActivityController@activityUpdate')->name('packages_activities_update');
	//Activity
	Route::get('/activity-ajax', 'Departure\ActivityController@getExpActivityAjax');
	Route::post('/dep-featured-image-crop', 'Departure\DepartureController@depFeaturedImageCrop')->name('dep_featured_image_crop');
	Route::post('/dep-banner-image-crop', 'Departure\DepartureController@depBannerImageCrop')->name('dep_featured_image_crop');

	//Visa Informations Routes Ends
	// Package Id Unique Check Live Route
	Route::post('/dook-package-id-check', 'Departure\DepartureController@liveValidationUniqueIdCheck')->name('dook_package_id__unique_check');

	Route::get('/package/hotelcategory/{id}', 'Departure\HotelCategoryController@packagesHotelCategoryCreate')->name('hotel_category');
	Route::post('/package/hotelcategory/store/{id}', 'Departure\HotelCategoryController@packagesHotelCategoryStore')->name('hotel_category_store');
	Route::post('/package/hotelcategory/update/{id}', 'Departure\HotelCategoryController@packagesHotelCategoryUpdate')->name('hotel_category_update');
	Route::post('/package/hotelcategory/delete/{id}', 'Departure\HotelCategoryController@packagesHotelCategoryDelete')->name('hotel_category_delete');
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

	//Group Packages Routes Starts
	Route::get('/group-package', 'Departure\GroupDepartureController@groupPackagesIndex')->name('group_packages');
	Route::get('/group-packages/basic-detail/create', 'Departure\GroupDepartureController@groupPackagesCreate')->name('group_packages_create');
	Route::get('/group-package/basic-detail/edit/{id}', 'Departure\GroupDepartureController@groupPackagesEdit')->name('group_packages_edit');
	Route::post('/group-package/basic-detail/store', 'Departure\GroupDepartureController@groupPackagesStore')->name('group_packages_store');
	Route::post('/group-package/basic-detail/update/{id}', 'Departure\GroupDepartureController@groupPackagesUpdate')->name('group_packages_update');
	Route::post('/group-package-disable/{id}', 'Departure\GroupDepartureController@groupPackagesDisable')->name('group_packages_disable');

	Route::post('/make-featured-package/{id}', 'Departure\GroupDepartureController@makePopularPackages')->name('popular_packages');
	Route::post('/add-to-emt-package/{id}', 'Departure\GroupDepartureController@addToEMT')->name('add_emt');
	
	//+++++++++++++Group Package inclusion++++++++++++++++++
	Route::get('/group-package/inclusion/{id}', 'Departure\GroupInclusionController@groupPackagesInclusionIndex')->name('group_packages_inclusion');
	Route::post('/group-package/inclusion/store/{id}', 'Departure\GroupInclusionController@groupPackagesStoreInclusion')->name('group_packages_inclusion_store');
	Route::post('/group-package/inclusion/update/{id}', 'Departure\GroupInclusionController@groupPackagesUpdateInclusion')->name('group_packages_inclusion_update');
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//Group POI Routes
	Route::get('/group-package/poi/{id}', 'Departure\GroupPointOfInterestController@groupPackagesPoiCreate')->name('group_packages_poi_create');
	Route::post('/group-package/poi/store/{id}', 'Departure\GroupPointOfInterestController@groupPackagesPoiStore')->name('group_packages_poi_store');
	Route::post('/group-package/poi/update/{id}', 'Departure\GroupPointOfInterestController@groupPackagesPoiUpdate')->name('group_packages_poi_update');
	Route::post('/group-package-poi-disable/{id}', 'Departure\GroupPointOfInterestController@groupPackagesPoiDisable')->name('group_packages_poi_disable');
	// Route::post('/add-more-poi', 'Departure\PointOfInterestController@addMorePoiSaveInPull')->name('group_add_more_pois');
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//Group Itinerary routes
	Route::get('/group-package/itinerary/create/{id}', 'Departure\GroupItineraryController@groupPackagesItineraryIndex')->name('group_packages_itinerary_create');
	Route::post('/group-package/itinerary/store/{id}', 'Departure\GroupItineraryController@groupPackagesItineraryStore')->name('group_packages_itinerary_store');
	Route::post('/group-package/itinerary/update/{id}', 'Departure\GroupItineraryController@groupPackagesItineraryUpdate')->name('group_packages_itinerary_update');
	Route::post('/group-package-itinerary-disable/{id}', 'Departure\GroupItineraryController@groupPackagesItinerayDisable')->name('group_packages_itinerary_disable');
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//Group Term & Conditions Routes

	Route::get('/group-package/terms-conditions/{id}', 'Departure\GroupDepartureController@groupTermConditionIndex')->name('group_term_conditions');
	Route::post('/group-package/terms-conditions/update/{id}', 'Departure\GroupDepartureController@groupTermConditionUpdate')->name('group_term_conditions_update');

	// Group Term & Conditions Routes Ends
	//Group Departure Dates Start

	Route::get('/group-tours-dates/{id}', 'Departure\DepartureDateController@groupIndex')->name('group_dates');
	Route::post('/group_tours_dates/update/{id}', 'Departure\DepartureDateController@groupUpdate')->name('group_date_update');
	Route::post('/group-tours-date/create/{id}', 'Departure\DepartureDateController@groupCreate')->name('group_date_create');

	//Departure Dates end routes
	//Departure Date wise inclusion edits
	Route::get('/group-tour-date-inclusion-edit/{id}/date_id/{date_id}', 'Departure\GroupInclusionController@groupDateInclusionIndex')->name('group_date_inclusion');
	Route::post('/group-tour-date-inclusion-store/{date_id}', 'Departure\GroupInclusionController@groupDateStoreInclusion')->name('group_date_inclusion_store');
	Route::post('/group-tour-date-inclusion-update/{date_id}', 'Departure\GroupInclusionController@groupDateUpdateInclusion')->name('group_date_inclusion_update');
	//Departure Date wise inclusion edit end
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//Group Visa Informations Routes

	Route::get('/group-package/visa-informations/{id}', 'Departure\GroupDepartureController@groupVisaInformationIndex')->name('group_visa_informations');
	Route::post('/group-package/visa-informations/update/{id}', 'Departure\GroupDepartureController@groupVisaInformationUpdate')->name('group_visa_informations_update');

	//Group Visa Informations Routes Ends
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	// Group Activity Routes

	Route::get('/group-packages/activities/{id}', 'Departure\GroupActivityController@groupActivityIndex')->name('group_packages_activities');
	Route::post('/group-packages/activities/store/{id}', 'Departure\GroupActivityController@groupActivityStore')->name('group_packages_activities_store');
	Route::post('/group-packages/activities/update/{id}', 'Departure\GroupActivityController@groupActivityUpdate')->name('group_packages_activities_update');
	Route::get('/group-activity-ajax', 'Departure\GroupActivityController@getGroupExpActivityAjax');

	// Group Activity Ends
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

	//Destinations Routes
	Route::get('/pull-departure', 'PullController@pullIndex')->name('pull_index');
	Route::post('/pull-departure-ajax', 'PullController@getDepartureAjax')->name('pull_store');	
	Route::post('/syncing-departure-ajax', 'PullController@syncDepartureAjax')->name('sync_store');	
	// Route::get('/pull-departure', 'Departure\DepartureController@pullIndex')->name('pull_index');
	// Route::post('/pull-departure-ajax', 'Departure\DepartureController@getDepartureAjax')->name('pull_store');

	//Aagent Itineray Create 
	Route::get('/itinerary', 'AgentItineraryController@ItinerayIndex')->name('agent_itinerary_index');
	Route::get('/itinerary/create', 'AgentItineraryController@ItinerayCreate')->name('agent_itinerary');
	Route::post('/itinerary/store', 'AgentItineraryController@ItinerayStore')->name('agent_itinerary_store');
	Route::get('/itinerary/edit/{id}', 'AgentItineraryController@ItinerayEdit')->name('agent_itinerary_edit');
	Route::post('/itinerary/update/{id}', 'AgentItineraryController@ItinerayUpdate')->name('agent_itinerary_update');
	Route::post('/itinerary-disable/{id}', 'AgentItineraryController@ItineraryDestroy')->name('agent_iti_disable');

	//Optional tour Routes Starts
	Route::get('/optional-activity', 'OptionalActivityController@index')->name('optional_activity');
	Route::get('/optional-activity/create', 'OptionalActivityController@create')->name('optional_activity_create');
	Route::post('/optional-activity/store', 'OptionalActivityController@store')->name('optional_activity_store');
	Route::get('/optional-activity/edit/{id}', 'OptionalActivityController@edit')->name('optional_activity_edit');
	Route::post('/optional-activity/update/{id}', 'OptionalActivityController@update')->name('optional_activity_update');
	Route::post('/optional-activity/disable/{id}', 'OptionalActivityController@destroy')->name('optional_activity_disable');
	Route::get('/destination-for-optional-ajax', 'OptionalActivityController@destinationForOptional');
	Route::post('/departure-optional-activity/update', 'Departure\DepartureController@departureOptinalActivityUpdate')->name('dep_optional_act_ipdate');
	Route::get('/get-optional-activity-ajax', 'Departure\DepartureController@getOptionalAjax');
	Route::post('/manage-booked-seat/update', 'Departure\DepartureController@manageBookedSeat')->name('manage_booked_seat');

	//Popular Package

	Route::get('/most-popular-packages', 'Departure\PopularToursController@popularPackagesIndex')->name('popular_packages');
	Route::post('/most-papular-package/{id}', 'Departure\PopularToursController@makePopularPackages')->name('most_popular_packages');
	Route::post('/popular-package-disable/{id}', 'Departure\PopularToursController@popularPackagesDisable')->name('popular_packages_disable');

	//++++++++++++++++++++ Activity Experience Routes ++++++++++++++++++++++++++++++

	Route::get('/activities', 'ActivityExpriencesController@ActivityIndex')->name('activity_index');
	Route::post('/activities/update/{id}', 'ActivityExpriencesController@ActivityUpdate')->name('activity_update');
	Route::post('/activities-disable/{id}', 'ActivityExpriencesController@activityDisable')->name('activity_disable');

	Route::get('/experiences', 'ActivityExpriencesController@experienceIndex')->name('experience_index');
	Route::get('/experiences/edit/{id}', 'ActivityExpriencesController@experienceEdit')->name('experience_edit');
	Route::post('/experiences/update/{id}', 'ActivityExpriencesController@experienceUpdate')->name('experience_update');
	Route::post('/experiences-disable/{id}', 'ActivityExpriencesController@experienceDisable')->name('experience_disable');

	Route::post('/experiences-show-at-home/{id}', 'ActivityExpriencesController@experienceShowAtHome')->name('exp_show_at_home');

	Route::post('experience-position-reshifting','ActivityExpriencesController@experiencePositionReshifting')->name('experiencePositionReshiftings');

	//++++++++++++++++++++ Activity Experience Routes End ++++++++++++++++++++++++++++++

	//Destinations Routes
	Route::get('/destinations', 'DestinationController@destinationIndex')->name('destination_index');
	Route::post('/destinations-disable/{id}', 'DestinationController@destinationDisable')->name('destinations_disable');
	Route::get('/destination/edit/{id}', 'DestinationController@destinationEdit')->name('destination_edit');
	Route::post('/destination/update/{id}', 'DestinationController@destinationUpdate')->name('destination_update');
	Route::post('/top-destination/{id}', 'DestinationController@makeTopDestination')->name('top_destinations');
	Route::post('/destination/store-events/{id}', 'DestinationController@addEvents')->name('add_events');
	Route::post('/destination/store-restaurants/{id}', 'DestinationController@addRestaurants')->name('add_restaurants');
	Route::post('/destination/store-hotels/{id}', 'DestinationController@addHotels')->name('add_hotels');
	Route::post('/destination-megamenu/{id}', 'DestinationController@destinationMegaMenu')->name('destination_megamenu');

	//Countries Routes
	Route::get('/countries', 'CountryController@countryIndex')->name('countries_index');
	Route::get('/countries/edit/{id}', 'CountryController@countryEdit')->name('countries_edit');
	Route::post('/countries-update/{id}', 'CountryController@countryUpdate')->name('countries_update');
	Route::post('/countries-disable/{id}', 'CountryController@countryDisable')->name('countries_disable');
	Route::post('/countries-meghamenu/{id}', 'CountryController@countryMeghaMenu')->name('countries_meghamenu');

	//ExpActivity Related Ajax Routes
	Route::get('get-experience-ajax', 'Departure\PointOfInterestController@getExperiences');

	//Top Destination Departure Routes
	Route::get('/top-destinations-departure/create', 'TopDestinationController@topDestinationCreate')->name('top_destinations_create');
	Route::post('/top-destinations-departure', 'TopDestinationController@topDestinationStore')->name('top_destinations_store');
	Route::get('/top-destinations-departure/edit/{id}', 'TopDestinationController@topDestinationEdit')->name('top_destinations_departure_edit');
	Route::post('/top-destinations-departure/update/{id}', 'TopDestinationController@topDestinationUpdate')->name('top_destinations_update');
	Route::get('get-top-destination-ajax', 'TopDestinationController@getTopDestinationAjax');
	Route::get('/get-top-dest-name', 'TopDestinationController@getTopDestNameAjax');
	//Departure in focus
	Route::get('/departure-focus', 'LandingDepartureController@departureInFocusIndex')->name('departure_in_focus');
	Route::get('get-departure-in-focus-ajax', 'LandingDepartureController@getDepartureInFocusAjax');
	Route::post('/departure-focus-delete/{id}', 'LandingDepartureController@departureFocusDelete')->name('departure_focus_delete');
	Route::post('/departure-focus/store', 'LandingDepartureController@departureFocusStore')->name('departure_focus_store');
	//Departure Recommended
	Route::get('/departure-recommended', 'LandingDepartureController@departureRecommendedIndex')->name('departure_in_recommended');
	Route::post('/departure-recommended-delete/{id}', 'LandingDepartureController@departureRecommendedDelete')->name('departure_recommended_delete');
	Route::post('/departure-recommended/store', 'LandingDepartureController@departureRecommendedStore')->name('departure_recommended_store');
	Route::get('get-departure-recommended-ajax', 'LandingDepartureController@getDepartureRecommendedAjax');
	Route::get('get-experiences-departure-ajax', 'LandingDepartureController@getExperienceDepartureRecommendedAjax');


	// landing Pages
	Route::get('landing-pages', 'LandingPageController@landingPages')->name('landing_pages');
	Route::get('landing-page-create', 'LandingPageController@landingPagesCreate')->name('landing_pages_create');
	Route::get('landing-page-edit/{id}', 'LandingPageController@landingPagesEdit')->name('landing_pages_edit');
	Route::post('landing-pages/store', 'LandingPageController@landingPageStore')->name('landing_pages_store');
	Route::post('landing-presentation/update/{id}', 'LandingPageController@landingPageUpdate')->name('landing_page_update');

	//++++++++++Landing Home Page Routes++++++++++//
	Route::get('landing-home', 'LandingHomeController@landingHome')->name('landing_home');
	//Route::post('landing-home/store', 'LandingHomeController@landingHomeStore')->name('landing_home_store');
	Route::post('landing-home/update/{id}', 'LandingHomeController@landingHomeUpdate')->name('landing_home_update');
	Route::get('landing-home-sections-grid', 'LandingHomeController@landingHomeGridImages')->name('landing_home_grid_images');
	Route::post('landing-home-sections-grid/store', 'LandingHomeController@landingHomeGridImagesStore')->name('landing_home_grid_images_store');
	Route::post('landing-home-sections-grid/update/{id}', 'LandingHomeController@landingHomeGridImagesUpdate')->name('landing_home_grid_images_update');

	Route::get('landing-home/slider/create', 'LandingHomeController@landingHomeSlider')->name('landing_home_slider');
	Route::get('/get-country-departure-ajax', 'LandingHomeController@countryDepartureAjax');
	Route::post('/departure-slider/store', 'LandingHomeController@departureSliderStore')->name('departure_slider_store');
	Route::post('/departure-slider/update', 'LandingHomeController@departureSliderUpdate')->name('departure_slider_update');
	
	//Top Destination Routes
	Route::get('/top-destinations/create', 'TopDestinationController@topDestinationDestinationCreate')->name('top_destinations_destinations_create');
	Route::post('/top-destinations/store', 'TopDestinationController@topDestinationDestinationStore')->name('top_destinations_destinations_store');
	Route::get('/top-destinations/edit/{id}', 'TopDestinationController@topDestinationDestinationEdit')->name('top_destinations_destinations_edit');
	Route::post('/top-destinations/update/{id}', 'TopDestinationController@topDestinationDestinationUpdate')->name('top_destinations_destinations_update');

	//About Us Routes
	Route::get('about-us', 'AboutController@index')->name('about_index');
	Route::post('/about-update', 'AboutController@aboutUpdate')->name('about_update');
	
	//Contact Us Routes
	Route::get('contact-us', 'LandingPageController@lanndingContactIndex')->name('contact_index');
	Route::post('/contact-store', 'LandingPageController@lanndingContactStore')->name('contact_store');
	Route::post('/contactus-update/{id}', 'LandingPageController@lanndingContactUpdate')->name('contactus_update');

	//Regions Routes
	Route::get('/regions', 'RegionController@regionIndex')->name('region_index');
	Route::get('/region/edit/{id}', 'RegionController@regionEdit')->name('region_edit');
	Route::post('/region/update/{id}', 'RegionController@regionUpdate')->name('region_update');
	Route::get('get-region_countries-ajax', 'RegionController@getTopCountriesAjax');
	Route::post('/region-megamenu/{id}', 'RegionController@regionMegaMenu')->name('region_megamenu');
	//Banner Routes
	Route::get('/banners', 'BannerController@index')->name('banner_index');
	Route::get('/banners/create', 'BannerController@create')->name('banner_create');
	Route::post('/banners/store', 'BannerController@store')->name('banner_store');
	Route::post('/banners/update/{id}', 'BannerController@update')->name('banner_update');

	/////++++++++++++FOOTER ROUTES++++++++++////////
	// footer address

	Route::get('/footer-create', 'FooterController@footerCreate')->name('footer_create');
	Route::post('/footer-store', 'FooterController@footerStore')->name('footer_store');
	Route::post('/footer/update/{id}', 'FooterController@footerUpdate')->name('footer_update');
	// footer address

	Route::get('/footer/popular-destination', 'FooterController@footerPopularDestination')->name('popular_destination');
	Route::post('/footer/popular-destination/store', 'FooterController@footerPopularDestinationStore')->name('popular_destination_store');
	Route::post('/footer-destination/disable/{id}', 'FooterController@footerPopularDestinationDelete')->name('popular_destination_disable'); 
	Route::get('get-top-footer-destination-ajax', 'FooterController@getTopFooterDestinationAjax');
	Route::get('get-top-footer-countries-ajax', 'FooterController@getTopFooterCountriesAjax');
	Route::post('/footer/popular-countries/store', 'FooterController@footerPopularCountryStore')->name('popular_country_store');
	Route::post('/footer-county/disable/{id}', 'FooterController@footerPopularCountryDelete')->name('popular_country_disable'); 
	Route::get('get-search-destination-ajax', 'FooterController@getSearchBarDestinationAjax');

	Route::get('searching-destinations', 'FooterController@defaultSearchDestination')->name('searching_destinations');
	Route::post('/searching-destinations-store', 'FooterController@defaultSearchDestinationStore')->name('searching_destinations_store'); 
	Route::post('/searching-destination/delete/{id}', 'FooterController@searchingDestinationDelete')->name('searching_destination_disable'); 
	Route::post('search-destination-position-reshifting','FooterController@positionShifting')->name('searchPositionReshifting');
	Route::post('footer-destination-position-reshifting','FooterController@footerDestPositionReshifting')->name('footerDestPositionReshifting');
	Route::post('footer-country-position-reshifting','FooterController@footerCountPositionReshifting')->name('footerCountryPositionReshifting');
	/////++++++++++++User Routes++++++++++////////

	Route::get('/users', 'UserController@index')->name('user_index');
	Route::post('/users/store', 'UserController@store')->name('user_store');
	Route::post('/users/delete/{id}', 'UserController@destroy')->name('user_delete');
	Route::post('/users/update/{id}', 'UserController@update')->name('user_update');
	// Route::post('/footer/store', 'FooterController@footerStore')->name('footer_store');
	// Route::post('/footer/update/{id}', 'FooterController@footerUpdate')->name('footer_update');
	//Existing poi
	Route::get('/existingpoi', 'ExistingPoiController@index')->name('existingpoi_index');
	Route::get('/existingpoi/create', 'ExistingPoiController@create')->name('existingpoi_create');
	Route::post('existingpoi-store', 'ExistingPoiController@store')->name('existingpoi_store');
	Route::post('existingpoi/update/{id}', 'ExistingPoiController@update')->name('existingpoi_update');
	Route::post('existingpoi/delete/{id}', 'ExistingPoiController@existingPoiDisable')->name('delete_existing_poi');
	
	//Reviews

	Route::get('/reviews', 'PresentationReviewController@reviewList')->name('reviews');
	Route::post('/review-disable/{id}', 'PresentationReviewController@reviewDisable')->name('reviews_disable');
	Route::post('review-delete/{id}', 'PresentationReviewController@reviewDelete')->name('review_delete');

	//Job Careers Routes
	Route::get('/manage_job', 'CareerController@index')->name('job_manage');
	Route::post('/manage_job_store', 'CareerController@store')->name('job_store');
	Route::post('manage_job_update/{id}', 'CareerController@update')->name('job_update');
	Route::post('job_delete/{id}', 'CareerController@jobDelete')->name('job_delete');
	Route::post('job_status_change/{id}', 'CareerController@statusChange')->name('job_status_change');

	//Package Image Compress Routes
	Route::get('/image_compress', 'ImageCompressController@packageImageCompress')->name('image_compress');
	Route::post('/four_image_compress_update', 'ImageCompressController@packageFourImageCompressUpdate')->name('four_image_compress_update');

	Route::post('/featured_image_compress_update', 'ImageCompressController@packageImageCompressUpdate')->name('featured_image_compress_update');
	
	Route::post('/banner_image_compress_update', 'ImageCompressController@packageBannerImageCompressUpdate')->name('banner_image_compress_update');
	// Route::get('/enquiry', function () {
	// 	return view('enquiry.fixed_departure_queries');
	// })->name('enquiry');
Route::get('/enquiry', 'EnquiryController@generateReport')->name('enquiry');
Route::get('/enquiry-report', 'EnquiryController@generateReport')->name('enquiry.report');
// monthly report
	Route::get('/report', 'EnquiryController@monthReport')->name('report');
	// Route::post('/monthly-report', 'EnquiryController@monthReport')->name('monthly.report');
	//Enquiries
	Route::get('/enquiries', 'EnquiryController@getEnquiries')->name('enquiries');
	Route::get('/enquiries_filter', 'EnquiryController@filterEnquiries')->name('filter.enquery');
	Route::post('/enquiry_delete/{id}', 'EnquiryController@deleteEnquiries')->name('delete_enquiry');
	Route::get('/job_enquiries', 'EnquiryController@getJobs')->name('job_enquiries');
	Route::get('/wat_job_enquiries', 'EnquiryController@watResume')->name('wat_job_enquiries');
	Route::get('/daily_reports', 'EnquiryController@dailyReport')->name('daily_reports');
	Route::get('/email-count', 'EnquiryController@emailCount')->name('eamil.count');
	Route::get('/country-count', 'EnquiryController@countryCount')->name('country.count');
	Route::get('/lead-count', 'EnquiryController@leadCount')->name('lead.count');

	//passenger report
	Route::get('/booking-detail', 'EnquiryController@bookingdetail')->name('booking-detail'); 

	// signature
	Route::get('/upload-image-signature', 'SignatureController@createSignature')->name('create_signature');
	Route::post('/upload-image-signature', 'SignatureController@uploadSignature')->name('upload_signature');

	//signature Routes
  Route::get('/signature_upload', 'SignatureController@index')->name('signature_upload');
	Route::post('/signature_store', 'SignatureController@store')->name('signature_store');
	Route::post('signature_update', 'SignatureController@update')->name('signature_update');
	Route::post('signature_delete/{id}', 'SignatureController@Delete')->name('signature_delete');
	
	// Dollor RS Update
	Route::post('/rupee_dollar/{id}', 'Departure\HotelCategoryController@rupeeDollorUpdate')->name('ruppe_dollor_update');

	Route::post('/copy-package/{id}', 'PackageCopyController@copyPackages')->name('copy_package');
	Route::post('/copy-group-tour/{id}', 'GroupTourCopyController@copyTour')->name('copy_tour');
	// Create pdf for packages
	Route::get('pdf-pages/create/{id}','PdfController@pdfCreate')->name('pdf-pages.create');
	Route::post('pdf-pages/store/{id}','PdfController@storeModule')->name('store_module');
	Route::get('pdf-pages/{id}','PdfController@pdfPages')->name('pdf-pages.index');
	//Route::post('banner-update','PdfController@bannerUpdate')->name('banner-update');
	Route::post('basic-detail-update','PdfController@basicPageUpdate')->name('basic-detail-update');
	//Route::post('contact-update','PdfController@contactUpdate')->name('contact-update');
	Route::post('terms-update','PdfController@termsUpdate')->name('terms-update');
	Route::post('inc-exc-update','PdfController@incExcUpdate')->name('inc-exc-update');
	Route::post('flight-update','PdfController@flightUpdate')->name('flight-update');
	Route::post('hotel-update','PdfController@hotelUpdate')->name('hotel-update');
	Route::post('itinerary-update','PdfController@itineraryUpdate')->name('itinerary-update');
	Route::post('add-page','PdfController@addPage')->name('add-page');

	Route::post('generate_pdf','PdfController@generatePdf')->name('generate_pdf');
	Route::get('call_pdf','PdfController@callPdf')->name('call_pdf');
	Route::get('check_pdf_status','PdfController@checkStatusPdf')->name('check_pdf_status');

	//Tags
	Route::get('/create-tag', 'TagController@index')->name('tag_index');
	Route::post('/store-tag', 'TagController@store')->name('tag_store');
	Route::post('/update-tag/{id}', 'TagController@update')->name('tag_update');
	Route::post('/delete-tag/{id}', 'TagController@delete');

	Route::get('/get-ajax-tag', 'TagController@getTagAjax')->name('ajax_tag');
	//Publish Itinerary Route

	Route::post('/publish-itinearary/{id}', 'PublishItineraryToFinderController@publishItinerary');
	Route::post('/publish-itinearary-appolyte/{id}', 'PublishItineraryToAppolyteController@publishItineraryToAppolyte');

	//Publish Itinerary Route End
	//Visa popular routs
	Route::get('/visa/popular-destination', 'DestinationController@visaPopularDestination')->name('visa_destination');
	Route::post('/visa-popular-destination/store', 'DestinationController@visaPopularDestinationStore')->name('visa_destination_store');
	Route::post('/visa-destination/disable/{id}', 'DestinationController@visaPopularDestinationDelete')->name('visa_destination_disable'); 
	//Mega Menu Route
	Route::get('/megamenu-destination', 'MegaMenuController@megaMenuDestination')->name('mega_destination');
	Route::post('/megamenu-destination/store', 'MegaMenuController@megaMenuDestinationStore')->name('mega_destination_store');
	Route::post('/megamenu-destination/disable/{id}', 'MegaMenuController@megaMenuDestinationDelete')->name('mega_destination_disable'); 
	Route::post('position-reshifting','MegaMenuController@positionShifting')->name('positionReshifting');

	//Mega Menu Route
	Route::get('/megamenu-country', 'MegaMenuController@megaMenuCountry')->name('mega_country');
	Route::post('/megamenu-country/store', 'MegaMenuController@megaMenuCountryStore')->name('mega_country_store');
	Route::post('/megamenu-country/disable/{id}', 'MegaMenuController@megaMenuCountryDelete')->name('mega_country_disable'); 

	Route::post('position-reshifting-country','MegaMenuController@positionShiftingCountry')->name('positionReshiftingCountry');

	Route::get('/make-urls', 'AboutController@departureUrlMake');
	// Route::get('/make-country', 'AboutController@countryUrlMake');
	// Route::get('/make-dest', 'AboutController@destUrlMake');
	// Route::get('/make-exp', 'AboutController@expUrlMake');
	// Route::get('/make-act', 'AboutController@actUrlMake');
	// Route::get('/make-region', 'AboutController@regionUrlMake');

	Route::get('/ajayraj-make-poiurl', 'AboutController@makePoiUrls');

	//Departure Pricing
	Route::get('/departures-pricing/{id}/{d_id}', 'Departure\DeparturePriceController@index')->name('departure_pricing');
	Route::post('/departures-pricing/update', 'Departure\DeparturePriceController@update')->name('departure_pricing_update');

	//Mailer Send
	Route::get('/mailers','MailerController@indexMailer')->name('index_mailer');
	Route::post('/mailer-store','MailerController@htmlUpload')->name('mailer_store');
	Route::post('/mailer-delete/{id}','MailerController@mailerDelete')->name('mailer_delete');

	Route::post('/sync-tfc-lead','EnquiryController@syncLeadWithTFC')->name('synce_lead_tfc');

	Route::post('/sync-tfc-lead-refID','EnquiryController@syncLeadWithTFCRefId')->name('synce_lead_tfc_refid');
	Route::get('/abcde','EnquiryController@abcd');

	// DOM Pdf Routes
	Route::post('banner-update','PdfController@bannerUpdate')->name('banner-update');
	Route::post('contact-update','PdfController@contactUpdate')->name('contact-update');
	Route::get('create-pdf/{id}','PdfController@domPdfPages')->name('pdf_create');
	Route::get('/generate-pdf-dom/{id}', 'PdfController@generatePDFDom')->name('pdf_dom');

	//group tours

	Route::get('departure/create-pdf/{id}','PdfController@domPdfPages')->name('departure_pdf_create');

		// pullit visa
	Route::resource('visa','VisaController');
	Route::get('visa/{visa}', [VisaController::class, 'show'])->name('visa.show');
	Route::get('visaupdateurl','VisaController@updateUrlForDook');
	Route::get('changeStatusv', 'VisaController@changeStatus');
	Route::get('get-document-ajax', 'VisaController@getDocumentAjax');

	//Hotel
	Route::get('/hotel-create','Departure\HotelController@hotelIndex')->name('hotel-create');
	Route::post('/hotel-store','Departure\HotelController@hotelStore')->name('hotel-store');
	Route::get('/hotel/dest/country','Departure\HotelController@getDestCountry')->name('hotel-dest-country');
	Route::get('/hotel-details','Departure\HotelController@getHotelDetails')->name('hotel-details');
	Route::get('/destination-details/{id}','Departure\HotelController@getDestinationDetails')->name('destination-details');

// countrywisepackages
	Route::resource('country-wise-packages', CountryWisePackageController::class);

});

// This will be exempted from CSRF in VerifyCsrfToken middleware
Route::post('/api/meta/webhook', [MetaLeadController::class, 'receiveLead'])
    ->name('meta.webhook');

// GET endpoint for Facebook webhook verification
Route::get('/api/meta/webhook', [MetaLeadController::class, 'receiveLead'])
    ->name('meta.webhook.verify');

// Sync and management endpoints
Route::get('/manual-sync', [MetaLeadController::class, 'manualSync'])
    ->name('meta.manual.sync');

Route::get('/full-sync', [MetaLeadController::class, 'fullSync'])
    ->name('meta.full.sync');

Route::get('/sync-status', [MetaLeadController::class, 'getSyncStatus'])
    ->name('meta.sync.status');

// Setup and testing endpoints
Route::get('/test-webhook-setup', [MetaLeadController::class, 'testWebhookSetup'])
    ->name('meta.test.webhook');

Route::get('/test-connection', [MetaLeadController::class, 'testConnection'])
    ->name('meta.test.connection');

Route::get('/test-sample-lead', [MetaLeadController::class, 'testSampleLead'])
    ->name('meta.test.sample');

// Get Page Access Token from User Token
Route::get('/get-page-token', [MetaLeadController::class, 'getPageToken'])
    ->name('meta.get.page.token');

// Debug: Check form names
Route::get('/debug-form-names', [MetaLeadController::class, 'debugFormNames'])
    ->name('meta.debug.form.names');

// Add this route to test your connection
// Route::get('/api/crm/test-connection', [MetaLeadController::class, 'testConnection']);

// Route::get('/leads', function () { return redirect('/crm/dashboard'); });
// Route::get('/crm/dashboard', [MetaLeadController::class, 'dashboard']);
// Route::get('/crm/leads', [MetaLeadController::class, 'leadsPage']);
 
// Route::match(['GET', 'POST'], '/api/meta-leads', [MetaLeadController::class, 'receiveLead']);
// Route::post('/api/crm/import-leads-direct', [MetaLeadController::class, 'importAllLeadsApi']);

// // Add this route for the new limited import function
// Route::post('/api/crm/import-recent-leads', [MetaLeadController::class, 'importRecentLeads']);

// // Keep your existing route for background processing
// Route::post('/api/crm/import-all-leads', [MetaLeadController::class, 'importAllExistingLeads']);


// // Add these routes for campaign name handling
// Route::post('/api/crm/update-campaign-names', [MetaLeadController::class, 'updateLeadCampaignNames']);
// Route::get('/api/crm/test-campaign/{campaignId?}', [MetaLeadController::class, 'testCampaignName']);

// Route::prefix('api/crm')->group(function () {
//     Route::get('/dashboard-stats', [MetaLeadController::class, 'getDashboardStats']);
//     Route::get('/leads', [MetaLeadController::class, 'getLeads']);
//     Route::put('/leads/{id}/status', [MetaLeadController::class, 'updateLeadStatus']);
//     Route::post('/import-all-leads', [MetaLeadController::class, 'importAllExistingLeads']);
// });