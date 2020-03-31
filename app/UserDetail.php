<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $fillable =  ['user_id', 'agency_name', 'agency_type', 'street_address_1', 'street_address_2', 
        'destination_id', 'state_id', 'country_id', 'zip_code', 'latitude', 'longitude', 'overview', 'territories', 
        'website', 'phone', 'toll_free_phone', 'fax', 'keywords', 'featured', 'notes', 'audio', 'license_number', 
        'meta_title', 'meta_keywords', 'meta_description', 'active'];
    public function country(){
        return $this->belongsTo('App\Country');
    }
    public function user(){
        return $this->belongsTo('App\User')->with(['userRole', 'userDefaultImages', 'userAgency', 'userSocialMedia']);
    }
    public function state(){
        return $this->belongsTo('App\State');
    }
    public function destination(){
        return $this->belongsTo('App\Destination');
    }
}
