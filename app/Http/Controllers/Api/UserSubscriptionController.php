<?php

namespace App\Http\Controllers\Api;

use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Http\Controllers\RestController;
use App\Models\{UserSubscription,User};

class UserSubscriptionController extends RestController
{

    public function __construct(Request $request)
    {
        parent::__construct('UserSubscription');
        $this->__request     = $request;
        $this->__apiResource = 'UserSubscription';
    }

    /**
     * This function is used for validate restfull request
     * @param $action
     * @param string $slug
     * @return array
     */
    public function validation($action,$slug=0)
    {
        $validator = [];
        switch ($action){
            case 'POST':
                $validator = Validator::make($this->__request->all(), [
                    'attribute'        => 'required',
                ]);
                break;
            case 'PUT':
                $validator = Validator::make($this->__request->all(), [
                    'attribute'     => 'required',
                ]);
                break;
        }
        return $validator;
    }

    /**
     * @param $request
     */
    public function beforeIndexLoadModel($request)
    {

    }

    /**
     * @param $request
     */
    public function beforeStoreLoadModel($request)
    {

    }

    /**
     * @param $request
     */
    public function beforeShowLoadModel($request,$slug)
    {

    }

    /**
     * @param $request
     */
    public function beforeUpdateLoadModel($request,$slug)
    {

    }

    /**
     * @param $request
     */
    public function beforeDestroyLoadModel($request,$slug)
    {

    }

    public function storeUserSubscription(Request $request)
    {
        $this->__apiResource = 'Auth'; 

        $param_rules['subscription_package_id'] = 'required'; 
        $param_rules['gateway_request']         = 'json';
        $param_rules['gateway_response']        = 'required';
        $param_rules['device_type']             = 'required';

        $response = $this->__validateRequestParams($request->all(), $param_rules);

        if($this->__is_error == true)
            return $response;
        
        //file_put_contents(public_path('purchases/'.$request['user']->id . '.json'), $request['gateway_response']);
        $gateway_response = json_decode($request['gateway_response'],true);
            
        if( $request['device_type'] == 'android' ){
            $data_android =  json_decode($gateway_response['transactionReceipt'],true);
            $gateway_response['transactionId'] = $data_android['orderId'];
            $gateway_response['original_transaction_id'] = $gateway_response['purchaseToken'];
        }else{
            $gateway_response['transactionId'] = $gateway_response['transaction_id'];
            $gateway_response['original_transaction_id'] = $gateway_response['original_transaction_id'];
        }   

        $checkSubscription = UserSubscription::checkSubscription($gateway_response['original_transaction_id']);
              
        if( isset($checkSubscription->id) )
        {
            if( $checkSubscription->user_id != $request['user']['id'] )
            {
                if( $request['device_type'] == 'android' )
                    $data['message'] = 'This Google account is already associated with another user.';
                else
                    $data['message'] = 'This Apple account is already associated with another user.';
                
                return $this->__sendError('Error Message',['message' => $data['message']]);    
            } 
            if( $checkSubscription->gateway_transaction_id != $gateway_response['transactionId'] )
            {
                $data = UserSubscription::addUserSubscription($request->all());

                if($data['error'] == 1){
                    return $this->__sendError('Error Message',['message' => $data['message']], 400);
                }

                $this->__is_collection  = false;
                $this->__is_paginate    = false;

                $user = User::getUserByEmail($request->user->email);
                // print_r($user); die;
                return $this->__sendResponse($user,200, 'User has been subscribe successfully');
            }   
            if( strtotime(date('Y-m-d'))  >= strtotime($checkSubscription->subscription_expiry_date) ){ 
                $array = [
                    'subscription_expiry_date'=>$checkSubscription->subscription_expiry_date,
                    'current_time'=>date('Y-m-d'),
                ];
                // print_r($array); die;
                \DB::table('user_subscriptions')
                    ->where('id',$checkSubscription->id)
                    ->update([
                        'status' => 'expired'
                    ]);
         
            $this->__is_collection  = false;
            $this->__is_paginate    = false;

            $user = User::getUserByEmail($request->user->email);
            return $this->__sendResponse($user,200, 'Your subscription has been expired.');
            }
            else
            {
                $this->__is_collection  = false;
                $this->__is_paginate    = false;
             
                $user = User::getUserByEmail($request->user->email);              
                return $this->__sendResponse($user,200, 'You have already subscribed to this package.');         
            }
            
        }
        else
        {
            $data = UserSubscription::addUserSubscription($request->all());
            // print_r($data); die;
            if($data['error'] == 1){
                return $this->__sendError('Error Message',['message' => $data['message']], 400);
            }

            $this->__is_collection  = false;
            $this->__is_paginate    = false;

            $user = User::getUserByEmail($request->user->email);
            return $this->__sendResponse($user,200, 'User has been subscribe successfully');
        }        

    }
}
