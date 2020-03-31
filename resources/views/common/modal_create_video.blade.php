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
    <h4 class="modal-title" id="myModalLabel">Upload Video</h4>
</div>
<div id="modal-response-msg" style="color: green; font-size: 16px; margin: 0 15px;"></div>
{!! Form::open(array('url' => 'common/upload_video', 'id'=>'destination-video-modal-form')) !!}
<div class="modal-body"> 
    <div class="form-group">
        <label>Format</label>
        <select name="video_type" class="form-control"><option>YouTube</option><option>Vimeo</option></select>
    </div>
    <div class="form-group">
        <label>Title</label>
        <input type="text" name="video_title" class="form-control" placeholder="Video Title" value="" />
    </div>
    <div class="form-group">
        <label>Description</label>
        <input type="text" name="video_description" class="form-control" placeholder="Video Description" value="" />
    </div>
    <div class="form-group">
        <label>Link</label>
        <input type="text" name="video_link" class="form-control" placeholder="Video Link" value="" />
    </div>
</div>
<div class="modal-footer">
    <input type="hidden" name="module_type" value="{{ $type }}" />
    <!--<button type="button" class="btn btn-danger">Delete Message</button>-->
    <button type="button" class="btn btn-primary pop-up-modal-form-submit">Add Video</button>
</div>
{!! Form::close() !!}   
<script type="text/javascript">
$(document).ready(function() {
 
});
 //<span class=\"remove\">Remove</span>
  
 
</script>
