<li style="color: #f00; background: lightyellow; padding: 10px 25px 10px 15px; font-size: 12px;">SHOP CODE : {{ isset($user->shop_code) && $user->shop_code ? str_pad($user->shop_code, 3, "0", STR_PAD_LEFT) : 'N/A'}}</li>
<li class="header">SHOP NAVIGATION</li>
    <li>
      <a href="{{ url('/dashboard') }}">
        <i class="fa fa-dashboard"></i> <span>Shop - Dashboard</span>
      </a>
    </li>
       
    <li>
      <a href="{{ url('/customer/list') }}">
        <i class="fa fa-gear"></i> <span>Manage Customer</span>
      </a>
    </li> 
    <li class="treeview">
      <a href="javascript:void(0)">
        <i class="fa fa-bell"></i> <span>Reports</span> 
      </a>
       <ul class="treeview-menu">
                <li><a href="{{ url('/reports/physical_money') }}"><i class="fa fa-circle-o"></i> All Players Physical Money</a></li>
                <!--<li><a href="{{ url('/reports/physical_money') }}"><i class="fa fa-circle-o"></i> Total Physical Profits/(Losses)</a></li>-->
                <li><a href="{{ url('/reports/game_rtp') }}"><i class="fa fa-circle-o"></i> Games RTP</a></li>
                <li><a href="{{ url('/reports/jackpot_rtp') }}"><i class="fa fa-circle-o"></i> Jackpot RTP</a></li>
                <li><a href="{{ url('/reports/transaction_log') }}"><i class="fa fa-circle-o"></i> Transaction Log</a></li>
                <li><a href="{{ url('/reports/game_history') }}"><i class="fa fa-circle-o"></i> Game History</a></li>
                <li><a href="{{ url('/reports/account_history') }}"><i class="fa fa-circle-o"></i> Account History</a></li>
                <li><a href="{{ url('/reports/jackpot_history') }}"><i class="fa fa-circle-o"></i> Jackpot History</a></li>
              </ul>
    </li>