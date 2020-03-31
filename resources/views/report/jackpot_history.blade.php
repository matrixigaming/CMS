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
            Report
          </h1>
          <ol class="breadcrumb">
            <li><a href="{{ url('/dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Jackpot History</li>
          </ol>
        </section>
@if(Session::has('message')) <div class="alert alert-info"> {{Session::get('message')}} </div> @endif
        <!-- Main content -->
        <section class="content"><?php //echo "<pre>"; print_r(request()->all()); echo "</pre>"; ?>
{!! Form::open(array('url' => '/reports/jackpot_history', 'id'=>'qwerty', 'class'=>'form-inline')) !!}
                
<div class="row" style="margin-bottom: 1rem">
                    @if($isDistributor || $isAdmin)
                   
                        <select name="distributor_id" class="distributor-list  form-control" style="margin-left: 2rem;">
                            <option value="">Select distributor</option>
                            @foreach($data['distributorList'] as $distributor)
                            @php
                            $selected = isset(request()->all()['distributor_id']) && request()->all()['distributor_id'] == $distributor->id ?'selected'  : '';
                            @endphp
                            <option value="{{ $distributor->id}}" {{ $selected }}>{{ $distributor->first_name .' '.$distributor->last_name }}</option>
                            @endforeach
                        </select>
                    
                        <select name="shop_id" class="shop-list  form-control" style="margin-left: 2rem;">
                            <option value="">Select Shop</option>
                            @foreach($data['shopLists'] as $shop)
                            @php
                            $selected = isset(request()->all()['shop_id']) && request()->all()['shop_id'] == $shop->id ?'selected'  : '';
                            @endphp
                            <option value="{{ $shop->id}}" {{ $selected }}>{{ $shop->shop_name }}</option>
                            @endforeach
                        </select>
                  
                    <label style="margin-left: 2rem;">From: </label> 
                        <input type="text" value="{{ isset(request()->all()['start'])? request()->all()['start'] : ''}}" name="start" id="start" class="form-control datetimepicker1" />
                     <label style="margin-left: 2rem;">To: </label>
                         <input type="text" value="{{ isset(request()->all()['end'])? request()->all()['end'] : ''}}" name="end" id="end" class="form-control daterangepicker_end_input datetimepicker1" style="float: none;" />
                    <button type="submit" class="btn btn-primary" style="margin-left: 2rem;">Go</button>
                    @endif
                    @if($isShop)  
                      <label style="margin-left: 2rem;">From: </label> 
                        <input type="text" value="{{ isset(request()->all()['start'])? request()->all()['start'] : ''}}" name="start" id="start" class="form-control datetimepicker1" />
                     <label style="margin-left: 2rem;">To: </label>
                         <input type="text" value="{{ isset(request()->all()['end'])? request()->all()['end'] : ''}}" name="end" id="end" class="form-control daterangepicker_end_input datetimepicker1" style="float: none;" />
                    <button type="submit" class="btn btn-primary" style="margin-left: 2rem;">Go</button>
                    @endif
                </div>
                {!! Form::close() !!} 
                <?php
//echo "<pre>"; print_r($data);echo "</pre>"; die;
?>
                <style>
.Gold{background-color: #ffc746;padding: 5px 7px;border-radius: 4px;}
.Diamond{background-color: #B9F2FF;padding: 5px 7px;border-radius: 4px;}
.Silver{background-color: #aaa9ad;padding: 5px 7px;border-radius: 4px;}
.Bronze{background-color: #b08d57;padding: 5px 7px;border-radius: 4px;}
                </style>
     
          <div class="row">
          	
            <div class="col-md-12">
                
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#manageUsers" data-toggle="tab">Jackpot History</a></li>
                </ul>
                <div class="tab-content">
                <!-- manage users -->
                <div class="active tab-pane" id="manageUsers">
                <div class="box-body">
                  <table class="table table-bordered table-striped fullDetailTable">
                    <thead>
                      <tr>
                        <th>Customer Name</th>
                        <th>Customer Code</th>
                        <th>Game</th>
                        <th>Win Amount</th>
                        <th>Jackpot Type</th>
                        <th>Win Time</th>
                      </tr>
                    </thead>
                    <tbody>                       
                        @foreach($data['playerJackpotHistoryDataArr'] as $val) 
                       
                      <tr>
                        <td>{{ ucwords($val->name) }}</td>
                        <td>{{ $val->code }}</td>
                        <td>{{ $val->game_name }}</td>
                        <td>{{ $val->winAmount }}</td>
                        <td><?php
                            switch($val->winLevel){
                                case 1:
                                    $winType = 'Diamond';
                                    break;
                                case 2:
                                    $winType = 'Gold';
                                    break;
                                case 3:
                                    $winType = 'Silver';
                                    break;
                                case 4:
                                    $winType = 'Bronze';
                                    break;
                            }
                            ?>
                            <span class="{{ $winType }}">{{ $winType }}</span></td>
                        <td><?php
                        $date = \Carbon\Carbon::parse($val->created_at, 'UTC');
        $date->setTimezone('EST');
        echo $date->toDayDateTimeString();
                        ?></td>
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
