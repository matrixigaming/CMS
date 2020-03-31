<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class Listing extends Model
{

    protected $fillable =  [

        'name', 'property_type', 'meta_title','meta_keywords','meta_description','country_id','state_id',
        'featured','active','sale_type','property_status','mls_number','web_id_number','street_address_1',
        'street_address_2','destination_id','zip_code','latitude','longitude','price_display_option','currency',
        'price','overview','exterior_features','interior_features','amenities','school_district','stories',
        'bedrooms','full_bathrooms','three_fourth_bathrooms','half_bathrooms','living_space','living_space_units',
        'lot_size','lot_size_units','hoa_fees','hoa_fee_type','year_built','year_remodeled','days_on_market','active',
        'website','virtual_tour','audio_tour','keywords','notes','banner_homepage','featured','email_address','phone','toll_free_phone',
        'fax','user_id'];

    public function country(){
        return $this->belongsTo('App\Country');
    }

    public function state(){
        return $this->belongsTo('App\State');
    }

    public function destination(){
        return $this->belongsTo('App\Destination');
    }

    

    public function listingImages(){
        return $this->hasMany('App\ListingImage');
    }

    public function listingDefaultImage(){
        return $this->hasOne('App\ListingImage')->where('is_default', 1);
    }

    public function listingVideos(){
        return $this->hasMany('App\ListingVideo');
    }

    public function listingFiles(){
        return $this->hasMany('App\ListingFile');
    }

    public function listingCategories(){
        return $this->hasMany('App\ListingCategory');
    }

    public function listingSocialMedia(){
        return $this->hasMany('App\ListingSocialMedia');
    }

    public function listingOpenhouse(){
        return $this->hasMany('App\ListingOpenhouse');
    }

    public function uploaderInfo(){
        return $this->belongsTo('App\User', 'user_id', 'id')->with(['userDetail', 'userDefaultImages', 'userRole', 'userAgency']);

    }
    protected static function boot() {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('updated_at', 'desc');
        });
    }

}

