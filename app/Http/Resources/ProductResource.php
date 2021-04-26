<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            
            'id' => $this->id,
            'name_en' => $this->name,
            'name_ar' =>  $this->translate('ar')->name,
            'image'=>route('file_show',  $this->image),
            'price'=>$this->price,
            'qty'=>(!empty($this->pivot)) ?$this->pivot->qty:""
        ];
    }
}
