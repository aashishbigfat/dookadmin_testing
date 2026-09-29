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
    // compressToS3() and directImageSaveInS3() lived here. Both wrote to
    // Storage::disk('spaces') - DigitalOcean Spaces, now retired - and neither
    // was called from anywhere: compressToS3() had no callers, and
    // directImageSaveInS3() was only reached from its catch blocks.

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
