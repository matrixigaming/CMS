@extends('layouts.loggedinheader')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Create User
            <small>create site users</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ url('/user/list') }}">Users</a></li>
            <li class="active">Create User</li>
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
        {!! Form::open(array('url' => 'user/addPost', 'id'=>'user-add-form')) !!}
            <div class="col-md-9">
                <h5>User Info</h5>
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
                <div class="form-group">
                    <select name="role_id" class="form-control">
                        <option value="0">Access Level</option>
                        <option value='1'>1 - ADMIN</option>
                        @php /*
                        @foreach ($data['roles'] as $role)
                            <option value='{{ $role->id }}'>{{ $role->id }} - {{$role->name }}</option>
                        @endforeach */
                        @endphp
                    </select>
                </div>
                 
                 <!-- <div class="form-group">
                     <input type="text" class="form-control" id="userurl" placeholder="Company URL">
                 </div>
                <div class="form-group">
                     <div class="checkbox">
                         <label>
                             <input type="checkbox"> Create Company Only
                         </label>
                     </div>
                 </div>-->
            </div>
            
        <input type="hidden" name="id" value="" />
        <button type="submit" class="btn btn-iboom btn-block">Create &amp; Continue</button>
        {!! Form::close() !!} 
    </section>


</div><!-- end wrapper -->
@endsection