<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageServiceProvider;
use DB;
use Storage;
use Image;
use Auth;
use App\Departure;
use App\Banner;
use App\Traits\TinyPngImageCompress;

class BannerController extends Controller
{
    use TinyPngImageCompress;

    public function index(Request $request)
    {
        $data = $request->all();
        $banners = Banner::paginate();
        $banner = Banner::select('id')->get();
        $total = count($banner);
        //$s3url= "https://dook-international.sgp1.cdn.digitaloceanspaces.com/dook/images/banner/";
        // $s3url = url('/dook/images/banner/').'/';
         $s3url = 'https://dooktravels.s3.ap-south-1.amazonaws.com/com/banner/';

        if($request->ajax()){
                return view('banners.data',compact('banners','s3url'));
            }
        return view('banners.index',compact('banners','total','s3url'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('banners.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $user = auth()->user();
        $banners = new Banner;
        $banners->title = $request->title;
        $banners->sub_title = $request->sub_title;
        $banners->slug_url = $request->slug_url;
        $banners->description = $request->description;
        $banners->days = $request->days;
        $banners->where_to_show = $request->where_show;
        $banners->user_id = $user->id;
        if($request->banner_image){
            $image = $request->file('banner_image');
            // $extension = $image->getClientOriginalExtension();
            // $filename = Str::random(5).time() .'.'. $extension;
            // $images = Image::make($image);
            // Storage::disk('s3')->put('dook/images/banner/'.$filename, $images->stream(), 'public');
            //Storage::disk('spaces')->putFileAs('dook/images/banner', $image, $filename,'public');
            // $imagecompress = $this->compressToLocal($image, 'dook/images/banner', $filename);
            // $banners->image = $filename;  


             $ext = 'webp';
            $convertImage = Image::make($image)->encode($ext, 60);
            $fileName = uniqid().'.'.$ext;
            Storage::disk('s3')->put('com/banner/'.$fileName, $convertImage);
            $banners->image = $fileName;
        }
        $banners->save();
        $status = [
                'url'=> url('/banners'),
            ];
        return response()->json($status);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $user = auth()->user();
        $banners = Banner::find($id);
        $banners->title = $request->edit_title;
        $banners->sub_title = $request->edit_sub_title;
        $banners->slug_url = $request->edit_slug_url;
        $banners->description = $request->edit_description;
        $banners->days = $request->edit_days;
        $banners->where_to_show = $request->where_show;
        $banners->user_id = $user->id;
        if($request->edit_image){
            $image = $request->file('edit_image');
            // $extension = $image->getClientOriginalExtension();
            // $filename = Str::random(5).time() .'.'. $extension;
            // $images = Image::make($image);
            // Storage::disk('s3')->put('dook/images/banner/'.$filename, $images->stream(), 'public');
            //Storage::disk('spaces')->putFileAs('dook/images/banner', $image, $filename,'public');
            // $imagecompress = $this->compressToLocal($image, 'dook/images/banner', $filename);
            //$imagecompress = $this->compressToLocal($image, 'dook/images/banner', $filename);
            // $banners->image = $filename;  

             $ext = 'webp';
            $convertImage = Image::make($image)->encode($ext, 60);
            $fileName = uniqid().'.'.$ext;
            Storage::disk('s3')->put('com/banner/'.$fileName, $convertImage);
            $banners->image = $fileName;
        }
        $banners->save();
        $status = [
                'url'=> url('/banners'),
            ];
        return response()->json($status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
