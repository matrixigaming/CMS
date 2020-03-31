<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListingSocialMedia extends Model
{
    protected $fillable =  ['listing_id', 'social', 'link'];
    /*public function destination(){
        return $this->belongsTo('App\Destination');
    }*/
}
