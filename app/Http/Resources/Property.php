<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Property extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
    //    return Parent::toArray($request);
         if ($this->image_url == NULL) {
            $image_url = \URL::to('images/image-not-avail.png');
         }else{
            $image_url    = \URL::to('/').$this->image_url;
         }
          return [
                'id'                =>$this->id,
                'agent_id'          =>$this->agent_id,
                'customer_id'       =>$this->customer_id,
                'creator_id'        =>$this->creator_id,
                'title'             =>$this->title,
                'slug'              =>$this->slug,
                'image_url'         =>$image_url,
                'address'           =>$this->address,
                'city'              =>$this->city,
                'state'             =>$this->state,
                'zipcode'           =>$this->zipcode,
                'property_type'     =>$this->property_type,
                'mls_detail'        =>$this->mls_detail,
                'asking_price'      =>$this->asking_price,
                'sell_date'         =>$this->sell_date,
                'cma_appointment'   =>$this->cma_appointment,
                'property_status'   =>$this->property_status,
                'initiate_contract' =>$this->initiate_contract,
                'rating'            =>$this->rating,
                'review'            =>$this->review,
                'status'            =>$this->status,
                'created_at'        =>$this->created_at,
                'updated_at'        =>$this->updated_at,
                'deleted_at'        =>$this->deleted_at,
                'buyer_id'          =>$this->buyer_id,
                'agent'             =>$this->agent,
                'customer'          =>$this->customer, 
          ];
    }
}
