<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListingFile extends Model
{
    protected $fillable =  ['listing_id', 'file_name', 'file_path'];
    /*public function destination(){
        return $this->belongsTo('App\Destination');
    }*/
}
