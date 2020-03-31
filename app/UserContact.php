<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserContact extends Model
{
    protected $fillable =  ['user_id', 'first_name', 'last_name', 'email_address', 'phone', 'message'];
}
