<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Departure;
use App\Destination;
use App\Country;
use DB;
use App\DefaultSearchDestination;
use App\FooterDestination;
use App\FooterCountry;
use App\FooterSettings;

class FooterController extends Controller
{
    public function footerPopularDestination(Request $request)
    {
    	$popular_destination = FooterDestination::join('destinations','destinations.id','=','footer_destinations.destination_id')->select('footer_destinations.id','destinations.dest_name','footer_destinations.orders')->orderBy('footer_destinations.orders','ASC')->get();
        $total = count($popular_destination);

		$popular_country = FooterCountry::join('countries','countries.id','=','footer_countries.country_id')->orderBy('footer_countries.orders','ASC')->select('footer_countries.id','countries.country_name','footer_countries.orders')->get();
		$total_country = count($popular_country);
    	return view('footer.popular_destination', compact('popular_destination','popular_country','total','total_country'));
    }

    public function footerPopularDestinationStore(Request $request)
    {
    	$data = $request->all();
    	if($request->destination){
        	foreach($request->destination as $key => $value) {
    			$popular_destination = new FooterDestination;
                $popular_destination->destination_id = $value;
                $popular_destination->orders = $key+1;
                $popular_destination->save();
            }
        }
        return response()->json(['success'=>'Success!']);
    }

    public function footerPopularDestinationDelete(Request $request, $id)
    { 
        $destination = FooterDestination::where('id',$id)->delete();
        return response()->json(['success'=>'Success!']);
    }

    public function footerPopularCountryStore(Request $request)
    {
        $data = $request->all();
        if($request->country){
            foreach($request->country as $key => $value) {
                $popular_country = new FooterCountry;
                $popular_country->country_id = $value;
                $popular_country->orders = $key+1;
                $popular_country->save();
            }
        }
    }

    public function footerPopularCountryDelete(Request $request, $id)
    {
		$destination = FooterCountry::where('id',$id)->delete();
        return response()->json(['success'=>'Success!']);
    }

    public function getTopFooterCountriesAjax(Request $request)
    {
        $data = [];
        if($request->has('q')){
            $search = $request->q;
            $data = Country::select("id","country_name")
                    ->where('country_name','LIKE',"%$search%")
                    ->get(20);
        }
        else{
            $data = Country::select("id","country_name")
                    ->limit(15)
                    ->get();
        }
        return response()->json($data);
    }

    public function getTopFooterDestinationAjax(Request $request)
    {
        $data = [];
        if($request->has('q')){
            $search = $request->q;
            $data = Destination::select("id","dest_name")
                        ->where('dest_name','LIKE',"%$search%")
                        ->get(20);
        }
        else{
            $data = Destination::select("id","dest_name")
                        ->limit(15)
                        ->get();
        }
        return response()->json($data);
    }
    /////////Searching destinations default///////

    public function defaultSearchDestination(Request $request)
    {
        $search_bar_destination = DefaultSearchDestination::select('id','name','orders')
            ->orderBy('orders','ASC')
            ->get();
        $total = count($search_bar_destination);
        return view('searchDestination.search_bar_destination',compact('search_bar_destination','total'));
    }

    public function defaultSearchDestinationStore(Request $request)
    {
        if($request->destination){
            foreach ($request->destination as $key => $value) {
                $defaultDest = new DefaultSearchDestination;
                $defaultDest->name = $value;
                $defaultDest->save();
                $defaultDestUp = DefaultSearchDestination::find($defaultDest->id);
                $defaultDestUp->orders = $defaultDest->id;
                $defaultDestUp->save();
            }
        }
        return response()->json(['success'=>'Success!']);
    }

    public function searchingDestinationDelete(Request $request, $id)
    {        
        DefaultSearchDestination::where('id',$id)->delete();   
        return response()->json(['success'=>'Successfully deleted!']);
    }
    public function getSearchBarDestinationAjax(Request $request)
    {
        $data = [];
        if($request->has('q')){
            $search = $request->q;
            $dest = Destination::select("dest_name")
                    ->where('dest_name','LIKE',"$search%")
                    ->get(15);
            $country = Country::select("country_name as dest_name")
                    ->where('country_name','LIKE',"$search%")
                    ->get(5);
            $countinent = Country::select("continent as dest_name")
                    ->where('continent','LIKE',"$search%")
                    ->groupBy('continent')
                    ->get(5);
            if(count($dest)>0){
                $data = $dest;
            }elseif(count($country)>0){
                $data = $country;
            }else{
                $data = $countinent;
            }
        }
        else{
            $data = Destination::select("dest_name")
                        ->limit(10)
                        ->get();
        }
        return response()->json($data);
    }
    public function positionShifting(Request $request){

        if($request->position)
        {
            $pos = $request->position;
            $i = 0;
            foreach($pos as $k=>$v)
            {
                $i++;
                $item = DefaultSearchDestination::find($v);
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

    public function footerDestPositionReshifting(Request $request){

        if($request->position)
        {
            $pos = $request->position;
            $i = 0;
            foreach($pos as $k=>$v)
            {
                $i++;
                $item = FooterDestination::find($v);
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
    public function footerCountPositionReshifting(Request $request){

        if($request->position)
        {
            $pos = $request->position;
            $i = 0;
            foreach($pos as $k=>$v)
            {
                $i++;
                $item = FooterCountry::find($v);
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

    public function footerCreate(Request $request)
    {
        $footer = FooterSettings::first();
        if($footer){
            return view('footer.footer_edit', compact('footer'));
        }else{
            return view('footer.footer_create');
        }
    }

    function footerStore(Request $request){
        $data = $request->all();
        $user = auth()->user();
        $footer = new FooterSettings;
        $footer->opening_day = $request->opening_day;
        $footer->opening_timing = $request->opening_timing;
        $footer->phone = $request->phone;
        $footer->mobile = $request->mobile;
        $footer->email = $request->email;
        $footer->header_phone = $request->header_phone;
        $footer->header_email = $request->header_email;
        $footer->address = $request->address;
        $footer->about = $request->description;
        $footer->user_id = $user->id;
        $footer->save();
        $status = [
                'url'=> url('/footer-create'),
            ];
        return response()->json($status);
    }
    function footerUpdate(Request $request, $id){
        $data = $request->all();
        $user = auth()->user();
        $footer = FooterSettings::find($id);
        $footer->opening_day = $request->opening_day;
        $footer->opening_timing = $request->opening_timing;
        $footer->phone = $request->phone;
        $footer->mobile = $request->mobile;
        $footer->email = $request->email;
        $footer->header_phone = $request->header_phone;
        $footer->header_email = $request->header_email;
        $footer->address = $request->address;
        $footer->about = $request->description;
        $footer->user_id = $user->id;
        $footer->save();
        $status = [
                'url'=> url('/footer-create'),
            ];
        return response()->json($status);
    }
}
