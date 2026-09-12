<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Destination;
use App\DookEnquiry;
use DB;

class DestinationBlogController extends Controller
{
    public function destinationListForBlog(Request $request){
        if($request->dest == 'abcd'){
            $destinations = Destination::where('status',1)
                    ->select('id','dest_name','country_name')
                    ->get();

            $status = array(
                'error' => false,
                'destinations' => $destinations,
            );
            return response()->json($status, 200);
        }
    }

    public function careerListing(Request $request)
    {
        $jobs = DB::table('dook_jobs')
                    ->distinct()
                    ->select('id','title','location','role','slug_url','position','exp')
                    ->where(['status'=> 1, 'type'=> 'Wat'])
                    ->orderBy('id', 'DESC')
                    ->get();
        if(count($jobs)>0){
            $status = array(
                'error' => false,
                'data' => $jobs
            );
        }
        else{
            $status = array(
                'error' => false,
                'data' => []
            );
        }
        return response()->json($status, 200);
    }

    public function careerDetails(Request $request)
    {
        $slug = $request->slug_url;
        $job_detail = DB::table('dook_jobs')
                    ->where('slug_url', $slug)
                    ->select('title','location','role','slug_url','position','exp','meta_title','meta_keywords','meta_description','description')
                    ->where('status', 1)
                    ->first();
        $jobs = DB::table('dook_jobs')
            ->distinct()
            ->select('id','title','location','role','slug_url','position','exp')
            ->where(['status'=> 1, 'type'=> 'Wat'])
            ->where('slug_url','!=',$job_detail->slug_url)
            ->orderBy('id', 'DESC')
            ->get();
        if($job_detail){
            $status = array(
                'error' => false,
                'data' => $job_detail,
                'jobs' => $jobs
            );
        }
        else{
            $status = array(
                'error' => false,
                'data' => $job_detail,
                'jobs' => []
            );
        }
        return response()->json($status, 200);
    }

    public function userResumeStore(Request $request)
    {
        $name = $request->name;
        $mobile = $request->mobile;
        $mail = $request->mail;
        $designation = $request->designation;
        $title = $request->title;
        $resume = $request->resume;
        $filename = $request->filename;

        $store = new DookEnquiry;
        $store->name = $name;
        $store->mob_no = $mobile;
        $store->email = $mail;
        $store->job_role = $designation;
        $store->job_title = $title;
        $store->type = "WatJob";
        $store->status = 17;

        if($resume){
            $file = $resume;
            $base = base64_decode($file);  
            $base64 = time().'.' . explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1].$filename;
            $destinationPath = public_path() . "/dook/resume/" . $base64;             
            file_put_contents($destinationPath, $base);

            // $file = $resume;                
            // $newName = time().$filename;
            // $relPath = '/dook/resume/';
            // if (!file_exists(public_path($relPath))) {
            //     mkdir(public_path($relPath), 777, true);
            // }
            // $file->move( public_path().$relPath,$newName);

            $store->resume = $base64;
        }
        $store->save();

            $status = array(
                'error' => false,
                'data' => $store->id,
            );
        
        return response()->json($status, 200);
    }
}
