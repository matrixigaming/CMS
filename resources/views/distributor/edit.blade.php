@extends('layouts.loggedinheader')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Edit Distributor
            <small>Edit site distributor</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ url('/distributor/list') }}">Distributors</a></li>
            <li class="active">Edit Distributor</li>
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
        {!! Form::open(array('url' => 'distributor/distributoraddPost', 'id'=>'user-add-form')) !!}
            <div class="col-md-9">
                <h5>Distributor Info</h5>
                <div class="form-group">
                    <label>First Name</label>
                    {{ Form::text('first_name',$data['user_data']->first_name, array('class'=>'form-control', 'placeholder'=>'First Name', 'id'=>'userfname')) }}
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    {{ Form::text('last_name',$data['user_data']->last_name, array('class'=>'form-control', 'placeholder'=>'Last Name', 'id'=>'userlname')) }}
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
                
                @if($isAdmin)
                @php
                    $config_rtp_variant = config('constants.distributor_rtp_variant');
                @endphp
                <div class="form-group">
                    <label>Select RTP Variant</label>
                    <select name="distributor_rtp_variant" class="form-control">
                        @foreach ($config_rtp_variant as $rtpV)
                        <?php $selected = ($rtpV == $data['user_data']->distributor_rtp_variant) ? 'selected="selected"' : '';?>
                            <option value='{{ $rtpV }}' <?php echo $selected; ?>>{{ $rtpV }}</option>
                        @endforeach 
                    </select>
                </div>
                @endif
            </div>
            <input type="hidden" name="role_id" value="2" />
            <input type="hidden" name="id" value="{{ $data['user_data']->id}}" />
            <button type="submit" class="btn btn-iboom btn-block">Update</button>
        {!! Form::close() !!} 
    </section>


</div><!-- end wrapper -->
@endsection