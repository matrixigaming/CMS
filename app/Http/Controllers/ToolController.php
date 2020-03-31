<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\ToolPostRequest;
use App\Repositories\Tool\ToolRepository as Tool;
use App\Repositories\MessageNotificationReport\MessageNotificationReportRepository as MessageNotificationReport;

class ToolController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $tool;
    private $mnr;
    
    public function __construct(Tool $tool, MessageNotificationReport $mnr)
    {
        $this->middleware('auth');
        $this->tool = $tool;
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
         $result = $this->tool->all();       
         return view('tool.index', ['data' => $result]);
    }
    public function loadModal(Request $request, $action, $id)
    {
        $res = $this->tool->all(); 
        $tools = array();
        foreach($res as $k => $tool){
            $tools[$tool['id']] = $tool['name'];
        }
        $results['all_tools'] = $tools;
        if($action == 'create_tool'){  
           return view('tool.modal_create', ['data' => $results]);        
       }
       if($action == 'update_tool'){
           $results['tool'] = $this->tool->find($id);           
           return view('tool.modal_update', ['data' => $results]);
       }
    }
    public function create(ToolPostRequest $request){
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $post_data = array();
            $post_data = $request->all();
            $id = $post_data['id'];
            //unset($post_data['_wysihtml5_mode']);
            
            if(isset($id) && !empty($id)){
                unset($post_data['_token']);
                unset($post_data['event_trigger_fields']);                
                $response = $this->tool->update($post_data, $id);
            }else{
                $response = $this->tool->create($post_data);
            }
            if(isset($response->id) && !empty($response->id)){
                $resp['status'] = 1;
                $resp['msg'] = 'Tool created successfully.';
            }else if($response){
                $resp['status'] = 1;
                $resp['msg'] = 'Tool updated successfully.';
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
