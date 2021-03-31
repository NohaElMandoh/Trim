<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model 
{
  
    protected $fillable = [
        'comment','rate','salon_id','user_id'
    ];
    public function user() {
        return $this->belongsTo('App\User','user_id','id');
    }
    public function salon() {
        return $this->belongsTo('App\User', 'salon_id','id');
    }

}