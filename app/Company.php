<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable =  ['name', 'shortname', 'url'];
    public function users()
    {
        return $this->hasMany('App\User','company_id');
    }
}
