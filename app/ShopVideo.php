<?php 
namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopVideo extends Model
{
    protected $fillable =  ['user_id','video_name','status'];
    
}
