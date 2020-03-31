<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentLog extends Model
{
    protected $fillable =  ['distributor_id','shop_id','customer_id','amount','bounceback_amount','type'];
    public function getCreatedAtAttribute($value){
        $date = new Carbon($value);
        $date->setTimezone('EST');
        return $date->toDayDateTimeString();
    }
}
