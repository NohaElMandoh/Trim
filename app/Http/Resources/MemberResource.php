<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MemberResource extends JsonResource
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
            'spaciality'=>$this->spaciality,
            'image' => !empty($this->image) ? url($this->image) : url('uploads/user.png'),
            'address' => (!empty($this->address)) ? $this->address : "",
            'description'=>(!empty($this->description)) ?$this->description:"" ,
           
        ];
    }
}
