<div class="agent-banner">
    <div class="inner"> 
        <div class="col-lg-12">
            <div class="row profile-detail-box address">
                <div class="right">
                    <button class="btn btn-defalut strock black pull-right edit address-box">Edit</button>
                </div>
                <div class="left">
                    <div id="crop-avatar">
                        <div class="avatar-view user-img" title=''>
                            @if($user_default_images)
                                @php
                                    $imagePath = $user_default_images['image_path'];
                                    $path_parts = pathinfo($imagePath);
                                    $filename = $path_parts['dirname'] . '/' . $path_parts['filename'] . '_th' . '.' . $path_parts['extension'];
                                    $filename = file_exists($filename) ? $filename : $path_parts['dirname'].'/'.$path_parts['filename'].'.'.$path_parts['extension'];
                                @endphp
                                <i><img id="avatarImage" src="{{ url($filename) }}" /></i>
                            @else
                                <i><img id="avatarImage" src="{{ url('frontend/images/profile-no-image.jpg') }}" /></i>
                            @endif
                        </div>
                        <div class="avatar-view-popup">
                            <h4 class="oH2Low">You need a photo!</h4>
                            <p>Freelancers with a friendly, professional-looking portrait are hired <b>5 times more often</b> than those without a photo.</p>
                            <a class="oAddLink" id="avatarImage" href="#">Add a photo now</a>
                        </div>

                        <div class="modal" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    {!! Form::open(['action' => 'ProfileController@userphotoupload', 'files' => true, 'class' => 'avatar-form']) !!}
                                        <div class="modal-header">
                                            <button type="button" class="close-btn" data-dismiss="modal"> X </button>
                                            <h4 class="modal-title" id="avatar-modal-label">Profile Photo</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="avatar-body">
                                                <div class="avatar-upload">
                                                    {!! Form::hidden('avatar_src', '', ['class' => 'avatar-src']) !!}
                                                    {!! Form::hidden('avatar_data', '', ['class' => 'avatar-data']) !!}
                                                    {!! Form::label('avatarInput', 'Local upload', ['class' => 'hide']) !!}
                                                    {!! Form::file('avatar_file', ['class' => 'avatar-input', 'id' => 'avatarInput']) !!}
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-9">
                                                        <div class="avatar-wrapper"></div>
                                                        <p>Drag frame to adjust photo.</p>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <h5>YOUR PROFILE PHOTO</h5>
                                                        <div class="avatar-preview preview-lg"></div>
                                                        <div class="avatar-preview preview-md hide"></div>
                                                        <div class="avatar-preview preview-sm hide"></div>
                                                        <div class="col-lg-12">
                                                            {!! Form::submit('Save Image', ['class' => 'btn btn-primary btn-block avatar-save']) !!}
                                                            {!! Form::button('Cancel', ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) !!}
                                                        </div>
                                                        <p>Must be an actual picture of you! Logos, clip-art, group pictures, and digitally-altered images not allowed. The picture needs to have a minimum size of 500 (W)/ 500 (H) pixels.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    {!! Form::close() !!}
                                </div>
                                <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
                            </div>
                        </div>
                    </div>

                    <div class="details" id="userInfo">
                        <h3>@if($user_role['role_id'] == 3) 
                            {{ $user_detail['agency_name'] }}
                            @else
                            {{ $first_name }} {{ $last_name }}
                        @endif</h3>
                        <p><!--Licensed Real Estate-->
                            {{ $user_detail['street_address_1'] }}<br>
                            @if($user_detail['street_address_2'])
                                {{ $user_detail['street_address_2'] }}<br>
                            @endif
                            @if(isset($user_detail['destination']['name']))
                                {{ $user_detail['destination']['name'] }}, 
                            @endif
                            {{ $user_detail['state']['state_name'] }} {{ $user_detail['zip_code'] }}
                            @if($user_detail['country']['id'] != 232)
                                {{ $user_detail['country']['name'] }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="editor address-editor" id="divAgentInfo">
            {!! Form::open(['name' => 'frmAgentInfo', 'id' => 'frmAgentInfo']) !!}
                <div class="box left">
                    {!! Form::hidden('profile_agent_latitude', $user_detail['latitude'], ['id' => 'profile_agent_latitude']) !!}
                    {!! Form::hidden('profile_agent_longitude', $user_detail['longitude'], ['id' => 'profile_agent_longitude']) !!}
                    
                    @if($user_role['role_id'] == 3)
                        <p>{!! Form::label('', 'Email') !!}</p>{!! Form::text('profile_agency_email', $email, ['id' => 'profile_agency_email', 'disabled' => 'true']) !!}<br>
                        <p>{!! Form::label('', 'Name') !!}</p>{!! Form::text('profile_agency_name', $user_detail['agency_name'], ['id' => 'profile_agency_name', 'placeholder' => 'Name']) !!}<br>
                    @else
                    <p>{!! Form::label('', 'First Name') !!}</p>{!! Form::text('profile_agent_first_name', $last_name, ['id' => 'profile_agent_first_name']) !!}<br>
                    <p>{!! Form::label('', 'Last Name') !!}</p>{!! Form::text('profile_agent_last_name', $first_name, ['id' => 'profile_agent_last_name']) !!}<br>
                    @endif
                    <p>{!! Form::label('', 'Street Address 1') !!}</p>{!! Form::text('profile_agent_street_address_1', $user_detail['street_address_1'], ['id' => 'profile_agent_street_address_1']) !!}<br>
                    <p>{!! Form::label('', 'Street Address 2') !!}</p>{!! Form::text('profile_agent_street_address_2', $user_detail['street_address_2'], ['id' => 'profile_agent_street_address_2']) !!}<br>
                    <p>{!! Form::label('', 'Country') !!}</p>
                    <span class="select">
                        @php $countriesValue = $countryStates = $statesValues = $stateDestinations = $destinationsValues = []; @endphp
                        @if($user_detail['country_id'])
                            @php $defaultcountry = $user_detail['country_id']; @endphp
                        @else
                            @php $defaultcountry = '232'; @endphp
                        @endif
                        @foreach($countries as $country)
                            @php $countriesValue[$country['id']] = $country['name']; @endphp
                            @if($country['id'] == $defaultcountry)
                                @php $countryStates = isset($country['states']) && !empty($country['states']) ? $country['states'] : []; @endphp
                            @endif
                        @endforeach
                        {!! Form::select('profile_agent_country', $countriesValue, $defaultcountry, ['placeholder' => ' - select country - ', 'id' => 'profile_agent_country', 'onchange' => 'show_hide_state_region(this.value);', 'class' => 'select required']) !!}
                    </span><br>
                    <div class="clearfix"></div>
                    @if(isset($user_detail['state_id']))
                        @php $defaultstate = $user_detail['state_id']; @endphp
                    @else
                        @php $defaultstate = null; @endphp
                    @endif
                    @foreach($countryStates as $state)
                        @php $statesValues[$state['id']] = $state['state_name']; @endphp
                    @endforeach
                    <p>{!! Form::label('', 'State') !!}</p>
                    <span class="select">
                        {!! Form::select('profile_agent_state', $statesValues, $defaultstate, ['placeholder' => ' - select state - ', 'id' => 'profile_agent_state', 'style' => 'z-index: 10;', 'class' => 'select required']) !!}
                    </span><br>
                    <div class="clearfix"></div>
                    <p>{!! Form::label('', 'City') !!}</p>
                    <span class="select">
                        @if($user_detail['destination_id'])
                            @php $defaultdestination = $user_detail['destination_id']; @endphp
                        @else
                            @php $defaultdestination = null; @endphp
                        @endif
                        @foreach($states as $state)
                            @if($state['id'] == $defaultstate)
                                @php $stateDestinations = isset($state['destinations']) && !empty($state['destinations']) ? $state['destinations'] : []; @endphp
                            @endif
                        @endforeach
                        @foreach($stateDestinations as $destination)
                            @php $destinationsValues[$destination['id']] = $destination['name']; @endphp
                        @endforeach
                        {!! Form::select('profile_agent_city', $destinationsValues, $defaultdestination, ['placeholder' => ' - select city - ', 'id' => 'profile_agent_city', 'class' => 'select required']) !!}
                    </span><br>
                    <p>{!! Form::label('', 'Zip Code') !!}</p>
                    {!! Form::text('profile_agent_zip_code', $user_detail['zip_code'], ['id' => 'profile_agent_zip_code']) !!}
                    <br>
                    <p>{!! Form::label('', 'Phone Number') !!}</p>
                    {!! Form::text('profile_agent_phone_number', $user_detail['phone'], ['id' => 'profile_agent_phone_number']) !!}
                    <br>
                    <p>{!! Form::label('', 'New Password') !!}</p>
                    {!! Form::password('profile_agent_password', null, ['id' => 'profile_agent_password']) !!}
                </div>
            {!! Form::close() !!}

            <div class="right">
                <button class="btn btn-defalut block edit " onclick="updateAgentInfo();">Save</button> <button class="btn btn-defalut strock black pull-right agent-info Cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">
$('select[name=\'profile_agent_country\']').on('change', function() {
        if(this.value != '' && this.value != undefined){
            $.ajax({
                    url: '/country/states/' + this.value,
                    dataType: 'html',
                    beforeSend: function() {
                            $('select[name=\'profile_agent_country\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
                    },
                    complete: function() {
                            $('.fa-spin').remove();
                    },
                    success: function(json) {
                            $('select[name=\'profile_agent_state\']').html(json);

                            
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
            });
        }
});

$('select[name=\'profile_agent_state\']').on('change', function() {
        if(this.value != '' && this.value != undefined){
            $.ajax({
                    url: '/state/cities/' + this.value,
                    dataType: 'html',
                    beforeSend: function() {
                            $('select[name=\'profile_agent_state\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
                    },
                    complete: function() {
                            $('.fa-spin').remove();
                    },
                    success: function(json) {
                            $('select[name=\'profile_agent_city\']').html(json);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
            });
        }
});

// $('select[name=\'profile_agent_country\']').trigger('change');

function updateAgentInfo(){
    var street_address_1 = $("#profile_agent_street_address_1").val();
    var city = $("#profile_agent_city").val();
    var zip_code = $("#profile_agent_zip_code").val();
    var country = $("#profile_agent_country").val();

    if(country == 232){
        var state = $("#profile_agent_state").val();
    }else{
        var state = '';
    }

    var param = street_address_1 + " " + city + " " + state + " " + zip_code + "," + country;

    $.ajax({
        type: "POST",
        url: "http://maps.google.com/maps/api/geocode/json?address=" + param + "&sensor=false&region=" + country,
        data: param,
        async: false,
        success: function(msg){
            if(msg.results != ''){
                $("#profile_agent_latitude").val(msg.results[0].geometry.location.lat);
                $("#profile_agent_longitude").val(msg.results[0].geometry.location.lng);

                var param = $('#frmAgentInfo').serialize();
                $.ajax({
                    type: 'POST',
                    url: '/profile/update-agent-info',
                    data: param,
                    dataType: 'JSON',
                    success: function(msgdata){
                       $.unblockUI()          
                       if(msgdata.status == 'success'){
                            var data = msgdata.data;
                            var user_info = '<h3>' + data.first_name + ' ' + data.last_name + '</h3>';
                                user_info += '<p>' + data.user_detail.street_address_1 + '<br>';

                            if(data.user_detail.street_address_2)
                                user_info += data.user_detail.street_address_2 + '<br>';

                            if(data.user_detail.destination != null && data.user_detail.destination.name != '')
                                user_info += data.user_detail.destination.name + ', ';
                            
                            if(data.user_detail.state != null && data.user_detail.state.state_name != '')
                                user_info += data.user_detail.state.state_name + ' ';
                            
                            user_info +=  data.user_detail.zip_code + '<br>';
                            user_info += data.user_detail.country.name + '</p>';

                            $('#userInfo').html(user_info);
                            $('.profile-detail-box.address').slideDown();
                            $('.address-editor').slideUp();
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
        }
    });
}
</script>
@endpush