<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\LenderStateDisclosure;


class lendersStateDisclosureController extends Controller
{
    public function storeDisclosure(Request $request){
        $param_rules['link'] = 'url';
        // $param_rules['document'] = '';
		$response = $this->__validateRequestParams($request->all(),$param_rules);
		if( $this->__is_error  )
			return $response;
        $data = LenderStateDisclosure::storeLendersDisclosure($request["user"]->id,$request['title'],$request['link'],$request['document'],$request['filename']);
        // print_r($data);
        $this->__is_paginate   = false;
		$this->__collection = false;
        return $this->__sendResponse($data,200,__('app.success_listing_message'));
    }

    public function getDisclosure(Request $request){
        $data = LenderStateDisclosure::getLendersDisclosure($request["user"]->id,$request["user"]->user_group_id);
        $this->__is_paginate   = false;
		$this->__collection = false;
        return $this->__sendResponse($data,200,__('app.success_listing_message'));
    }

    public function deleteDisclosure(Request $request){

        $param_rules['type'] = 'required|in:document,link';
        if ($request["type"] == "document") {
            $param_rules['record_id'] = 'required|exists:media,id,deleted_at,NULL,id,'. $request["record_id"];
        }else{
            $param_rules['record_id'] = 'required|exists:lenders_state_disclosure,id,deleted_at,NULL,id,'. $request["record_id"];
        }

        $response = $this->__validateRequestParams($request->all(),$param_rules);
		if( $this->__is_error  )
			return $response;

        $data = LenderStateDisclosure::deleteLendersDisclosure($request["user"]->id,$request["type"],$request["record_id"]);
        $this->__is_paginate   = false;
		$this->__collection = false;
        return $this->__sendResponse($data,200,__('app.success_listing_message'));
    }


    
}
