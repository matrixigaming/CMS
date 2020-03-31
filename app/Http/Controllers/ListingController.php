<?php 

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\ListingPostRequest;
use App\Repositories\Listing\ListingRepository as Listing;
use App\Repositories\Country\CountryRepository as Country;
use App\Repositories\Category\CategoryRepository as Category;
use Illuminate\Support\Facades\Storage; //Laravel Filesystem
use App\Repositories\ListingImage\ListingImageRepository as ListingImage;
use App\Repositories\ListingCategory\ListingCategoryRepository as ListingCategory;
use App\Repositories\ListingFile\ListingFileRepository as ListingFile;
use App\Repositories\ListingOpenhouse\ListingOpenhouseRepository as ListingOpenhouse;
use App\Repositories\ListingSocialMedia\ListingSocialMediaRepository as ListingSocialMedia;
use App\Repositories\ListingVideo\ListingVideoRepository as ListingVideo;
use App\Repositories\User\UserRepository as User;
use Auth;
//use Config;
use Excel;

class ListingController extends Controller
{
   
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $listing;
    private $country;
    private $category;
    private $listingImage;
    private $listingCategory;
    private $listingFile;
    private $listingOpenhouse;
    private $listingSocialMedia;
    private $listingVideo;
    private $user;
    public function __construct(Listing $listing, Country $country, Category $category, 
            ListingImage $listingImage, ListingCategory $listingCategory, 
            ListingFile $listingFile, ListingOpenhouse $listingOpenhouse,
            ListingSocialMedia $listingSocialMedia, ListingVideo $listingVideo,
            User $user)
    {
        $this->middleware('auth');
        $this->listing = $listing;
        $this->country = $country;
        $this->category = $category;
        $this->listingImage = $listingImage;
        $this->listingCategory = $listingCategory;
        $this->listingFile = $listingFile;
        $this->listingOpenhouse = $listingOpenhouse;
        $this->listingSocialMedia = $listingSocialMedia;
        $this->listingVideo = $listingVideo;
        $this->user = $user;
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function listing(){
         $result = $this->listing->with(['country', 'state'])->all();  
         //echo "<pre>"; print_r($result); die;
         return view('listing.listing', ['data' => $result]);
    }
    public function add(Request $request){   
         //$request->session()->forget('destination_images');
         $data['countries'] = $this->country->all();
         $data['categories'] = $this->category->all();
         $data['agents'] = $this->user->getAgentList();
         //$data['images'] = $request->session()->get('destination_images');
         return view('listing.add', ['data' => $data]);
    }
    public function edit($id){     
        $data['countries'] = $this->country->all();
        $data['categories'] = $this->category->all();
        $data['listing'] = $this->listing->with(
                ['listingCategories','listingImages', 'listingVideos', 'listingFiles','listingSocialMedia', 'listingOpenhouse'])
                ->find($id);//->toArray();
        /*$cats = array_map(function($a){
            return $a->category_id;
        }, $data['listing']->listing_categories);*/
        $data['listing_category'] = array();
        if(!empty($data['listing']->listingCategories)){
            foreach($data['listing']->listingCategories as $lc){
                $data['listing_category'][] = $lc->category_id;
            }
        }
        
        $countryId = $data['listing']->country_id;
        $data['country_detail'] = $this->country->with(['states','destinations'])->find($countryId);
        $data['agents'] = $this->user->getAgentList();
        //echo "<pre>"; print_r($data); die;
        return view('listing.edit', ['data' => $data]);
    }
    public function loadModal(Request $request, $action, $id){
        if($action == 'create_listing'){  
           return view('department.modal_create');        
       }
       if($action == 'update_listing'){
           $results = $this->listing->find($id);           
           return view('listing.modal_update', ['data' => $results]);
       }
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
//                    if($image->is_default){
//                        $response['status'] = false;
//                        $response['msg'] = 'You can not delete default image.';
//                    }else{
                        $deleted = $image->image_path;
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
                        $this->listingImage->delete($id);  
                        $response['status'] = true;
                    //}
                //echo "<pre>"; print_r($image->is_default); die;
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
    public function create(ListingPostRequest $request){
        if ($request->isMethod('post')) {
            $post_data = array();
            $post_data = $request->all();
            //echo "<pre>"; print_r($post_data); die;
            $imgArr = $request->session()->pull('listing_images');
            $videosArr = $request->session()->pull('listing_videos_details');
            $filesArr = $request->session()->pull('listing_file');
            $openHouseArr = $request->session()->pull('listing_open_house');
            $socialArr = $request->session()->pull('listing_social_media');
            
            $default_image_from = isset($post_data['default_image_from']) ? $post_data['default_image_from'] : '';
            //if(isset($imgArr) && !empty($imgArr)){
                if(isset($post_data['default_image']) && !empty($post_data['default_image'])){
                    $defultImage = $post_data['default_image'];
                }else{
                    $defultImage = isset($imgArr) && !empty($imgArr)? array_keys($imgArr)[0] : 0;
                }
            //}
            $callFrom = isset($post_data['call_from']) && $post_data['call_from']=='frontend'?$post_data['call_from']:'';            unset($post_data['default_image_from']);
            unset($post_data['default_image']);
            unset($post_data['_token']);
            unset($post_data['call_from']);
            $listingConfig = config('constants.listing');
            //$listingConfig = Config::get('constants.listing');
            //echo "<pre>"; print_r($listingConfig); echo "</pre>"; die;
            if ($request->hasFile('audio_file')) {
                //get filename with extension
                $filenamewithextension = $request->audio_file->getClientOriginalName();

                //get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                $filename = $this->_getFileName($filename);
                $uniqId = uniqid();
                $filename = $filename . '_' . $uniqId;
                //get file extension
                $extension = $request->audio_file->getClientOriginalExtension();

                //filename to store
                $filenametostore =  $filename. '.' . $extension;
                
                $destinationAudioPath = $listingConfig['listing_audio_full_path'];
                
                $a = $request->audio_file->move($destinationAudioPath, $filenametostore);
               // echo "<pre>"; var_dump($a); echo "</pre>";
                //$request->audio_file->save();
                $post_data['audio_tour'] = $filenametostore;
            }
            //echo "<pre>"; print_r($post_data); echo "</pre>"; die;
            $id = $post_data['id'];
            $postCategories = $post_data['categories'];
            unset($post_data['categories']);
            $listingCats = array();
            $post_data['banner_homepage'] = isset($post_data['banner_homepage'])?$post_data['banner_homepage']:0;
            $post_data['featured'] = isset($post_data['featured'])?$post_data['featured']:0;
            
            if(isset($id) && !empty($id)){
                $response = $this->listing->update($post_data, $id);
                $dImgs = array('is_default'=>0); 
                $dImgsRes1 = $this->listingImage->update($dImgs, $id, 'listing_id');
                if($default_image_from=='db'){
                    $dImgs2 = array('is_default'=>1); 
                    $dImgsRes2 = $this->listingImage->update($dImgs2, $defultImage);
                }
                $lastInsertId = $id;
                $cats = $this->listing->with(['listingCategories'])->find($lastInsertId)->toArray();
                $listingCats = array_map(function($a){
                            return $a['category_id'];
                        }, $listingCats);
                $deleted = $this->listing->deleteListingCategoryByLisitngId($lastInsertId);
            }else{
                $response = $this->listing->create($post_data);
                $lastInsertId = $response->id;
            }
            //var_dump($response, $response->id); die;
            if($lastInsertId){
                if(!empty($imgArr)){
                    foreach($imgArr as $k=>$v){
                        $isDefault = isset($defultImage) && $k==$defultImage ? 1 : 0;
                        $dImgs = array('listing_id'=>$lastInsertId, 'image_path'=>$v, 'is_default'=>$isDefault); 
                        $dImgsRes = $this->listingImage->create($dImgs);
                    }
                }
        
                if(!empty($postCategories)){
                    foreach($postCategories as $k=>$v){
                        if(in_array($v, $listingCats)) continue;
                        $dImgs = array('listing_id'=>$lastInsertId, 'category_id'=>$v); 
                        $dImgsRes = $this->listingCategory->create($dImgs);
                    }
                }
                
                if(!empty($videosArr)){
                    foreach($videosArr as $k=>$v){                        
                        $dImgs = array('listing_id'=>$lastInsertId, 
                            'video_format'=>$v['video_format'], 
                            'video_title'=>$v['video_title'],
                            'video_description'=>$v['video_description'],
                            'video_link'=>$v['video_link']); 
                        $dImgsRes = $this->listingVideo->create($dImgs);
                    }
                }
                if(!empty($filesArr)){
                    foreach($filesArr as $k=>$v){
                        $dImgs = array('listing_id'=>$lastInsertId, 
                            'file_name'=>$v['original_file_name'], 
                            'file_path'=>$v['filename']); 
                        $dImgsRes = $this->listingFile->create($dImgs);
                    }
                }
                if(!empty($socialArr)){
                    foreach($socialArr as $k=>$v){                        
                        $dImgs = array('listing_id'=>$lastInsertId, 
                            'social'=>$v['social'], 
                            'link'=>$v['link']); 
                        $dImgsRes = $this->listingSocialMedia->create($dImgs);
                    }
                }
                
                if(!empty($openHouseArr)){
                    foreach($openHouseArr as $k=>$v){                        
                        $dImgs = array('listing_id'=>$lastInsertId, 
                            'house_title'=>$v['house_title'], 
                            'start_date'=>date('Y-m-d H:i:s', strtotime($v['start_date'].' '. $v['start_time'])),
                            'end_date'=>date('Y-m-d H:i:s', strtotime($v['start_date'].' '. $v['end_time'])),
                            'notes'=>$v['notes']); 
                        $dImgsRes = $this->listingOpenhouse->create($dImgs);
                        $dImgs = [];
                    }
                }
                $resp['status'] = 1;
                $resp['msg'] = 'Listing created successfully.';
            }else if($response){
                $resp['status'] = 1;
                $resp['msg'] = 'Listing updated successfully.';
            }else{
                $resp['status'] = 0;
                $resp['msg'] = 'Invalid Data, Please try again.';
            }
            if($callFrom=='frontend'){
                echo json_encode($resp);   
            }else{
                return redirect('/listings/list')->with('status', $resp['msg']);
            }
            
            //echo "<pre>"; print_r($response); die;
            //echo json_encode($resp);   
            //echo \Response::json($response);
            //die;
          }
    }    
    
    public function addproperty(){
        $currentUser = Auth::User();

        $data['user_id'] = $currentUser->id;
        if($currentUser->hasRole('Agency')){
            $userData = $this->user->getUser($currentUser->id);
            $data['agents'] = $userData['agents_detail'];
            $data['user_role_type'] = 'Agency';
            $data['agency_type'] = $userData['user_detail']['agency_type'];
            $data['thisAgencyAgents'] = [];
            if(!empty($data['agents'])){
                foreach($data['agents'] as $agnt){
                   $data['thisAgencyAgents'][$agnt['id']] =  $agnt['first_name'].' '. $agnt['last_name'];
                }
            }
        }else{
            $data['user_role'] = 'Agent';
        }
        //echo "<pre>"; print_r($data['agents']); echo "</pre>"; die;
        $data['countries'] = $this->country->all();
        $data['categories'] = $this->category->all();
         
//         echo "<pre>"; print_r($data); echo "</pre>"; die;
         return view('properties.addproperty', ['data' => $data]);
    }
    
    public function editproperty($id){
        $currentUser = Auth::User();

        $data['user_id'] = $currentUser->id;
        if($currentUser->hasRole('Agency')){
            $userData = $this->user->getUser($currentUser->id);
            $data['agents'] = $userData['agents_detail'];
            $data['user_role_type'] = 'Agency';
            $data['agency_type'] = $userData['user_detail']['agency_type'];
            $data['thisAgencyAgents'] = [];
            if(!empty($data['agents'])){
                foreach($data['agents'] as $agnt){
                   $data['thisAgencyAgents'][$agnt['id']] =  $agnt['first_name'].' '. $agnt['last_name'];
                }
            }
        }else{
            $data['user_role'] = 'Agent';
        }
        //echo "<pre>"; print_r($data['agents']); echo "</pre>"; die;
        $data['countries'] = $this->country->all();
        $data['categories'] = $this->category->all();
        $data['listing'] = $this->listing->with(
                ['listingCategories','listingImages', 'listingVideos', 'listingFiles','listingSocialMedia', 'listingOpenhouse'])
                ->find($id);//->toArray();
        
        $data['listing_category'] = array();
        if(!empty($data['listing']->listingCategories)){
            foreach($data['listing']->listingCategories as $lc){
                $data['listing_category'][] = $lc->category_id;
            }
        }
        
        $countryId = $data['listing']->country_id;
        $data['country_detail'] = $this->country->with(['states','destinations'])->find($countryId); 
        //echo "<pre>"; print_r($data); echo "</pre>"; die;
         return view('properties.editproperty', ['data' => $data]);
    }
    
    public function addimages()
    {
        $currentUser = Auth::User();
         
        return view('properties.add_property_form.addimages');
    }
    public function addvideo()
    {         
        return view('properties.add_property_form.addvideo');
    }
    public function uploadimages()
    {
        
    }
    public function export()
    {
        $data = $this->listing->with(['country', 'state', 'destination', 'uploaderInfo'])->all();

        $listings = [];
        
        if($data){
            foreach($data->toArray() as $listing){
                $listings[] = [
                    'Name' => $listing['name'],
                    'Street Address 1' => $listing['street_address_1'],
                    'Street Address 2' => $listing['street_address_2'],
                    'Country' => isset($listing['country']['name']) ? $listing['country']['name'] : '',
                    'State' => isset($listing['state']['state_name']) ? $listing['state']['state_name'] : '',
                    'City' => isset($listing['destination']['name']) ? $listing['destination']['name'] : '',
                    'Zip Code' => $listing['zip_code'],
                    'Property Type' => $listing['property_type'],
                    'Sale Type' => $listing['sale_type'],
                    'Agent Name' => isset($listing['uploader_info']['first_name'], $listing['uploader_info']['last_name']) ? $listing['uploader_info']['first_name'] . ' ' . $listing['uploader_info']['last_name'] : ''
                ];
            }
        }

        \Excel::create('Listing-Requests-' . date('dmy'), function($excel) use($listings) {
            $excel->sheet('Listings', function($sheet) use($listings) {
                $sheet->fromArray($listings);
            });
        })->download('xls');
    }
    public function addsocialmedia()
    {    
        return view('properties.add_property_form.addsocialmedia');
    }
    public function addopenhouse()
    {    
        return view('properties.add_property_form.addopenhouse');
    }
}