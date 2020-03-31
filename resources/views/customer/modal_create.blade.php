      <div class="modal-header">
        <button type="button" class="close closethismodalpopup" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Customer</h4>
      </div>
        <div id="modal-response-msg" style="color: green; font-size: 16px; margin: 0 15px;"></div>
        {!! Form::open(array('url' => 'customer/create', 'id'=>'customer-create-modal-form')) !!}
      <div class="modal-body">            
          <div class="form-group">
            <label>Name*</label>
            {{ Form::text('name','', array('class'=>'form-control required')) }}
          </div>
          <div class="form-group">
            <label>Email</label>
            {{ Form::text('email','', array('class'=>'form-control')) }}
          </div>
          <div class="form-group">
            <label>Mobile</label>
            {{ Form::text('mobile','', array('class'=>'form-control')) }}
          </div>
          <div class="form-group">
            <label>Amount*</label>
            {{ Form::text('balance','', array('class'=>'form-control required')) }}
            <i>You can add max <b>{{$loggedInUser->available_credit}}</b> amount.</i>
          </div>
          @php 
            $otp = random_int(1001, 9999); 
            $otp_valid_time = config('constants.otp_valid_time');  
          @endphp
          <div class="form-group" style="display: none;">
            <label>One time password</label>
            {{ Form::text('otp', $otp, array('class'=>'form-control')) }}
            <i>This can be used only once and will expire in {{ $otp_valid_time/60 }} mins</i>
          </div>
          @if($loggedInUser->sweepstakes)
          <div class="form-group">           
            {{ Form::checkbox('bounce_back','1',null, array('class'=>'form-checkbox')) }} &nbsp;&nbsp;Bounce Back?
          </div>
          @endif
        </div>
        <div class="modal-footer">
            <input type="hidden" name="id" value="" />
          <!--<button type="button" class="btn btn-danger">Delete Message</button>-->
          <button type="button" class="btn btn-primary pop-up-modal-form-submit">Save Customer</button>
        </div>
    {!! Form::close() !!}   