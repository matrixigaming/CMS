<!-- admin create message modal -->
      <div class="modal-header">
        <button type="button" class="close closethismodalpopup" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit Login Verbiage - {{ $data->name }}</h4>
      </div>
        <div id="modal-response-msg" style="color: green; font-size: 16px; margin: 0 15px;"></div>
        {!! Form::open(array('url' => 'loginverbiage/create', 'id'=>'page-create-modal-form')) !!}
      <div class="modal-body"> 
          <div class="form-group">
            <label>Name*</label>
            {{ Form::text('name',$data->name, array('class'=>'form-control')) }}
          </div> 
          <div class="form-group">
            <label>Content*</label>
            {{ Form::textarea('content', $data->content, ['class' => 'editor form-control', 'id'=>'editor', 'size'=>'80x10']) }}
          </div> 
        </div>
        <div class="modal-footer">
            <input type="hidden" name="id" value="{{ $data->id }}" />
          <!--<button type="button" class="btn btn-danger">Delete Message</button>-->
          <button type="button" class="btn btn-primary pop-up-modal-form-submit">Update Login Verbiage</button>
        </div>
    {!! Form::close() !!}<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

