<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable =  ['type', 'event_type', 'title', 'content', 'icon', 'created_by'];
    
    public function user()
    {
        return $this->belongsTo('App\User', 'created_by');
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
