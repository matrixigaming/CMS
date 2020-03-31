<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTool extends Model
{
    protected $fillable =  ['user_id', 'tool_id', 'ut_var', 'status'];
}
