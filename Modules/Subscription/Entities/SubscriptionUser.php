<?php

namespace Modules\Subscription\Entities;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionUser extends Model  
{
    use  SoftDeletes;
    
   
    protected $fillable = ['user_id','months','subscription_id','from','to','is_active','price'];
    protected $dates = ['deleted_at'];

   
}
