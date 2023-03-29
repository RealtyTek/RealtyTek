<?php

namespace App\Models\Hooks\Api;
use Carbon\Carbon;
use App\Helpers\CustomHelper;
use App\Models\{Notification,User,NotificationSetting};

class PropertyHook
{
    private $_model,
        $except_update_params = [
            'slug' ,
            'agent_id' ,
            'customer_id',
        ];

    public function __construct($model)
    {
        $this->_model = $model;
    }

    /*
   | ----------------------------------------------------------------------
   | Hook for manipulate query of index result
   | ----------------------------------------------------------------------
   | @query   = current sql query
   | @request = laravel http request class
   |
   */
    public function hook_query_index(&$query,$request, $slug='')
	{
		$base_url    = \URL::to('/');
		$placeholder = \URL::to('images/image-not-avail.png');

		$query->with(['agent','customer'])
			->select('properties.*');
            if ($request['user']->user_group_id == 1) {
                $query->where('properties.agent_id',$request["user"]->id);
            }else{
                if (empty($request['property_type'])) {
                    $query->where('properties.customer_id',$request["user"]->id);
                }
            }

            if (!empty($request['property_type'])) {
                    if( $request['property_type'] == "recommended" ){
                        $query->select('properties.*','buyer_properties.buyer_id')
                                ->join('buyer_properties', 'properties.id', '=', 'buyer_properties.property_id')
                                ->where('properties.initiate_contract','0')
                                ->where('buyer_properties.customer_id',$request["user"]->id);
                    }
                    if( $request['property_type'] == "under_contract" ){
                        $query->with('initiateBuyerProperty');
                        $query->select('properties.*','buyer_properties.buyer_id')
                        ->join('buyer_properties', 'properties.id', '=', 'buyer_properties.property_id')
                        ->where('properties.initiate_contract','1')
                        ->where('buyer_properties.customer_id',$request["user"]->id);

                    }

                }

            if (!empty( $request['property_type'])) {
                if( $request['property_type'] == "search" ){
                    $query->where('title', 'like', '%' . $request['value'] . '%');
                }

                if( $request['property_type'] == "search_recommended" ){
                    $query->where('title', 'like', '%' . $request['value'] . '%')->where('initiate_contract','0');
                }
            }

            if (!empty($request['user_id'])) {
                $query->where('properties.customer_id',$request['user_id']);
            }

            $query->groupBy('properties.id');


    }

    /*
    | ----------------------------------------------------------------------
    | Hook for manipulate data input before add data is execute
    | ----------------------------------------------------------------------
    | @arr
    |
    */
    public function hook_before_add($request,&$postdata)
    {
         if ($request['user']->user_group_id == 1) {
            $postdata['agent_id']  = $request["user"]->id;
            $postdata['creator_id']  = $request["user"]->id;
            if (!empty($request["customer_id"])) {
                $postdata['customer_id']  = $request["customer_id"];
            }else{
                $postdata['customer_id']  = $request["user"]->id;
            }
         }else{
            $postdata['agent_id']  = $request['user']->parent_id;
            $postdata['customer_id']  = $request["user"]->id;
            $postdata['creator_id']  = $request["user"]->id;
         }

		 $postdata['slug']  = rand();
         $postdata['created_at'] = Carbon::now();

		 if( !empty($postdata['image_url']) ){
			$postdata['image_url'] = '/storage/' . CustomHelper::uploadMedia('property',$postdata['image_url']);
		 }

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after add public static function called
    | ----------------------------------------------------------------------
    | @record
    |
    */
    public function hook_after_add($request,$record)
    {
        if ($request['user']->user_group_id == 1) {
            $targetUser = User::getUserApiTokenByID($record['customer_id']);
            $actorUser = User::getUserApiTokenByID($request['user']['id']);
        }else{
            $targetUser = User::getUserApiTokenByID($request['user']['getagent']['id']);
            $actorUser = User::getUserApiTokenByID($request['user']['id']);
        }

        if (count($targetUser) > 0) {
            //send push notification to research center
            $getNotificationSetting = NotificationSetting::getSetting($targetUser[0]->id);
            if (!empty($getNotificationSetting['notification_all'])) {
                if ($getNotificationSetting['notification_all'] == 1) {
                    $custom_data = [
                        'record_id'     => 0,
                        'redirect_link' => NULL,
                        'identifier'    => 'property_create',
                        'data'    => ['id'=>$record->id,'slug'=>$record->slug,],
                    ];
                    $notification_data = [
                        'actor'            => $actorUser,
                        'actor_type'       => 'users',
                        'target'           => $targetUser,
                        'target_type'      => 'users',
                        'title'            => 'Property created',
                        'message'          => "".$actorUser[0]['name']." has added a property",
                        'reference_slug'   => $record->slug,
                        'reference_id'     => $record->id,
                        'custom_data'      => $custom_data,
                        'reference_module' => 'properties',
                        'redirect_link'    => NULL,
                        'badge'            => '0'
                    ];


                Notification::sendPushNotification('property_create',$notification_data,$custom_data,$targetUser[0]->device_type);
            }
            }

        }

        //Your code here
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for manipulate data input before update data is execute
    | ----------------------------------------------------------------------
    | @request  = http request object
    | @postdata = input post data
    | @id       = current id
    |
    */
    public function hook_before_edit($request, $slug, &$postData)
    {
        foreach( $postData as $key => $value ){
            if( in_array($key,$this->except_update_params) )
                unset($postData[$key]);
        }
        if( !empty($postData['image_url']) ){
            $postData['image_url'] = '/storage/' . CustomHelper::uploadMedia('property',$postData['image_url']);
        }
	}

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after edit public static function called
    | ----------------------------------------------------------------------
    | @request  = Http request object
    | @$slug    = $slug
    |
    */
    public function hook_after_edit($request, $slug) {
        //Your code here
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command before delete public static function called
    | ----------------------------------------------------------------------
    | @request  = Http request object
    | @$id      = record id = int / array
    |
    */
    public function hook_before_delete($request, $slug) {
        //Your code here

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after delete public static function called
    | ----------------------------------------------------------------------
    | @$request       = Http request object
    | @records        = deleted records
    |
    */
    public function hook_after_delete($request,$records) {
        //Your code here
    }

    public function create_cache_signature($request)
    {
        $cache_params = $request->except(['user','api_token']);
        return 'sample_' . md5(implode('',$cache_params));
    }
}
