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
            Admin - Manage Game
          </h1>
          <ol class="breadcrumb">
            <li><a href="{{ url('/user/list') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Admin - Manage Games</li>
          </ol>
        </section>
@if(Session::has('message')) <div class="alert alert-info"> {{Session::get('message')}} </div> @endif
        <!-- Main content -->
        <section class="content">

          <div class="row">
          	<div class="col-md-3">                    
          		<a  href="" class="btn btn-primary btn-block margin-bottom msg-popup-modal" data-toggle="modal" data-controller="games"  data-id="createmsg" data-post="data-php" data-action="create_game">Create Game</a>
                        <!--<a  href="" class="btn btn-primary btn-block margin-bottom msg-popup-modal" data-toggle="modal" data-controller="games"  data-id="createmsg" data-post="data-php" data-action="customize_rtp">Manage RTP</a>-->
          	</div>
            <div class="col-md-9">
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#manageGame" data-toggle="tab">Manage Games</a></li>
                </ul>
                <div class="tab-content">
                <!-- manage users -->
                <div class="active tab-pane" id="manageGame">
                <div class="box-body">
                  <table class="table table-bordered table-striped fullDetailTable">
                    <thead>
                      <tr>
                        <th>Icon</th>
                        <th>Name</th>
                        <th>Url</th>
                        <th>Edit</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $dpt)                            
                      <tr>
                        <td>
                            @if($dpt->icon)
                            @php
                            $moduleConfig = config('constants.game');
                                $imagePath = $dpt->icon;
                                $path_parts = pathinfo($imagePath);
                                $filename = $moduleConfig['game_icon_path'] . '/' . $path_parts['filename'] . '_xs' . '.' . $path_parts['extension'];
                                $filename = file_exists($filename) ? $filename : $moduleConfig['game_icon_path'].'/'.$path_parts['filename'].'.'.$path_parts['extension'];
                            @endphp
                            <img class="img-responsive" src="{{ url($filename) }}" alt="game icon - {{ $dpt->name }}">
                        @else
                            <img class="img-responsive" src="{{ url('uploads/no-image_th.jpg') }}"  >
                        @endif</td>
                        <td>{{ $dpt->name }}</td>
                        <td>{{ $dpt->url }}</td>
                        <td>
<a class="msg-popup-modal" href="" data-toggle="modal"  data-id="{{ $dpt->id }}" data-post="data-php" data-controller="games" data-action="update_game"><i class="fa fa-pencil"></i></a>
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
