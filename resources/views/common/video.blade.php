<div id="divLoginForm" class="signInCont-popup signinBox">
    <div class="modal-header video-popup">
        <h3>{{ $videotitle }}</h3>
        <a onclick="self.parent.tb_remove();" style="cursor: pointer; color: #FFFFFF; text-decoration: none; float: right;"><div class="og-close">x</div></a>
    </div>
    <div class="modal-body">
        <div style="text-align: center;">
            @if(isset($videohtml) && !empty($videohtml))
                {!! $videohtml !!}
            @endif
        </div>
    </div>
</div>
<style type="text/css">
iframe { height: 295px; }
#TB_title { display: none; }
.modal-header.video-popup > h3 { text-transform: none; }
</style>
