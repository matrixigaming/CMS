<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserVideo extends Model
{
    protected $fillable =  ['user_id', 'video_format', 'video_title', 'video_description', 'video_link', 'is_default'];
    /*public function destination(){
        return $this->belongsTo('App\Destination');
    }*/
}
