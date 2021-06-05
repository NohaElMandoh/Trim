<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CourseResource extends JsonResource
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
            'name_en' => !empty($this->translate('en')->name) ? $this->translate('en')->name:'',
            'name_ar' =>!empty($this->translate('ar')->name) ? $this->translate('ar')->name:'',
            'description_en' =>!empty($this->translate('en')->description) ? $this->translate('en')->description:'',
            'description_ar' => !empty($this->translate('ar')->description) ? $this->translate('ar')->description:'',
            'duration' =>  $this->duration,
            'price' =>  $this->price,
            'image' => !empty($this->image) ? url($this->image) : url('uploads/user.png'),
            'from'=>$this->from,
            'to'=>$this->to,
            'lessons'=>(!empty($this->lessons))? LessonResource::collection( $this->lessons):null
            
        
        ];
    }
}
