@extends('layouts.loggedinheader')
@section('content')
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Requests
            <small>view site requests</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Requests</li>
          </ol>
        </section>
        <!-- show requests -->
        <section class="content">
          <div class="row">
            <div class="col-lg-8">
              <div class="box box-danger">
                <div class="box-header">
                  <h3 class="box-title">Select Request To Manage</h3>
                </div>
                <div class="box-body">
                  <table class="table table-bordered table-striped fullDetailTable">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Date</th>
                        <th>View</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($data as $contact)   
                      <tr>
                        <td>{{ $contact->name }}</td>
                        <td>{{ $contact->email }}</td>
                        <td>{{ $contact->phone }}</td>
                        <td>{{ $contact->created_at }}</td>
                        <td>
<a class="msg-popup-modal" href="" data-toggle="modal"  data-id="{{ $contact->id }}" data-post="data-php" data-controller="contact" data-action="view_contact"><i class="fa fa-binoculars"></i></a>
                            </td>
                      </tr> 
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div><!-- end wrapper -->

@endsection
