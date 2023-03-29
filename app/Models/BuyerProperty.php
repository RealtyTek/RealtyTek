<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\URL;

class BuyerProperty extends Model
{
    use SoftDeletes,CRUDGenerator;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'buyer_properties';

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
          'agent_id', 'customer_id','slug','buyer_id','property_id',
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

    public function property()
    {
    	$base_url    = \URL::to('/');
		$placeholder = \URL::to('images/user-placeholder.png');
        return $this->hasOne(Property::class, 'id', 'property_id')
					->select('id','title','slug','image_url','status','address','city','state','zipcode','mls_detail','asking_price','sell_date','property_type','property_status','rating','review')
					->selectRaw("IF(image_url IS NOT NULL, CONCAT('$base_url',image_url),'$placeholder') AS image_url");
                    // ->selectRaw("IF(review IS NULL, 0, review) AS review");
    }

    public function customer()
    {
		$base_url    = URL::to('/');
		$placeholder = URL::to('images/user-placeholder.png');
        return $this->belongsTo(User::class, 'customer_id', 'id')
					->select('id','slug','name','address','email','mobile_no')
					->selectRaw("IF(image_url IS NOT NULL, CONCAT('$base_url',image_url),'$placeholder') AS image_url");
    }

}
