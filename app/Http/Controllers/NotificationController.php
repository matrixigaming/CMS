<?php

namespace App\Http\Controllers;
use Auth;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\NotificationPostRequest;
use App\Repositories\Notification\NotificationRepository as Notification;
use App\Repositories\MessageNotificationReport\MessageNotificationReportRepository as MessageNotificationReport;

class NotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $notification;
    private $mnr;
    public function __construct(Notification $notification, MessageNotificationReport $mnr)
    {
        $this->middleware('auth');
        $this->notification = $notification;
        $this->mnr = $mnr;
        parent::__construct();
    }
    
    public function loadModal(Request $request, $action, $id)
    {
       if($action == 'create_notification'){
           return view('notification.modal_createnotification');
       }
       if($action == 'update_notification'){           
           $results = $this->notification->find($id);
           return view('notification.modal_updatenotification', ['notification_data' => $results]);
       }
    }
    public function create(NotificationPostRequest $request){
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $post_data = array();
            $post_data = $request->all();
            //unset($post_data['_wysihtml5_mode']);
            $id = $post_data['id'];
            $user = Auth::user();
            $post_data['created_by'] = $user->id;
            $imageName = '';
            if ($request->hasFile('icon')) {
                $destinationPath = config('constants.notification_icon_full_path');//base_path() . '/public/uploads/notification_icons/';
                $imageName = time() . '_' . $request->file('icon')->getClientOriginalName();
                $request->file('icon')->move($destinationPath, $imageName);
                $post_data['icon'] = $imageName;
            }
            
            if(isset($id) && !empty($id)){
                unset($post_data['_token']);                
                $response = $this->notification->update($post_data, $id);
            }else{ 
                $response = $this->notification->create($post_data);
            }
            if(isset($response->id) && !empty($response->id)){
                $resp['status'] = 1;
                $resp['msg'] = 'Notification created successfully.';
            }else if($response){
                $resp['status'] = 1;
                $resp['msg'] = 'Notification updated successfully.';
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
    
    public function get_notification_event_by_type($id){
        return view('notification.notification_event_type', ['type' => $id]);
    }
    
}
