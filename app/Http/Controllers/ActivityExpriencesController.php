<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Intervention\Image\ImageServiceProvider;
use DB;
use Storage;
use Image;
use Auth;
use App\User;
use App\Country;
use App\SlugMaster;
use App\Experience;
use App\Activity;
use App\ExperienceTopActivities;
use App\ActivityExperience;
use App\ActivityDeparture;
use App\Traits\TinyPngImageCompress;
use Google\Cloud\Storage\StorageClient;


class ActivityExpriencesController extends Controller
{
    use TinyPngImageCompress;
    public function ActivityIndex(Request $request)
    {
    	$data = $request->all();
        $status = $request->status;
        $keywords = $request->keyword;
        $data = $request->all();
        $status = $request->status;
        $keywords = $request->keyword;
        if($keywords){
            $activities = DB::table('activities')  
                        ->orWhere('activity_name', 'LIKE','%'.$keywords.'%') 
                        ->orderBy('activity_name','ASC')
                        ->select('activities.*')
                        ->distinct('activities.id')
                        ->paginate(25);

        }elseif($status){
            $activities = Activity::where('activities.status', $status) 
                        ->orderBy('activity_name','ASC')
                        ->select('activities.*')
                        ->distinct('activities.id')
                        ->paginate(25);
        }
        else{
            $activities =  DB::table('activities')
                        ->orderBy('activity_name','ASC')          
                        ->select('activities.*')
                        ->distinct('activities.id')
                        ->paginate(25);
        }
        foreach($activities as $key => $value) {
            $str_lower = strtolower($value->activity_name);
            $array = explode(" ",$str_lower);
            $str = implode("-",$array);
            if($value->slug_url == ''){
                $value->slug_url = $str;
            }
        }
        //dd($activities);
        // if($keywords){
        //     $activities = DB::table('activities')
        //                 ->join('experiences','experiences.id','=','activities.experience_id')
        //                 //->where('activities.status', $status)  
        //                 ->where('experiences.experience_name', 'LIKE','%'.$keywords.'%')
        //                 ->orWhere('activities.activity_name', 'LIKE','%'.$keywords.'%') 
        //                 ->orderBy('experiences.experience_name','ASC')
        //                 ->select('activities.*','experiences.experience_name')
        //                 ->paginate(25);
        // }elseif($status){
        //     $activities = Activity::join('experiences','experiences.id','=','activities.experience_id')
        //                 ->where('activities.status', $status) 
        //                 ->orderBy('experiences.experience_name','ASC')
        //                 ->select('activities.*','experiences.experience_name')
        //                 ->paginate(25);
        // }elseif($status && $keywords){
        //     $activities = DB::table('activities')
        //                 ->join('experiences','experiences.id','=','activities.experience_id')
        //                 ->where('activities.status', $status)  
        //                 ->where(function($query)use($keywords){
        //                     $query->where('experiences.experience_name', 'LIKE','%'.$keywords.'%')
        //                         ->orWhere('activities.activity_name', 'LIKE','%'.$keywords.'%');
        //                     }) 
        //                 ->orderBy('experiences.experience_name','ASC')
        //                 ->select('activities.*','experiences.experience_name')
        //                 ->paginate(25);
        // }else{
        //     $activities = DB::table('activities')
        //                 ->join('experiences','experiences.id','=','activities.experience_id')          
        //                 ->orderBy('experiences.experience_name','ASC')
        //                 ->select('activities.*','experiences.experience_name')
        //                 ->paginate(25);
        // }
        //dd($activities);

        $total_activities = DB::table('activities')->get();
        $total = count($total_activities);
        $status = ($status == null)?'no':$status;
        //$urlS3 = "https://dook-international.sgp1.cdn.digitaloceanspaces.com/dook/images/activities/";
        // $urlS3 = url('/dook/images/activities/').'/';
        $urlS3 = 'https://dooktravels.s3.ap-south-1.amazonaws.com/com/activities/';
        if($request->ajax()){
            return view('activityExperience.activity_data',compact('activities','urlS3','keywords','status'));
        }
        return view('activityExperience.activity_index',compact('activities','total','urlS3','keywords','status'));
    }

    public function ActivityUpdate(Request $request, $id)
    {
    	$activities       = Activity::find($id);
        $activities->activity_name = $request->edit_name;
        $activities->description = $request->edit_description;
        $activities->slug_url = $request->edit_slug;
        $activities->header_title = $request->edit_header_title;
        $activities->header_sub_title = $request->edit_header_sub_title;
        $activities->meta_title = $request->meta_title;
        $activities->meta_keywords = $request->meta_keywords;
        $activities->meta_description = $request->meta_description;
           if ($request->hasFile('edit_image')) {
                $image = $request->file('edit_image');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/activities/' . $originalName;

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

                $experiences->image = $originalName;
            }
               if ($request->hasFile('edit_banner_image')) {
                $image = $request->file('edit_banner_image');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/activities/' . $originalName;

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

                $activities->banner_image = $originalName;
            }
        // if($request->edit_image){
        //     $image = $request->file('edit_image');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $filename = Str::random(5).time().'.'.$extension;
        //     // $imagecompress = $this->compressToLocal($image, 'dook/images/activities', $filename);
        //     //$images = Image::make($image);
        //     //Storage::disk('s3')->put('dook/images/activities/'.$filename, $images->stream(), 'public');
        //     // $activities->image = $filename;  

        //       $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/activities/'.$fileName, $convertImage);
        //     $activities->image = $fileName;
        // }

        // if($request->edit_banner_image){
        //     $image = $request->file('edit_banner_image');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $filename = Str::random(5).time().'.'.$extension;
        //     // $imagecompress = $this->compressToLocal($image, 'dook/images/activities', $filename);
        //     //$images = Image::make($image);
        //     //Storage::disk('s3')->put('dook/images/activities/'.$filename, $images->stream(), 'public');
        //     // $activities->banner_image = $filename;  

        //     $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/activities/'.$fileName, $convertImage);
        //     $activities->banner_image = $fileName;
        // }
        $activities->save();
        $status = [
                'status'=> "Success",
            ];
        return response()->json($status);
    }

    public function activityDisable(Request $request, $id)
    {
        $activities  = Activity::find($id);
       	
        if($activities->status == 1){
            $activities->status = 0;
            $activities->save();
        }
        else{
            $activities->status = 1;
            $activities->save();
        }
        
        return response()->json(['success'=>'Success!']);
    }

    //Experiences Functions

    public function experienceIndex(Request $request)
    {
        $data = $request->all();
        $status = $request->status;
        $keywords = $request->keyword;
        if($keywords != '' || $status != ''){
            $experiences = DB::table('experiences')
                        ->where('status', $status)  
                        ->orWhere('experience_name', 'LIKE','%'.$keywords.'%')       
                        ->orderBy('sorting','ASC')
                        ->paginate(30);
        }else{
            $experiences = DB::table('experiences')       
                        ->orderBy('sorting','ASC')
                        ->paginate(30);
        }
        $total_experiences = DB::table('experiences')->get();
        $total = count($total_experiences);
        $status = ($status == null)?'no':$status;
        //$urlS3 = "https://dook-international.sgp1.cdn.digitaloceanspaces.com/dook/images/experience/";
        // $urlS3 = url('/dook/images/experience/').'/';
        $urlS3 = 'https://dooktravels.s3.ap-south-1.amazonaws.com/com/experience/';
        if($request->ajax()){
            return view('activityExperience.experience_data',compact('experiences','urlS3','keywords','status'));
        }
        return view('activityExperience.experience_index',compact('experiences','total','urlS3','keywords','status'));
    }

    public function experienceEdit(Request $request, $id)
    {  
        $experiences = Experience::where('id',$id)->first();
        $expActivities = ExperienceTopActivities::where('experience_id', $id)->get();
        $departure_id = DB::table('destination_experiences')
                        ->where('experience_id',$experiences->id)
                        ->distinct()
                        ->pluck('departure_id')
                        ->toArray();
          
        $package_wise_activity_id = ActivityDeparture::whereIn('departure_id', $departure_id)
                                ->distinct()
                                ->pluck('activity_id')
                                ->toArray();
        $all_activities = ActivityExperience::where('experience_id',$experiences->id)
                        ->distinct()
                        ->pluck('activity_id')
                        ->toArray(); 
        $activity_id_array = array_intersect($package_wise_activity_id, $all_activities);
        // dd($activity_id_array);
        $activities = Activity::whereIn('id', $activity_id_array)
                    ->select('id','activity_name')
                    ->where('status', 1)
                    ->get();  
        //$urlS3 = "https://dook-international.sgp1.cdn.digitaloceanspaces.com/dook/images/experience/";
        // $urlS3 = url('/dook/images/experience/').'/';
        $urlS3 = 'https://dooktravels.s3.ap-south-1.amazonaws.com/com/experience/';
        return view('activityExperience.experience_edit',compact('experiences','urlS3','activities','expActivities'));
    }

    public function experienceUpdate(Request $request, $id)
    {
        $user = auth()->user();
        $experiences = Experience::find($id);
        $experiences->experience_name = $request->edit_name;
        //$experiences->label_name = $request->edit_label;
        $experiences->sub_title = $request->sub_title;
        $experiences->edit_Header_title = $request->edit_Header_title;
        $experiences->header_sub_title = $request->header_sub_title;

        $experiences->slug_url = $request->edit_slug;
        $experiences->description = $request->edit_description;
        $experiences->exp_title = $request->exp_title;
        $experiences->exp_sub_title = $request->exp_sub_title;
        $experiences->pkg_title = $request->pkg_title;
        $experiences->pkg_sub_title = $request->pkg_sub_title;
        $experiences->country_title = $request->country_title;
        $experiences->country_sub_title = $request->country_sub_title;
        
        $experiences->meta_title = $request->meta_title;
        $experiences->meta_keywords = $request->meta_keywords;
        $experiences->meta_description = $request->meta_description;
        
         if ($request->hasFile('edit_image')) {
                $image = $request->file('edit_image');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/experience/' . $originalName;

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

                $experiences->image = $originalName;
            }
            if ($request->hasFile('edit_banner_image')) {
                $image = $request->file('edit_banner_image');
                $originalName = $image->getClientOriginalName(); // original file name with extension
                $path = 'com/experience/' . $originalName;

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

                $experiences->banner_image = $originalName;
            }

        // if($request->edit_image){
        //     $image = $request->file('edit_image');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $filename = Str::random(5).time().'.'.$extension;
        //     // $imagecompress = $this->compressToLocal($image, 'dook/images/experience', $filename);
        //     //$images = Image::make($image);
        //     //Storage::disk('s3')->put('dook/images/experience/'.$filename, $images->stream(), 'public');
        //     // $experiences->image = $filename;  

        //      $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/experience/'.$fileName, $convertImage);
        //     $experiences->image = $fileName;

        // }

        // if($request->edit_banner_image){
        //     $image = $request->file('edit_banner_image');
        //     // $extension = $image->getClientOriginalExtension();
        //     // $filename = Str::random(5).time().'.'.$extension;
        //     // $imagecompress = $this->compressToLocal($image, 'dook/images/experience', $filename);
        //     //$images = Image::make($image);
        //     //Storage::disk('s3')->put('dook/images/experience/'.$filename, $images->stream(), 'public');
        //     // $experiences->banner_image = $filename;  

        //     $ext = 'webp';
        //     $convertImage = Image::make($image)->encode($ext, 60);
        //     $fileName = uniqid().'.'.$ext;
        //     Storage::disk('s3')->put('com/experience/'.$fileName, $convertImage);
        //     $experiences->banner_image = $fileName;
        // }
        $experiences->save();
        $last_id = $experiences->id;

        $activities = $request->activityId;
        if ($activities) {
            ExperienceTopActivities::where('experience_id', $last_id)->delete();
            foreach ($activities as $value) {
                $experiencesActivity = new ExperienceTopActivities;
                $experiencesActivity->experience_id = $last_id;
                $experiencesActivity->activity_id = $value;
                $experiencesActivity->user_id = $user->id;
                $experiencesActivity->save();
            }
        }

        $slugUnique = SlugMaster::where('experience_id', $last_id)
                  ->where('module_name', 'experience_detail_page')
                  ->first();
        if($slugUnique){
            $destinationSlug  = SlugMaster::find($slugUnique->id);
            $destinationSlug->slug_name = $request->edit_slug;
            $destinationSlug->save();
        }else{
            $destinationSlug  = new SlugMaster;
            $destinationSlug->experience_id = $last_id;
            $destinationSlug->slug_name = $request->edit_slug;
            $destinationSlug->module_name = 'experience_detail_page';
            $destinationSlug->save();
        }

        $status = [
                'url'=> url('/experiences'),
            ];
        return response()->json($status);
    }

    public function experienceDisable(Request $request, $id)
    {
        $experiences  = Experience::find($id);
        
        if($experiences->status == 1){
            $experiences->status = 0;
            $experiences->save();
        }
        else{
            $experiences->status = 1;
            $experiences->save();
        }
        
        return response()->json(['success'=>'Success!']);
    }

    public function experienceShowAtHome(Request $request, $id)
    {
        $experiences  = Experience::find($id);
        
        if($experiences->show_at_home == 1){
            $experiences->show_at_home = 0;
            $experiences->save();
        }
        else{
            $experiences->show_at_home = 1;
            $experiences->save();
        }
        
        return redirect()->back();
    }

    public function experiencePositionReshifting(Request $request){

        if($request->position)
        {
            $pos = $request->position;
            $i = 0;
            foreach($pos as $k=>$v)
            {
                $i++;
                $item = Experience::find($v);
                $item->sorting = $i;
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
}
