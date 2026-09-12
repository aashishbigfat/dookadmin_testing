<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageServiceProvider;
use DB;
use Storage;
use Image;
use Auth;
use App\DookJob;

class CareerController extends Controller
{
	public function index(Request $request)
    {
    	$jobs = DookJob::orderBy('id','DESC')->paginate(100);
    	return view('career.index',compact('jobs'));
    }
    public function store(Request $request)
    {
        $data = $request->all();
        $user = auth()->user();
        $dookjob = new DookJob;
        $dookjob->title = $request->title;
        $dookjob->role = $request->role;
        $dookjob->slug_url = $request->slug_url;
        $dookjob->location = $request->location;
        $dookjob->description = $request->description;
        $dookjob->position = $request->position;
        $dookjob->exp = $request->experience;
        $dookjob->status = $request->status;
        $dookjob->meta_title = $request->meta_title;
        $dookjob->meta_keywords = $request->meta_keywords;
        $dookjob->meta_description = $request->meta_description;
        $dookjob->type = $request->type;
        $dookjob->save();
        $status = [
            'message'=> "Success.!",
        ];
        return response()->json($status);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $user = auth()->user();
        $dookjob = DookJob::find($id);
        $dookjob->title = $request->edit_title;
        $dookjob->role = $request->edit_role;
        $dookjob->slug_url = $request->edit_slug_url;
        $dookjob->location = $request->edit_location;
        $dookjob->description = $request->edit_description;
        $dookjob->position = $request->edit_position;
        $dookjob->exp = $request->edit_experience;
        $dookjob->status = $request->edit_status;
        $dookjob->meta_title = $request->edit_meta_title;
        $dookjob->meta_keywords = $request->edit_meta_keywords;
        $dookjob->meta_description = $request->edit_meta_description;
        $dookjob->type = $request->edit_type;
        $dookjob->save();
        $status = [
            'message'=> "Success.!",
        ];
        return response()->json($status);
    }
    public function statusChange(Request $request, $id)
    {
        $job  = DookJob::find($id);
        if($job->status == 1){
            $job->status = 0;
            $job->save();
        }
        else{
            $job->status = 1;
            $job->save();
        }
        return response()->json(['success'=>'Success!']);
    }
    public function jobDelete(Request $request, $id)
    {
        $job  = DookJob::find($id)->delete();
        return response()->json(['success'=>'Success!']);
    }
}
