<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DestinationVideo extends Model
{
    protected $fillable =  ['destination_id', 'video_format', 'video_title', 'video_description', 'video_link', 'is_default'];
   
}
