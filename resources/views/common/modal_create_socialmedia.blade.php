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
    <h4 class="modal-title" id="myModalLabel">Add Social Media</h4>
</div>
<div id="modal-response-msg" style="color: green; font-size: 16px; margin: 0 15px;"></div>
{!! Form::open(array('url' => 'common/add_social_media', 'id'=>'destination-video-modal-form')) !!}
<div class="modal-body"> 
    <div class="form-group">
        <label>Type</label>
        <select name="social" class="form-control">
            <option>facebook</option>
            <option>twitter</option>
            <option>instagram</option>
            <option>pintrest</option>
            <option>google-plus</option>
            <option>youtube</option>
            <option>vimeo</option>
            <option>linkedin</option>
            <option>tumblr</option>
        </select>
    </div>
    <div class="form-group">
        <label>Link</label>
        <input type="text" name="link" class="form-control" placeholder="Link" value="" />
    </div>
</div>
<div class="modal-footer">
    <input type="hidden" name="module_type" value="{{ $type }}" />
    <!--<button type="button" class="btn btn-danger">Delete Message</button>-->
    <button type="button" class="btn btn-primary pop-up-modal-form-submit">Add Media</button>
</div>
{!! Form::close() !!}   
<script type="text/javascript">
$(document).ready(function() {
 
});
 //<span class=\"remove\">Remove</span>
  
 
</script>
