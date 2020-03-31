<!-- admin create message modal -->
      <div class="modal-header">
        <button type="button" class="close closethismodalpopup" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Generate OTP</h4>
      </div>
<div id="modal-response-msg" style="color: green; font-size: 16px; margin: 0 15px;"></div>
        {!! Form::open(array('url' => 'customer/generate_otp', 'id'=>'customerotp-create-modal-form')) !!}
        
      <div class="modal-body">
          <div class="form-group">            
            @php 
              $otp = random_int(1001, 9999); 
              $otp_valid_time = config('constants.otp_valid_time'); 
            @endphp
            <div class="form-group">
              <label>One time password</label>
              {{ Form::text('otp', $otp, array('class'=>'form-control')) }}
              <i>This can be used only once and will expire in {{ $otp_valid_time/60 }} mins</i>
            </div>
          </div>          
        </div>
        <div class="modal-footer">
          <input type="hidden" name="customer_id" value="{{ $data->id }}">
          <button type="button" class="btn btn-primary pop-up-modal-form-submit">Save OTP</button>
        </div>
    {!! Form::close() !!} 