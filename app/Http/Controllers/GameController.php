<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Image; //Intervention Image
use Illuminate\Support\Facades\Storage; //Laravel Filesystem
use App\Http\Requests\GamePostRequest;
use App\Repositories\Game\Game1Repository as Game1;
use App\Repositories\User\UserRepository as User;
use App\Repositories\GameRtpSetting\GameRtpSettingRepository as GameRtpSetting;
use DB;
class GameController extends Controller
{
    private $game;
    private $user;
    private $gameRtpSetting;
    public function __construct(Game1 $game, User $user, GameRtpSetting $gameRtpSetting)
    {
        $this->middleware('auth');
        $this->game = $game;
        $this->user = $user;
        $this->gameRtpSetting = $gameRtpSetting;
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
         $result = $this->game->all(); 
         return view('game.index', ['data' => $result]);
    }
    
    public function loadModal(Request $request, $action, $id)
    {
        if($action == 'create_game'){  
           return view('game.modal_create');        
       }
       if($action == 'update_game'){
           $results = $this->game->find($id);           
           return view('game.modal_update', ['data' => $results]);
       }
       if($action == 'customize_rtp'){
           $gameList = $this->game->all(); 
           $shopList = $this->user->getShopList();
           return view('game.modal_customize_rtp', ['shop_list' => $shopList, 'game_list'=>$gameList]);
       }
    }
    public function _getFileName($name){
        $specialChar = [' ','@','#','$','~','%','&','^','*','(',')','+','=','{','}','[',';','"','\'','?','<','>','.',',','_'];
        foreach($specialChar as $char){
            $name = str_replace($char, '-', $name);
        }
        return strtolower($name);
    }
    public function checkrtp(Request $request, $shopId, $gameId){
        if ($request->isXmlHttpRequest()) {
            if(!empty($shopId) && !empty($gameId)){
                $game_rtp = DB::table('game_rtp_settings')->where([
                    ['shop_id', '=', $shopId],
                    ['game_id', '=', $gameId],
                ])->get();
                if(!empty($game_rtp)){
                    $resp['status'] = 1;
                    $resp['rtp'] = $game_rtp[0]->rtpVariant;
                    $resp['rtpsettingid'] = $game_rtp[0]->id;
                    
                }else{
                    $resp['status'] = 0;
                    $resp['rtp'] = '';
                }
                echo json_encode($resp); 
                die;
            }
            //echo "<pre>"; print_r($game_rtp); die;
        }
    }

    public function setrtp(Request $request){
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $post_data = $request->all();
            $id = isset($post_data['id']) && !empty($post_data['id']) ? $post_data['id'] : 0;
            unset($post_data['_token']); unset($post_data['id']);
            //echo "<pre>"; print_r($post_data); echo "</pre>"; die;
            if($id){
                $response = $this->gameRtpSetting->update($post_data, $id);
            }else{
                $response = $this->gameRtpSetting->create($post_data);
            }
            
            if(isset($response->id) && !empty($response->id)){
                $resp['status'] = 1;
                $resp['msg'] = 'Game RTP set successfully.';
            }else if($response){
                $resp['status'] = 1;
                $resp['msg'] = 'Game RTP updated successfully.';
            }else{
                $resp['status'] = 0;
                $resp['msg'] = 'Error!!';
            }
            echo json_encode($resp);
            die;
        }
    }

    public function create(GamePostRequest $request){
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $post_data = array();
            $post_data = $request->all();
            $id = $post_data['id'];
            $moduleConfig = config('constants.game');
            //echo "<pre>"; print_r($moduleConfig); print_r($post_data); die;
            if ($request->hasFile('icon')) {                
                $file = $request->file('icon');
                $filenamewithextension = $file->getClientOriginalName();
                $targetPath = $moduleConfig['game_icon_full_path'];
                //get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                $filename = $this->_getFileName($filename);
                $uniqId = uniqid();
                $filename = $filename . '_' . $uniqId;
                //get file extension
                $extension = $file->getClientOriginalExtension();                
                //filename to store
                $filenametostore =  $filename. '.' . $extension;                
                $original = Image::make($file->getRealPath());                 
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
                $post_data['icon'] = $filenametostore;
            }
            //lobby icon gif
            if ($request->hasFile('lobby_icon')) {                
                $file = $request->file('lobby_icon');
                $filenamewithextension = $file->getClientOriginalName();
                $targetPath = $moduleConfig['game_icon_full_path'];
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                $filename = $this->_getFileName($filename);
                $uniqId = uniqid();
                $filename = $filename . '_' . $uniqId;
                $extension = $file->getClientOriginalExtension();
                $filenametostore =  $filename. '.' . $extension;   
                $file->move($targetPath , $filenametostore);
                $post_data['lobby_icon'] = $filenametostore;
            }
            //echo "<pre>"; print_r($post_data); echo "</pre>"; die;
            if(isset($id) && !empty($id)){
                unset($post_data['_token']);
                $response = $this->game->update($post_data, $id);
            }else{                
                $response = $this->game->create($post_data); 
            }
            if(isset($response->id) && !empty($response->id)){
                $resp['status'] = 1;
                $resp['msg'] = 'Game created successfully.';
            }else if($response){
                $resp['status'] = 1;
                $resp['msg'] = 'Game updated successfully.';
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
}
