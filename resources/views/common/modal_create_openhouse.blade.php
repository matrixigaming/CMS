<style>
input[type="file"] {
  display: block;
}
.imageThumb {
  max-height: 75px;
  border: 2px solid;
  padding: 1px;
  cursor: pointer;
}
.pip {
  display: inline-block;
  margin: 10px 0 10px 10px;
}
.remove {
  display: block;
  background: #444;
  border: 1px solid black;
  color: white;
  text-align: center;
  cursor: pointer;
}
.remove:hover {
  background: white;
  color: black;
}
</style>
<!-- admin create message modal -->
<div class="modal-header">
    <button type="button" class="close closethismodalpopup" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Add Open House</h4>
</div>
<div id="modal-response-msg" style="color: green; font-size: 16px; margin: 0 15px;"></div>
{!! Form::open(array('url' => 'common/add_open_house', 'id'=>'destination-video-modal-form')) !!}
<div class="modal-body">
    <div class="form-group">
        <label>Title</label>
        <input type="text" name="house_title" class="form-control" placeholder="House Title" value="" />
    </div>
    <div class="form-group">
        <label>Start Date</label>
        <input type="text" name="start_date" class="form-control" placeholder="Start Date i.e. YYYY-MM-DD" value="" />
    </div>
    <div class="form-group">
        <label>Start Time</label>
        <input type="text" name="start_time" class="form-control timepicker" placeholder="Start Time i.e HH:MM am/pm" value="" />
    </div>
    <div class="form-group">
        <label>End Time</label>
        <input type="text" name="end_time" class="form-control"  placeholder="End Time i.e HH:MM am/pm" value="" />
        <!--<div class="input-group bootstrap-timepicker timepicker">
            <input id="timepicker" class="form-control" data-provide="timepicker" data-template="modal" data-minute-step="1" data-modal-backdrop="true" type="text"/>
        </div>-->
    </div>
    <div class="form-group">
        <label>Notes</label>
        <textarea name="notes" placeholder="Notes" rows="2" class="form-control"></textarea>
        <!--<input type="text" name="notes" class="form-control" placeholder="Notes" rows="3" value="" />-->
    </div>
</div>
<div class="modal-footer">
    <input type="hidden" name="module_type" value="{{ $type }}" />
    <!--<button type="button" class="btn btn-danger">Delete Message</button>-->
    <button type="button" class="btn btn-primary pop-up-modal-form-submit">Add Open House</button>
</div>
{!! Form::close() !!}   
<script type="text/javascript">
$(document).ready(function() {
 
});
 //<span class=\"remove\">Remove</span>
  
 
</script>
