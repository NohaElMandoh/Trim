<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OfferResource extends JsonResource
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
            'name_en'=>$this->name,
            'name_ar' =>  $this->translate('ar')->name,
            'description_ar' =>  $this->translate('ar')->description,
            'description_en'=>$this->description,
            'price' => $this->price,
            // 'image' => route('file_show', $this->image),
            'salon' => new UserResource($this->user) ,
            'category' => $this->category,
           
        ];
    }
}