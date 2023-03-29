<?php

namespace App\Http\Middleware;

use App\Http\Models\ApiUser;
use App\Models\User;
use App\Models\UserApiToken;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomAuthentication
{
    /**
     * The authentication factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth , $request;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth, Request $request)
    {
        $this->auth    = $auth;
        $this->request = $request;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if($this->authenticate($guards)){
            return $this->authenticate($guards);
        }
        return $next($request);
    }

    protected function authenticate(array $guards)
    {
        if (empty($guards)) {
            return $this->auth->authenticate();
        }
        foreach ($guards as $guard) {
            if($guard == "cms_user"){
                return  $this->adminAuth($guard);
            }else if($guard == "web"){
                return $this->webAuth($guard);
            }else if($guard == "api"){
                return $this->apiAuth();
            }
        }
    }

    /**
     * This function is used for validate session based authentication for admin user
     * @return \Illuminate\Http\JsonResponse
     */
    protected function adminAuth($guard)
    {
        if ($this->auth->guard($guard)->guest()) {
            return redirect('/')->with('info','Your session has been expired');
        }
    }

    /**
     * This function is used for validate session based authentication for web user
     * @return \Illuminate\Http\JsonResponse
     */
    protected function webAuth($guard)
    {
        if ($this->auth->guard($guard)->guest()) {
            return redirect()->guest('/');
        }
    }

    /**
     * This function is used for validate json web token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function apiAuth()
    {
        $api_token    = $this->request->header('user-token');
        if( empty($api_token) ){
            return response()->json([
                'code'    => 401,
                'message' => 'Unauthorized',
                'data'    => [ 'auth' => __('app.authorize_header_missing') ]
            ],401);
        }

        $user      = User::getUserByApiToken($api_token);
        if(!isset($user->id))
        {
            return response()->json([
                'code'    => 401,
                'message' => 'Unauthorized',
                'data'    => [ 'auth' => __('app.authorize_header_invalid') ]
            ],401);
        }
        if (\App::environment('production')) {
            $check_api_token  = UserApiToken::generateApiToken($user->id,$this->request->ip(),$this->request->header('token'),$user->created_at);
            if( $check_api_token != $api_token ){
                return response()->json([
                    'code'    => 401,
                    'message' => 'Unauthorized',
                    'data'    => [ 'auth' => __('app.authorize_header_invalid') ]
                ],401);
            }
        }

        if( $user->status != 1 ){
            return response()->json([
                'code'    => 401,
                'message' => 'Unauthorized',
                'data'    => [ 'auth' => __('app.user_account_disabled') ]
            ],401);
        }
        if( env('VERIFICATION_TYPE') == 'email' ){
            if($user->is_email_verify != 1 ){
                return response()->json([
                    'code'    => 401,
                    'message' => 'Unauthorized',
                    'data'    => [ 'auth' => __('app.email_not_verified') ]
                ],401);
            }
        }
        $this->request['api_token'] = $api_token;
        $this->request['user'] = $user;

    }
}
