<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserImage extends Model
{
    protected $fillable =  ['user_id', 'image_path', 'is_default'];
    /*public function destination(){
        return $this->belongsTo('App\Destination');
    }*/
}
