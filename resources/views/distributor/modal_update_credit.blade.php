<!-- admin create message modal -->
      <div class="modal-header">
        <button type="button" class="close closethismodalpopup" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Update Credit</h4>
      </div>
<div id="modal-response-msg" style="color: green; font-size: 16px; margin: 0 15px;"></div>
        {!! Form::open(array('url' => 'user/update_credit', 'id'=>'user-credit-modal-form')) !!}
      <div class="modal-body">  
          
          @if(!$isAdmin)
          <div class="form-group">
            <label>Your Credit Balance currently: {{$loggedInUser->available_credit}}</label>
          </div>
          @endif
          
          <div class="form-group">
            <label>Credit Balance for {{$data->first_name .' '.$data->last_name}}: </label>
            {{$data->available_credit}}
          </div>
          <div class="form-group">
            <label>Credit Amount</label>
            {{ Form::text('credit_amount', '', array('class'=>'form-control onlyNumeric')) }}
          </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" name="id" value="{{ $data->id}}" />
          <!--<button type="button" class="btn btn-danger">Delete Message</button>-->
          <button type="button" class="btn btn-primary pop-up-modal-form-submit">Update Credit</button>
        </div>
    {!! Form::close() !!}   
    