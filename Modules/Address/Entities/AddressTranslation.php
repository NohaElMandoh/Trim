<?php

namespace Modules\Address\Entities;

use Illuminate\Database\Eloquent\Model;

class AddressTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['address'];
}
