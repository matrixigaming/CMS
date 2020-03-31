$(document).ready(function () {
    $('.easy-get').on('click', () => {
        show_easy_numpad();
    });
});

function show_easy_numpad() {
    var easy_numpad = `
    <div class="modal" id="myModal1Login">
    <div class="modal-dialog" style="margin-top: 10%;font-family:arial;">
      <div class="modal-content" style="border:0">
        <div class="modal-body">

        <div class="alert alert-danger" id="error-msg">
           <button type="button" class="close" data-dismiss="alert">&times;</button>
           Sorry something went wrong.
        </div>
        
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
                            <td><a href="Done" class="done loginbtnnew" id="done" >Login</a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        </div>
      </div>
    </div>
  </div>
    `;
    $('body').append(easy_numpad);
}

function easy_numpad_close() {
    $('#easy-numpad-frame').remove();
}

function easynum() {
    event.preventDefault();

    navigator.vibrate = navigator.vibrate || navigator.webkitVibrate || navigator.mozVibrate || navigator.msVibrate;
    if (navigator.vibrate) {
        navigator.vibrate(60);
    }

    var easy_num_button = $(event.target);
    var easy_num_value = easy_num_button.text();
    $('#easy-numpad-output').append(easy_num_value);
}
function easy_numpad_del() {
    event.preventDefault();
    var easy_numpad_output_val = $('#easy-numpad-output').text();
    var easy_numpad_output_val_deleted = easy_numpad_output_val.slice(0, -1);
    $('#easy-numpad-output').text(easy_numpad_output_val_deleted);
}
function easy_numpad_clear() {
    event.preventDefault();
    $('#easy-numpad-output').text("");
}
function easy_numpad_cancel() {
    event.preventDefault();
    $('#easy-numpad-frame').remove();
}
function easy_numpad_done() {
    event.preventDefault();
    var easy_numpad_output_val = $('#easy-numpad-output').text();
    alert(easy_numpad_output_val);
    if(easy_numpad_output_val !=''){ alert(1);
        var formData = {"code":easy_numpad_output_val};
        $.ajax({
            url: 'http://casino.local/player/ajaxLoginPost',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (response) {
                $('.modal-header').css('display','initial');
                $(this).removeAttr('disabled');
                
                if(response.status){ 
                    $('#modal-response-msg').html(response.msg);
                    setTimeout(function(){
                       window.location.reload(true);
                       }, 1000);
                }else{
                    $('#modal-response-msg').html('<span style="color:red;background-color: #fff;">' + response.msg + '</span>');
                }                
            },
            error: function (data) {
                $('.modal-header').css('display','initial');
                var errors = $.parseJSON(data.responseText);
                //console.log(errors);
                var errorMsg = '';
                $.each(errors, function (index, value) {
                    errorMsg += value + '<br />';
                });
                $('#modal-response-msg').html('<span style="color:red;background-color: #fff;">' + errorMsg + '</span>');
            }
        });
    }
    
    //$('.easy-put').val(easy_numpad_output_val);
    
}