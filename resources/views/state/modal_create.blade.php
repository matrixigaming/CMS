<!-- admin create message modal -->
<div class="modal-header">
    <button type="button" class="close closethismodalpopup" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Create State</h4>
</div>
<div id="modal-response-msg" style="color: green; font-size: 16px; margin: 0 15px;"></div>
{!! Form::open(array('url' => 'states/create', 'id'=>'state-create-modal-form')) !!}
<div class="modal-body"> 
    <div class="form-group">
        <label>Name*</label>
        {{ Form::text('state_name','', array('class'=>'form-control')) }}
    </div>  
    <div class="form-group">
        <label>Country*</label>
        <select name="country_id" class="form-control">
            <option value="">Select Country</option>
            @foreach ($countries as $country)
            <option value='{{ $country->id }}'>{{$country->name }}</option>
            @endforeach 
        </select>
    </div>
</div>
<div class="modal-footer">
    <input type="hidden" name="id" value="" />
    <!--<button type="button" class="btn btn-danger">Delete Message</button>-->
    <button type="button" class="btn btn-primary pop-up-modal-form-submit">Create State</button>
</div>
{!! Form::close() !!}   
