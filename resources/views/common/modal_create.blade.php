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
    <h4 class="modal-title" id="myModalLabel">Upload Images</h4>
</div>
<div id="modal-response-msg" style="color: green; font-size: 16px; margin: 0 15px;"></div>
{!! Form::open(array('url' => 'common/upload_images', 'id'=>'destination-image-modal-form', 'enctype'=>'multipart/form-data')) !!}
<div class="modal-body"> 
    <div class="form-group">
        <label>Upload Image</label>
        <input type="file" id="files" name="uploaded_images[]" multiple />
    </div>
    <div class="row" id="image-preview-modal">
        
    </div>
</div>
<div class="modal-footer">
    <input type="hidden" name="module_type" value="{{ $type }}" />
    <!--<button type="button" class="btn btn-danger">Delete Message</button>-->
    <button type="button" class="btn btn-primary pop-up-modal-form-submit-with-image">Upload</button>
</div>
{!! Form::close() !!}   
<script type="text/javascript">
$(document).ready(function() {
 
 if(window.File && window.FileList && window.FileReader) {
 $("#files").on("change",function(e) {
     $('#image-preview-modal').html('');
      $('#modal-response-msg').html('');
 var files = e.target.files ,
 filesLength = files.length ;
 for (var i = 0; i < filesLength ; i++) {
 var f = files[i];
 if(f.size > 3145728){ //3MB
     $('#modal-response-msg').html('<span style="color:red;">You can only upload max 3MB size image.</span>');
     $('#image-preview-modal').html('');
     return false;
 }

 var fileReader = new FileReader();
 fileReader.onload = (function(e) {
var file = e.target;
          $("#image-preview-modal").append($("<span class=\"pip\">" +
            "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
            "<br/>" +
            "</span>"));
          $(".remove").click(function(){
            $(this).parent(".pip").remove();
          });
 });
 fileReader.readAsDataURL(f);
 }
});
 } else { alert("Your browser doesn't support to File API") }
});
 //<span class=\"remove\">Remove</span>
  
 
</script>
