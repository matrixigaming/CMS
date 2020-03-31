<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerOtp extends Model
{
    protected $fillable =  ['shop_id','customer_id','otp','is_used'];
    
}
