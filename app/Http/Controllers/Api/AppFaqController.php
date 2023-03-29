<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AppFaq;

class AppFaqController extends Controller
{
    public function getFaqs(){
        $data = AppFaq::all();
        $this->__is_paginate   = false;
		$this->__collection = false;
        return $this->__sendResponse($data,200,__('app.success_listing_message'));
    }
}
