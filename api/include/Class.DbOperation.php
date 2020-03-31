<?php

require_once 'constants.php';

class DbOperation {

    function __construct() {
        try {
            $this->con = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
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
        $balance = $customerInfo['balance'];
        return $balance > $betAmount ? true : false;
    }

    protected function _getShopConfiguration($shopId) {
        $sql = 'SELECT id,email,created_at,updated_at,created_by,first_name,last_name,active,shop_name,shop_code,'
                . 'available_credit,nudgeFeature,preRevealWithSkillStop,jackpot,sweepstakes '
                . 'FROM `users`, `role_user` where `users`.id=`role_user`.user_id and `role_user`.role_id=3 and `users`.id=:id';
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(":id", $shopId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    protected function _getGameRtpVarient($gameId, $shopId) {
        $sql = 'SELECT d.`distributor_rtp_variant` FROM `users` as d inner join  `users` as s on d.id=s.created_by where s.id=:shop_id';
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(":shop_id", $shopId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result && $result['distributor_rtp_variant'] > 0) {
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

    protected function _getGameMathUrl($gameId) {
        $sql = 'select math_url from games where id=:id';
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(":id", $gameId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['math_url'];
    }

    protected function _getResponsePrivateData($userId, $shopId, $gameId) {
        $sql3 = 'SELECT response_private FROM game_server_logs WHERE customer_id=:customer_id AND '
                . 'shop_id=:shop_id AND '
                . 'game_id=:game_id and (response_private!="" and response_private IS NOT NULL ) ORDER BY created_at DESC limit 1';
        $stmt = $this->con->prepare($sql3);
        $stmt->bindParam(":customer_id", $userId, PDO::PARAM_INT);
        $stmt->bindParam(":shop_id", $shopId, PDO::PARAM_INT);
        $stmt->bindParam(":game_id", $gameId, PDO::PARAM_INT);
        $stmt->execute();
        $resultResponsePrivate = $stmt->fetch(PDO::FETCH_ASSOC);
        $responsePrivate = ($resultResponsePrivate && isset($resultResponsePrivate['response_private']) && !empty($resultResponsePrivate['response_private'])) ? json_decode($resultResponsePrivate['response_private']) : (object) [];
        return $responsePrivate;
    }

    protected function _getJackpotRequestData($jackpotBet, $shopId, $flag = true) {
        $sql3 = 'SELECT response_data FROM jackpot_logs where  shop_id=:shop_id and response_data !="" ORDER BY created_at DESC limit 1';
        $stmt = $this->con->prepare($sql3);
        $stmt->bindParam(":shop_id", $shopId, PDO::PARAM_INT);
        $stmt->execute();
        $resultResponsePrivate = $stmt->fetch(PDO::FETCH_ASSOC);
        $defaultJackpot = NULL;
        $responsePrivate = ($resultResponsePrivate && isset($resultResponsePrivate['response_data']) && !empty($resultResponsePrivate['response_data'])) ? json_decode($resultResponsePrivate['response_data'], true) : $defaultJackpot;

        if ($flag) {
            unset($responsePrivate['winAmount']);
            unset($responsePrivate['winLevel']);
        }
        $responsePrivate['jackpotBet'] = $jackpotBet;

        return $responsePrivate;
    }

    protected function _callCurl($url, $payload) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curlResult = curl_exec($ch);
        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            $err['status'] = false;
            $err['msg'] = $error_msg;
            curl_close($ch);
            return $err;
        }
        
        $r_data = json_decode($curlResult, true);
        if(!(($r_data != $curlResult) && is_array($r_data))){
            $err['status'] = false;
            $err['msg'] = 'Invalid API response.';
        }
        return $curlResult;
    }

    protected function _insertData($tableName, $winArray, $level = 0) {
        if ($level) {
            foreach ($winArray as $key => $winSubArr) {
                $updateArrayKey = [];
                $updateFields = '';
                foreach ($winSubArr as $keys => $values) {
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
        } else {
            foreach ($winArray as $keys => $values) {
                $updateArrayKey[$keys] = $keys . '=:' . $keys;
            }
            $updateFields = implode(', ', $updateArrayKey);
            $sql = "INSERT INTO {$tableName} set {$updateFields}";
            $stmt = $this->con->prepare($sql);
            foreach ($winArray as $k => $v) {
                $stmt->bindValue(':' . $k, $v);
            }
            $result = $stmt->execute();
        }
        $lastInsetId = $this->con->lastInsertId();
        return $lastInsetId;
    }

    protected function _getWinnerJackpot1($winAmount, $jackpotLogId, $jackpotTriggerd) {
        $sql = 'select id, customer_id, shop_id, game_id from jackpot_logs where created_at > date_sub("' . $jackpotTriggerd . '", interval 30 second) and created_at <= "' . $jackpotTriggerd . '" order by RAND() limit 1';
        $stmt = $this->con->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        $data = [];
        if (!empty($results)) {
            $data = [
                'customer_id' => $results['customer_id'],
                'shop_id' => $results['shop_id'],
                'game_id' => $results['game_id'],
                'win_amount' => $winAmount,
                'win_type' => 'Jackpot_level_1',
                'jackpot_log_id' => $jackpotLogId
            ];
        }
        return $data;
    }

    protected function _getWinnerJackpot2_4($shopId, $winAmount, $jackpotLogId, $jackpotLevel, $jackpotTriggerd) {
        $sql = 'select id, customer_id, game_id from jackpot_logs where created_at > date_sub("' . $jackpotTriggerd . '", interval 30 second) and created_at <= "' . $jackpotTriggerd . '" and shop_id=:shop_id order by RAND() limit 1';
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(":shop_id", $shopId, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        $data = [];
        if (!empty($results)) {
            $data = [
                'customer_id' => $results['customer_id'],
                'shop_id' => $shopId,
                'game_id' => $results['game_id'],
                'win_amount' => $winAmount,
                'win_type' => $jackpotLevel,
                'jackpot_log_id' => $jackpotLogId
            ];
        }
        return $data;
    }

    protected function _getWinnerJackpot3($shopId, $winAmount, $jackpotLogId, $jackpotTriggerd) {
        $sql = 'select id, customer_id, game_id from jackpot_logs where created_at > date_sub("' . $jackpotTriggerd . '", interval 30 second) and created_at <= "' . $jackpotTriggerd . '" and shop_id=:shop_id order by id desc';
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(":shop_id", $shopId, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data = [];
        if (!empty($results)) {
            $resultArr = [];
            foreach ($results as $v) {
                $resultArr[$v['customer_id']] = $v;
            }
            $winnerUsers = count($resultArr);
            $winAmountEachUser = round(($winAmount / $winnerUsers), 2);
            foreach ($resultArr as $result) {
                $data[] = [
                    'customer_id' => $result['customer_id'],
                    'shop_id' => $shopId,
                    'game_id' => $result['game_id'],
                    'win_amount' => $winAmountEachUser,
                    'win_type' => 'Jackpot_level_3',
                    'jackpot_log_id' => $jackpotLogId
                ];
            }
        }
        return $data;
    }

    protected function _jackpotLogDataById($id) {
        $sql = 'select id, customer_id, game_id, created_at from jackpot_logs where id=:id';
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        return $results;
    }
    protected function _deleteJackpotData($jackpotLogId){
        $sql = 'delete from jackpot_logs where id=:id';
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(":id", $jackpotLogId, PDO::PARAM_INT);
        $stmt->execute();
        //delete data from win_details (jackpot win data delete)
        $sql1 = 'delete from win_details where jackpot_log_id=:jackpot_log_id';
        $stmt1 = $this->con->prepare($sql1);
        $stmt1->bindParam(":jackpot_log_id", $jackpotLogId, PDO::PARAM_INT);
        $stmt1->execute();
    }

    public function cms_engine_call($request, $response) {
        $returnData = array();
        $queryParameter = $request->getParsedBody();
        $userId = isset($queryParameter['user_id']) ? $queryParameter['user_id'] : '';
        $gameId = isset($queryParameter['game_id']) ? $queryParameter['game_id'] : '';
        $betAmount = isset($queryParameter['stake']) ? $queryParameter['stake'] : 0;
        $jackpotBet = isset($queryParameter['jackpotBet']) ? $queryParameter['jackpotBet'] : 0;

        if (empty($userId) || empty($gameId)) {
            $returnData['status'] = false;
            $returnData['message'] = 'User Id or Game Id is missing.';
            return $returnData;
        }
        $gameEngineUrl = $this->_getGameMathUrl($gameId);
        if (empty($gameEngineUrl)) {
            $returnData['status'] = false;
            $returnData['message'] = 'Game math url is not set by admin.';
            return $returnData;
        }
        $action = $queryParameter['action'];
        $customerInfo = $this->_getCustomerInfoById($userId);

        $requestLogId = 0;
        if ($customerInfo !== false) {
            if ($this->_haveBalance($customerInfo, $betAmount)) {
                $shopId = $customerInfo['shop_id'];
                $jackpotWinAmountThisPlayer = 0;
                $shopConfiguration = $this->_getShopConfiguration($shopId);
                if (!$shopId || empty($shopConfiguration)) {
                    $returnData['status'] = false;
                    $returnData['msg'] = 'Invalid shop id.';
                    return $returnData;
                }
                if ($action == 'spin' && $shopConfiguration['jackpot'] && !$jackpotBet) {
                    $returnData['status'] = false;
                    $returnData['message'] = 'Jackpot is active for this shop but jackpot bet amount is missing.';
                    return $returnData;
                }
                $gameRtp = $this->_getGameRtpVarient($gameId, $shopId);
                $balance = $customerInfo['balance'];
                foreach ($queryParameter as $key => $value) {
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
        } else { //309788 {"level1Amount":201.2610,"level2Amount":101.2610,"level3Amount":77.1095,"level4Amount":26.3930,"jackpotBet":0.05,"winAmount":0,"winLevel":0}
            if ($shopConfiguration['jackpot']) {
                if ($action == 'spin' && $jackpotBet) {
                    $jackpotEngineUrl = JACKPOT_ENGINE_URL;
                    $jackpotReqArr = $this->_getJackpotRequestData($jackpotBet, $shopId);
                    $jackpotReqJson = json_encode($jackpotReqArr);
                    $curlJackpotResponse = $this->_callCurl($jackpotEngineUrl, $jackpotReqJson);
                    if (isset($curlJackpotResponse['status'])) {
                        $err['status'] = false;
                        $err['message'] = isset($curlJackpotResponse['msg']) ? $curlJackpotResponse['msg'] : 'Error in jackpot engine response';
                        $response_data = '';
                        $errorMsg = $err['message'];
                        $jackpotResult = [];
                    } else {
                        $response_data = $curlJackpotResponse;
                        $errorMsg = '';
                        $jackpotResult = json_decode($curlJackpotResponse, true);
                    }

                    $jackpotLogArr = [
                        'customer_id' => $userId,
                        'shop_id' => $shopId,
                        'game_id' => $gameId,
                        'jackpotBet' => $jackpotBet,
                        'request_data' => $jackpotReqJson,
                        'response_data' => $response_data,
                        'winAmount' => isset($jackpotResult['winAmount']) ? $jackpotResult['winAmount'] : 0,
                        'winLevel' => isset($jackpotResult['winLevel']) ? $jackpotResult['winLevel'] : 0,
                        'error_msg' => $errorMsg
                    ];
                    $jackpotLogId = $this->_insertData('jackpot_logs', $jackpotLogArr); //17394;//16989;
                    $jackpotLogData = $this->_jackpotLogDataById($jackpotLogId);

                    if (isset($curlJackpotResponse['status']))
                        return $err;

                    if ($jackpotResult['winAmount'] > 0 && $jackpotResult['winLevel']) {
                        if ($jackpotResult['winLevel'] == 3) {
                            $winArray = $this->_getWinnerJackpot3($shopId, $jackpotResult['winAmount'], $jackpotLogId, $jackpotLogData['created_at']);
                            $this->_insertData('win_details', $winArray, 3);
                        } else {
                            if ($jackpotResult['winLevel'] == 2 || $jackpotResult['winLevel'] == 4) {
                                $jackpotWinType = 'Jackpot_level_' . $jackpotResult['winLevel'];
                                $winArray = $this->_getWinnerJackpot2_4($shopId, $jackpotResult['winAmount'], $jackpotLogId, $jackpotWinType, $jackpotLogData['created_at']);
                                $shopSweepstakes = $shopConfiguration['sweepstakes'];
                            } else if ($jackpotResult['winLevel'] == 1) {
                                $winArray = $this->_getWinnerJackpot1($jackpotResult['winAmount'], $jackpotLogId, $jackpotLogData['created_at']);
                                $shopSweepstakes = $this->_getShopConfiguration($winArray['shop_id'])['sweepstakes'];
                            }
                            $this->_insertData('win_details', $winArray);
                        }
                    }
                    $returnData['public']['jackpot'] = $jackpotResult;
                } else {
                    $returnData['public']['jackpot'] = $this->_getJackpotRequestData($jackpotBet, $shopId, false);
                }
            }
            $payload = json_encode($returnData);
            $requestData = json_encode($queryParameter);
            $insertDataArr = [
                'customer_id' => $userId,
                'shop_id' => $shopId,
                'game_id' => $gameId,
                'bet_amount' => $betAmount,
                'jackpot_bet' => $jackpotBet,
                'request_data_client' => $requestData,
                'request_data_server' => $payload
            ];
            $url = $gameEngineUrl;
            $curlResponseResult = $this->_callCurl($url, $payload);

            if (isset($curlResponseResult['status']) && isset($curlResponseResult['message'])) {
                $insertDataArr['response_all'] = json_encode($curlResponseResult);
                $insertDataArr['error_msg'] = $curlResponseResult['message'];
                $this->_insertData('game_server_logs', $insertDataArr);
                //delete jackpot log data and jackpot win
                if(isset($jackpotLogId) && $jackpotLogId){
                     $this->_deleteJackpotData($jackpotLogId);                   
                }
                return $curlResponseResult;
            }
            $curlResponse = json_decode($curlResponseResult, true);
            $gameEngineErrorCodes = [100, 101, 102, 103, 104, 107];
            if (isset($curlResponse['status']) && in_array($curlResponse['status'], $gameEngineErrorCodes)) {
                $err['status'] = false;
                $err['message'] = $curlResponse['msg'];
                $insertDataArr['response_all'] = $curlResponseResult;
                $insertDataArr['error_msg'] = $curlResponse['msg'];
                $this->_insertData('game_server_logs', $insertDataArr);
                //delete jackpot log data and jackpot win
                if(isset($jackpotLogId) && $jackpotLogId){
                     $this->_deleteJackpotData($jackpotLogId);                   
                }
                return $err;
            } else if (isset($curlResponse['private']) && isset($curlResponse['public']) && isset($curlResponse['transaction'])) {
                $privateRes = $curlResponse['private'];
                $publicRes = $curlResponse['public'];
                $transactionRes = $curlResponse['transaction'];
                $customerInfo = $this->_getCustomerInfoById($userId);
                $customerCurrentWinAmount = (float) $customerInfo['win_amount'];
                $winAmount = $transactionRes['totalWin'] + $customerCurrentWinAmount;
                if ($shopConfiguration['sweepstakes']) {
                    $balanceRem = $transactionRes['balance'];
                    $publicRes['cmsTotalWinAmount'] = round($winAmount, 2);
                } else {
                    $balanceRem = (((float) $customerInfo['balance'] + $transactionRes['totalWin']) - $transactionRes['totalBet']);
                    $publicRes['balance'] = round($balanceRem, 2);
                }
                $sql = 'update customers set balance=:balance, win_amount=:win_amount where id=:id';
                $stmt = $this->con->prepare($sql);
                $stmt->bindParam(":balance", $balanceRem);
                $stmt->bindParam(":win_amount", $winAmount);
                $stmt->bindParam(":id", $userId, PDO::PARAM_INT);
                $stmt->execute();

                $publicResJson = json_encode($publicRes);
                $privateResJson = json_encode($privateRes);
                $transactionResJson = json_encode($transactionRes);

                $insertDataArr = [
                    'customer_id' => $userId,
                    'shop_id' => $shopId,
                    'game_id' => $gameId,
                    'jackpot_bet' => $jackpotBet,
                    'request_data_client' => $requestData,
                    'request_data_server' => $payload,
                    'bet_amount' => $transactionRes['totalBet'],
                    'response_all' => $curlResponseResult,
                    'response_private' => $privateResJson
                ];
                $responseLogId = $this->_insertData('game_server_logs', $insertDataArr);
                if ($transactionRes['totalWin'] > 0) {
                    $winArray = [
                        'customer_id' => $userId,
                        'shop_id' => $shopId,
                        'game_id' => $gameId,
                        'win_amount' => $transactionRes['totalWin'],
                        'win_type' => $action,
                        'response_log_id' => $responseLogId
                    ];
                    $this->_insertData('win_details', $winArray);
                }
                return $publicRes;
            } else {
                $err['status'] = false;
                $err['message'] = 'There is some technical error while connecting to game server.';
                return $err;
            }
        }
    }

    public function jackpot_player_call($request, $response) {
        $queryParameter = $request->getParsedBody();
        $userId = isset($queryParameter['uid']) ? $queryParameter['uid'] : 0;
        $customerInfo = $this->_getCustomerInfoById($userId);
        if ($customerInfo !== false) {
            $shopId = $customerInfo['shop_id'];
            $shopConfiguration = $this->_getShopConfiguration($shopId);
            if (!$shopId || empty($shopConfiguration) || (isset($shopConfiguration['jackpot']) && !$shopConfiguration['jackpot'])) {
                $returnData['status'] = false;
                $returnData['msg'] = 'Invalid shop id.';
                return $returnData;
            }
            $defaultJackpot = NULL;
            $sql = 'select wd.id, wd.customer_id, wd.shop_id, wd.game_id, wd.win_amount, wd.win_type, wd.jackpot_log_id, wd.isDeclared, 
                    jl.response_data, jl.updated_at, c.name, c.code, u.shop_name 
                    from win_details as wd left join jackpot_logs as jl 
                    on  jl.id=wd.jackpot_log_id 
                    left join users as u on wd.shop_id = u.id 
                    left join customers as c on wd.customer_id = c.id
                    where wd.jackpot_log_id is not null and wd.isDeclared=0 
                    and NOW() > date_add(jl.created_at, interval 20 second) 
                    and wd.customer_id=:customer_id order by wd.created_at DESC'; //and jl.isDeclared=1 
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(":customer_id", $userId, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($results)) {
                foreach ($results as $result) {
                    $data = json_decode($result['response_data'], true);
                    $ids[] = $result['id'];
                    $data['player'][] = ['customer_id' => $result['customer_id'], 'customer_name' => $result['name'], 'customer_code' => $result['code'], 'shop_id' => $result['shop_id'], 'shop_name' => $result['shop_name'], 'win_amount' => $result['win_amount'], 'win_level' => str_replace('Jackpot_level_', '', $result['win_type'])];
                    $this->_updatePlayerBalance($result);
                    $winnerDetail[] = $data;
                }
                $ids = array_unique($ids);
                $sql = 'update win_details set isDeclared=1 where id in (' . implode(",", $ids) . ')';
                $stmt = $this->con->prepare($sql);
                $stmt->execute();
                $returnData['status'] = true;
                $returnData['msg'] = 'Jackpot win found!!';
                $returnData['data'] = $winnerDetail;
            } else {
                $sql = 'select jl.response_data from jackpot_logs as jl where jl.shop_id=:shop_id and jl.isDeclared=0 and jl.winAmount=0 and jl.winLevel=0 order by jl.created_at DESC limit 1';
                $stmt = $this->con->prepare($sql);
                $stmt->bindParam(":shop_id", $shopId, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $jackpotValues = empty($result) ? $defaultJackpot : json_decode($result['response_data'], true);

                $returnData['status'] = false;
                $returnData['msg'] = 'No win';
                $returnData['data'] = $jackpotValues;
            }
        } else {
            $returnData['status'] = false;
            $returnData['msg'] = 'Invalid player id.';
            return $returnData;
        }
        return $returnData;
    }
    
    protected function _updatePlayerBalance($winRec) {
        $custWinInfo = $this->_getCustomerInfoById($winRec['customer_id']);
        $updateWinVal = $winRec['win_amount'] + $custWinInfo['win_amount'];
        $shopConfiguration = $this->_getShopConfiguration($winRec['shop_id']);
        if ($shopConfiguration['sweepstakes']) {
            $sql = 'update customers set win_amount=:win_amount where id=:id';
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(":win_amount", $updateWinVal);
            $stmt->bindParam(":id", $winRec['customer_id'], PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $updateBalanceVal = round(($winRec['win_amount'] + $custWinInfo['balance']), 2);
            $sql = 'update customers set balance=:balance, win_amount=:win_amount where id=:id';
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(":balance", $updateBalanceVal);
            $stmt->bindParam(":win_amount", $updateWinVal);
            $stmt->bindParam(":id", $winRec['customer_id'], PDO::PARAM_INT);
            $stmt->execute();
        }
    }

    public function convert_win_credit($request, $response) {
        $queryParameter = $request->getParsedBody();
        $id = $queryParameter['uid'];
        $reverse_amount = (float) $queryParameter['amount'];
        $custInfo = $this->_getCustomerInfoById($id);
        if (!empty($custInfo) && $custInfo['active']) {
            $shopId = $custInfo['shop_id'];
            $customerId = $custInfo['id'];
            $shopConfiguration = $this->_getShopConfiguration($shopId);
            if (!empty($shopConfiguration) && $shopConfiguration['active'] && $shopConfiguration['sweepstakes']) {
                $currentWinAmount = $custInfo['win_amount'];
                $currentBalance = $custInfo['balance'];
                if ($reverse_amount > $currentWinAmount) {
                    $returnData['status'] = false;
                    $returnData['msg'] = 'Entered amount must be less than winning amount.';
                } else if ($reverse_amount > 0 && $reverse_amount <= $currentWinAmount) {
                    $updatedBalance = round($currentBalance + $reverse_amount, 2);
                    $updatedWinAmount = round($currentWinAmount - $reverse_amount, 2);
                    $sql = 'update customers set balance=:balance, win_amount=:win_amount where id=:id';
                    $stmt = $this->con->prepare($sql);
                    $stmt->bindParam(":balance", $updatedBalance);
                    $stmt->bindParam(":win_amount", $updatedWinAmount);
                    $stmt->bindParam(":id", $customerId, PDO::PARAM_INT);
                    $stmt->execute();
                    $returnData['status'] = true;
                    $returnData['msg'] = 'Success';
                    $returnData['data'] = ['updated_balance' => $updatedBalance, 'updated_win_amount' => $updatedWinAmount]; //$this->_getCustomerInfoById($id);
                } else {
                    $returnData['status'] = false;
                    $returnData['msg'] = 'Invalid amount, Please try again.';
                }
            } else {
                $returnData['status'] = false;
                $returnData['msg'] = 'Invalid shop.';
            }
        } else {
            $returnData['status'] = false;
            $returnData['msg'] = 'Invalid customer.';
        }
        return $returnData;
    }

    public function jackpot_ui_call($request, $response) {
        $queryParameter = $request->getParsedBody();
        $shopId = isset($queryParameter['sid']) ? $queryParameter['sid'] : 0;
        $shopConfiguration = $this->_getShopConfiguration($shopId);
        $defaultJackpot = NULL;
        if (!$shopId || empty($shopConfiguration) || (isset($shopConfiguration['jackpot']) && !$shopConfiguration['jackpot'])) {
            $returnData['status'] = false;
            $returnData['msg'] = 'Invalid shop id.';
            return $returnData;
        }
        $isDeclared = 0;
        $sql = 'select jl.id, jl.winLevel,jl.response_data, wd.customer_id, wd.shop_id, wd.win_amount, c.name, u.shop_name 
            from jackpot_logs as jl  
            left join win_details as wd on jl.id = wd.jackpot_log_id 
            left join users as u on jl.shop_id = u.id 
            left join customers as c on wd.customer_id = c.id 
            where jl.shop_id=:shop_id and jl.isDeclared=:isDeclared and jl.winAmount >0 and jl.winLevel>0';
        $stmt = $this->con->prepare($sql);
        $stmt->bindParam(":isDeclared", $isDeclared, PDO::PARAM_INT);
        $stmt->bindParam(":shop_id", $shopId, PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($results)) {
            foreach ($results as $key => $result) {
                $data = json_decode($result['response_data'], true);
                $ids[] = $result['id'];
                $data['player'][] = ['customer_id' => $result['customer_id'], 'customer_name' => $result['name'], 'shop_id' => $result['shop_id'], 'shop_name' => $result['shop_name'], 'win_amount' => $result['win_amount']];
                $winnerDetail[] = $data;
            }
            $isDeclared = 1;
            $ids = array_unique($ids);
            $sql = 'update jackpot_logs set isDeclared=1 where id in (' . implode(",", $ids) . ')'; //die;
            $stmt = $this->con->prepare($sql);
            $stmt->execute();
            $returnData['status'] = true;
            $returnData['msg'] = 'Jackpot winner found!!';
            $returnData['data'] = $winnerDetail;
        } else {
            $sql = 'select jl.response_data from jackpot_logs as jl where jl.shop_id=:shop_id and jl.isDeclared=0 and jl.winAmount=0 and jl.winLevel=0 order by jl.created_at DESC limit 1';
            $stmt = $this->con->prepare($sql);
            $stmt->bindParam(":shop_id", $shopId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $jackpotValues = empty($result) ? $defaultJackpot : json_decode($result['response_data'], true);
            $returnData['status'] = false;
            $returnData['msg'] = 'No winner';
            $returnData['data'] = $jackpotValues;
        }
        return $returnData;
    }

}
