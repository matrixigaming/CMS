@extends('layouts.loggedinheader')
@section('content')

<?php
//echo "<pre>"; print_r($data); die;
?>
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Manage Customers
          </h1>
          <ol class="breadcrumb">
            <li><a href="{{ url('/dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Shop - Manage Customer</li>
          </ol>
        </section>
@if(Session::has('message')) <div class="alert alert-info"> {{Session::get('message')}} </div> @endif
        <!-- Main content -->
        <section class="content">

          <div class="row">
          	<div class="col-md-2">                    
          		<a  href="" class="btn btn-primary btn-block margin-bottom msg-popup-modal" data-toggle="modal" data-controller="customer"  data-id="createmsg" data-post="data-php" data-action="create_customer">Add Customer</a>
                        <!-- <a  href="{{ url('/customer/add') }}" class="btn btn-primary btn-block margin-bottom" >Add Customer</a>-->
          	</div>
            <div class="col-md-10">
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#manageUsers" data-toggle="tab">Manage Customers</a></li>
                </ul>
                <div class="tab-content">
                <!-- manage users -->
                <div class="active tab-pane" id="manageUsers">
                <div class="box-body">
                  <table class="table table-bordered table-striped fullDetailTable">
                    <thead>
                      <tr>
                        <th>Customer Name</th>
                        <th>Code</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Credit</th>
                        <th>Last Recharge</th>
                        @if($loggedInUser->sweepstakes)
                        <th>Win Amount</th>
                        @endif
                        <th>Created</th>
                        <th>Active</th>
                        <th>Top Up</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $user)                            
                      <tr><?php //echo "<pre>"; print_r($user); echo "</pre>";//die;?>
                        <td>{{ $user['name'] }}</td>
                        <td>{{ $user['code'] }}</td>
                        <td>{{ $user['email'] }}</td>
                        <td>{{ $user['mobile'] }}</td>
                        <td>{{ $user['balance'] }}</td>
                        <td>
                            <?php if(!empty($user['rechargeDetails']) && $user['rechargeDetails']['can_edit'] && !empty($user['rechargeDetails']['amount'])): ?>
                                <a class="msg-popup-modal" href="" data-toggle="modal"  data-id="{{ $user['rechargeDetails']['id'] }}" data-post="data-php" data-controller="customer" data-action="adjust_transaction" title="Can edit this amount.">{{ $user['rechargeDetails']['amount'] }}</a>
                            <?php else: ?>
                                <?php echo !empty($user['rechargeDetails']) && isset($user['rechargeDetails']['amount']) ? $user['rechargeDetails']['amount'] : 0;?>
                            <?php endif;?>
                            </td>
                        @if($loggedInUser->sweepstakes)
                        <td>
                            <?php if($user['win_amount'] > 0):?>
                                <a class="msg-popup-modal" href="" data-toggle="modal"  data-id="{{ $user['id'] }}" data-post="data-php" data-controller="customer" data-action="win_balance" title="Pay winning amount">{{ $user['win_amount'] }}</a>
                                <?php else : ?>
                                {{ $user['win_amount'] }}
                                <?php endif; ?>
                        </td>
                        @endif
                        <td>{{ $user['created_at'] }}</td>
                        <td><span class="hide">{{ $user['active'] }}</span>
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default btn-sm @if($user['active'] == 1) {{ 'active' }} @endif">
                                  <input class="list-update" name="active" data-controller="customer" data-token="{{ csrf_token() }}" data-field="active" data-listing-id="{{ $user['id'] }}" value="1" type="radio"> Yes
                                </label>
                                <label class="btn btn-default btn-sm  @if($user['active'] == 0) {{ 'active' }} @endif">
                                  <input class="list-update" name="active" data-controller="customer" data-token="{{ csrf_token() }}" data-field="active" data-listing-id="{{ $user['id'] }}" value="0" type="radio"> No
                                </label>
                            </div>
                        </td>
                        <td>
                            <a class="msg-popup-modal" href="" data-toggle="modal"  data-id="{{ $user['id'] }}" data-post="data-php" data-controller="customer" data-action="customer_update"><i class="fa fa-pencil" aria-hidden="true" title="Update Customer Info"></i></a> &nbsp;&nbsp;
                            <a class="msg-popup-modal" href="" data-toggle="modal"  data-id="{{ $user['id'] }}" data-post="data-php" data-controller="customer" data-action="update_balance"><i class="fa fa-plus" aria-hidden="true" title="Add Top up"></i></a> 

                            @if(!$loggedInUser->sweepstakes)
                            &nbsp;&nbsp;
                            <a class="msg-popup-modal" href="" data-toggle="modal"  data-id="{{ $user['id'] }}" data-post="data-php" data-controller="customer" data-action="reverse_balance"><i class="fa fa-reply" aria-hidden="true" title="Reverse Amount"></i></a>
                            @endif
                        </td>
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
