<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListingOpenhouse extends Model
{
    protected $fillable =  ['listing_id', 'start_date', 'end_date', 'notes'];
    /*public function destination(){
        return $this->belongsTo('App\Destination');
    }*/
}
