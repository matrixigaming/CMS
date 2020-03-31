<div class="header">
    <h3>about <button class="btn btn-defalut strock black pull-right edit about">Edit</button> <span class="header-edit-box about-btn"><button class="btn btn-defalut block edit " onclick="updateAgentAbout();">Save</button> <button class="btn btn-defalut strock black agent-about Cancel">Cancel</button></span></h3>
</div>

<div class="box-open edit-about">
    <div id="agentAbout">
        {!! $user_detail['overview'] !!}
    </div>
    <br />

    @if(strlen($user_detail['overview']) > 300)
        <button class="btn btn-defalut palin"><span class="plus"></span>SHOW MORE</button>
    @endif
</div>

<div class="box-open editor about-edit">
    {!! Form::open(['name' => 'frmAgentAbout', 'id' => 'frmAgentAbout']) !!}
        @php $overview = $user_detail['overview']; @endphp
        {!! Form::text('profile_agent_about', $overview, ['id' => 'profile_agent_about']) !!}
    {!! Form::close() !!}
</div>

@push('scripts')
{!! Helper::renderTextEditor('profile_agent_about') !!}
<script type="text/javascript">
function updateAgentAbout(){
    tinyMCE.triggerSave();
    var profile_agent_about = $('#profile_agent_about').val();
    var param = $('#frmAgentAbout').serialize();

    $.ajax({
        type: 'POST',
        url: 'profile/update-agent-about',
        data: param,
        dataType: 'JSON',
        success: function(msgdata){
            $.unblockUI();

            if(msgdata.status == 'success'){
                 $('#agentAbout').html(profile_agent_about);
                 $('.header-edit-box.about-btn').css('display', 'none');
                 $('.box-open.edit-about').slideDown();
                 $('.box-open.editor.about-edit').slideUp();
                 $('.property-details-page .detail-tabs .header .btn.about').css('display', 'block');

                 loadblockUI(msgdata.message);
            }else{
                loadblockUI('your changes have not been updated successfully. please try again!');
            }
            setTimeout('$.unblockUI()', '3000');
        },
        beforeSend: function(){
            loadblockUI('Loading...');
        }
    });
}
</script>
@endpush
