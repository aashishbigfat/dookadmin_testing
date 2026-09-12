<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageServiceProvider;
use DB;
use Storage;
use Image;
use Auth;
use App\User;
use App\AgentItinerary;

class AgentItineraryController extends Controller
{
   
    public function ItinerayIndex(Request $request)
    {
       $agent_itinerary = AgentItinerary::orderBy('id','DESC')
                        ->paginate(25);
       $agent_itinerary_Count = AgentItinerary::get();
       $total = count($agent_itinerary_Count);
       if($request->ajax()){
           return view('itinerary.data', compact('agent_itinerary'));
       }
       return view('itinerary.index', compact('agent_itinerary','total'));
    }

    public function ItinerayCreate()
    {
        return view('itinerary.create');
    }

    public function ItinerayStore(Request $request)
    {
        $data = $request->all();

        $user = auth()->user(); 

        $agent_itinerary = new AgentItinerary;
        $agent_itinerary->title = $request->title;
        $agent_itinerary->description = $request->description;
        $agent_itinerary->tenant_id = $user->tenant_id;
        $agent_itinerary->user_id = $user->id;
        $agent_itinerary->dep_type = "main";
        $agent_itinerary->unique_key = Str::random(10).time();
        if($request->hasFile('pdf_name')){ 
                $pdf_files = $request->file('pdf_name');
                $extension = $request->pdf_name->getClientOriginalExtension();
                $name = $request->pdf_name->getClientOriginalName();
                $filenames = $name;
                $filenamess = explode('.pdf',$filenames);
                $filename = $filenamess[0].'-'.time().'.'.$extension;
                $relPath = '/agentitinerary/';
                if (!file_exists(public_path($relPath))) {
                    mkdir(public_path($relPath), 777, true);
                }
                $pdf_files->move( public_path().$relPath, $filename );
                $agent_itinerary->pdf_file = $filename;                    
        };
        $agent_itinerary->save();
        $status = [
                'url'=> url('/itinerary'),
            ];
        return response()->json($status);
    }

    public function ItinerayEdit(Request $request, $id)
    {
        $agentData = AgentItinerary::find($id);
        return view('itinerary.edit',compact('agentData'));
    }

    public function ItinerayUpdate(Request $request, $id)
    {
        $user = auth()->user(); 

        $agent_itinerary = AgentItinerary::find($id);
        $agent_itinerary->title = $request->title;
        $agent_itinerary->description = $request->description;
        $agent_itinerary->tenant_id = $user->tenant_id;
        $agent_itinerary->user_id = $user->id;

        if($request->hasFile('pdf_name')){ 
                $pdf_files = $request->file('pdf_name');
                $extension = $request->pdf_name->getClientOriginalExtension();
                $name = $request->pdf_name->getClientOriginalName();
                $filenames = $name;
                $filenamess = explode('.pdf',$filenames);
                $filename = $filenamess[0].'-'.time().'.'.$extension;
                $relPath = '/agentitinerary/';
                if (!file_exists(public_path($relPath))) {
                    mkdir(public_path($relPath), 777, true);
                }
                $pdf_files->move( public_path().$relPath, $filename );
                $agent_itinerary->pdf_file = $filename;                    
        };
        $agent_itinerary->save();
        $status = [
                'url'=> url('/itinerary'),
            ];
        return response()->json($status);
    }

    public function ItineraryDestroy(Request $request, $id)
    {
        $agent_itinerary  = AgentItinerary::find($id);
        $status = $agent_itinerary->status;
        if($agent_itinerary->status == 1){
            $agent_itinerary->status = 0;
            $agent_itinerary->save();
        }
        else{
            $agent_itinerary->status = 1;
            $agent_itinerary->save();
        }
        
        return response()->json(['status'=>$status]);
    }
}
