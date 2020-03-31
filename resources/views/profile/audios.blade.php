<div class="header">
    <h3>Audio <button class="btn btn-defalut strock black pull-right addaudio">Add Audio</button> <span class="header-edit-box audio-save-cancel-btn pull-right" style="display: none;"><button class="btn btn-defalut saveaudio " onclick="saveAudio()">Save</button> <button class="btn btn-defalut strock black cancelaudio">Cancel</button></span></h3>
</div>

<div class="box-open audio" id="divAgentAudio">
    @if($user_detail['audio'])
        {{ $user_detail['audio'] }}
    @else
        <div class="col-lg-12  text-center">You currently have no audio.</div>
    @endif
</div>

<div class="box-open editor audio-add-form">
    {!! Form::open(['name' => 'frmAgentAudio', 'id' => 'frmAgentAudio']) !!}
        <div class="box left">
            {!! Form::file('profile_agent_audio', ['id' => 'profile_agent_audio', 'style' => 'height: 100%;']) !!}<br>
        </div>
    {!! Form::close() !!}
</div>

@push('scripts')
<script type="text/javascript">
function saveAudio(){
    var profile_agent_audio = $('#profile_agent_audio').val();
    if(profile_agent_audio){
        var frmAgentAudio = $('#frmAgentAudio')[0];
        var data = new FormData(frmAgentAudio);

        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            enctype: 'multipart/form-data',
            url: '/profile/addagentaudio',
            processData: false, 
            contentType: false,
            cache: false,
            data: data,
            success: function(msg){
                $('.audio-add-form').hide();
                $('#divAgentAudio').show();
                $('.btn.addaudio').show();
                $('.audio-save-cancel-btn').hide();
                $('#divAgentAudio').html(msg['file']);
            }
        });
    }
}

$('.btn.addaudio').click(function(){
    $('.audio-add-form').show();
    $('#divAgentAudio').hide();
    $('.btn.addaudio').hide();
    $('.audio-save-cancel-btn').show();
});

$('.cancelaudio').click(function(){
    $('.audio-add-form').hide();
    $('#divAgentAudio').show();
    $('.btn.addaudio').show();
    $('.audio-save-cancel-btn').hide();
});
</script>
@endpush
