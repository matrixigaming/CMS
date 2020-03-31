<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\UserPostRequest;
use App\Repositories\User\User1Repository as User1;
use App\Repositories\User\UserRepository as User;
use App\Repositories\Role\RoleRepository as Role;
use App\Repositories\Country\CountryRepository as Country;
use App\Repositories\UserImage\UserImageRepository as UserImage;
use App\Repositories\UserFile\UserFileRepository as UserFile;
use App\Repositories\UserSocialMedia\UserSocialMediaRepository as UserSocialMedia;
use App\Repositories\UserVideo\UserVideoRepository as UserVideo;
use App\Repositories\UserDetail\UserDetailRepository as UserDetail;
use App\Repositories\Destination\DestinationRepository as Destination;
use App\Repositories\UserContact\UserContactRepository as UserContact;
use App\Repositories\UserAgency\UserAgencyRepository as UserAgency;
use Auth;
use Validator;
use Hash;
use Helper;

class UserController extends Controller
{
    private $user1;
    private $user;
    private $role;
    private $country;
    
    private $userImage;
    private $userFile;
    private $userSocialMedia;
    private $userVideo;
    private $userDetail;
    private $destination;
    private $usercontact;
    private $userAgency;
    public function __construct(User1 $user1, User $user, Role $role, Country $country, 
            UserImage $userImage, UserFile $userFile, UserDetail $userDetail,
            UserSocialMedia $userSocialMedia, UserVideo $userVideo, Destination $destination,
            UserContact $usercontact, UserAgency $userAgency) {
        $this->middleware('auth', ['except' => ['lenderdetail','contactlender', 'agentlisting', 'agentdetail', 'agencylisting', 'agencydetail','userLogin','signupPost', 'showVideo', 'contact']]);
        $this->user1 = $user1;
        $this->user = $user;
        $this->role = $role;
        $this->country = $country;
        $this->userImage = $userImage;
        $this->userFile = $userFile;
        $this->userSocialMedia = $userSocialMedia;
        $this->userVideo = $userVideo;
        $this->userDetail = $userDetail;
        $this->destination = $destination;
        $this->usercontact = $usercontact;
        $this->userAgency = $userAgency;
        parent::__construct();
    }
    /*public function index(){
        $data['users'] = $this->userbj->all();
        echo "<pre>"; print_r($data); die;
        return view('user.index', ['data' => $data]);
    }*/
    public function userList(){
        //$data = $this->user1->all();
        $data = $this->user->getAdminList();
        //echo "<pre>"; print_r($data); die;
        return view('user.list', ['data' => $data]);
    }
    public function add(){ 
        $data['roles'] = $this->role->all();
        return view('user.add', ['data' => $data]);
    }
    public function edit($id){        
        
        $data['roles'] = $this->role->all();
        $data['user_data'] = $this->user1->find($id);        
        
        $userRole = $this->user1->getUserRoleId($id);
        $data['user_role_id'] = $userRole->role_id;
        
        //echo "<pre>"; print_r(count($data['user_data']->userTool)); die;
        return view('user.edit', ['data' => $data]);
    }

    public function addPost(UserPostRequest $request){
        if($request->isMethod('post')){
            $post_data = $request->all();
            
            $id = $post_data['id'];
            
            $post_data['role_id'] = $post_data['role_id'] ? $post_data['role_id'] : 4;
            $role_id = $post_data['role_id'];
            
            if(isset($id) && !empty($id)){                
                           
                unset($post_data['_token']);
                unset($post_data['role_id']);
                $userRole = $this->user1->getUserRoleId($id);
                $currentRoleId = $userRole->role_id;
                if(empty($post_data['password'])){
                    unset($post_data['password']);
                }else{
                    $post_data['password'] = bcrypt($post_data['password']);
                }
                $response = $this->user1->update($post_data, $id);
                //echo "<pre>"; print_r($response); echo "</pre>"; die;
                if($response){
                    if($role_id != $userRole->role_id){
                        $user = $this->user1->find($id);
                        $roleData = $this->role->find($userRole->role_id);
                        $user->detachRole($roleData);
                        $roleData = $this->role->find($role_id);
                        $user->attachRole($roleData);
                    }
                    
                    return redirect('/user/list')->with('message', 'User updated successfully.');
                    }
            }else{
                $response = $this->user1->createUser($post_data, $this->role);
                if(isset($response->id) && !empty($response->id)){ //if(isset($response->id) && !empty($response->id))              
                   $id = $response->id;
                    return redirect('/user/list')->with('message', 'User created successfully.');
                }else{
                    return redirect('/user/list')->with('message', 'Something went wrong, please try again.');
                }
            }
            //$reponse = $this->user1->createUser($post_data, $this->role);
            //$newUserId = isset($reponse['id']) && !empty($reponse['id']) ? $reponse['id'] : 0;
            
        }
    }

    public function loadModal(Request $request, $action, $id){
        if($action == 'update_credit'){
            $loggedInUser = Auth::user();
            $isAdmin = $loggedInUser->hasRole(['Admin']); 
            $userData = $this->user1->find($id);
            return view('distributor.modal_update_credit', ['data' => $userData, 'isAdmin'=>$isAdmin, 'loggedInUser'=>$loggedInUser]);        
        }
        if($action == 'create_user'){
           $data['roles'] = $this->role->all();
           return view('user.modal_create', ['data' => $data]);        
        }
        if($action == 'update_user'){  
           $data['roles'] = $this->role->all();
           $data['user']= $this->user1->find($id);
           $userRole = $this->user1->getUserRoleId($id);
           $data['user_role_id'] = $userRole->role_id;
           //$user = Auth::user();
           //echo "<pre>"; print_r($userRole->role_id); die;
           return view('user.modal_update', ['data' => $data]);
        }         
        if($action == 'manage_tv_video'){
            $user = Auth::user();
            $hasDistributor = $user->hasRole(['Distributor']); 
            $data = $this->user->getShopList($hasDistributor, $user->id);
            return view('shop.modal_manage_tv_video', ['shop_list' => $data]);        
        }
    }
    
    public function create(UserPostRequest $request){
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $post_data = array();
            $post_data = $request->all();
            
            //echo "<pre>"; print_r($post_data); echo "</pre>"; die;
            $id = $post_data['id'];
            unset($post_data['_token']);
           
            
            if(isset($id) && !empty($id)){
                $role_id = $post_data['role_id'];
                unset($post_data['role_id']);
                $response = $this->user1->update($post_data, $id);
            }else{
                $response = $this->user1->createUser($post_data, $this->role);
            }
            //echo "<pre>"; print_r($response); echo "</pre>"; die;
            if(isset($response->id) && !empty($response->id)){
                $resp['status'] = 1;
                $resp['msg'] = 'User created successfully.';
                $id = $response->id;
            }else if($response){
                $resp['status'] = 1;
                $resp['msg'] = 'User updated successfully.';
            }else{
                $resp['status'] = 0;
                $resp['msg'] = 'Invalid Data, Please try again.';
            }
            //echo "<pre>"; print_r($response); die;
            echo json_encode($resp);   
            //echo \Response::json($response);
            die;
          }
    }
    
    public function profile(Request $request){
        $user = Auth::user();
        $userId = $user->id;
        if ($request->isXmlHttpRequest()) {
            
            //return view('user.user_timeline_ajax', ['data' => $userData])->render();
        }
       
        /*
         * $user_message_reports = $this->message_report->get_user_messages($userId);
        $user_notification_reports = $this->notification_report->get_user_notification($userId);
        //echo "<pre>"; print_r($user_notification_reports); die;
        $timeline = array();
        $i = 0;
        foreach($user_message_reports as $msg){
            $tempDate = date('Y-m-d', strtotime($msg->created_at));
            $labelDate = strtotime($tempDate); 
            $timeline[$labelDate][$i]['type'] = 'Message';
            $timeline[$labelDate][$i]['id'] = $msg->id;
            $timeline[$labelDate][$i]['updated_at'] = $msg->updated_at;
            $timeline[$labelDate][$i]['created_at'] = $msg->created_at;
            $timeline[$labelDate][$i]['read_at'] = $msg->read_at;
            $timeline[$labelDate][$i]['message_title'] = $msg->message_title;
            $timeline[$labelDate][$i]['message_content'] = $msg->message_content;
            $timeline[$labelDate][$i]['message_id'] = $msg->message_id;
            $timeline[$labelDate][$i]['department'] = $msg->department;
            $timeline[$labelDate][$i]['sent_by'] = $msg->sent_by;
            $i++;
        }
        
        foreach($user_notification_reports as $nr){
            $tempDate = date('Y-m-d', strtotime($nr->getOriginal('created_at')));
            $labelDate = strtotime($tempDate); 
            //$labelDate = date('j M Y', strtotime($nr->getOriginal('created_at')));
            $timeline[$labelDate][$i]['type'] = 'Notification';
            $timeline[$labelDate][$i]['id'] = $nr->id;
            $timeline[$labelDate][$i]['created_at'] = $nr->getOriginal('created_at');
            $timeline[$labelDate][$i]['notification']['type'] = $nr->notification->type;
            $timeline[$labelDate][$i]['notification']['event_type'] = $nr->notification->event_type;
            $timeline[$labelDate][$i]['notification']['title'] = $nr->notification->title;
            $timeline[$labelDate][$i]['notification']['content'] = $nr->notification->content;
            $timeline[$labelDate][$i]['notification']['icon'] = $nr->notification->icon;
            $i++;
        }
        krsort($timeline);*/
        //echo "<pre>"; print_r($userData['timeline']); die;
        $userData['profile'] = $this->user1->find($userId);
        //$userData['timeline'] = $timeline;
        return view('user.profile', ['data' => $userData]);
    }
    public function profilePost(UserPostRequest $request){
        $user = Auth::user();
        $userId = $user->id;
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $post_data = array();
            $post_data = $request->all();
            
            $id = $userId;
            unset($post_data['_token']);
            
            $response = $this->user1->update($post_data, $id);
            
            if(isset($response->id) && !empty($response->id)){
                $resp['status'] = 1;
                $resp['msg'] = 'User created successfully.';
            }else if($response){
                $resp['status'] = 1;
                $resp['msg'] = 'User updated successfully.';
                $resp['update_user_prifile'] = true;
                
            }else{
                $resp['status'] = 0;
                $resp['msg'] = 'Invalid Data, Please try again.';
                $resp['update_user_prifile'] = true;
            }
           // echo "<pre>"; print_r($user); die;
            echo json_encode($resp);   
            //echo \Response::json($response);
            die;
          }
    }
    public function send_notification_for_updated_fields($event_trigger_fields, $userId){
        //$notificationProfile = config('constants.Profile');
        /*foreach($event_trigger_fields as $key=>$value){
            $notificationData = $this->notification->findBy('event_type', $value);
            $notificationId = (!empty($notificationData)) && isset($notificationData->id) && !empty($notificationData->id) ? $notificationData->id : '';
            if(isset($notificationId) && !empty($notificationId)){                
                $notificationReportData = array('notification_id'=>$notificationId, 'send_to_user_id' => $userId, 'type'=>'Notification');
                $this->mnr->create($notificationReportData);
            }
        }*/
    }
    public function avatarPost(UserPostRequest $request){
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $post_data = array();
            
            $user = Auth::user();
            $id = $user->id;
            
            $imageName = '';
            $response = 0;
            if ($request->hasFile('avatar')) {
                $destinationPath = config('constants.user_avatar_full_path');//base_path() . '/public/uploads/notification_icons/';
                $imageName = time() . '_' . $request->file('avatar')->getClientOriginalName();
                $request->file('avatar')->move($destinationPath, $imageName);
                $post_data['avatar'] = $imageName;
                $response = $this->user1->update($post_data, $id);
            }else{
                $resp['status'] = 0;
                $resp['msg'] = 'Please select avatar.';
                $resp['avatar'] = true;
                echo json_encode($resp);  
                exit(0);
            }
            if($response){
                $image_path = config('constants.user_avatar_path');
                $resp['status'] = 1;
                $resp['msg'] = 'Avatar updated successfully.';
                $resp['avatar'] = url($image_path.$imageName);
                $event_trigger_fields[] = 'avatar';
                $this->send_notification_for_updated_fields($event_trigger_fields, $id);
            }else{
                $resp['status'] = 0;
                $resp['msg'] = 'Invalid Data, Please try again.';
                $resp['avatar'] = true;
            }
            //echo "<pre>"; print_r($response); die;
            echo json_encode($resp);   
            //echo \Response::json($response);
            die;
        }
            
    }
    public function changePasswordPost(Request $request){
        $user = Auth::user();
        $userId = $user->id;
        $post_data = $request->all();
         $rules = array(
                'old_password' => 'required',
                'password' => 'required|alphaNum|between:6,16|confirmed'
            );

        $validator = Validator::make($post_data, $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $errorStr = '';
            if(!empty($errors->all())){
                $errorStr .= '<ul class="alert alert-danger">';
                  foreach($errors->all() as $error){
                    $errorStr .= '<li>'.$error.'</li>';
                  }
                $errorStr .= '</ul>';
            }
            $resp['status'] = 0;
            $resp['msg'] = $errorStr;
            $resp['update_user_prifile'] = true;
            echo json_encode($resp);  die; 
        }else{
           if (Hash::check($post_data['old_password'], $user->getAuthPassword())){
                $user->password = bcrypt($post_data['password']);
                $userResp = $user->save();
                if($userResp){
                    $resp['status'] = 1;
                    $resp['msg'] = 'Password successfully updated!';
                    $resp['update_user_prifile'] = true;
                    echo json_encode($resp);  die; 
                }else{
                    $resp['status'] = 0;
                    $resp['msg'] = 'Something went wrong, Please try again!';
                    $resp['update_user_prifile'] = true;
                    echo json_encode($resp);  die; 
                }
           }else{
                $resp['status'] = 0;
                $resp['msg'] = '<ul class="alert alert-danger"><li>Invalid current password!</li></ul>';
                $resp['update_user_prifile'] = true;
                echo json_encode($resp);  die; 
           }               
        }
        ///echo "<pre>"; print_r($post_data); die;
    }
    
    
    private function _filterUserData($post_data){
        
        $userData = [];
            if(isset($post_data['first_name'])){
                $userData['first_name'] = $post_data['first_name'];
            }
            if(isset($post_data['last_name'])){
                $userData['last_name'] = $post_data['last_name'];
            }
            if(isset($post_data['email'])){
                $userData['email'] = $post_data['email'];
            }
            if(isset($post_data['password']) && !empty($post_data['password'])){
                $userData['password'] = $post_data['password'];
            }
            if(isset($post_data['role_id'])){
                $userData['role_id'] = $post_data['role_id'];
            }
            if(isset($post_data['featured'])){
                $userData['featured'] = $post_data['featured'];
            }
            if(isset($post_data['type']) && $post_data['type'] == 'frontend'){
                $userData['active'] = $post_data['role_id']==4?1:0;
            }
            if(isset($post_data['active'])){
                $userData['active'] = $post_data['active'];
            }
            return $userData;
            
    }
    private function _filterUserDetailData($post_data){
            $userDetailData = [];
            if(isset($post_data['street_address_1'])){
                $userDetailData['street_address_1'] = $post_data['street_address_1'];
            }
            if(isset($post_data['street_address_2'])){
                $userDetailData['street_address_2'] = $post_data['street_address_2'];
            }
            if(isset($post_data['destination_id'])){
                $userDetailData['destination_id'] = $post_data['destination_id'];
            }
            if(isset($post_data['state_id'])){
                $userDetailData['state_id'] = $post_data['state_id'];
            }
            if(isset($post_data['country_id'])){
                $userDetailData['country_id'] = $post_data['country_id'];
            }
            if(isset($post_data['zip_code'])){
                $userDetailData['zip_code'] = $post_data['zip_code'];
            }
            if(isset($post_data['latitude'])){
                $userDetailData['latitude'] = $post_data['latitude'];
            }
            if(isset($post_data['longitude'])){
                $userDetailData['longitude'] = $post_data['longitude'];
            }
            if(isset($post_data['overview'])){
                $userDetailData['overview'] = $post_data['overview'];
            }
            if(isset($post_data['territories'])){
                $userDetailData['territories'] = $post_data['territories'];
            }
            if(isset($post_data['website'])){
                $userDetailData['website'] = $post_data['website'];
            }
            if(isset($post_data['phone'])){
                $userDetailData['phone'] = $post_data['phone'];
            }
            if(isset($post_data['toll_free_phone'])){
                $userDetailData['toll_free_phone'] = $post_data['toll_free_phone'];
            }
            if(isset($post_data['fax'])){
                $userDetailData['fax'] = $post_data['fax'];
            }
            if(isset($post_data['keywords'])){
                $userDetailData['keywords'] = $post_data['keywords'];
            }
            if(isset($post_data['featured'])){
                $userDetailData['featured'] = $post_data['featured'];
            }
            if(isset($post_data['notes'])){
                $userDetailData['notes'] = $post_data['notes'];
            }
            if(isset($post_data['license_number'])){
                $userDetailData['license_number'] = $post_data['license_number'];
            }
            if(isset($post_data['meta_title'])){
                $userDetailData['meta_title'] = $post_data['meta_title'];
            }
            if(isset($post_data['meta_keywords'])){
                $userDetailData['meta_keywords'] = $post_data['meta_keywords'];
            }
            if(isset($post_data['meta_description'])){
                $userDetailData['meta_description'] = $post_data['meta_description'];
            }
            if(isset($post_data['active'])){
                $userDetailData['active'] = $post_data['active'];
            }
            if(isset($post_data['agency_name'])){
                $userDetailData['agency_name'] = $post_data['agency_name'];
            }
            if(isset($post_data['type']) && $post_data['type'] == 'frontend' && isset($post_data['role_id']) && $post_data['role_id']==3){
                $userDetailData['agency_type'] = 1;
            }
            if(isset($post_data['agency_type'])){
                $userDetailData['agency_type'] = $post_data['agency_type'];
            }
            if(isset($post_data['user_id'])){
                $userDetailData['user_id'] = $post_data['user_id'];
            }
            if(isset($post_data['audio'])){
                $userDetailData['audio'] = $post_data['audio'];
            }
            return $userDetailData;
    }
    private function _getFileName($name){
        $specialChar = [' ','@','#','$','~','%','&','^','*','(',')','+','=','{','}','[',';','"','\'','?','<','>','.',',','_'];
        foreach($specialChar as $char){
            $name = str_replace($char, '-', $name);
        }
        return strtolower($name);
    }
    public function createUser(UserPostRequest $request){
        if ($request->isMethod('post')) { //$request->isXmlHttpRequest() && 
            $post_data = array();
            $post_data = $request->all();
            $role_id = $post_data['role_id'];
            //echo "<pre>"; print_r($post_data); echo "</pre>"; die;
            //$role_id = $post_data['role_id'];
            $imgArr = $request->session()->pull('user_images');
            $videosArr = $request->session()->pull('user_videos_details');
            $filesArr = $request->session()->pull('user_file');
            $socialArr = $request->session()->pull('user_social_media');
            $default_image_from = isset($post_data['default_image_from']) ? $post_data['default_image_from'] : '';
            //if(isset($imgArr) && !empty($imgArr)){
                if(isset($post_data['default_image']) && !empty($post_data['default_image'])){
                    $defultImage = $post_data['default_image'];
                }else{
                    $defultImage = isset($imgArr) && !empty($imgArr)? array_keys($imgArr)[0] : 0;
                }
            //}
            unset($post_data['default_image_from']);
            unset($post_data['default_image']);
            
            
            //echo "<pre>"; print_r($post_data); echo "</pre>"; die;
            $id = $post_data['id'];
            unset($post_data['_token']);
           $userConfig = config('constants.user');
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
                
                $userAudioPath = $userConfig['user_audio_full_path'];
                
                $a = $request->audio_file->move($userAudioPath, $filenametostore);
               // echo "<pre>"; var_dump($a); echo "</pre>";
                //$request->audio_file->save();
                $post_data['audio'] = $filenametostore;
            }
            
            if(isset($id) && !empty($id)){
                $dImgs = array('is_default'=>0); 
                $dImgsRes1 = $this->userImage->update($dImgs, $id, 'user_id');
                unset($post_data['role_id']);
                $userData = $this->_filterUserData($post_data);
                $response = $this->user1->update($userData, $id);
                $userDetailData = $this->_filterUserDetailData($post_data);
                $usrDtlRes = $this->userDetail->update($userDetailData, $id, 'user_id');
                if($default_image_from=='db'){
                    $dImgs2 = array('is_default'=>1); 
                    $dImgsRes2 = $this->userImage->update($dImgs2, $defultImage);
                }
                $lastInsertId = $id;
                if(isset($post_data['agency_id']) && !empty($post_data['agency_id'])){
                    $user_agency_data = array('agency_id'=>$post_data['agency_id']);
                    $this->userAgency->update($user_agency_data, $id, 'user_id');
                }
                
            }else{
                $userData = $this->_filterUserData($post_data);
                $response = $this->user1->createUser($userData, $this->role);
                $lastInsertId = $response->id;
                $post_data['user_id'] = $lastInsertId;
                $userDetailData = $this->_filterUserDetailData($post_data);
                $dImgsRes = $this->userDetail->create($userDetailData);
                if(isset($post_data['agency_id']) && !empty($post_data['agency_id'])){
                    $user_agency_data = array('user_id'=>$lastInsertId, 'agency_id'=>$post_data['agency_id']);
                    $ad = $this->userAgency->create($user_agency_data);
                }
            }
            //echo "<pre>"; print_r($response); echo "</pre>"; die;
            if($lastInsertId){
                if(!empty($imgArr)){
                    foreach($imgArr as $k=>$v){
                        $isDefault = isset($defultImage) && $k==$defultImage ? 1 : 0;
                        $dImgs = array('user_id'=>$lastInsertId, 'image_path'=>$v, 'is_default'=>$isDefault); 
                        $dImgsRes = $this->userImage->create($dImgs);
                    }
                }
                if(!empty($videosArr)){
                    foreach($videosArr as $k=>$v){                        
                        $dImgs = array('user_id'=>$lastInsertId, 
                            'video_format'=>$v['video_format'], 
                            'video_title'=>$v['video_title'],
                            'video_description'=>$v['video_description'],
                            'video_link'=>$v['video_link']); 
                        $dImgsRes = $this->userVideo->create($dImgs);
                    }
                }
                if(!empty($filesArr)){
                    foreach($filesArr as $k=>$v){
                        $dImgs = array('user_id'=>$lastInsertId, 
                            'file_name'=>$v['original_file_name'], 
                            'file_path'=>$v['filename']); 
                        $dImgsRes = $this->userFile->create($dImgs);
                    }
                }
                if(!empty($socialArr)){
                    foreach($socialArr as $k=>$v){                        
                        $dImgs = array('user_id'=>$lastInsertId, 
                            'social'=>$v['social'], 
                            'link'=>$v['link']); 
                        $dImgsRes = $this->userSocialMedia->create($dImgs);
                    }
                }
                $resp['status'] = 1;
                $resp['msg'] = 'User created successfully.';
                //$id = $response->id;
            }else if($response){
                $resp['status'] = 1;
                $resp['msg'] = 'User updated successfully.';
            }else{
                $resp['status'] = 0;
                $resp['msg'] = 'Invalid Data, Please try again.';
            }
            if($role_id==2){
                return redirect('/agents/list')->with('status', $resp['msg']);
            }else if($role_id==3){
                return redirect('/agency/list')->with('status', $resp['msg']);
            }else{
                return redirect('/user/list')->with('status', $resp['msg']);
            }
            //echo json_encode($resp);   
            //echo \Response::json($response);
            //die;
          }
    }
    
  
    public function deletedata(Request $request ,$type ,$id){
        $moduleConfig = config('constants.user');
        $response = array();
        $response['status'] = false;
        $response['msg'] = 'Invalid selection.';
        switch($type){
            case 'delete_image':
                    $fullPathKey = 'user_full_path';
                    $image = $this->userImage->find($id);  
                    if($image->is_default){
                        $response['status'] = false;
                        $response['msg'] = 'You can not delete default image.';
                    }else{
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
                        $this->userImage->delete($id);  
                        $response['status'] = true;
                    }
                //echo "<pre>"; print_r($image->is_default); die;
                break;
            case 'delete_vedio':
                        $this->userVideo->delete($id);
                        $response['status'] = true;
                break;
            case 'delete_file':
                        $fullPathKey = 'user_file_full_path';
                        $file = $this->userFile->find($id);
                        $deleted = $file->file_path;
                        $deletedImgArr = explode('/', $deleted);
                        $filenamewithextension = end($deletedImgArr);
                        $delFile = $moduleConfig[$fullPathKey].$filenamewithextension;
                        @unlink($delFile);
                        $this->userFile->delete($id);
                        $response['status'] = true;
                break;
            case 'delete_social_media':
                        $this->userSocialMedia->delete($id);
                        $response['status'] = true;
                break;
        }
        echo json_encode($response);
    }
    
    public function userLogin(){
         return view('user.login_register');
    }
    
    
    public function distributorlist(){
        $user = Auth::user();
        $hasDistributor = $user->hasRole(['Distributor']); 
        $data = $this->user->getDistributorList($hasDistributor, $user->id);
        //echo "<pre>"; print_r($data); die;
        return view('distributor.list', ['data' => $data]);
    }
    public function distributoradd(){ 
        $user = Auth::user();
        $isAdmin = $user->hasRole(['Admin']);
        $data['roles'] = $this->role->all();
        return view('distributor.add', ['data' => $data, 'isAdmin'=>$isAdmin]);
    }
    public function distributoredit($id){        
        $user = Auth::user();
        $isAdmin = $user->hasRole(['Admin']);
        $data['roles'] = $this->role->all();
        $data['user_data'] = $this->user1->find($id);        
        //echo "<pre>"; print_r($data['user_data']); die;
        $userRole = $this->user1->getUserRoleId($id);
        $data['user_role_id'] = $userRole->role_id;
        
        //echo "<pre>"; print_r(count($data['user_data']->userTool)); die;
        return view('distributor.edit', ['data' => $data, 'isAdmin'=>$isAdmin]);
    }
    public function distributoraddPost(UserPostRequest $request){
        if($request->isMethod('post')){
            $post_data = $request->all();
            
            $id = $post_data['id'];
            
            $post_data['role_id'] = $post_data['role_id'] ? $post_data['role_id'] : 4;
            $role_id = $post_data['role_id'];
            $user = Auth::user();
                $hasDistributor = $user->hasRole(['Distributor']); 
            if(isset($id) && !empty($id)){                
                           
                unset($post_data['_token']);
                unset($post_data['role_id']);
                $userRole = $this->user1->getUserRoleId($id);
                $currentRoleId = $userRole->role_id;
                if(empty($post_data['password'])){
                    unset($post_data['password']);
                }else{
                    $post_data['password'] = bcrypt($post_data['password']);
                }
                //echo "<pre>"; print_r($post_data); die;
                $response = $this->user1->update($post_data, $id);
                if(!$hasDistributor){
                    $rtpData = ['distributor_rtp_variant'=>$post_data['distributor_rtp_variant']];
                    $response4 = $this->user1->update($rtpData, $id, 'created_by');
                }
                
                //echo "<pre>"; print_r($response); echo "</pre>"; die;
                if($response){
                    if($role_id != $userRole->role_id){
                        $user = $this->user1->find($id);
                        $roleData = $this->role->find($userRole->role_id);
                        $user->detachRole($roleData);
                        $roleData = $this->role->find($role_id);
                        $user->attachRole($roleData);
                    }
                    
                    return redirect('/distributor/list')->with('message', 'User updated successfully.');
                    }
            }else{
                
                if($hasDistributor){
                    $post_data['distributor_rtp_variant'] = $user->distributor_rtp_variant;
                }
                $post_data['created_by'] = $user->id;
                $response = $this->user1->createUser($post_data, $this->role);
                if(isset($response->id) && !empty($response->id)){ //if(isset($response->id) && !empty($response->id))              
                   $id = $response->id;
                    return redirect('/distributor/list')->with('message', 'User created successfully.');
                }else{
                    return redirect('/distributor/list')->with('message', 'Something went wrong, please try again.');
                }
            }
            //$reponse = $this->user1->createUser($post_data, $this->role);
            //$newUserId = isset($reponse['id']) && !empty($reponse['id']) ? $reponse['id'] : 0;
            
        }
    }
    public function getDistributorShop(Request $request, $id){
        $data = $this->user->getShopList(true, $id);
        $str = '<option value="">-Select Shop-</option>';
        foreach($data as $shop){
            $str .= '<option value="'.$shop->id.'">'.$shop->shop_name.'</option>';
        }
        echo $str;
        die;
    }

    public function shoplist(){
        $user = Auth::user();
        $hasDistributor = $user->hasRole(['Distributor']); 
        $isAdmin = $user->hasRole(['Admin']);
        $data = $this->user->getShopList($hasDistributor, $user->id);
        //echo "<pre>"; print_r($data); die;
        return view('shop.list', ['data' => $data,'isAdmin'=>$isAdmin]);
    }
    public function shopadd(){ 
        $data['roles'] = $this->role->all();
        $user = Auth::user();
        $hasDistributor = $user->hasRole(['Distributor']);
        $data['isDistributor'] = $hasDistributor;
        $data['distributors'] = $this->user->getDistributorList();
        return view('shop.add', ['data' => $data]);
    }
    public function shopedit($id){        
        
        $data['roles'] = $this->role->all();
        $data['user_data'] = $this->user1->find($id);        
        
        $userRole = $this->user1->getUserRoleId($id);
        $data['user_role_id'] = $userRole->role_id;
        $user = Auth::user();
        $hasDistributor = $user->hasRole(['Distributor']);
        $data['isDistributor'] = $hasDistributor;
        $data['distributors'] = $this->user->getDistributorList();
        //echo "<pre>"; print_r($userRole); die;
        return view('shop.edit', ['data' => $data]);
    }
    public function shopaddPost(UserPostRequest $request){
        if($request->isMethod('post')){
            $post_data = $request->all();
            
            $id = $post_data['id'];
            
            $post_data['role_id'] = $post_data['role_id'] ? $post_data['role_id'] : 4;
            $role_id = $post_data['role_id'];
            
            if(isset($id) && !empty($id)){                
                           
                unset($post_data['_token']);
                unset($post_data['role_id']);
                $post_data['jackpot'] = isset($post_data['jackpot']) ? 1 : 0;
                $post_data['nudgeFeature'] = isset($post_data['nudgeFeature']) ? 1 : 0;
                $post_data['preRevealWithSkillStop'] = isset($post_data['preRevealWithSkillStop']) ? 1 : 0;
                $post_data['sweepstakes'] = isset($post_data['sweepstakes']) ? 1 : 0;    
                if(!$post_data['sweepstakes']){
                    $post_data['login_verbiage_id'] = 0;
                }
                $userRole = $this->user1->getUserRoleId($id);
                $currentRoleId = $userRole->role_id;
                if(empty($post_data['password'])){
                    unset($post_data['password']);
                }else{
                    $post_data['password'] = bcrypt($post_data['password']);
                }
                $userinfo = $this->user1->find($id);
                if(!$userinfo->shop_code)
                    $post_data['shop_code'] = $this->user1->getShopCode();
                $response = $this->user1->update($post_data, $id);
                //echo "<pre>"; print_r($response); echo "</pre>"; die;
                if($response){
                    if($role_id != $userRole->role_id){
                        $user = $this->user1->find($id);
                        $roleData = $this->role->find($userRole->role_id);
                        $user->detachRole($roleData);
                        $roleData = $this->role->find($role_id);
                        $user->attachRole($roleData);
                    }
                    
                    return redirect('/shop/list')->with('message', 'Shop updated successfully.');
                    }
            }else{
                $user = Auth::user();
                $post_data['shop_code'] = $this->user1->getShopCode();
                $post_data['created_by'] = isset($post_data['created_by']) && !empty($post_data['created_by']) ? $post_data['created_by'] : $user->id;
                $response = $this->user1->createUser($post_data, $this->role);
                if(isset($response->id) && !empty($response->id)){ //if(isset($response->id) && !empty($response->id))              
                   $id = $response->id;
                    return redirect('/shop/list')->with('message', 'Shop created successfully.');
                }else{
                    return redirect('/shop/list')->with('message', 'Something went wrong, please try again.');
                }
            }
            //$reponse = $this->user1->createUser($post_data, $this->role);
            //$newUserId = isset($reponse['id']) && !empty($reponse['id']) ? $reponse['id'] : 0;
            
        }
    }
    public function update_credit(Request $request){
         if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
             //echo "<pre>"; print_r($request->all()); die;
             $post_data = $request->all();
             $userId = $post_data['id'];
             $creditAmount = (int) $post_data['credit_amount'];
             $loggedInUser = Auth::user();
             $isAdmin = $loggedInUser->hasRole(['Admin']);
             $userData = $this->user1->find($userId);
             $availableCredit = $userData->available_credit;
             $requestData['available_credit'] = $availableCredit + $creditAmount;
             if($isAdmin){
                $response = $this->user1->update($requestData, $userId);
                if($response){
                   $resp['status'] = 1;
                   $resp['msg'] = 'Credit amount updated successfully.';
               }else{
                   $resp['status'] = 0;
                   $resp['msg'] = 'Invalid Data, Please try again.';
               }
             }else{
                 $loggedInUserAvailableCredit = $loggedInUser->available_credit;
                 if($creditAmount > $loggedInUserAvailableCredit){
                     $resp['status'] = 0;
                     $resp['msg'] = 'Credit Amount can not be greater than your available credit amount.';
                 }else{                     
                     $response = $this->user1->update($requestData, $userId);
                     $loggedInUserId = $loggedInUser->id;
                     $updateCreditAmount['available_credit'] = $loggedInUserAvailableCredit - $creditAmount;
                     $response = $this->user1->update($updateCreditAmount, $loggedInUserId);
                     if($response){
                        $resp['status'] = 1;
                        $resp['msg'] = 'Credit amount updated successfully.';
                     }else{
                        $resp['status'] = 0;
                        $resp['msg'] = 'Invalid Data, Please try again1.';
                     }
                 }
             }             
         }else{
             $resp['status'] = 0;
             $resp['msg'] = 'Invalid Data, Please try again.';
         }
         echo json_encode($resp); die;
    }

    public function dashboard(){
        $jackpotUiUrl = config('constants.jackpotUiUrl');
        $user = Auth::user();
        $isShop = $user->hasRole(['Shop']);
        $jackpotUiUrl = $isShop && $user->jackpot  ? $jackpotUiUrl.'?sid='.$user->id : '';
        return view('dashboard.index', ['jackpotUiUrl'=>$jackpotUiUrl]);
    }
}
