<!-- admin create message modal -->
      <div class="modal-header">
        <button type="button" class="close closethismodalpopup" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit User</h4>
      </div>
<div id="modal-response-msg" style="color: green; font-size: 16px; margin: 0 15px;"></div>
        {!! Form::open(array('url' => 'user/create', 'id'=>'user-create-modal-form')) !!}
      <div class="modal-body">        
          <div class="form-group">
            <label>First Name*</label>
            {{ Form::text('first_name',$data['user']->first_name, array('class'=>'form-control')) }}
          </div>
          <div class="form-group">
            <label>Last Name*</label>
            {{ Form::text('last_name',$data['user']->last_name, array('class'=>'form-control')) }}
          </div>
          <?php /*<div class="form-group">
            <label>Username*</label>
            {{ Form::text('username','', array('class'=>'form-control')) }}
          </div> */?>
          <div class="form-group">
            <label>Email*</label>
            {{ Form::text('email', $data['user']->email, array('class'=>'form-control')) }}
          </div>
          
          <div class="form-group">
            <label>User Type*</label>
            
            <select name="role_id" class="form-control" required="">
                <option value='0'>-Select Role-</option>
               @foreach ($data['roles'] as $role)
               <?php $selected = $role->id == $data['user_role_id'] ? 'selected' : ''; ?>
               <option value='{{ $role->id }}' <?php echo $selected; ?>>{{$role->name }}</option>
                @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" name="id" value="{{ $data['user']->id}}" />
          <!--<button type="button" class="btn btn-danger">Delete Message</button>-->
          <button type="button" class="btn btn-primary pop-up-modal-form-submit">Save User</button>
        </div>
    {!! Form::close() !!}   
    