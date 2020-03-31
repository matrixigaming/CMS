<!-- admin create message modal -->
      <div class="modal-header">
        <button type="button" class="close closethismodalpopup" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Create User</h4>
      </div>
        <div id="modal-response-msg" style="color: green; font-size: 16px; margin: 0 15px;"></div>
        {!! Form::open(array('url' => 'user/create', 'id'=>'user-create-modal-form')) !!}
      <div class="modal-body"> 
          <div class="form-group">
            <label>First Name*</label>
            {{ Form::text('first_name','', array('class'=>'form-control')) }}
          </div>
          <div class="form-group">
            <label>Last Name*</label>
            {{ Form::text('last_name','', array('class'=>'form-control')) }}
          </div>
          <?php /*<div class="form-group">
            <label>Username*</label>
            {{ Form::text('username','', array('class'=>'form-control')) }}
          </div> */?>
          <div class="form-group">
            <label>Email*</label>
            {{ Form::text('email','', array('class'=>'form-control')) }}
          </div>
          <div class="form-group">
            <label>Password*</label>
            {{ Form::password('password', array('class'=>'form-control')) }}
          </div>
          <div class="form-group">
            <label>User Type*</label>
            <select name="role_id" class="form-control" required="">
                <option value='0'>-Select Role-</option>
               @foreach ($data['roles'] as $role)
               <option value='{{ $role->id }}'>{{$role->name }}</option>
                @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" name="id" value="" />
          <!--<button type="button" class="btn btn-danger">Delete Message</button>-->
          <button type="button" class="btn btn-primary pop-up-modal-form-submit">Create User</button>
        </div>
    {!! Form::close() !!}   
    