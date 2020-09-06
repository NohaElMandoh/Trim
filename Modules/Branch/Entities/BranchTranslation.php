<?php

namespace Modules\Branch\Entities;

use Illuminate\Database\Eloquent\Model;

class BranchTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['address'];
}
