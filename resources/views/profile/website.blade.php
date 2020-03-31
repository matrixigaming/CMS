<h3>Links
    <button style="@if($user_detail['website'] == '') {{ 'display: none' }} @endif" id="btnAgentWebsiteEdit" class="btn btn-defalut strock black pull-right edit website-edit" onclick="show_hide_agent_website()">Edit</button>
    <button style="@if($user_detail['website'] != '') {{ 'display: none' }} @endif" id="btnAgentWebsiteAdd" class="btn btn-defalut strock black pull-right edit website-edit" onclick="show_hide_agent_website()">Add</button>
    <button id="btnAgentWebsiteSave" class="btn btn-defalut block pull-right save website-save" onclick="updateAgentWebsite();">Save</button>
</h3>

<label id="lblAgentWebsite" style="@if($user_detail['website'] == '') {{ 'display: none' }} @endif">
    <a target="_blank" href="{{ $user_detail['website'] }}">Agent Website</a>
</label>

{!! Form::open(['name' => 'frmAgentWebsite', 'id' => 'frmAgentWebsite']) !!}
<div id="divAgentWebsite" style="display: none;">
    {!! Form::text('txt_agent_website', $user_detail['website'], ['id' => 'txt_agent_website']) !!}
    <div class="col-lg-12">
        <div class="row">
            <button type="button" class="btn btn-defalut strock black Cancel website-cancel" data-dismiss="modal" onclick="show_hide_agent_website();">Cancel</button>
        </div>
    </div>
</div>
{!! Form::close() !!}
<br/>

@push('scripts')
<script type="text/javascript">
function show_hide_agent_website() {
    if($('#divAgentWebsite').css('display') == 'none'){
        $('#divAgentWebsite').show();
        $('#btnAgentWebsiteSave').show();
        $('#btnAgentWebsiteEdit').hide();
        $('#btnAgentWebsiteAdd').hide();
        $('#lblAgentWebsite').hide();
    }else{
        $('#btnAgentWebsiteSave').hide();
        $('#divAgentWebsite').hide();
        $('#btnAgentWebsiteEdit').show();
        $('#lblAgentWebsite').show();
    }
}

function updateAgentWebsite(){
    var agent_website = $('#txt_agent_website').val();
    var param = $('#frmAgentWebsite').serialize();
    $.ajax({
        type: 'POST',
        url: '/profile/update-agent-website',
        data: param,
        dataType: 'JSON',
        success: function(msgdata){
            $.unblockUI();

            if(msgdata.status == 'success'){
                if(agent_website == ''){
                    $('#btnAgentWebsiteEdit').hide();
                    $('#btnAgentWebsiteAdd').show();
                }else{
                    $('#btnAgentWebsiteEdit').show();
                    $('#btnAgentWebsiteAdd').hide();
                    $('#lblAgentWebsite').html('<a href="'+agent_website+'" target="_blank">Agent Website</a>');
                    $('#lblAgentWebsite').show();
                }
                $('#divAgentWebsite').hide();
                $('#btnAgentWebsiteSave').hide();

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
