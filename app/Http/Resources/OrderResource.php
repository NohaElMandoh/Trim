<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderResource extends JsonResource
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
            'lat' =>  number_format( $this->lat, 0, '', '' ),
            'lng' =>  number_format( $this->lng, 0, '', '' ),
            'user_id' =>  $this->user_id,
            'user_name'=>$this->user->name,
            'barber_id' =>  $this->barber_id,
            'barber_name'=>$this->barber->name,
            'status_id' => $this->status_id,
            'status_ar' => $this->status->translate('ar')->name,
            'status_en' => $this->status->translate('en')->name,
            'cancel_reason'=> $this->cancel_reason,
            'approve'=>$this->approve,
            'rate' =>  $this->rate,
            'review' => $this->review,
            'review_image' => route('file_show', $this->review_image),
            'payment_method' => $this->payment_method,
            'payment_coupon' => $this->payment_coupon,
            'phone' => $this->phone,
            'address' => $this->address,
            'is_now' => $this->is_now,
            'type' => $this->address,
            'is_now' => $this->is_now,
            'work_day_id'=> $this->work_day_id,
            'cost'=> $this->cost,
            'discount'=> $this->discount,
            'total'=> $this->total,
            'reservation_time'=> $this->reservation_time,
            'reservation_day'=> $this->reservation_day,
            'created_at'=>$this->created_at,
            'services'=>ServiceResource::collection($this->services)

        ];
    }
}
