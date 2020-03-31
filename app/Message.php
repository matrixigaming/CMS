<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable =  ['title', 'content', 'department_id', 'created_by'];
    public function department()
    {
        return $this->belongsTo('App\Department', 'department_id');
    }
    public function getCreatedAtAttribute($value)
    {
        return date('m-d-y', strtotime($value));
    }
    public function getUpdatedAtAttribute($value)
    {
        return date('m-d-y', strtotime($value));
    }
}
