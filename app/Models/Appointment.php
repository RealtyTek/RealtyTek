<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Appointment extends Model
{
    use SoftDeletes,CRUDGenerator;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'appointments';

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
    protected $fillable = ['agent_id','customer_id','property_id','buyer_id','slug','appointment_time','appointment_date','status','created_at',
    'updated_at','deleted_at'];

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

    public static function appointmentChecking($user_id,$slug){
        $query = self::where('customer_id',$user_id)->where('slug',$slug)->first();
        return $query;
    }

    public function property()
    {
    	$base_url    = \URL::to('/');
		$placeholder = \URL::to('images/user-placeholder.png');
        return $this->hasOne(Property::class, 'id', 'property_id')
					->select('id','title','slug','image_url','address','city','state','zipcode','mls_detail','asking_price','sell_date','property_type','property_status')
					->selectRaw("IF(image_url IS NOT NULL, CONCAT('$base_url',image_url),'$placeholder') AS image_url");
    }

    public function client()
    {
        return $this->hasOne(User::class, 'id', 'customer_id')
					->select('id','name');
    }

    public static function checkAppointmentStatus($slug){
        $query = self::where('slug',$slug)->where('status','accept')->first();
        return $query;
    }

    public static function getAppointment($slug){
        $query =  self::where('slug',$slug)->first();
        return $query;
    }

}
