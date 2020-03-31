@extends('layouts.frontapp')

@section('content')

<!-- Review section -->
    <section class="review-section spad1 set-bg">
        <div class="container">
            <div class="section-title">
                <h2>Game List</h2>
            </div>
            <div class="row">
                <?php 
                $moduleConfig = config('constants.game');                                
                    foreach($game_data as $game){
                        $imagePath = $game->icon;
                        $path_parts = pathinfo($imagePath);
                        $filename = $moduleConfig['game_icon_path'] . '/' . $path_parts['filename'] . '.' . $path_parts['extension'];
                        $filename = file_exists($filename) ? $filename : $moduleConfig['game_icon_path'].'/'.$path_parts['filename'].'.'.$path_parts['extension'];
                          ?>  
                <div class="col-lg-3 col-md-6" style="margin-bottom: 15px;">
                    <div class="recent-game-item">
                        <div class="rgi-thumb set-bg" data-setbg="{{url($filename)}}" style="background-image: url(&quot;{{url($filename)}}&quot;);">
                        </div>
                        <div class="rgi-content">
                            {!! Form::open(array('url' => '/player/play')) !!}
                            <input type="hidden" name="game_url" value="{{$game->url}}" />
                            <input type="hidden" name="game_id" value="{{$game->id}}" />
                            <input type="hidden" name="user_id" value="{{$cust_data->id}}" />
                            <h5>{{ $game->name }}</h5>
                            <div class="user-panel" style="padding: 2px 35px;
    width: 56%;
    float: inherit;"><button style="background: #ffb320;" type="submit" class="btn" name="Play" value="Play">Play</button> </div>
                            {!! Form::close() !!} 
                        </div>                            
                    </div>  
                </div>
            <?php } ?>
            </div>
        </div>
    </section>
    <!-- Review section end -->



@endsection