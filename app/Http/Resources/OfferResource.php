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
            'name_ar' => (!empty($this->translate('ar')->name))? $this->translate('ar')->name:'',
            'description_ar' =>  (!empty($this->translate('ar')->description))? $this->translate('ar')->description:'',
            'description_en'=>$this->description,
            'price' => $this->price,
            // 'image' => route('file_show', $this->image),
            'image' => !empty($this->image) ? url($this->image) : url('uploads/user.png'),
            'salon' => $this->user->name ,
            'is_sponsored'=>$this-> is_sponsored, 
            'category_ar' =>  (!empty($this->category))?$this->category->translate('ar')->name:'',
            'category_en' =>  (!empty($this->category))? $this->category->translate('en')->name:'',
            'qty'=>(!empty($this->pivot)) ?$this->pivot->qty:""
      
        ];
    }

  
    
}
