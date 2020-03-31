<?php

namespace App\Http\Controllers;
use Auth;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Repositories\MessageReport\MessageReportRepositoryInterface;
use App\Repositories\NotificationReport\NotificationReportRepository as NotificationReport;
use App\Repositories\Criteria\NotificationReport\FilterNotificationByUser;
use App\Repositories\MessageNotificationReport\MessageNotificationReportRepository as MessageNotificationReport1;
use App\MessageNotificationReport;
//use App\Repositories\Department\DepartmentRepositoryInterface;
//use App\Repositories\User\UserRepositoryInterface;

class MessageReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $message_report;
    private $notification_report;
    private $mnr;
    public function __construct(MessageReportRepositoryInterface $message_report, NotificationReport $notification_report, MessageNotificationReport1 $mnr)
    {
        $this->middleware('auth');
        $this->message_report = $message_report;
        $this->notification_report = $notification_report;
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
         $message_reports = $this->message_report->all();
         return view('message_report.index', ['message_reports' => $message_reports]);
    }

    
    public function sendmessage(Request $request){
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $post_data = array();
            $input = $request->all();
            $post_data = $input;
//            $post_data['send_to'] = $request->input('customer', null);
//            $post_data['msg_ids'] = $request->input('msg_ids', null);
//            $post_data['department'] = $request->input('department', null);
            $user = Auth::user();
            $post_data['send_from'] = $user->id;
            $post_data['created_at'] = date('Y-m-d H:i:s');
            $response = $this->message_report->createOrUpdate($post_data);
            //var_dump($response); die;
            /*if(!empty($msg_ids)){
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
            }*/
            echo json_encode($response);
          }
    }
    public function messages_notification_by_user(Request $request){
        //$user = Auth::user();
        //$userId = $user->id;       
        
        if ($request->isXmlHttpRequest()) {
//            $this->notification_report->pushCriteria(new FilterNotificationByUser());
//            $this->notification_report->orderBy('created_at', 'DESC');
//            $response['notification_reports'] = $this->notification_report->with(array('user','notification'))->paginate(10);
            $data['notification_reports'] = MessageNotificationReport::notificationsByUser()->with(array('sendUser','notification'))->orderBy('created_at', 'DESC')->paginate(10); 
            return view('message_report.user_notification_ajax', ['data' => $data])->render();
        }
        $data['message_reports'] = MessageNotificationReport::messagesByUser()->with(array('sendUser','fromUser', 'message'))->orderBy('created_at', 'DESC')->get();
        $data['notification_reports'] = MessageNotificationReport::notificationsByUser()->with(array('sendUser','notification'))->orderBy('created_at', 'DESC')->paginate(10); 
//        $response['message_reports'] = $this->message_report->get_user_messages($userId);
//        $this->notification_report->pushCriteria(new FilterNotificationByUser());
//        $this->notification_report->orderBy('created_at', 'DESC');
//        $response['notification_reports'] = $this->notification_report->with(array('user','notification'))->paginate(10);//all(); 
        //$response['notification_reports'] = $this->notification_report->get_user_notification($userId); 
        //$response['notification_reports'] = $this->notification_report->paginate(10);
        //echo "<pre>"; print_r($response['notification_reports']); echo "</pre>"; die; 
        return view('message_report.user_notification', ['data' => $data]);
        
    }
    public function mark_as_read(Request $request){
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {            
            $input = $request->all();            
            $id = $input['id'];   
            $input['mark_as_read'] = 1;
            $response = $this->message_report->createOrUpdate($input, $id);
            echo json_encode($response);
        }
    }
    public function mark_as_unread(Request $request){
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {            
            $input = $request->all();            
            $id = $input['id'];   
            $input['mark_as_read'] = 0;
            $response = $this->message_report->createOrUpdate($input, $id);
            echo json_encode($response);
        }        
    }
    public function msgReportDelete($id){
        //$id = (int)$id;
        if(is_numeric($id)){
            $response = $this->message_report->msgReportDelete($id);
            echo json_encode($response); 
        }else{
           $response['status'] = 0;
           $response['msg'] = 'Invalid id.';
           echo json_encode($response);
        }               
    }
}
