<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAgency extends Model
{
    protected $fillable = ['agency_id', 'user_id'];
    
    public $timestamps = false;
    
    public function user(){
        return $this->belongsTo('App\User', 'agency_id');
    }
    public function userDetail(){
        return $this->belongsTo('App\UserDetail', 'agency_id', 'user_id')->with(['state', 'country', 'destination']);
    }
    public function userDefaultImages(){
        return $this->hasOne('App\UserImage', 'user_id', 'agency_id')->where('is_default', 1);
    }
}
