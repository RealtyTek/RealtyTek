<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\{PropertyContractStatus,PropertyContractStatusHistory,BuyerProperty,Buyer,User,Notification,NotificationSetting};

class Property extends Model
{
    use SoftDeletes,CRUDGenerator;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'properties';

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
    protected $fillable = ['agent_id','creator_id','title','customer_id','slug','image_url','address','city','state','zipcode',
	'property_type','mls_detail','asking_price','sell_date','cma_appointment','property_status',
	'initiate_contract','rating','review','status','created_at','updated_at','deleted_at'];

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

	public function agent()
    {
		$base_url    = \URL::to('/');
		$placeholder = \URL::to('images/user-placeholder.png');
        return $this->hasOne(User::class, 'id', 'agent_id')
					->select('id','slug','name','email','mobile_no')
					->selectRaw("IF(image_url IS NOT NULL, CONCAT('$base_url',image_url),'$placeholder') AS image_url");
    }

	public function customer()
    {
		$base_url    = \URL::to('/');
		$placeholder = \URL::to('images/user-placeholder.png');
        return $this->hasOne(User::class, 'id', 'customer_id')
					->select('id','slug','name','email','mobile_no')
					->selectRaw("IF(image_url IS NOT NULL, CONCAT('$base_url',image_url),'$placeholder') AS image_url");
    }

    public static function generateUniquePropertyAddr($propertyaddr)
    {
        $propertyaddr = Str::slug($propertyaddr);
        $query = self::where('slug',$propertyaddr)->count();
        if( $query > 0){
            $propertyaddr = $propertyaddr . $query . rand(111,999);
        }
        return Str::slug($propertyaddr);
    }

    public static function updateProperty($agent_id,$buyer_id,$property_id,$initiate_contract_status)
	{ 
        self::where('agent_id',$agent_id)
        ->where('id',$property_id)
        ->update(array('initiate_contract'=>$initiate_contract_status));

        Buyer::where('id',$buyer_id)
        ->update(['initiate_contract'=>$initiate_contract_status]);

        BuyerProperty::where('buyer_id',$buyer_id)
        ->where('property_id',$property_id)
        ->update(['initiate_contract'=>$initiate_contract_status]);

        $getBuyer = Buyer::find($buyer_id);

            $agentdata = User::getUserApiTokenByID($agent_id);
            $clientdata = User::getUserApiTokenByID($getBuyer->customer_id);
            //send push notification to research center
            $getNotificationSetting = NotificationSetting::getSetting($clientdata[0]->id); 
            if ($getNotificationSetting['notification_all'] == 1) {
                $custom_data = [
                    'record_id'     => 0,
                    'redirect_link' => NULL,
                    'identifier'    => 'initiate_contract',
                    'data'    => ['id'=>$getBuyer->id,'slug'=>$getBuyer->slug,],
                ];
                $notification_data = [
                    'actor'            => $agentdata,
                    'actor_type'      => 'users',
                    'target'           => $clientdata,
                    'target_type'      => 'users',
                    'title'            => 'Contract initiated',
                    'message'          => "".$agentdata[0]['name']." initiated your contract",
                    'reference_slug'   => $getBuyer->slug,
                    'reference_id'     => $getBuyer->id,
                    'custom_data'      => $custom_data,
                    'reference_module' => 'buyers',
                    'redirect_link'    => NULL,
                    'badge'            => '0'
                ];

                Notification::sendPushNotification('initiate_contract',$notification_data,$custom_data,$clientdata[0]->device_type);
            }
        
    }

    public static function checkInitiateProperty($property_id){
        $query = self::where('id',$property_id)->where('initiate_contract','1')->first();
        return $query;
    }

    public static function getProperty($agent_id,$property_id)
	{
        $base_url    = \URL::to('/');
		$placeholder = \URL::to('images/image-not-avail.png');

		$query = self::with(['agent','customer'])
			->select('properties.*')
			->selectRaw("IF(image_url IS NOT NULL, CONCAT('$base_url',image_url),'$placeholder') AS image_url")
            ->where('agent_id',$agent_id)
            ->where('id',$property_id)
            ->first();
 
        return $query;
    }

    public static function getInitiateBuyerProperty($user_id,$buyer_id)
	{  
        $query = Buyer::with('buyerProperties','initiateBuyerProperty','customer')
                        ->where('id',$buyer_id)
                        ->where('initiate_contract','1')
                        ->first(); 
        return $query;
    }

    public function initiateBuyerProperty()
    {
        return $this->hasOne(BuyerProperty::class, 'property_id', 'id')
                ->where('initiate_contract','1')
                ->with('property')
                ->select('id','buyer_id','property_id','initiate_contract','is_tour');
    }

    public static function checkProperty($agent_id, $property_id)
	{
        $query = self::where('agent_id',$agent_id)->where('id',$property_id)->first();
        return $query;
    }

    public static function updatePropertyStatus($params)
	{
        $getPropertyContractStatus = PropertyContractStatus::where('property_id',$params['property_id'])->first(); 

        $updateContract = new PropertyContractStatus;
        $updateContract->property_id=$params['property_id'];
        $updateContract->slug=rand().uniqid();
        PropertyContractStatus::where('property_id',$params['property_id'])->delete();        

        if(!empty($getPropertyContractStatus)){
            if (!empty($params['contract_offer'])) {
                $updateContract->contract_offer=$params['contract_offer'];
            }else{
                $updateContract->contract_offer=$getPropertyContractStatus->contract_offer;
            }
            if (!empty($params['contract_countered'])) {
                $updateContract->contract_countered=$params['contract_countered'];
            }else{
                $updateContract->contract_countered=$getPropertyContractStatus->contract_countered;
            }
            if (!empty($params['contract_accepted'])) {
                $updateContract->contract_accepted=$params['contract_accepted'];
            }else{
                $updateContract->contract_accepted=$getPropertyContractStatus->contract_accepted;
            }
            
            if (!empty($params['contract_executed'])) {
                $updateContract->contract_executed=$params['contract_executed'];
            }else{
                $updateContract->contract_executed=$getPropertyContractStatus->contract_executed;
            }
            if (!empty($params['offer_decline'])) {
                $updateContract->offer_decline=$params['offer_decline'];
            }else{
                $updateContract->offer_decline=$getPropertyContractStatus->offer_decline;
            }
            if (!empty($params['inspection'])) {
                $updateContract->inspection=$params['inspection'];
            }else{
                $updateContract->inspection=$getPropertyContractStatus->inspection;
            }
            if (!empty($params['appraisal'])) {
                $updateContract->appraisal=$params['appraisal'];
            }else{
                $updateContract->appraisal=$getPropertyContractStatus->appraisal;
            }
            if (!empty($params['final_walk_thru'])) {
                $updateContract->final_walk_thru=$params['final_walk_thru'];
            }else{
                $updateContract->final_walk_thru=$getPropertyContractStatus->final_walk_thru;
            }
            if (!empty($params['sattlement_date'])) {
                $updateContract->sattlement_date=$params['sattlement_date'];
            }else{
                $updateContract->sattlement_date=$getPropertyContractStatus->sattlement_date;
            }
            if (!empty($params['add_comment'])) {
                $updateContract->add_comment=$params['add_comment'];
            }else{
                $updateContract->add_comment=$getPropertyContractStatus->add_comment;
            }
            if (!empty($params['contract_status'])) {
                $updateContract->contract_status=$params['contract_status'];
            }else{
                $updateContract->contract_status=$getPropertyContractStatus->contract_status;
            }
            if (!empty($params['contract_status_updated_date'])) {
                $updateContract->contract_status_updated_date=$params['contract_status_updated_date'];
            }else{
                $updateContract->contract_status_updated_date=$getPropertyContractStatus->contract_status_updated_date;
            }
            $updateContract->created_at=Carbon::now();
            $updateContract->save(); 
        }else{
         if (!empty($params['contract_offer'])) {
                $updateContract->contract_offer=$params['contract_offer'];
            }
            if (!empty($params['contract_countered'])) {
                $updateContract->contract_countered=$params['contract_countered'];
            }
            if (!empty($params['contract_accepted'])) {
                $updateContract->contract_accepted=$params['contract_accepted'];
            }
            if (!empty($params['contract_executed'])) {
                $updateContract->contract_executed=$params['contract_executed'];
            }
            if (!empty($params['offer_decline'])) {
                $updateContract->offer_decline=$params['offer_decline'];
            }
            if (!empty($params['inspection'])) {
                $updateContract->inspection=$params['inspection'];
            }
            if (!empty($params['appraisal'])) {
                $updateContract->appraisal=$params['appraisal'];
            }
            if (!empty($params['final_walk_thru'])) {
                $updateContract->final_walk_thru=$params['final_walk_thru'];
            }
            if (!empty($params['sattlement_date'])) {
                $updateContract->sattlement_date=$params['sattlement_date'];
            }
            if (!empty($params['add_comment'])) {
                $updateContract->add_comment=$params['add_comment'];
            }
            if (!empty($params['contract_status'])) {
                $updateContract->contract_status=$params['contract_status'];
            }
            if (!empty($params['contract_status_updated_date'])) {
                $updateContract->contract_status_updated_date=$params['contract_status_updated_date'];
            }
            $updateContract->created_at=Carbon::now();
            $updateContract->save(); 
        }
        PropertyContractStatusHistory::create([
            'contract_id'=>$updateContract->id,
            'property_id'=>$updateContract->property_id,
            'contract_status'=>$updateContract->contract_status,
            'created_at'=>Carbon::now(),

        ]);
        return $updateContract;
    }

    public static function updatePropertyLoanInfo($params)
	{
        $query = PropertyLoanInfo::where('property_id',$params['property_id'])->first();
        if (!empty($query)) {
            $query->property_id=$params['property_id'];
            $query->slug=rand().uniqid();
            if (!empty($params['company'])) {
                $query->company=$params['company'];
            }
            if (!empty($params['contact'])) {
                $query->contact=$params['contact'];
            }
            if (!empty($params['contact_number'])) {
                $query->contact_number=$params['contact_number'];
            }  
            if (!empty($params['sale_price'])) {
                $query->sale_price=$params['sale_price'];
            }
            if (!empty($params['financing'])) {
                $query->financing=$params['financing'];
            }
            if (!empty($params['emd_submitted'])) {
                $query->emd_submitted=$params['emd_submitted'];
            }
            if (!empty($params['down_payment'])) {
                $query->down_payment=$params['down_payment'];
            }
            if (!empty($params['loan_status_updated_date'])) {
                $query->loan_status_updated_date=$params['loan_status_updated_date'];
            }
            if (!empty($params['loan_status'])) {
                $query->loan_status=$params['loan_status'];
            }
            $query->created_at=Carbon::now();
            $query->save();
        }else{
            $queryCreate= new PropertyLoanInfo;
            $queryCreate->property_id=$params['property_id'];
            $queryCreate->slug=rand().uniqid();
            if (!empty($params['company'])) {
                $queryCreate->company=$params['company'];
            }
            if (!empty($params['contact'])) {
                $queryCreate->contact=$params['contact'];
            }
            if (!empty($params['contact_number'])) {
                $queryCreate->contact_number=$params['contact_number'];
            }  
            if (!empty($params['sale_price'])) {
                $queryCreate->sale_price=$params['sale_price'];
            }
            if (!empty($params['financing'])) {
                $queryCreate->financing=$params['financing'];
            }
            if (!empty($params['emd_submitted'])) {
                $queryCreate->emd_submitted=$params['emd_submitted'];
            }
            if (!empty($params['down_payment'])) {
                $queryCreate->down_payment=$params['down_payment'];
            }
            if (!empty($params['loan_status_updated_date'])) {
                $queryCreate->loan_status_updated_date=$params['loan_status_updated_date'];
            }
            if (!empty($params['loan_status'])) {
                $queryCreate->loan_status=$params['loan_status'];
            }
            $queryCreate->created_at=Carbon::now();
            $queryCreate->save();
        }
        
        $queryshow = PropertyLoanInfo::where('property_id',$params['property_id'])->first();

        return $queryshow;
    }

    public static function getPropertyLoanInfo($property_id){
        $query = PropertyLoanInfo::where('property_id',$property_id)->first();
        return $query;
    }
    
    public static function getPropertyContractStatus($property_id){
        $query = PropertyContractStatus::where('property_id',$property_id)->first();
        return $query;
    }

    public static function propertyChecking($user_id,$slug){
        $query = self::where('creator_id',$user_id)->where('slug',$slug)->first();
        return $query;
    }

    public static function updatePropertyChangeStatus($user_id,$slug,$status)
    {
        self::where('slug',$slug)->update(['status'=>$status]);
        return self::where('slug',$slug)->first();
    }

    public static function checkPropertyStatus($property_id){
        $query = self::where('id',$property_id)->where('status','0')->first();
        return $query;
    }

}
