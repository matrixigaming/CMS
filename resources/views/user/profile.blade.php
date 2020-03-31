<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content load_modal"></div>
    </div>
</div>

@extends('layouts.loggedinheader')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            User Profile
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/home')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">User profile</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <?php
                        $avatar_path = Config::get('constants.user_avatar_path');
                        $path = !empty($data['profile']->avatar) ? $avatar_path . $data['profile']->avatar : 'dist/img/avatar.png';
                        //{{ asset($path) }}
                        ?>
                        <img class="profile-user-img img-responsive img-circle" src="{{ url($path) }}" alt="User profile picture">
                        <h3 class="profile-username text-center">{{ $data['profile']->first_name .' '.$data['profile']->last_name }}</h3>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

                <!-- About Me Box -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">About Me</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <strong><i class="fa fa-envelope margin-r-5"></i>  Email</strong>
                        <p class="text-muted profile-email">
                            {{ $data['profile']->email }}
                        </p>

                        <hr>

                        <strong><i class="fa fa-phone margin-r-5"></i> Phone</strong>
                        <p class="text-muted profile-phone">{{ $data['profile']->phone }}</p>

                        <hr>

                        <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>
                        <p class="text-muted profile-location">{{ $data['profile']->city }}, {{ $data['profile']->state }}</p>

                        <hr>
                        <strong><i class="fa fa-share-alt margin-r-5"></i> Social Media</strong>
                        <p>
                            <a href="{{ isset($data['profile']->google_url) && !empty($data['profile']->google_url) ? $data['profile']->google_url : 'javascript:void(0)' }}" target="_blank" ><span class="label label-danger">Google</span></a>
                            <a href="{{ isset($data['profile']->facebook_url) && !empty($data['profile']->facebook_url) ? $data['profile']->facebook_url : 'javascript:void(0)' }}" target="_blank" ><span class="label label-primary">Facebook</span></a>
                            <a href="{{ isset($data['profile']->twitter_url) && !empty($data['profile']->twitter_url) ? $data['profile']->twitter_url : 'javascript:void(0)' }}" target="_blank" ><span class="label label-info">Twitter</span></a>
                        </p>

                        <hr>

                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
            <div class="col-md-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#timeline" data-toggle="tab">Timeline</a></li>
                        <li><a href="#settings" data-toggle="tab">Settings</a></li>
                        <li><a href="#avatar" data-toggle="tab">Avatar</a></li>
                        <li><a href="#changePassword" data-toggle="tab">Change Password</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane ajax-response-div" id="timeline">
                            <!-- The timeline -->
                            <ul class="timeline timeline-inverse">
                                <?php
                                $tempDate = '';
                                $Helper = new Helper();
                                //echo "<pre>"; print_r($data['message_reports']); echo "</pre>";
                                if (isset($data['timeline']) && !empty($data['timeline'])) {
                                    $counter = 1;
                                    foreach ($data['timeline'] as $key => $timeline) {
                                       // die($timeline->getOriginal('created_at'));
                                        $lableDate = date('j M Y', strtotime($timeline->getOriginal('created_at')));
                                        if($lableDate != $tempDate){
                                        $lableClass = $counter % 2 ? 'bg-green' : 'bg-red';
                                        echo '<li class="time-label">
                                                <span class="' . $lableClass . '">
                                                  ' . $lableDate . '
                                                </span>
                                              </li>';
                                        $counter++;
                                        }
                                        $tempDate = $lableDate;
                                        //foreach ($timeline as $key => $timelineData) {
                                            $message_sent = $Helper->getTimeDuration($timeline->getOriginal('created_at')); //date('j M h:i a', strtotime($timelineData['created_at']));
                                            if ($timeline->type == 'Message') {
                                                $readClass = !empty($timeline->read_at) ? '' : 'msgReadClass';
                                                ?>
                                                <!-- timeline item -->
                                                <li id="timeline-row-<?php echo $timeline->id; ?>">
                                                    <i class="fa fa-support bg-red"></i>
                                                    <div class="timeline-item">
                                                        <span class="time"><i class="fa fa-clock-o"></i> <?php echo $message_sent; ?></span>
                                                        <h3 class="timeline-header <?php echo $readClass; ?>"><a href="#"><?php echo $timeline->fromUser['first_name'].' '. $timeline->fromUser['last_name']; ?></a> sent you an message</h3>
                                                        <div class="timeline-body <?php echo $readClass; ?>">
                                                            <?php
                                                            $message_content = strlen($timeline->message->content) > 203 ? substr($timeline->message->content, 0, 200) . '...' : $timeline->message->content;
                                                            echo $message_content;
                                                            ?>
                                                        </div>
                                                        <div class="timeline-footer">
                                                            <a  href="" class="btn btn-primary btn-xs msg-popup-modal" data-toggle="modal" data-controller="messages"  data-id="<?php echo $timeline->id; ?>" data-post="data-php" data-action="view_user_message">Read More</a>                                    
                                                            <a class="btn btn-danger btn-xs pop-up-modal-delete-form-submit" data-id="<?php echo $timeline->id; ?>" data-controller="mnr" data-action="msg_report_delete">Delete</a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <!-- END timeline item -->    
                                            <?php } elseif ($timeline->type == 'Notification') { ?>
                                                <!-- timeline item -->                      
                                                <li>
                                                    <i class="fa fa-envelope bg-aqua"></i>
                                                    <div class="timeline-item">
                                                        <span class="time"><i class="fa fa-clock-o"></i> <?php echo $message_sent; ?></span>
                                                        <h3 class="timeline-header no-border"><a href="#"><?php echo $timeline->notification->title; ?></a> <?php echo $timeline->notification->content; ?></h3>
                                                    </div>
                                                </li>
                                                <!-- END timeline item -->   
                                            <?php
                                            }
                                        //} //end foreach($timeline as $key => $data)
                                        
                                    } //end foreach($data['timeline'] as $key => $timeline)
                                } //end of if(isset($data['timeline']) && !empty($data['timeline']))
                                ?>  

                                <li>
                                    <i class="fa fa-clock-o bg-gray"></i>
                                </li>
                                <li style="float: right; margin-top: -50px; padding-right: 16px;">{!! $data['timeline']->links() !!}</li>
                            </ul>
                        </div><!-- /.tab-pane -->
                        <div id="modal-response-msg" style="color: green; font-size: 16px; margin: 0 15px;"></div>
                        <div class="tab-pane" id="settings">                      
                            {!! Form::open(array('url' => 'user/profilePost', 'id'=>'user-profile-update', 'class'=>'form-horizontal')) !!}                    
                            <div class="form-group">
                                <label for="inputFirstName" class="col-sm-2 control-label">First Name</label>
                                <div class="col-sm-6">
                                    {{ Form::text('first_name', $data['profile']->first_name, array('class'=>'form-control event-trigger-class', 'id'=>'inputFirstName', 'placeholder'=>'First Name')) }}                          
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputLastName" class="col-sm-2 control-label">Last Name</label>
                                <div class="col-sm-6">
                                    {{ Form::text('last_name', $data['profile']->last_name, array('class'=>'form-control event-trigger-class', 'id'=>'inputLastName', 'placeholder'=>'Last Name')) }}                          
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputEmail" class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-6">
                                    {{ Form::text('email', $data['profile']->email, array('class'=>'form-control event-trigger-class', 'id'=>'inputEmail', 'placeholder'=>'Email')) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPhone" class="col-sm-2 control-label">Phone</label>
                                <div class="col-sm-6">
                                    {{ Form::text('phone', $data['profile']->phone, array('class'=>'form-control event-trigger-class onlyNumeric', 'id'=>'inputPhone', 'placeholder'=>'Phone')) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputCity" class="col-sm-2 control-label">City</label>
                                <div class="col-sm-6">
                                    {{ Form::text('city', $data['profile']->city, array('class'=>'form-control event-trigger-class', 'id'=>'inputCity', 'placeholder'=>'City')) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputState" class="col-sm-2 control-label">State</label>
                                <div class="col-sm-6">
                                    {{ Form::text('state', $data['profile']->state, array('class'=>'form-control event-trigger-class', 'id'=>'inputState', 'placeholder'=>'State')) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputZipCode" class="col-sm-2 control-label">Zip Code</label>
                                <div class="col-sm-6">
                                    {{ Form::text('zipcode', $data['profile']->zipcode, array('class'=>'form-control event-trigger-class', 'id'=>'inputZipCode', 'placeholder'=>'Zip Code')) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputGoogle" class="col-sm-2 control-label">Google +</label>
                                <div class="col-sm-6">
                                    {{ Form::text('google_url', $data['profile']->google_url, array('class'=>'form-control event-trigger-class', 'id'=>'inputGoogle', 'placeholder'=>'Google+ Url')) }}
                                    <span style="font-size: 11px; font-weight: bold; color: #999;">Please provide full url.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputFacebook" class="col-sm-2 control-label">Facebook</label>
                                <div class="col-sm-6">
                                    {{ Form::text('facebook_url', $data['profile']->facebook_url, array('class'=>'form-control event-trigger-class', 'id'=>'inputFacebook', 'placeholder'=>'Facebook Url')) }}
                                    <span style="font-size: 11px; font-weight: bold; color: #999;">Please provide full url.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputTwitter" class="col-sm-2 control-label">Twitter</label>
                                <div class="col-sm-6">
                                    {{ Form::text('twitter_url', $data['profile']->twitter_url, array('class'=>'form-control event-trigger-class', 'id'=>'inputTwitter', 'placeholder'=>'Twitter Url')) }}
                                    <span style="font-size: 11px; font-weight: bold; color: #999;">Please provide full url.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="hidden" name="id" value="{{ $data['profile']->id}}" />
                                    {{ Form::submit('Submit', array('class'=>'btn btn-danger pop-up-modal-form-submit')) }}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div><!-- /.tab-pane -->
                        <div class="tab-pane" id="avatar">
                            {!! Form::open(array('url' => 'user/avatarPost', 'id'=>'user-avatar-update', 'class'=>'form-horizontal', 'files'=>true)) !!}
                            <div class="form-group">
                                <label for="inputTwitter" class="col-sm-2 control-label">Select File</label>
                                <div class="col-sm-6">
                                    {{ Form::file('avatar','', array('class'=>'form-control', 'id'=>'avatar')) }}  
                                    {{ Form::hidden('first_name', $data['profile']->first_name) }}
                                    {{ Form::hidden('last_name', $data['profile']->last_name) }}
                                    {{ Form::hidden('email', $data['profile']->email) }}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="hidden" name="id" value="{{ $data['profile']->id}}" />
                                    {{ Form::submit('Submit', array('class'=>'btn btn-danger pop-up-modal-form-submit-with-image', 'name'=>'submit')) }}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        <div class="tab-pane" id="changePassword">
                           {!! Form::open(array('url' => 'user/changePasswordPost', 'id'=>'user-password-update', 'class'=>'form-horizontal')) !!}                    
                            <div class="form-group">
                                <label for="inputOldPassword" class="col-sm-2 control-label">Current Password</label>
                                <div class="col-sm-6">
                                    {{ Form::password('old_password', array('class'=>'form-control', 'id'=>'inputOldPassword', 'placeholder'=>'Current Password')) }}                          
                                </div>
                            </div>
                           <div class="form-group">
                                <label for="inputNewPassword" class="col-sm-2 control-label">New Password</label>
                                <div class="col-sm-6">
                                    {{ Form::password('password', array('class'=>'form-control', 'id'=>'inputNewPassword', 'placeholder'=>'New Password')) }}                          
                                </div>
                            </div> 
                           <div class="form-group">
                                <label for="inputConfirmNewPassword" class="col-sm-2 control-label">Confirm New Password</label>
                                <div class="col-sm-6">
                                    {{ Form::password('password_confirmation', array('class'=>'form-control', 'id'=>'inputConfirmNewPassword', 'placeholder'=>'Confirm New Password')) }}                          
                                </div>
                            </div>
                           <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <input type="hidden" name="id" value="{{ $data['profile']->id}}" />
                                    {{ Form::submit('Submit', array('class'=>'btn btn-danger pop-up-modal-form-submit')) }}
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        <!-- tab pane -->
                    </div><!-- /.tab-content -->
                </div><!-- /.nav-tabs-custom -->
            </div><!-- /.col -->
        </div><!-- /.row -->

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

@endsection
