<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\PagePostRequest;
use App\Repositories\Page\PageRepository as Page;
use App\Repositories\MessageNotificationReport\MessageNotificationReportRepository as MessageNotificationReport;

class PageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private $page;
    private $mnr;
    
    public function __construct(Page $page, MessageNotificationReport $mnr)
    {
        $this->middleware('auth');
        $this->page = $page;
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
         $result = $this->page->all();       
         return view('page.index', ['data' => $result]);
    }
    public function loadModal(Request $request, $action, $id)
    {
        if($action == 'create_page'){  
           return view('page.modal_create');        
       }
       if($action == 'update_page'){
           $results = $this->page->find($id);           
           return view('page.modal_update', ['data' => $results]);
       }
    }
    public function create(PagePostRequest $request){
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $post_data = array();
            $post_data = $request->all();
            //echo "<pre>"; print_r($post_data); die;
            //unset($post_data['_wysihtml5_mode']);
            $id = $post_data['id'];
            if(isset($id) && !empty($id)){
                unset($post_data['_token']);
                $response = $this->page->update($post_data, $id);
            }else{
                $response = $this->page->create($post_data);
            }
            if(isset($response->id) && !empty($response->id)){
                $resp['status'] = 1;
                $resp['msg'] = 'Page created successfully.';
            }else if($response){
                $resp['status'] = 1;
                $resp['msg'] = 'Page updated successfully.';
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
