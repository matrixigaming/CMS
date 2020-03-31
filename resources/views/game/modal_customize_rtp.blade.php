<!-- admin create message modal -->
      <div class="modal-header">
        <button type="button" class="close closethismodalpopup" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Set Game RTP for a Shop</h4>
      </div>
        <div id="modal-response-msg" style="color: green; font-size: 16px; margin: 0 15px;"></div>
        {!! Form::open(array('url' => 'games/setrtp', 'id'=>'game-setrtp-modal-form', 'files'=>false)) !!}
      <div class="modal-body"> 
          <div class="form-group">
            <label>Select Shop*</label>
            <select name="shop_id" class="form-control shoprtp">
                <option value=""></option>
                @foreach($shop_list as $shop)
                <option value="{{$shop->id}}">{{$shop->shop_name}}</option>
                @endforeach 
            </select>
          </div>  
          <div class="form-group">
            <label>Select Game*</label>
            <select name="game_id" class="form-control gamertp">
                <option value=""></option>
                @foreach($game_list as $game)
                <option value="{{$game->id}}">{{$game->name}}</option>
                @endforeach 
            </select>
          </div> 
          
          <div class="form-group">
            <label>Select Game RTP for selected shop</label>
            
            <select name="rtpVariant" class="form-control game-rtpval">
                    <option value=""></option>
                    <option value="90">90</option>
                    <option value='91'>91</option>
                    <option value="92">92</option>
                    <option value="93">93</option>
                    <option value="94">94</option>
                    <option value="95">95</option>
                </select>
          </div>
        </div>
        
        <div class="modal-footer">
            <input type="hidden" name="id" value="" class="game-rtp-setting" />
          <!--<button type="button" class="btn btn-danger">Delete Message</button>-->
          <button type="button" class="btn btn-primary pop-up-modal-form-submit">Set RTP</button>
        </div>
    {!! Form::close() !!}   
    