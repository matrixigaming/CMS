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
            <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Report - Game wise RTP</li>
          </ol>
        </section>
@if(Session::has('message')) <div class="alert alert-info"> {{Session::get('message')}} </div> @endif
        <!-- Main content -->
        <section class="content">
{!! Form::open(array('url' => '/reports/game_rtp', 'id'=>'qwerty')) !!}
                <div class="row">
                    @if($isDistributor || $isAdmin)
                    <div class="col-md-2">
                        <select name="distributor_id" class="distributor-list">
                            <option value="">Select distributor</option>
                            @foreach($data['distributorList'] as $distributor)
                            @php
                            $selected = isset(request()->all()['distributor_id']) && request()->all()['distributor_id'] == $distributor->id ?'selected'  : '';
                            @endphp
                            <option value="{{ $distributor->id}}" {{ $selected }}>{{ $distributor->first_name .' '.$distributor->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="shop_id" class="shop-list">
                            <option value="">Select Shop</option>
                            @foreach($data['shopLists'] as $shop)
                            @php
                            $selected = isset(request()->all()['shop_id']) && request()->all()['shop_id'] == $shop->id ?'selected'  : '';
                            @endphp
                            <option value="{{ $shop->id}}" {{ $selected }}>{{ $shop->shop_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3"><label style="float: left;">From: </label> 
                        <input type="text" value="{{ isset(request()->all()['start'])? request()->all()['start'] : ''}}" name="start" id="start" class="form-control1 daterangepicker_start_input datetimepicker1" /></div>
                     <div class="col-md-3"><label  style="float: left;">To: </label>
                         <input type="text" value="{{ isset(request()->all()['end'])? request()->all()['end'] : ''}}" name="end" id="end" class="form-control1 daterangepicker_end_input datetimepicker1" /></div>
                    <div class="col-md-2"><button type="submit" class="btn btn-primary margin-bottom">Go</button></div>
                    @endif
                    @if($isShop)
                    <div class="col-md-4"><label style="float: left;">From: </label> 
                        <input type="text" value="{{ isset(request()->all()['start'])? request()->all()['start'] : ''}}" name="start" id="start" class="form-control1 daterangepicker_start_input datetimepicker1" /></div>
                     <div class="col-md-4"><label  style="float: left;">To: </label>
                         <input type="text" value="{{ isset(request()->all()['end'])? request()->all()['end'] : ''}}" name="end" id="end" class="form-control1 daterangepicker_end_input datetimepicker1" /></div>
                    <div class="col-md-4"><button type="submit" class="btn btn-primary margin-bottom">Go</button></div>
                    @endif
                </div>
                {!! Form::close() !!} 
          <div class="row">
          	
            <div class="col-md-12">
                
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#manageUsers" data-toggle="tab">Game wise RTP</a></li>
                </ul>
                <div class="tab-content">
                <!-- manage users -->
                <div class="active tab-pane" id="manageUsers">
                <div class="box-body">
                  <table class="table table-bordered table-striped fullDetailTable">
                    <thead>
                      <tr>
                        <th>Icon</th>
                        <th>Game Name</th>
                        <th>Bet Amount</th>
                        <th>Win Amount</th>
                        <th>RTP</th>
                      </tr>
                    </thead>
                    <tbody>
                        @if(!empty($data['betWinDataArr']))  
                            
                            @php $total_game_bet_amount = 0; $total_game_win_amount = 0;@endphp
                            @foreach($data['betWinDataArr'] as $val) 
                                @php 
                                    
                                    $game_bet_amount = isset($val['game_bet_amount'])?$val['game_bet_amount'] : 0;
                                    $game_win_amount= isset($val['game_win_amount'])?$val['game_win_amount'] : 0; 
                                    $total_game_bet_amount += $game_bet_amount;
                                    $total_game_win_amount += $game_win_amount;
                                @endphp
                          <tr>
                              <td>
                            @if($val['icon'])
                            @php
                            $moduleConfig = config('constants.game');
                                $imagePath = $val['icon'];
                                $path_parts = pathinfo($imagePath);
                                $filename = $moduleConfig['game_icon_path'] . '/' . $path_parts['filename'] . '_xs' . '.' . $path_parts['extension'];
                                $filename = file_exists($filename) ? $filename : $moduleConfig['game_icon_path'].'/'.$path_parts['filename'].'.'.$path_parts['extension'];
                            @endphp
                            <img class="img-responsive" src="{{ url($filename) }}" alt="game icon - {{ $val['name'] }}">
                        @else
                            <img class="img-responsive" src="{{ url('uploads/no-image_th.jpg') }}" alt="game icon - {{ $val['name'] }}"  >
                        @endif</td>
                            <td>{{ $val['name'] }}</td>
                            <td>{{ $game_bet_amount }}</td>
                            <td>{{ $game_win_amount }}</td>
                            <td>{{ $game_bet_amount > 0 ? number_format((($game_win_amount)*100)/$game_bet_amount, 2) : 0 }}%</td>                        
                          </tr>
                          @endforeach 
                        <tfoot>
                            <td></td>
                            <th>Total</th>
                            <th>{{number_format($total_game_bet_amount,2)}}</th>
                            <th>{{number_format($total_game_win_amount,2)}}</th>
                            <th>{{ $total_game_bet_amount>0?number_format((($total_game_win_amount)*100)/$total_game_bet_amount, 2):0 }}%</th>
                        </tfoot>
                      @else
                        <tr>
                            <td colspan="5"> No data.</td>                        
                        </tr>
                      @endif
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
