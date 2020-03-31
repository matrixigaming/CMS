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
            Admin - Manage States
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Admin - Manage States</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-3">                    
                <a  href="" class="btn btn-primary btn-block margin-bottom msg-popup-modal" data-toggle="modal" data-controller="states"  data-id="createmsg" data-post="data-php" data-action="create_state">Create State</a>
            </div>
            <div class="col-md-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#manageState" data-toggle="tab">Manage States</a></li>
                    </ul>
                    <div class="tab-content">
                        <!-- manage users -->
                        <div class="active tab-pane" id="manageState">
                            <div class="box-body">
                                <table class="table table-bordered table-striped fullDetailTable">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Country</th>
                                            <th>Edit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data as $dpt)                            
                                        <tr>
                                            <td>{{ $dpt->state_name }}</td>
                                            <td>{{ $dpt->country->name }}</td>
                                            <td>
                                                <a class="msg-popup-modal" href="" data-toggle="modal"  data-id="{{ $dpt->id }}" data-post="data-php" data-controller="states" data-action="update_state"><i class="fa fa-pencil"></i></a>
                                                <!--<a href="#" data-toggle="modal" data-target=".adminMessageModal"><i class="fa fa-pencil"></i></a>--></td>
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
