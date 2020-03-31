<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Matrix iGaming: Let's Play</title>
        <link rel="stylesheet" href="{{asset('playerapp/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.css">
        <link rel="stylesheet" href="{{asset('playerapp/css/easy-numpad.min.css?v=1.0') }}">
        <script src="{{asset('playerapp/js/jquery.min.js') }}"></script>
        <script src="{{asset('playerapp/js/popper.min.js') }}"></script>
        <script src="{{asset('playerapp/js/bootstrap.min.js') }}"></script>
        <script>
$('#demo').carousel({
    interval: 5000
});
        </script>
        <style type="text/css">
            .col {
                flex: 1 0 50%; 
            }
            .row{
                margin-right:0px;
                margin-left:0px;
                display:flex!important;
            }
            .container-fluid{
                padding-right:0px;
                padding-left:0px;
            }
            .con-sl{
                background-image: url(/playerapp/header.png);
                padding-right: 0px;
                padding-left: 0px;
                background-size: 100% 100%;
            }

            body{
                background-color: black;
            }
            #myModal .modal-content{
                background-image: url(/playerapp/bg1.png);
                margin-top: 30%;
                background-size: 750px 405px;
                height:405px;
                width:555px;

            }
            #footerloginbutton {
                margin-left: 12%;
                margin-top: -5%;
            } 

            .modal-header{
                border-bottom:0px;
                padding-bottom: 0;
                margin-top: 55px;

            }
            .modal-title{
                font-family: Impact, Charcoal, sans-serif;
                width: 100%;
                font-size:3rem;
            }
            .modal-body{
                padding-top: 0;

            }
            .modal-body p{
                padding-right: 5rem;
                padding-left: 5rem;
            }
            .footer a {
                margin-left: 39.8%;
            }
            #footerbutton{
                height: 5.33vw;

                margin-top: -4%;
            }
            .footer{
                margin-top:3rem;
            }
            .form-control{
                background-color: rgba(177, 196, 215, 0.26)!important;
                padding:0;
                width:88%!important;
                border-radius: 0;
                border:0;
                padding: 5px;
                color:#fff!important;
            }
            input[type="text"]::placeholder {  
                /* Firefox, Chrome, Opera */ 
                text-align: center; 
            } 
            input[type="text"]::-ms-input-placeholder { 
                /* Internet Explorer 10-11 */ 
                text-align: center; 
            } 
            input[type="password"]::placeholder {  
                /* Firefox, Chrome, Opera */ 
                text-align: center; 
            } 
            input[type="password"]::-ms-input-placeholder { 
                /* Internet Explorer 10-11 */ 
                text-align: center; 
            } 
            /* ######################## for image overlay ########################### */

            .image {
                opacity: 1;
                display: block;
                width: 100%;
                height: auto;
                transition: .5s ease;
                backface-visibility: hidden;
            }
            .middle {
                transition: .5s ease;
                opacity: 0;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                -ms-transform: translate(-50%, -50%);
                text-align: center;
            }
            .img-contain:hover .image {
                opacity: 0.3;
            }
            .img-contain:hover .middle {
                opacity: 1;
            }
            #myModal1 .modal-content{
                background:transparent!important;
            }
            #btn {
                background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #a3e059), color-stop(50%, #4cae00), color-stop(50%, #136c00), color-stop(100%, #309000));
                border: 1px solid #76f53b;
                border-radius: 5px;
                box-shadow: inset 0 0 0 1px rgba(255, 115, 100, 0.4), 0 1px 3px #333333;
                color: #fff;
                text-align: center;
                text-shadow: 0 -1px 1px rgb(166, 45, 0);
                width: auto;
                font-weight: bold;
                padding: 2px 11px;
            }
            #btn:hover{
                opacity: .9;
            }
            #win-credit::placeholder{
                font-size: 11px!important;
            }
            
.easy-numpad-frame{
background: transparent!important;
    position: relative!important;
  }
.easy-numpad-output{
 border-radius: 5px;
    background: rgb(2, 5, 101)!important;
    color: #fff!important;
    border: 2px solid #fff;
 } 
 .modal-content{
  background: transparent!important;
 }
 a{
  text-decoration: none!important;
 }
 .easy-numpad-number-container > table a {
  border-radius: 5px!important;
 }
 .easy-numpad-container {
    max-width: 345px!important;
    }
    #done {
    background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #a3e059), color-stop(50%, #4cae00), color-stop(50%, #136c00), color-stop(100%, #309000));
    border: 1px solid #76f53b;
    border-radius: 5px;
    box-shadow: inset 0 0 0 1px rgba(255, 115, 100, 0.4), 0 1px 3px #333333;
    color: #fff;
    text-align: center;
    text-shadow: 0 -1px 1px rgb(166, 45, 0);
    width: auto;
    font-weight: bold;
    padding: 14px 11px;
}
#done:hover{
opacity: .9;
  }
  .thoughtbot:hover{
opacity: .9;
  }
.button {
  height:1!important;
      line-height: 10px;
    display: inline-block!important;
    text-decoration: none!important;
    color: #fff!important;
    font-weight: bold!important;
    background-color: #538fbe!important;
    padding: 20px 40px!important;
    font-size: 16px!important;
    border: 1px solid #2d6898!important;
    background-image: linear-gradient(bottom, rgb(73,132,180) 0%, rgb(97,155,203) 100%)!important;
    background-image: -o-linear-gradient(bottom, rgb(73,132,180) 0%, rgb(97,155,203) 100%)!important;
    background-image: -moz-linear-gradient(bottom, rgb(73,132,180) 0%, rgb(97,155,203) 100%)!important;
    background-image: -webkit-linear-gradient(bottom, rgb(73,132,180) 0%, rgb(10, 40, 64) 100%)!important;
    background-image: -ms-linear-gradient(bottom, rgb(73,132,180) 0%, rgb(97,155,203) 100%)!important;
 
    background-image: -webkit-gradient(
        linear,
        left bottom,
        left top,
        color-stop(0, rgb(73,132,180)),
        color-stop(1, rgb(97,155,203))
    );
    -webkit-border-radius: 5px!important;
    -moz-border-radius: 5px!important;
    border-radius: 5px!important;
    text-shadow: 0px -1px 0px rgba(0,0,0,.5)!important;
    -webkit-box-shadow: 0px 6px 0px #2b638f, 0px 3px 15px rgba(0,0,0,.4), inset 0px 1px 0px rgba(255,255,255,.3), inset 0px 0px 3px rgba(255,255,255,.5)!important;
    -moz-box-shadow: 0px 6px 0px #2b638f, 0px 3px 15px rgba(0,0,0,.4), inset 0px 1px 0px rgba(255,255,255,.3), inset 0px 0px 3px rgba(255,255,255,.5)!important;
    box-shadow: 0px 6px 0px #2b638f, 0px 3px 15px rgba(0,0,0,.4), inset 0px 1px 0px rgba(255,255,255,.3), inset 0px 0px 3px rgba(255,255,255,.5)!important;
}
.button:hover{
  opacity: .8;
}
td{
  padding:10px;
}

.thoughtbot {
    background-color: #ee432e!important;
    background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #ee432e), color-stop(50%, #c63929), color-stop(50%, #b51700), color-stop(100%, #891100));
    background-image: -webkit-linear-gradient(top, #ee432e 0%, #c63929 50%, #b51700 50%, #891100 100%)!important;
    background-image: linear-gradient(top, #ee432e 0%, #c63929 50%, #b51700 50%, #891100 100%)!important;
    border: 1px solid #951100!important;
    border-radius: 5px!important;
    -webkit-box-shadow: inset 0 0 0 1px rgba(255, 115, 100, 0.4), 0 1px 3px #333333!important;
    box-shadow: inset 0 0 0 1px rgba(255, 115, 100, 0.4), 0 1px 3px #333333!important;
    color: #fff!important;
    font-weight: bold!important;
    padding: 14px 11px!important;
    text-align: center!important;
    text-shadow: 0 -1px 1px rgba(0, 0, 0, 0.8)!important!important;
    width: auto!important;
}

 .footer {
    margin-top: 0rem !important;
    position: fixed !important;
    bottom: 0px !important;
}

#demo{
     margin-top: 2.5rem !important;
}
.mt-4rem{
margin-top:4rem !important;
    }
    .carousel-control-next, .carousel-control-prev {
    width: 7% !important;
}
        </style>

    </head>
    <!--oncontextmenu="return false" onselectstart="return false" ondragstart="return false"--> 
    <body oncontextmenu="return false" onselectstart="return false" ondragstart="return false">
        <div id="body">
            <div class="col-lg-12 col-md-12 text-light con-sl img-fluid">
                <div class="row" style="margin-right:0px; margin-left:0px">
                    <div class="col-3 mt-3"><span class="ml-5">Welcome <?php echo (isset($cust_data['name'])) ? $cust_data['name'] : 'Guest' ?></span></div>
                    <div class="col-6 text-center" style="padding-top: .5%;padding-bottom: .5%;">
                        <strong>Matrix</strong> <br> <strong>iGaming</strong>        
                    </div>
                    <?php $classMt2 = isset($totalWins) && $totalWins != '' ? '' : 'mt-2'; ?>
                    <div class="col-3 {{$classMt2}}">
                        <span class="float-right mr-5 {{$classMt2}}">
                            @if(isset($cust_data['balance']) && request()->segment(count(request()->segments())) != 'play')
                            Total Credit : ${{ $cust_data['balance'] }} <br />
                            @if($totalWins !='') 
                            <a href="#" data-toggle="modal" data-target="#myModal1WinToCredit"><button class="btn btn-sm btn-warning float-bottom" style="margin-left: -181px;margin-top: -16px;">Convert Win to Credit</button></a>
                            <!--<div id="dem" class="collapse dropdown-menu animated bounceInDown p-3" style="animation-duration: 1.5s!important;width:345px;left: unset!important;right: 30%!important;padding-left: 10px;padding-right: 10px;background-color: /*rgb(162, 5, 56)*/rgb(0, 33, 156);border: 2px solid #fff;">
                                {!! Form::open(array('url' => '/player/convert_win_credit', 'id'=>'win-to-credit-form', 'class'=>'form-inline', 'style'=>'margin-bottom: 0')) !!}

                                    <label for=""></label>
                                    <input id="win-credit" type="number" class="ml-2 text-left" name="reverse_amount" placeholder="Enter amount to add into credit" style="color:#4c4c4c;">
                                    <input type="hidden" name="id" value="{{ $cust_data['id'] }}" />
                                    <input type="button" class="ml-3 convert-win-to-credit" id="btn" value="Submit" name="">
                               {!! Form::close() !!}  
                            </div>-->
                            <span class="ml-4">Total Wins &nbsp;&nbsp;: ${{$totalWins}}</span> 
                            @endif
                            @else
                            <a href="{{ url('/player')}}"><i class="fa fa-home" style="font-size:30px;color:#fff;padding-right: 10px;"></i></a>
                            @endif
                            <!--Balance: 1000.00-->
                        </span>
                    </div>
                </div>
            </div>
            @yield('content')
        </div>
    </body>
</html>