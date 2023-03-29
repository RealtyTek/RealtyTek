<?php

namespace App\Models\Hooks\Api;
use Carbon\Carbon;
use App\Models\{Buyer,Appointment,User,Notification,NotificationSetting};

class AppointmentHook
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
        if ($request['user']->user_group_id == 1) {
            $query->with('property','client')->where('agent_id',$request["user"]->id);
            if(isset($request['status']) && !empty($request['status'])){
                $query->where('status',$request['status']);
            }

            if(isset($request['year']) && !empty($request['year']) && isset($request['month']) && !empty($request['month'])){
                $query->whereYear('appointment_date',$request["year"])->whereMonth('appointment_date',$request["month"]);
            }
        }
        if ($request['user']->user_group_id == 2) {
            $query->with('property')->where('customer_id',$request["user"]->id);
            if(isset($request['status']) && !empty($request['status'])){
                $query->where('status',$request['status']);
            }

            if(isset($request['year']) && !empty($request['year']) && isset($request['month']) && !empty($request['month'])){
                $query->whereYear('appointment_date',$request["year"])->whereMonth('appointment_date',$request["month"]);
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
        $getbuyerdetail = Buyer::getBuyerDetail($postdata['buyer_id']);
        $postdata['agent_id']  = $getbuyerdetail["agent_id"];
        $postdata['customer_id']  = $getbuyerdetail["customer_id"];
        $postdata['slug']  = rand() . uniqid();
        $postdata['created_at']  = Carbon::now();
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
        if ($request['user']->user_group_id == 2) {
            $agentdata = User::getUserApiTokenByID($request['user']['getagent']['id']);
            $clientdata = User::getUserApiTokenByID($request['user']['id']);
            //send push notification to research center
            $getNotificationSetting = NotificationSetting::getSetting($clientdata[0]->id); 
            if ($getNotificationSetting['notification_all'] == 1) {
                    $custom_data = [
                        'record_id'     => 0,
                        'redirect_link' => NULL, 
                        'identifier'    => 'create_appointment',
                        'data'    => ['id'=>$record->id,'slug'=>$record->slug,],
                    ];
                    $notification_data = [
                        'actor'            => $clientdata,
                        'actor_type'      => 'users',
                        'target'           => $agentdata,
                        'target_type'      => 'users',
                        'title'            => 'Appointment request',
                        'message'          => "".$clientdata[0]['name']." create an appointment request",
                        'reference_slug'   => $record->slug,
                        'reference_id'     => $record->id,
                        'custom_data'      =>$custom_data,
                        'reference_module' => 'appointments',
                        'redirect_link'    => NULL,
                        'badge'            => '0'
                    ];
                
                Notification::sendPushNotification('create_appointment',$notification_data,$custom_data,$agentdata[0]->device_type);
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
        if ($request['user']->user_group_id == 2) {
            $getbuyerdetail = Buyer::getBuyerDetail($postData['buyer_id']);
            $postData['agent_id']  = $getbuyerdetail["agent_id"];
            $postData['customer_id']  = $getbuyerdetail["customer_id"];
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
        $getAppointment = Appointment::getAppointment($slug);
        if ($request['status'] == "accept") {
            if ($request['user']->user_group_id == 1) {
                $agentdata = User::getUserApiTokenByID($request['user']['id']);
                $clientdata = User::getUserApiTokenByID($getAppointment['customer_id']);
                // print_r($agentdata); die;
                //send push notification to research center
                $getNotificationSetting = NotificationSetting::getSetting($clientdata[0]->id); 
                if ($getNotificationSetting['notification_all'] == 1) {
                        $custom_data = [ 
                            'record_id'     => 0,
                            'redirect_link' => NULL,
                            'identifier'    => 'accept_appointment',
                            'data'    => ['id'=>$getAppointment->id,'slug'=>$getAppointment->slug,],

                        ];
                        $notification_data = [
                            'actor'            => $agentdata,
                            'actor_type'      => 'users',
                            'target'           => $clientdata,
                            'target_type'      => 'users',
                            'title'            => 'Appointment request accepted',
                            'message'          => "".$agentdata[0]['name']." has accept Your appointment",
                            'reference_slug'    => $getAppointment->slug,
                            'reference_id'     => $getAppointment->id,
                            'custom_data'      =>$custom_data,
                            'reference_module' => 'appointment',
                            'redirect_link'    => NULL,
                            'badge'            => '0'
                        ];
                        
            
                    Notification::sendPushNotification('accept_appointment',$notification_data,$custom_data,$clientdata[0]->device_type);
                }
            }
        }
        // if ($request['user']->user_group_id == 1) {
        //     $targetUsers = User::getUserAndBadgeApiTokenByID($getAppointment['customer_id']);
        // }else{
        //     $targetUsers = User::getUserAndBadgeApiTokenByID($getAppointment['agent_id']);
        // }
        // if( count($targetUsers) ){
        //     //send push notification to research center
        //     $notification_data = [
        //         'actor'            => $request['user'],
        //         'actor_type'      => 'users',
        //         'target'           => $targetUsers['user'],
        //         'target_type'      => 'users',
        //         'title'            => env('APP_NAME'),
        //         'message'          => __('app.test_notification_msg'),
        //         'reference_id'     => 0,
        //         'reference_module' => 'identifier',
        //         'redirect_link'    => NULL,
        //         'badge'            => '0'
        //     ];
        //     $custom_data = [
        //         'record_id'     => 0,
        //         'redirect_link' => NULL,
        //         'identifier'    => 'identifier',
        //     ];
        //     Notification::sendPushNotification('identifier',$notification_data,$custom_data,'android');
        // }

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
