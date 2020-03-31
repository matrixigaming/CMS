<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $fillable =  ['name', 'description', 'meta_title','meta_keywords','meta_description','country_id','state_id','featured','active'];
    public function country()
    {
        return $this->belongsTo('App\Country');
    }
    public function state()
    {
        return $this->belongsTo('App\State');
    }
    public function listing()
    {
        return $this->hasMany('App\Listing');
    }
    
    public function destinationImages()
    {
        return $this->hasMany('App\DestinationImage');
    }
    public function destinationVideos()
    {
        return $this->hasMany('App\DestinationVideo');
    }
    public function destinationFiles()
    {
        return $this->hasMany('App\DestinationFile');
    }
    public function destinationAgents()
    {
        return $this->hasMany('App\UserDetail')->with(['user', 'country', 'state', 'destination']);
    }
}
