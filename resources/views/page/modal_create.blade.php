
  <!-- admin create message modal -->
      <div class="modal-header">
        <button type="button" class="close closethismodalpopup" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Create Page</h4>
      </div>
        <div id="modal-response-msg" style="color: green; font-size: 16px; margin: 0 15px;"></div>
        {!! Form::open(array('url' => 'page/create', 'id'=>'page-create-modal-form')) !!}
      <div class="modal-body"> 
          <div class="form-group">
            <label>Name*</label>
            {{ Form::text('name','', array('class'=>'form-control')) }}
          </div>   
          <div class="form-group">
            <label>Content*</label>
            {{ Form::textarea('content', null, ['class' => 'form-control', 'id'=>'editor', 'size'=>'80x10']) }}
          </div>           
        </div>
        <div class="modal-footer">
            <input type="hidden" name="id" value="" />
          <button type="button" class="btn btn-primary pop-up-modal-form-submit">Create Page</button>
        </div>
    {!! Form::close() !!}