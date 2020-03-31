@extends('layouts.loggedinheader')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Create Shop
            <small>create site shop</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ url('/Shop/list') }}">Shop</a></li>
            <li class="active">Create Shop</li>
        </ol>
    </section>
    <?php //echo "<pre>"; print_r($data); echo "</pre>"; die;?>
    @if(!empty($errors->all()))
      <ul class="alert alert-danger">
        @foreach($errors->all() as $error)
          <li>{{$error}}</li>
        @endforeach
      </ul>
    @endif
    <!-- show requests -->
    <section class="content">
        {!! Form::open(array('url' => 'shop/shopaddPost', 'id'=>'shop-add-form')) !!}
            <div class="col-md-9">
                <h5>Shop Info</h5>
                <div class="form-group">
                    {{ Form::text('first_name','', array('class'=>'form-control', 'placeholder'=>'First Name', 'id'=>'userfname')) }}
                </div>
                <div class="form-group">
                    {{ Form::text('last_name','', array('class'=>'form-control', 'placeholder'=>'Last Name', 'id'=>'userlname')) }}
                </div>
                <div class="form-group">
                    {{ Form::text('shop_name','', array('class'=>'form-control', 'placeholder'=>'Shop full name', 'id'=>'shop_name')) }}
                </div>
                <div class="form-group">
                    {{ Form::text('email','', array('class'=>'form-control', 'placeholder'=>'Email', 'id'=>'useremail')) }}
                </div>
                <div class="form-group">
                    {{ Form::text('phone','', array('class'=>'form-control onlyNumeric', 'placeholder'=>'Phone', 'id'=>'userphone')) }}
                </div>
                <div class="form-group">
                    {{ Form::text('address','', array('class'=>'form-control', 'placeholder'=>'Address', 'id'=>'useraddress')) }}
                </div>
                <div class="form-group">
                    {{ Form::text('city','', array('class'=>'form-control', 'placeholder'=>'City', 'id'=>'usercity')) }}
                </div>
                <div class="form-group">
                    {{ Form::text('state','', array('class'=>'form-control', 'placeholder'=>'State', 'id'=>'userstate')) }}
                </div>
                <div class="form-group">
                    {{ Form::text('zipcode','', array('class'=>'form-control', 'placeholder'=>'Zip Code', 'id'=>'userzipcode')) }}
                </div>
                <?php /*
                 <div class="form-group">
                  {{ Form::text('username','', array('class'=>'form-control', 'placeholder'=>'Username', 'id'=>'username')) }}
                  </div> */ ?>
                <div class="form-group">
                    {{ Form::password('password', array('class'=>'form-control', 'placeholder'=>'Password', 'id'=>'userpass')) }}
                </div>
                <!--<div class="form-group">
                    <select name="role_id" class="form-control">
                        <option value='3'>3 - Shop</option>
                    </select>
                </div>-->
                <input type="hidden" name="role_id" value="3" />
                <?php if(!$data['isDistributor']): ?>
                <div class="form-group">
                    <select name="created_by" class="form-control" required="">
                        <option value="">Select Distributor</option>
                        <?php foreach ($data['distributors'] as $distributor): ?>
                            <option value='{{ $distributor->id }}'>{{$distributor->first_name .' '.$distributor->last_name }}</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                 <?php endif;?>
                <div class="form-group">
                     <div class="checkbox">
                         <label>
                             <input type="checkbox" name="jackpot" value="1"> Is Jackpot active?
                         </label>
                     </div>
                 </div>
                <div class="form-group">
                     <div class="checkbox">
                         <label>
                             <input type="checkbox" name="nudgeFeature" value="1"> Is Nudge Feature active?
                         </label>
                     </div>
                 </div>
                <div class="form-group">
                     <div class="checkbox">
                         <label>
                             <input type="checkbox" name="preRevealWithSkillStop" value="1"> Is Pre Reveal With Skill Stop active?
                         </label>
                     </div>
                 </div>
                
                <div class="form-group">
                     <div class="checkbox">
                         <label>
                             <input type="checkbox" name="sweepstakes" value="1" class="sweepstakesState"> Is Sweep Stakes On?
                         </label>
                     </div>
                 </div>
                <div class="form-group sweepstakesStateField" style="display: none;">
                    <label>Select State</label>
                    <select name="login_verbiage_id" class="form-control">          
                        <option value='1'>North Carolina</option>
                        <option value='2'>Texas</option>
                    </select>
                </div>
                 
            </div>
            
        <input type="hidden" name="id" value="" />
        <button type="submit" class="btn btn-iboom btn-block">Create &amp; Continue</button>
        {!! Form::close() !!} 
    </section>


</div><!-- end wrapper -->
@endsection