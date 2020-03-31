<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFile extends Model
{
    protected $fillable =  ['user_id', 'file_name', 'file_path'];
    /*public function destination(){
        return $this->belongsTo('App\Destination');
    }*/
}
