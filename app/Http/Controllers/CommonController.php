<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Image; //Intervention Image
use Illuminate\Support\Facades\Storage; //Laravel Filesystem
use App\Repositories\User\User1Repository as User;
use App\Repositories\Customer\CustomerRepository as Customer;
use App\Repositories\Destination\DestinationRepository as Destination;

class CommonController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $country;
    private $user;
    private $customer;
    private $destination;

    public function __construct(User $user, Customer $customer, Destination $destination) {
        //$this->middleware('auth');
        parent::__construct();
        
        $this->user = $user;
        $this->customer = $customer;
        $this->destination = $destination;
    }

    public function loadModal(Request $request, $action, $id) {
        if ($action == 'upload_image') {
            return view('common.modal_create', ['type' => $id]);
        }
        if ($action == 'upload_video') {
            return view('common.modal_create_video', ['type' => $id]);
        }
       if($action == 'upload_file') {
           return view('common.modal_create_file',['type' => $id]);
       }
       if ($action == 'open_house') {
            return view('common.modal_create_openhouse', ['type' => $id]);
        }
       if($action == 'social_media') {
           return view('common.modal_create_socialmedia',['type' => $id]);
       }
//       if($action == 'update_state'){
//           $results = $this->state->find($id);           
//           return view('state.modal_update', ['data' => $results, 'countries'=>$data['countries']]);
//       }
    }
    
    public function _getFileName($name){
        $specialChar = [' ','@','#','$','~','%','&','^','*','(',')','+','=','{','}','[',';','"','\'','?','<','>','.',',','_'];
        foreach($specialChar as $char){
            $name = str_replace($char, '-', $name);
        }
        return strtolower($name);
    }
    public function deleteAllImageFromSession(Request $request, $module){
        $moduleConfig = config('constants.'.$module);
        $fullPathKey = $module.'_full_path';
        $sessionModuleKey = $module.'_images';
        if ($request->session()->has($sessionModuleKey)) {
          $imgArr = $request->session()->pull($sessionModuleKey);
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
        }
        $data['status'] = true;
        echo json_encode($data);
        //return;
        //die;
    }
    public function deleteImageFromSession(Request $request, $module, $id){
        $moduleConfig = config('constants.'.$module);
        $fullPathKey = $module.'_full_path';
        $sessionModuleKey = $module.'_images';
        if ($request->session()->has($sessionModuleKey)) {
          /* 
           var_dump( session($sessionModuleKey));
            $deleted = $request->session()->pull($sessionModuleKey.'.'.$id);
           unset(session($sessionModuleKey)[$id]);
           //var_dump( session($sessionModuleKey));
            $request->session()->forget($sessionModuleKey.'.'.$id);
var_dump( session($sessionModuleKey));die;*/
            //var_dump( session($sessionModuleKey));
            $imgArr = session($sessionModuleKey);
            $deleted = $imgArr[$id];
            unset($imgArr[$id]);
            $request->session()->forget($sessionModuleKey);
            session([$sessionModuleKey => $imgArr]);
            //var_dump( session($sessionModuleKey));
            //$deleted = $request->session()->pull($sessionModuleKey[$id]);
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
        $data['status'] = true;
        echo json_encode($data);
        //return;
        //die;
    }
    
    public function deleteFileFromSession(Request $request, $module, $id){
        $moduleConfig = config('constants.'.$module);
        $fullPathKey = $module.'_file_full_path';
        $sessionModuleKey = $module.'_file';
        if ($request->session()->has($sessionModuleKey)) {
          
            $imgArr = session($sessionModuleKey);
            //print_r($imgArr); die;
            $deleted = $imgArr[$id]['filename'];
            unset($imgArr[$id]);
            $request->session()->forget($sessionModuleKey);
            session([$sessionModuleKey => $imgArr]);
            
            $deletedImgArr = explode('/', $deleted);
            $filenamewithextension = end($deletedImgArr);
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
            $filenameExt = pathinfo($filenamewithextension, PATHINFO_EXTENSION);
            $sizes = array_keys($moduleConfig['sizes']);
            $delFile = $moduleConfig[$fullPathKey].$filenamewithextension;
            @unlink($delFile);
            //remove all size images
            
        }
        $data['status'] = true;
        echo json_encode($data);
        //return;
        //die;
    }
//image upload and stroe in session
    public function store(Request $request) {
        $uploadedImages = [];
        $post_data = $request->all();
        $moduleConfig = config('constants.'.$post_data['module_type']);
        $fullPathKey = $post_data['module_type'].'_full_path';
        $targetPath = $moduleConfig[$fullPathKey]; //$moduleConfig['destination_full_path'];
        $sessionModuleKey = $post_data['module_type'].'_images';
        //echo "<pre>"; print_r($moduleConfig); echo "</pre>"; die;
        if ($request->hasFile('uploaded_images')) {
            
            foreach ($request->file('uploaded_images') as $file) {
                //get filename with extension
                $filenamewithextension = $file->getClientOriginalName();

                //get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                $filename = $this->_getFileName($filename);
                $uniqId = uniqid();
                $filename = $filename . '_' . $uniqId;
                //get file extension
                $extension = $file->getClientOriginalExtension();
                //var_dump($extension, $filename);                die();
                //filename to store
                $filenametostore =  $filename. '.' . $extension;
                
                $original = Image::make($file->getRealPath()); 
                /*, function($constraint) {
                    $constraint->aspectRatio();
                }*/
                $original->save($targetPath . $filenametostore);
                
                foreach($moduleConfig['sizes'] as $type => $size) {
                    $newName = $filename . '_'.$type . '.'.$extension;
                    $background = Image::canvas($size[0], $size[1]);
                    $newImg = Image::make($file->getRealPath());
                    /*$resizeImage = $newImg->resize($size[0], null, function($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });*/
                    $resizeImage = $newImg->fit($size[0], $size[1], function($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $background->insert($resizeImage, 'center');
                    $newImg->save($targetPath . $newName);
                }
                $uploadedImages[$uniqId] = $moduleConfig[$post_data['module_type'].'_path'].$filenametostore;
        //profile->img = 'storage/uploads/'.$user_id.'/img/profile/'.$fName;
            }
            if(!empty($uploadedImages)){
                if ($request->session()->has($sessionModuleKey)) {
                    $alreadyUploaded = $request->session()->get($sessionModuleKey);
                    $uploadedAllImages = array_merge($alreadyUploaded, $uploadedImages);
                   //echo "<pre>"; print_r($request->session()->get($sessionModuleKey)); die('aa');
                }else{
                    $uploadedAllImages = $uploadedImages;
                }
                session([$sessionModuleKey => $uploadedAllImages]);
                $data['status'] = true;
                $data['images'] = $uploadedImages;
                $data['module'] = $post_data['module_type'];
                echo json_encode($data);
            }else{
                $data['status'] = false;
                $data['module'] = $post_data['module_type'];
                $data['msg'] = 'Invalid Data, Please try again.';
                echo json_encode($data);
            }
 //print_r($request->session()->all()); die;
            //return redirect('images')->with('status', "Image uploaded successfully.");
        }
    }
//video store in session
    public function vediodetails(Request $request){
        $post_data = $request->all();
         $video_type = $post_data['video_type'];
         $video_title = $post_data['video_title'];
         $video_description = $post_data['video_description'];
         $video_link = $post_data['video_link'];
         $module_type = $post_data['module_type'];
         $uniqId1 = uniqid();
         $destination[$uniqId1] = array('video_format'=>$video_type , 'video_title'=>$video_title , 'video_description'=>$video_description ,'video_link'=>$video_link );
         $moduleConfig = config('constants.'.$post_data['module_type']);
         $fullPathKey = $post_data['module_type'].'_full_path';
         $targetPath = $moduleConfig[$fullPathKey]; //$moduleConfig['destination_full_path'];
         $sessionModuleKey = $post_data['module_type'].'_videos_details';
         //var_dump(session($sessionModuleKey));//die;
         //var_dump($request->session()->has($sessionModuleKey));
         if($request->session()->has($sessionModuleKey)){
                $alreadyUploadedVedioDetails = $request->session()->get($sessionModuleKey);
                $uploadedvedioDetails = array_merge($alreadyUploadedVedioDetails, $destination);
            //    echo '<pre>';   print_r(array_merge($alreadyUploadedVedioDetails, $destination));  echo '</pre>'; die;
         } else {
             $uploadedvedioDetails = $destination;
         }
          //echo '<pre>';   print_r(array_merge($alreadyUploadedVedioDetails, $destination));  echo '</pre>'; die;
          session([$sessionModuleKey => $uploadedvedioDetails]);
          //$qq = session($sessionModuleKey);
         // echo '<pre>';   print_r($qq);  echo '</pre>'; die;
          $data['status'] = TRUE;
          $data['vedio'] = $destination;
          $data['module'] = $module_type;
          echo json_encode($data);
    }
    //open house store in session
    public function openhousedetails(Request $request){
        $post_data = $request->all();
         $house_title = $post_data['house_title'];
         $start_date = $post_data['start_date'];
         $start_time = $post_data['start_time'];
         $end_time = $post_data['end_time'];
         $notes = $post_data['notes'];
         $module_type = $post_data['module_type'];
         $uniqId1 = uniqid();
         $destination[$uniqId1] = array('house_title'=>$house_title , 'start_date'=>date('d F Y', strtotime($start_date)) , 
             'start_time'=>$start_time,'end_time'=>$end_time,'notes'=>$notes );
         $moduleConfig = config('constants.'.$post_data['module_type']);
         //$fullPathKey = $post_data['module_type'].'_full_path';
         //$targetPath = $moduleConfig[$fullPathKey]; //$moduleConfig['destination_full_path'];
         $sessionModuleKey = $post_data['module_type'].'_open_house';
         //var_dump(session($sessionModuleKey));//die;
         //var_dump($request->session()->has($sessionModuleKey));
         if($request->session()->has($sessionModuleKey)){
                $alreadyUploadedVedioDetails = $request->session()->get($sessionModuleKey);
                $uploadedvedioDetails = array_merge($alreadyUploadedVedioDetails, $destination);
            //    echo '<pre>';   print_r(array_merge($alreadyUploadedVedioDetails, $destination));  echo '</pre>'; die;
         } else {
             $uploadedvedioDetails = $destination;
         }
          //echo '<pre>';   print_r(array_merge($alreadyUploadedVedioDetails, $destination));  echo '</pre>'; die;
          session([$sessionModuleKey => $uploadedvedioDetails]);
          //$qq = session($sessionModuleKey);
         // echo '<pre>';   print_r($qq);  echo '</pre>'; die;
          $data['status'] = TRUE;
          $data['openhouse'] = $destination;
          $data['module'] = $module_type;
          echo json_encode($data);
    }
    //social media store in session
    public function socialmediadetails(Request $request){
        $post_data = $request->all();
         $social = $post_data['social'];
         $link = $post_data['link'];
         $module_type = $post_data['module_type'];
         $uniqId1 = uniqid();
         $destination[$uniqId1] = array('social'=>$social,'link'=>$link );
         $moduleConfig = config('constants.'.$post_data['module_type']);
         $sessionModuleKey = $post_data['module_type'].'_social_media';
         
         if($request->session()->has($sessionModuleKey)){
                $alreadyUploadedVedioDetails = $request->session()->get($sessionModuleKey);
                $uploadedvedioDetails = array_merge($alreadyUploadedVedioDetails, $destination);
         } else {
             $uploadedvedioDetails = $destination;
         }
         session([$sessionModuleKey => $uploadedvedioDetails]);
         
          $data['status'] = TRUE;
          $data['socialmedia'] = $destination;
          $data['module'] = $module_type;
          echo json_encode($data);
    }
    
     public function deleteVedioFromSession(Request $request ,$module ,$id){
         $sessionModuleKey = $module.'_videos_details';
         if($request->session()->has($sessionModuleKey)){
            $vedioArr = session($sessionModuleKey);
            unset($vedioArr[$id]);
            session([$sessionModuleKey => $vedioArr]);
         }
        $data['status'] = true;
        echo json_encode($data);
}
    //file upload and stroe in session
    public function fileDetails(Request $request){
        $uploadedFiles = [];
        $post_data = $request->all();
        $moduleConfig = config('constants.'.$post_data['module_type']);
        $fullPathKey = $post_data['module_type'].'_file_full_path';
        //echo "<pre>"; print_r($moduleConfig); echo "</pre>"; die;
        $targetPath = $moduleConfig[$fullPathKey]; //$moduleConfig['destination_full_path'];
        
        $sessionModuleKey = $post_data['module_type'].'_file';
        //echo "<pre>"; print_r($moduleConfig); echo "</pre>"; die;
        
        if ($request->hasFile('uploaded_file')) {
                //get filename with extension
                $filenamewithextension = $request->uploaded_file->getClientOriginalName();
                //get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                $orginalFileName = $filename;
                $filename = $this->_getFileName($filename);
                $uniqId = uniqid();
                $filename = $filename . '_' . $uniqId;
                //get file extension
                $extension = $request->uploaded_file->getClientOriginalExtension();
                //filename to store
                $filenametostore =  $filename. '.' . $extension;                          
                $request->uploaded_file->move($targetPath, $filenametostore);              
                
               
                $uploadedFiles[$uniqId] = ['filename'=>$moduleConfig[$post_data['module_type'].'_file_path'].$filenametostore,
                    'original_file_name'=>$orginalFileName];
                                
            if(!empty($uploadedFiles)){
                if ($request->session()->has($sessionModuleKey)) {
                    $alreadyUploaded = $request->session()->get($sessionModuleKey);
                    $uploadedAllFiles = array_merge($alreadyUploaded, $uploadedFiles);
                }else{
                    $uploadedAllFiles = $uploadedFiles;
                }
                session([$sessionModuleKey => $uploadedAllFiles]);
                $data['status'] = true;
                $data['file'] = $uploadedFiles;
                $data['file_name'] = $orginalFileName;
                $data['module'] = $post_data['module_type'];
                echo json_encode($data);
            }else{
                $data['status'] = false;
                $data['module'] = $post_data['module_type'];
                $data['msg'] = 'Invalid Data, Please try again.';
                echo json_encode($data);
            }
        }
  }
    public function deleteSocialFromSession(Request $request ,$module ,$id){
         $sessionModuleKey = $module.'_social_media';
         if($request->session()->has($sessionModuleKey)){
            $vedioArr = session($sessionModuleKey);
            unset($vedioArr[$id]);
            session([$sessionModuleKey => $vedioArr]);
         }
        $data['status'] = true;
        echo json_encode($data);
    }
    public function deleteOpenHouseFromSession(Request $request ,$module ,$id){
         $sessionModuleKey = $module.'_open_house';
         if($request->session()->has($sessionModuleKey)){
            $vedioArr = session($sessionModuleKey);
            unset($vedioArr[$id]);
            session([$sessionModuleKey => $vedioArr]);
         }
        $data['status'] = true;
        echo json_encode($data);
    }

    public function update(Request $request){
        if ($request->isMethod('post')) {
            $post_data = $response = [];
            $post_data = $request->all();

            if($post_data){
                $this->{$post_data['controller']}->update([$post_data['field'] => $post_data['value']], $post_data['listing_id']);
                
                switch($post_data['controller']){
                    case 'user': 
                        $detail = $this->{$post_data['controller']}->with(['userDetail', 'userRole'])->find($post_data['listing_id']);

                        /*if($detail->userRole->role_id == 3)
                            $message = 'Agency - <b>' . ucwords($detail->userDetail->agency_name) . '</b> ';
                        else*/
                            $message = 'User - <b>' . ucwords($detail->first_name . ' ' . $detail->last_name) . '</b> ';                            
                        break;
                    default: 
                        $detail = $this->{$post_data['controller']}->find($post_data['listing_id']);
                    
                        $message = ucwords($post_data['controller']) . ' - <b>' . ucwords($detail->name) . '</b> ';
                }
                
                if($post_data['field'] == 'featured'){
                    $message .= ($post_data['value'] == 1) ? 'marked as featured.' : 'unmarked as featured.';
                }elseif($post_data['field'] == 'active'){
                    $message .= ($post_data['value'] == 1) ? 'is activated.' : 'is deactivated.';
                }
                
                $response['success'] = true;
                $response['message'] = $message;
            }else{
                $response['success'] = false;
                $response['message'] = 'Something went wrong please try again';
            }
            
            return response()->json($response);
          }
    }
}