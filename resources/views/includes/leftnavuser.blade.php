<li class="header">DISTRIBUTOR NAVIGATION</li>
    <!--<li>
      <a href="{{ url('/home') }}">
        <i class="fa fa-dashboard"></i> <span>Admin - Dashboard</span>
      </a>
    </li>-->
    <li>
      <a href="{{ url('/dashboard') }}">
        <i class="fa fa-dashboard"></i> <span>Distributor - Dashboard</span>
      </a>
    </li>   
    <li>
      <a href="{{ url('/states') }}">
        <i class="fa fa-bell"></i> <span>Manage State</span>
      </a>
    </li> 
    <li>
      <a href="#">
        <i class="fa fa-gear"></i> <span>Manage Game</span>
      </a>
    </li> 
    <li>
      <a href="{{ url('/distributor/list') }}">
        <i class="fa fa-users"></i> <span>Manage Distributor</span> 
      </a>
    </li>
    <li>
      <a href="{{ url('/shop/list') }}">
        <i class="fa fa-users"></i> <span>Manage Shop</span> 
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