<?php

namespace App\Models\Hooks\Api;
use Carbon\Carbon;
use App\Helpers\CustomHelper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use App\Models\{Lead,NotificationSetting};
class LeadHook
{
    private $_model;

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
    public function hook_query_index(&$query,$request, $slug='')
	{
		$base_url    = URL::to('/');
		$placeholder = URL::to('images/user-placeholder.png');
		$query->select('users.*')
			  ->selectRaw("IF(image_url IS NOT NULL, CONCAT('$base_url',image_url),'$placeholder') AS image_url");
        $query->where('parent_id',$request["user"]->id);

        if ($request["lead_type"] == "search") {
            $query->where('name', 'like', '%' . $request['value'] . '%');
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
         $postdata['parent_id'] = $request["user"]->id;
         $postdata['username'] = $this->_model::generateUniqueUserName($postdata['name']);
         $postdata['slug'] = $postdata['username'];
         $postdata['password'] = Hash::make("Wecheck12345@");
         $postdata['created_at'] = Carbon::now();
         if( !empty($postdata['image_url']) ){
             $postdata['image_url'] = '/storage/' . CustomHelper::uploadMedia('customer',$postdata['image_url']);
          }

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
        $params['notification_setting']['notification_all'] = 1;
        $params['user_id'] = $record->id;
        NotificationSetting::storeNewUserSetting($params);
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
        if( !empty($postData['image_url']) ){
            $postData['image_url'] = '/storage/' . CustomHelper::uploadMedia('customer',$postData['image_url']);
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
        $cache_params = $request->except(['user','api_token']);
        return 'sample_' . md5(implode('',$cache_params));
    }
}
