<?php
 
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppContent;

class AppContentController extends Controller
{
    public function getContent(Request $request){
        $param_rules['identifier'] = 'required|in:term_and_condition,privacy_policy';
		$response = $this->__validateRequestParams($request->all(),$param_rules);
		if( $this->__is_error  )
			return $response;
        $data = AppContent::getContentByIdentifier($request["identifier"]);

        $this->__is_paginate   = false;
		$this->__collection = false;
        return $this->__sendResponse($data,200,__('app.success_listing_message'));
    }
}
