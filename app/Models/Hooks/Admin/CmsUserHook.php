<?php

namespace App\Models\Hooks\Admin;

use App\Helpers\CustomHelper;
use Illuminate\Support\Facades\Hash;

class CmsUserHook
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
        $query->select('cms_users.*');
        $query->selectRaw('cr.name AS cms_role_name');
        $query->join('cms_roles AS cr','cr.id','=','cms_users.cms_role_id');

        if( !empty($request['keyword']) ){
            $keyword = $request['keyword'];
            $query->where(function($where) use ($keyword){
                $where->orWhere('name','like',"$keyword%");
                $where->orWhere('email','like',"$keyword%");
                $where->orWhere('mobile_no','like',"$keyword%");
                $where->orWhere('status',$keyword == 'active' ? 1 : 0);
                $where->orWhere('created_at','like',"$keyword%");
            });
        }
        $query->where('cms_role_id','!=',1);
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
        $postdata['username'] = $this->_model::generateUniqueUserName($postdata['name']);
        $postdata['slug']     = $postdata['username'];
        $postdata['password'] = Hash::make($postdata['password']);
        if( !empty($postdata['image_url']) ){
            $postdata['image_url'] = '/storage/' . CustomHelper::uploadMedia('cms_users',$postdata['image_url'],'50X50');
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
            $postData['image_url'] = '/storage/' . CustomHelper::uploadMedia('cms_users',$postData['image_url'],'50X50');
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
