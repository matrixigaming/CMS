<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Repositories\Role\RoleRepository as Role;
use App\Repositories\Permission\PermissionRepository as Permission;
use App\Repositories\MessageNotificationReport\MessageNotificationReportRepository as MessageNotificationReport;

class RoleController extends Controller
{
    private $role;
    private $permission;
    private $mnr;
    public function __construct(Role $role, Permission $permission, MessageNotificationReport $mnr) {
        $this->role = $role;
        $this->permission = $permission;
        $this->mnr = $mnr;
        parent::__construct();
    }
    public function index(){
        $data['roles'] = $this->role->all();
        $data['permission'] = $this->permission->all();
        //echo "<pre>"; print_r($data); die;
        return view('role.index', ['data' => $data]);
    }
    public function loadModal(Request $request, $action, $id)
    {
        if($action == 'create_role'){
           return view('role.modal_create');        
       }
       if($action == 'update_role'){                  
           $results = $this->role->find($id);           
           return view('role.modal_update', ['data' => $results]);
       }   
       
       if($action == 'create_permission'){
           return view('permission.modal_create');
       }
       if($action == 'update_permission'){           
           $results = $this->permission->find($id);
           return view('permission.modal_update', ['data' => $results]);
       }
    }
    
    public function create(Request $request){
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $post_data = array();
            $post_data = $request->all();
            //unset($post_data['_wysihtml5_mode']);
            $id = $post_data['id'];
            if(isset($id) && !empty($id)){
                unset($post_data['_token']);
                $response = $this->role->update($post_data, $id);
            }else{
                $response = $this->role->create($post_data);
            }
            if(isset($response->id) && !empty($response->id)){
                $resp['status'] = 1;
                $resp['msg'] = 'Role created successfully.';
            }else if($response){
                $resp['status'] = 1;
                $resp['msg'] = 'Role updated successfully.';
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
    
    public function manage(){
        $data['roles'] = $this->role->all();
        $data['permissions'] = $this->permission->all();
        
        foreach($data['roles'] as $role){
            $role_id = $role->id;
            $permissionsByRole = $this->permission->getpermissionbyroleid($role_id);
            $data['role_permissions'][$role_id] = $permissionsByRole;
        }
        return view('role.manage', ['data' => $data]);
    }
    public function managePost(Request $request){
        if ($request->isMethod('post')) {
           $data = $request->all();           
          foreach($data['permission'] as $roleId => $permission){
              $role = $this->role->find($roleId);
              $r = $this->role->deleteUserFromRolePermission($roleId);
              foreach($permission as $p){
                  $permission = $this->permission->find($p);
                  $role->attachPermission($permission);
              }
          }
                
        return redirect()->back()->withInput();
        }  
    }
    
}
