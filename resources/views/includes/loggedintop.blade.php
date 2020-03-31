<!-- 
1. we have a messages area. This will load messages sent from iBoomerang to users. Examples could be: promos iboomerang is running, an invite
to join training, or any marketing messages
  a. when they click a message in the list they will see the modal that pops up. file is - includes/modals.php
  b. when the modal loads we need will display the name of the message in the header when the time it was sent. If it has been before an hour we show how many min it was. If it has been over an hour we show hours example - 1 hour, if it has been days we show days
  c. We then load the admin avatar that sent them the message
  d. to the right of that we load the actual content
  e. the user has two buttons, the first is just to close the modal, the second is mark as read, this will take it away from the message total at the top nav but not delete the message. they can still see the message in their message area
  f. if the user clicks see all messages it will take them to the main message page, it will automatically remove messages from the header as they are viewing all
2. The bell is the account alert notifications. This will load any account notifications for the client. For example if the user's account
was over due, we can send them the notification
  a. when an alert is clicked we need to mark it as read and remove it from the top nav
  b. when they click a specific alert it should take them to the user alert page and go right to that alert - http://localhost:8888/iboom/admin/index.php?page=user-notifications
  c. If the user clicks view all we will take them to the notifications page and need to load the notifications tab, we will also remove all notifications from the header because we are viewing all
3. when the user clicks their avatar they will see a box to go to their profile. We load their avatar, when they signed up, 
a profile button and a sign out button
-->
<?php //echo "<pre>"; print_r($top_data); echo "</pre>"; ?>
<?php $user = Auth::user();?>
<?php
    $avatar_path = Config::get('constants.user_avatar_path');
    $path = !empty($user->avatar) ? $avatar_path . $user->avatar : 'dist/img/avatar.png';
?>
      <header class="main-header">
        <!-- Logo {{url('/home') }}-->
        <a href="#" class="logo" style="border-bottom: 1px solid #565656; color: #f8f411;">
          <!-- mini logo for sidebar mini 50x50 pixels -->
                                               <span class="logo-mini" style="color: #fff;">CMS<!--<img src="{{asset('dist/img/iboomlogo_small.png') }}">--></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg" style="border-bottom: 1px solid #565656; color: #f8f411;" >CMS<!--<img style="height: 53px;" src="{{asset('dist/img/lreslogo.png') }}">--></span>
        </a>
        
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <?php /*
              <!-- Messages: style can be found in dropdown.less-->
              <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-envelope-o"></i>
                  <span class="label label-success">{{ $top_data['message_reports_count'] }}</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have {{ $top_data['message_reports_count'] }} messages</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                     <?php
                     
                        $Helper = new Helper();

                        //echo "<pre>"; print_r($data['message_reports']); echo "</pre>";
                        if (isset($top_data['message_reports']) && !empty($top_data['message_reports'])) {
                            foreach ($top_data['message_reports'] as $msg) {                                
                                $labelDate = date('j M Y', strtotime($msg->getOriginal('created_at')));
                                $message_sent = $Helper->getTimeDuration($msg->getOriginal('created_at')); //date('j M h:i a', strtotime($msg->created_at));                                
                                $avatar_path = Config::get('constants.user_avatar_path'); 
                                $sent_by_avatar_path = !empty($msg->sendFrom['avatar']) ? $avatar_path.$msg->sendFrom['avatar'] : 'dist/img/avatar.png';
                                ?>                        
                                <li><!-- start message -->
                                <a  href="" class="msg-popup-modal" data-toggle="modal" data-controller="messages"  data-id="{{ $msg->id }}" data-post="data-php" data-action="view_user_message">                                    
                                  <div class="pull-left">
                                    <img src="{{ url($sent_by_avatar_path) }}" class="img-circle" alt="User Image">
                                  </div>
                                  <h4>
                                    {{ $msg->message->title }}
                                    <small><i class="fa fa-clock-o"></i> <?php echo $message_sent; ?></small>
                                  </h4>
                                  <p>
                                      <?php
                                        $message_content = strlen($msg->message->content > 38) ? substr($msg->message->content, 0, 35) . '...' : $msg->message->content;
                                        echo $message_content;
                                      ?>
                                  </p>
                                </a>
                              </li><!-- end message -->      
                                <?php
                            } //end foreach
                        } //end of if(isset($data['message_reports']) && !empty($data['message_reports']))
                        ?> 
                    </ul>
                  </li>
                  <?php //if($top_data['message_reports_count'] > $top_data['data_limit']) : ?>
                    <li class="footer"><a href="{{ url('/messages/user-notification') }}">See All Messages</a></li>
                  <?php //endif; ?>
                </ul>
              </li>
              <!-- Notifications: style can be found in dropdown.less -->
              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  <span class="label label-warning">{{ $top_data['notification_reports_count'] }}</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have {{ $top_data['notification_reports_count'] }} notifications</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                        <?php                            
                            if (isset($top_data['notification_reports']) && !empty($top_data['notification_reports'])) {
                                foreach ($top_data['notification_reports'] as $nr) { ?>
                                    <!-- timeline item --> 
                                    <li>
                                        <a href="{{ url('/messages/user-notification') }}">
                                            <i class="fa fa-support text-red"></i> <?php echo $nr->notification->title .' '.$nr->notification->content; ?>
                                        </a>
                                    </li>
                                    <!-- END timeline item -->       
                                    <?php
                                } //end foreach
                            } //end of if(isset($data['message_reports']) && !empty($data['message_reports']))
                            ?>
                    </ul>
                  </li>
                  <li class="footer"><a href="{{ url('/messages/user-notification') }}">View all</a></li>
                </ul>
              </li>
              <!-- User Account: style can be found in dropdown.less -->
              */?>
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="{{ url($path) }}" class="user-image" alt="User Image">
                  <span class="hidden-xs profile-user-first-name">{{ $user->first_name }}</span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="{{ url($path) }}" class="img-circle" alt="User Image">
                    <p>
                        <span class="profile-user-full-name">{{ $user->first_name .' '. $user->last_name }}</span>
                      <small>Member since <?php echo date('Y', strtotime($user->created_at)); ?></small>
                    </p>
                  </li>
                  
                  <li class="user-footer">
                    <div class="pull-left">
                      Profile<!--<a href="{{ url('/user/profile') }}" class="btn btn-default btn-flat">Profile</a>-->
                    </div>
                    <div class="pull-right">
                      <a href="{{ url('/logout') }}" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>
