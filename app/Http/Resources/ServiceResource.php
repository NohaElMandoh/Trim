<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceResource extends JsonResource
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
            'price_type' => $this->price_type,
            'gender' => $this->gender,
            'price' => $this->price,
            'min_price' => $this->min_price,
            'max_price' => $this->max_price,
            'for_children' => $this->for_children,
            'title_en'=>(!empty($this->title)) ?$this->title:"" ,
            'title_ar' =>  (!empty($this->translate('ar')->title)) ?$this->translate('ar')->title:"" ,
            'description_en'=>(!empty($this->description)) ?$this->description:"" ,
            'description_ar' =>  (!empty($this->translate('ar')->description)) ? $this->translate('ar')->description:"",
            'qty'=>(!empty($this->pivot)) ?$this->pivot->qty:"",
 

        ];
    }
}
