<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSocialMedia extends Model
{
    protected $fillable =  ['user_id', 'social', 'link'];
    /*public function destination(){
        return $this->belongsTo('App\Destination');
    }*/
}
