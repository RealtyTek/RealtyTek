<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Http\Controllers\RestController;
use App\Models\Property;

class PropertyController extends RestController
{

    public function __construct(Request $request)
    {
        parent::__construct('Property');
        $this->__request     = $request;
        $this->__apiResource = 'Property';
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
                        'title'            => 'required|min:3|max:50',
                        'image_url'        => 'image|file|max:50000',
                        'address'          => 'required|min:3',
                        'city'             => 'required|min:3|max:50',
                        'state'            => 'required|min:2|max:50',
                        'zipcode'          => 'required|min:2|max:50',
                        'mls_detail'       => 'required|min:3|max:1000',
                        'asking_price'     => 'required|min:2|max:50',
                        'sell_date'        => 'required|date_format:Y-m-d',
                        'cma_appointment'  => 'required|min:2|max:100',
                        'property_type'    =>'required',
                    ]);
                }else{
                    $validator = Validator::make($this->__request->all(), [
                        'image_url'        => 'image|file|max:50000',
                        'title'            => 'required|min:3|max:50',
                        'address'          => 'required|min:3',
                        'city'             => 'required|min:3|max:50',
                        'state'            => 'required|min:2|max:50',
                        'zipcode'          => 'required|min:3|max:50',
                        'mls_detail'       => 'required|min:3|max:1000',
                        'asking_price'     => 'required|min:2|max:50',
                        'sell_date'        => 'required|date_format:Y-m-d',
                        'cma_appointment'  => 'required|min:2|max:100',
                        'property_type'    =>'required',
                    ]);
                }

                break;
            case 'PUT':
                $validator = Validator::make($this->__request->all(), [
                    'customer_id'       => 'required|exists:users,id,deleted_at,NULL',
                    'title'            => 'required|min:3|max:50',
                    'image_url'         => 'image|file|max:50000',
                    'address'          => 'required|min:3',
                    'city'              => 'required|min:3|max:50',
                    'state'            => 'required|min:2|max:50',
                    'zipcode'           => 'required|min:3|max:50',
                    'mls_detail'        => 'required|min:3|max:1000',
                    'asking_price'      => 'required|min:2|max:50',
                    'sell_date'        => 'required|date_format:Y-m-d',
                    'cma_appointment'   => 'required|min:2|max:100',
                    'property_type'   =>'required',
                ]);
                break;
            // case 'INDEX':
            //     $validator = Validator::make($this->__request->all(), [
            //         'property_type'      => 'required',
            //     ]);
            //     break;
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
        $propertyChecking = Property::propertyChecking($request['user']->id,$slug);
        if( !isset($propertyChecking->id) ){
            $this->__is_error = true;
            return $this->__sendError('Validation Message',['message' => __('app.invalid_request')],400);
        }
    }

    public function updateInitiateContract()
	{
        $param_rules['buyer_id'] = 'required|exists:buyers,id,deleted_at,NULL,agent_id,'.$this->__request["user"]->id;
        $param_rules['initiate_contract_status'] = 'required|in:1,0';
        $param_rules['property_id'] = 'required|exists:properties,id,deleted_at,NULL,agent_id,'.$this->__request["user"]->id.'';

		$response = $this->__validateRequestParams($this->__request->all(),$param_rules);
		if( $this->__is_error  )
			return $response;

        $params = $this->__request->all();
        $checkPropertyStatus = Property::checkPropertyStatus($params['property_id']);

        if (!empty($checkPropertyStatus)){
            $this->__is_error = true;
            return $this->__sendError('Validation Message',['message' => __('You can not initiate this property')],400);
        }
        $checkInitiateProperty = Property::checkInitiateProperty($params['property_id']);

        if (!empty($checkInitiateProperty)){
            $this->__is_error = true;
            return $this->__sendError('Validation Message',['message' => __('This property has been sold')],400);
        }

        Property::updateProperty($params['user']->id,$params['buyer_id'],$params['property_id'],$params['initiate_contract_status']);

        $getProperty = Property::getInitiateBuyerProperty($params['user']->id,$params['buyer_id']);

        $this->__is_paginate   = false;
        $this->__is_collection = false;
        return $this->__sendResponse($getProperty,200,__('app.success_listing_message'));
    }

    public function getInitiateBuyerPropertyList(Request $request)
    {

        $param_rules['buyer_id'] = 'required|exists:buyers,id,deleted_at,NULL';
		$response = $this->__validateRequestParams($this->__request->all(),$param_rules);

        if( $this->__is_error  )
			return $response;

        $params = $this->__request->all();

        $getProperty = Property::getInitiateBuyerProperty($params['user']->id,$params['buyer_id']); 
        $this->__apiResource = 'InitiateBuyerProperty';

        $this->__is_paginate   = false;
        $this->__is_collection = false;
        return $this->__sendResponse($getProperty,200,__('app.success_listing_message'));
    }

    public function updateContractStatus(){

        $param_rules['property_id'] = 'required|exists:properties,id,deleted_at,NULL,agent_id,' . $this->__request['user']->id;
        $param_rules['contract_offer'] = 'nullable|date';
        $param_rules['contract_countered'] = 'nullable|date';
        $param_rules['contract_accepted'] = 'nullable|date';
        $param_rules['contract_executed'] = 'nullable|date';
        $param_rules['offer_decline'] = 'nullable|date';
        $param_rules['inspection'] = 'nullable|date';
        $param_rules['appraisal'] = 'nullable|date';
        $param_rules['final_walk_thru'] = 'nullable|date';
        $param_rules['sattlement_date'] = 'nullable|date';
        $param_rules['add_comment'] = 'nullable|min:2';
        $param_rules['contract_status'] = 'required|min:2|max:10';
        $param_rules['contract_status_updated_date'] = 'nullable|date';

		$response = $this->__validateRequestParams($this->__request->all(),$param_rules);
		if( $this->__is_error  )
			return $response;

        $params = $this->__request->all();
        $data = Property::updatePropertyStatus($params);
        // print_r($data); die;
        $this->__is_paginate   = false;
        $this->__is_collection = false;
        return $this->__sendResponse($data,200,__('app.success_update_message'));


    }

    public function updateLoanInfo()
	{
        $params = $this->__request->all();
        $param_rules['property_id'] = 'required|exists:properties,id,deleted_at,NULL,agent_id,' . $params['user']->id;
        $param_rules['company'] = 'nullable|min:3|max:150';
        $param_rules['contact'] = 'nullable|min:3|max:150';
        $param_rules['contact_number'] = 'nullable|regex:/^(\+?\d{1,3}[-])\d{9,11}$/';
        $param_rules['sale_price'] = 'nullable|min:3|max:50';
        $param_rules['financing'] = 'nullable|min:3|max:150';
        $param_rules['emd_submitted'] = 'nullable|min:3|max:150';
        $param_rules['down_payment'] = 'nullable|min:3|max:150';
        $param_rules['loan_status'] = 'nullable|min:2|max:50';
        $param_rules['loan_status_updated_date'] = 'nullable|date';
		$response = $this->__validateRequestParams($this->__request->all(),$param_rules);
		if( $this->__is_error  )
			return $response;

        $data = Property::updatePropertyLoanInfo($params);

        $this->__is_paginate   = false;
        $this->__is_collection = false;
        return $this->__sendResponse($data,200,__('app.success_update_message'));
    }

    public function getPropertyContractLoanInfo(){
        $params = $this->__request->all();
        $param_rules['property_id'] = 'required';
		$response = $this->__validateRequestParams($this->__request->all(),$param_rules);
		if( $this->__is_error)
			return $response;
        $data = Property::getPropertyLoanInfo($params['property_id']);

        $this->__is_paginate   = false;
        $this->__is_collection = false;
        return $this->__sendResponse($data,200,__('app.success_update_message'));
    }

    public function getPropertyContractStatus(){
        $params = $this->__request->all();
        $param_rules['property_id'] = 'required';
		$response = $this->__validateRequestParams($this->__request->all(),$param_rules);
		if( $this->__is_error)
			return $response;
        $data = Property::getPropertyContractStatus($params['property_id']);

        $this->__is_paginate   = false;
        $this->__is_collection = false;
        return $this->__sendResponse($data,200,__('app.success_update_message'));
    }

    public function updatePropertyChangeStatus(Request $request){

        $params = $this->__request->all();
        $param_rules['slug'] = 'required|exists:properties,slug';
        $param_rules['status'] = 'required|in:0,1';

		$response = $this->__validateRequestParams($this->__request->all(),$param_rules);
		if( $this->__is_error)
			return $response;

        $data = Property::updatePropertyChangeStatus($request['user']->id,$params['slug'],$params['status']);
        $this->__is_paginate   = false;
        $this->__is_collection = false;
        return $this->__sendResponse($data,200,__('app.success_update_message'));

    }


}
