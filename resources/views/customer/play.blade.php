@extends('layouts.playerappplay')

@section('content')

<div> 
    <iframe src="{{$data['game_url'].'?user_id='.$data['user_id'].'&game_id='.$data['game_id']}}" style="position:fixed; top:0; left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index:1;" />
</div>  
@endsection