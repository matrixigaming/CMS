<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\MessageNotificationReport;
use Auth;
use View;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct()
    {
        //$this->middleware('auth');  
        if (Auth::check())
        {
            /*$limit = 5;
            $response['message_reports'] = MessageNotificationReport::messagesByUser()->with(array('fromUser', 'message'))->orderBy('created_at', 'DESC')->paginate($limit); 
            $response['notification_reports'] = MessageNotificationReport::notificationsByUser()->with(array('notification'))->orderBy('created_at', 'DESC')->paginate($limit); 
            
            $response['message_reports_count'] = MessageNotificationReport::messagesByUser()->get()->count();
            $response['notification_reports_count'] = MessageNotificationReport::notificationsByUser()->get()->count();
            $response['data_limit'] = $limit;
            //echo "<pre>"; print_r($response); echo "</per>"; die;
            View::share('top_data', $response);
            */
        }
        
    }
}
