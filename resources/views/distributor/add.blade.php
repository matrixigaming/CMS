@extends('layouts.loggedinheader')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Create Distributor
            <small>create site distributors</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ url('/distributor/list') }}">Distributors</a></li>
            <li class="active">Create Distributor</li>
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
        {!! Form::open(array('url' => 'distributor/distributoraddPost', 'id'=>'distributor-add-form')) !!}
            <div class="col-md-9">
                <h5>Distributor Info</h5>
                <div class="form-group">
                    {{ Form::text('first_name','', array('class'=>'form-control', 'placeholder'=>'First Name', 'id'=>'userfname')) }}
                </div>
                <div class="form-group">
                    {{ Form::text('last_name','', array('class'=>'form-control', 'placeholder'=>'Last Name', 'id'=>'userlname')) }}
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
                @if($isAdmin)
                @php
                    $config_rtp_variant = config('constants.distributor_rtp_variant');
                @endphp
                <div class="form-group">
                    <label>Select RTP Variant</label>
                    <select name="distributor_rtp_variant" class="form-control">
                        @foreach ($config_rtp_variant as $rtpV)
                            <option value='{{ $rtpV }}'>{{ $rtpV }}</option>
                        @endforeach 
                    </select>
                </div>
                @endif
            </div>
            <input type="hidden" name="role_id" value="2" />
        <input type="hidden" name="id" value="" />
        <button type="submit" class="btn btn-iboom btn-block">Create &amp; Continue</button>
        {!! Form::close() !!} 
    </section>


</div><!-- end wrapper -->
@endsection