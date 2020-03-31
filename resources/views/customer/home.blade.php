@extends('layouts.playerapp2')

@section('content')
<?php //echo "<pre>"; print_r($_SESSION); ?>
<div class="container-fluid head-container mt-3">
                <div id="demo" class="carousel slide" data-ride="carousel">  
                    <div class="carousel-inner">
                <?php 
                $moduleConfig = config('constants.game'); 
                foreach($game_data as $k => $gameOuter){ $active = !$k?'active' : ''; ?>
                    <div class="carousel-item {{ $active }}">
                        <div class="row">
                            <?php foreach($gameOuter as $game){ 
                                $imagePath = isset($game->lobby_icon) && !empty($game->lobby_icon)? $game->lobby_icon : $game->icon;
                                $path_parts = pathinfo($imagePath);
                                $filename = $moduleConfig['game_icon_path'] . '/' . $path_parts['filename'] . '.' . $path_parts['extension'];
                                $filename = file_exists($filename) ? $filename : $moduleConfig['game_icon_path'].'/'.$path_parts['filename'].'.'.$path_parts['extension'];
                                ?>
                            <div class="col-3 mt-4rem img-contain">
                                <img src="{{url($filename)}}" class="img-fluid image mx-auto d-block" alt="{{ $game->name }}" />
                                <div class="middle">
<?php if(isset($cust_data['name'])): ?>
{!! Form::open(array('url' => '/player/play')) !!}
                            <input type="hidden" name="game_url" value="{{$game->url}}" />
                            <input type="hidden" name="game_id" value="{{$game->id}}" />
                            <input type="hidden" name="user_id" value="{{$cust_data->id}}" />
                            <?php 
                                  $customerInfoSession = session('customer_info');                             
                                    $playButtonImage = isset($customerInfoSession['login_verbiage_id']) && $customerInfoSession['login_verbiage_id'] =='2' ? 'playerapp/donate.png':'playerapp/play.png'; 
                            ?>
                            <button type="submit" class="btn btn-sm text" name=""><img src="{{ url($playButtonImage)}}" class="img-fluid" style="height: 4vw;width:7vw"></button>
                            {!! Form::close() !!} 
                                    
<?php else: ?>
<a href="#" data-toggle="modal" data-target="#myModal1Login"><img src="{{ url('playerapp/login.png')}}" class="img-fluid" style="height: 3vw;"></a>
<?php endif;?>
                                </div>    
                            </div>
                            <?php } ?>
                        </div>    
                    </div>
                <?php } ?>
            </div>
                    <!-- Left and right controls -->
                    <a class="carousel-control-prev" href="#demo" data-slide="prev">
                        <img src="{{ url('playerapp/previousarrow.png')}}" class="img-fluid bg-light" style="height:60px;width:60px;border-radius: 70px 70px 70px 70px;">
                    </a>
                    <a class="carousel-control-next" href="#demo" data-slide="next">
                        <img src="{{ url('playerapp/nextarrow.png')}}" class="img-fluid bg-light"style="height:60px;width:60px;border-radius: 70px 70px 70px 70px;">
                    </a>
                </div>

                <div class="footer">
                    <img src="{{ url('playerapp/footerborder.png')}}" class="img-fluid" style="margin-bottom: -1.4rem;">
                    <?php if(isset($customerInfoSession['login_verbiage_id']) && $customerInfoSession['login_verbiage_id'] =='1'): ?>
                        
                        <a href="#" data-toggle="modal" data-target="#myModalLogout"><img src="{{ url('playerapp/logout.png') }}" id="footerbutton" class="img-fluid"></a>
                    <?php else: 
                        if(isset($cust_data['name'])): ?>
                            <a href="{{url('/player/logout')}}"><img src="{{ url('playerapp/logout.png')}}" class="img-fluid" id="footerbutton"></a>
                        <?php else: ?>
                            <a href="#" data-toggle="modal" data-target="#myModal1Login"><img src="{{ url('playerapp/login.png') }}" id="footerbutton" class="img-fluid"></a>
                        <?php endif;
                    endif;?>
                </div>
            </div>

   <div class="modal" id="myModal1">
    <div class="modal-dialog  modal-xl modal-dialog-centered" style="max-width: 140vh !important;">
      <div class="modal-content">
        <div class="modal-body">
          <!--<img src="jackpot/congrats_gold_jackpot.png" class="mt-5 img-fluid">
          <img src="jackpot/congrats_silver_jackpot.png" class="mt-5 img-fluid">
          <img src="jackpot/congrats_bronze_jackpot.png" class="mt-5 img-fluid">-->
          <img src="{{ url('playerapp/jackpot/congrats_diamond_jackpot.png')}}" id="jackpot-win-level" class="mt-5 img-fluid">
          <h1 style="position: absolute;margin-top: -23vh;margin-left: 54vh; font-size:6vh;" id="jackpot-win-amount"></h1>
        </div>
      </div>
    </div>
  </div>  
@if(isset($customerInfoSession['login_verbiage_id']))
    <div class="modal" id="myModalLogout">
        <div class="modal-dialog  modal-xl modal-dialog-centered" style="max-width: 70vh !important;">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="login-form">
                        <div class="row">                
                            <div class="col-md-12">
                                <div style="max-height: 100px;margin: 1rem 0rem 3rem 0rem;">
                                        <p> Which one is green light?</p>
                                </div>
                                <div class="mt-4"> 

                                    <a href="{{url('/player/logout')}}" class="float-left "><img src="{{ url('playerapp/green.png')}}" class="img-fluid" id="footerbutton"></a>
                                    <a href="Done" class="float-right" data-dismiss="modal" ><img src="{{ url('playerapp/red.png')}}" class="img-fluid" id="footerbutton"></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </div>
@endif
@if(isset($cust_data['balance']) && request()->segment(count(request()->segments())) != 'play')
<div class="modal" id="myModal1WinToCredit">
    <div class="modal-dialog" style="margin-top: 10%;font-family:arial;">
      <div class="modal-content" style="border:0">
        <div class="modal-body">

        <div class="alert error-msg" id="error-msg"> </div>
        {!! Form::open(array('url' => '/player/convert_win_credit', 'id'=>'login-form2', 'class'=>'form-inline')) !!}
        <div class="easy-numpad-frame" id="easy-numpad-frame">
            <div class="easy-numpad-container">
                <div class="easy-numpad-output-container">
                    <p class="easy-numpad-output" id="easy-numpad-output"></p>
                </div>
                <div class="easy-numpad-number-container">
                    <table>
                        <tr>
                            <td><a href="7" class="button" onclick="easynum()">7</a></td>
                            <td><a href="8" class="button" onclick="easynum()">8</a></td>
                            <td><a href="9" class="button" onclick="easynum()">9</a></td>
                        </tr>
                        <tr>
                            <td><a href="4" class="button" onclick="easynum()">4</a></td>
                            <td><a href="5" class="button" onclick="easynum()">5</a></td>
                            <td><a href="6" class="button" onclick="easynum()">6</a></td>
                        </tr>
                        <tr>
                            <td><a href="1" class="button" onclick="easynum()">1</a></td>
                            <td><a href="2" class="button" onclick="easynum()">2</a></td>
                            <td><a href="3" class="button" onclick="easynum()">3</a></td>
                        </tr>
                        <tr>
                            <td onclick="easynum()"><a href="0" class="button">0</a></td>
                            <td><a href="Del" class="del button" id="del" onclick="easy_numpad_del()" style="padding-right: 33px!important;padding-left: 31px!important;">Del</a></td>
                            <td onclick="easynum()"><a href="." class="button" style="padding-right: 43px!important;padding-left: 43px!important;">.</a></td>
                        </tr>
                        <tr>
                            <td ><a href="Clear" class="clear thoughtbot" id="clear" onclick="easy_numpad_clear()">Clear</a></td>
                            <td></td>
                            <td><a href="Done" class="done loginbtnnew" data-modalType="convert-win-credit" id="done" >Submit</a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <input type="hidden" name="reverse_amount" id="customerWinAmtToCredit" value="" />
        <input type="hidden" name="id" value="{{ $cust_data['id'] }}" />
        {!! Form::close() !!}  
        </div>
      </div>
    </div>
  </div>
  @else
  <div class="modal" id="myModal1Login">
    <div class="modal-dialog"  style="margin-top: 10%;font-family:arial;">
      <div class="modal-content" style="border:0">
        <div class="modal-body">

        <div class="alert error-msg" id="error-msg"> </div>
        {!! Form::open(array('url' => '/player/ajaxLoginPost', 'id'=>'login-form', 'class'=>'form-inline')) !!}
        <div class="easy-numpad-frame" id="easy-numpad-frame">
            <div class="easy-numpad-container">
                <div class="easy-numpad-output-container">
                    <p class="easy-numpad-output" id="easy-numpad-output"></p>
                </div>
                <div class="easy-numpad-number-container">
                    <table>
                        <tr>
                            <td><a href="7" class="button" onclick="easynum()">7</a></td>
                            <td><a href="8" class="button" onclick="easynum()">8</a></td>
                            <td><a href="9" class="button" onclick="easynum()">9</a></td>
                        </tr>
                        <tr>
                            <td><a href="4" class="button" onclick="easynum()">4</a></td>
                            <td><a href="5" class="button" onclick="easynum()">5</a></td>
                            <td><a href="6" class="button" onclick="easynum()">6</a></td>
                        </tr>
                        <tr>
                            <td><a href="1" class="button" onclick="easynum()">1</a></td>
                            <td><a href="2" class="button" onclick="easynum()">2</a></td>
                            <td><a href="3" class="button" onclick="easynum()">3</a></td>
                        </tr>
                        <tr>
                            <td onclick="easynum()"><a href="0" class="button">0</a></td>
                            <td><a href="Del" class="del button" id="del" onclick="easy_numpad_del()" style="padding-right: 33px!important;padding-left: 31px!important;">Del</a></td>
                            <td onclick="easynum()"><a href="." class="button" style="padding-right: 43px!important;padding-left: 43px!important;">.</a></td>
                        </tr>
                        <tr>
                            <td ><a href="Clear" class="clear thoughtbot" id="clear" onclick="easy_numpad_clear()">Clear</a></td>
                            <td></td>
                            <td><a href="Done" class="done loginbtnnew" data-modalType="login-customer" id="done" >Login</a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <input type="hidden" name="key_code" value="" id="customerLoginCode" />
        {!! Form::close() !!}  
        </div>
      </div>
    </div>
  </div>
@endif
<?php $baseUrl = url(''); $customerInfoSession = session('customer_info'); //echo "<pre>"; print_r($customerInfoSession); echo "</pre>"; ?>
<?php if(!empty($customerInfoSession)):?>
<script>
$(document).ready(function () {
    var baseUrl = '<?php echo $baseUrl?>';    
    function jackpotAlerts(){
        var formData = {"uid":<?php echo $customerInfoSession['id'];?>};
        $.ajax({
            url: '//api.matrixigaming.net/jackpot_player_call',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {                
                if(response.status){ 
                    //console.log(response.data[0].player[0].win_amount); 
                    var winAmount = response.data[0].player[0].win_amount;
                    var winLevel = response.data[0].player[0].win_level;
                    var jackpotWinImg = baseUrl + '/playerapp/jackpot/jackpot_level_'+winLevel+'.png';
                    $('#jackpot-win-amount').text('$'+winAmount);
                    $('#jackpot-win-level').attr('src', jackpotWinImg);
                    $('#myModal1').modal();
                    //$('#myModal').modal('hide');
                    setTimeout(function(){
                       window.location.reload(true);
                       }, 3000);
                }              
            },
            error: function (data) {
                if(data.status == '502' || data.status == '500'){ 
                        console.log('Something went wrong, please try again.');
                        window.location.reload(true);
                    }
                var errors = $.parseJSON(data.responseText);
                //console.log(errors);
                var errorMsg = '';
                $.each(errors, function (index, value) {
                    errorMsg += value + '<br />';
                });
            }
        });
    }
    
   setInterval(function () {
        jackpotAlerts();
    }, 10 * 1000);
  });
</script>  
<?php endif; ?>
<script>
    function easy_numpad_close() {
    $('.easy-numpad-frame').remove();
}

function easynum() {
    event.preventDefault();

    navigator.vibrate = navigator.vibrate || navigator.webkitVibrate || navigator.mozVibrate || navigator.msVibrate;
    if (navigator.vibrate) {
        navigator.vibrate(60);
    }

    var easy_num_button = $(event.target);
    var easy_num_value = easy_num_button.text();
    $('.easy-numpad-output').append(easy_num_value);
}
function easy_numpad_del() {
    event.preventDefault();
    var easy_numpad_output_val = $('.easy-numpad-output').text();
    var easy_numpad_output_val_deleted = easy_numpad_output_val.slice(0, -1);
    $('.easy-numpad-output').text(easy_numpad_output_val_deleted);
}
function easy_numpad_clear() {
    event.preventDefault();
    $('.easy-numpad-output').text("");
}
function easy_numpad_cancel() {
    event.preventDefault();
    $('.easy-numpad-frame').remove();
}
$(document).ready(function () {
    $('body').on('click', ".loginbtnnew", function (e) {  
        e.preventDefault(); //keeps the form from behaving like a normal (non-ajax) html form        
        var modalType = $(this).attr('data-modalType');  
        if(modalType == 'convert-win-credit'){ 
            console.log($('.easy-numpad-output').text());
            var easy_numpad_output_val = parseFloat($('.easy-numpad-output').text());
            $('#customerWinAmtToCredit').val(easy_numpad_output_val);
             var enteredValue = $('#customerWinAmtToCredit').val();
        }else{ 
            var easy_numpad_output_val = $('.easy-numpad-output').text();
            if(easy_numpad_output_val !=''){
                $('#customerLoginCode').val(easy_numpad_output_val);
                var enteredValue = $('#customerLoginCode').val();
            }else{
                var enteredValue = $('#customerLoginCode').val();
            }            
        }
        
        if(enteredValue !=''){
            var form_id = $(this).closest("form").attr('id'); //$(this).attr('data-form-id');
            var form = $("#"+form_id); 
            var url = $(this).closest("form").attr('action');
            var formData = form.serialize();
            console.log(url); console.log(formData);
            //return false;
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function (response) { 
                    if(response.status){ 
                        if(response.msg){
                            $('.error-msg').removeClass('alert-danger').addClass('alert-success');
                            $('.error-msg').html(response.msg);
                            setTimeout(function(){
                                window.location.reload(true);
                           }, 1000);
                        }else{
                            $('.error-msg').removeClass('alert').removeClass('alert-danger');
                             $('.error-msg').html(response.msg);
                            $('#easy-numpad-frame').removeClass('easy-numpad-frame').addClass('row');
                            $('.modal-dialog').addClass('modal-size');
                            $('#easy-numpad-frame').html(response.msgHtml);
                        }

                    }else{ 
                        $('.error-msg').removeClass('alert-success').addClass('alert-danger');
                        $('.error-msg').html(response.msg);
                    }                
                },
                error: function (data) { 
                    if(data.status == '502' || data.status == '500'){ 
                        $('.error-msg').removeClass('alert-success').addClass('alert-danger');
                        $('.error-msg').html('Something went wrong, please try again.');
                        setTimeout(function(){
                           window.location.reload(true);
                           }, 1000);
                    }
                    //console.log('status'); 
                    //console.log(data.status); //return false;
                    $('.modal-header').css('display','initial');
                    var errors = $.parseJSON(data.responseText);
                    var errorMsg = '';
                    $.each(errors, function (index, value) {
                        errorMsg += value + '<br />';
                    });
                    $('.error-msg').removeClass('alert-success').addClass('alert-danger');
                    $('.error-msg').html(errorMsg);
                }
                /*,complete: function(xhr, textStatus) {
                    if(xhr.status == '502'){ alert('something went wrong, try again.');
                        window.location.reload(true);
                    }
                }*/ 
            });
        }
        
    });
});
</script>
@endsection
