@extends('layouts.loggedinheader')
@section('content')

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Admin - Roles & Permission
          </h1>
          <ol class="breadcrumb">
            <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Admin - Roles & Permission</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

          <div class="row">
          	<div class="col-md-3">                    
          		<a  href="" class="btn btn-primary btn-block margin-bottom msg-popup-modal" data-toggle="modal"  data-id="createmsg" data-post="data-php" data-controller="role_permission" data-action="create_role">Create Role</a>
          		<a href="" class="btn btn-danger btn-block margin-bottom msg-popup-modal" data-toggle="modal" data-id="createmsg" data-post="data-php" data-controller="role_permission" data-action="create_permission">Create Permission</a>
                </div>
              <div class="col-md-9">
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#manageRoles" data-toggle="tab">Manage Roles</a></li>
                  <li><a href="#messagePermission" data-toggle="tab">Manage Permission</a></li>
                </ul>
                <div class="tab-content">
                <!-- manage messages -->
                <div class="active tab-pane" id="manageRoles">
                <div class="box-body">
                  <table class="table table-bordered table-striped fullDetailTable">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Edit</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($data['roles'] as $role)                            
                      <tr>
                        <td>{{ $role->display_name }}</td>
                        <td>{{ $role->description }}</td>
                        
                        <td>
<a class="msg-popup-modal" href="" data-toggle="modal"  data-id="{{ $role->id }}" data-post="data-php" data-controller="role_permission" data-action="update_role"><i class="fa fa-pencil"></i></a>
<!--<a href="#" data-toggle="modal" data-target=".adminMessageModal"><i class="fa fa-pencil"></i></a>--></td>
                      </tr>
                      @endforeach
                      
                    </tbody>
                  </table>
                </div>

                </div>
                <!-- message reports -->
                <div class="tab-pane" id="messagePermission">
                  <table class="table table-bordered table-striped fullDetailTable msg-report-ajax-response">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>System Name</th>
                        <th>Description</th>
                        <th>Edit</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($data['permission'] as $perm)                            
                      <tr>
                        <td>{{ $perm->display_name }}</td>
                        <td>{{ $perm->name }}</td>
                        <td>{{ $perm->description }}</td> 
                        <td><a class="msg-popup-modal" href="" data-toggle="modal"  data-id="{{ $perm->id }}" data-post="data-php" data-controller="role_permission"  data-action="update_permission"><i class="fa fa-pencil"></i></a></td>
                      </tr>
                      @endforeach    
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
