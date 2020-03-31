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
            Admin - Manage Roles & Permission
          </h1>
          <ol class="breadcrumb">
            <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Admin - Roles & Permission</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="row">
              <div class="col-md-8">
                   <div class="box box-info">
                        <!--<div class="box-header">
                          <h3 class="box-title">Select Request To Manage</h3>
                        </div>-->
                        <div class="box-body">
                  {!! Form::open(array('url' => 'role_permission/managePost', 'id'=>'manage-role-permission-form')) !!}
                  <table>
                  <?php 
                    //echo "<pre>"; print_r($data['roles']);
                  foreach($data['roles'] as $role){
                      echo '<tr><td><strong>'.$role->display_name.'</strong>';
                      echo '<table cellspaccing="2" cellpadding="5" style="margin-left: 25px;"><tr>';
                      $x = 1;
                      
                      foreach($data['permissions'] as $permission){ 
                          $checked = in_array($permission->id, $data['role_permissions'][$role->id]) ? 'checked': '';
                          echo '<td><input type="checkbox" name="permission['.$role->id.'][]" value="'.$permission->id.'" '.$checked.'>&nbsp;&nbsp;'.$permission->display_name.'&nbsp;&nbsp;</td>';
                          $x++;
                          if($x % 6 == 0)
                            echo '</tr><tr>';
                      }
                      echo '</tr></table>';
                      echo '</td></tr><tr><td>&nbsp;</td></tr>';
                  }
                  ?>
                      </table>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="save-role-permission">Save</button>
                  </div>
                  {!! Form::close() !!} 
                  </div>
                   </div>
                  
                </div><!-- /.col -->
          </div><!-- /.row -->
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

@endsection
