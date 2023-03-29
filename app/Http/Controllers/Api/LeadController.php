<?php

namespace App\Http\Controllers\Api;

use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Http\Controllers\RestController;
use App\Models\User;
use Illuminate\Support\Str;
use CustomHelper;
use App\Models\Lead;
use Illuminate\Support\Facades\Hash;


class LeadController extends RestController
{

    public function __construct(Request $request)
    {
        parent::__construct('Lead');
        $this->__request     = $request;
        $this->__apiResource = 'Lead';
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
                    'user_group_id' => 'required|in:2',
                    'image_url' 	=> 'image|file|max:50000',
                    'name'          => ['required','min:3','max:50','regex:/^([A-Za-z0-9\s])+$/'],
                    'email'         => ['required', 'email',
                        Rule::unique('users')->whereNull('deleted_at')
                    ],
                    'mobile_no'     => [
                        'required',
                        Rule::unique('users')->whereNull('deleted_at'),
                        'regex:/^(\+?\d{1,3}[-])\d{9,11}$/'
                    ],
                    'address'   => 'required|min:3|max:200',
                ]);
                break;
            case 'PUT':
                $validator = Validator::make($this->__request->all(), [
                    'image_url' => 'image|file|max:50000',
                    'name'          => ['required','min:3','max:50','regex:/^([A-Za-z0-9\s])+$/'],
                    'mobile_no'     => [
                        'required',
                        Rule::unique('users')->ignore($slug,'slug')->whereNull('deleted_at'),
                        'regex:/^(\+?\d{1,3}[-])\d{9,11}$/'
                    ],
                    'address'   => 'required|min:3|max:200',
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
        $leadChecking = Lead::leadChecking($request['user']->id,$slug);
        if( !isset($leadChecking->id) ){
            $this->__is_error = true;
            return $this->__sendError('Validation Message',['message' => __('app.invalid_request')],400);
        }
    }

    public function sendInvitation()
	{
		$param_rules['lead_id'] = 'required';
		$response = $this->__validateRequestParams($this->__request->all(),$param_rules);
		if( $this->__is_error  )
			return $response;

		$params = $this->__request->all();
        $user = User::getAgentLead($params['user']->id,$params['lead_id']);
        if( !isset($user->id) )
			return $this->__sendError('Validation Message',['message' => __('app.invalid_request')],400);

		$password = Str::random(8); 
		User::updateUser($user->id,['password' => Hash::make($password)]);

		//send invitation email
		$mail_params['NAME'] 		= $user->name;
        $mail_params['AGENT_NAME']  = $params['user']->name;
        $mail_params['EMAIL']       = $user->email;
		$mail_params['PASSWORD']    = $password;
		$mail_params['VERIFY_LINK'] = route('verifyEmail',['name' => encrypt($user->email)]);
        $mail_params['APP_NAME']    = env('APP_NAME');
        CustomHelper::sendMail($user->email,'customer_invite',$mail_params);

		$this->__is_paginate   = false;
		$this->__is_collection = false;
		return $this->__sendResponse($user,200,__('app.send_invitation_msg'));
	}
}
