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
            Manage BounceBack Settings
          </h1>
          <ol class="breadcrumb">
            <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Manage BB Settings</li>
          </ol>
        </section>
@if(Session::has('message')) <div class="alert alert-info"> {{Session::get('message')}} </div> @endif
        <!-- Main content -->
        <section class="content">

          <div class="row">
            <div class="col-md-12">
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#manageUsers" data-toggle="tab">Manage Settings</a></li>
                </ul>
                <div class="tab-content">
                <!-- manage users -->
                <div class="active tab-pane" id="manageUsers">
                <div class="box-body">
                  <table class="table table-bordered table-striped fullDetailTable">
                    <thead>
                      <tr>
                        <th>Category Name</th>
                        <th>Min Amount</th>
                        <th>Max Amount</th>
                        <th>BounceBack Amount</th>
                        <th>Edit</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $setting)                            
                      <tr>
                        <td>{{ $setting->bb_category }}</td>
                        <td>{{ $setting->min_recharge }}</td>
                        <td>{{ $setting->max_recharge }}</td>
                        <td>{{ $setting->bb_amount }}</td>                        
                        <td>
                            <a class="msg-popup-modal" href="" data-toggle="modal"  data-id="{{ $setting->id }}" data-post="data-php" data-controller="bounceback" data-action="update_setting"><i class="fa fa-pencil" aria-hidden="true" title="Update Setting"></i></a>&nbsp;&nbsp;
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
