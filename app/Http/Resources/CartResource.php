<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Str;
class CartResource extends JsonResource
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
            'row_id' => $this->id,
            'id' => $this->product->id,
            'name_en'=>(Str::contains(get_class($this->product), 'Service'))? $this->product->title : $this->product->name,
            'name_ar' => (Str::contains(get_class($this->product), 'Service'))?$this->product->translate('ar')->title: $this->product->translate('ar')->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            // 'image' => route('file_show', $this->product->image),
            'image' => !empty($this->product->image) ? url($this->product->image) : url('uploads/product.png'),
        ];
    }
}


