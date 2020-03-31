<?php 

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\ShopVideoPostRequest;
use App\Repositories\ShopVideo\ShopVideoRepository as ShopVideo;
use Illuminate\Support\Facades\Storage; //Laravel Filesystem
use Auth;

class ShopVideoController extends Controller
{
   
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $shopVideo;
    public function __construct(ShopVideo $shopVideo)
    {
        $this->middleware('auth');
        $this->shopVideo = $shopVideo;
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getShopVideo(Request $request, $id){
        $data = $this->shopVideo->findBy('user_id',$id); 
        if(!empty($data)){
            $videoName = config('constants.shop_video_path').$data->video_name;
            $returnData['status'] = true;
            $returnData['id'] = $data->id;
            $returnData['video_name'] = $videoName;
        }else{
            $returnData['status'] = false;
        }
        
        return $returnData;
    }

    public function deletedata(Request $request ,$type ,$id){
        $moduleConfig = config('constants.listing');
        $response = array();
        $response['status'] = false;
        $response['msg'] = 'Invalid selection.';
        switch($type){
            case 'delete_all_images':
                    $fullPathKey = 'listing_full_path';
                    if($id){
                        $allImages = $this->listingImage->findAllBy('listing_id',$id)->toArray(); 
                        if(!empty($allImages)){
                            foreach($allImages as $image){
                                $deleted = $image['image_path'];
                                $deletedImgArr = explode('/', $deleted);
                                $filenamewithextension = end($deletedImgArr);
                                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                                $filenameExt = pathinfo($filenamewithextension, PATHINFO_EXTENSION);
                                $sizes = array_keys($moduleConfig['sizes']);
                                $delFile = $moduleConfig[$fullPathKey].$filenamewithextension;
                                @unlink($delFile);
                                //remove all size images
                                foreach($sizes as $val){
                                    $delFile = $moduleConfig[$fullPathKey].$filename.'_'.$val.'.'.$filenameExt;
                                    @unlink($delFile);
                                }
                                $this->listingImage->delete($image['id']);
                            }
                        }
                    }
                    $imgArr = $request->session()->pull('listing_images');
                    if(!empty($imgArr)){
                        foreach($imgArr as $k=>$v){
                            $deleted = $v;
                            $deletedImgArr = explode('/', $deleted);
                            $filenamewithextension = end($deletedImgArr);
                            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                            $filenameExt = pathinfo($filenamewithextension, PATHINFO_EXTENSION);
                            $sizes = array_keys($moduleConfig['sizes']);
                            $delFile = $moduleConfig[$fullPathKey].$filenamewithextension;
                            @unlink($delFile);
                            //remove all size images
                            foreach($sizes as $val){
                                $delFile = $moduleConfig[$fullPathKey].$filename.'_'.$val.'.'.$filenameExt;
                                @unlink($delFile);
                            }
                        }
                    }
                    $response['status'] = true;
                break;
            case 'delete_image':
                    $fullPathKey = 'listing_full_path';
                    $image = $this->listingImage->find($id);  
                        $deleted = $image->image_path;
                        $deletedImgArr = explode('/', $deleted);
                        $filenamewithextension = end($deletedImgArr);
                        $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                        $filenameExt = pathinfo($filenamewithextension, PATHINFO_EXTENSION);
                        $sizes = array_keys($moduleConfig['sizes']);
                        $delFile = $moduleConfig[$fullPathKey].$filenamewithextension;
                        @unlink($delFile);
                        foreach($sizes as $val){
                            $delFile = $moduleConfig[$fullPathKey].$filename.'_'.$val.'.'.$filenameExt;
                            @unlink($delFile);
                        }
                        $this->listingImage->delete($id);  
                        $response['status'] = true;
                break;
            case 'delete_vedio':
                        $this->listingVideo->delete($id);
                        $response['status'] = true;
                break;
            case 'delete_file':
                        $fullPathKey = 'listing_file_full_path';
                        $file = $this->listingFile->find($id);
                        $deleted = $file->file_path;
                        $deletedImgArr = explode('/', $deleted);
                        $filenamewithextension = end($deletedImgArr);
                        $delFile = $moduleConfig[$fullPathKey].$filenamewithextension;
                        @unlink($delFile);
                        $this->listingFile->delete($id);
                        $response['status'] = true;
                break;
            case 'delete_social_media':
                        $this->listingSocialMedia->delete($id);
                        $response['status'] = true;
                break;
        }
        echo json_encode($response);
    }
    private function _getFileName($name){
        $specialChar = [' ','@','#','$','~','%','&','^','*','(',')','+','=','{','}','[',';','"','\'','?','<','>','.',',','_'];
        foreach($specialChar as $char){
            $name = str_replace($char, '-', $name);
        }
        return strtolower($name);
    }
    public function set_tv_video(ShopVideoPostRequest $request){
        if ($request->isMethod('post')) {
            $post_data = array();
            $post_data = $request->all();
            $destinationVideoPath = config('constants.shop_video_full_path');
            if ($request->hasFile('video_name')) {
                //get filename with extension
                $filenamewithextension = $request->video_name->getClientOriginalName();
                //get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                $filename = $this->_getFileName($filename);
                $uniqId = uniqid();
                $filename = $filename . '_' . $uniqId;
                //get file extension
                $extension = $request->video_name->getClientOriginalExtension();

                //filename to store
                $filenametostore =  $filename. '.' . $extension;                
                $a = $request->video_name->move($destinationVideoPath, $filenametostore);
                $post_data['video_name'] = $filenametostore;
            }
            //echo "<pre>"; print_r($post_data); echo "</pre>"; die;
            $id = $post_data['id'];
            
            unset($post_data['_token']);
            if(isset($id) && !empty($id)){
                $response = $this->shopVideo->update($post_data, $id);                
                $lastInsertId = $id;
                $resp['msg'] = 'Video updated successfully.';
            }else{
                $response = $this->shopVideo->create($post_data);
                $lastInsertId = $response->id;
                $resp['msg'] = 'Video added successfully.';
            }
            //var_dump($response, $response->id); die;
            if($lastInsertId){
                $resp['status'] = 1;                
            }else{
                $resp['status'] = 0;
                $resp['msg'] = 'Invalid Data, Please try again.';
            }
            echo json_encode($resp); die;  
            
          }
    }    
}