<?php

namespace Modules\Salon\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkDay extends Model
{
    use SoftDeletes;

    protected $fillable = ['from', 'to', 'day', 'user_id'];
    protected $dates = ['deleted_at'];

    protected $appends = ['from_date', 'to_date'];

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }



    public function getFromDateAttribute()
    {
        $from = Carbon::createFromFormat('H:i:s', $this->from);
        $text = $from->format('g:i A');
        $replace = str_replace('AM', 'ص', $text);
        $replace = str_replace('PM', 'م', $replace);
        return $replace;
    }

    public function getToDateAttribute()
    {
        $to = Carbon::createFromFormat('H:i:s', $this->to);
        $text = $to->format('g:i A');
        $replace = str_replace('AM', 'ص', $text);
        $replace = str_replace('PM', 'م', $replace);
        return $replace;
    }
}
