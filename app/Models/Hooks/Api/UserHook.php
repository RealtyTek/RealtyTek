<?php

namespace App\Models\Hooks\Api;

use App\Helpers\CustomHelper;
use App\Models\{UserApiToken,CmsUser,NotificationSetting};
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

class UserHook
{
    private $_model,
            $except_update_params = [
				'user_group_id',
				'parent_id',
                'username',
                'slug',
                'email',
                'password',
                'status',
                'is_email_verify',
                'is_mobile_verify',
                'mobile_otp',
                'email_otp',
                'remember_token',
            ];

    public function __construct($model)
    {
        $this->_model = $model;
    }

    /*
   | ----------------------------------------------------------------------
   | Hook for manipulate query of index result
   | ----------------------------------------------------------------------
   | @query   = current sql query
   | @request = laravel http request class
   |
   */
    public function hook_query_index(&$query,$request, $slug='') {
        //Your code here
		$base_url    = URL::to('/');
		$placeholder = URL::to('images/user-placeholder.png');
        $logo = URL::to('images/user-logo.png');

        $query->select('users.*');
        //check same user
        if( $request['user']->slug == $slug ){
            $query->selectRaw("IF(image_url IS NOT NULL, CONCAT('$base_url',image_url),'$placeholder') AS image_url,
                IF(logo_url IS NOT NULL, CONCAT('$base_url',logo_url),'$logo') AS logo_url,
				api_token,device_type,device_token,platform_type,platform_id")
                ->join('user_api_token AS uat','uat.user_id','=','users.id')
                ->where('uat.api_token',$request['api_token']);
        }
        if( $slug == '' ){
            $query->where('id','!=',$request['user']->id);
        }

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for manipulate data input before add data is execute
    | ----------------------------------------------------------------------
    | @arr
    |
    */
    public function hook_before_add($request,&$postdata)
    {

        //set data
        $postdata['username']   = $this->_model::generateUniqueUserName($postdata['name']);
        $postdata['slug']       = $postdata['username'];
        $postdata['password']   = Hash::make($postdata['password']);
        $postdata['created_at'] = Carbon::now();
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after add public static function called
    | ----------------------------------------------------------------------
    | @record
    |
    */
    public function hook_after_add($request,$record)
    {
        //update notification setting
        $params['notification_setting']['notification_all'] = 1;      
        $params['user_id'] = $record->id;
        NotificationSetting::storeNewUserSetting($params); 

        CmsUser::CreateCmsAgent($record);
        $api_token  = UserApiToken::generateApiToken($record->id,$request->ip(),$request->header('token'),$record->created_at);
        $request['api_token'] = $api_token;
        $request['user']      = $record;
        //insert api token
        \DB::table('user_api_token')
            ->insert([
                'user_id'       => $record->id,
                'api_token'     => $api_token,
                'refresh_token' => UserApiToken::generateRefreshToken($record->id),
                'udid'          => $request->header('token'),
                'device_type'   => $request['device_type'],
                'device_token'  => $request['device_token'],
                'platform_type' => !empty($request['platform_type']) ? $request['platform_type'] : 'custom',
                'platform_id'   => !empty($request['platform_id']) ? $request['platform_id'] : NULL,
                'ip_address'    => $request->ip(),
                'user_agent'    => $request->server('HTTP_USER_AGENT'),
                'created_at'    => Carbon::now()
            ]);
        //send verification email
        if( env('VERIFICATION_TYPE') == 'email' ){
            $mail_params['USERNAME'] = $record->name;
            $mail_params['LINK']     = route('verifyEmail',['name' => encrypt($record->email)]);
            $mail_params['YEAR']     = date('Y');
            $mail_params['APP_NAME'] = env('APP_NAME');
            CustomHelper::sendMail($record->email,'user_registration',$mail_params);
        }

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for manipulate data input before update data is execute
    | ----------------------------------------------------------------------
    | @request  = http request object
    | @postdata = input post data
    | @id       = current id
    |
    */
    public function hook_before_edit($request, $slug, &$postData)
    {
        foreach( $postData as $key => $value ){
            if( in_array($key,$this->except_update_params) )
                unset($postData[$key]);
        }
        if( !empty($postData['image_url']) ){
            $postData['image_url'] = '/storage/' . CustomHelper::uploadMedia('users',$postData['image_url']);
        }

        if( !empty($postData['logo_url']) ){
            $postData['logo_url'] = '/storage/' . CustomHelper::uploadMedia('users',$postData['logo_url']);
        }
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after edit public static function called
    | ----------------------------------------------------------------------
    | @request  = Http request object
    | @$slug    = $slug
    |
    */
    public function hook_after_edit($request, $slug) {
        //Your code here
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command before delete public static function called
    | ----------------------------------------------------------------------
    | @request  = Http request object
    | @$id      = record id = int / array
    |
    */
    public function hook_before_delete($request, $slug) {
        //Your code here

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after delete public static function called
    | ----------------------------------------------------------------------
    | @$request       = Http request object
    | @records        = deleted records
    |
    */
    public function hook_after_delete($request,$records) {
        //Your code here

    }

    public function create_cache_signature($request)
    {
        $cache_params = $request->isMethod('post') ? [] : $request->except(['user','api_token']);
        return 'users_api_' . md5(implode('',$cache_params));
    }
}
