<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListingImage extends Model
{
    protected $fillable =  ['listing_id', 'image_path', 'is_default'];
    /*public function destination(){
        return $this->belongsTo('App\Destination');
    }*/
}
