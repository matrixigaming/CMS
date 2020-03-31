<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserNewsletter extends Model
{
    protected $fillable =  ['name', 'email_address', 'active'];
}
