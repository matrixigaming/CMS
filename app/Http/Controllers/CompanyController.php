<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\CompanyPostRequest;
use App\Repositories\Company\CompanyRepository as Company;

class CompanyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $company;
    private $mnr;
    
    public function __construct(Company $company)
    {
        $this->middleware('auth');
        $this->company = $company;
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $result = $this->company->all();       
         return view('company.index', ['data' => $result]);
    }
    public function companyUserList()
    {
         $result = $this->company->with(array('users'))->all(); 
         echo "<pre>"; print_r($result); die;
         //return view('company.index', ['data' => $result]);
    }
    public function loadModal(Request $request, $action, $id)
    {
        if($action == 'create_company'){  
           return view('company.modal_create');        
       }
       if($action == 'update_company'){
           $results = $this->company->find($id);           
           return view('company.modal_update', ['data' => $results]);
       }
    }
    public function create(CompanyPostRequest $request){
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $post_data = array();
            $post_data = $request->all();
            $id = $post_data['id'];
            if(isset($id) && !empty($id)){
                unset($post_data['_token']);
                $response = $this->company->update($post_data, $id);
            }else{
                $response = $this->company->create($post_data);
            }
            if(isset($response->id) && !empty($response->id)){
                $resp['status'] = 1;
                $resp['msg'] = 'Company created successfully.';
            }else if($response){
                $resp['status'] = 1;
                $resp['msg'] = 'Company updated successfully.';
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
