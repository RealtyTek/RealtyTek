<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CmsRole extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cms_roles';

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
        'name', 'slug', 'is_super_admin', 'created_at', 'updated_at', 'deleted_at'
    ];

    public static function createRole($params)
    {
        $record = self::create([
            'name' => $params['name'],
            'slug' => Str::slug($params['name']),
            'is_super_admin' => $params['is_super_admin'],
            'created_at' => Carbon::now()
        ]);
        //check super admin
        if( empty( $params['is_super_admin']) ){
            //module permission
            if( count($params['module_id']) ){
                for( $i=0; $i < count($params['module_id']); $i++ ){
                    $module_permission[] =[
                        'cms_role_id'   => $record->id,
                        'cms_module_id' => $params['module_id'][$i],
                        'is_add'        => !empty($params['is_add'][$params['module_id'][$i]]) ? '1' : '0',
                        'is_view'       => !empty($params['is_view'][$params['module_id'][$i]]) ? '1' : '0',
                        'is_update'     => !empty($params['is_update'][$params['module_id'][$i]]) ? '1' : '0',
                        'is_delete'     => !empty($params['is_delete'][$params['module_id'][$i]]) ? '1' : '0',
                        'created_at'    => Carbon::now()
                    ];
                }
                CmsModulePermission::insert($module_permission);
            }
        }
        return $record;
    }

    public static function getRecordBySlug($slug)
    {
        $record = self::where('slug',$slug)->first();
        return $record;
    }

    public static function updateRole($params,$slug)
    {
        self::where('slug',$slug)
        ->update([
            'name' => $params['name'],
            'slug' => Str::slug($params['name']),
            'is_super_admin' => $params['is_super_admin'],
            'updated_at' => Carbon::now()
        ]);
        $record = self::getRecordBySlug($slug);
        //delete old module permission
        self::deleteModulePermissionByRoleId($record->id);
        //check super admin
        if( empty( $params['is_super_admin']) ){
            //module permission
            if( count($params['module_id']) ){
                for( $i=0; $i < count($params['module_id']); $i++ ){
                    $module_permission[] =[
                        'cms_role_id'   => $record->id,
                        'cms_module_id' => $params['module_id'][$i],
                        'is_add'        => !empty($params['is_add'][$params['module_id'][$i]]) ? '1' : '0',
                        'is_view'       => !empty($params['is_view'][$params['module_id'][$i]]) ? '1' : '0',
                        'is_update'     => !empty($params['is_update'][$params['module_id'][$i]]) ? '1' : '0',
                        'is_delete'     => !empty($params['is_delete'][$params['module_id'][$i]]) ? '1' : '0',
                        'created_at'    => Carbon::now()
                    ];
                }
                CmsModulePermission::insert($module_permission);
            }
        }
        return $record;
    }

    public static function deleteModulePermissionByRoleId($role_id)
    {
        if( !is_array($role_id) )
            CmsModulePermission::where('cms_role_id',$role_id)->forceDelete();
        else
            CmsModulePermission::whereIn('cms_role_id',$role_id)->forceDelete();
    }

    public static function dataTableRecords($params = [])
    {
        $query = self::select('*');
        if(!empty($params['keyword'])){
            $name  = $params['keyword'];
            $query = $query->where('name','like',"$name%");
        }
        $data['total_record'] = count($query->get());
        $query = $query->where('slug','!=','super-admin')->take($params['length'])->skip($params['start'])->orderBy('id','desc');
        $query = $query->get();
        $data['records'] = $query;
        return $data;
    }

    public static function deleteRecord($record_id)
    {
        if( !is_array($record_id) ){
            self::where('id',$record_id)->delete();
            self::deleteModulePermissionByRoleId($record_id);
        } else {
            self::whereIn('id',$record_id)->delete();
            self::deleteModulePermissionByRoleId($record_id);
        }
        return true;
    }

    public static function getCmsRole($is_super_role = false)
    {
        $query = \DB::table('cms_roles');
        if( $is_super_role == false )
            $query->where('slug','!=','super-admin');

        $query = $query->orderBy('name','asc')->get();
        return $query;
    }
}
