<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DestinationImage extends Model
{
    protected $fillable =  ['destination_id', 'image_path', 'is_default'];
    /*public function destination(){
        return $this->belongsTo('App\Destination');
    }*/
}
