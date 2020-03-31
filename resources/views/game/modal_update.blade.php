<!-- admin create message modal -->
    <div class="modal-header">
      <button type="button" class="close closethismodalpopup" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title" id="myModalLabel">Update Game</h4>
    </div>
      <div id="modal-response-msg" style="color: green; font-size: 16px; margin: 0 15px;"></div>
      {!! Form::open(array('url' => 'games/create', 'id'=>'game-create-modal-form', 'files'=>true)) !!}
    <div class="modal-body"> 
        <div class="form-group">
          <label>Name*</label>
          {{ Form::text('name',$data->name, array('class'=>'form-control required')) }}
        </div>
        <div class="form-group">
            <label>Icon*</label>
            {{ Form::file('icon', array('class'=>'form-control', 'id'=>'icon')) }} 
            <br />
            @if($data->icon)
                            @php
                            $moduleConfig = config('constants.game');
                                $imagePath = $data->icon;
                                $path_parts = pathinfo($imagePath);
                                $filename = $moduleConfig['game_icon_path'] . '/' . $path_parts['filename'] . '_xs' . '.' . $path_parts['extension'];
                                $filename = file_exists($filename) ? $filename : $moduleConfig['game_icon_path'].'/'.$path_parts['filename'].'.'.$path_parts['extension'];
                            @endphp
                            <img class="img-responsive" src="{{ url($filename) }}" alt="game icon - {{ $data->name }}">
                       
                        @endif
          </div> 
          
          <div class="form-group">
            <label>Lobby Icon*</label>
            {{ Form::file('lobby_icon', array('class'=>'form-control', 'id'=>'lobby_icon')) }} 
            <br />
            @if($data->lobby_icon)
                            @php
                            $moduleConfig = config('constants.game');
                                $imagePath = $data->lobby_icon;
                                $path_parts = pathinfo($imagePath);
                                $filename = $moduleConfig['game_icon_path'] . '/' . $path_parts['filename'] . '.' . $path_parts['extension'];
                                //$filename = file_exists($filename) ? $filename : $moduleConfig['game_icon_path'].'/'.$path_parts['filename'].'.'.$path_parts['extension'];
                            @endphp
                            <img class="img-responsive" src="{{ url($filename) }}" alt="game icon - {{ $data->name }}">
                        @endif
          </div>
        
          <div class="form-group">
            <label>URL*</label>
            {{ Form::text('url',$data->url, array('class'=>'form-control required')) }}
          </div>
        <div class="form-group">
            <label>Math URL*</label>
            {{ Form::text('math_url',$data->math_url, array('class'=>'form-control required')) }}
          </div>
          <div class="form-group">
            <label>Default RTP</label>
            <select name="default_rtp" class="form-control">
                    <option value="90" @php echo $data->default_rtp == 90? 'selected':'' @endphp>90</option>
                    <option value='91' @php echo $data->default_rtp == 91? 'selected':'' @endphp>91</option>
                    <option value="92" @php echo $data->default_rtp == 92? 'selected':'' @endphp>92</option>
                    <option value="93" @php echo $data->default_rtp == 93? 'selected':'' @endphp>93</option>
                    <option value="94" @php echo $data->default_rtp == 94? 'selected':'' @endphp>94</option>
                    <option value="95" @php echo $data->default_rtp == 95? 'selected':'' @endphp>95</option>
                </select>
          </div>
      </div>
      <div class="modal-footer">
          <input type="hidden" name="id" value="{{ $data->id }}" />
        <!--<button type="button" class="btn btn-danger">Delete Message</button>-->
        <button type="button" class="btn btn-primary pop-up-modal-form-submit-with-image">Update Game</button>
      </div>
  {!! Form::close() !!}   
    