<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Carbon\Carbon;
class User extends Authenticatable
{
    use EntrustUserTrait; // add this trait to your user model
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */ 
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'city', 'state', 'zipcode', 'available_credit','nudgeFeature','preRevealWithSkillStop','jackpot','sweepstakes','login_verbiage_id' ,'distributor_rtp_variant','shop_name', 'shop_code', 'phone', 'address', 'active', 'created_by'
    ];
    protected $with = ['roles'];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function userDefaultImages(){
        return $this->hasOne('App\UserImage')->where('is_default', 1);;
    }
    public function userImages(){
        return $this->hasMany('App\UserImage');
    }
    public function userVideos(){
        return $this->hasMany('App\UserVideo');
    }
    public function userFiles(){
        return $this->hasMany('App\UserFile');
    }
    public function userRole(){
        return $this->hasOne('App\UserRole');
    }
    public function userDetail(){
        return $this->hasOne('App\UserDetail')->with(['state', 'country', 'destination']);
    }
    
    public function userSocialMedia(){
        return $this->hasMany('App\UserSocialMedia');
    }
    
    public function userListing(){
        return $this->hasMany('App\Listing')->with(['destination', 'state', 'country', 'listingDefaultImage', 'listingImages', 'uploaderInfo']);
    }
    public function userAgency(){
        return $this->hasOne('App\UserAgency')->with(['user', 'userDetail', 'userDefaultImages']);
    }
    public function userAgents(){
        return $this->hasMany('App\UserAgency', 'agency_id');
    }
    public function getUpdatedAtAttribute($value){
        $date = new Carbon($value);
        $date->setTimezone('EST');
        return $date->toDayDateTimeString();
    }
    public function getCreatedAtAttribute($value){
        $date = new Carbon($value);
        $date->setTimezone('EST');
        return $date->toDayDateTimeString();
    }
}
