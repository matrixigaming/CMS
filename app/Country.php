<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable =  ['name'];
    public function states() {
        return $this->hasMany('App\State');
    }
    public function destinations() {
        return $this->hasMany('App\Destination');
    }
}
