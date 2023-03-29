<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\CustomHelper;
use App\Models\Media;
use Url;
class LenderStateDisclosure extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lenders_state_disclosure';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','title','link','created_at', 'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * It is used to enable or disable DB cache record
     * @var bool
     */
    protected $__is_cache_record = false;

    /**
     * @var
     */
    protected $__cache_signature;

    /**
     * @var string
     */
    protected $__cache_expire_time = 1; //days

    public static function storeLendersDisclosure($user_id,$title,$link,$document,$filename){
        $base_url    = \URL::to('/');
		$placeholder = \URL::to('images/image-not-avail.png');
        
        if (isset($link) && !empty($link)) { 
            self::create([
                'user_id'=>$user_id,
                'title'=>$title,
                'link'=>$link
            ]);
        }

        if (isset($document) && !empty($document)) {
            Media::create([
                'module'=>'lenders_state_disclosure',
                'module_id'=>$user_id,
                'filename'=>$filename,
                'file_url'=> '/storage/' . CustomHelper::uploadMedia('customer',$document),
            ]);
        }
        $data = [
            'link'=>self::where('user_id',$user_id)->get(),
            'document'=> Media::where('module_id',$user_id)->select('media.*')->selectRaw("IF(file_url IS NOT NULL, CONCAT('$base_url',file_url),'$placeholder') AS file_url")->get(),
        ];
        return $data;
    }
    public static function getLendersDisclosure($user_id,$user_group_id){
        $base_url    = \URL::to('/');
		$placeholder = \URL::to('images/image-not-avail.png');
        if ($user_group_id == 1) {
            $data = [
                'link'=>self::where('user_id',$user_id)->get(),
                'document'=> Media::where('module_id',$user_id)->select('media.*')->selectRaw("IF(file_url IS NOT NULL, CONCAT('$base_url',file_url),'$placeholder') AS file_url")->get(),
            ];
        }else{
            $agent = User::find($user_id);
            $data = [
                'link'=>self::where('user_id',$agent->parent_id)->get(),
                'document'=> Media::where('module_id',$agent->parent_id)->select('media.*')->selectRaw("IF(file_url IS NOT NULL, CONCAT('$base_url',file_url),'$placeholder') AS file_url")->get(),
            ];
        }
        return $data;
    }

    public static function deleteLendersDisclosure($user_id,$type,$record_id){
        
        if ($type == 'document') {
            Media::find($record_id)->forceDelete();
        }else{
            self::where('user_id',$user_id)->where('id',$record_id)->forceDelete();

        }
        // return $data;
    }
    

}
