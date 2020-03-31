<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Customer extends Model
{
    protected $fillable =  ['name', 'shop_id','distributor_id','code','email','mobile','balance','win_amount','active','last_login'];
    public function getCreatedAtAttribute($value){
        $date = new Carbon($value);
        $date->setTimezone('EST');
        return $date->toDayDateTimeString();
    }
    
}
