<div class="detail-tabs">
    <div class="header">
        <h3>Agents
            <button id="btnAgencySave1" class="btn btn-defalut block pull-right save" onclick="createAgentInfo();" style="display: block;" >Save</button>
        </h3>
    </div>
		
    <form name="frmAgency" id="frmAgency" enctype="multipart/form-data">
        <div id="divAgencyOption">
            <input type="hidden" id="hidden_agentid" name="hidden_agentid" value="">
            <input name="agency_agent_name" id="agency_agent_name"  type="text" class="required field-longs"  maxlength="255"/><br>
            <div class="divAutocomplete" style="display: none;" ></div>
            <div id="agency_agent_detail" style="display:none;padding:10px; background-color:transparent; text-align:left; border:#CCC 1px solid; color:#554743;margin:11px 0 7px 0px;width: 288px; line-height: 14px;"></div>
        </div>

        <div class="box-open contact edit" id="divAgencyAgentForm"  style="display: none;">
            <input type="hidden" id="profile_agency_agent_latitude" name="profile_agent_latitude" value="" />
            <input type="hidden" id="profile_agency_agent_longitude" name="profile_agent_longitude" value="" /><br>
            <input type="text" id="profile_agent_first_name" name="profile_agent_first_name" value=""  placeholder="Fisrt Name"/>
            <input type="text"  id="profile_agent_last_name" name="profile_agent_last_name"  value="" placeholder="Last Name" />
            <input type="text"  id="profile_agent_email" name="profile_agent_email"  value="" placeholder="Email" />
            <input type="password"  id="profile_agent_password" name="profile_agent_password"  value="" placeholder="Password" />
            <input type="text"  id="profile_agent_street_address_1" name="profile_agent_street_address_1" value="" placeholder="Street Address 1" /><br>
            <input type="text"  id="profile_agent_street_address_2" name="profile_agent_street_address_2" value="" placeholder="Street Address 2" /><br>
            <span class="select top-move">
                <select name="profile_agency_city" id="profile_agency_city" class="required">
                    <option value="" > - select city - </option>
                    @if(count($destinations) > 0)
                        @foreach($destinations as $city)
                            <option value="{{ $city['id'] }}" @if($city['id'] == $user_detail['destination_id']) {{ 'selected' }} @endif>{{ $city['name'] }}</option>
                        @endforeach
                    @endif
                </select>
            </span><br>
            <span class="select">
                <select name="profile_agent_country" id="profile_agency_country" class="required"  onchange="show_hide_state_region(this.value);">
                    @if(count($countries) > 0)
                        @foreach($countries as $country)
                            <option value="{{ $country['id'] }}" @if($country['id'] == 232) {{ 'selected' }} @endif>{{ $country['name'] }}</option>
                        @endforeach
                    @endif
                </select>
            </span><br>
            <span class="select" id="spanAgencyAgentState" style="@if($user_detail['country_id'] == 232 || $user_detail['country_id'] == '' || $user_detail['country'] == '0') {{ 'display: block;' }} @else {{ 'display: none;' }} @endif>
                <select name="profile_agency_agent_state" id="profile_agent_state" class="select required" style="z-index: 10; width: 100%;">
                    <option value="" > - select state - </option>
                    @if(count($states) > 0)
                        @foreach($states as $state)
                            <option value="{{ $state['id'] }}">{{ $state['state_name'] }}</option>
                        @endforeach
                    @endif
                </select>
            </span>
            <input name="profile_agent_zip_code" id="profile_agent_zip_code" type="text" value="" placeholder="Zip Code">
            <input name="profile_agent_phone" id="profile_agent_phone" type="text" value="" placeholder="Phone">
            <input name="profile_agent_toll_free_phone" id="profile_agent_toll_free_phone" type="text" value="" placeholder="Toll Free Phone">
            <input name="profile_agent_fax" id="profile_agent_fax" type="text" value="" placeholder="Fax">
            <input name="profile_agent_website" id="profile_agent_website" type="text" value="" placeholder="Website">
            <input name="profile_agent_territories" id="profile_agent_territories" type="text" value="" placeholder="Territories">

            <div class="header-edit-box">
                <button type="button" class="btn btn-defalut strock black Cancel informo-cancel">Cancel</button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<style>
  .ui-autocomplete {
    max-height: 200px;
    overflow-y: auto;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
    width: 20% !important;
    border: 1px solid #000;
  }
  /* IE 6 doesn't support max-height
   * we use height instead, but this forces the menu to always be this tall
   */
  * html .ui-autocomplete {
    height: 200px;
  }
</style>
<script type="text/javascript">
function createAgentInfo(){ 
    if($('#hidden_agentid').val()!=''){
        var data = {agent_id: $('#hidden_agentid').val(), _token: '{{ csrf_token() }}'}
        jQuery.ajax({
            type: 'POST',
            url:baseurl+'/profile/add-agent-to-agency',
            data: data,
            success: function(msgdata){
               $.unblockUI();

               if(msgdata.status == 'success'){
                   loadblockUI(msgdata.message); 
               }else{
                   loadblockUI('your changes have not been updated successfully. please try again!');
               }

               setTimeout('$.unblockUI()','3000');
               location.reload();
            },
            beforeSend: function(){
                loadblockUI('Loading...');
            }
        });
    }
}

$("#agency_agent_name" ).autocomplete({
    source: "/profile/searchagents",
    minLength: 1,
    select: function( event, ui ) {
        $("#hidden_agentid").val(ui.item.id);
        $("#agency_agent_detail").html(ui.item.detail).fadeIn();
    }
});
</script>
@endpush
