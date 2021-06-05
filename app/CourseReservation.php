<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseReservation extends Model
{
    protected $table = 'courses_reservations';
    public $timestamps = true;


    protected $fillable = array(
        'type', 'name', 'governorate_id', 'phone', 'price', 'payment_type', 'course_id','user_id'
    );
    public function course()
    {
        return $this->belongsTo('\Modules\Course\Entities\Course');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function governorate()
    {
        return $this->belongsTo('Modules\Governorate\Entities\Governorate');
    }
  
}
