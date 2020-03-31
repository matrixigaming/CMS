@extends('layouts.loggedinheader')
@section('content')

<?php
//echo "<pre>"; print_r($data['messages']); die;
?>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Manage Distributors
          </h1>
          <ol class="breadcrumb">
            <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Distributor - Manage Users</li>
          </ol>
        </section>
@if(Session::has('message')) <div class="alert alert-info"> {{Session::get('message')}} </div> @endif
        <!-- Main content -->
        <section class="content">

          <div class="row">
          	<div class="col-md-2">                    
          		<!-- <a  href="" class="btn btn-primary btn-block margin-bottom msg-popup-modal" data-toggle="modal" data-controller="user"  data-id="createmsg" data-post="data-php" data-action="create_user">Create User</a>-->
                        <a  href="{{ url('/distributor/add') }}" class="btn btn-primary btn-block margin-bottom" >Create Distributor</a>
          	</div>
            <div class="col-md-10">
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#manageUsers" data-toggle="tab">Manage Distributors</a></li>
                </ul>
                <div class="tab-content">
                <!-- manage users -->
                <div class="active tab-pane" id="manageUsers">
                <div class="box-body">
                  <table class="table table-bordered table-striped fullDetailTable">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>A. Credit</th>
                        <th>RTP</th>
                        <th>Last Login</th>
                        <th>Active</th>
                        <th>Edit</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $user)                            
                      <tr>
                        <td>{{ $user->first_name .' '.$user->last_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->available_credit }}</td>
                        <td>{{ $user->distributor_rtp_variant }}</td>
                        <td>{{ $user->updated_at }}</td>
                        <td><span class="hide">{{ $user->active }}</span>
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default btn-sm @if($user->active == 1) {{ 'active' }} @endif">
                                  <input class="list-update" name="active" data-controller="user" data-token="{{ csrf_token() }}" data-field="active" data-listing-id="{{ $user->id }}" value="1" type="radio"> Yes
                                </label>
                                <label class="btn btn-default btn-sm  @if($user->active == 0) {{ 'active' }} @endif">
                                  <input class="list-update" name="active" data-controller="user" data-token="{{ csrf_token() }}" data-field="active" data-listing-id="{{ $user->id }}" value="0" type="radio"> No
                                </label>
                            </div>
                        </td>
                        <td>
<a class="msg-popup-modal" href="" data-toggle="modal"  data-id="{{ $user->id }}" data-post="data-php" data-controller="user" data-action="update_credit"><i class="fa fa-dollar"></i></a>
&nbsp;&nbsp;|&nbsp;&nbsp;<a href="{{ url('/distributor/edit/'.$user->id) }}"><i class="fa fa-pencil"></i></a></td>
                      </tr>
                      @endforeach 
                      
                    </tbody>
                  </table>
                </div>

                </div>
                
                </div><!-- /.tab-content -->
              </div><!-- /.nav-tabs-custom -->
            </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

@endsection
