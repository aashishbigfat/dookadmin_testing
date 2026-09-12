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
use App\Destination;
use App\OptionalActivity;

class OptionalActivityController extends Controller
{
    public function index(Request $request)
    {
        $keywords = $request->keyword;
        $dest_id = $request->dest_id;
        $destination = OptionalActivity::join('destinations','destinations.id','=','optional_activities.destination_id')
                            ->where('destinations.id',$dest_id)
                            ->select('destinations.dest_name')
                            ->first();

        $optional_activity = OptionalActivity::join('destinations','destinations.id','=','optional_activities.destination_id')
                            //->where('optional_activities.tenant_id',auth()->user()->tenant_id)
                            ->where(function($query)use($keywords){
                                $query->where('optional_activities.title', 'LIKE','%'.$keywords.'%')
                                        ->orWhere('destinations.dest_name', 'LIKE','%'.$keywords.'%');
                            })
                            ->select('optional_activities.*','destinations.dest_name')
                            ->paginate(25);

        $optional_activityCount = OptionalActivity::get();
        $total = count($optional_activityCount);
        if($request->ajax()){
            return view('optionalActivity.data', compact('optional_activity'));
        }
        return view('optionalActivity.index', compact('optional_activity','total'));
    }

    public function create()
    {
        return view('optionalActivity.create');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $optional_activity = new OptionalActivity;
        $optional_activity->title = $request->title;
        $optional_activity->destination_id = $request->destination;
        $optional_activity->duration = $request->duration;
        $optional_activity->price = $request->price;
        $optional_activity->description = $request->description;
        $optional_activity->tenant_id = $user->tenant_id;
        $optional_activity->user_id = $user->id;
        $optional_activity->unique_key = Str::random(10).time();
        if($request->hasFile('image_name')){ 
            $image = $request->file('image_name');
            $extension = $image->getClientOriginalExtension();
            $filename= Str::random(5).time().'.'.$extension;
            $relPath = 'images/uploads/optionalactivity/';
                if (!file_exists(public_path($relPath))) {
                    mkdir(public_path($relPath), 777, true);
                }
            Image::make($image)->save( public_path($relPath . $filename ) );
            $optional_activity->image = $filename;                    
        };
        $optional_activity->save();
        $status = [
                'url'=> url('/optional-activity'),
            ];
        return response()->json($status);
    }
    
    public function edit(Request $request, $id)
    {
        $optional_activity = OptionalActivity::find($id);
        $destination_match = Destination::join('optional_activities','optional_activities.destination_id','=','destinations.id')
                        ->select('destinations.id','destinations.dest_name')
                        ->where('optional_activities.id',$id)
                        ->first();
        return view('optionalActivity.edit', compact('optional_activity','destination_match'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $optional_activity = OptionalActivity::find($id);
        $optional_activity->title = $request->title;
        $optional_activity->destination_id = $request->destination;
        $optional_activity->duration = $request->duration;
        $optional_activity->description = $request->description;
        $optional_activity->price = $request->price;
        if($request->hasFile('image_name')){ 
            $image = $request->file('image_name');
            $extension = $image->getClientOriginalExtension();
            $filename= Str::random(5).time().'.'.$extension;
            $relPath = 'images/uploads/optionalactivity/';
                if (!file_exists(public_path($relPath))) {
                    mkdir(public_path($relPath), 777, true);
                }
            Image::make($image)->save( public_path($relPath . $filename ) );
            $optional_activity->image = $filename;                    
        };
        $optional_activity->save();

        $status = [
                'url'=> url('/optional-activity'),
            ];
        return response()->json($status);
    }

    public function destroy(Request $request, $id)
    {
        $optional_activity  = OptionalActivity::find($id);
        $status = $optional_activity->status;
        if($optional_activity->status == 1){
            $optional_activity->status = 0;
            $optional_activity->save();
        }
        else{
            $optional_activity->status = 1;
            $optional_activity->save();
        }
        
        return response()->json(['status'=>$status]);
    }

    public function destinationForOptional(Request $request)
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
                        ->limit(10)
                        ->get();
        }
        return response()->json($data);
    }
}
