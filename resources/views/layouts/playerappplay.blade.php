<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<title>Matrix iGaming: Let's Play</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


</head>
 <!--oncontextmenu="return false" onselectstart="return false" ondragstart="return false"--> 
 <body oncontextmenu="return false" onselectstart="return false" ondragstart="return false">
    
        <div id="body">
            <div style="position: absolute;bottom: -8px; margin-right: 92%; z-index: 2; ">
                <a href="{{ url('/player')}}">
                    <img src="{{ url('playerapp/home-button75x75.png')}}" class="img-fluid" style="padding-right:10px;">
<!--<i class="fa fa-home" style="font-size:40px;color:#00B55B;padding-right: 10px;"></i>--></a>
                        
                    </div>
    @yield('content')
    </div>
    </body>
</html>