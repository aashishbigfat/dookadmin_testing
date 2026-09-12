<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Redirect;
use App\VisaDocument;
use App\Visa;
//use App\Airport;
use App\Country;
use App\Document; 
use Illuminate\Support\Str;
use App\Destination;

class VisaController extends Controller
{
     public function index(Request $request){ 
         $visas =Visa::get();
         $keywords = $request->input('search');
         $fromcountry_id= $request->input('from_country_id');
         $tocountry_id= $request->input('to_country_id');
         if($fromcountry_id != null &&  $tocountry_id != null){
            $paginate_data=Visa::where('residence_iso3',$fromcountry_id)->where('visiting_iso3',$tocountry_id)->orderBy('id','DESC')->paginate(25);
         }
         elseif($fromcountry_id != null){
            $paginate_data=Visa::where('residence_iso3',$fromcountry_id)->orderBy('id','DESC')->paginate(25);
         }
         elseif($tocountry_id != null){
            $paginate_data=Visa::where('visiting_iso3',$tocountry_id)->orderBy('id','DESC')->paginate(25);
         }
         else{
         $paginate_data=Visa::orderBy('id','DESC')->paginate(25);
         }
         $countries = Country::orderBy('country_name','ASC')->get();
         
        $total = count($visas);
        $existingQuery = $this->getExistingQueryParams();
        return view('visa.index',[
            'visas'=> $paginate_data,
            'total' => $total,
            'existingQuery' => $existingQuery,
            'countries'=>$countries,
            'from_country_id'=>$fromcountry_id,
            'to_country_id'=>$tocountry_id,
            'keywords'=>$keywords,
            ]);
    }

    public function create()
    {
        $countries = Country::get();
        $documents = Document::get();
        $req_visa = DB::table('visa_requires')->select('id','name')->get();
        $arriv_visa = DB::table('visa_arrivals')->select('id','name')->orderBy('id','DESC')->get();
        $visa_categories = DB::table('visa_categories')->select('id','name')->get();
        $visa_types = DB::table('visa_types')->select('id','name')->get();
        return view('visa.create',[
            'countries'=> $countries,
            'documents'=> $documents,
            'req_visa'=> $req_visa,
            'arriv_visa'=> $arriv_visa,
            'visa_categories'=> $visa_categories,
            'visa_types'=> $visa_types,
            ]);
     }
    public function store(Request $request)
    {
        $data = $request->all(); 
         // $countryjson=json_decode($request->from_country);
        $residence_iso = Country::where('country_name',$request->country_of_residence)
                        ->select('iso_2', 'iso_3')
                        ->first();
        $visiting_iso = Country::where('country_name',$request->visiting_country)
                        ->select('iso_2', 'iso_3')
                        ->first();
        $issuingData = DB::table('countries')
            ->where('country_name',$request->passport_holder_country)
            ->select('country_name','iso_2')
            ->first();
        $residenceData = DB::table('countries')
                    ->where('country_name',$request->country_of_residence)
                    ->select('country_name','iso_2')
                    ->first();
        $visitingData = DB::table('countries')
                    ->where('country_name',$request->visiting_country)
                    ->select('country_name','iso_2')
                    ->first();

        $arrH = explode(' ',$issuingData->country_name);
        $strH = implode("-", $arrH);
        $aa = str_replace(',', '', $strH);
        $urlH = strtolower($aa);

        $arrV = explode(' ',$visitingData->country_name);
        $strV = implode("-", $arrV);
        $bb = str_replace(',', '', $strV);
        $urlV = strtolower($bb);
        $visaUrl = $urlH.'-to-'.$urlV.'-visa';

        $sd = $residenceData->country_name.'@'.$visitingData->country_name;

        $visa  = new Visa;
        $visa->passport_holder_country = $request->passport_holder_country;
        $visa->country_of_residence = $request->country_of_residence;

        $visa->residence_iso2 = $residence_iso->iso_2;
        $visa->residence_iso3 = $residence_iso->iso_3;
        $visa->visiting_country = $request->visiting_country;
        $visa->visiting_iso2 = $visiting_iso->iso_2;
        $visa->visiting_iso3 = $visiting_iso->iso_3;
        $visa->visa_category = $request->visa_category;
        $visa->required_documents = $request->required_documents; 
        $visa->eligibility_criteria = $request->eligibility_criteria; 
        $visa->exemptions = $request->exemptions; 
        $visa->general_information = $request->general_information; 
        $visa->visa_application_process = $request->visa_application_process; 
        $visa->additional_info = $request->additional_info;
        //$visa->urls=  json_encode($request->urls);
        $visa->visa_required = $request->visa_required;
        if($request->visa_required == "Yes"){
            $visa->visa_arrival = $request->visa_arrival;
            $visa->visa_type = $request->visa_type;
            $visa->processing_time = $request->processing_time;
            $visa->stay_period = $request->stay_period;            
            $visa->validity = $request->validity; 
            $visa->fees = $request->fees; 
        }
        $visa->status = 1;
        
        $visa->meta_title = $request->meta_title;
        $visa->meta_keywords = $request->meta_keywords;
        $visa->meta_description = $request->meta_description;
        $user = auth()->user();
        $visa->created_by = $user->id; 
        $visa->ph_country_url = $urlH;
        $visa->v_country_url = $urlV;
        $visa->slug_url = $visaUrl;
        $visa->dook_visa_urls = "https://www.dookinternational.com/".$visaUrl;
        $visa->save();
dd($visa);
        $last_id = $visa->id;
     
        // $visadocs=$request->document;
        // if($visadocs){                
        //     foreach ($visadocs as $value) {

        //         $visa_docs  = new VisaDocument;
        //         $visa_docs->visa_id=$last_id;
        //         $visa_docs->document_id=$value;
        //         $visa_docs->save();
        //     }   
        // }
        $request->session()->flash('status', 'Visa created successfully.');
        return Redirect::to('visa');      
    }
    public function edit($id)
    {
        $visa = Visa::where('id', $id)->first();
        $urls= json_decode($visa->urls);
        
        $req_visa = DB::table('visa_requires')->select('id','name')->get();
        $arriv_visa = DB::table('visa_arrivals')->select('id','name')->orderBy('id','DESC')->get();
        $visa_types = DB::table('visa_types')->select('id','name')->get();
        $visa_categories = DB::table('visa_categories')->select('id','name')->get();

        $countries = Country::get();
        return view('visa.edit',[
            'visa' => $visa,
            'urls' => $urls,
            'countries'=>$countries,
            'req_visa'=> $req_visa,
            'arriv_visa'=> $arriv_visa,
            'visa_categories'=> $visa_categories,
            'visa_types'=> $visa_types,
        ]);
    }
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $residence_iso = Country::where('country_name',$request->country_of_residence)
                    ->select('iso_2', 'iso_3')
                    ->first();
        $visiting_iso = Country::where('country_name',$request->visiting_country)
                    ->select('iso_2', 'iso_3')
                    ->first();
        $issuingData = DB::table('countries')
            ->where('country_name',$request->passport_holder_country)
            ->select('country_name','iso_2')
            ->first();
            //dd($request->passport_holder_country);
        $residenceData = DB::table('countries')
                    ->where('country_name',$request->country_of_residence)
                    ->select('country_name','iso_2')
                    ->first();
        $visitingData = DB::table('countries')
                    ->where('country_name',$request->visiting_country)
                    ->select('country_name','iso_2')
                    ->first();

        $arrH = explode(' ',$issuingData->country_name);
        $strH = implode("-", $arrH);
        $aa = str_replace(',', '', $strH);
        $urlH = strtolower($aa);

        $arrV = explode(' ',$visitingData->country_name);
        $strV = implode("-", $arrV);
        $bb = str_replace(',', '', $strV);
        $urlV = strtolower($bb);
        $visaUrl = $urlH.'-to-'.$urlV.'-visa';

        $sd = $residenceData->country_name.'@'.$visitingData->country_name;
         $visa = Visa::find($id);
         $visa->passport_holder_country = $request->passport_holder_country;
         $visa->country_of_residence = $request->country_of_residence;
         $visa->residence_iso2 = $residence_iso->iso_2;
         $visa->residence_iso3 = $residence_iso->iso_3;
         $visa->visa_type = $request->visa_type;
         $visa->visiting_country = $request->visiting_country;
         $visa->visiting_iso2 = $visiting_iso->iso_2;
         $visa->visiting_iso3 = $visiting_iso->iso_3;
         $visa->visa_category = $request->visa_category;
         $visa->visa_application_process = $request->visa_application_process;
         $visa->required_documents = $request->required_documents; 
         $visa->eligibility_criteria = $request->eligibility_criteria; 
         $visa->exemptions = $request->exemptions; 
         $visa->general_information = $request->general_information; 
         $visa->additional_info = $request->additional_info;
         //$visa->urls=  json_encode($request->urls);
         $visa->visa_required = $request->visa_required;
         if($request->visa_required == "Yes"){
            $visa->visa_arrival = $request->visa_arrival;
            $visa->visa_type = $request->visa_type;
            $visa->processing_time = $request->processing_time;
            $visa->stay_period = $request->stay_period;            
            $visa->validity = $request->validity; 
            $visa->fees = $request->fees; 
         }else{
            $visa->visa_arrival = "";
            $visa->visa_type = "";
            $visa->processing_time = "";
            $visa->stay_period = "";    
            $visa->validity = "";
            $visa->fees = "";
         }
         $visa->meta_title = $request->meta_title;
         $visa->meta_keywords = $request->meta_keywords;
         $visa->meta_description = $request->meta_description;
         $user = auth()->user();
         $visa->created_by = $user->id; 
         $visa->ph_country_url = $urlH;
         $visa->v_country_url = $urlV;
         $visa->slug_url = $visaUrl;
         $visa->dook_visa_urls = "https://www.dookinternational.com/".$visaUrl;
         $visa->save();



            // $visa->ducuments()->sync($request->documentes);
            // $last_id = $visa->id;
            // $visadocs=$request->document;
            // if($visadocs){                
            //     foreach ($visadocs as $value) {

            //         $visa_docs  = new VisaDocument;
            //         $visa_docs->visa_id=$last_id;
            //         $visa_docs->document_id=$value;
            //         $visa_docs->save();
            //     }   
            // }

        $request->session()->flash('status', 'Airport updated successfully.');
        return Redirect::to('visa');
    
    }

    
  public function show($id)
    {
       
        $visa = Visa::find($id);
        //$document =Document::get();
        $urls= json_decode($visa->urls);
        return view('visa.view',[
            'visa' => $visa,
            'urls' => $urls,
            //'document' => $document
        ]);

    }
    public function destroy(Request $request, $id)
    {

        $visa = Visa::where('id',$id)->delete();
   
        $request->session()->flash('status', 'Visa deleted successfully.');
        return Redirect::to('visa');
    }
    
public function changeStatus(Request $request){

        $visa = Visa::find($request->visa_id);
        $visa->status = $request->status;
        $visa->save();
        return response()->json(['success'=>'Status change successfully.']);
    }
    public function getDocumentAjax(Request $request)
    {
        $data = [];
        if($request->has('q')){
            $search = $request->q;
            $data = DB::table("documents")
                    ->select("id","document_name")
                     ->where('document_name','LIKE',"%$search%")
                    ->get();
        }
        else{
           $data = DB::table("documents")
                    ->select("id","document_name")
                     ->limit(20)
                    ->get();
        }
        return response()->json($data);
    }
    
    public function getExistingQueryParams()
    {
        $existingQueryParams = [];
    
        foreach (request()->all() as $key => $value)
        {
            if ($key != 'page')
            {
                $existingQueryParams[$key] = urldecode($value);
            }
        }
    
        return $existingQueryParams;
    }

    public function updateUrlForDook(){
        $visas = Visa::get();
        foreach ($visas as $key => $value) {
            $from = $value->passport_holder_country;
            $to = $value->visiting_country;
            $strlower = Str::lower($from);
            $arr = explode(' ', $strlower);
            $str = implode('-', $arr);
            $strlower2 = Str::lower($to);
            $arr2 = explode(' ', $strlower2);
            $str2 = implode('-', $arr2);
            $url = "https://www.dookinternational.com/" . $str . '-to-' . $str2 . '-visa';
            $visaupdate = Visa::where('id', $value->id)->first();
            $visaupdate->dook_visa_urls = $url;
            $visaupdate->save();

        }
        return 'success';
    }

    
}



