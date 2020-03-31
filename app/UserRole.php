<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $fillable = ['role_id'];
    
    protected $table = 'role_user';
}
