<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Expires" content="30" />
        <meta name="keywords" content="">
        <meta name="description" content="Matrix iGaming">

        <title>CMS</title>        
        

    <link href="{{ asset('frontend/css2/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css2/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css2/style.css') }}" rel="stylesheet">



</head>
<body>

    <!-- Header section -->
    <header class="header-section">
        <div class="container">
            <!-- logo -->
            <a class="site-logo" href="#">
                Matrix iGaming
            </a>
            <div class="user-panel">
                <a href="#">{{$cust_data['name']}}</a>  /  <a href="{{url('/player/logout')}}">Logout</a>
            </div>
            <!-- responsive -->
            <div class="nav-switch">
                <i class="fa fa-bars"></i>
            </div>
            <!-- site menu -->
            <nav class="main-menu">
                <ul>
                    <li><a href="{{url('/player/game_list')}}">Home</a></li>
                   @if(request()->segment(count(request()->segments())) == 'game_list') <li><a href="#">Credit: {{$cust_data['balance']}}</a></li> @endif

                </ul>
            </nav>
        </div>
    </header>
    @yield('content')

</body>
</html>