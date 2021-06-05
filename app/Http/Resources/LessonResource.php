<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LessonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
    /**
     *    "video": "7932162250343360b57009f2323.png",
                  
                             */   
        return [

            'id' => $this->id,
            'name_en' => $this->translate('en')->name,
            'name_ar' => $this->translate('ar')->name,
            
            'image' => !empty($this->image) ? url($this->image) : url('uploads/user.png'),
          
            
        
        ];
    }
}
