@extends('layouts.frontapp')

@section('content')
<div class="col-lg-12">
    <div class="row inner-page property-details-page">
        <div class="col-lg-12">
            <div class="row bg-gray">
                <div class="col-lg-12 content-section bg-white blog-detail">
                    <div class="detail-tabs">
                        <div class="box">
                            <div class="col-lg-12  content-section signin-dropdown login-page" >

                                <div class="col-lg-12 left">

                                    <div class="col-sm-5">
                                        <div class="row signin">
                                            <form role="form" method="POST" action="{{ url('/login') }}">
                                                {!! csrf_field() !!}
                                                <h2>sign in</h2>
                                                
                                                <input type="email" placeholder="Email Address" id="email" name="email" value="{{ old('email') }}" required="true" >
                                                @if ($errors->has('email'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('email') }}</strong>
                                                    </span>
                                                @endif
                                                <input type="password" placeholder="Password *" name="password" id="password" required="true">
                                                @if ($errors->has('password'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                @endif
                                                <br>
                                                <br>
                                                * must be 8 to 12 characters<br>
                                                * no special characters
                                                <br><br>
                                                <button type="submit" class="btn btn-defalut strock black full-width" id="cBtnSec">Sign in</button><br><br>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="or">
                                            <span>OR</span>
                                        </div>
                                    </div>

                                    <div class="col-sm-5">
                                        <div class="row register">

                                            <h2>create an account</h2>

                                            <div class="validationmssg" id="signinmsg" style="display:none;" >
                                                <div class="mssgtext">Please complete or correct all fields outlined in red.</div>
                                            </div>
                                            <form name="signup" id="signup" class="cmxform form-inline register" action="" method="post">
                                                <input type="text" placeholder="Last Name" id="lastname" name="lastname" tabindex="2" >
                                                <input type="text" placeholder="First Name" id="firstname" name="firstname" tabindex="1" >
                                                <input type="text" placeholder="Email Address" id="reg_email_address" name="reg_email_address" tabindex="3" style="float: left; width: 49%"   >
                                                <select id="reg_type" name="reg_type" style="height: 50px;margin: 5px 0 5px 5px;text-indent: 5px;color: #a7a4a4;width: 49%;" class="valid">
                                                    <option value="">You Are ..*</option>
                                                    <option value="4">Buyer</option>
                                                    <option value="2">Agent</option>
                                                    <option value="3">Agency</option></select>
                                                <input type="text" placeholder="Agency Name" class="hide" id="reg_agency_name" name="reg_agency_name" tabindex="3" style="float: left; width: 100%"   >
                                                <input type="password" id="reg_password" name="reg_password"  placeholder="Password *" tabindex="4"  >
                                                <input type="password" id="cpassword" name="cpassword" placeholder="Confirm Password *" tabindex="5" >
                                                <span class="clearfix"></span>
                                                <br>
                                                * must be 8 to 12 characters<br>
                                                * no special characters
                                                <br><br>
                                                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                                <button type="submit" class="btn btn-defalut strock black full-width">Register</button><br><br>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection