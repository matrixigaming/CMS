@extends('layouts.loggedinheader')
@section('content')
<!-- DEV Notes
MESSAGES
Admin will need to do the following:
1. create messages for message bank
	a. when creating a message they will pick a dept, fill in the title, fill in the message
	b. in the database we need to capture the user that is logged in and what date/time they created this message
  c. when the admin clicks the edit pencil we need to load the same modal but change the items in the modal
    i. change the title to say - edit message
    ii. load the department, title and message to edit

2. send a message to a specific user
	a. the admin will click the send message button, this triggers the send message modal
	b. We show the admin's avatar because this will show up when sending the message to the user
  c. the first text box is a customer search box, as the admin types we need to display customers. they will select the customer
  d. the admin will pick the deparment that they want to load messages for
  e. when the department is selected we will show all messages that are in the database for that department. The user will select 1 and click send
  f. they need to only be able to select 1 message to send at a time
3. manage messages
	a. here they can view all messages that have been created in the message bank
	b. they can click on the edit pencil it will bring up a modal to edit the message that has been created
  c. they can also delete a message from this modal. we need to make sure when clicking delete it asks the admin if they are sure the want to delete it
4. message reports
  a. this table shows all messages that have been send from the admin to the customers
  b. they admin can click the view icon to all details of the message. we load the admin's avatar who sent it, we show the avatar of the person we sent to
  c. inside the modal we show all data
  d. the admin can resend the message if they need to


Creating Message:
EXAMPLE:
Title - Delinquent Account
Dept - accounting
Message - Your account is currently delinquent. Please update your billing online. If you have questions please contact
the accounting department at - 123-334-2222



NOTIFICATIONS

The notifications will be located in a database and pull when certain events occur. The admin will have the ability to edit the text that shows up to the user.
  a. when the admin clicks the modal we will load the notifcation
  b. they can change the main text the user will see. A list of notifications to be added are below. These get trigged when the field in the database is updated
  c. we have a report that will display notifications that have been sent
Profile Notifications:
Email address has been updated
Phone number has been updated
Name has been updated
Location has been updated
Google has been updated
Facebook has been updated
Twitter has been updated
Avatar has been updated

Account Changes Notifications:
Payment status
Committment Length
Commitment Start Date
Commitment End Date
[id] => 25
                    [title] => Email Updated
                    [updated_at] => 2016-04-08 15:35:52
                    [type] => Profile
                    [created_by] => bhaskar
-->
<?php
//echo "<pre>"; print_r($data['messages']); die;
?>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Admin - Messages & Notifications
          </h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Admin - Notifications</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

          <div class="row">
          	<div class="col-md-3">                    
          		<a  href="" class="btn btn-primary btn-block margin-bottom msg-popup-modal" data-toggle="modal"  data-id="createmsg" data-post="data-php" data-action="create">Create Message</a>
          		<a href="" class="btn btn-danger btn-block margin-bottom msg-popup-modal" data-toggle="modal" data-id="createmsg" data-post="data-php" data-action="sendmsg">Send Message</a>
                        <a class="btn btn-success btn-block margin-bottom msg-popup-modal" href=""  data-toggle="modal" data-id="createmsg" data-post="data-php" data-action="create_notification">Create Notifications</a>
          	</div>
            <div class="col-md-9">
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#manageMessages" data-toggle="tab">Manage Messages</a></li>
                  <li><a href="#messageReports" data-toggle="tab">Message Reports</a></li>
                  <li><a href="#manageNoti" data-toggle="tab">Manage Notifications</a></li>
                  <li><a href="#notiReports" data-toggle="tab">Notification Reports</a></li>
                </ul>
                <div class="tab-content">
                <!-- manage messages -->
                <div class="active tab-pane" id="manageMessages">
                <div class="box-body">
                  <table class="table table-bordered table-striped fullDetailTable">
                    <thead>
                      <tr>
                        <th>Department</th>
                        <th>Title</th>
                        <th>Created By</th>
                        <th>Last Updated</th>
                        <th>Edit</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($data['messages']['messages'] as $msg)                            
                      <tr>
                        <td>{{ $msg->department_name }}</td>
                        <td>{{ $msg->title }}</td>
                        <td>{{ $msg->created_by }}</td>
                        <td>{{ $msg->updated_at }}</td>
                        
                        <td>
<a class="msg-popup-modal" href="" data-toggle="modal"  data-id="{{ $msg->id }}" data-post="data-php" data-action="update"><i class="fa fa-pencil"></i></a>
<!--<a href="#" data-toggle="modal" data-target=".adminMessageModal"><i class="fa fa-pencil"></i></a>--></td>
                      </tr>
                      @endforeach
                      
                    </tbody>
                  </table>
                </div>

                </div>
                <!-- message reports -->
                <div class="tab-pane" id="messageReports">
                  <table class="table table-bordered table-striped fullDetailTable msg-report-ajax-response">
                    <thead>
                      <tr>
                        <th>Customer</th>
                        <th>Department</th>
                        <th>Sent From</th>
                        <th>Message</th>
                        <th>Date Sent</th>
                        <th>Date Viewed</th>
                        <th>View</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($data['message_reports'] as $msg)                            
                      <tr>
                        <td>{{ $msg->sent_to }}</td>
                        <td>{{ $msg->department }}</td>
                        <td>{{ $msg->sent_by }}</td>
                        <td>{{ $msg->message_title }}</td>
                        <td>{{ $msg->created_at }}</td>
                        <td> - </td>                        
                        <td><a class="msg-popup-modal" href="" data-toggle="modal"  data-id="{{ $msg->id }}" data-post="data-php" data-action="view_message"><i class="fa fa-binoculars"></i></a></td>
                      </tr>
                      @endforeach    
                    </tbody>
                  </table>
                </div>
                <!-- manage notifications -->
                <div class="tab-pane" id="manageNoti">
                  <table class="table table-bordered table-striped fullDetailTable">
                    <thead>
                      <tr>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Updated By</th>
                        <th>Last Updated</th>
                        <th>Edit</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($data['messages']['notification'] as $msg)                            
                      <tr>
                        <td>{{ $msg->type }}</td>
                        <td>{{ $msg->title }}</td>
                        <td>{{ $msg->created_by }}</td>
                        <td>{{ $msg->updated_at }}</td>
                        
                        <td>
<a class="msg-popup-modal" href="" data-toggle="modal"  data-id="{{ $msg->id }}" data-post="data-php" data-action="update_notification"><i class="fa fa-pencil"></i></a>
<!--<a href="#" data-toggle="modal" data-target=".adminMessageModal"><i class="fa fa-pencil"></i></a>--></td>
                      </tr>
                      @endforeach
                      
                    </tbody>
                  </table>
                </div>
                <!-- reports -->
                <div class="tab-pane" id="notiReports">
                  <table class="table table-bordered table-striped fullDetailTable">
                    <thead>
                      <tr>
                        <th>Type</th>
                        <th>Customer</th>
                        <th>Date Sent</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Avatar Updated</td>
                        <td>James Doe</td>
                        <td>2-29-16</td>
                      </tr>
                      <tr>
                        <td>Email Updated</td>
                        <td>Jane Doe</td>
                        <td>2-25-16</td>
                      </tr>
                      <tr>
                        <td>Committment Length</td>
                        <td>Lily Lowe</td>
                        <td>2-22-16</td>
                      </tr>
                      <tr>
                        <td>Payment Status</td>
                        <td>Tony Stark</td>
                        <td>2-19-16</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                </div><!-- /.tab-content -->
              </div><!-- /.nav-tabs-custom -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

@endsection
