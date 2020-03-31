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
            Manage Shops
          </h1>
          <ol class="breadcrumb">
            <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Shop - Manage Users</li>
          </ol>
        </section>
@if(Session::has('message')) <div class="alert alert-info"> {{Session::get('message')}} </div> @endif
        <!-- Main content -->
        <section class="content">

          <div class="row">
          	<div class="col-md-2">                    
          		<!-- <a  href="" class="btn btn-primary btn-block margin-bottom msg-popup-modal" data-toggle="modal" data-controller="user"  data-id="createmsg" data-post="data-php" data-action="create_user">Create User</a>-->
                        <a  href="{{ url('/shop/add') }}" class="btn btn-primary btn-block margin-bottom" >Create Shop</a>
                        @if($isAdmin)
                        <a  href="" class="btn btn-primary btn-block margin-bottom msg-popup-modal" data-toggle="modal" data-controller="user"  data-id="createmsg" data-post="data-php" data-action="manage_tv_video">Manage TV Video</a>
                        @endif
          	</div>
            <div class="col-md-10">
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#manageUsers" data-toggle="tab">Manage Shops</a></li>
                </ul>
                <div class="tab-content">
                <!-- manage users -->
                <div class="active tab-pane" id="manageUsers">
                <div class="box-body">
                  <table class="table table-bordered table-striped fullDetailTable">
                    <thead>
                      <tr>
                        <th>Shop Name</th>
                        <th>Shop Code</th>
                        <th>Contact Person</th>
                        <th>Email</th>
                        <th>A. Credit</th>
                        <th>Signup</th>
                        <th>Active</th>
                        <th>Edit</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $user)                            
                      <tr>
                        <td>{{ $user->shop_name }}</td>
                        <td>{{ str_pad($user->shop_code, 3, "0", STR_PAD_LEFT) }}</td>
                        <td>{{ $user->first_name}}</td><!-- .' '.$user->last_name -->
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->available_credit }}</td>
                        <td>{{ $user->created_at }}</td>
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
&nbsp;&nbsp;|&nbsp;&nbsp;<a href="{{ url('/shop/edit/'.$user->id) }}"><i class="fa fa-pencil"></i></a></td>
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
