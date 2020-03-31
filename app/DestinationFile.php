<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DestinationFile extends Model
{
    protected $fillable =  ['destination_id', 'file_name', 'file_path'];
   
}
