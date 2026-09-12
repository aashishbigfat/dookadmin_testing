<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mailer;
use Illuminate\Support\Facades\File; 

class MailerController extends Controller
{
    public function indexMailer(Request $request){
        $keyword = $request->keyword;
        $monthN = $request->month;
        $yearN = $request->year;
        $datas = Mailer::where(function ($query) use($request){
                if($request->keyword != ''){
                    $query->where('html_name', 'LIKE','%'.$request->keyword.'%')->orWhere('image_name', 'LIKE','%'.$request->keyword.'%');
                }
                if($request->month != ""){
                  $query->where('month', 'LIKE','%'.$request->month.'%');
                        
                }
                if($request->year != ""){
                  $query->where('year', 'LIKE','%'.$request->year.'%');
                        
                }
            })
            ->orderBy('created_at','DESC')
            ->paginate(45);
        $months = Mailer::select('month')->distinct('month')->get();
        $years = Mailer::select('year')->distinct('year')->get();
        $total = Mailer::count();
        return view('mailer.index',compact('datas','months','monthN','keyword','yearN','years','total'));
    }
    public function htmlUpload(Request $request){
        $year = date("Y");   
        $month = date("F"); 
        if($request->hasFile('image_file')){
            foreach ($request->image_file as $key => $value) {
                $original_name = $value->getClientOriginalName();
                $abc = Mailer::where('image_name',$original_name)->first();
                if($abc){
                    $status = [
                            'msg'=>"[ ".$original_name."] Image already exist please add another image using different name",
                        ];
                    return response()->json($status);
                }
                else{
                    $html = new Mailer;
                    $html->user_id = auth()->user()->id;
                    $html->user_name = auth()->user()->name;
                    $html->month = $month;
                    $html->year = $year;
                    $image_files = $value;
                    //$image_files = $request->file('image_file');
                    //$original_name = $value->getClientOriginalName();
                    //$filename = "/mailer/images/".$month."-".$year.'/';
                    $filename = "/var/www/www.dookinternational.com/html/static/mailer/images/";
                        if (!file_exists($filename)) {
                            mkdir($filename, 777, true); 
                        }  
                    $image_files->move($filename, $original_name );  
                    $html->image = "https://www.dookinternational.com/mailer/images/".$original_name; 
                    $html->image_name = $original_name; 
                    $html->save();
                }
            }
        }
        else{
            $original_name = $request->html_file->getClientOriginalName();
            $abcd = Mailer::where('html_name',$original_name)->first();
            if($abcd){
                $status = [
                        'msg'=>"[".$original_name."] Html already exist please add another html using different name",
                    ];
                return response()->json($status);
            }
            else{
                if($request->hasFile('html_file')){ 
                    $html = new Mailer;
                    $html->user_id = auth()->user()->id;
                    $html->user_name = auth()->user()->name;
                    $html->month = $month;
                    $html->year = $year;
                    $html_files = $request->file('html_file');
                    $only_name = $request->html_file->getClientOriginalName();
                    //$filename = "/mailer/".$month."-".$year.'/';
                    $filename = "/var/www/www.dookinternational.com/html/static/mailer/";
                        if (!file_exists($filename)) {
                            mkdir($filename, 777, true); 
                        }  
                    $html_files->move($filename, $original_name);  
                    $html->html_file = "https://www.dookinternational.com/mailer/".$original_name; 
                    $html->html_name = $only_name; 
                }
                $html->save();
            }
        }
    }
    public function mailerDelete($id){
        
        $html = Mailer::find($id);
        if($html->image){

            $public_path = "/var/www/www.dookinternational.com/html/static/mailer/images/".$html->image_name;
        }else{
            $public_path = "/var/www/www.dookinternational.com/html/static/mailer/".$html->html_name;
        }
        if(file_exists($public_path)){
            unlink($public_path);
        }
        $html->delete();
        return redirect()->back();
    }
}
