<?php

namespace App\Models\Hooks\Admin;
use Carbon\Carbon;
use App\Helpers\CustomHelper;
use Illuminate\Support\Facades\Auth;

class PropertyHook
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
    public function hook_query_index(&$query,$request, $slug='') {
        //Your code here
        $base_url    = \URL::to('/');
		$placeholder = \URL::to('images/image-not-avail.png');

        $query->with('customer')
              ->selectRaw("IF(image_url IS NOT NULL, CONCAT('$base_url',image_url),'$placeholder') AS image_url");

        if (Auth::guard('cms_user')->user()->cms_role_id == 2) {
            $query->where('agent_id',Auth::guard('cms_user')->user()->user_ref_id);
        }
        if( !empty($request['keyword']) ){
            $keyword = $request['keyword'];
            $query->where(function($where) use ($keyword){
                $where->orWhere('title','like',"$keyword%");
                $where->orWhere('state','like',"$keyword%");
            });
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
        if (Auth::guard('cms_user')->user()->cms_role_id == 2) {
            $postdata['agent_id']  = Auth::guard('cms_user')->user()->user_ref_id;
            $postdata['creator_id']  = Auth::guard('cms_user')->user()->user_ref_id;
        }{
            $postdata['agent_id']  = $postdata['agent_id'];
            $postdata['creator_id']  = $postdata['agent_id'];
        }   
		 $postdata['slug']  = rand(); 
         $postdata['created_at'] = Carbon::now();
		 if( !empty($postdata['image_url']) ){
			$postdata['image_url'] = '/storage/' . CustomHelper::uploadMedia('property',$postdata['image_url']);
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
        //Your code here
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
			$postData['image_url'] = '/storage/' . CustomHelper::uploadMedia('property',$postData['image_url']);
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
}
