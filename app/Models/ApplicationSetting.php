<?php

namespace App\Models;

use App\Helpers\CustomHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class ApplicationSetting extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'application_setting';

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
        'identifier', 'meta_key', 'value', 'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * This function is used to save application setting from admin
     * @param array $params
     * @return bool
     */
    public static function saveAppSetting(array $params)
    {
        if( !empty($params['logo']) )
            $data['logo'] = '/storage/' . CustomHelper::uploadMedia('application_setting',$params['logo']);
        else
            $data['logo'] = $params['old_logo'];
        if( !empty($params['favicon']) )
            $data['favicon'] = '/storage/' . CustomHelper::uploadMedia('application_setting',$params['favicon']);
        else
            $data['favicon'] = $params['old_favicon'];

        $data['application_name'] = $params['application_name'];
        foreach($data as $key => $value){
            $app_setting[] = [
                'identifier' => 'application_setting',
                'meta_key'   => $key,
                'value'      => $value,
                'created_at' => Carbon::now()
            ];
        }
        //delete old app setting
        self::where('identifier','application_setting')->forceDelete();
        //save new app setting
        self::insert($app_setting);
        //remove application setting from cache
        Cache::forget('setting_application_setting' );
        return true;
    }
}
