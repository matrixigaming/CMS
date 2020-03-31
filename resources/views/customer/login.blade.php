@extends('layouts.app')

@section('content')
<div class="jumbotron boom-bg-container" id="worksJumbo" style="height:635px">
        <div class="container" id="howItWorks">
        	<div class="page-header"><h1>Front End</h1></div>
        	<div class="row">                
	        	<div class="col-lg-4 col-lg-offset-4">
                    @if (session('status'))
                        <div class="alert alert-danger">
                            {{ session('status') }}
                        </div>
                    @endif
                    {!! Form::open(array('url' => '/player/loginPost', 'id'=>'customer-login-form')) !!}
                            
		        		<div class="form-group">
		        			<label>Customer Code:</label>
                            <input type="text" class="form-control" name="key_code" value="" required>
		        		</div>
                        <div class="form-group">
                            <label>One Time Password</label>
                            <input type="password" class="form-control" name="otp" value="" required>
                            <i>Get this from store owner.</i>
                        </div>
                            
		        		<div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg btn-block" id="cBtnSec">
                            <i class="fa fa-btn fa-sign-in"></i>Login
                            </button>
		              </div>
		        	{!! Form::close() !!} 
		        </div>
        	</div>
        </div>
    </div>

@endsection