<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    

    protected $table = 'notifications';
    public $timestamps = true;

    protected $fillable = [
       'type','data','read_at','notifiable_type','notifiable_id'
    ];

  

}
