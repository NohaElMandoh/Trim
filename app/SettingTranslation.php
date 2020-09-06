<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SettingTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'title', 'description', 'copyrights', 'privacy',
        'how_it_works', 'work_in_oq'
    ];
}
