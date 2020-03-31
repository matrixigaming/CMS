<div class="header">
    <h3>Territories <button class="btn btn-defalut strock black pull-right edit territories">Edit</button> <span class="header-edit-box territories-btn"><button class="btn btn-defalut block edit " onclick="updateAgentTerritories()">Save</button> <button class="btn btn-defalut strock black Cancel territories-cancel">Cancel</button></span></h3>
</div>

<div class="box-open territories" id="divTerritories">
    @if($user_detail['territories'])
        <ol>
            @php $territories = explode(',', $user_detail['territories']); @endphp
            @if(count($territories) > 0)
                @foreach($territories as $territory)
                    <li>{{ $territory }}</li>
                @endforeach
            @endif
        </ol>
    @else
        <div class="col-lg-12  text-center">You currently have no territories available.</div>
    @endif
</div>

<div class="box-open editor territories-edit">
    {!! Form::open(['name' => 'frmAgentTerritories', 'id' => 'frmAgentTerritories']) !!}
        <div class="box left">
            {!! Form::text('profile_agent_territories', $user_detail['territories'], ['id' => 'profile_agent_territories', 'placeholder' => 'enter values separated by commas']) !!}
        </div>
    {!! Form::close() !!}
</div>

@push('scripts')
<script type="text/javascript">
function updateAgentTerritories(){
    var profile_agent_territories = $('#profile_agent_territories' ).val();
    var param = $('#frmAgentTerritories').serialize();
    jQuery.ajax({
        type: 'POST',
        url: '/profile/update-agent-territories',
        data: param,
        dataType: 'JSON',
        success: function(msgdata){
            $.unblockUI();

            if(msgdata.status == 'success'){
                $('#divTerritories').html(msgdata.territories);
                $('.header-edit-box.territories-btn').css('display', 'none');
                $('.box-open.territories').slideDown();
                $('.box-open.editor.territories-edit').slideUp();
                $('.property-details-page .detail-tabs .header .btn.territories').css('display', 'block');

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
