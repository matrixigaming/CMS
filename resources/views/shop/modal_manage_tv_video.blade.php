<!-- admin create message modal -->
      <div class="modal-header">
        <button type="button" class="close closethismodalpopup" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Set TV Video for a Shop</h4>
      </div>
        <div id="modal-response-msg" style="color: green; font-size: 16px; margin: 0 15px;"></div>
        {!! Form::open(array('url' => 'shop/set_tv_video', 'id'=>'shop-tv-video-modal-form', 'files'=>true)) !!}
      <div class="modal-body"> 
          <div class="form-group">
            <label>Select Shop*</label>
            <select name="user_id" class="form-control shops-video required">
                <option value=""></option>
                @foreach($shop_list as $shop)
                <option value="{{$shop->id}}">{{$shop->shop_name}}</option>
                @endforeach 
            </select>
          </div> 
          <div class="form-group">
            <label>Select Video</label>
            <input type="file" name="video_name" class="form-control required" accept=".mp4" />
          </div>
          <div class="form-group shopVideoDisplaySection"></div>
        </div>


        
        <div class="modal-footer">
            <input type="hidden" name="id" value="" class="shop-tv-video-setting" />
          <!--<button type="button" class="btn btn-danger">Delete Message</button>-->
          <button type="button" class="btn btn-primary pop-up-modal-form-submit-with-image">Submit</button>
        </div>
    {!! Form::close() !!}   
    