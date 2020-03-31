<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable =  ['name', 'icon', 'lobby_icon','default_rtp','url', 'math_url','order','active'];
    
}
