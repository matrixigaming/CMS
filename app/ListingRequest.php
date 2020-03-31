<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListingRequest extends Model
{
    protected $fillable =  ['listing_id', 'first_name', 'last_name', 'email_address', 'phone', 'day_time', 'message'];
}
