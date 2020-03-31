@extends('layouts.loggedinheader')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Edit User
            <small>Edit site users</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ url('/user/list') }}">Users</a></li>
            <li class="active">Edit User</li>
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
                <?php /*<div class="form-group">
                    <label>Zip Code</label>
                    {{ Form::text('zipcode',$data['user_data']->zipcode, array('class'=>'form-control event-trigger-class', 'placeholder'=>'Zip Code', 'id'=>'userzipcode')) }}
                </div>
                 <div class="form-group">
                  {{ Form::text('username','', array('class'=>'form-control', 'placeholder'=>'Username', 'id'=>'username')) }}
                  </div> */ ?>
                
                
                <div class="form-group">
                    <label>Access Level</label>
                    <select name="role_id" class="form-control">
                        <option value="0">Access Level</option>
                        <option value='1' selected>1 - ADMIN</option>
                        @php /*
                        @foreach ($data['roles'] as $role)
                        <?php $selected = $role->id == $data['user_role_id'] ? 'selected="selected"' : ''; ?>
                            <option value='{{ $role->id }}' <?php echo $selected; ?>>{{ $role->id }} - {{$role->name }}</option>
                        @endforeach */
                        @endphp                     
                    </select>
                </div>
            </div>
            
            <input type="hidden" name="id" value="{{ $data['user_data']->id}}" />
            <button type="submit" class="btn btn-iboom btn-block">Update</button>
        {!! Form::close() !!} 
    </section>


</div><!-- end wrapper -->
@endsection