<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\DepartmentPostRequest;
use App\Repositories\Department\Department1Repository as Department1;
use App\Repositories\MessageNotificationReport\MessageNotificationReportRepository as MessageNotificationReport;

class DepartmentController extends Controller
{
    private $mnr;
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $department;
    
    public function __construct(Department1 $department, MessageNotificationReport $mnr)
    {
        $this->middleware('auth');
        $this->department = $department;
        $this->mnr = $mnr;
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $result = $this->department->all();       
         return view('department.index', ['data' => $result]);
    }
    public function loadModal(Request $request, $action, $id)
    {
        if($action == 'create_department'){  
           return view('department.modal_create');        
       }
       if($action == 'update_department'){
           $results = $this->department->find($id);           
           return view('department.modal_update', ['data' => $results]);
       }
    }
    public function create(DepartmentPostRequest $request){
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $post_data = array();
            $post_data = $request->all();
            $id = $post_data['id'];
            if(isset($id) && !empty($id)){
                unset($post_data['_token']);
                $response = $this->department->update($post_data, $id);
            }else{
                $response = $this->department->create($post_data);
            }
            if(isset($response->id) && !empty($response->id)){
                $resp['status'] = 1;
                $resp['msg'] = 'Department created successfully.';
            }else if($response){
                $resp['status'] = 1;
                $resp['msg'] = 'Department updated successfully.';
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
