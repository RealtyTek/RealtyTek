<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Models\UserSubscription;

class Auth extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $checkUserSubscription = UserSubscription::getUserSubscription($this->id);
        $current_date = Carbon::now()->format('Y-m-d');
        if( isset($checkUserSubscription->id) )
        {
            if($checkUserSubscription->status == 'expire'){
                $is_subscribe = 0;
            }else{
                if( !empty($checkUserSubscription->subscription_expiry_date) ){
                    if( strtotime($current_date) > strtotime($checkUserSubscription->subscription_expiry_date) ){
                        $is_subscribe = 0;
                    }else{
                        $is_subscribe = 1;
                    }
                }else{
                    $is_subscribe = 1;
                }
            }
        }
        else
        {
          $is_subscribe = 0;
        }

       return [
           'id'               => $this->id,
           'name'             => $this->name,
           'slug'             => $this->slug,
           'email'            => $this->email,
           'mobile_no'        => $this->mobile_no,
           'image_url'        => $this->image_url,
           'status'           => $this->status,
           'is_email_verify'  => $this->is_email_verify,
           'is_mobile_verify' => $this->is_mobile_verify,
           'country'          => $this->country,
           'state'            => $this->state,
           'city'             => $this->city,
           'zipcode'          => $this->zipcode,
           'address'          => $this->address,
           'about_us'         => $this->about_us,
           'tag_line'         => $this->tag_line,
           'website'          => $this->website,
           'licence_state'    => $this->licence_state,
           'license_number'   => $this->license_number,
           'company_name'     => $this->company_name,
           'company_description'=> $this->company_description,
           'logo_url'         => $this->logo_url,
           'longitude'        => $this->longitude,
           'latitude'         => $this->latitude,
           'api_token'        => $this->api_token,
           'device_type'      => $this->device_type,
           'device_token'     => $this->device_token,
           'platform_type'    => $this->platform_type, 
           'platform_id'      => $this->platform_id,
           'created_at'       => $this->created_at,
           'agent'            => $this->getagent,
        //    'is_subscribe'     =>$is_subscribe,
           'is_subscribe'     => 1,
           'carrier'     => $this->carrier,
           'share_profile_image'=>$this->share_profile_image,
           'subscription_expiry_date'      => $this->subscription_expiry_date,
           'agent_agrement' => ($this->agent_agrement) ? \URL::to($this->agent_agrement) : NULL,
           'is_agrement_accept'=> $this->is_agrement_accept,
           'user_notification_setting'=> $this->whenLoaded('userNotificationSetting'),
       ];
    }
}
