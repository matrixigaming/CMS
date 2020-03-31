<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; //Laravel Filesystem
use App\Http\Requests\CustomerPostRequest;
use App\Repositories\Customer\CustomerRepository as Customer;
use App\Repositories\Game\Game1Repository as Game;
use App\Repositories\User\User1Repository as User1;
use App\Repositories\CustomerOtp\CustomerOtpRepository as CustomerOtp;
use App\Repositories\PaymentLog\PaymentLogRepository as PaymentLog;

use Auth;
class CustomerController extends Controller
{
    private $customer;
    private $game;
    private $user1;
    private $customerOtp;
    private $paymentLog;
    public function __construct(Customer $customer, Game $game, User1 $user1, CustomerOtp $customerOtp, PaymentLog $paymentLog)
    {
        $this->middleware('auth', ['except' => ['playerLogin', 'ajaxLoginPost', 'loginPost','game_list', 'play', 'playerLogout','playerHomePage','convert_win_credit']]);
        $this->customer = $customer;
        $this->game = $game;
        $this->user1 = $user1;
        $this->customerOtp = $customerOtp;
        $this->paymentLog = $paymentLog;
        parent::__construct();
    }
    public function playerLogin(){
        //return redirect('/player');
         return view('customer.login');
    }
    
    public function playerHomePage(){
        $customerInfoSession = session('customer_info');
        $customerInfo = isset($customerInfoSession) && !empty($customerInfoSession) ? $this->customer->findBy('code', $customerInfoSession['code']) : [];
        $gameData = $this->game->all();
        foreach($gameData as $gd){
            $gameDataArr[] = $gd;
        }
        if(!empty($customerInfo)){
            $post_data = ['last_login'=>date('Y-m-d H:i:s')];
            $this->customer->update($post_data, $customerInfo->id);
        }
        $gameDataInParts = array_chunk($gameDataArr, 8);
        $shopInfo = !empty($customerInfo) ? $this->user1->find($customerInfo->shop_id) : [];
        $totalWins = !empty($shopInfo) && $shopInfo->sweepstakes ? $customerInfo->win_amount : '';
        //echo "<pre>"; print_r($customerInfo); echo $customerInfo->win_amount;
        return view('customer.home', ['cust_data' => $customerInfo, 'game_data'=>$gameDataInParts, 'totalWins'=>$totalWins]);
    }
    
    /*public function ajaxLoginPost(Request $request){
        if($request->isXmlHttpRequest() && $request->isMethod('post')){
            $post_data = $request->all();
            $key_code = $post_data['key_code'];
            $customerInfo = $this->customer->findBy('code', $key_code); 
            $returnData['status'] = false;
            $returnData['msg'] = 'Invalid request';
            //echo "<pre>"; print_r($customerInfo); print_r($customerInfo); die;
            if($customerInfo && $customerInfo->active){                
                if($customerInfo->balance > 0 ){
                    $cInfo = [ 'id'=>$customerInfo->id,
                                'shop_id' => $customerInfo->shop_id,
                                'distributor_id' => $customerInfo->distributor_id,
                                'name' => $customerInfo->name,
                                'code' => $customerInfo->code,
                                'email' => $customerInfo->email,
                                'mobile' => $customerInfo->mobile,
                                'balance' => $customerInfo->balance];
                    session(['customer_info' => $cInfo]);
                    $returnData['status'] = true;
                    $returnData['msg'] = 'Welcome, You are logged in.';
                    $post_data = ['last_login'=>date('Y-m-d H:i:s')];
                    $this->customer->update($post_data, $customerInfo->id);
                }else{
                    $returnData['msg'] = 'Low balance, Please contact store owner.';                        
                }                               
            }else{
                $returnData['msg'] = 'Invalid Code.';
            }
        }
        echo json_encode($returnData);
    }*/
    
    public function ajaxLoginPost(Request $request){
        if($request->isXmlHttpRequest() && $request->isMethod('post')){
            $post_data = $request->all();
            $key_code = $post_data['key_code'];
            $customerInfo = $this->customer->findBy('code', $key_code); 
            $id_login_verbiages = isset($post_data['id_login_verbiages']) && !empty($post_data['id_login_verbiages']) ? $post_data['id_login_verbiages'] : '';
            if(!empty($id_login_verbiages) && isset($id_login_verbiages)){
                if($customerInfo && $customerInfo->active){                
                    if($customerInfo->balance > 0 ){
                        $cInfo = [ 'id'=>$customerInfo->id,
                                    'shop_id' => $customerInfo->shop_id,
                                    'distributor_id' => $customerInfo->distributor_id,
                                    'name' => $customerInfo->name,
                                    'code' => $customerInfo->code,
                                    'email' => $customerInfo->email,
                                    'mobile' => $customerInfo->mobile,
                                    'balance' => $customerInfo->balance,
                                    'login_verbiage_id' => $id_login_verbiages
                                ];
                        session(['customer_info' => $cInfo]);
                        $returnData['status'] = true;
                        $returnData['msg'] = 'Welcome, You are logged in.';
                        $post_data = ['last_login'=>date('Y-m-d H:i:s')];
                        $this->customer->update($post_data, $customerInfo->id);
                    }else{
                        $returnData['msg'] = 'Low balance, Please contact store owner.';                        
                    }                               
                }
            }else{
                $shopInfo = !empty($customerInfo) ? $this->user1->find($customerInfo->shop_id) : [];
                
                if($shopInfo && ($shopInfo->sweepstakes == 1)){
                    $id_login_verbiages =$shopInfo->login_verbiage_id;
                    $customerLoginVerbiages = $this->customer->loginVerbiages($id_login_verbiages);
//                echo "<pre>"; print_r($customerLoginVerbiages[0]->name); die;
                    if(empty($customerLoginVerbiages)){
                        $returnData['status'] = false;
                        $returnData['msg'] = 'Invalid data';
                    }else{
                        $returnData['status'] = true;
                        $returnData['msg'] = '';
                        $returnData['msgHtml'] = '<input type="hidden" name="id_login_verbiages" value="'.$id_login_verbiages.'" /><div >'
                        . '<div class="agree-content">'.$customerLoginVerbiages.''
                        . '</div><div class="mt-4"> <a href="Done" class="float-left loginbtnnew" data-modaltype="login-customer" id="done">AGREE</a>'
                        . '<a href="/player" class="thoughtbot float-right">DISAGREE</a></div></div>';
                    }
                    
                }else{
                    if($customerInfo && $customerInfo->active){                
                        if($customerInfo->balance > 0 ){
                            $cInfo = [ 'id'=>$customerInfo->id,
                                        'shop_id' => $customerInfo->shop_id,
                                        'distributor_id' => $customerInfo->distributor_id,
                                        'name' => $customerInfo->name,
                                        'code' => $customerInfo->code,
                                        'email' => $customerInfo->email,
                                        'mobile' => $customerInfo->mobile,
                                        'balance' => $customerInfo->balance];
                            session(['customer_info' => $cInfo]);
                            $returnData['status'] = true;
                            $returnData['msg'] = 'Welcome, You are logged in.';
                            $post_data = ['last_login'=>date('Y-m-d H:i:s')];
                            $this->customer->update($post_data, $customerInfo->id);
                        }else{
                            $returnData['msg'] = 'Low balance, Please contact store owner.';                        
                        }                               
                    }
                    else{
                        $returnData['msg'] = 'Invalid Code.';
                    }
                }
            }          
        }
        echo json_encode($returnData);
    }
    
    public function ajaxLoginPost_withOTP(Request $request){
        if($request->isXmlHttpRequest() && $request->isMethod('post')){
            $post_data = $request->all();
            $key_code = $post_data['key_code'];
            $otp = $post_data['otp'];
            $customerInfo = $this->customer->findBy('code', $key_code); 
            $returnData['status'] = false;
            $returnData['msg'] = 'Invalid request';
            //echo "<pre>"; print_r($customerInfo); print_r($customerInfo); die;
            if($customerInfo && $customerInfo->active){
                $otp_valid_time = config('constants.otp_valid_time'); 
                $otpData = $this->customerOtp->validateOtp($customerInfo->id, $customerInfo->shop_id, $otp, $otp_valid_time);
                //echo "<pre>"; print_r($otpData); die;
                if(!empty($otpData)){
                    $otpUdata = ['is_used' =>1];
                    $this->customerOtp->update($otpUdata, $otpData[0]->id);
                    if($customerInfo->balance > 0 ){
                        $cInfo = [ 'id'=>$customerInfo->id,
                                    'shop_id' => $customerInfo->shop_id,
                                    'distributor_id' => $customerInfo->distributor_id,
                                    'name' => $customerInfo->name,
                                    'code' => $customerInfo->code,
                                    'email' => $customerInfo->email,
                                    'mobile' => $customerInfo->mobile,
                                    'balance' => $customerInfo->balance];
                        session(['customer_info' => $cInfo]);
                        $returnData['status'] = true;
                        $returnData['msg'] = 'Welcome, You are logged in.';
                    }else{
                        $returnData['msg'] = 'Low balance, Please contact store owner.';                        
                    }
                }else{
                    $returnData['msg'] = 'One time password is invalid or expired, please contact store owner.';
                }                
            }else{
                $returnData['msg'] = 'Invalid Code.';
            }
        }
        echo json_encode($returnData);
    }

    public function loginPost(Request $request){
        if($request->isMethod('post')){
            $post_data = $request->all();
            $key_code = $post_data['key_code'];
            $otp = $post_data['otp'];
            $customerInfo = $this->customer->findBy('code', $key_code);            
            //echo "<pre>"; print_r($customerInfo); print_r($otpData); die;
            if($customerInfo){
                $otp_valid_time = config('constants.otp_valid_time'); 
                $otpData = $this->customerOtp->validateOtp($customerInfo->id, $customerInfo->shop_id, $otp, $otp_valid_time);
                //echo "<pre>"; print_r($otpData); die;
                if(!empty($otpData)){
                    $otpUdata = ['is_used' =>1];
                    $this->customerOtp->update($otpUdata, $otpData[0]->id);
                    if($customerInfo->balance > 0 ){
                        $cInfo = [ 'id'=>$customerInfo->id,
                                    'shop_id' => $customerInfo->shop_id,
                                    'distributor_id' => $customerInfo->distributor_id,
                                    'name' => $customerInfo->name,
                                    'code' => $customerInfo->code,
                                    'email' => $customerInfo->email,
                                    'mobile' => $customerInfo->mobile,
                                    'balance' => $customerInfo->balance];
                        session(['customer_info' => $cInfo]);
                        return redirect('/player/game_list');
                    }else{
                        return redirect('/player/login')->with('status', 'Low balance, Please contact store owner.');
                        //die('Low balance');
                    }
                }else{
                    return redirect('/player/login')->with('status', 'One time password is invalid or expired, please contact store owner.');
                }                
            }else{
                return redirect('/player/login')->with('status', 'Invalid User');
                //die('Invalid Code');
            }
        }else{
            return redirect('/player/login')->with('status', 'Invalid request');
            //die('invalid request');
        }
    }
    
    public function game_list(Request $request){
        $customerInfoSession = session('customer_info');
        $customerInfo = $this->customer->findBy('code', $customerInfoSession['code']);
        //echo "<pre>"; print_r($customerInfo); die;
        if($this->_isCustomerLoggedIn()){
            $gameData = $this->game->all();
            return view('customer.game_list', ['cust_data' => $customerInfo, 'game_data'=>$gameData]);
        }else{
            return redirect('/player/login');
        }        
    }
    protected function _isCustomerLoggedIn(){
        $customerInfo = session('customer_info');
        return isset($customerInfo['id']) && !empty($customerInfo['id']) ? true : false;
    }

    public function play(Request $request){        
        //echo "<pre>"; print_r($customerInfo); die;
        if($this->_isCustomerLoggedIn()){
           if($request->isMethod('post')){
                $post_data = $request->all();
                $customerInfo = session('customer_info');
                
                    $u_data = ['last_login'=>date('Y-m-d H:i:s')];
                    $this->customer->update($u_data, $customerInfo['id']);
                
                return view('customer.play', ['data' => $post_data, 'cust_data'=>$customerInfo]);
            }else{
                return redirect('/player');
            } 
        }else{
            return redirect('/player');
        }
        //echo "<pre>"; print_r($customerInfo1); die;
        
    }
    public function playerLogout(){        
        $cInfo = [];
        session(['customer_info' => $cInfo]);
        $customerInfo = session('customer_info');
        //print_r($customerInfo); die;
        return redirect('/player');
    }

    


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function clist(){  //echo "<pre>";
        $user = Auth::user(); 
        $hasShop = $user->hasRole(['Shop']); 
        if($hasShop){
            $customerData = [];
            $result = $this->customer->findAllBy('shop_id', $user->id); 
            foreach($result as $k=>$r){                
                $rechargeDetails = $this->customer->getLastRechargeDetails($r->id); 
                if(!empty($rechargeDetails)){
                    $lastLogin = $r->last_login;
                    $lastRecharge = isset($rechargeDetails['created_at'])? $rechargeDetails['created_at'] : null;
                    //var_dump($lastLogin, $lastRecharge);
                    $rechargeDetails['can_edit'] = (empty($lastLogin)) || (strtotime($lastLogin) < strtotime($lastRecharge)) ? 1 : 0;
                    if($rechargeDetails['can_edit']){
                        $gameLog = $this->customer->getLastGameServerLog($r->id);
                        if(!empty($gameLog)){
                            $lastGameAction = isset($gameLog['created_at'])? $gameLog['created_at'] : null;
                            $rechargeDetails['can_edit'] = (empty($lastGameAction)) || (strtotime($lastGameAction) < strtotime($lastRecharge)) ? 1 : 0;
                        }
                    }
                }
                
                $customerData[] = ['id'=>$r->id, 
                    'shop_id'=>$r->shop_id,
                    'distributor_id'=>$r->distributor_id,
                    'name'=>$r->name,
                    'code'=>$r->code,
                    'email'=>$r->email,
                    'mobile'=>$r->mobile,
                    'balance'=>$r->balance,
                    'win_amount'=>$r->win_amount,
                    'active'=>$r->active,
                    'last_login'=>$r->last_login,
                    'created_at'=>$r->created_at,
                    'rechargeDetails'=>$rechargeDetails
                    ];
                //echo "<pre>"; print_r($rechargeDetails); echo "</pre>";
                
            }
            //$result['rechargeDetails'] = $rechargeDetails;
            //echo "<pre>"; print_r($customerData); echo "</pre>";die;
            return view('customer.list', ['data' => $customerData, 'loggedInUser'=>$user]);
        }
    }
    public function getCustomerListShopWise(Request $request, $id) {
        $data = $this->customer->findAllBy('shop_id', $id);
        $str = '<option value="">Select Customer</option>';
        foreach($data as $customers){
            $str .= '<option value="'.$customers->id.'">'.$customers->name.'</option>';
        }
        echo $str;
        die;
    }
    public function loadModal(Request $request, $action, $id)
    {
        $loggedInUser = Auth::user();
        if($action == 'create_customer'){  
            return view('customer.modal_create', ['loggedInUser'=>$loggedInUser]);        
        }
        if($action == 'update_balance'){
            $results = $this->customer->find($id);    
            $isBouncebackAvailable = $this->customer->isBouncebackAvailable($id);
            return view('customer.modal_update', ['data' => $results,'loggedInUser'=>$loggedInUser, 'isBouncebackAvailable'=>$isBouncebackAvailable]);
        }
        if($action == 'reverse_balance'){
            $results = $this->customer->find($id);           
            return view('customer.modal_reverse_balance', ['data' => $results, 'transaction_type'=>'Reverse']);
        }
        if($action == 'win_balance'){
            $results = $this->customer->find($id);           
            return view('customer.modal_reverse_balance', ['data' => $results, 'transaction_type'=>'Win','loggedInUser'=>$loggedInUser]);
        }
        if($action == 'generate_otp'){
            $results = $this->customer->find($id);           
            return view('customer.modal_generate_otp', ['data' => $results]);
        }
        if($action == 'customer_update'){
            $results = $this->customer->find($id);           
            return view('customer.modal_customer_update', ['data' => $results]);
        } 
        if($action == 'adjust_transaction'){
            $results = $this->paymentLog->find($id);           
            return view('customer.modal_adjust_transaction', ['data' => $results]);
        } 
    }
    
    public function adjust_transaction(Request $req){
            if ($req->isXmlHttpRequest() && $req->isMethod('post')) {
                
                $user = Auth::user();
                $post_data = $req->all();                
                $id = $post_data['id'];
                $amount = $post_data['amount'];
                if($amount<=0){
                   $resp['status'] = 0;
                   $resp['msg'] = 'Amount must be greater than 0.'; 
                }else{
                    $isBounceBack = isset($post_data['bounce_back'])?1:0;
                    $isReverseAmount = isset($post_data['reverse_amount'])?1:0;
                    $results = $this->paymentLog->find($id);
                    if(empty($results)){
                        $resp['status'] = 0;
                        $resp['msg'] = 'Invalid transaction id..';
                        echo json_encode($resp); die;
                    }
                    $oldAmount = $results->amount;
                    $oldBBAmount = $results->bounceback_amount;
                    $customerId = $results->customer_id;
                    $customerResult = $this->customer->find($customerId); 
                    if(empty($customerResult)){
                        $resp['status'] = 0;
                        $resp['msg'] = 'Invalid customer id..';
                        echo json_encode($resp); die;
                    }
                    $currentBalance = $customerResult->balance; 
                    $rechargeDetails = $this->customer->getLastRechargeDetails($customerId); 
                    unset($post_data['bounce_back']);  unset($post_data['_token']); unset($post_data['reverse_amount']);
                    if(!empty($rechargeDetails)){
                        $lastLogin = $customerResult->last_login;
                        $lastRecharge = isset($rechargeDetails['created_at'])? $rechargeDetails['created_at'] : null;
                        $rechargeDetailsCanEdit = (empty($lastLogin)) || (strtotime($lastLogin) < strtotime($lastRecharge)) ? 1 : 0;
                        if($rechargeDetailsCanEdit){
                            $gameLog = $this->customer->getLastGameServerLog($customerId);
                            if(!empty($gameLog)){
                                $lastGameAction = isset($gameLog['created_at'])? $gameLog['created_at'] : null;                                //var_dump($lastLogin, $lastRecharge);
                                $rechargeDetailsCanEdit = (empty($lastGameAction)) || (strtotime($lastGameAction) < strtotime($lastRecharge)) ? 1 : 0;
                            }
                        }
                    }
                    if($rechargeDetailsCanEdit){
                        if($isReverseAmount){
                            if($currentBalance >= ($oldAmount + $oldBBAmount)){
                                $customerBalanceUpdate  = ($currentBalance) - ($oldAmount + $oldBBAmount);
                                $cdata = ['balance'=>$customerBalanceUpdate, 'name'=>$customerResult->name];
                                $this->customer->update($cdata, $customerId);
                                $this->paymentLog->delete($id);

                                $storeOwnerCredit = $user->available_credit + $oldAmount;
                                $userData['available_credit'] = $storeOwnerCredit;
                                $this->user1->update($userData, $user->id);
                                $resp['status'] = 1;
                                $resp['msg'] = 'Transaction reversed successfully.';
                            }else{
                                $resp['status'] = 0;
                                $resp['msg'] = 'You can not edit this transaction now..';
                            }                            
                        }else{
                            if($isBounceBack){                        
                                $bouncebackAmount = $this->customer->getBounceBackAmount($amount);
                                $topupWithBounceBack = $bouncebackAmount + $amount;
                                $post_data['bounceback_amount'] = $bouncebackAmount;
                                $customerBalanceUpdate  = ($currentBalance + $topupWithBounceBack) - ($oldAmount + $oldBBAmount);
                            }else{
                                $customerBalanceUpdate  = ($currentBalance + $amount) - ($oldAmount + $oldBBAmount);
                            }

                            $cdata = ['balance'=>$customerBalanceUpdate, 'name'=>$customerResult->name];
                            $this->paymentLog->update($post_data, $id);
                            //echo "<pre>"; print_r($post_data); echo "</pre>"; die;
                            $this->customer->update($cdata, $customerId);

                            $storeOwnerCredit = $user->available_credit + $oldAmount - $amount;
                            $userData['available_credit'] = $storeOwnerCredit;
                            $this->user1->update($userData, $user->id);
                            $resp['status'] = 1;
                            $resp['msg'] = 'Transaction corrected successfully.';
                        }
                    }else{
                        $resp['status'] = 0;
                        $resp['msg'] = 'You can not edit this transaction now..';
                    }
                }                  
            }else{
                $resp['status'] = 0;
                $resp['msg'] = 'Invalid request data, Please try again.';
            }
             echo json_encode($resp); die;
    }

        public function convert_win_credit(Request $req){
        if($this->_isCustomerLoggedIn()){
            if ($req->isXmlHttpRequest() && $req->isMethod('post')) {
                $post_data = $req->all();
                //echo "<pre>"; print_r($post_data); die;
                $customerInfo = session('customer_info');
                $id = $customerInfo['id'];
                $results = $this->customer->find($id); 
                $currentBalance = (float) $results->balance;
                $winAmount = (float) $results->win_amount;
                $reverse_amount = (float) $post_data['reverse_amount'];
                $response = 0;
                if($reverse_amount > $winAmount){
                    $resp['status'] = 0;
                    $resp['msg'] = 'Entered amount must be less than winning amount.';
                    echo json_encode($resp); die;
                }
                if($reverse_amount > 0 && $reverse_amount <= $winAmount){
                    $updateArray['balance'] = $currentBalance + $reverse_amount;
                    $updateArray['win_amount'] = $winAmount - $reverse_amount;
                    $response = $this->customer->update($updateArray, $id);
                }
                if($response){
                    $resp['status'] = 1;
                    $resp['msg'] = 'Balance updated successfully.';                
                }else{
                    $resp['status'] = 0;
                    $resp['msg'] = 'Invalid Data, Please try again.';
                }
                //echo "<pre>"; print_r($customerInfo); echo "</pre>"; die;
            }else{
                $resp['status'] = 0;
                $resp['msg'] = 'Invalid request, Please try again.';
            }
        }else{
            $resp['status'] = 0;
            $resp['msg'] = 'Session expired, Please login again.';
        }
        echo json_encode($resp); die;
    }

        public function update_balance(CustomerPostRequest $request){
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $post_data = $request->all();
            $id = $post_data['id'];
            $results = $this->customer->find($id); 
            $currentBalance = (float) $results->balance;
            $winAmount = (float) $results->win_amount;
            $transaction_type = $post_data['transaction_type'];
            $reverse_amount = (float) $post_data['reverse_amount'];
            
            //echo '$currentBalance = '.$currentBalance. ', $winAmount = '.$winAmount. ', $reverse_amount = '.$reverse_amount;
            //echo "<pre>"; var_dump($reverse_amount <= $winAmount); print_r($post_data);
            if(($transaction_type == 'Reverse' && $reverse_amount > $currentBalance)){
                $resp['status'] = 0;
                $resp['msg'] = 'Paid amount must be less than available balance.';
                echo json_encode($resp); die;
            }
            if(($transaction_type == 'Win' && $reverse_amount > $winAmount)){
                $resp['status'] = 0;
                $resp['msg'] = 'Paid amount must be less than winning amount.';
                echo json_encode($resp); die;
            }
            $pFlag = false;
            if($transaction_type == 'Reverse' && $reverse_amount <= $currentBalance){
                $updateArray['balance'] = $currentBalance - $reverse_amount;
                $pFlag = true;
            }else if($transaction_type == 'Win' && $reverse_amount <= $winAmount){
                $payMethod = $post_data['pay_method'];
                if($payMethod == 'add-to-credit-balance'){
                    $updateArray['balance'] = $currentBalance + $reverse_amount;
                    $updateArray['win_amount'] = $winAmount - $reverse_amount;
                }else if($payMethod == 'pay-cash'){
                    $updateArray['win_amount'] = $winAmount - $reverse_amount;
                    $pFlag = true;
                }
                
            }
            $response = $this->customer->update($updateArray, $id);
            if($response){
                $resp['status'] = 1;
                $resp['msg'] = 'Balance updated successfully.';
                if($pFlag){
                    $pLogData = ['distributor_id'=>$results->distributor_id,'shop_id'=>$results->shop_id,'customer_id'=>$id, 'amount'=>$reverse_amount, 'type'=>'Paid'];
                    $this->paymentLog->create($pLogData);
                }
            }else{
                $resp['status'] = 0;
                $resp['msg'] = 'Invalid Data, Please try again.';
            }
            //echo "<pre>"; print_r($post_data); die;
            echo json_encode($resp); die;
        }
    }
    public function update_customer(CustomerPostRequest $request){
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $post_data = array();
            $post_data = $request->all();
            $id = $post_data['id'];
            $user = Auth::user();
            $loggedInUserId = $user->id;
                if(isset($id) && !empty($id)){
                    unset($post_data['_token']);
                    $results = $this->customer->find($id); 
                    if(empty($results)){
                        $resp['status'] = 0;
                        $resp['msg'] = 'Invalid Data, Please try again.';
                    }else{
                        $response = $this->customer->update($post_data, $id);
                    }                    
                }else{
                    $response = false;
                }
                if($response){
                    $resp['status'] = 1;
                    $resp['msg'] = 'Customer updated successfully.';
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

    public function create(CustomerPostRequest $request){
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $post_data = array();
            $post_data = $request->all();
            $id = $post_data['id'];
            $user = Auth::user();
            $loggedInUserId = $user->id;
            $loggedInUserCredit = $user->available_credit;
            $creditTobeAmount = $post_data['balance'];
            $bouncebackAmount = 0;
            if($creditTobeAmount > $loggedInUserCredit){
                $resp['status'] = 0;
                $resp['msg'] = 'Credit Amount can not be greater than your available credit amount.';
            }else{
                //echo "<pre>";  print_r($post_data); die;
                $isBounceBack = isset($post_data['bounce_back'])?1:0;
                $otp = isset($post_data['otp'])?$post_data['otp']:random_int(1001, 9999);
                unset($post_data['bounce_back']); unset($post_data['otp']);
                if(isset($id) && !empty($id)){ 
                    $customerId = $id;
                    unset($post_data['_token']);
                    $results = $this->customer->find($id); 
                    $currentBalance = $results->balance; 

                    if($isBounceBack){
                        //$bouncBackPercent = 20; //config('constants.customer.bounce_back_percent');
                        $topup = $post_data['balance'];
                        $bouncebackAmount = $this->customer->getBounceBackAmount($topup, $id);
                        $topupWithBounceBack = $bouncebackAmount + $topup;//round($topup + (($topup * $bouncBackPercent)/100), 2);
                        $post_data['balance'] = $currentBalance + $topupWithBounceBack;
                    }else{
                        $post_data['balance'] = round($currentBalance + $post_data['balance'], 2);
                    }
                    $response = $this->customer->update($post_data, $id);
                }else{                
                    $post_data['shop_id'] = $user->id;
                    $shopCode = str_pad($user->shop_code, 3, "0", STR_PAD_LEFT);
                    $post_data['distributor_id'] = $user->created_by;
                    /*$custName = $post_data['name'];
                    $custNameArr = explode(' ', $custName);
                    $firstName = array_shift($custNameArr);
                    $firstName = strtolower(trim($firstName));*/
                    $customerCode = random_int(10001, 99999).$shopCode;
                    $isCustomerExist = $this->customer->isCustomerExist($customerCode);
                    //var_dump($isCustomerExist); die;
                    //$counter = 1;
                    if(!$isCustomerExist){
                        $post_data['code'] = $customerCode;
                    }else{
                        do{
                            $customerCode = random_int(10001, 99999).$shopCode;//$firstName.$counter;
                            $isCustomerExist = $this->customer->isCustomerExist($customerCode);
                            //$counter++;
                        }while ($isCustomerExist);
                         $post_data['code'] = $customerCode;
                    }
                    //$post_data['code'] = strtoupper(uniqid());
                    //$post_data['balance'] = round($post_data['balance'], 2);
                    if($isBounceBack){
                        //$bouncBackPercent = 20; //config('constants.customer.bounce_back_percent');
                        $topup = $post_data['balance'];
                        $bouncebackAmount = $this->customer->getBounceBackAmount($topup);
                        $topupWithBounceBack = $bouncebackAmount + $topup;//round($topup + (($topup * $bouncBackPercent)/100), 2);
                        $post_data['balance'] = $topupWithBounceBack;
                    }else{
                        $post_data['balance'] = round($post_data['balance'], 2);
                    }
                    $response = $this->customer->create($post_data);
                }
                if(isset($response->id) && !empty($response->id)){
                    $customerId = $response->id;
                    //$postOtpData = ['shop_id'=>$user->id, 'customer_id'=>$customerId, 'otp'=>$otp];
                    //$response1 = $this->customerOtp->create($postOtpData);
                    $resp['status'] = 1;
                    $resp['msg'] = 'Customer created successfully.';
                }else if($response){
                    $resp['status'] = 1;
                    $resp['msg'] = 'Customer updated successfully.';
                }else{
                    $resp['status'] = 0;
                    $resp['msg'] = 'Invalid Data, Please try again.';
                }
                if(isset($resp['status']) && $resp['status']){
                    $updateCreditAmount['available_credit'] = $loggedInUserCredit - $creditTobeAmount;
                    $response = $this->user1->update($updateCreditAmount, $loggedInUserId);
                    $pLogData = ['distributor_id'=>$user->created_by,'shop_id'=>$user->id,'customer_id'=>$customerId, 'amount'=>$creditTobeAmount, 'bounceback_amount'=>$bouncebackAmount,'type'=>'Recieved'];
                    $this->paymentLog->create($pLogData);
                }                    
            }
            
            //echo "<pre>"; print_r($response); die;
            echo json_encode($resp);   
            //echo \Response::json($response);
            die;
          }
    } 

    public function generate_otp(Request $request){
        if ($request->isXmlHttpRequest() && $request->isMethod('post')) {
            $post_data = array();
            $post_data = $request->all();
            
            $user = Auth::user();
            $post_data['shop_id'] = $user->id;   
            //echo "<pre>"; print_r($post_data) ; die;       
            $response = $this->customerOtp->create($post_data);

            if(isset($response->id) && !empty($response->id)){
                $resp['status'] = 1;
                $resp['msg'] = 'Customer OTP generated successfully.';
            }else{
                $resp['status'] = 0;
                $resp['msg'] = 'Invalid Data, Please try again.';
            } 
            echo json_encode($resp); 
            die;
          }
    }    
}
