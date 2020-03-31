<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\StatePostRequest;
use App\Repositories\State\StateRepository as State;
use App\Repositories\Country\CountryRepository as Country;

class StateController extends Controller
{
   
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $state;
    private $country;
    public function __construct(State $state, Country $country)
    {
        $this->middleware('auth', ['except' => ['getCities']]);
        $this->state = $state;
        $this->country = $country;
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $result = $this->state->with(['country'])->all();    
         //echo "<pre>"; print_r($result);
         return view('state.index', ['data' => $result]);
    }
    /*public function add(){         
         $data['countries'] = $this->country->all();
         return view('state.add', ['data' => $data]);
    }
    public function edit($id){         
         $data['countries'] = $this->country->all();
         $data['state'] = $this->state->find($id); 
         return view('state.edit', ['data' => $data]);
    }*/
    public function loadModal(Request $request, $action, $id)
    {
        $data['countries'] = $this->country->all();
        if($action == 'create_state'){  
           return view('state.modal_create', ['countries'=>$data['countries']]);        
       }
       if($action == 'update_state'){
           $results = $this->state->find($id);           
           return view('state.modal_update', ['data' => $results, 'countries'=>$data['countries']]);
       }
    }
    public function create(StatePostRequest $request){
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $post_data = array();
            $post_data = $request->all();
           // echo "<pre>"; print_r($post_data); echo "</pre>"; die;
            $id = $post_data['id'];
            if(isset($id) && !empty($id)){
                unset($post_data['_token']);
                $response = $this->state->update($post_data, $id);
            }else{
                $response = $this->state->create($post_data);
            }
            //var_dump($response, $response->id); die;
            if(isset($response->id) && !empty($response->id)){
                $resp['status'] = 1;
                $resp['msg'] = 'State created successfully.';
            }else if($response){
                $resp['status'] = 1;
                $resp['msg'] = 'State updated successfully.';
            }else{
                $resp['status'] = 0;
                $resp['msg'] = 'Invalid Data, Please try again.';
            }
            //return redirect('/states')->with('status', $resp['msg']);
            //echo "<pre>"; print_r($response); die;
            echo json_encode($resp);   
            //echo \Response::json($response);
            die;
          }
    }

    public function getCities($id)
    {
         $result = $this->state->with(['destinations'])->find($id); 
         $cityList = '<option value="">Select City</option>';
         if(!empty($result->destinations)){
             foreach($result->destinations as $destination){
                 $cityList .= '<option value="'.$destination->id.'">'.$destination->name.'</option>';
             }
         }
         return $cityList;
         //return response()->json($result);
         //echo "<pre>"; print_r($result->states); die;
         //return view('destination.index', ['data' => $result]);
    }
}
