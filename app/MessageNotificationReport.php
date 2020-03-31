<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
class MessageNotificationReport extends Model
{
    protected $fillable =  ['message_id', 'notification_id', 'send_to_user_id',  'from_user_id', 'type', 'read_at'];
    use SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    public function sendUser()
    {
        return $this->belongsTo('App\User', 'send_to_user_id');
    }
    public function fromUser()
    {
        return $this->belongsTo('App\User', 'from_user_id');
    }
    public function notification()
    {
        return $this->belongsTo('App\Notification', 'notification_id');
    }
    public function message()
    {
        return $this->belongsTo('App\Message', 'message_id');
    }
    public function scopeMessages($query){
        return $query->where('type', '=', 'Message');
    }
    public function scopeNotifications($query){
        return $query->where('type', '=', 'Notification');
    }
    public function scopeMessagesByUser($query){
        $userId = Auth::user()->id;
        $query->where('type', '=', 'Message');
        $query->where('send_to_user_id', '=', $userId);
        return $query;
    }
    public function scopeNotificationsByUser($query){
        $userId = Auth::user()->id;
        $query->where('type', '=', 'Notification');
        $query->where('send_to_user_id', '=', $userId);
        return $query;
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
