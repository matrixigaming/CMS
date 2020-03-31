<?php

namespace App\Http\Controllers;
use Auth;
use App\Department;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Repositories\Criteria\MessageReport\FilterMessageByUser;
use App\Http\Requests\MessagePostRequest;
use App\Repositories\Message\MessageRepositoryInterface;
use App\Repositories\Department\DepartmentRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\MessageReport\MessageReportRepositoryInterface;
use App\Repositories\Notification\NotificationRepository as Notification;
use App\Repositories\NotificationReport\NotificationReportRepository as NotificationReport;
use App\Repositories\MessageNotificationReport\MessageNotificationReportRepository as MessageNotificationReport1;
use App\Repositories\Criteria\MessageNotificationReport\FilterMnrByMessage;
use App\Repositories\Criteria\MessageNotificationReport\FilterMnrByNotification;
use App\MessageNotificationReport;

class MessageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $message;
    private $department;
    private $user;
    private $message_report;
    private $notification;
    private $notification_report;
    private $mnr;
    
    public function __construct(MessageRepositoryInterface $message, DepartmentRepositoryInterface $department, 
             UserRepositoryInterface $user, MessageReportRepositoryInterface $message_report, 
            Notification $notification, NotificationReport $notification_report, MessageNotificationReport1 $mnr)
    {
        $this->middleware('auth');
        $this->message = $message;
        $this->department = $department;
        $this->user = $user;
        $this->message_report = $message_report;
        $this->notification = $notification;
        $this->notification_report = $notification_report;
        $this->mnr = $mnr;
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $data['messages'] = $this->message->all();
        $data['notification'] = $this->notification->all();         
        //$data['message_reports'] = $this->message_report->all();  
        //$data['notification_reports'] = $this->notification_report->all(); 

        //$this->mnr->pushCriteria(new FilterMnrByMessage());
        //$this->mnr->orderBy('created_at', 'DESC');
        $data['message_reports'] = MessageNotificationReport::messages()->with(array('sendUser','fromUser', 'message'))->get(); 
        //$this->mnr->with(array('sendUser','fromUser', 'message'))->all();

        //$criteria = new FilterMnrByNotification(); 
        //$this->mnr->orderBy('created_at', 'DESC');
        $data['notification_reports'] = MessageNotificationReport::notifications()->with(array('sendUser','notification'))->get(); 
        //$this->mnr->skipCriteria(false)->getByCriteria($criteria)->with(array('sendUser','notification'))->all(); 
         
        //echo "<pre>"; print_r($data['notification_reports']); echo "</pre>"; die;
        return view('message.index', ['data' => $data]);
    }
    public function loadModal(Request $request, $action, $id){
        if($action == 'create'){            
           $results = $this->department->all();
           $results['departments'] = $results;
           return view('message.modal_create', ['data' => $results]);        
       }
       if($action == 'update'){
           $res = $this->department->all();
           $dept = array();
           foreach($res as $k => $dep){
               $dept[$dep['id']] = $dep['name'];
           }
           $results['departments'] = $dept;        
           $results['message'] = $this->message->get($id);
           return view('message.modal_update', ['data' => $results]);
       }   
       if($action == 'view_message'){   
           $results = $this->mnr->with(array('sendUser','fromUser', 'message'))->find($id);
           return view('message.modal_viewmessage', ['data' => $results]);
       }
       if($action == 'sendmsg'){
           $results['departments'] = $this->department->all(); 
           $customers = $this->user->all(); //DB::table('users') ->where('department_id', '=', $id) ->get();
           $results['customers'] = $customers;
           return view('message.modal_sendmessage', ['data' => $results]);
       }
       if($action == 'view_user_message'){   
           $results = $this->mnr->find($id);           
           return view('message.modal_view_user_message', ['data' => $results]);
       }
       
       if($action == 'create_notification'){
           return view('message.modal_createnotification');
       }
       if($action == 'update_notification'){           
           $results['message'] = $this->message->get($id);
           return view('message.modal_updatenotification', ['data' => $results]);
       }
    }
    public function create(MessagePostRequest $request){
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $post_data = array();
            $input = $request->all();
            $post_data = $input;
            //unset($post_data['_wysihtml5_mode']);
            //_wysihtml5_mode
//            $post_data['title'] = $request->input('title', null);
//            $pos$post_datat_data['content'] = $request->input('content', null);
//            $post_data['department'] = $request->input('department', 0);
//            $post_data['type'] = $request->input('type');
//            $id = $request->input('id', null);
            if(empty($post_data['type'])){
                $post_data['type'] = 'Message';
            }           
            
            
            $user = Auth::user();
            $post_data['created_by'] = $user->id;
            $post_data['created_at'] = date('Y-m-d H:i:s');
            $id = $post_data['id'];
            $response = $this->message->createOrUpdate($post_data, $id);
            echo json_encode($response);
          }
    }
    
    /*public function sendmessage(Request $request){
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $post_data = array();
            $post_data['send_to'] = $request->input('customer', null);
            $post_data['msg_ids'] = $request->input('msg_ids', null);
            $post_data['department'] = $request->input('department', null);
            $user = Auth::user();
            $post_data['send_from'] = $user->id;
            $post_data['created_at'] = date('Y-m-d H:i:s');
            $response = $this->message->saveSendMessages($post_data);
            //var_dump($response); die;
            if(!empty($msg_ids)){
                foreach ($msg_ids as $id){
                    $a = Message::findOrFail($id);
                    $data['content'] = $a->content;
                    $data['title'] = $a->title;
                    $data['customer'] = $customer;
                    Mail::send('emails.message', $data, function ($message) use ($data) {
                        $message->from('b4bhaskarr@gmail.com', 'Iboomerang');
                        $message->to($data['customer'])->subject($data['title']);
                    });  
                }
            }
            
            //$response['status'] = 1;
            //$response['msg'] = 'Message sent successfully!';
            echo json_encode($response);
          }
    }*/
    public function get_message_by_department($id){
        $messages = $this->message->getMessageByDepartmentId($id);
            $str = '';
            $response = array();
            if(count($messages)){
                foreach($messages as $msg){
                   $str .= '<option value="'.$msg->id.'">'.$msg->title.'</option>'; 
                }
                $response['status'] = 1;
                $response['messages'] = $str;
            }else{
                $response['status'] = 0;
                $response['messages'] = '<option value="">-No Message Exists-</option>';
            }
            echo json_encode($response);
    }
    public function get_notification_event_by_type($id){
        return view('message.notification_event_type', ['type' => $id]);
    }
    
}
