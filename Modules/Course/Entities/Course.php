<?php

namespace Modules\Course\Entities;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model implements TranslatableContract
{
    use Translatable, SoftDeletes;

    public $translatedAttributes = ['name', 'description'];
    protected $fillable = ['order', 'price', 'image','from','to'];
    protected $dates = ['deleted_at'];
    protected $appends = ['duration'];

    public function getDurationAttribute($value)
    {
        $start = Carbon::parse($this->from);
        $end = Carbon::parse($this->to);
      
        // $diffHuman = $start->diffForHumans ($end, true, false, 6);// "2 months 2 days 4 minutes 52 seconds",
       
        $diffHuman = $start->diffForHumans ($end, true, false,2);//"2 months 2 days",
    
        return  $diffHuman;
    }
}
