<?php
namespace App\Traits;
use App\User;
use DB;
use Auth;
use Image;
use Storage;

trait TinyPngImageCompress {

    /**
     * Localize a date to users timezone
     *
     * @param null $dateField
     * @return Carbon
     */
    public function compressToS3($image,$foldername, $filename)
    {
        $relPath = 'images/uploads/tiny/';
            if (!file_exists(public_path($relPath))) {
                mkdir(public_path($relPath), 777, true);
            }
            Image::make($image)->save( public_path($relPath . $filename ) );
            $filepath = public_path($relPath . $filename );
        try {
            \Tinify\setKey("tJ62FVVM14GGLVGsDZkcs6wNXLTxlhy4"); 
            $source = \Tinify\fromFile($filepath);
            $source->toFile($filepath);
            $filePathN= url('images/uploads/tiny/'. $filename );
            $img = file_get_contents('https://adm.dookinternational.com/images/uploads/tiny/'.$filename);
            $base64String = base64_encode($img);
            $images = base64_decode($base64String);
            //dd($images);
            Storage::disk('spaces')->put($foldername.'/'.$filename, $images, 'public');
            // $source->store(array(
            //     "service" => "s3",
            //     "aws_access_key_id" => env('TINIFY_AWS_ACCESS_KEY_ID'),
            //     "aws_secret_access_key" => env('TINIFY_AWS_SECRET_ACCESS_KEY'),
            //     "region" => "us-west-2",
            //     "headers" => array("Cache-Control" => "max-age=31536000, public"),
            //     "path" => 's3-pullit-bucket/'.$foldername.'/'.$filename
            // ));
            //$image_paths = public_path('images/uploads/tiny/'.$filename);
            if(file_exists($filepath))
            {
                unlink($filepath);
            }  
        } catch(\Tinify\AccountException $e) {
               $this->directImageSaveInS3($image,$foldername, $filename);
                //return redirect('images/create1')->with('error', $e->getMessage());
            } catch(\Tinify\ClientException $e) {
                $this->directImageSaveInS3($image,$foldername, $filename);
                //return redirect('images/create')->with('error', $e->getMessage());
            } catch(\Tinify\ServerException $e) {
                $this->directImageSaveInS3($image,$foldername, $filename);
                //return redirect('images/create')->with('error', $e->getMessage());
            } catch(\Tinify\ConnectionException $e) {
                $this->directImageSaveInS3($image,$foldername, $filename);
                //return redirect('images/create')->with('error', $e->getMessage());
            } catch(Exception $e) {
                $this->directImageSaveInS3($image,$foldername, $filename);
                //return redirect('images/create')->with('error', $e->getMessage());
            }
    }


    function directImageSaveInS3($image,$foldername, $filename){
        //$imageFile = Image::make($image)->stream();
        //$imageFile = $imageFile->__toString();
        Storage::disk('spaces')->putFileAs($foldername, $image, $filename, 'public');
    }

    public function compressToLocal($image,$foldername, $filename)
    {

        $relPath = 'images/uploads/tiny/';
            if (!file_exists(public_path($relPath))) {
                mkdir(public_path($relPath), 777, true);
            }
            Image::make($image)->save( public_path($relPath . $filename ) );
            $filepath = public_path($relPath . $filename );
        try {
            //$filepath = public_path('storage/profile_images/'.$filename); 
            $filePathN= public_path($foldername.'/'. $filename );
            \Tinify\setKey("tJ62FVVM14GGLVGsDZkcs6wNXLTxlhy4");
            $source = \Tinify\fromFile($filepath);
            $source->toFile($filePathN);

            if(file_exists($filepath))
            {
                unlink($filepath);
            }  
        } catch(\Tinify\AccountException $e) {
               $this->directImageSaveInLocal($image,$foldername, $filename);
                //return redirect('images/create1')->with('error', $e->getMessage());
        } catch(\Tinify\ClientException $e) {
            $this->directImageSaveInLocal($image,$foldername, $filename);
            //return redirect('images/create')->with('error', $e->getMessage());
        } catch(\Tinify\ServerException $e) {
            $this->directImageSaveInLocal($image,$foldername, $filename);
            //return redirect('images/create')->with('error', $e->getMessage());
        } catch(\Tinify\ConnectionException $e) {
            $this->directImageSaveInLocal($image,$foldername, $filename);
            //return redirect('images/create')->with('error', $e->getMessage());
        } catch(Exception $e) {
            $this->directImageSaveInLocal($image,$foldername, $filename);
            //return redirect('images/create')->with('error', $e->getMessage());
        }
    }

    function directImageSaveInLocal($image,$foldername, $filename){
        Image::make($image)->save( public_path($foldername.'/'. $filename )); 
    }
}
