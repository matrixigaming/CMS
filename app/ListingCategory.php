<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListingCategory extends Model
{
    protected $fillable =  ['listing_id', 'category_id'];
    /*public function destination(){
        return $this->belongsTo('App\Destination');
    }*/
}
