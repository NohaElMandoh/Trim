<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CouponeResource extends JsonResource
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
            'code' => $this->code,
            'title_en' => $this->translate('en')->title,
            'title_ar' => $this->translate('ar')->title,
            'duration' =>  $this->duration,
            'price' =>  $this->price,
            'city_en' => $this->city->name,
            'city_ar' =>  $this->city->translate('ar')->name,
            'governorate_en' => $this->governorate->name,
            'governorate_ar' =>  $this->governorate->translate('ar')->name,
            'user_usage_times'=>(!empty($this->pivot))?$this->pivot->usage:"",
            // 'usage_number_times' =>  $this->usage_number_times,
            'image' => route('file_show', $this->image),
            'anywhere' => $this->anywhere,
            'moreway' => $this->moreway,
            'oneway' => $this->oneway,
            'oq' => $this->oq,
            'week' => $this->week,
        
        ];
    }
}
