<?php

namespace App\Http\Controllers;
ini_set('max_execution_time', '540');
use Illuminate\Http\Request;
use App\DepartureImage;
use App\Departure;

class ImageCompressController extends Controller
{
    public function packageImageCompress(Request $request)
    {
    	$count4img = DepartureImage::where('image_compress', 1)->count();
        $countFimg = Departure::where('image_compress', 1)->count();
        $countBimg = Departure::where('banner_image_compress', 1)->count();
    	return view('packageImageCompress.index', compact('count4img','countFimg','countBimg'));
    }

    public function packageFourImageCompressUpdate(Request $request)
    {
    	$data = DepartureImage::where('image_compress', 0)
                ->where('image','!=', null)
    			->select('id', 'image')
    			->take(20)
    			->get();
    	if(count($data)>0){
    		foreach ($data as $key => $value) {
    			$img_compress = DepartureImage::find($value->id);

    			$filepath = 'https://s3-pullit-bucket.s3.us-west-2.amazonaws.com/dook/images/package/'.$value->image;
    			echo $filename = $value->image;
            	try {
	                \Tinify\setKey("tJ62FVVM14GGLVGsDZkcs6wNXLTxlhy4"); 
	                $source = \Tinify\fromFile($filepath);
	                //$source->toFile($filepath);

	                $source->store(array(
	                    "service" => "s3",
	                    "aws_access_key_id" => env('TINIFY_AWS_ACCESS_KEY_ID'),
	                    "aws_secret_access_key" => env('TINIFY_AWS_SECRET_ACCESS_KEY'),
	                    "region" => "us-west-2",
	                    "headers" => array("Cache-Control" => "max-age=31536000, public"),
	                    "path" => 's3-pullit-bucket/dook/images/package/'.$filename
	                ));
	                $img_compress->image_compress = 1;

            	} catch(\Tinify\AccountException $e) {
            		$status = [
                        'url'=>"AccountException Error!",
                    ];
                    return response()->json($status);
                   //$this->directImageSaveInS3($image,$foldername, $filename);
                    //return redirect('images/create1')->with('error', $e->getMessage());
                } catch(\Tinify\ClientException $e) {
                	$status = [
                        'url'=>"ClientException Error!",
                    ];
                    return response()->json($status);
                    //$this->directImageSaveInS3($image,$foldername, $filename);
                    //return redirect('images/create')->with('error', $e->getMessage());
                } catch(\Tinify\ServerException $e) {
                	$status = [
                        'url'=>"ServerException Error!",
                    ];
                    return response()->json($status);
                    //$this->directImageSaveInS3($image,$foldername, $filename);
                    //return redirect('images/create')->with('error', $e->getMessage());
                } catch(\Tinify\ConnectionException $e) {
                	$status = [
                        'url'=>"ConnectionException Error!",
                    ];
                    return response()->json($status);
                    //$this->directImageSaveInS3($image,$foldername, $filename);
                    //return redirect('images/create')->with('error', $e->getMessage());
                } catch(Exception $e) {
                	$status = [
                        'url'=> "Something went wrong try again later!",
                    ];
                    return response()->json($status);
                    //$this->directImageSaveInS3($image,$foldername, $filename);
                    //return redirect('images/create')->with('error', $e->getMessage());
                }
    			$img_compress->save();
    		}
    		//return response()->json(['success'=>'Success!']);
    	}
    }

    public function packageImageCompressUpdate(Request $request)
    {
    	$data = Departure::where('image_compress', 0)
                ->where('image','!=', null)
    			->select('id', 'image')
    			->take(20)
    			->get();
    	if(count($data)>0){
    		foreach ($data as $key => $value) {
    			$img_compress = Departure::find($value->id);

    			$filepath = 'https://s3-pullit-bucket.s3.us-west-2.amazonaws.com/dook/images/package/'.$value->image;
    			$filename = $value->image;
            	try {
	                \Tinify\setKey("tJ62FVVM14GGLVGsDZkcs6wNXLTxlhy4"); 
	                $source = \Tinify\fromFile($filepath);
	                //$source->toFile($filepath);

	                $source->store(array(
	                    "service" => "s3",
	                    "aws_access_key_id" => env('TINIFY_AWS_ACCESS_KEY_ID'),
	                    "aws_secret_access_key" => env('TINIFY_AWS_SECRET_ACCESS_KEY'),
	                    "region" => "us-west-2",
	                    "headers" => array("Cache-Control" => "max-age=31536000, public"),
	                    "path" => 's3-pullit-bucket/dook/images/package/'.$filename
	                ));
	                $img_compress->image_compress = 1;

            	} catch(\Tinify\AccountException $e) {
            		$status = [
                        'url'=>"AccountException Error!",
                    ];
                    return response()->json($status);
                   //$this->directImageSaveInS3($image,$foldername, $filename);
                    //return redirect('images/create1')->with('error', $e->getMessage());
                } catch(\Tinify\ClientException $e) {
                	$status = [
                        'url'=>"ClientException Error!",
                    ];
                    return response()->json($status);
                    //$this->directImageSaveInS3($image,$foldername, $filename);
                    //return redirect('images/create')->with('error', $e->getMessage());
                } catch(\Tinify\ServerException $e) {
                	$status = [
                        'url'=>"ServerException Error!",
                    ];
                    return response()->json($status);
                    //$this->directImageSaveInS3($image,$foldername, $filename);
                    //return redirect('images/create')->with('error', $e->getMessage());
                } catch(\Tinify\ConnectionException $e) {
                	$status = [
                        'url'=>"ConnectionException Error!",
                    ];
                    return response()->json($status);
                    //$this->directImageSaveInS3($image,$foldername, $filename);
                    //return redirect('images/create')->with('error', $e->getMessage());
                } catch(Exception $e) {
                	$status = [
                        'url'=> "Something went wrong try again later!",
                    ];
                    return response()->json($status);
                    //$this->directImageSaveInS3($image,$foldername, $filename);
                    //return redirect('images/create')->with('error', $e->getMessage());
                }
    			$img_compress->save();
    		}
    		//return response()->json(['success'=>'Success!']);
    	}
    }

    public function packageBannerImageCompressUpdate(Request $request)
    {
    	$data = Departure::where('banner_image_compress', 0)
                ->where('banner_image','!=', null)
    			->select('id', 'banner_image')
    			->take(20)
    			->get();
    	if(count($data)>0){
    		foreach ($data as $key => $value) {
    			$img_compress = Departure::find($value->id);

    			$filepath = 'https://s3-pullit-bucket.s3.us-west-2.amazonaws.com/dook/images/package/'.$value->banner_image;
    			$filename = $value->banner_image;
            	try {
	                \Tinify\setKey("tJ62FVVM14GGLVGsDZkcs6wNXLTxlhy4"); 
	                $source = \Tinify\fromFile($filepath);
	                //$source->toFile($filepath);

	                $source->store(array(
	                    "service" => "s3",
	                    "aws_access_key_id" => env('TINIFY_AWS_ACCESS_KEY_ID'),
	                    "aws_secret_access_key" => env('TINIFY_AWS_SECRET_ACCESS_KEY'),
	                    "region" => "us-west-2",
	                    "headers" => array("Cache-Control" => "max-age=31536000, public"),
	                    "path" => 's3-pullit-bucket/dook/images/package/'.$filename
	                ));
	                $img_compress->banner_image_compress = 1;
	                $img_compress->save();
            	} catch(\Tinify\AccountException $e) {
            		$status = [
		                'url'=> "AccountException Error!",
		            ];
		        	return response()->json($status);
                   //$this->directImageSaveInS3($image,$foldername, $filename);
                    //return redirect('images/create1')->with('error', $e->getMessage());
                } catch(\Tinify\ClientException $e) {
                	$status = [
		                'url'=>"ClientException Error!",
		            ];
		        	return response()->json($status);
                    //$this->directImageSaveInS3($image,$foldername, $filename);
                    //return redirect('images/create')->with('error', $e->getMessage());
                } catch(\Tinify\ServerException $e) {
                	$status = [
		                'url'=>"ServerException Error!",
		            ];
		        	return response()->json($status);
                    //$this->directImageSaveInS3($image,$foldername, $filename);
                    //return redirect('images/create')->with('error', $e->getMessage());
                } catch(\Tinify\ConnectionException $e) {
                	$status = [
		                'url'=>"ConnectionException Error!",
		            ];
		        	return response()->json($status);
                    //$this->directImageSaveInS3($image,$foldername, $filename);
                    //return redirect('images/create')->with('error', $e->getMessage());
                } catch(Exception $e) {
                	$status = [
                		'url'=> "Something went wrong try again later!",
			        ];
			        return response()->json($status);
                    //$this->directImageSaveInS3($image,$foldername, $filename);
                    //return redirect('images/create')->with('error', $e->getMessage());
                }
    		}
    		//return response()->json(['success'=>'Success!']);
    	}
    }
}
