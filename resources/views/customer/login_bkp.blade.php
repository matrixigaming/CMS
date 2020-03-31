@extends('layouts.playerapp')

@section('content')
 <div class="container-fluid" style="">
    <div class="row blog" style="margin-left: 0px;margin-right: 0px;">
        <div class="col-lg-1 col-md-1">
            <a class="carousel-control-prev" href="#blogCarousel" data-slide="prev">
    <img src="{{ url('playerapp/leftarrow.png') }}" class="img-fluid" style="margin-left:100;height: 70px;">
  </a>
        </div>
        <div class="col-md-10">

            <div id="blogCarousel" class="carousel slide" data-ride="carousel" style="padding-bottom: 2rem">

                <ol class="carousel-indicators">
                    <li data-target="#blogCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#blogCarousel" data-slide-to="1"></li>
                </ol>
            <div class="carousel-inner">

                <div class="carousel-item active">
                    <div class="row">
                        <div class="col-md-4 mt-5 img-contain">
                            <img src="MariachiFiesta_Lobby_Icon.jpg" class="img-fluid image mx-auto d-block" alt="img2">
                            <div class="middle">
                                <form action="" method="POST">
                                <button type="submit" class="btn btn-sm text" name=""><img src="layout/login.png" class="img-fluid" style="height:30; width:90"></button>
                                <!--<button type="submit" class="btn btn-sm text" name=""><img src="layout/play.png" class="img-fluid" style="height:60; width:90"></button>-->
                                </form>
                            </div>    
                        </div>
                        <div class="col-md-4 mt-5 img-contain">
                            <img src="ToucanParadise_LobbyIcon.jpg" class="img-fluid image mx-auto d-block" alt="img9">
                            <div class="middle">
                                <form action="" method="POST">
                                <button type="submit" class="btn btn-sm text" name=""><img src="layout/login.png" class="img-fluid" style="height:30; width:90"></button>
                                <!--<button type="submit" class="btn btn-sm text" name=""><img src="layout/play.png" class="img-fluid" style="height:60; width:90"></button>-->
                                </form>
                            </div>    
                        </div>
                        <div class="col-md-4 mt-5 img-contain">
                            <img src="REDNECK REBEL_LobbyIcon.jpg" class="img-fluid image mx-auto d-block" alt="img3">
                            <div class="middle">
                                <form action="" method="POST">
                                <!--<button type="submit" class="btn btn-sm text" name=""><img src="layout/login.png" class="img-fluid" style="height:30; width:90"></button>-->
                                <button type="submit" class="btn btn-sm text" name=""><img src="layout/play.png" class="img-fluid" style="height:60; width:90"></button>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-4 mt-5 img-contain">
                            <img src="RichBaron_Lobby_Icon.jpg" class="img-fluid image mx-auto d-block" alt="img4">    
                            <div class="middle">
                                <form action="" method="POST">
                                <!--<button type="submit" class="btn btn-sm text" name=""><img src="layout/login.png" class="img-fluid" style="height:30; width:90"></button>-->
                                <button type="submit" class="btn btn-sm text" name=""><img src="layout/play.png" class="img-fluid" style="height:60; width:90"></button>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-4 mt-5 img-contain">
                            <img src="Strike N Gold_Lobby_Icon.jpg" class="img-fluid image mx-auto d-block" alt="img7">
                            <div class="middle">
                                <form action="" method="POST">
                                <!--<button type="submit" class="btn btn-sm text" name=""><img src="layout/login.png" class="img-fluid" style="height:30; width:90"></button>-->
                                <button type="submit" class="btn btn-sm text" name=""><img src="layout/play.png" class="img-fluid" style="height:60; width:90"></button>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-4 mt-5 img-contain">          
                            <img src="SunSetTreasures_LobbyIcon.jpg" class="img-fluid image mx-auto d-block" alt="img8">  
                            <div class="middle">
                                <form action="" method="POST">
                                <!--<button type="submit" class="btn btn-sm text" name=""><img src="layout/login.png" class="img-fluid" style="height:30; width:90"></button>-->
                                <button type="submit" class="btn btn-sm text" name=""><img src="layout/play.png" class="img-fluid" style="height:60; width:90"></button>
                                </form>
                            </div>    
                        </div>
                    </div>
                </div>

                <div class="carousel-item">
                    <div class="row">
                        <div class="col-md-4 mt-5 img-contain">
                            <img src="Riches of the God_384x216.jpg" class="img-fluid image mx-auto d-block" alt="img5">
                            <div class="middle">
                                <form action="" method="POST">
                                <!--<button type="submit" class="btn btn-sm text" name=""><img src="layout/login.png" class="img-fluid" style="height:30; width:90"></button>-->
                                <button type="submit" class="btn btn-sm text" name=""><img src="layout/play.png" class="img-fluid" style="height:60; width:90"></button>
                                </form>
                            </div>      
                        </div>
                        <div class="col-md-4 mt-5 img-contain">
                            <img src="SeaGÇÖKing Treasures_384x216.jpg" class="img-fluid image mx-auto d-block" alt="img6">
                            <div class="middle">
                                <form action="" method="POST">
                                <!--<button type="submit" class="btn btn-sm text" name=""><img src="layout/login.png" class="img-fluid" style="height:30; width:90"></button>-->
                                <button type="submit" class="btn btn-sm text" name=""><img src="layout/play.png" class="img-fluid" style="height:60; width:90"></button>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-4 mt-5 img-contain">
                            <img src="Strike N Gold_Lobby_Icon.jpg" class="img-fluid image mx-auto d-block" alt="img7">  
                            <div class="middle">
                                <form action="" method="POST">
                                <!--<button type="submit" class="btn btn-sm text" name=""><img src="layout/login.png" class="img-fluid" style="height:30; width:90"></button>-->
                                <button type="submit" class="btn btn-sm text" name=""><img src="layout/play.png" class="img-fluid" style="height:60; width:90"></button>
                                </form>
                            </div>  
                        </div>
                        <div class="col-md-4 mt-5 img-contain">
                            <img src="SunSetTreasures_LobbyIcon.jpg" class="img-fluid image mx-auto d-block" alt="img8">
                            <div class="middle">
                                <form action="" method="POST">
                                <!--<button type="submit" class="btn btn-sm text" name=""><img src="layout/login.png" class="img-fluid" style="height:30; width:90"></button>-->
                                <button type="submit" class="btn btn-sm text" name=""><img src="layout/play.png" class="img-fluid" style="height:60; width:90"></button>
                                </form>
                            </div>    
                        </div>
                        <div class="col-md-4 mt-5 img-contain">
                            <img src="REDNECK REBEL_LobbyIcon.jpg" class="img-fluid image mx-auto d-block" alt="img3">  
                            <div class="middle">
                                <form action="" method="POST">
                                <!--<button type="submit" class="btn btn-sm text" name=""><img src="layout/login.png" class="img-fluid" style="height:30; width:90"></button>-->
                                <button type="submit" class="btn btn-sm text" name=""><img src="layout/play.png" class="img-fluid" style="height:60; width:90"></button>
                                </form>
                            </div>  
                        </div>
                        <div class="col-md-4 mt-5 img-contain">  
                            <img src="RichBaron_Lobby_Icon.jpg" class="img-fluid image mx-auto d-block" alt="img4">
                            <div class="middle">
                                <form action="" method="POST">
                                <!--<button type="submit" class="btn btn-sm text" name=""><img src="layout/login.png" class="img-fluid" style="height:30; width:90"></button>-->
                                <button type="submit" class="btn btn-sm text" name=""><img src="layout/play.png" class="img-fluid" style="height:60; width:90"></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-1 col-md-1">
        <a class="carousel-control-next" href="#blogCarousel" data-slide="next">
    <img src="{{ url('playerapp/rightarrow.png') }}" class="img-fluid"  style="margin-right:100;height: 70px;">
  </a>
    </div>
</div>
</div>
<div class="container-fluid footer-container">
            <div>
                <img src="{{ url('playerapp/footerborder.png') }}" class="img-fluid" style="margin-bottom: -67px;">
                <a href="#" data-toggle="modal" data-target="#myModal"><img src="{{ url('playerapp/login.png') }}" id="footerbutton" class="img-fluid"></a>
                <!--<a href="#"><img src="layout/logout.png" class="img-fluid" id="footerbutton"></a>-->
            </div>
            <div class="modal" id="myModal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header text-light">
                            <h4 class="modal-title">Enter your credentials to play
                            </h4>
                            <button type="button" class="close text-light" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body mt-5">
                            <div class="col-lg-12">
                                <form method="POST" action="" id="login-form" class=" form-inline">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="user" placeholder="User name" value="" required="">
                                    </div>
                                    <div class="form-group ml-2">
                                        <input type="password" class="form-control" name="otp" Placeholder="Enter OTP" value="" required="">
                                    </div>

                                    <div class="form-group mt-5 mx-auto">
                                        <button type="submit" class="btn btn-success btn-sm btn-block" id="loginbtn">
                                            <i class="fa fa-btn fa-sign-in"></i> Login
                                        </button>
                                    </div>
                                </form> 
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
@endsection
