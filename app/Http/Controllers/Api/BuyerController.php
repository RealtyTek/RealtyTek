<?php

namespace App\Http\Controllers\Api;

use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Http\Controllers\RestController;
use App\Models\Buyer;
class BuyerController extends RestController
{

    public function __construct(Request $request)
    {
        parent::__construct('Buyer');
        $this->__request     = $request;
        $this->__apiResource = 'Buyer';
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
                if ($this->__request['user']->user_group_id == 1) {
                    $validator = Validator::make($this->__request->all(), [
                        'customer_id'      => 'required|exists:users,id,deleted_at,NULL,parent_id,'.$this->__request['user']->id.'',
                        'requirements'     => 'required|min:3|max:150',
                        'price'            => 'required|min:2|max:50',
                        'house_type'       => 'required|min:1|max:50',
                        'move_date'        => 'required|min:2|max:50',
                        'first_time_buyer' => 'required|min:2|max:50',
                        'pre_approved'     => 'required|min:2|max:50',
                        'company_name'     => 'min:2|max:50',
                        'amount'           => 'min:2|max:50',
                        'address'           => 'required',
                    ]);
                }else{
                    $validator = Validator::make($this->__request->all(), [
                        'requirements'     => 'required|min:3|max:150',
                        'price'            => 'required|min:2|max:50',
                        'house_type'       => 'required|min:1|max:50',
                        'move_date'        => 'required|min:2|max:50',
                        'first_time_buyer' => 'required|min:2|max:50',
                        'pre_approved'     => 'required|min:2|max:50',
                        'company_name'     => 'min:2|max:50',
                        'amount'           => 'min:2|max:50',
                        'address'           => 'required',

                    ]);
                }
                break;
            case 'PUT':
                if ($this->__request['user']->user_group_id == 1) {
                    $validator = Validator::make($this->__request->all(), [
                        'customer_id'      => 'required|exists:users,id,deleted_at,NULL,parent_id,'.$this->__request['user']->id.'',
                        // 'property_id'      => 'required',
                        'requirements'     => 'required|min:3|max:150',
                        'price'            => 'required|min:2|max:50',
                        'house_type'       => 'required|min:1|max:50',
                        'move_date'        => 'required|min:2|max:50',
                        'first_time_buyer' => 'required|min:2|max:50',
                        'pre_approved'     => 'required|min:2|max:50',
                        'company_name'     => 'min:2|max:50',
                        'amount'           => 'min:2|max:50',
                        'is_recommand'     => 'required|in:0,1' 
                    ]);
                }else{
                    $validator = Validator::make($this->__request->all(), [
                        'requirements'     => 'required|min:3|max:150',
                        'price'            => 'required|min:2|max:50',
                        'house_type'       => 'required|min:1|max:50',
                        'move_date'        => 'required|min:2|max:50',
                        'first_time_buyer' => 'required|min:2|max:50',
                        'pre_approved'     => 'required|min:2|max:50',
                        'company_name'     => 'min:2|max:50',
                        'amount'           => 'min:2|max:50',
                    ]);
                }
                break;
            case 'INDEX':
                $validator = Validator::make($this->__request->all(), [
                    'buyer_type'      => 'required|in:buyers,under_contract',
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
        $buyerChecking = Buyer::buyerChecking($request['user']->id,$slug);

        // if( !isset($buyerChecking->id) ){
        //     $this->__is_error = true;
        //     return $this->__sendError('Validation Message',['message' => __('app.invalid_request')],400);
        // }
    }

    public function getRecommendedHome()
	{
		$param_rules['buyer_id'] = 'required';
		$response = $this->__validateRequestParams($this->__request->all(),$param_rules);
		if( $this->__is_error  )
			return $response;

        $records = buyer::getRecommendedHome($this->__request['user']->user_group_id,$this->__request['user']->id,$this->__request->buyer_id);
        
        $this->__is_paginate   = false;
		$this->__is_collection = false;
        return $this->__sendResponse($records,200,__('app.success_listing_message'));

    }

    public function getTourHome()
	{
		$param_rules['buyer_id'] = 'required';
		$response = $this->__validateRequestParams($this->__request->all(),$param_rules);
		if( $this->__is_error  )
			return $response; 
        if ($this->__request['user']->user_group_id == 1) {
            $records = buyer::getTourHome($this->__request['user']->id,$this->__request->buyer_id);
        }else{
            $records = buyer::getCustomerTourHome($this->__request['user']->id,$this->__request->buyer_id);
        }
        $this->__is_paginate   = false;
		$this->__is_collection = false;
        return $this->__sendResponse($records,200,__('app.success_listing_message'));
    }



}
