<h3>Social 
    <button id="btnAgentSocialEdit" style="@if(count($user_social_media) > 0) {{ 'display: block' }} @else {{ 'display: none' }} @endif" class="btn btn-defalut strock black pull-right social-edit edit" >Edit</button>
    <button id="btnAgentSocialSave" type="submit" class="btn btn-defalut block pull-right save social-save" onclick="updateAgentSocial();">Save</button>
</h3>
<div class="agency-socials no-border no-padding text-left" id="divAgentSocial">
    @if(count($user_social_media) > 0)
        @foreach($user_social_media as $socialagent)
            @php $class = Helper::getSocialClass($socialagent); @endphp
            <a href="{{ $socialagent['link'] }}" target="_blank" class="{{$class }} agent_social_{{ $socialagent['social'] }}"></a>
        @endforeach
    @endif
</div>

{!! Form::open(['name' => 'frmAgentSocialEdit', 'id' => 'frmAgentSocialEdit']) !!}
<div id="divEditFormAgentSocial" class="box-open agency-socials edit">
    @if(count($user_social_media) > 0)
        @foreach($user_social_media as $socialagent)
            @php $class = Helper::getSocialClass($socialagent); @endphp
            <span class="agent_social_{{ $socialagent['social'] }}"><i class="{{ $class }}"></i>
                {!! Form::text('txt_social_' . $socialagent['social'], $socialagent['link'], ['id' => 'social_link', 'data-socialid' => $socialagent['id'], 'class' => 'txt_social']) !!}
                <span class="delete" onclick="deleteConfirmSocial('{{ $socialagent['id'] }}');"></span><br>
            </span>
        @endforeach
    @endif
    <div class="text-right">
      <button type="button" onclick="show_hide_agent_social();" class="btn btn-defalut strock black social-cancel Cancel" data-dismiss="modal">Cancel</button>
    </div>
</div>
{!! Form::close() !!}

<div id="divAddFormAgentSocial" style="display: none;">
   <span class="agent_social_{{-- socialagent['social'] --}}">
        {!! Form::open(['name' => 'frmAgentSocialAdd', 'id' => 'frmAgentSocialAdd']) !!}
            <span class="select">
                @php $socialValues = []; @endphp
                @foreach(Helper::getAllSocial() as $social)
                    @php $socialValues[$social] = $social; @endphp
                @endforeach
                {!! Form::select('social', $socialValues, null, ['placeholder' => ' - select country - ', 'id' => 'social', 'onchange' => 'show_hide_agency_state_region(this.value);', 'class' => 'required']) !!}
            </span>
            {!! Form::text('social_link', '', ['id' => 'social_link', 'placeholder' => 'enter social channel URL', 'class' => 'txt_social']) !!}
        {!! Form::close() !!}
   </span>
    <div class="text-right">
        <button type="button" class="btn btn-defalut strock black" data-dismiss="modal" onclick="addAgentSocial()">Add</button>
        <button type="button" class="btn btn-defalut strock black" data-dismiss="modal" onclick="$('#divAddFormAgentSocial').hide();$('#divAgentSocial').show();$('#btnAgentSocialEdit').show();">Cancel</button>
    </div><br>
</div>

<a href="javascript:void(0);" onClick="showAddAgentSocial();">+ add a social channel</a>

@push('scripts')
<script type="text/javascript">
function updateAgentSocial() {
    var social_links = [];
    $(".txt_social").each(function() {
        var socials = {};
        socials.id = $(this).attr('data-socialid');
        socials.link = $(this).val();
        social_links.push(socials);
    });

    var param = social_links;
    $.ajax({
        type: 'POST',
        url: '/profile/update-agent-social',
        data: { 'data': param, '_token': $('input[name=_token]').val() },
        dataType: 'JSON',
        success: function(msgdata){
            $.unblockUI();

            if(msgdata.status == 'success'){
                $('#divAgentSocial').html(msgdata.social_icons);
                $('#divEditFormAgentSocial').html(msgdata.social_edit);
                $('#divEditFormAgentSocial').hide();
                $('#divAgentSocial').show();
                $('#btnAgentSocialEdit').show();
                $('#btnAgentSocialAdd').hide();
                loadblockUI(msgdata.message);
            }else{
                loadblockUI('your changes have not been updated successfully. please try again!');
            }
            setTimeout('$.unblockUI()','3000');
        },
        beforeSend: function(){
            loadblockUI('Loading...');
        }
    });
}

function deleteConfirmSocial(id) {
    loadblockUI('Are you sure you want to delete the following social channel? <br><br><b>'+name+'</b></b><br><br><div class=\"row\"><button type=\"submit\" class=\"btn btn-defalut block pull-right save\" onclick=\"deleteAgentSocial('+id+');\">Delete Channel</button><a href=\"javascript:void(0)\" onclick=\"$.unblockUI();\">Cancel</a></div>');
}

function deleteAgentSocial(socialid) {
    $.unblockUI();
    var param = 'socialid=' + socialid + '&_token=' + $('input[name=_token]').val();
    $.ajax({
        type: 'POST',
        url: '/profile/delete-agent-social',
        data: param,
        dataType: 'JSON',
        success: function(msgdata){
            $.unblockUI();

            if(msgdata.status == 'success'){
                $('#divAgentSocial').html(msgdata.social_icons);
                $('#divEditFormAgentSocial').hide();
                $('#divEditFormAgentSocial').html(msgdata.social_edit);
                $('#divAgentSocial').show();
                $('#btnAgentSocialEdit').show();
                $('#btnAgentSocialAdd').hide();
                $('#btnAgentSocialSave').hide();
                $('.agent_social_'+socialid).remove();
                loadblockUI(msgdata.message);
            }else{
                loadblockUI('your changes have not been updated successfully. please try again!');
            }
            setTimeout('$.unblockUI()','3000');
        },
        beforeSend: function(){
            loadblockUI('Loading...');
        }
    });
}

function show_hide_agent_social() {
    if($('#divEditFormAgentSocial').css('display')=='none'){
        $('#divEditFormAgentSocial').show();
        $('#divAgentSocial').hide();
        $('#btnAgentSocialSave').show();
        $('#btnAgentSocialEdit').hide();
        $('.social-save').hide();
    }else{
        $('#divEditFormAgentSocial').hide();
        $('#divAgentSocial').show();
        $('#btnAgentSocialSave').hide();
        if(jQuery.trim($('#divAgentSocial').html())==''){
            $('#btnAgentSocialEdit').hide();
        }else{
            $('#btnAgentSocialAdd').hide();
            $('#btnAgentSocialEdit').show();
        }
    }
}

function showAddAgentSocial() {
    $('#divAddFormAgentSocial').show();
    $('#divEditFormAgentSocial').hide();
    $('#btnAgentSocialEdit').hide();
}

function addAgentSocial() {
    var social_link = $('#social_link').val();
    var social = $('#social').val();
    var param = $('#frmAgentSocialAdd').serialize();
    $.ajax({
        type: 'POST',
        url: '/profile/add-agent-social',
        data: param,
        dataType: 'JSON',
        success: function(msgdata){
            $.unblockUI();

            if(msgdata.status == 'success'){
                $('#divAgentSocial').html(msgdata.social_icons);
                $('#divAddFormAgentSocial').hide();
                $('#divEditFormAgentSocial').html(msgdata.social_edit);
                $('#divAgentSocial').show();
                $('#btnAgentSocialEdit').show();
                loadblockUI(msgdata.message);
            }else{
                loadblockUI('your changes have not been updated successfully. please try again!');
            }
            setTimeout('$.unblockUI()','3000');
        },
        beforeSend: function(){
            loadblockUI('Loading...');
        }
    });
}
</script>
@endpush
