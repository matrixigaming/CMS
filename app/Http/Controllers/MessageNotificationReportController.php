<?php

namespace App\Http\Controllers;
use Auth;
use App\Http\Requests;
use Illuminate\Http\Request;
//use App\Repositories\MessageReport\MessageReportRepositoryInterface;
//use App\Repositories\NotificationReport\NotificationReportRepository as NotificationReport;
use App\Repositories\MessageNotificationReport\MessageNotificationReportRepository as MessageNotificationReport;
use App\Repositories\Criteria\NotificationReport\FilterNotificationByUser;

class MessageNotificationReportController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $message_notification_report;
    
    public function __construct(MessageNotificationReport $message_notification_report)
    {
        $this->middleware('auth');
        $this->message_notification_report = $message_notification_report;
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//         $message_reports = $this->message_report->all();
//         return view('message_report.index', ['message_reports' => $message_reports]);
    }

    
    public function sendmessage(Request $request){
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $post_data = array();
            $input = $request->all();
            $post_data = $input;
            $post_data['type'] = 'Message';
            $user = Auth::user();
            $post_data['from_user_id'] = $user->id;
            $post_data['created_at'] = date('Y-m-d H:i:s');
            unset($post_data['department']);
            $response = $this->message_notification_report->create($post_data); 
            if(isset($response->id) && !empty($response->id)){
                $resp['status'] = 1;
                $resp['msg'] = 'Message send successfully.';
            }else if($response){
                $resp['status'] = 1;
                $resp['msg'] = 'Message updated successfully.';
            }else{
                $resp['status'] = 0;
                $resp['msg'] = 'Invalid Data, Please try again.';
            }
            //echo "<pre>"; print_r($response); die;
            echo json_encode($resp);   
            //echo \Response::json($response);
            die;
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
          }
    }
    public function messages_notification_by_user(Request $request){
        $user = Auth::user();
        $userId = $user->id;
        if ($request->isXmlHttpRequest()) {
            $this->notification_report->pushCriteria(new FilterNotificationByUser());
            $this->notification_report->orderBy('created_at', 'DESC');
            $response['notification_reports'] = $this->notification_report->with(array('user','notification'))->paginate(10);
            return view('message_report.user_notification_ajax', ['data' => $response])->render();
        }
        $response['message_reports'] = $this->message_report->get_user_messages($userId);
        $this->notification_report->pushCriteria(new FilterNotificationByUser());
        $this->notification_report->orderBy('created_at', 'DESC');
        $response['notification_reports'] = $this->notification_report->with(array('user','notification'))->paginate(10);//all(); 
        //$response['notification_reports'] = $this->notification_report->get_user_notification($userId); 
        //$response['notification_reports'] = $this->notification_report->paginate(10);
        //echo "<pre>"; print_r($response['notification_reports']); echo "</pre>"; die; 
        return view('message_report.user_notification', ['data' => $response]);
        
    }
    public function mark_as_read(Request $request){
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {            
            $input = $request->all(); 
            unset($input['_token']);
            $id = $input['id'];   
            $input['read_at'] = date('Y-m-d H:i:s');
            $res = $this->message_notification_report->update($input, $id);
            if($res){
                $response['status'] = 1;
                $response['msg'] = 'Message successfully updated.';
            }
            echo json_encode($response);
        }
    }
    public function mark_as_unread(Request $request){
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {            
            $input = $request->all();  
            unset($input['_token']);
            $id = $input['id'];   
            $input['read_at'] = null;
            $res = $this->message_notification_report->update($input, $id);
            if($res){
                $response['status'] = 1;
                $response['msg'] = 'Message successfully updated.';
            }
            echo json_encode($response);
        }        
    }
    public function msgReportDelete($id){
        //$id = (int)$id;
        if(is_numeric($id)){
            $data = $this->message_notification_report->find($id);
            if ($data->delete()) {
                $response['status'] = 1;
                $response['msg'] = 'Message deleted successfully.';
                echo json_encode($response);
            }else{
                $response['status'] = 0;
                $response['msg'] = 'There is some error to perform this action. Please try again.';
                echo json_encode($response);
            }
        }else{
           $response['status'] = 0;
           $response['msg'] = 'Invalid id.';
           echo json_encode($response);
        }               
    }
}
