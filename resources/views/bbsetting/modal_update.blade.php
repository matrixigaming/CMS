<!-- admin create message modal -->
      <div class="modal-header">
        <button type="button" class="close closethismodalpopup" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Update Bounceback Setting</h4>
      </div>
<div id="modal-response-msg" style="color: green; font-size: 16px; margin: 0 15px;"></div>
        {!! Form::open(array('url' => 'bounceback/updatesettings', 'id'=>'bounceback-update-modal-form')) !!}
      <div class="modal-body">
          <div class="form-group">
            <label>Category Name</label>
            {{ Form::text('bb_category',$data->bb_category, array('class'=>'form-control','retquired'=>true)) }}
          </div>
          <div class="form-group">
            <label>Minimum Recharge Amount</label>
            {{ Form::text('min_recharge',$data->min_recharge, array('class'=>'form-control')) }}
          </div>
          <div class="form-group">
            <label>Maximum Recharge Amount</label>
            {{ Form::text('max_recharge',$data->max_recharge, array('class'=>'form-control')) }}
          </div>
          <div class="form-group">
            <label>BounceBack Amount</label>
            {{ Form::text('bb_amount',$data->bb_amount, array('class'=>'form-control')) }}
          </div>
          
        </div>
        <div class="modal-footer">
            <input type="hidden" name="id" value="{{ $data->id }}" />
          <button type="button" class="btn btn-primary pop-up-modal-form-submit">Update Setting</button>
        </div>
    {!! Form::close() !!} 