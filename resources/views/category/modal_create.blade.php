<!-- admin create message modal -->
      <div class="modal-header">
        <button type="button" class="close closethismodalpopup" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Create Category</h4>
      </div>
        <div id="modal-response-msg" style="color: green; font-size: 16px; margin: 0 15px;"></div>
        {!! Form::open(array('url' => 'category/create', 'id'=>'category-create-modal-form')) !!}
      <div class="modal-body"> 
          <div class="form-group">
            <label>Name*</label>
            {{ Form::text('name','', array('class'=>'form-control')) }}
          </div>          
        </div>
        <div class="modal-footer">
            <input type="hidden" name="id" value="" />
          <!--<button type="button" class="btn btn-danger">Delete Message</button>-->
          <button type="button" class="btn btn-primary pop-up-modal-form-submit">Create Category</button>
        </div>
    {!! Form::close() !!}   
    