<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\{SubscriptionPackage,User};
use Carbon\Carbon;

class UserSubscription extends Model
{
    use SoftDeletes,CRUDGenerator;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_subscriptions';

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
    protected $fillable = ['user_id','subscription_package_id','gateway_transaction_id','transaction_id','gateway_original_transaction_id','subscription_expiry_date','status','is_trial_period','device_type','created_at','updated_at','deleted_at'];

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

    public function subscriptionPackage()
    {
        return $this->hasOne(SubscriptionPackage::class, 'id', 'subscription_package_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public static function checkSubscription($original_transaction_id){
        $query = \DB::table('user_subscriptions')
                ->where('gateway_original_transaction_id',$original_transaction_id)
                ->orderBy('id','desc')
                ->first();
        return $query;
    }

    public static function addUserSubscription($params)
    {
        $user = User::find($params['user']['id']);
        $gateway_response = json_decode($params['gateway_response'],true);

        if( $params['device_type'] == 'android' ){
            $data_android =  json_decode($gateway_response['transactionReceipt'],true);
            $gateway_response['transactionId'] = $data_android['orderId'];
            $gateway_response['original_transaction_id'] = $gateway_response['purchaseToken'];
        }
        else{
            $gateway_response['transactionId']           = $gateway_response['transaction_id'];
            $gateway_response['original_transaction_id'] = $gateway_response['original_transaction_id'];
        }

        //get package
        $package = SubscriptionPackage::where('id',$params['subscription_package_id'])->first();
        //create transaction
        $trial_period_duration = $package['trial_period'];
        // return $package->duration_unit;
        $params['payment_method'] = 'inapp_purchase';
        $params['total_amount']   = $package->amount;
        
        $query = self::where('user_id',$params['user']['id'])
                    ->where('subscription_package_id',$params['subscription_package_id'])
                    ->count();
        //check trial period

        $is_trial_period = $query == '0' ? '1' : '0';
        if( $is_trial_period == 1  && $package->trial_period > 0){
            $transaction_id = 0;
            $subscription_expiry_date = Carbon::now()->addDays($package['trial_period'])->format('Y-m-d H:i:s');
        }else{
            // //in app purchase transaction
            // $params['actor_id']               = $user->id;
            // $params['actor_type']             = 'user';
            // $params['module']                 = 'subscription_packages';
            // $params['module_id']              = $package->id;
            // $params['transaction_type']       = 'single'; 
            // $params['gateway_fee']            = 0;
            // $params['gateway_transaction_id'] = $gateway_response['transactionId'];
            // $params['gateway']                = $user->device_type;
            if($package->duration_unit == 'days'){
                $subscription_expiry_date = Carbon::now()->addDays($package['duration'])->format('Y-m-d H:i:s');
            }else if($package->duration_unit == 'week'){
                    $subscription_expiry_date = Carbon::now()->addWeeks($package['duration'])->format('Y-m-d H:i:s');
            }else if($package->duration_unit == 'month'){
                $subscription_expiry_date = Carbon::now()->addMonths($package['duration'])->format('Y-m-d H:i:s');
            }else{
                $subscription_expiry_date = Carbon::now()->addYears($package['duration'])->format('Y-m-d H:i:s');
            } 
            
            $transaction_id = 0; 
        }

        if( $params['device_type'] == 'ios' ){
            $expires_date = date('Y-m-d H:i:s',strtotime($gateway_response['expires_date']));
            $subscription_expiry_date = $expires_date;  
        }

        self::where('user_id',$params['user']['id'])
            ->where('status','active')
            ->update([
                'status' => 'expired'
            ]);
           //add user subscription data
        //    print_r($gateway_response['transactionId']); die;

           $record = self::create([
                'gateway_transaction_id'   => $gateway_response['transactionId'],
                'gateway_original_transaction_id' => $gateway_response['original_transaction_id'],
                'subscription_package_id'  => $params['subscription_package_id'],
                'user_id'                  => $params['user']['id'],
                'subscription_expiry_date' => $subscription_expiry_date,
                'is_trial_period'          => ($package->trial_period == 0) ? '0' : $is_trial_period,
                'status'                   => 'active',
                'device_type'			   => $params['device_type'],	
                'created_at'               => Carbon::now(),
            ]);
        //update user expiry date
        User::where('id',$params['user']['id'])
        ->update([
            'subscription_expiry_date' => $subscription_expiry_date
        ]);
        return [
            'error'   => 0,
            'message' => 'Success',
            'data'    => $record,
        ];


    }

     /**
     *  this function is used to get user subscription
     *  @params {int} $user_id
     *  @return {object}
     */
    public static function getUserSubscription($user_id)
    {
        $checkUserSubscription = \DB::table('user_subscriptions')
                                      ->where('user_id',$user_id)
                                      ->orderBy('id','desc')
                                      ->first();
        return $checkUserSubscription;
    }

}
