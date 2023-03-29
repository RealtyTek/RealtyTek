<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Notification extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        
        $notificationBadge = Notification::getBadge($this->target_id);

        return [
            'id'               => $this->id,
            'unique_id'        => $this->unique_id,
            'identifier'       => $this->identifier,
            'actor_id'         => $this->actor_id,
            'actor_type'       => $this->actor_type,
            'target_id'        => $this->target_id,
            'target_type'      => $this->target_type,
            'reference_id'     => $this->reference_id,
            'reference_slug'   => $this->reference_slug,
            'reference_module' => $this->reference_module,
            'title'            => $this->title,
            'description'      => $this->description,
            'web_redirect_link'=> $this->web_redirect_link,
            'is_read'          => $this->is_read,
            'is_view'          => $this->is_view,
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
            'actor_name'       => $this->actor_name,
            'actor_email'      => $this->actor_email,
            'actor_image_url'  => $this->actor_image_url,
            'custom_data'      => json_decode($this->custom_data),
            'total_badge'      =>$notificationBadge,
        ];
    }
}
