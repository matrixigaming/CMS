<!-- admin create message modal -->
      <div class="modal-header">
        <button type="button" class="close closethismodalpopup" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Correct the transaction</h4>
      </div>
<div id="modal-response-msg" style="color: green; font-size: 16px; margin: 0 15px;"></div>
        {!! Form::open(array('url' => 'customer/adjust_transaction', 'id'=>'customer-adjust-transaction-modal-form')) !!}
        
      <div class="modal-body">
          <div class="form-group">
            <div class="form-group">
              <label>Last Recharged Amount</label>
              {{ Form::text('amount', $data->amount, array('class'=>'form-control', 'onkeypress'=>'return (event.charCode >= 48 && event.charCode < 58) || event.charCode == 46', 'min'=>'0')) }}
            </div>
              <div class="form-group">     
                  <?php $checked = $data->bounceback_amount > 0 ? 'checked': null;?>
                {{ Form::checkbox('bounce_back','1', $checked, array('class'=>'form-checkbox')) }} &nbsp;&nbsp;Bounce Back?
                <span style="float:right;">{{ Form::checkbox('reverse_amount','1', null, array('class'=>'form-checkbox')) }} &nbsp;&nbsp;Reverse whole amount?</span>
              </div>
          </div>          
        </div>
        <div class="modal-footer">
          <input type="hidden" name="id" value="{{ $data->id }}">
          <button type="button" class="btn btn-primary pop-up-modal-form-submit">Confirm</button>
        </div>
    {!! Form::close() !!} 