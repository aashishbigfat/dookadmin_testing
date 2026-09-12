<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DookReview;
class PresentationReviewController extends Controller
{
    public function reviewList(Request $request)
    {
    	$reviews = DookReview::paginate(15);
    	$reviews_total = DookReview::get();
    	$total = count($reviews_total);
    	if($request->ajax()){
	        return view('review.data',compact('reviews'));
	    }
    	return view('review.index',compact('reviews','total'));
    }
    public function reviewDisable(Request $request, $id)
    {
        $review  = DookReview::find($id);
        if($review->active_status == 1){
            $review->active_status = 0;
            $review->save();
        }
        else{
            $review->active_status = 1;
            $review->save();
        }
        return response()->json(['success'=>'Success!']);
    }

    public function reviewDelete(Request $request, $id)
    {
        DookReview::where('id',$id)->delete();

        return response()->json(['success'=>'Success!']);
    }
}
