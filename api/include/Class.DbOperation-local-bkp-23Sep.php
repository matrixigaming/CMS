<?php

//require_once dirname(dirname(__FILE__)) . '../../include/constants.php';
require_once 'constants.php';

class DbOperation {

    //protected $con;

    function __construct() { //echo dirname(dirname(__FILE__)); die;
        try {
            $this->con = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
            //$this->con->exec("set names utf8");
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    private function _getFileName($name) {
        $specialChar = [' ', '@', '#', '$', '~', '%', '&', '^', '*', '(', ')', '+', '=', '{', '}', '[', ';', '"', '\'', '?', '<', '>', '.', ',', '_'];
        foreach ($specialChar as $char) {
            $name = str_replace($char, '-', $name);
        }
        return strtolower($name);
    }

    protected function _checkUser($value, $key) {
        $stmt = $this->con->prepare("SELECT * FROM user WHERE $key=:username and status=:status");
        $stmt->bindValue(':username', $value);
        $stmt->bindValue(':status', 1);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        return $userData;
    }

    protected function _getCustomerInfoById($id) {
        $sql = 'select id,shop_id,distributor_id,name,code,email,mobile,balance,win_amount,'
                . 'active,created_at,updated_at,deleted_at from customers where id=:id and active=1';
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return empty($result) ? false : $result;
    }

    protected function _haveBalance($customerInfo, $betAmount = 0) {
        $balance =  $customerInfo['balance'];
        return $balance > $betAmount ? true : false;
    }

    protected function _getShopConfiguration($shopId) {
        $sql = 'select * from users where id=:id';
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(":id", $shopId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    protected function _getGameRtpVarient($gameId, $shopId) {
        /*$sql = 'select rtpVariant from game_rtp_settings where shop_id=:shop_id and game_id=:game_id';
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(":shop_id", $shopId, PDO::PARAM_INT);
        $stmt->bindParam(":game_id", $gameId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result['rtpVariant'];
        } else {
            $sql = 'select default_rtp from games where id=:id';
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(":id", $gameId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['default_rtp'];
        }*/
        $sql = 'SELECT d.`distributor_rtp_variant` FROM `users` as d inner join  `users` as s on d.id=s.created_by where id=:shop_id';
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(":shop_id", $shopId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result && $result['distributor_rtp_variant']>0) {
            return $result['distributor_rtp_variant'];
        } else {
            $sql = 'select default_rtp from games where id=:id';
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(":id", $gameId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['default_rtp'];
        }
    }
    protected function _getGameMathUrl($gameId){
        $sql = 'select math_url from games where id=:id';
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(":id", $gameId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['math_url'];
    }

    protected function _getResponsePrivateData($userId, $shopId, $gameId){
        $sql3 = 'SELECT response_private FROM response_logs WHERE customer_id=:customer_id AND '
                        . 'shop_id=:shop_id AND '
                        . 'game_id=:game_id ORDER BY created_at DESC limit 1';
        //echo "<br />userId = ".$userId.', $shopId = '.$shopId.', $gameId = '.$gameId;
        $stmt = $this->con->prepare($sql3);
        $stmt->bindParam(":customer_id", $userId, PDO::PARAM_INT);                
        $stmt->bindParam(":shop_id", $shopId, PDO::PARAM_INT);
        $stmt->bindParam(":game_id", $gameId, PDO::PARAM_INT);              
        $stmt->execute(); 
        $resultResponsePrivate = $stmt->fetch(PDO::FETCH_ASSOC);
        //echo "<pre>11"; print_r($resultResponsePrivate); echo "</pre>"; 
        if($resultResponsePrivate && isset($resultResponsePrivate['response_private']) && !empty($resultResponsePrivate['response_private'])){
            $responsePrivate = json_decode($resultResponsePrivate['response_private']);
        }else{                    
            $responsePrivate = (object) [];
        }
        return $responsePrivate;
    }
    protected function _getJackpotRequestData($jackpotBet, $flag = true){
        $sql3 = 'SELECT response_data FROM jackpot_logs where  response_data !="" ORDER BY created_at DESC limit 1';
        //echo "<br />userId = ".$userId.', $shopId = '.$shopId.', $gameId = '.$gameId;
        $stmt = $this->con->prepare($sql3);            
        $stmt->execute(); 
        $resultResponsePrivate = $stmt->fetch(PDO::FETCH_ASSOC);
        //echo "<pre>11"; print_r($resultResponsePrivate); echo "</pre>"; 
        if($resultResponsePrivate && isset($resultResponsePrivate['response_data']) && !empty($resultResponsePrivate['response_data'])){
            $responsePrivate = json_decode($resultResponsePrivate['response_data'], true);
            if($flag){
                unset($responsePrivate['winAmount']); 
                unset($responsePrivate['winLevel']);
            }
            $responsePrivate['jackpotBet'] = $jackpotBet;
        }else{                    
            $responsePrivate = (object) [];
        }
        return $responsePrivate;
    }

    protected function _callCurl($url, $payload){
        //echo $url;
        //echo "<br />".$payload; 
        //create a new cURL resource
        $ch = curl_init($url);
        //echo "<pre>"; print_r($returnData); echo "</pre>"; 

        //attach encoded JSON string to the POST fields
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        //set the content type to application/json
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        //return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //execute the POST request
        $curlResult = curl_exec($ch);
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            $err['status'] = false;
            $err['msg'] = $error_msg;
            curl_close($ch);
            return $err;
        }
        //header("Access-Control-Allow-Origin: *");
        //close cURL resource            
        return $curlResult;
    }

    protected function _insertData($tableName,$winArray, $level=0){
        //ksort($winArray);
        if($level){
            foreach ($winArray as $key => $winSubArr) {
                $updateArrayKey = [];
                $updateFields = '';
                foreach ($winSubArr as $keys=>$values) {
                    $updateArrayKey[$keys] = $keys . '=:' . $keys;
                }
                $updateFields = implode(', ', $updateArrayKey);
                $sql = "INSERT INTO {$tableName} set {$updateFields}";
                $stmt = $this->con->prepare($sql);
                foreach ($winSubArr as $k => $v) {
                    $stmt->bindValue(':' . $k, $v);
                }
                $result = $stmt->execute();
            }                        
        }else{
            foreach ($winArray as $keys => $values) {
                $updateArrayKey[$keys] = $keys . '=:' . $keys;
            }
            $updateFields = implode(', ', $updateArrayKey);
            $sql = "INSERT INTO {$tableName} set {$updateFields}";            
            $stmt = $this->con->prepare($sql);
            foreach ($winArray as $k => $v) {
                $stmt->bindValue(':' . $k, $v);
            }
            //echo "<br />table1 = ".$tableName."<br /> sql = ".$sql; 
            $result = $stmt->execute();  
        } 
        $lastInsetId = $this->con->lastInsertId();
        return $lastInsetId;       
    }
    //
    protected function _getWinnerJackpot3($shopId, $winAmount, $jackpotLogId){
        $sql = 'select id, customer_id, game_id from jackpot_logs where created_at > date_sub(now(), interval 30 second) and shop_id=:shop_id order by id desc';
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(":shop_id", $shopId, PDO::PARAM_INT);
        $stmt->execute();  
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = [];
        if(!empty($results)){
            $winnerUsers = count($results);
            $winAmountEachUser = round(($winAmount/$winnerUsers), 2);
            foreach ($results as $key => $result) {
                $data[] =   [
                                'customer_id'=>$result['customer_id'],
                                'shop_id'=>$shopId,
                                'game_id'=>$result['game_id'],
                                'win_amount'=>$winAmountEachUser,
                                'win_type'=>'Jackpot_level_3',
                                'jackpot_log_id'=>$jackpotLogId
                            ];
            }
        }
        return $data;
    }

    public function cms_engine_call($request, $response) {
        $returnData = array();
        $queryParameter = $request->getParsedBody();
       //echo "<pre>";        
        $userId = isset($queryParameter['user_id']) ? $queryParameter['user_id'] : '';
        $gameId = isset($queryParameter['game_id']) ? $queryParameter['game_id'] : '';
        $betAmount = isset($queryParameter['stake']) ? $queryParameter['stake'] : 0;
        $jackpotBet = isset($queryParameter['jackpotBet']) ? $queryParameter['jackpotBet'] : 0;

        if(empty($userId) || empty($gameId)){
            $returnData['status'] = false;
            $returnData['message'] = 'User Id or Game Id is missing.';
            return $returnData;
        }
        $gameEngineUrl = $this->_getGameMathUrl($gameId);
        if(empty($gameEngineUrl)){
            $returnData['status'] = false;
            $returnData['message'] = 'Game math url is not set by admin.';
            return $returnData;
        }
        $action = $queryParameter['action'];
        //$stake = isset($queryParameter['stake']) ? $queryParameter['stake'] : 0;
        //$pick = isset($queryParameter['pick']) ? $queryParameter['pick'] : 0;
        $customerInfo = $this->_getCustomerInfoById($userId);        
        
        $requestLogId = 0;
        if ($customerInfo !== false) {
            if ($this->_haveBalance($customerInfo, $betAmount)) {
                $shopId = $customerInfo['shop_id'];
                $shopConfiguration = $this->_getShopConfiguration($shopId);
                if($action == 'spin' && $shopConfiguration['jackpot'] && !$jackpotBet){
                    $returnData['status'] = false;
                    $returnData['message'] = 'Jackpot is active for this shop but jackpot bet amount is missing.';
                    return $returnData;
                }
                $gameRtp = $this->_getGameRtpVarient($gameId, $shopId);
                $balance = $customerInfo['balance'];
                //var_dump($gameRtp);
                foreach($queryParameter as $key=>$value){
                    $returnData['public'][$key] = $value;
                }
                
                $returnData['public']['config'] = [
                    "currency" => '',
                    "defaultStake" => 1,
                    "stakeList" => [1, 2, 5, 10, 25, 50],
                    "rtpVariant" => $gameRtp,
                    "nudgeFeature" => $shopConfiguration['nudgeFeature'] ? true : false,
                    "preRevealWithSkillStop" => $shopConfiguration['preRevealWithSkillStop'] ? true : false,
                    "jackpot" => $shopConfiguration['jackpot'] ? true : false,
                    "jackpotUrl" => ""
                ];
                
                $returnData['private'] = $this->_getResponsePrivateData($userId, $shopId, $gameId);
                $returnData['transaction'] = ["balance" => $balance];                
            } else {
                $returnData['status'] = false;
                $returnData['message'] = 'Insuficient balance!';
            }
        } else {
            $returnData['status'] = false;
            $returnData['message'] = 'Invalid customer!';
        }
        if (isset($returnData['status']) && $returnData['status'] === false) {
            return $returnData;
        } else { 
            if($shopConfiguration['jackpot']){
               if($action == 'spin' && $jackpotBet){
                    $jackpotEngineUrl = JACKPOT_ENGINE_URL;
                    $jackpotReqArr = $this->_getJackpotRequestData($jackpotBet); 
                    //print_r($jackpotReqArr); die;
                    $jackpotReqJson = json_encode($jackpotReqArr);
                    $curlJackpotResponse = $this->_callCurl($jackpotEngineUrl, $jackpotReqJson);
                
                    //echo "<br />bhaskar</br />";var_dum($curlJackpotResponse); die('werrt');
                    
                    
                    if(isset($curlJackpotResponse['status'])){
                        $err['status'] = false;
                        $err['message'] = isset($curlJackpotResponse['msg']) ? $curlJackpotResponse['msg'] : 'Error in jackpot engine response';
                        $response_data = '';
                        $errorMsg = $err['message'];  
                        $jackpotResult = [];                      
                    }else{
                        $response_data = $curlJackpotResponse;
                        $errorMsg = '';
                        $jackpotResult = json_decode($curlJackpotResponse, true);
                    }
                    
                    $jackpotLogArr = [
                                    'customer_id'=>$userId,
                                    'shop_id'=>$shopId,
                                    'game_id'=>$gameId,
                                    'jackpotBet'=>$jackpotBet,
                                    'request_data'=>$jackpotReqJson,
                                    'response_data'=>$response_data,
                                    'winAmount'=>isset($jackpotResult['winAmount']) ? $jackpotResult['winAmount']:0,
                                    'winLevel'=>isset($jackpotResult['winLevel'])?$jackpotResult['winLevel']:0,
                                    'error_msg'=>$errorMsg
                                ];
                    $jackpotLogId = $this->_insertData('jackpot_logs',$jackpotLogArr);
                    if(isset($curlJackpotResponse['status'])) return $err;
                    
                    if($jackpotResult['winAmount'] > 0 && $jackpotResult['winLevel']){
                        if($jackpotResult['winLevel'] == 3){
                            $winArray = $this->_getWinnerJackpot3($shopId, $jackpotResult['winAmount'], $jackpotLogId);
                            $this->_insertData('win_details',$winArray, 3);
                        }else{
                            $winArray = [
                                            'customer_id'=>$userId,
                                            'shop_id'=>$shopId,
                                            'game_id'=>$gameId,
                                            'win_amount'=>$jackpotResult['winAmount'],
                                            'win_type'=>'Jackpot_level_'.$jackpotResult['winLevel'],
                                            'jackpot_log_id'=>$jackpotLogId
                                        ];
                            $this->_insertData('win_details',$winArray);
                        }
                        
                    }
                    $returnData['public']['jackpot'] = $jackpotResult;
                }else{
                    $returnData['public']['jackpot'] = $this->_getJackpotRequestData($jackpotBet, false);
                } 
            }
            //echo "<pre>"; print_r($returnData); die;
            //setup request to send json via POST
            $payload = json_encode($returnData);
            $requestData = json_encode($queryParameter);
            $insertDataArr = [
                                'customer_id'=>$userId,
                                'shop_id'=>$shopId,
                                'game_id'=>$gameId,
                                'bet_amount'=>$betAmount,
                                'request_data_client'=>$requestData,
                                'request_data_server'=>$payload
                             ];
            $requestLogId = $this->_insertData('request_logs',$insertDataArr);
            
            //API URL
            $url =  $gameEngineUrl; //GAME_ENGINE_URL;

            //Call Game engine 
            $curlResponseResult = $this->_callCurl($url, $payload);

            if(isset($curlResponseResult['status']) && isset($curlResponseResult['message'])){
                return $curlResponseResult;
            }
            $curlResponse = json_decode($curlResponseResult, true);
            $gameEngineErrorCodes = [100,101,102,103,104,107];
            //echo "<pre>"; print_r($curlResponse); die;
            if(isset($curlResponse['status']) &&  in_array($curlResponse['status'], $gameEngineErrorCodes)){
                $err['status'] = false;
                $err['message'] = $curlResponse['msg'];
                return $err;
            }else if(isset($curlResponse['private']) && isset($curlResponse['public']) && isset($curlResponse['transaction'])){
                //echo "<pre>"; print_r($curlResponse); die;
                $privateRes = $curlResponse['private'];
                $publicRes = $curlResponse['public'];
                $transactionRes = $curlResponse['transaction'];
                $balanceRem = $transactionRes['balance'];
                $winAmount = $transactionRes['totalWin'];
                $customerInfo['win_amount'] = (float)$customerInfo['win_amount'];
                $winAmount = $winAmount + $customerInfo['win_amount'];
                $sql = 'update customers set balance=:balance, win_amount=:win_amount where id=:id';
                $stmt = $this->con->prepare($sql);
                $stmt->bindParam(":balance", $balanceRem);
                $stmt->bindParam(":win_amount", $winAmount);
                $stmt->bindParam(":id", $userId, PDO::PARAM_INT);
                $stmt->execute();

                $publicResJson = json_encode($publicRes);
                $privateResJson = json_encode($privateRes);
                $transactionResJson = json_encode($transactionRes);
                $insertArr = [
                                'customer_id'=>$userId,
                                'shop_id'=>$shopId,
                                'game_id'=>$gameId,
                                'request_log_id'=>$requestLogId,
                                'response_all'=>$curlResponseResult,
                                'response_public'=>$publicResJson,
                                'response_private'=>$privateResJson,
                                'response_transaction'=>$transactionResJson
                             ];                             
                $responseLogId = $this->_insertData('response_logs',$insertArr);
                
                if($transactionRes['totalWin'] > 0){
                    $winArray = [];
                    $winArray = [
                                    'customer_id'=>$userId,
                                    'shop_id'=>$shopId,
                                    'game_id'=>$gameId,
                                    'win_amount'=>$transactionRes['totalWin'],
                                    'win_type'=>$action,
                                    'response_log_id'=>$responseLogId
                                ];
                    $this->_insertData('win_details',$winArray);
                }                                
                return $publicRes;
            }else{
                $err['status'] = false;
                $err['message'] = 'There is some technical error while connecting to game server.';
                return $err;
            }
            //print_r($curlResponse); die('aaa');
        } 
    }

    public function jackpot_ui_call($request, $response){
        $isDeclared = 0;
        $sql = 'select jl.id, jl.winLevel,jl.response_data, wd.customer_id, wd.shop_id, wd.win_amount, c.name, u.shop_name from jackpot_logs as jl  left join win_details as wd on jl.id = wd.jackpot_log_id 
            left join users as u on jl.shop_id = u.id 
            left join customers as c on wd.customer_id = c.id where jl.isDeclared=:isDeclared and jl.winAmount >0 and jl.winLevel>0';
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(":isDeclared", $isDeclared, PDO::PARAM_INT);
        $stmt->execute();  
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(!empty($results)){
            foreach ($results as $key => $result) {
                $data = json_decode($result['response_data'], true);
                $ids[] = $result['id'];
                $data['player'][] = ['customer_id'=>$result['customer_id'], 'customer_name'=>$result['name'], 'shop_id'=>$result['shop_id'],'shop_name'=>$result['shop_name'], 'win_amount'=>$result['win_amount']];
                $winnerDetail[] = $data;
            }
            $isDeclared = 1;
            $ids = array_unique($ids);
            $sql = 'update jackpot_logs set isDeclared=1 where id in ('.implode(",", $ids).')'; //die;
            $stmt = $this->con->prepare($sql);
            //$stmt->bindParam(":isDeclared", $isDeclared, PDO::PARAM_INT);
            //$stmt->bindParam(":id", $logId, PDO::PARAM_INT);
            $stmt->execute();
            $returnData['status'] = true;
            $returnData['msg'] = 'Jackpot winner found!!';
            $returnData['data'] = $winnerDetail;
        }else{
            //$isDeclared = 0;
        $sql = 'select jl.response_data from jackpot_logs as jl where jl.isDeclared=0 and jl.winAmount=0 and jl.winLevel=0 order by jl.created_at DESC limit 1';
        $stmt = $this->con->prepare($sql);
        //$stmt->bindParam(":isDeclared", $isDeclared, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $returnData['status'] = false;
            $returnData['msg'] = 'No winner';
            $returnData['data'] = json_decode($result['response_data'], true);;
        }
        return $returnData;
    }

    public function login($request, $response) {
        $returnData = array();
        $queryParameter = $request->getParsedBody();
        if (isset($queryParameter['type']) && !empty($queryParameter['type'])) {
            $userData = $this->_checkUser($queryParameter['username'], 'token_id');
        } else {
            if (filter_var($queryParameter['username'], FILTER_VALIDATE_EMAIL)) {
                $userData = $this->_checkUser($queryParameter['username'], 'email');
            } else {
                $userData = $this->_checkUser($queryParameter['username'], 'mobile');
            }
        }

        if (isset($userData) && !empty($userData) && $userData['password'] == $queryParameter['password']) {
            $this->_updateDeviceId($queryParameter['device_id'], $userData['id']);
            $returnData['status'] = true;
            $returnData['message'] = 'Login Successfully.';
            $returnData['data'] = $userData;
        } else {
            $returnData['status'] = false;
            $returnData['message'] = 'Invalid login credentials!';
        }
        return $returnData;
    }

    public function isUserEmailExists($email) {
        $stmt = $this->con->prepare("SELECT id from user WHERE email =:email");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return empty($result) ? false : true;
    }

    protected function sendEmail($to_email, $to_name, $subject, $body, $from = "bjain@tecziq.com") {
        require_once ROOT_DIR . 'library/phpmailer/PHPMailerAutoload.php';

        //Create a new PHPMailer instance
        $mail = new PHPMailer;
        //Tell PHPMailer to use SMTP
        $mail->isSMTP();
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages

        $mail->SMTPDebug = 0;
        //Ask for HTML-friendly debug output
        $mail->Debugoutput = 'html';
        //Set the hostname of the mail server
        //$mail->Host = "mail.premium.exchange";
        $mail->Host = "mail.tecziqdemo.com";
        //Set the SMTP port number - likely to be 25, 465 or 587
        $mail->Port = 25;
        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;
        //$mail->SMTPAuth = false;
        //$mail->SMTPSecure = false;
        //Username to use for SMTP authentication
        //$mail->Username = "reservations@rent24.com.au";
        //Password to use for SMTP authentication
        //$mail->Password = "LOVE123!!";
        //Set who the message is to be sent from
        $mail->Username = "tecziqdemo@tecziqdemo.com";
        $mail->Password = "password@123";
        $mail->setFrom($from, 'Tecziq');
        //Set an alternative reply-to address
        $mail->addReplyTo($from, 'Tecziq');
        //Set who the message is to be sent to
        $mail->addAddress($to_email, $to_name);
        //Set the subject line
        $mail->Subject = $subject;
        $mail->isHTML(true);

        $mail->Body = $body;
        //send the message, check for errors
        if (!$mail->send()) {
            return false;
        } else {
            return true;
        }
    }

}
