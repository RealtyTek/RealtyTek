<?php

namespace App\Models\Hooks\Api;
use App\Models\{Property,BuyerProperty};
use Illuminate\Support\Facades\DB;
class RatingHook
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
            
        $postdata["buyer_id"] = $postdata["buyer_id"];
        $postdata["buyer_property_id"] = $postdata["property_id"];
        $postdata["user_id"] = $request["user"]->id;
        $postdata["slug"] = uniqid();
        $postdata["status"] = "1";

        // print_r($postdata);
        // die();

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
        $get_avg_rating = \DB::table('user_review')
                                ->selectRaw("sum(rating) as rating, count(id) AS total_rows, round(sum(rating) / count(id) ) as avg_rating")
                                ->where('buyer_id',$record->buyer_id)
                                ->where('buyer_property_id',$record->buyer_property_id)
                                ->first();
          \DB::table('buyer_properties')
                ->where('id',$record->buyer_property_id)
                ->update([ 
                    'rating' => $record->rating,
                    'review' => $record->review
                ]);
            $buyer_properties = BuyerProperty::find($record->buyer_property_id);
        
        \DB::table('properties')
            ->where('id',$buyer_properties->property_id)
            ->update([
                'rating' => $get_avg_rating->avg_rating,
                'review' => $get_avg_rating->total_rows
            ]);
       
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

    public function create_cache_signature($request)
    {
        $cache_params = $request->except(['user','api_token']);
        return 'sample_' . md5(implode('',$cache_params));
    }
}
