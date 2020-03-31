<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;

class BouncebackSetting extends Model
{
    protected $fillable =  ['bb_category','min_recharge','max_recharge','bb_amount'];
    
}
