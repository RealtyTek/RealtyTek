<?php

namespace App\Http\Controllers;

use App\Models\ResetPassword;
use App\Models\{User,CmsUser};
use App\Models\UserApiToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

class UserController extends Controller
{
    public function resetPassword(Request $request, $token)
    {
        $checkRequest = ResetPassword::getUserRequest($token);
        if( !isset($checkRequest->id) )
            return redirect('/')->with('error',__('app.invalid_new_password_link'));

        if( strtotime(Carbon::now()) > strtotime(Carbon::make($checkRequest->created_at)->addHour()) )
            return redirect('/')->with('error',__('app.invalid_new_password_link'));

        if( $request->isMethod('post') )
            return self::_submitResetPassword($request,$checkRequest);

        if( $checkRequest->is_email_verify == 0 )
            User::updateUser($checkRequest->user_id,['is_email_verify' => '1']);

        return view('reset-password');
    }

    public function _submitResetPassword($request, $reset_pass_record)
    {
        $custom_messages = [
            'new_password.regex' => __('app.password_regex')
        ];
        $validator = Validator::make($request->all(), [
            'new_password'     => [
                'required',
                'regex:/^(?=.*[A-Z])(?=.*[!@#$&*])(?=.*[0-9])(?=.*[a-z]).{8,150}$/'
            ],
            'confirm_password' => 'required|same:new_password',
        ],$custom_messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator) ->withInput();
        }
        //update new password
        User::updateUserByEmail($reset_pass_record->email,['password' => Hash::make($request['new_password'])]);
        //delete reset password request
        $reset_pass_record->forceDelete();
        //delete all api token
        UserApiToken::where('user_id',$reset_pass_record->user_id)->forceDelete();

        return redirect('/')->with('success',__('app.password_success_msg'));
    }

    public function verifyEmail($token)
    {
        $email = decrypt($token);
        $user = User::getUserByEmail($email); 
        if( !isset($user->id) )
            return redirect('/');
		if ($user->user_group_id == 1) {
            $CmsUser = CmsUser::updateCmsUser($user->id);
        }
        
		$data = [
			'is_email_verify' => 1,
			'email_verify_at' => Carbon::now(),
			'invitation_acceptance' => 1
		];
        User::updateUser($user->id,$data);
        return redirect('/')->with('success',__('app.verify_user_success_msg'));
    }
}
