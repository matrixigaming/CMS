<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\LoginVerbiagePostRequest;
use App\Repositories\LoginVerbiage\LoginVerbiageRepository as LoginVerbiage;

class LoginVerbiageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $login_verbiage;
    
    public function __construct(LoginVerbiage $login_verbiage)
    {
        $this->middleware('auth');
        $this->login_verbiage = $login_verbiage;
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $result = $this->login_verbiage->all();       
         return view('loginverbiage.index', ['data' => $result]);
    }
    public function loadModal(Request $request, $action, $id)
    { 
        if($action == 'create_page'){  
           return view('loginverbiage.modal_create');        
       }
       if($action == 'update_page'){
           
           $results = $this->login_verbiage->find($id);   
           return view('loginverbiage.modal_update', ['data' => $results]);
       }
    }
    public function create(LoginVerbiagePostRequest $request){
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $post_data = array();
            $post_data = $request->all();
            //echo "<pre>"; print_r($post_data); die;
            //unset($post_data['_wysihtml5_mode']);
            $id = $post_data['id'];
            if(isset($id) && !empty($id)){
                unset($post_data['_token']);
                $response = $this->login_verbiage->update($post_data, $id);
            }else{
                $response = $this->login_verbiage->create($post_data);
            }
            if(isset($response->id) && !empty($response->id)){
                $resp['status'] = 1;
                $resp['msg'] = 'Login Verbiage created successfully.';
            }else if($response){
                $resp['status'] = 1;
                $resp['msg'] = 'Login Verbiage updated successfully.';
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
