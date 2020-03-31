<div class="header">
    <h3>Active Properties <button class="btn btn-defalut strock black pull-right edit" onclick="window.location='{{ url('property/addproperty') }}'" >Add Property</button></h3>
</div>

<div class="box-open">
    <div class="row properties-tab view">
        <div class="carousel slide agents-list-slider" id="agents-active-list-slider">
            <div class="three-slider">
                @if(count($user_listing_active) > 0)
                    @foreach($user_listing_active as $key => $related_property)
                        
                            <div class="col-md-4 cover">
                                <div class="box">
                                    <div class="hover">
                                        <a href="{{ url('property/' . $related_property['id'] . '/' . urlencode(strtolower(str_replace([" ", "&", ",","'","?","/"], ["-", "and","","","","-"], $related_property['name'])))) }}"><button class="btn btn-defalut strock gold">view property</button></a>
                                    </div>
                                    <button class="btn btn-defalut reverse top">{{ $related_property['sale_type'] }}</button>
                                    @if($related_property['listing_default_image']['image_path'])
                                        @php
                                            $imagePath = $related_property['listing_default_image']['image_path'];
                                            $path_parts = pathinfo($imagePath);
                                            $filename = $path_parts['dirname'] . '/' . $path_parts['filename'] . '_th' . '.' . $path_parts['extension'];
                                            $filename = file_exists($filename) ? $filename : $path_parts['dirname'].'/'.$path_parts['filename'].'.'.$path_parts['extension'];
                                        @endphp
                                        <img class="img-responsive" src="{{ url($filename) }}" alt="Luxury Real Estate Search Properties - {{ $related_property['name'] }}">
                                    @else
                                        <img class="img-responsive" src="{{ url('uploads/listing/images/no-image_th.jpg') }}" alt="Luxury Real Estate Search Properties - {{ $related_property['name'] }}">
                                    @endif
                                </div>
                                <div class="col-lg-12 properties-details">
                                    <div class="row">
                                        <div class="top">
                                            @if($related_property['living_space'])
                                                {{ $related_property['living_space'] }} {{ $related_property['living_space_units'] }} | 
                                            @endif
                                            @if($related_property['bedrooms'])
                                                {{ $related_property['bedrooms'] }} beds | 
                                            @endif
                                            @php $bathrooms = $related_property['full_bathrooms'] + $related_property['three_fourth_bathrooms'] + $related_property['half_bathrooms']; @endphp
                                            @if($bathrooms)
                                                {{ $bathrooms }} baths
                                            @endif
                                        </div>
                                        <div class="bottom">
                                            <div class="details text-left">
                                                <p>{{ $related_property['destination']['name'] }}, {{ $related_property['state']['state_name'] }}</p>
                                                <a href="{{ url('property/' . $related_property['id'] . '/' . urlencode(strtolower(str_replace([" ", "&", ",","'","?","/"], ["-", "and","","","","-"], $related_property['name'])))) }}">{{ $related_property['name'] }}</a><br />
                                                <h4 class="base-color">
                                                    @if($related_property['price_display_option'] == 'display price')
                                                        {{ $related_property['currency'] }} {{ number_format($related_property['price']) }}
                                                        <dummy class="no-uppercase">
                                                            @if($related_property['sale_type'] == 'lease')
                                                                {{-- " / " . $related_property['lease_payment_name'] --}}
                                                            @endif
                                                        </dummy>
                                                    @else
                                                        {{ $related_property['price_display_option'] }}
                                                    @endif
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="like-box">
                                            <p>
                                                @if($related_property['uploader_info']['user_role']['role_id'] == 2)
                                                    {{ $related_property['uploader_info']['first_name'] }} {{ $related_property['uploader_info']['last_name'] }}
                                                @elseif($related_property['uploader_info']['user_role']['role_id'] == 3)
                                                    {{ $related_property['uploader_info']['user_detail']['agency_name'] }}
                                                @endif
                                                <span class="pull-right like-ico"></span>
                                            </p>
                                        </div>
                                        <div class="like-box">
                                            <button type="button" class="btn btn-defalut strock edit" onclick="window.location = '{{ url('property/editproperty/' . $related_property['id']) }}'"><i class="far fa-edit"></i></button>
                                            <!--<button type="button" onclick="deactivateConfirmProperty('{{ ucfirst($related_property['name']) }}','{{ $related_property['id'] }}');" class="btn btn-defalut strock edit"><i class="far fa-eye-slash"></i></button>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                    @endforeach
                @else
                    <div class="col-lg-12 text-center">You currently have no active properties.</div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(count($user_listing_inactive) > 0)
<div class="header">
    <h3>InActive Properties</h3>
</div>

<div class="box-open">
    <div class="row properties-tab view"> 
        <div class="carousel slide agents-list-slider" id="agents-inactive-list-slider">
            <div class="three-slider"  id="divInactiveProperties">
                @if(count($user_listing_inactive) > 0)
                    @foreach($user_listing_inactive as $key => $related_property)
                        <div class="col-md-4 cover">
                            <div class="box">
                                <div class="hover">
                                    <a href="{{ url('property/' . $related_property['id'] . '/' . urlencode(strtolower(str_replace([" ", "&", ",","'","?","/"], ["-", "and","","","","-"], $related_property['name'])))) }}"><button class="btn btn-defalut strock gold">view property</button></a>
                                </div>
                                <button class="btn btn-defalut reverse top">{{ $related_property['sale_type'] }}</button>
                                @if($related_property['listing_default_image']['image_path'])
                                    @php
                                        $imagePath = $related_property['listing_default_image']['image_path'];
                                        $path_parts = pathinfo($imagePath);
                                        $filename = $path_parts['dirname'] . '/' . $path_parts['filename'] . '_th' . '.' . $path_parts['extension'];
                                        $filename = file_exists($filename) ? $filename : $path_parts['dirname'].'/'.$path_parts['filename'].'.'.$path_parts['extension'];
                                    @endphp
                                    <img class="img-responsive" src="{{ url($filename) }}" alt="Luxury Real Estate Search Properties - {{ $related_property['name'] }}">
                                @else
                                    <img class="img-responsive" src="{{ url('uploads/listing/images/no-image_th.jpg') }}" alt="Luxury Real Estate Search Properties - {{ $related_property['name'] }}">
                                @endif
                            </div>
                            <div class="col-lg-12 properties-details">
                                <div class="row">
                                    <div class="top">
                                        @if($related_property['living_space'])
                                            {{ $related_property['living_space'] }} {{ $related_property['living_space_units'] }} | 
                                        @endif
                                        @if($related_property['bedrooms'])
                                            {{ $related_property['bedrooms'] }} beds | 
                                        @endif
                                        @php $bathrooms = $related_property['full_bathrooms'] + $related_property['three_fourth_bathrooms'] + $related_property['half_bathrooms']; @endphp
                                        @if($bathrooms)
                                            {{ $bathrooms }} baths
                                        @endif
                                    </div>
                                    <div class="bottom">
                                        <div class="details text-left">
                                            <p>{{ $related_property['destination']['name'] }}, {{ $related_property['state']['state_name'] }}</p>
                                            <a href="{{ url('property/' . $related_property['id'] . '/' . urlencode(strtolower(str_replace([" ", "&", ",","'","?","/"], ["-", "and","","","","-"], $related_property['name'])))) }}">{{ $related_property['name'] }}</a><br />
                                            <h4 class="base-color">
                                                @if($related_property['price_display_option'] == 'display price')
                                                    {{ $related_property['currency'] }} {{ number_format($related_property['price']) }}
                                                    <dummy class="no-uppercase">
                                                        @if($related_property['sale_type'] == 'lease')
                                                            {{-- " / " . $related_property['lease_payment_name'] --}}
                                                        @endif
                                                    </dummy>
                                                @else
                                                    {{ $related_property['price_display_option'] }}
                                                @endif
                                            </h4>
                                        </div>
                                    </div>

                                    <div class="like-box">
                                        <button type="button" class="btn btn-defalut strock edit" onclick="window.location = '{{ url('/property/editproperty/' . $related_property['id']) }}'"><i class="far fa-edit"></i></button>
                                        <!--<button type="button" class="btn btn-defalut strock edit" onclick="deletePropertyConfirm('{{ ucfirst(htmlentities(addslashes($related_property['name']))) }}','{{ $related_property['id'] }}');"><i class="far fa-trash-alt"></i></button>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-lg-12 text-center">You currently have no inactive properties.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<div class="header">
    <h3>Past Sales<button class="btn btn-defalut strock black pull-right edit" onclick="window.location='{{ url('property/addproperty/pastsales') }}'" >Add Property</button></h3>
</div>

<div class="box-open">
    <div class="row properties-tab view">
        <div class="carousel slide agents-list-slider" data-ride="carousel" data-type="multi" data-interval="0" id="agents-inactive-list-slider">
            <div class="carousel-inner">
                {{-- PASTSALES PROPERTIES --}}
            </div>
            @if(count($user_listing_inactive) > 3)
                <a class="carousel-control black left" href="#agents-inactive-list-slider" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                </a>
                <a class="carousel-control black right" href="#agents-inactive-list-slider" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                </a>
            @endif
        </div>
    </div>
</div>

@if(count($user_listing_inactive) > 0)
    @foreach($user_listing_inactive as $key => $related_property)
        <div id="inactiveModal{{ $related_property['id'] }}" class="modal inactive-property">
            <div class="modal-dialog">               
                <div class="modal-content">
                    <button type="button" class="close-btn" data-dismiss="modal" aria-hidden="true"></button>
                    <div class="modal-body">
                        <div>
                            <object type="text/html"
                                    data="{{ url('property-popup/' . $related_property['id'] . '/' . urlencode(strtolower(str_replace([" ", "&", ",","'","?","/"], ["-", "and","","","","-"], $related_property['name'])))) }}"
                                    width="100%" height="700px" style="overflow:auto;">
                            </object>
			</div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif

@push('scripts')
<script type="text/javascript">
function deletePropertyConfirm(name,id) {
    loadblockUI('<a onclick=\"$.unblockUI();\" class=\"close-btn\"></a>Are you sure you want to delete the following property? <br><br><b>'+name+'</b></b><br><br><div class=\"clearfix\"><button type=\"submit\" class=\"btn btn-defalut block pull-right save\" onclick=\"deleteProperty('+id+');\">Delete Property</button><a class=\"pull-left\" href=\"javascript:void(0)\" onclick=\"$.unblockUI();\" style=\"line-height: 32px\">cancel</a></div>');
}

threeslider();

function deleteProperty(ID) {
    $.unblockUI();
    var param = 'ID='+ID;
    $.ajax({
        type: 'POST',
        url:baseurl+'/profile/delete-property',
        data: param,
        success: function(msg){
            $.unblockUI()            
            var msgdata = jQuery.parseJSON( msg );
            if(msgdata.status=='success'){
                $('#divInactiveProperties').html(msgdata.property);
                loadblockUI(msgdata.status_msg);
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

function deactivateConfirmProperty(name,id) {
    loadblockUI('Are you sure you want to deactivate the following property? <br><br><b>'+name+'</b></b><br><br><div class=\"row\"><button type=\"submit\" class=\"btn btn-defalut block pull-right save\" onclick=\"deactivateProperty('+id+');\">Deactivate</button><a href=\"javascript:void(0)\" onclick=\"$.unblockUI();\">Cancel</a></div>');
}


function deactivateProperty(ID) {
    $.unblockUI();
    var param = 'ID='+ID;
    $.ajax({
        type: 'POST',
        url: '/profile/deactivate-property',
        data: param,
        success: function(msg){
            $.unblockUI()            
            var msgdata = jQuery.parseJSON( msg );
            if(msgdata.status=='success'){
                $('#divFormAgentSocial').hide();
                $('#divAgentSocial').show();
                $('#btnAgentSocial').show();
                $('.agent_social_'+socialid).remove();
                loadblockUI(msgdata.status_msg);
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

<style>
.inactive-property .modal-content {
    height: 620px;
    width: 1000px;
}
.inactive-property  .modal-body {
    padding: 0;
}
.inactive-property .modal-content .close-btn {
    right: 32px;
    top: 10px;
    z-index: 99;
}
.inactive-property object .navbar.navbar-default, .inactive-property object .footer {
    display: none;
	padding:0
}
.inactive-property  .modal-dialog {
	margin: 0 auto;
    top: 50%;
    transform: translateY(-50%)!important;
    width: 1000px;
}

.light-box-content {
    padding-top: 55px;
}
</style>
@endpush
