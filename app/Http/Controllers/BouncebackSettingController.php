<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; //Laravel Filesystem
use App\Repositories\BouncebackSetting\BouncebackSettingRepository as BouncebackSetting;
use Auth;

class BouncebackSettingController extends Controller {

    private $bbsetting;

    public function __construct(BouncebackSetting $bbsetting) {
        $this->middleware('auth');
        $this->bbsetting = $bbsetting;
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function settings() {
        $user = Auth::user();
        $isAdmin = $user->hasRole(['Admin']);
        if ($isAdmin) {
            $result = $this->bbsetting->all();
            return view('bbsetting.list', ['data' => $result, 'loggedInUser' => $user]);
        } else {
            return;
        }
    }

    public function loadModal(Request $request, $action, $id) {
        $loggedInUser = Auth::user();
        if ($action == 'update_setting') {
            $results = $this->bbsetting->find($id);
            //echo "<pre>"; print_r($results); echo "</pre>"; die;
            return view('bbsetting.modal_update', ['data' => $results, 'loggedInUser' => $loggedInUser]);
        }
    }

    public function updatesettings(Request $request) {
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $post_data = array();
            $post_data = $request->all();
            $id = $post_data['id'];
            $user = Auth::user();
            $loggedInUserId = $user->id;
            $response = 0;
            if (isset($id) && !empty($id)) {
                unset($post_data['_token']);
                $results = $this->bbsetting->find($id);
                if (!empty($results))
                    $response = $this->bbsetting->update($post_data, $id);
            }
            if ($response) {
                $resp['status'] = 1;
                $resp['msg'] = 'Setting updated successfully.';
            } else {
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
