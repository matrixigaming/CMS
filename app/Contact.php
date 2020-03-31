<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    public function getCreatedAtAttribute($value)
    {
        return date('m-d-y', strtotime($value));
    }
}
