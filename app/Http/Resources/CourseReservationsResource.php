<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CourseReservationsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
         return [
            
            'id' => $this->id,
            'name' => $this->name,
            'governorate_en' => (!empty($this->governorate->name)) ? $this->governorate->name : "",
            'governorate_ar' => (!empty($this->governorate->name)) ? $this->governorate->translate('ar')->name : "",
            'phone' =>  $this->phone,
            'price' =>  $this->price,
            'payment_type'=>$this->payment_type,
            'course_en' => (!empty($this->course->name)) ? $this->course->name : "",
            'course_ar' => (!empty($this->course->name)) ? $this->course->translate('ar')->name : "",
           
        ];
    }
}
