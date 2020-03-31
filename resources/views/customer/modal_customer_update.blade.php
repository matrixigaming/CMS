<!-- admin create message modal -->
      <div class="modal-header">
        <button type="button" class="close closethismodalpopup" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Update Customer Info</h4>
      </div>
<div id="modal-response-msg" style="color: green; font-size: 16px; margin: 0 15px;"></div>
        {!! Form::open(array('url' => 'customer/update_customer', 'id'=>'customer-create-modal-form')) !!}
        
      <div class="modal-body">   
          <div class="form-group">
            <label>Name</label>
            {{ Form::text('name',$data->name, array('class'=>'form-control')) }}
          </div>
           <div class="form-group">
            <label>Email</label>
            {{ Form::text('email',$data->email, array('class'=>'form-control')) }}
          </div>
          <div class="form-group">
            <label>Mobile</label>
            {{ Form::text('mobile',$data->mobile, array('class'=>'form-control')) }}
          </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" name="id" value="{{ $data->id }}" />
          <button type="button" class="btn btn-primary pop-up-modal-form-submit">Update Info</button>
        </div>
    {!! Form::close() !!} 