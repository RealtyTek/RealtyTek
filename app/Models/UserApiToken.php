<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class UserApiToken extends Model
{
    use SoftDeletes,CRUDGenerator;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_api_token';

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
        'user_id', 'api_token', 'refresh_token', 'udid',
        'device_type', 'device_token','platform_type', 'platform_id', 'ip_address', 'user_agent', 'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'secret_key'
    ];

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

    public static function generateApiToken($user_id,$ip_address,$udid,$datetime)
    {
        $secret_key = config('constants.SECRET_KEY');
        $token = "$user_id|$ip_address|$udid|$datetime";
        $token = hash_hmac('sha256', $token, $secret_key);
        return $token;
    }

    public static function generateRefreshToken($user_id)
    {
        $secret_key = config('constants.SECRET_KEY');
        $token = hash_hmac('sha256', "$user_id|" . Str::random(50) , $secret_key);
        return $token;
    }
}
