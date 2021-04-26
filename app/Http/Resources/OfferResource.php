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
            'image' => route('file_show', $this->image),
            'salon' => $this->user->name ,
            'is_sponsored'=>$this-> is_sponsored, 
            'category_ar' => $this->category->translate('ar')->name,
            'category_en' => $this->category->translate('en')->name,
            'qty'=>(!empty($this->pivot)) ?$this->pivot->qty:""
        ];
    }
}
