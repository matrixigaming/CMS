<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\UserPostRequest;
use App\Repositories\User\User1Repository as User1;
use App\Repositories\User\UserRepository as User;
use App\Repositories\Role\RoleRepository as Role;
use App\UserAgency as UserAgency;
use App\Repositories\Country\CountryRepository as Country;
use App\Repositories\UserImage\UserImageRepository as UserImage;
use App\Repositories\UserFile\UserFileRepository as UserFile;
use App\Repositories\UserSocialMedia\UserSocialMediaRepository as UserSocialMedia;
use App\Repositories\UserVideo\UserVideoRepository as UserVideo;
use App\Repositories\UserDetail\UserDetailRepository as UserDetail;
use App\Repositories\Destination\DestinationRepository as Destination;
use App\Repositories\State\StateRepository as State;
use Auth;
use Validator;
use Hash;
use Helper;
use App\Http\Controllers\CommonController as CommonController;
use Image; //Intervention Image

class ProfileController extends Controller
{
    private $id;
    private $user1;
    private $user;
    private $userdetail;
    private $usersocialmedia;
    private $userimage;
    private $useragency;
    private $country;
    private $state;
    private $destination;
    private $commoncontroller;
    public function __construct(Request $request, User1 $user1, User $user, Country $country, State $state, 
            Destination $destination, UserDetail $userdetail, UserSocialMedia $usersocialmedia, UserImage $userimage
            , UserAgency $useragency, CommonController $commoncontroller) {

        $loggedInUser = Auth::user();

        if($loggedInUser){
            $this->id = $loggedInUser->id;
        }else{
            return redirect()->intended('/login-register');
        }

        $this->user = $user;
        $this->user1 = $user1;
        $this->userdetail = $userdetail;
        $this->usersocialmedia = $usersocialmedia;
        $this->userimage = $userimage;
        $this->useragency = $useragency;
        $this->country = $country;
        $this->destination = $destination;
        $this->state = $state;
        $this->commoncontroller = $commoncontroller;
        parent::__construct();
    }

    public function index(){
         $loggedInUser = Auth::user();

        if($loggedInUser){
            $this->id = $loggedInUser->id;
        }else{
            return redirect()->intended('/login-register');
        }
        $data = [];

        $userData = $this->user->getUser($this->id);

        if(!empty($userData))
            $data = $userData;

        $destinations = $this->destination->all()->toArray();

        if($destinations)
            $data['destinations'] = $destinations;
        else
            $data['destinations'] = [];

        $states = $this->state->with(['destinations'])->all()->toArray();

        if($states)
            $data['states'] = $states;
        else
            $data['states'] = [];

        $countries = $this->country->with(['states'])->all()->toArray();
        
        if($countries)
            $data['countries'] = $countries;
        else
            $data['countries'] = [];

        return view('profile.profile', $data);
    }
    
    public function userphotoupload(Request $request)
    {
        $message = $thumb = $standard = "";
        $post_data = $request->all();
        $moduleConfig = config('constants.user');
        $targetPath = $moduleConfig['user_full_path'];

        if ($request->hasFile('avatar_file')) {
            $file = $request->file('avatar_file');

            //get filename with extension
            $filenamewithextension = $file->getClientOriginalName();
            
            $mimeType = $file->getClientMimeType();
            
            $mimeTypes = ['image/gif', 'image/jpeg', 'image/png'];
            
            if(in_array($mimeType, $mimeTypes)){
                //get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                $filename = $this->commoncontroller->_getFileName($filename);
                $uniqId = uniqid();
                $filename = $filename . '_' . $uniqId;
                //get file extension
                $extension = $file->getClientOriginalExtension();
                //filename to store
                $filenametostore =  $filename. '.' . $extension;

                $original = Image::make($file->getRealPath());
                $original->save($targetPath . $filenametostore);

                foreach($moduleConfig['sizes'] as $type => $size) {
                    $newName = $filename . '_' . $type . '.' . $extension;
                    //$newImg = Image::make($file->getRealPath());
                    //$newImg->resize($size[0], $size[1]);
                    $background = Image::canvas($size[0], $size[1]);
                    $newImg = Image::make($file->getRealPath());                    
                    $resizeImage = $newImg->fit($size[0], $size[1], function($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $background->insert($resizeImage, 'center');
                    $newImg->save($targetPath . $newName);

                    if($type == 'th')
                        $thumb = url($moduleConfig['user_path'] . $filename . '_' . $type . '.' . $extension);
                    if($type == 'standard')
                        $standard = url($moduleConfig['user_path'] . $filename . '_' . $type . '.' . $extension);
                }

                $uploaded_file_path = $moduleConfig['user_path'] . $filenametostore;

                $userDefaultImage = $this->userimage->findWhere([['user_id', '=', $this->id], ['is_default', '=', 1]])->first();

                if($userDefaultImage){
                    $userDefaultImage = $userDefaultImage->toArray();

                    unlink($userDefaultImage['image_path']);

                    $imagePath = $userDefaultImage['image_path'];
                    $path_parts = pathinfo($imagePath);

                    foreach($moduleConfig['sizes'] as $type => $size) {
                        unlink($path_parts['dirname'] . '/' . $path_parts['filename'] . '_' . $type . '.' . $path_parts['extension']);
                    }

                    $this->userimage->delete($userDefaultImage['id']);
                }

                $this->userimage->create(['user_id' => $this->id, 'image_path' => $uploaded_file_path, 'is_default' => 1]);
            }else{
                $message = 'Please upload image with the following types: JPG, PNG, GIF';
            }
        }
        
        $response = array(
            'state'  => 200,
            'message' => $message,
            'result' => $thumb,
            'image_name' => $standard
        );
        
        return response()->json($response);
    }
    
    public function updateagentinfo(Request $request)
    {
        $json = TRUE; $response = $user = $user_detail = [];

        if($request->isMethod('post'))
            $post = $request->all();
        else
            $json = FALSE;

        $userClone = clone $this->user;

        $userData = $this->user->getUser($this->id);

        $this->user = $userClone;

        if(isset($userData['user_role']['role_id']))
            $role_id = $userData['user_role']['role_id'];
        else
            $json = FALSE;

        if($json){
            $user_detail = array(
                'phone' => $post['profile_agent_phone_number'],
                'street_address_1' => $post['profile_agent_street_address_1'],
                'street_address_2' => $post['profile_agent_street_address_2'],
                'destination_id' => $post['profile_agent_city'],
                'state_id' => $post['profile_agent_state'],
                'country_id' => $post['profile_agent_country'],
                'zip_code' => $post['profile_agent_zip_code'],
                'latitude' => $post['profile_agent_latitude'],
                'longitude' => $post['profile_agent_longitude']
            );

            if($role_id == 3){
                $user_detail['agency_name'] = $post['profile_agency_name'];
            }else{
                $user['first_name'] = $post['profile_agent_first_name'];
                $user['last_name'] = $post['profile_agent_last_name'];
            }

            if (isset($post['profile_agent_password']) && !empty($post['profile_agent_password']))
                $user['password'] = bcrypt($post['profile_agent_password']);

            if(!empty($user))
                $this->user1->update($user, $this->id);

            if(!empty($user_detail))
                $this->userdetail->update ($user_detail, $userData['user_detail']['id']);

            $response = array(
                'status' => 'success',
                'message' => 'Your changes have been saved successfully.',
                'data' => $this->user->getUser($this->id)
            );
        }

        return response()->json($response);
    }
    
    public function updateagentabout(Request $request)
    {
        $json = TRUE; $response = $user = $user_detail = [];

        if($request->isMethod('post'))
            $post = $request->all();
        else
            $json = FALSE;

        $userData = $this->user->getUser($this->id);
        
        if($json){
            $user_detail['overview'] = $post['profile_agent_about'];

            if(!empty($user_detail))
                $this->userdetail->update($user_detail, $userData['user_detail']['id']);

            $response = array(
                'status' => 'success',
                'message' => 'Your changes have been saved successfully.'
            );
        }

        return response()->json($response);
    }
    
    public function updateagentterritories(Request $request)
    {
        $json = TRUE; $response = $user = $user_detail = [];

        if($request->isMethod('post'))
            $post = $request->all();
        else
            $json = FALSE;

        $userData = $this->user->getUser($this->id);

        if($json){
            $user_detail['territories'] = $post['profile_agent_territories'];

            if(!empty($user_detail))
                $this->userdetail->update($user_detail, $userData['user_detail']['id']);

            $territories = '';
            
            $profile_agent_territories = explode(',', $post['profile_agent_territories']);
            if (count($profile_agent_territories) > 0) {
                $territories = '<ol>';
                foreach ($profile_agent_territories as $territory) {
                    $territories .= '<li>' . $territory . '</li>';
                }
                $territories .= '</ol>';
            }
            
            $response = array(
                'status' => 'success',
                'message' => 'Your changes have been saved successfully.',
                'territories' => $territories
            );
        }

        return response()->json($response);
    }
    
    public function updateagentwebsite(Request $request)
    {
        $json = TRUE; $response = $user = $user_detail = [];

        if($request->isMethod('post'))
            $post = $request->all();
        else
            $json = FALSE;

        $userData = $this->user->getUser($this->id);

        if($json){
            $user_detail['website'] = $post['txt_agent_website'];

            if(!empty($user_detail))
                $this->userdetail->update($user_detail, $userData['user_detail']['id']);
            
            $response = array(
                'status' => 'success',
                'message' => 'Your changes have been saved successfully.'
            );
        }

        return response()->json($response);
    }
    
    public function addagentsocial(Request $request)
    {
        $json = TRUE; $response = $user = $user_detail = [];

        if($request->isMethod('post'))
            $post = $request->all();
        else
            $json = FALSE;

        $userClone = clone $this->user;

        $userData = $this->user->getUser($this->id);

        $this->user = $userClone;

        if($json){
            $user_social_media = ['social' => $post['social'], 'link' => $post['social_link'], 'user_id' => $userData['id']];

            if(!empty($user_social_media))
                $this->usersocialmedia->create($user_social_media);
            
            $userData = $this->user->getUser($this->id);
            
            $icons = $edit = '';
            
            if (count($userData['user_social_media']) > 0) {
                foreach ($userData['user_social_media'] as $socialagent) {
                    $class = Helper::getSocialClass($socialagent);
                    $icons .= '<a href="' . $socialagent['link'] . '" target="_blank" class="' . $class . '" agent_social_' . $socialagent['social'] . '"></a>';
                    $edit .= '<span class="agent_social_' . $socialagent['social'] . '">'
                            . '<i class="' . $class . '"></i>'
                            . '<input type="text" class="txt_social" data-socialid="' . $socialagent['id'] . '" name="txt_social_' . $socialagent['social'] . '" value="' . $socialagent['link'] . '"> '
                            . '<span class="delete" onclick="deleteConfirmSocial(\'' . $socialagent['id'] . '\');">x</span><br></span>';
                }
            }

            $response = array(
                'status' => 'success',
                'message' => 'Your changes have been updated successfully.',
                'social_icons' => $icons,
                'social_edit' => $edit
            );
        }

        return response()->json($response);
    }
    
    public function deleteagentsocial(Request $request)
    {
        $json = TRUE; $response = $user = $user_detail = [];

        if($request->isMethod('post'))
            $post = $request->all();
        else
            $json = FALSE;

        if($json){
            $this->usersocialmedia->delete($post['socialid']);
            
            $userData = $this->user->getUser($this->id);
            
            $icons = $edit = '';
            
            if (count($userData['user_social_media']) > 0) {
                foreach ($userData['user_social_media'] as $socialagent) {
                    $class = Helper::getSocialClass($socialagent);
                    $icons .= '<a href="' . $socialagent['link'] . '" target="_blank" class="' . $class . '" agent_social_' . $socialagent['social'] . '"></a>';
                    $edit .= '<span class="agent_social_' . $socialagent['social'] . '">'
                            . '<i class="' . $class . '"></i>'
                            . '<input type="text" class="txt_social" data-socialid="' . $socialagent['id'] . '" name="txt_social_' . $socialagent['social'] . '" value="' . $socialagent['link'] . '"> '
                            . '<span class="delete" onclick="deleteConfirmSocial(\'' . $socialagent['id'] . '\');">x</span><br></span>';
                }
            }

            $response = array(
                'status' => 'success',
                'message' => 'Your changes have been updated successfully.',
                'social_icons' => $icons,
                'social_edit' => $edit
            );
        }

        return response()->json($response);
    }
    
    public function updateagentsocial(Request $request)
    {
        $json = TRUE; $response = $user = $user_detail = [];

        if($request->isMethod('post'))
            $post = $request->all();
        else
            $json = FALSE;

        if($json){
            foreach($post['data'] as $link)
                if(isset($link['id']) && !empty($link['id']))
                    $this->usersocialmedia->update(['link' => $link['link']], $link['id']);
            
            $userData = $this->user->getUser($this->id);
            
            $icons = $edit = '';
            
            if (count($userData['user_social_media']) > 0) {
                foreach ($userData['user_social_media'] as $socialagent) {
                    $class = Helper::getSocialClass($socialagent);
                    $icons .= '<a href="' . $socialagent['link'] . '" target="_blank" class="' . $class . '" agent_social_' . $socialagent['social'] . '"></a>';
                    $edit .= '<span class="agent_social_' . $socialagent['social'] . '">'
                            . '<i class="' . $class . '"></i>'
                            . '<input type="text" class="txt_social" data-socialid="' . $socialagent['id'] . '" name="txt_social_' . $socialagent['social'] . '" value="' . $socialagent['link'] . '"> '
                            . '<span class="delete" onclick="deleteConfirmSocial(\'' . $socialagent['id'] . '\');">x</span><br></span>';
                }
            }

            $response = array(
                'status' => 'success',
                'message' => 'Your changes have been updated successfully.',
                'social_icons' => $icons,
                'social_edit' => $edit
            );
        }

        return response()->json($response);
    }
    
    public function searchagents(Request $request)
    {
        $json = TRUE; $response = $user = $agents = [];

        if($request->isMethod('get'))
            $get = $request->all();
        else
            $json = FALSE;
        
        $get['is_active'] = 1;

        if($json){
            $users = $this->user->getAgentListForSearch($get);
            
            if(!empty($users)){
                $users = $users->toArray();
                
                foreach($users as $user){
                    if(empty($user['user_agency'])){
                        $state = '<br>' . $user['user_detail']['destination']['name'] . ', ' . $user['user_detail']['state']['state_name'] . ' ' . $user['user_detail']['zip_code'] . '</br>' . $user['user_detail']['country']['name'] . '</span>';
                        $address = $user['user_detail']['street_address_1'] . ', ' . $user['user_detail']['destination']['name'] . ' ' . $user['user_detail']['state']['state_name'] . ' ' . $user['user_detail']['zip_code'];
                        $agency_detail = '<strong>' . htmlentities($user['first_name'] . ' ' . $user['last_name'], ENT_QUOTES) . '</strong><br>';
                        $agency_detail .= $user['user_detail']['street_address_1'];

                        if ($user['user_detail']['street_address_2'])
                            $agency_detail .= ', ' . $user['user_detail']['street_address_2'];
                        
                        $agency_detail .= $state;

                        $agents[] = array(
                            'id' => $user['id'],
                            'label' => $user['first_name'] . ' ' . $user['last_name'] . ' - ' . $address,
                            'value' => $user['first_name'] . ' ' . $user['last_name'] . ' - ' . $address,
                            'detail' => $agency_detail
                        );
                    }
                }
            }
        }

        if(!empty($agents)){
            $response = $agents;
        }else{
            $response = [ [ 'id' => '0', 'label' => 'No matches available', 'value' => 'No matches available' ] ];
        }
        
        return response()->json($response);
    }
    
    public function addagenttoagency(Request $request)
    {
        $json = TRUE; $response = $user = [];

        if($request->isMethod('post'))
            $post = $request->all();
        else
            $json = FALSE;

        if($json){
            $this->useragency->create(['user_id' => $post['agent_id'], 'agency_id' => $this->id]);

            $response = array(
                'status' => 'success',
                'message' => 'Your changes have been updated successfully.'
            );
        }

        return response()->json($response);
    }
    
    public function addagentaudio(Request $request)
    {
        $message = $file = "";
        $post = $request->all();
        $moduleConfig = config('constants.user');

        if ($request->hasFile('profile_agent_audio')) {
            $file = $request->file('profile_agent_audio');
            
            //get filename with extension
            $filenamewithextension = $file->getClientOriginalName();
            
            $mimeType = $file->getClientMimeType();
            
            $mimeTypes = ['audio/x-wav', 'audio/wav', 'audio/mp3', 'audio/mpeg'];
            
            if(in_array($mimeType, $mimeTypes)){
                //get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                $filename = $this->commoncontroller->_getFileName($filename);
                $uniqId = uniqid();
                $filename = $filename . '_' . $uniqId;
                //get file extension
                $extension = $file->getClientOriginalExtension();
                //filename to store
                $filenametostore =  $filename. '.' . $extension;

                $userAudioPath = $moduleConfig['user_audio_full_path'];
                
                $file->move($userAudioPath, $filenametostore);
                
                $userDefaultImage = $this->userdetail->findWhere([['user_id', '=', $this->id]])->first();

                if($userDefaultImage){
                    $userDefaultImage = $userDefaultImage->toArray();
                    
                    if(!empty($userDefaultImage['audio'])){
                        unlink($moduleConfig['user_audio_path'] . $userDefaultImage['audio']);
                    }
                    
                    $this->userdetail->update(['audio' => $filenametostore], $userDefaultImage['id']);
                }
                
                $file = $filenametostore;
                $message = 'Your changes have been updated successfully.';
            }else{
                $message = 'Please upload audio with the following types: WAV, MP3, MPEG';
            }
        }
        
        $response = array(
            'state'  => 200,
            'message' => $message,
            'file' => $file
        );
        
        return response()->json($response);
    }
}
