<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageReport extends Model
{
    public function getCreatedAtAttribute($value)
    {
        return date('m-d-y', strtotime($value));
    }
    public function getUpdatedAtAttribute($value)
    {
        return date('m-d-y', strtotime($value));
    }
}
