<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;
class Lead extends Model
{
    use CRUDGenerator;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

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
        'user_group_id','parent_id', 'name', 'username', 'slug', 'email', 'mobile_no', 'password', 'image_url', 'status', 'is_email_verify','is_mobile_verify', 'country', 'city', 'state', 'zipcode', 'address', 'latitude', 'longitude', 'online_status','mobile_otp', 'email_otp', 'website', 'about_us', 'tag_line', 'logo_url', 'remember_token', 'created_at', 'updated_at', 'deleted_at'
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


    public static function generateUniqueUserName($username)
    {
        $username = Str::slug($username);
        $query = \DB::table('users')->where('username',$username)->count();
        if( $query > 0){
            $username = $username . $query . rand(111,999);
        }
        return Str::slug($username);
    }

    public static function leadChecking($user_id,$slug){
        $customerslug = $slug;
        $query = self::where('parent_id',$user_id)->where('slug',$customerslug)->first();
        return $query;
    }

    /**
     * Get the user associated with the Lead
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function agent()
    {
        return $this->hasOne(User::class, 'id', 'parent_id');
    }

}
