<?php

namespace App\Models\Hooks\Api;
use App\Models\{BuyerProperty,Buyer,User,Notification,NotificationSetting};
use Carbon\Carbon;

class BuyerHook
{
    private $_model;

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
    public function hook_query_index(&$query,$request, $slug='') {
        $query->with('buyerProperties'); 
        if ($request['user']->user_group_id == 2) {
            $query->with('customer'); 
            if ($request['buyer_type'] == 'buyers') {
                $query->where('customer_id',$request['user']->id)->where('initiate_contract','0');
            }
            if ($request['buyer_type'] == 'under_contract') {
                $query->with('initiateBuyerProperty')->where('customer_id',$request['user']->id)->where('initiate_contract','1');
            }
        }else{
            $query->with('customer');
            if ($request['buyer_type'] == 'buyers') {
                $query->with('customer')->where('agent_id',$request['user']->id)->where('initiate_contract','0');
                if (isset($request["search"]) && !empty($request["search"])) {
                    $search = $request['search'];
                        $query->whereHas('customer',function($q) use ($search){
                            $q->where('name', 'like', '%' . $search . '%');
                        });
                }
            }
            if ($request['buyer_type'] == 'under_contract') {
                $query->with('initiateBuyerProperty')->where('agent_id',$request['user']->id)->where('initiate_contract','1');
                if (isset($request["search"]) && !empty($request["search"])) {
                    $search = $request['search'];
                        $query->whereHas('customer',function($q) use ($search){
                            $q->where('name', 'like', '%' . $search . '%');
                        });
                }
            }
        }

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
            $postdata['agent_id'] = $request["user"]->id;
            $postdata['creator_id'] = $request["user"]->id;
        }else{
            $postdata['agent_id'] = $request["user"]->parent_id;
            $postdata['creator_id'] = $request["user"]->id;
            $postdata['customer_id'] = $request["user"]->id;
        }
            $postdata['created_at']  = Carbon::now();
            $postdata['slug']  = rand() . uniqid();
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
            if (!empty($request["property_id"])) {
                $getproperties =  explode(',' ,$request["property_id"]);
                for( $i=0; $i < count($getproperties); $i++ ){
                    $insert_properties[] =[
                        'agent_id'   => $record["agent_id"],
                        'customer_id' => $record['customer_id'],
                        'buyer_id' => $record['id'],
                        'property_id' => $getproperties[$i],
                        'slug' => rand(1,100).uniqid(),
                    ];
                }
                BuyerProperty::insert($insert_properties);
            }  

        }
        if ($request['user']->user_group_id == 1) {
            $actorUser = User::getUserApiTokenByID($request['user']['id']);
            $targetUser = User::getUserApiTokenByID($record['customer_id']);
        }else{
            $targetUser = User::getUserApiTokenByID($request['user']['getagent']['id']);
            $actorUser = User::getUserApiTokenByID($request['user']['id']);
        }
        if (count($targetUser) > 0) {
                //send push notification to research center
                $getNotificationSetting = NotificationSetting::getSetting($targetUser[0]->id); 
                if (!empty($getNotificationSetting)) {
                    if (isset($getNotificationSetting['notification_all'])) {
                        if ($getNotificationSetting['notification_all'] == 1) {
                            $custom_data = [
                                'record_id'     => 0,
                                'redirect_link' => NULL,
                                'identifier'    => 'create_buying_query',
                                'data'    => ['id'=>$record->id,'slug'=>$record->slug],
                            ];
                            $notification_data = [
                                'actor'            => $actorUser,
                                'actor_type'       => 'users',
                                'target'           => $targetUser,
                                'target_type'      => 'users',
                                'title'            => 'Buying query created',
                                'message'          => "".$actorUser[0]['name']." has added a buying query",
                                'reference_slug'   => $record->slug,
                                'reference_id'     => $record->id,
                                'custom_data'      => $custom_data,
                                'reference_module' => 'buyers',
                                'redirect_link'    => NULL,
                                'badge'            => '0'
                            ];
    
                            Notification::sendPushNotification('create_buying_query',$notification_data,$custom_data,$targetUser[0]->device_type);
                        }
                    }
                  
                }
                
            }
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
        if( !empty($postData['image_url']) ){
            $postData['image_url'] = '/storage/' . CustomHelper::uploadMedia('cms_users',$postData['image_url'],'50X50');
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
        $getBuyerId = Buyer::where('slug',$slug)->first();

        if ($request['user']->user_group_id == 1) {
            if (!empty($request["property_id"])) {

                $checkbuyerproperties = BuyerProperty::where('buyer_id',$getBuyerId->id)->first();
                if ($checkbuyerproperties) {
                    $buyerproperties = BuyerProperty::where('buyer_id',$getBuyerId->id)->where('is_tour','0')->forceDelete();
                }
                $getproperties =  explode(',' ,$request["property_id"]);
        
                    for( $i=0; $i < count($getproperties); $i++ ){
                        $checkbuyerproperties = BuyerProperty::where('buyer_id',$getBuyerId->id)->where('is_tour','1')->first();
                        if (!empty($checkbuyerproperties)) {
                            if ($checkbuyerproperties->property_id != $getproperties[$i]) {
                                $insert_properties[] =[
                                    'agent_id'   => $getBuyerId["agent_id"],
                                    'customer_id' => $getBuyerId['customer_id'],
                                    'buyer_id' => $getBuyerId['id'],
                                    'property_id' => $getproperties[$i],
                                    'slug' => rand(1,100).uniqid(),
                                ];
                            }
                        }else{
                            $insert_properties[] =[
                                'agent_id'   => $getBuyerId["agent_id"],
                                'customer_id' => $getBuyerId['customer_id'],
                                'buyer_id' => $getBuyerId['id'],
                                'property_id' => $getproperties[$i],
                                'slug' => rand(1,100).uniqid(),
                            ];
                        }
                    
                    }
                    BuyerProperty::insert($insert_properties);
            }else{
                $checkbuyerproperties = BuyerProperty::where('buyer_id',$getBuyerId->id)->first();
                if ($checkbuyerproperties) {
                    $buyerproperties = BuyerProperty::where('buyer_id',$getBuyerId->id)->forceDelete();
                }
            }
            ///////buying query notification by agent///
            $agentdata = User::getUserApiTokenByID($request['user']['id']);
            $clientdata = User::getUserApiTokenByID($request['customer_id']);
            
            if ($request['is_recommand'] == 0) {
                if (count($clientdata) > 0) {
                    //send push notification to client
                    $getNotificationSetting = NotificationSetting::getSetting($clientdata[0]->id); 
                    if ($getNotificationSetting['notification_all'] == 1) {
                            $custom_data = [
                                'record_id'     => 0,
                                'redirect_link' => NULL,
                                'identifier'    => 'update_buying_query',
                                'data'    => ['id'=>$getBuyerId->id,'slug'=>$getBuyerId->slug,],
                            ];
                            $notification_data = [
                                'actor'            => $agentdata,
                                'actor_type'       => 'users',
                                'target'           => $clientdata,
                                'target_type'      => 'users',
                                'title'            => 'Buying query updated',
                                'message'          => "".$agentdata[0]['name']." has updated buying query",
                                'reference_slug'   => $getBuyerId->slug,
                                'reference_id'     => $getBuyerId->id,
                                'custom_data'      => $custom_data,
                                'reference_module' => 'buyers',
                                'redirect_link'    => NULL,
                                'badge'            => '0'
                            ];

                            Notification::sendPushNotification('update_buying_query',$notification_data,$custom_data,$clientdata[0]->device_type);
                        }
                }
            }else{
                if (count($clientdata) > 0) {
                    //send push notification to client
                    $getNotificationSetting = NotificationSetting::getSetting($clientdata[0]->id); 
                    if ($getNotificationSetting['notification_all'] == 1) {
                            $custom_data = [
                                'record_id'     => 0,
                                'redirect_link' => NULL,
                                'identifier'    => 'home_recommended',
                                'data'    => ['id'=>$getBuyerId->id,'slug'=>$getBuyerId->slug,],
                            ];
                            $notification_data = [
                                'actor'            => $agentdata,
                                'actor_type'       => 'users',
                                'target'           => $clientdata,
                                'target_type'      => 'users',
                                'title'            => 'Home Recommended',
                                'message'          => "".$agentdata[0]['name']." recommended home on your buying query",
                                'reference_slug'   => $getBuyerId->slug,
                                'reference_id'     => $getBuyerId->id,
                                'custom_data'      => $custom_data,
                                'reference_module' => 'buyers',
                                'redirect_link'    => NULL,
                                'badge'            => '0'
                            ];

                            Notification::sendPushNotification('update_buying_query',$notification_data,$custom_data,$clientdata[0]->device_type);
                        }
                }
            }
            
        }

        if ($request['user']->user_group_id == 2) {
            $agentdata = User::getUserApiTokenByID($request['user']['getagent']['id']);
            $clientdata = User::getUserApiTokenByID($request['user']['id']);
            //send push notification to research center
            $getNotificationSetting = NotificationSetting::getSetting($agentdata[0]->id); 
            if ($getNotificationSetting['notification_all'] == 1) {
                    $custom_data = [
                        'record_id'     => 0,
                        'redirect_link' => NULL, 
                        'identifier'    => 'update_buying_query',
                        'data'    => ['id'=>$getBuyerId->id,'slug'=>$getBuyerId->slug,],
                    ];
                    $notification_data = [
                        'actor'            => $clientdata,
                        'actor_type'       => 'users',
                        'target'           => $agentdata,
                        'target_type'      => 'users',
                        'title'            => 'Buying query updated',
                        'message'          => "".$clientdata[0]['name']." has updated a buying query",
                        'reference_slug'   => $getBuyerId->slug,
                        'reference_id'     => $getBuyerId->id,
                        'custom_data'      => $custom_data,
                        'reference_module' => 'buyers',
                        'redirect_link'    => NULL,
                        'badge'            => '0'
                    ];

                Notification::sendPushNotification('update_buying_query',$notification_data,$custom_data,$agentdata[0]->device_type);
            }
        }
      
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
