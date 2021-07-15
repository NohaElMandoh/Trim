<?php

namespace Modules\Subscription\Entities;

use Illuminate\Database\Eloquent\Model;

class SubscriptionTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title','desc'];
}
