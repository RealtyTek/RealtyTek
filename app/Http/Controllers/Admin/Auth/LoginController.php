<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\CmsModule;
use App\Models\CmsUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if( $request['auth_token'] != config('constants.admin_auth_token') )
            return abort(404);

        if( $request->isMethod('post') )
            return self::_login($request);

        return $this->__cbAdminView('auth.login'); 
    }

    private function _login($request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $auth = CmsUser::auth($request['email'],$request['password']);
        if( $auth ){
            if (Auth::guard('cms_user')->user()->cms_role_id !==1) {
                if (Auth::guard('cms_user')->user()->is_email_verify =='0') {
                    Auth::guard('cms_user')->logout();
                    return redirect()->back()->with('error','Your account is not verified. Kindly check your inbox and verify your account');
                }
                return redirect()->route('admin.dashboard');
            }else{
                return redirect()->route('admin.dashboard');
            }

        }else{
            return redirect()->back()->with('error','Invalid credential');
        }
    }
}
