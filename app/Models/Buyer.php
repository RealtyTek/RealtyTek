<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BuyerProperty;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use App\Models\Appointment;

class Buyer extends Model
{
    use SoftDeletes,CRUDGenerator;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'buyers';

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
          'creator_id', 'agent_id','address', 'customer_id','slug','requirements','price','house_type','move_date','first_time_buyer',
		  'pre_approved','company_name','amount','status','created_at','updated_at','deleted_at'
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


    public function buyer()
    {
		$base_url    = URL::to('/');
		$placeholder = URL::to('images/user-placeholder.png');
        return $this->belongsTo(User::class, 'id', 'agent_id')
					->select('id','slug','name')
					->selectRaw("IF(image_url IS NOT NULL, CONCAT('$base_url',image_url),'$placeholder') AS image_url");
    }

    public function customer()
    {
		$base_url    = URL::to('/');
		$placeholder = URL::to('images/user-placeholder.png');
        return $this->belongsTo(User::class, 'customer_id', 'id')
					->select('id','slug','name','address','email','mobile_no')
					->selectRaw("IF(image_url IS NOT NULL, CONCAT('$base_url',image_url),'$placeholder') AS image_url");
    }

    public function initiateBuyerProperty()
    {
        return $this->hasOne(BuyerProperty::class, 'buyer_id', 'id')
                ->where('initiate_contract','1')
                ->with('property')
                ->select('id','buyer_id','property_id','initiate_contract','is_tour');
    }

    public function buyerProperties()
    {
        return  $this->hasMany(BuyerProperty::class, 'buyer_id', 'id')->select('buyer_id','property_id');
    }

    public static function getRecommendedHome($user_group_id,$user_id,$buyer_id)
	{
        $query = BuyerProperty::whereHas('property',function($q){
                            $q->where('status','1');
                        })
                        ->with(['customer','property'])
						->where('buyer_id',$buyer_id)
						->where('is_tour','0');
                        if ($user_group_id == 2) {
                            $query->where('customer_id',$user_id);
                        }else{
                            $query->where('agent_id',$user_id);
                        }
        return $query->get();
    }

    public static function getTourHome($agent_id,$buyer_id)
	{
        $query = BuyerProperty::with('property')
								->where('buyer_id',$buyer_id)
								->where('agent_id',$agent_id)
								->where('is_tour','1')
								->get();
        return $query;
    }

    public static function getCustomerTourHome($customer_id,$buyer_id)
	{
        $query = BuyerProperty::with('property')
								->where('buyer_id',$buyer_id)
								->where('customer_id',$customer_id)
								->where('is_tour','1')
								->get();
        return $query;
    }



    public static function buyerChecking($user_id,$slug){
        $query = self::where('creator_id',$user_id)->where('slug',$slug)->first();
        return $query;
    }

    public static function getBuyerDetail($buyer_id){
        $query = self::find($buyer_id);
        return $query;
    }

    public static function updateTourHomeStatus(){
        $todayDate = Carbon::now()->format('Y-m-d');
        foreach (Appointment::where('appointment_date', '<',$todayDate)->cursor() as $appointment) {
            $property_ids[] = $appointment->property_id;
        }
        if (isset($property_ids) && !empty($property_ids)) {
            BuyerProperty::whereIn('property_id',$property_ids)->update(['is_tour'=>1]);
            return 1;
        }else{
            return 0;
        }
    }

}
