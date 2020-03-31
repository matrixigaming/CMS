<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Repositories\Permission\PermissionRepository as Permission;
use App\Repositories\MessageNotificationReport\MessageNotificationReportRepository as MessageNotificationReport;

class PermissionController extends Controller
{
    private $permission;
    private $mnr;
    
    public function __construct(Permission $permission, MessageNotificationReport $mnr) {
        $this->middleware('auth');
        $this->permission = $permission;
        $this->mnr = $mnr;
        parent::__construct();
    }
    public function create(Request $request){
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $post_data = array();
            $post_data = $request->all();
            //unset($post_data['_wysihtml5_mode']);
            $id = $post_data['id'];
            if(isset($id) && !empty($id)){
                unset($post_data['_token']);
                $response = $this->permission->update($post_data, $id);
            }else{
                $response = $this->permission->create($post_data);
            }
            if(isset($response->id) && !empty($response->id)){
                $resp['status'] = 1;
                $resp['msg'] = 'Permission created successfully.';
            }else if($response){
                $resp['status'] = 1;
                $resp['msg'] = 'Permission updated successfully.';
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
