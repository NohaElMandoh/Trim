<?php

namespace Modules\Offer\Entities;

use Illuminate\Database\Eloquent\Model;

class OfferTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'description'];
}
