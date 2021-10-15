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

    protected $appends = ['from_date', 'to_date','day_name'];


    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
    public function getDayNameAttribute($value)
    {
       if($this->day == 0) return 'Sunday';
       if($this->day == 1) return 'Monday';
       if($this->day == 2) return 'Tuesday';
       if($this->day == 3) return 'Wednesday';
       if($this->day == 4) return 'Thursday';
       if($this->day == 5) return 'Friday';
       if($this->day ==6) return 'Saturday';

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
