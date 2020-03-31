<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    protected $fillable =  ['parentid', 'name', 'type', 'url', 'description', 'price', 'status'];
    
    public function getTypeAttribute($value){
        return empty($value) ? '-' : $value;
    }


}