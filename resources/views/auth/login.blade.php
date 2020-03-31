@extends('layouts.app')

@section('content')
<!-- how it works -->
	<div class="jumbotron boom-bg-container" id="worksJumbo" style="height:500px;">
        <div class="container" id="howItWorks">
        	<div class="page-header"><h1>Login To Access</h1></div>
                <div class="row">
                    <div class="col-lg-4 col-lg-offset-4">
                        @if(Session::has('message')) <div class="alert alert-danger"> {{Session::get('message')}} </div> @endif
                    </div>
                </div>
        	<div class="row">                    
	        	<div class="col-lg-4 col-lg-offset-4">
                            <form role="form" method="POST" action="{{ url('/login') }}">
                            {!! csrf_field() !!}
		        		<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
		        			<label>Email ID:</label>
                                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                                @if ($errors->has('email'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
		        		</div>
		        		<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
		        			<label>Password:</label>
                                                <input type="password" class="form-control" name="password" required>
                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
		        		</div>
                            
		        		<div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block" id="cBtnSec">
                                                <i class="fa fa-btn fa-sign-in"></i>Login
                                            </button>
		        		</div>
		        	</form>
		        </div>
        	</div>
        </div>
    </div>

@endsection
