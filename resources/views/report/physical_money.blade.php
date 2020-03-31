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
            <li class="active">Report - Physical Money</li>
          </ol>
        </section>
@if(Session::has('message')) <div class="alert alert-info"> {{Session::get('message')}} </div> @endif
        <!-- Main content -->
        <section class="content"><?php //echo "<pre>"; print_r(request()->all()); echo "</pre>"; ?>
{!! Form::open(array('url' => '/reports/physical_money', 'id'=>'qwerty')) !!}
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
                  <li class="active"><a href="#manageUsers" data-toggle="tab">Physical Money</a></li>
                </ul>
                <div class="tab-content">
                <!-- manage users -->
                <div class="active tab-pane" id="manageUsers">
                <div class="box-body">
                  <table class="table table-bordered table-striped fullDetailTable">
                    <thead>
                      <tr>
                        <th>Customer Name</th>
                        <th>Bet Amount</th>
                        <th>Redeem Amount</th>
                        <th>Diff</th>
                      </tr>
                    </thead>
                    <tbody>
                        @php $totalBetAmount = 0;
                        $totalRedeemAmount = 0;
                        @endphp
                        @foreach($data['betRedeemDataArr'] as $val) 
                        @php $betAmount = isset($val['bet_amount'])?$val['bet_amount'] : 0;
                        $redeemAmount = isset($val['redeem_amount'])?$val['redeem_amount'] : 0;
                        $totalBetAmount +=$betAmount; $totalRedeemAmount +=$redeemAmount;
                        @endphp
                      <tr>
                        <td>{{ $val['name'] }}</td>
                        <td>{{ $betAmount }}</td>
                        <td>{{ $redeemAmount }}</td>
                        <td>{{ $betAmount - $redeemAmount }}</td>                        
                      </tr>
                      @endforeach 
                      
                    </tbody>
                    @if(!empty($data['betRedeemDataArr']))
                    <tfoot>
                        <td></td>
                        <td></td>
                        <th>Total {{ ($totalBetAmount - $totalRedeemAmount) > 0 ?'Profits' : 'Losses' }}</th>
                        <th>{{ $totalBetAmount>0?number_format((($totalBetAmount - $totalRedeemAmount)*100)/$totalBetAmount, 2):0 }}%</th>
                    </tfoot>
                    <tfoot>
                        <td>Total</td>
                        <td>{{ $totalBetAmount }}</td>
                        <td>{{ $totalRedeemAmount }}</td>
                        <td>{{ $totalBetAmount - $totalRedeemAmount }}</td>
                    </tfoot>
                    @endif
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
