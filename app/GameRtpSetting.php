<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GameRtpSetting extends Model
{
    protected $fillable =  ['shop_id', 'game_id','rtpVariant'];
    
}
