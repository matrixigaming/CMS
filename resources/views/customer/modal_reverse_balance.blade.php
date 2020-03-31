<!-- admin create message modal -->
      <div class="modal-header">
        <button type="button" class="close closethismodalpopup" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Balance Adjust</h4>
      </div>
<div id="modal-response-msg" style="color: green; font-size: 16px; margin: 0 15px;"></div>
        {!! Form::open(array('url' => 'customer/update_balance', 'id'=>'customer-create-modal-form')) !!}
        {{ Form::hidden('name',$data->name, array('class'=>'form-control')) }}
        {{ Form::hidden('transaction_type',$transaction_type, array('class'=>'form-control', 'id'=>'cust_transaction_type')) }}
        {{ Form::hidden('win_amount',$data->win_amount, array('class'=>'form-control', 'id'=>'cust_win_amount')) }}
        {{ Form::hidden('balance',$data->balance, array('class'=>'form-control', 'id'=>'cust_balance')) }}
      <div class="modal-body">   
          
          <div class="form-group">            
            <?php if($transaction_type == 'Win'): ?>
              <div class="form-group">            
                <label>Amount to be Paid:</label>
                {{ Form::text('reverse_amount',$data->win_amount, array('class'=>'form-control', 'id'=>'cust_reverse_amount')) }}
                
              </div>
              <div class="form-group">
                <label>Pay cash</label>
                {{ Form::radio('pay_method', 'pay-cash', true)}}
                @if(!$loggedInUser->sweepstakes)
                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                <label>Add to credit balance</label>
                {{ Form::radio('pay_method', 'add-to-credit-balance')}} 
                @endif               
                
              </div>
            <?php elseif($transaction_type == 'Reverse') :?>
              <div class="form-group">            
                <label>Amount to be Reversed:</label>
                {{ Form::text('reverse_amount','', array('class'=>'form-control', 'id'=>'cust_reverse_amount')) }}
              </div>
            <?php endif; ?>
          </div>
          
          
        </div>
        <div class="modal-footer">
            <input type="hidden" name="id" value="{{ $data->id }}" />
          <button type="button" class="btn btn-primary pop-up-modal-form-balance-update">Pay</button>
        </div>
    {!! Form::close() !!} 