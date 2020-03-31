@extends('layouts.loggedinheader')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Edit Shop
            <small>Edit site Shop</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ url('/shop/list') }}">Shops</a></li>
            <li class="active">Edit Shop</li>
        </ol>
    </section>
    <?php //echo "<pre>"; print_r($errors->all()); echo "</pre>";?>
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
                    <label>First Name</label>
                    {{ Form::text('first_name',$data['user_data']->first_name, array('class'=>'form-control', 'placeholder'=>'First Name', 'id'=>'userfname')) }}
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    {{ Form::text('last_name',$data['user_data']->last_name, array('class'=>'form-control', 'placeholder'=>'Last Name', 'id'=>'userlname')) }}
                </div>
                <div class="form-group">
                    <label>Shop Full Name</label>
                    {{ Form::text('shop_name',$data['user_data']->shop_name, array('class'=>'form-control', 'placeholder'=>'Shop full name', 'id'=>'shop_name')) }}
                </div>
                <div class="form-group">
                    <label>Email</label>
                    {{ Form::text('email',$data['user_data']->email, array('class'=>'form-control', 'placeholder'=>'Email', 'id'=>'useremail')) }}
                </div>
                <div class="form-group">
                    <label>Password</label>
                    {{ Form::text('password','', array('class'=>'form-control', 'placeholder'=>'Password', 'id'=>'userpassword')) }}
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    {{ Form::text('phone',$data['user_data']->phone, array('class'=>'form-control onlyNumeric', 'placeholder'=>'Phone', 'id'=>'userphone')) }}
                </div>
                <div class="form-group">
                    <label>Address</label>
                    {{ Form::text('address',$data['user_data']->address, array('class'=>'form-control', 'placeholder'=>'City', 'id'=>'usercity')) }}
                </div>
                <div class="form-group">
                    <label>City</label>
                    {{ Form::text('city',$data['user_data']->city, array('class'=>'form-control', 'placeholder'=>'City', 'id'=>'usercity')) }}
                </div>
                <div class="form-group">
                    <label>State</label>
                    {{ Form::text('state',$data['user_data']->state, array('class'=>'form-control', 'placeholder'=>'State', 'id'=>'userstate')) }}
                </div>
                <div class="form-group">
                    <label>Zip Code</label>
                    {{ Form::text('zipcode',$data['user_data']->zipcode, array('class'=>'form-control', 'placeholder'=>'Zip Code', 'id'=>'userzipcode')) }}
                </div>
                
                <!--<div class="form-group">
                    <label>Access Level</label>
                    <select name="role_id" class="form-control">
                        <option value='3' selected>3 - Shop</option>                                       
                    </select>
                </div>-->
                <input type="hidden" name="role_id" value="3" />
                <?php if(!$data['isDistributor']): ?>
                <div class="form-group">
                    <label>Distributor</label>
                    <select name="created_by" class="form-control" required="">
                        <option value="">Select Distributor</option>
                        <?php foreach ($data['distributors'] as $distributor): 
                            $selected = $distributor->id == $data['user_data']->created_by ? 'selected' : '';
                            ?>
                            <option value='{{ $distributor->id }}' {{ $selected }}>{{$distributor->first_name .' '.$distributor->last_name }}</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                 <?php endif;?>
                <div class="form-group">
                     <div class="checkbox">
                         <label>
                             <input type="checkbox" <?php echo $data['user_data']->jackpot ? 'checked' : ''?> name="jackpot" value="1"> Is Jackpot active?
                         </label>
                     </div>
                 </div>
                <div class="form-group">
                     <div class="checkbox">
                         <label>
                             <input type="checkbox" <?php echo $data['user_data']->nudgeFeature ? 'checked' : ''?> name="nudgeFeature" value="1"> Is Nudge Feature active?
                         </label>
                     </div>
                 </div>
                <div class="form-group">
                     <div class="checkbox">
                         <label>
                             <input type="checkbox" <?php echo $data['user_data']->preRevealWithSkillStop ? 'checked' : ''?> name="preRevealWithSkillStop" value="1"> Is Pre Reveal With Skill Stop active?
                         </label>
                     </div>
                 </div>
                 <div class="form-group">
                     <div class="checkbox">
                         <label>
                             <input type="checkbox" <?php echo $data['user_data']->sweepstakes ? 'checked' : ''?> name="sweepstakes" value="1" class="sweepstakesState"> Is Sweep Stakes On?
                         </label>
                     </div>
                 </div>
                
                <div class="form-group sweepstakesStateField" style="display: <?php echo $data['user_data']->sweepstakes? 'block' : 'none'?>">
                    <label>Select State</label>
                    <select name="login_verbiage_id" class="form-control">          
                        <option value='1' <?php echo $data['user_data']->login_verbiage_id==1 ? 'selected="selected"' : ''; ?>>North Carolina</option>
                        <option value='2' <?php echo $data['user_data']->login_verbiage_id==2 ? 'selected="selected"' : ''; ?>>Texas</option>
                    </select>
                </div>
                
            </div>
            
            <input type="hidden" name="id" value="{{ $data['user_data']->id}}" />
            <button type="submit" class="btn btn-iboom btn-block">Update</button>
        {!! Form::close() !!} 
    </section>


</div><!-- end wrapper -->
@endsection