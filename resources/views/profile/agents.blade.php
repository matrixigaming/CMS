<div class="header">
    <h3>Agents</h3>
</div>

<div class="box-open">
    <div class="row properties-tab view">
        @if(count($agents_detail) > 0)
            @foreach($agents_detail as $agent)
                <div class="col-lg-3 cover" style="width: 32% !important; margin-bottom: 10px;">
                    <div class=" box">
                        <div class="hover">
                            <a href="{{ url('agent/' . $agent['id'] . '/' . urlencode(strtolower(str_replace([" ", "&", ",","'","?","/"], ["-", "and","","","","-"], $agent['first_name'] . '-' . $agent['last_name'])))) }}">
                                <button class="btn btn-defalut strock gold">view profile</button>
                            </a>
                        </div>
                        @if($agent['user_default_images'])
                            @php
                                $imagePath = $agent['user_default_images']['image_path'];
                                $path_parts = pathinfo($imagePath);
                                $filename = $path_parts['dirname'] . '/' . $path_parts['filename'] . '_th' . '.' . $path_parts['extension'];
                                $filename = file_exists($filename) ? $filename : $path_parts['dirname'].'/'.$path_parts['filename'].'.'.$path_parts['extension'];
                            @endphp
                            <img class="img-responsive" src="{{ url($filename) }}" alt="Luxury Real Estate Search Agents - {{ $agent['first_name'] }} {{ $agent['last_name'] }}" width="220" height="200">
                        @else
                            <img class="img-responsive" src="{{ url('uploads/no-image_th.jpg') }}"  alt="Luxury Real Estate Search Agents - {{ $agent['first_name'] }} {{ $agent['last_name'] }}" width="220" height="200">
                        @endif
                    </div>

                    <div class="col-lg-12 properties-details">
                        <div class="row">
                            <div class="top">
                                @if(count($agent['user_social_media']) > 0)
                                    @foreach($agent['user_social_media'] as $socialmedia)
                                        @php $class = Helper::getSocialClass($socialmedia); @endphp
                                        <a href="{{ $socialmedia['link'] }}" target="_blank" class="{{ $class }}"></a> 
                                    @endforeach
                                @endif
                            </div>

                            <div class="bottom">
                                <div class="details">
                                    {{ $agent['first_name'] }} {{ $agent['last_name'] }}<br />
                                    <p>
                                        {{ $agent['user_detail']['destination']['name'] }}, {{ $agent['user_detail']['state']['state_name'] }}, {{ $agent['user_detail']['country']['name'] }}<br />
                                    </p>
                                </div>
                            </div>

                            <div class="like-box">
                                <p class="base-color">
                                    @if($agent['user_agency'])
                                        {{ $agent['user_agency']['user_detail']['agency_name'] }}
                                    @endif
                                    <span class="pull-right like-ico"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

@push('scripts')
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
