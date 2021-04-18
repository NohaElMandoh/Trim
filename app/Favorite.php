<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model 
{
  
    protected $fillable = [
        'is_fav','salon_id','user_id'
    ];
    public function user() {
        return $this->belongsTo('App\User','user_id','id');
    }
    public function salon() {
        return $this->belongsTo('App\User', 'salon_id','id');
    }

}