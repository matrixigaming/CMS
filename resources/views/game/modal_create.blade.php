<!-- admin create message modal -->
      <div class="modal-header">
        <button type="button" class="close closethismodalpopup" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Create Game</h4>
      </div>
        <div id="modal-response-msg" style="color: green; font-size: 16px; margin: 0 15px;"></div>
        {!! Form::open(array('url' => 'games/create', 'id'=>'game-create-modal-form', 'files'=>true)) !!}
      <div class="modal-body"> 
          <div class="form-group">
            <label>Name*</label>
            {{ Form::text('name','', array('class'=>'form-control required')) }}
          </div>  
          <div class="form-group">
            <label>Icon*</label>
            {{ Form::file('icon', array('class'=>'form-control required', 'id'=>'icon')) }} 
          </div> 
          <div class="form-group">
            <label>Lobby Icon*</label>
            {{ Form::file('lobby_icon', array('class'=>'form-control required', 'id'=>'lobby_icon')) }} 
          </div> 
          <div class="form-group">
            <label>URL*</label>
            {{ Form::text('url','', array('class'=>'form-control required')) }}
          </div>
          <div class="form-group">
            <label>Math URL*</label>
            {{ Form::text('math_url','', array('class'=>'form-control required')) }}
          </div>
          <div class="form-group">
            <label>Default RTP</label>
            
            <select name="default_rtp" class="form-control">
                    <option value="90" selected="selected">90</option>
                    <option value='91'>91</option>
                    <option value="92">92</option>
                    <option value="93">93</option>
                    <option value="94">94</option>
                    <option value="95">95</option>
                </select>
          </div>
        </div>
        
        <div class="modal-footer">
            <input type="hidden" name="id" value="" />
          <!--<button type="button" class="btn btn-danger">Delete Message</button>-->
          <button type="button" class="btn btn-primary pop-up-modal-form-submit-with-image">Create Game</button>
        </div>
    {!! Form::close() !!}   
    