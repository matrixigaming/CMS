<!-- admin create message modal -->
      <div class="modal-header">
        <button type="button" class="close closethismodalpopup" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Top up</h4>
      </div>
<div id="modal-response-msg" style="color: green; font-size: 16px; margin: 0 15px;"></div>
<div style="color: #000; font-size: 16px; margin: 0 15px;">You can add max <b>{{$loggedInUser->available_credit}}</b> amount.</div>
        {!! Form::open(array('url' => 'customer/create', 'id'=>'customer-create-modal-form')) !!}
        {{ Form::hidden('name',$data->name, array('class'=>'form-control')) }}
      <div class="modal-body">   
          <div class="form-group">
            <label>Amount</label>
            {{ Form::text('balance','', array('class'=>'form-control')) }}
          </div>
          @if($loggedInUser->sweepstakes)
            @if($isBouncebackAvailable)
              <div class="form-group">           
                {{ Form::checkbox('bounce_back','1', null, array('class'=>'form-checkbox')) }} &nbsp;&nbsp;Bounce Back?
              </div>
            @else
              <div class="form-group">           
                Bounceback to be given only once in one day.
              </div>
            @endif          
          @endif 
        </div>
        <div class="modal-footer">
            <input type="hidden" name="id" value="{{ $data->id }}" />
          <button type="button" class="btn btn-primary pop-up-modal-form-submit">Update Topup</button>
        </div>
    {!! Form::close() !!} 