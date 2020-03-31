<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationReport extends Model
{
    protected $fillable =  ['notification_id', 'send_to_user_id'];
    
    public function user()
    {
        return $this->belongsTo('App\User', 'send_to_user_id');
    }
    public function notification()
    {
        return $this->belongsTo('App\Notification', 'notification_id');
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
