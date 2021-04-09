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
            'name' => $this->name,
            'duration' =>  $this->duration,
            'price' =>  $this->duration,
            'city_en' => $this->city->name,
            'city_ar' =>  $this->city->translate('ar')->name,
            'governorate_en' => $this->governorate->name,
            'governorate_ar' =>  $this->governorate->translate('ar')->name,
            'usage_number_times' =>  $this->usage_number_times,
            'image' => route('file_show', $this->image),
            'anywhere' => $this->anywhere,
            'moreway' => $this->moreway,
            'oneway' => $this->oneway,
            'oq' => $this->oq,
            'week' => $this->week,
        ];
    }
}
