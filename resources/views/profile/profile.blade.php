@extends('layouts.frontapp')

@section('content')
<div class="col-lg-12"> 
    <div class="row inner-page property-details-page agent-details-page profile-settings-page">
        <ul class="breadcrumb letter-space">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li class="active">Profile</li>
            <li class="right"><a href="{{ url('logout') }}">Logout</a></li>
        </ul>

        <div class="col-lg-12" style="margin-top: 49px;">
            <div class="row">
                <div class="@if($user_role['role_id'] != 1) {{ 'col-lg-9' }} @else {{ 'col-lg-12' }} @endif content-section bg-white padding-bottom-50 page-heigh">
                    <div class="row">
                        @include('profile.agent_info')
                        <div class="detail-tabs">
                            @if($user_role['role_id'] != 1)
                                @include('profile.about')
                                @include('profile.territories')
                                @include('profile.videos')
                                @include('profile.audios')
                                @include('profile.properties')
                                @if($user_role['role_id'] == 3 && $user_detail['agency_type'] == 2)
                                    @include('profile.agents')
                                @endif
                            @endif
                        </div>
                   </div>
                </div>

                <div class="col-lg-3 content-section  bg-gray">
                    <div class="side-bar">
                        @if($user_role['role_id'] != 1)
                            @if($user_role['role_id'] == 3 && $user_detail['agency_type'] == 2)
                                @include('profile.agency_info')
                            @endif
                            @include('profile.social')
                            @include('profile.website')
                        @endif
                    </div>
                </div>
            </div>
        </div>
   </div>
</div>
<div class="clearfix"></div>
@endsection

@push('scripts')
<link href="{{ asset('frontend/css/ui-css/jquery.ui.all.css') }}" rel="stylesheet">
<script type="text/javascript" src="{{ asset('frontend/js/cropper.js') }}"></script>
<script type="text/javascript" src="{{ asset('frontend/js/main.js') }}"></script>
<link href="{{ asset('frontend/css/slick.css') }}" rel="stylesheet">

<style type="text/css">
.properties-tab.view  .like-box {
    text-align: center;
    padding: 10px 23px;
    background:#000  !important;
}

.inner-page.property-details-page.agent-details-page.profile-settings-page {
    background: #e6e6e6 none repeat scroll 0 0;
}
</style>
<script type="text/javascript">
$(window).on('load', function() {
    var sidebarHeight = $('.side-bar').height() + 37 ; 
    var pageHeigh = $('.page-heigh').height() + 140;

    if(sidebarHeight > pageHeigh){	
            $('.page-heigh').css('height', sidebarHeight);
    }else if(sidebarHeight < pageHeigh){
            $('.side-bar').css('height', pageHeigh);
    }
});

$('.btn.save').click(function(){
	$(this).hide();
});

$('#btnAgencyEdit').click(function(){
	$('#divAgencyForm').slideDown();
	$('#divAgencyInfo').slideUp();
	$(this).css('display', 'none');
	$(this).next('.save').css('display', 'block');
});

$('.informo-cancel').click(function(){
	$('#divAgencyForm').slideUp();
	$('#divAgencyInfo').slideDown();
	$('#btnAgencySave').css('display', 'none');
	$('#btnAgencyEdit').css('display', 'block');
});


$('.social-edit').click(function(){
	$('.box-open.agency-socials.edit').slideDown();
	$('#divAgentSocial').slideUp();
	$(this).css('display', 'none');
	$(this).next('.save').css('display', 'block');
});

$('.social-cancel').click(function(){
	$('.box-open.agency-socials.edit').slideUp();
	$('#divAgentSocial').slideDown();
	$('.btn.social-save').css('display', 'none');
	$('.btn.social-edit').css('display', 'block');
});


$('.website-edit').click(function(){
	$('#divAgentWebsite').slideDown();
	$('#lblAgentWebsite').slideUp();
	$(this).css('display', 'none');
	$(this).next('.save').css('display', 'block');
});

$('.website-cancel').click(function(){
	$('.box-open.agency-socials.edit').slideUp();
	$('#divAgentSocial').slideDown();
	$('.website-save').css('display', 'none');
	$('.btn.social-edit').css('display', 'block');
});


$('.btn.agent-info.Cancel').click(function(){
	$('#divAgentInfo').slideUp();
	$('.profile-detail-box.address').slideDown();
});

$('.property-details-page .detail-tabs .header .btn.edit.about').click(function(){
	$(this).css('display', 'none');
    $('.box-open.edit-about').slideUp();
	$('.box-open.editor.about-edit').slideDown();
	$('.header-edit-box.about-btn').css('display', 'block');
});

$('.header-edit-box.about-btn .btn.agent-about.Cancel').click(function(){
	$('.header-edit-box.about-btn').css('display', 'none');
    $('.box-open.edit-about').slideDown();
	$('.box-open.editor.about-edit').slideUp();
	$('.property-details-page .detail-tabs .header .btn.about').css('display', 'block');
});

$('.property-details-page .detail-tabs .header .btn.edit.territories').click(function(){
	$(this).css('display', 'none');
    $('.box-open.territories').slideUp();
	$('.box-open.editor.territories-edit').slideDown();
	$('.header-edit-box.territories-btn').css('display', 'block');
});

$('.header-edit-box.territories-btn .btn.territories-cancel').click(function(){
	$('.box-open.territories').slideDown();
	$('.box-open.editor.territories-edit').slideUp();
	$('.header-edit-box.territories-btn').css('display', 'none');
	$('.btn.edit.territories').css('display','block');
});

$('.btn.Cancel.informo-cancel').click(function(){
	$('#divAgencyForm').slideUp();
});
</script>
@endpush
