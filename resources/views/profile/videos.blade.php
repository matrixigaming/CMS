<div class="header">
    <h3>Videos <a href='{{ url('/') }}' id="open_btn" role="button" class="thickbox pull-right" data-toggle="modal"><button class="btn btn-defalut strock black pull-right">+ add video</button></a></h3>
</div>

<input type="hidden" name="modulename" id="modulename" value="users.agents">
<div>
    <ul id="videoboxes" class="ui-sortable" style="list-style: none;width: 100%;">
        @if(count($user_videos) > 0)
            @foreach($user_videos as $video)
                @if($video['video_format'] == 'YouTube')
                    @php $filetype = "youtube-icon.png"; @endphp
                @elseif($video['video_format'] == 'Vimeo')
                    @php $filetype = "vimeo-icon.png"; @endphp
                @endif
                <li id="recordsArray_{{ $video['id'] }}}">
                    <div>
                        <div style="text-align: left;padding: 5px 10px 5px 0px;">
                            <img src="{{ url('images/' . $filetype) }}" align="absmiddle" hspace="5"/>
                            <span style="margin-left: 0px; width: 100%;">{{ stripslashes($video['video_title']) }}</span>
                            <span style="float: right;">
                                <a id="galleryEditImageThick" class="thickbox" title="edit video" href="">edit</a>
                                | 
                                <a id="galleryEditImageThick" class="thickbox" title="delete video" href="">delete</a>
                            </span>
                        </div>
                    </div>
                </li>
            @endforeach
        @else
            <div class="col-lg-12 text-center">You currently have no videos.</div>
        @endif
    </ul>
</div>

@push('scripts')
<script type="text/javascript">
function getVideosFromEditLightbox(id,actiontype){
    var data = "id=" + id + "&actiontype=" + actiontype + "&modulename=" + $('#modulename').val();
    $.ajax({
        type: "POST",
        url: '/common/getvideos',
        data: data,
        success: function(msg){
            if($.trim(msg)){
                if(msg == "sessionexpires") {
                    window.location.href = "/";
                } else {
                    $('#videoboxes').html(msg);
                    tb_init('a.thickbox, area.thickbox, input.thickbox');
                }
                $('#emptymsg').hide();
            }else{
                $('#videoboxes').html('');
                $('#emptymsg').show();
            }
        }
    });
}
</script>
@endpush
