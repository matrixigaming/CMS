<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; //Laravel Filesystem
use App\Repositories\PaymentLog\PaymentLogRepository as PaymentLog;
use App\Repositories\User\UserRepository as User;
use App\Repositories\Customer\CustomerRepository as Customer;
use Carbon\Carbon;
use DB;
use Auth;

class ReportController extends Controller {

    private $paymentLog;
    private $user;
    private $customer;

    public function __construct(PaymentLog $paymentLog, User $user, Customer $customer) {
        $this->middleware('auth');
        $this->paymentLog = $paymentLog;
        $this->user = $user;
        $this->customer = $customer;
        parent::__construct();
    }

    public function jackpot_rtp(Request $request) {
        //getTotalJackpotBetWinAmountGameWise
        $user = Auth::user();
        $hasShop = $user->hasRole(['Shop']);
        $hasDistributor = $user->hasRole(['Distributor']);
        $hasAdmin = $user->hasRole(['Admin']);
        if ($request->isMethod('post')) {
            $post_data = $request->all();
            if (!empty($post_data['start'])) {
                $carbon1 = new Carbon($post_data['start'], 'EST');
                $carbon1->setTimezone('UTC');
                $reportFrom = $carbon1->toDateTimeString();
            }
            if (!empty($post_data['end'])) {
                $carbon2 = new Carbon($post_data['end'], 'EST');
                $carbon2->setTimezone('UTC');
                $reportTo = $carbon2->toDateTimeString();
            }
            $start = !empty($post_data['start']) ? $reportFrom : Carbon::now()->subMonth();
            $end = !empty($post_data['end']) ? $reportTo : Carbon::now();
            if ($hasDistributor || $hasAdmin) {
                $shopId = $post_data['shop_id'];
                $distributorId = isset($post_data['distributor_id']) && !empty($post_data['distributor_id']) ? $post_data['distributor_id'] : $user->id;
                $shopLists = $this->user->getShopList(true, $distributorId);
                $distributorList = $this->user->getDistributorList($hasDistributor, $user->id);
                $results['shopLists'] = $shopLists;
                $results['distributorList'] = $distributorList;
            }
            if ($hasShop) {
                $shopId = $user->id;
            }

            $betWinData = $this->paymentLog->getTotalJackpotBetWinAmountGameWise($shopId, $start, $end);
            $results['betWinDataArr'] = $betWinData;
            return view('report.jackpot_rtp', ['data' => $results, 'isAdmin' => $hasAdmin, 'isDistributor' => $hasDistributor, 'isShop' => $hasShop]);
        } else {
            $end = Carbon::now();
            $start = Carbon::now()->subMonth();
            if ($hasAdmin) {
                $distributorList = $this->user->getDistributorList($hasDistributor, $user->id);
                $results = ['betWinDataArr' => [], 'shopLists' => [], 'distributorList' => $distributorList];
            }
            if ($hasDistributor) {
                $distributorList = $this->user->getDistributorList($hasDistributor, $user->id);
                $end = Carbon::now();
                $start = Carbon::now()->subMonth();
                $shopLists = $this->user->getShopList($hasDistributor, $user->id);
                $results = ['betWinDataArr' => [], 'shopLists' => $shopLists, 'distributorList' => $distributorList];
            }
            if ($hasShop) {
                $shopId = $user->id;
                $betWinData = $this->paymentLog->getTotalJackpotBetWinAmountGameWise($shopId, $start, $end);
                $results = ['betWinDataArr' => $betWinData, 'shopLists' => [], 'distributorList' => []];
            }
            return view('report.jackpot_rtp', ['data' => $results, 'isAdmin' => $hasAdmin, 'isDistributor' => $hasDistributor, 'isShop' => $hasShop]);
        }
    }

    public function game_rtp(Request $request) {
        $user = Auth::user();
        $hasShop = $user->hasRole(['Shop']);
        $hasDistributor = $user->hasRole(['Distributor']);
        $hasAdmin = $user->hasRole(['Admin']);
        if ($request->isMethod('post')) {
            $post_data = $request->all();
            if (!empty($post_data['start'])) {
                $carbon1 = new Carbon($post_data['start'], 'EST');
                $carbon1->setTimezone('UTC');
                $reportFrom = $carbon1->toDateTimeString();
            }
            if (!empty($post_data['end'])) {
                $carbon2 = new Carbon($post_data['end'], 'EST');
                $carbon2->setTimezone('UTC');
                $reportTo = $carbon2->toDateTimeString();
            }
            $start = !empty($post_data['start']) ? $reportFrom : Carbon::now()->subMonth();
            $end = !empty($post_data['end']) ? $reportTo : Carbon::now();
            if ($hasDistributor || $hasAdmin) {
                $shopId = $post_data['shop_id'];
                $distributorId = isset($post_data['distributor_id']) && !empty($post_data['distributor_id']) ? $post_data['distributor_id'] : $user->id;
                $shopLists = $this->user->getShopList(true, $distributorId);
                $distributorList = $this->user->getDistributorList($hasDistributor, $user->id);
                $results['shopLists'] = $shopLists;
                $results['distributorList'] = $distributorList;
            }
            if ($hasShop) {
                $shopId = $user->id;
            }

            $gameBetResults = $this->paymentLog->getTotalBetAmountGameWise($shopId, $start, $end);
            $gameWinResults = $this->paymentLog->getTotalWinAmountGameWise($shopId, $start, $end);
            $betWinData = array_merge($gameBetResults, $gameWinResults);
            //echo "<pre>"; print_r($betWinData); die;
            $betWinDataArr = [];
            if (!empty($betWinData)) {
                foreach ($betWinData as $data) {
                    $betWinDataArr[$data->game_id]['name'] = $data->game_name;
                    $betWinDataArr[$data->game_id]['icon'] = $data->game_icon;
                    if (isset($data->game_bet_amount) && !isset($betWinDataArr[$data->game_id]['game_bet_amount'])) {
                        $betWinDataArr[$data->game_id]['game_bet_amount'] = $data->game_bet_amount;
                    }
                    if (isset($data->game_win_amount) && !isset($betWinDataArr[$data->game_id]['game_win_amount'])) {
                        $betWinDataArr[$data->game_id]['game_win_amount'] = $data->game_win_amount;
                    }
                }
            }
            $results['betWinDataArr'] = $betWinDataArr;
            return view('report.game_rtp', ['data' => $results, 'isAdmin' => $hasAdmin, 'isDistributor' => $hasDistributor, 'isShop' => $hasShop]);
        } else {
            $end = Carbon::now();
            $start = Carbon::now()->subMonth();
            if ($hasAdmin) {
                $distributorList = $this->user->getDistributorList($hasDistributor, $user->id);
                $results = ['betWinDataArr' => [], 'shopLists' => [], 'distributorList' => $distributorList];
            }
            if ($hasDistributor) {
                $distributorList = $this->user->getDistributorList($hasDistributor, $user->id);
                $end = Carbon::now();
                $start = Carbon::now()->subMonth();
                $shopLists = $this->user->getShopList($hasDistributor, $user->id);
                $results = ['betWinDataArr' => [], 'shopLists' => $shopLists, 'distributorList' => $distributorList];
            }
            if ($hasShop) {
                $shopId = $user->id;
                $gameBetResults = $this->paymentLog->getTotalBetAmountGameWise($shopId, $start, $end);
                $gameWinResults = $this->paymentLog->getTotalWinAmountGameWise($shopId, $start, $end);
                $betWinData = array_merge($gameBetResults, $gameWinResults);
                $betWinDataArr = [];
                if (!empty($betWinData)) {
                    foreach ($betWinData as $data) {
                        $betWinDataArr[$data->game_id]['name'] = $data->game_name;
                        $betWinDataArr[$data->game_id]['icon'] = $data->game_icon;
                        if (isset($data->game_bet_amount) && !isset($betWinDataArr[$data->game_id]['game_bet_amount'])) {
                            $betWinDataArr[$data->game_id]['game_bet_amount'] = $data->game_bet_amount;
                        }
                        if (isset($data->game_win_amount) && !isset($betWinDataArr[$data->game_id]['game_win_amount'])) {
                            $betWinDataArr[$data->game_id]['game_win_amount'] = $data->game_win_amount;
                        }
                    }
                }
                $results = ['betWinDataArr' => $betWinDataArr, 'shopLists' => [], 'distributorList' => []];
            }
            return view('report.game_rtp', ['data' => $results, 'isAdmin' => $hasAdmin, 'isDistributor' => $hasDistributor, 'isShop' => $hasShop]);
        }
    }

    public function physical_money(Request $request) {
        $user = Auth::user();
        $hasShop = $user->hasRole(['Shop']);
        $hasDistributor = $user->hasRole(['Distributor']);
        $hasAdmin = $user->hasRole(['Admin']);
        if ($request->isMethod('post')) {
            $post_data = $request->all();
            if (!empty($post_data['start'])) {
                $carbon1 = new Carbon($post_data['start'], 'EST');
                $carbon1->setTimezone('UTC');
                $reportFrom = $carbon1->toDateTimeString();
            }
            if (!empty($post_data['end'])) {
                $carbon2 = new Carbon($post_data['end'], 'EST');
                $carbon2->setTimezone('UTC');
                $reportTo = $carbon2->toDateTimeString();
            }
            $start = !empty($post_data['start']) ? $reportFrom : Carbon::now()->subMonth();
            $end = !empty($post_data['end']) ? $reportTo : Carbon::now();
            if ($hasDistributor || $hasAdmin) {
                $shopId = $post_data['shop_id'];
                $distributorId = isset($post_data['distributor_id']) && !empty($post_data['distributor_id']) ? $post_data['distributor_id'] : $user->id;
                $shopLists = $this->user->getShopList(true, $distributorId);
                $distributorList = $this->user->getDistributorList($hasDistributor, $user->id);
                $results['shopLists'] = $shopLists;
                $results['distributorList'] = $distributorList;
            }
            if ($hasShop) {
                $shopId = $user->id;
            }

            $playerBetResults = $this->paymentLog->getPhysicalMoneyBet($shopId, $start, $end);
            $playerRedeemResults = $this->paymentLog->getPhysicalMoneyRedeemed($shopId, $start, $end);
            $betRedeemData = array_merge($playerBetResults, $playerRedeemResults);
            $betRedeemDataArr = [];
            if (!empty($betRedeemData)) {
                foreach ($betRedeemData as $data) {
                    $betRedeemDataArr[$data->customer_id]['name'] = $data->customer_name;
                    if (isset($data->bet_amount) && !isset($betRedeemDataArr[$data->customer_id]['bet_amount'])) {
                        $betRedeemDataArr[$data->customer_id]['bet_amount'] = $data->bet_amount;
                    }
                    if (isset($data->redeem_amount) && !isset($betRedeemDataArr[$data->customer_id]['redeem_amount'])) {
                        $betRedeemDataArr[$data->customer_id]['redeem_amount'] = $data->redeem_amount;
                    }
                }
            }
            $results['betRedeemDataArr'] = $betRedeemDataArr;
            return view('report.physical_money', ['data' => $results, 'isAdmin' => $hasAdmin, 'isDistributor' => $hasDistributor, 'isShop' => $hasShop]);
        } else {
            $end = Carbon::now();
            $start = Carbon::now()->subMonth();
            if ($hasAdmin) {
                $distributorList = $this->user->getDistributorList($hasDistributor, $user->id);
                $results = ['betRedeemDataArr' => [], 'shopLists' => [], 'distributorList' => $distributorList];
            }
            if ($hasDistributor) {
                $distributorList = $this->user->getDistributorList($hasDistributor, $user->id);
                $end = Carbon::now();
                $start = Carbon::now()->subMonth();
                $shopLists = $this->user->getShopList($hasDistributor, $user->id);
                $results = ['betRedeemDataArr' => [], 'shopLists' => $shopLists, 'distributorList' => $distributorList];
            }
            if ($hasShop) {
                $shopId = $user->id;
                $playerBetResults = $this->paymentLog->getPhysicalMoneyBet($shopId, $start, $end);
                $playerRedeemResults = $this->paymentLog->getPhysicalMoneyRedeemed($shopId, $start, $end);
                $betRedeemData = array_merge($playerBetResults, $playerRedeemResults);
                $betRedeemDataArr = [];
                if (!empty($betRedeemData)) {
                    foreach ($betRedeemData as $data) {
                        $betRedeemDataArr[$data->customer_id]['name'] = $data->customer_name;
                        if (isset($data->bet_amount) && !isset($betRedeemDataArr[$data->customer_id]['bet_amount'])) {
                            $betRedeemDataArr[$data->customer_id]['bet_amount'] = $data->bet_amount;
                        }
                        if (isset($data->redeem_amount) && !isset($betRedeemDataArr[$data->customer_id]['redeem_amount'])) {
                            $betRedeemDataArr[$data->customer_id]['redeem_amount'] = $data->redeem_amount;
                        }
                    }
                }
                $results = ['betRedeemDataArr' => $betRedeemDataArr, 'shopLists' => [], 'distributorList' => []];
            }
            return view('report.physical_money', ['data' => $results, 'isAdmin' => $hasAdmin, 'isDistributor' => $hasDistributor, 'isShop' => $hasShop]);
        }
    }

    public function transaction_log(Request $request) {
        $user = Auth::user();
        $hasShop = $user->hasRole(['Shop']);
        $hasDistributor = $user->hasRole(['Distributor']);
        $hasAdmin = $user->hasRole(['Admin']);
        if ($request->isMethod('post')) {
            $post_data = $request->all();
            if (!empty($post_data['start'])) {
                $carbon1 = new Carbon($post_data['start'], 'EST');
                $carbon1->setTimezone('UTC');
                $reportFrom = $carbon1->toDateTimeString();
            }
            if (!empty($post_data['end'])) {
                $carbon2 = new Carbon($post_data['end'], 'EST');
                $carbon2->setTimezone('UTC');
                $reportTo = $carbon2->toDateTimeString();
            }
            $start = !empty($post_data['start']) ? $reportFrom : Carbon::now()->subMonth();
            $end = !empty($post_data['end']) ? $reportTo : Carbon::now();
            if ($hasDistributor || $hasAdmin) {
                $shopId = $post_data['shop_id'];
                $distributorId = isset($post_data['distributor_id']) && !empty($post_data['distributor_id']) ? $post_data['distributor_id'] : $user->id;
                $shopLists = $this->user->getShopList(true, $distributorId);
                $distributorList = $this->user->getDistributorList($hasDistributor, $user->id);
                $results['shopLists'] = $shopLists;
                $results['distributorList'] = $distributorList;
            }
            if ($hasShop) {
                $shopId = $user->id;
            }
            $customerId = $post_data['customer_id'];
            $customerLists = $this->customer->findAllBy('shop_id', $shopId);
            $results['customerLists'] = $customerLists;

            $playerTransactionLogDataArr = $this->paymentLog->getTransactionLog($shopId, $customerId, $start, $end);

            $results['playerTransactionLogDataArr'] = $playerTransactionLogDataArr;
            //echo "<pre>"; print_r($results); echo "</pre>"; die;
            return view('report.transaction_log', ['data' => $results, 'isAdmin' => $hasAdmin, 'isDistributor' => $hasDistributor, 'isShop' => $hasShop]);
        } else {
            $end = Carbon::now();
            $start = Carbon::now()->subMonth();
            if ($hasAdmin) {
                $distributorList = $this->user->getDistributorList($hasDistributor, $user->id);
                $results = ['customerLists' => [], 'playerTransactionLogDataArr' => [], 'shopLists' => [], 'distributorList' => $distributorList];
            }
            if ($hasDistributor) {
                $distributorList = $this->user->getDistributorList($hasDistributor, $user->id);
                $end = Carbon::now();
                $start = Carbon::now()->subMonth();
                $shopLists = $this->user->getShopList($hasDistributor, $user->id);
                $results = ['customerLists' => [], 'playerTransactionLogDataArr' => [], 'shopLists' => $shopLists, 'distributorList' => $distributorList];
            }
            if ($hasShop) {
                $shopId = $user->id;
                $customerLists = $this->customer->findAllBy('shop_id', $shopId);
                $results['customerLists'] = $customerLists;
                $playerTransactionLogDataArr = [];
                $results = ['customerLists' => $customerLists, 'playerTransactionLogDataArr' => $playerTransactionLogDataArr, 'shopLists' => [], 'distributorList' => []];
            }
            return view('report.transaction_log', ['data' => $results, 'isAdmin' => $hasAdmin, 'isDistributor' => $hasDistributor, 'isShop' => $hasShop]);
        }
    }

    public function jackpot_history(Request $request) {
        $user = Auth::user();
        $hasShop = $user->hasRole(['Shop']);
        $hasDistributor = $user->hasRole(['Distributor']);
        $hasAdmin = $user->hasRole(['Admin']);
        if ($request->isMethod('post')) {
            $post_data = $request->all();
            if (!empty($post_data['start'])) {
                $carbon1 = new Carbon($post_data['start'], 'EST');
                $carbon1->setTimezone('UTC');
                $reportFrom = $carbon1->toDateTimeString();
            }
            if (!empty($post_data['end'])) {
                $carbon2 = new Carbon($post_data['end'], 'EST');
                $carbon2->setTimezone('UTC');
                $reportTo = $carbon2->toDateTimeString();
            }
            $start = !empty($post_data['start']) ? $reportFrom : Carbon::now()->subMonth();
            $end = !empty($post_data['end']) ? $reportTo : Carbon::now();
            if ($hasDistributor || $hasAdmin) {
                $shopId = $post_data['shop_id'];
                $distributorId = isset($post_data['distributor_id']) && !empty($post_data['distributor_id']) ? $post_data['distributor_id'] : $user->id;
                $shopLists = $this->user->getShopList(true, $distributorId);
                $distributorList = $this->user->getDistributorList($hasDistributor, $user->id);
                $results['shopLists'] = $shopLists;
                $results['distributorList'] = $distributorList;
            }
            if ($hasShop) {
                $shopId = $user->id;
            }
            $playerJackpotHistoryDataArr = $this->paymentLog->getJackpotHistory($shopId, $start, $end);

            $results['playerJackpotHistoryDataArr'] = $playerJackpotHistoryDataArr;
            //echo "<pre>"; print_r($results['playerJackpotHistoryDataArr']); echo "</pre>"; die;
            return view('report.jackpot_history', ['data' => $results, 'isAdmin' => $hasAdmin, 'isDistributor' => $hasDistributor, 'isShop' => $hasShop]);
        } else {
            $end = Carbon::now();
            $start = Carbon::now()->subMonth();
            if ($hasAdmin) {
                $distributorList = $this->user->getDistributorList($hasDistributor, $user->id);
                $results = ['playerJackpotHistoryDataArr' => [], 'shopLists' => [], 'distributorList' => $distributorList];
            }
            if ($hasDistributor) {
                $distributorList = $this->user->getDistributorList($hasDistributor, $user->id);
                $end = Carbon::now();
                $start = Carbon::now()->subMonth();
                $shopLists = $this->user->getShopList($hasDistributor, $user->id);
                $results = ['playerJackpotHistoryDataArr' => [], 'shopLists' => $shopLists, 'distributorList' => $distributorList];
            }
            if ($hasShop) {
                $shopId = $user->id;
                $results = ['playerJackpotHistoryDataArr' => [], 'shopLists' => [], 'distributorList' => []];
            }
            return view('report.jackpot_history', ['data' => $results, 'isAdmin' => $hasAdmin, 'isDistributor' => $hasDistributor, 'isShop' => $hasShop]);
        }
    }

    public function game_history(Request $request) {
        $user = Auth::user();
        $hasShop = $user->hasRole(['Shop']);
        $hasDistributor = $user->hasRole(['Distributor']);
        $hasAdmin = $user->hasRole(['Admin']);
        if ($request->isMethod('post')) {
            $post_data = $request->all();
            if (!empty($post_data['start'])) {
                $carbon1 = new Carbon($post_data['start'], 'EST');
                $carbon1->setTimezone('UTC');
                $reportFrom = $carbon1->toDateTimeString();
            }
            if (!empty($post_data['end'])) {
                $carbon2 = new Carbon($post_data['end'], 'EST');
                $carbon2->setTimezone('UTC');
                $reportTo = $carbon2->toDateTimeString();
            }
            $start = !empty($post_data['start']) ? $reportFrom : Carbon::now()->subMonth();
            $end = !empty($post_data['end']) ? $reportTo : Carbon::now();
            if ($hasDistributor || $hasAdmin) {
                $shopId = $post_data['shop_id'];
                $distributorId = isset($post_data['distributor_id']) && !empty($post_data['distributor_id']) ? $post_data['distributor_id'] : $user->id;
                $shopLists = $this->user->getShopList(true, $distributorId);
                $distributorList = $this->user->getDistributorList($hasDistributor, $user->id);
                $results['shopLists'] = $shopLists;
                $results['distributorList'] = $distributorList;
            }
            if ($hasShop) {
                $shopId = $user->id;
            }
            $customerId = $post_data['customer_id'];
            $customerLists = $this->customer->findAllBy('shop_id', $shopId);
            $results['customerLists'] = $customerLists;

            $playerGameHistory = $this->paymentLog->getGameHistory($shopId, $customerId, $start, $end);

            $results['playerGameHistory'] = $playerGameHistory;
            //echo "<pre>"; print_r($results);die;
            return view('report.game_history', ['data' => $results, 'isAdmin' => $hasAdmin, 'isDistributor' => $hasDistributor, 'isShop' => $hasShop]);
        } else {
            $end = Carbon::now();
            $start = Carbon::now()->subMonth();
            if ($hasAdmin) {
                $distributorList = $this->user->getDistributorList($hasDistributor, $user->id);
                $results = ['customerLists' => [], 'playerGameHistory' => [], 'shopLists' => [], 'distributorList' => $distributorList];
            }
            if ($hasDistributor) {
                $distributorList = $this->user->getDistributorList($hasDistributor, $user->id);
                $end = Carbon::now();
                $start = Carbon::now()->subMonth();
                $shopLists = $this->user->getShopList($hasDistributor, $user->id);
                $results = ['customerLists' => [], 'playerGameHistory' => [], 'shopLists' => $shopLists, 'distributorList' => $distributorList];
            }
            if ($hasShop) {
                $shopId = $user->id;
                $customerLists = $this->customer->findAllBy('shop_id', $shopId);
                $results['customerLists'] = $customerLists;
                $playerGameHistory = [];
                $results = ['customerLists' => $customerLists, 'playerGameHistory' => $playerGameHistory, 'shopLists' => [], 'distributorList' => []];
            }
            return view('report.game_history', ['data' => $results, 'isAdmin' => $hasAdmin, 'isDistributor' => $hasDistributor, 'isShop' => $hasShop]);
        }
    }

    public function account_history(Request $request) {
        $user = Auth::user();
        $hasShop = $user->hasRole(['Shop']);
        $hasDistributor = $user->hasRole(['Distributor']);
        $hasAdmin = $user->hasRole(['Admin']);
        if ($request->isMethod('post')) {
            $post_data = $request->all();
            if (!empty($post_data['start'])) {
                $carbon1 = new Carbon($post_data['start'], 'EST');
                $carbon1->setTimezone('UTC');
                $reportFrom = $carbon1->toDateTimeString();
            }
            if (!empty($post_data['end'])) {
                $carbon2 = new Carbon($post_data['end'], 'EST');
                $carbon2->setTimezone('UTC');
                $reportTo = $carbon2->toDateTimeString();
            }
            $start = !empty($post_data['start']) ? $reportFrom : Carbon::now()->subMonth();
            $end = !empty($post_data['end']) ? $reportTo : Carbon::now();
            if ($hasDistributor || $hasAdmin) {
                $shopId = $post_data['shop_id'];
                $distributorId = isset($post_data['distributor_id']) && !empty($post_data['distributor_id']) ? $post_data['distributor_id'] : $user->id;
                $shopLists = $this->user->getShopList(true, $distributorId);
                $distributorList = $this->user->getDistributorList($hasDistributor, $user->id);
                $results['shopLists'] = $shopLists;
                $results['distributorList'] = $distributorList;
            }
            if ($hasShop) {
                $shopId = $user->id;
            }

            $playerAccountHistoryLogArr = $this->paymentLog->getAccountHistoryLog($shopId, $start, $end);

            $results['playerAccountHistoryLogArr'] = $playerAccountHistoryLogArr;
            return view('report.account_history', ['data' => $results, 'isAdmin' => $hasAdmin, 'isDistributor' => $hasDistributor, 'isShop' => $hasShop]);
        } else {
            $end = Carbon::now();
            $start = Carbon::now()->subMonth();
            if ($hasAdmin) {
                $distributorList = $this->user->getDistributorList($hasDistributor, $user->id);
                $results = ['playerAccountHistoryLogArr' => [], 'shopLists' => [], 'distributorList' => $distributorList];
            }
            if ($hasDistributor) {
                $distributorList = $this->user->getDistributorList($hasDistributor, $user->id);
                $end = Carbon::now();
                $start = Carbon::now()->subMonth();
                $shopLists = $this->user->getShopList($hasDistributor, $user->id);
                $results = ['playerAccountHistoryLogArr' => [], 'shopLists' => $shopLists, 'distributorList' => $distributorList];
            }
            if ($hasShop) {
                $shopId = $user->id;
                
                $results = ['playerAccountHistoryLogArr' => [], 'shopLists' => [], 'distributorList' => []];
            }
            return view('report.account_history', ['data' => $results, 'isAdmin' => $hasAdmin, 'isDistributor' => $hasDistributor, 'isShop' => $hasShop]);
        }
    }

}
