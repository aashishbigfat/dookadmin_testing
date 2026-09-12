<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();
        $tags = Tag::paginate(25);
        $tag = Tag::select('id')->get();
        $total = count($tag);
        return view('tags.create',compact('tags','total'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $unique_tag = Tag::where('name', $request->title)->value('name');
        if($unique_tag){
            $status = [
                'status'=>false,
                'msg'=> "This tag is already taken please add different tag name.!",
            ];
            return response()->json($status);
        }
        else{
            $tag = new Tag;
            $tag->name = $request->title;
            $tag->save();
            $status = [
                'status'=>true,
                'msg'=> "Successfully added.!",
            ];
            return response()->json($status);
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $tag = Tag::find($id);
        $tag->name = $request->edit_tag;
        $tag->save();
        $status = [
            'status'=>true,
            'msg'=> "Successfully added.!",
        ];
        return response()->json($status);
    }
    public function delete(Request $request, $id)
    {
        $tag = Tag::find($id)->delete();
        return response()->json(['success'=>'Tag deleted successfully!']);
    }


    // public function getTagAjax(Request $request)
    // {
    //     $data = [];
    //     if($request->has('q')){
    //         $search = $request->q;
    //         $data = Tag::select("id","name")
    //                     ->where('name','LIKE',"%$search%")
    //                     ->get(30);
    //         }
    //     else{
    //         $data = Tag::select("id","name")
    //                     ->limit(50)
    //                     ->get();
    //     }
    //     return response()->json($data);
    // }
}
