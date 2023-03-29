<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsWidget;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use CustomHelper;   

class DashboardController extends Controller
{
    public function index()
    {
        $data['page_title'] = 'Dashboard';
        return $this->__cbAdminView('dashboard.index',$data);
    }

    public function getSmallWidget()
    {
        $data = [];
        $widgets = CmsWidget::getWidgetByType('small_box');
        foreach( $widgets as $widget ){
            if (Auth::guard('cms_user')->user()->cms_role_id == 2) {
                if ($widget->slug == "total-customer") {
                    $query = $widget->sql . ' AND `parent_id`='.Auth::guard('cms_user')->user()->user_ref_id.' '.' limit 1';
                }else{
                    $query = $widget->sql . ' WHERE `agent_id`='.Auth::guard('cms_user')->user()->user_ref_id.' '.' limit 1';
                }

            }else{
                $query = $widget->sql.' limit 1';
            }
            $records = \DB::select($query);
            if( count($records) ){
                foreach( $records[0] as $record ){
                    $value = $record;
                }
            } else {
                $value = 0;
            }
            $data[] = [
                'title'            => $widget->title,
                'icon'             => $widget->icon,
                'color'            => $widget->color,
                'div_column_class' => $widget->div_column_class,
                'link'             => $widget->link,
                'config'           => $widget->config,
                'value'            => $value,
            ];
            // echo "<pre>"; print_r($data); 

        }
        return response()->json($data);
    }

    public function getLineChart()
    { 
        $data       = [];
        $final_data = [];
        $widgets = CmsWidget::getWidgetByType('line_chart');
        foreach( $widgets as $widget ){
            if (Auth::guard('cms_user')->user()->cms_role_id == 2) {
                $query = $widget->sql .' WHERE `parent_id`='.Auth::guard('cms_user')->user()->user_ref_id.' AND YEAR(created_at) = YEAR(now()) GROUP BY MONTH(created_at) ORDER BY MONTH(created_at) ASC';
            }else{
                $query = $widget->sql .' WHERE YEAR(created_at) = YEAR(now()) GROUP BY MONTH(created_at) ORDER BY MONTH(created_at) ASC';
            }
            $records = \DB::select($query);
            if( count($records) ){
                $data = [];
                foreach( $records as $record ){
                    $data['label'][] = $record->label;
                    $data['value'][] = $record->value;
                }
                $final_data[] = [
                    'title'            => $widget->title,
                    'description'      => $widget->description,
                    'icon'             => $widget->icon,
                    'color'            => $widget->color,
                    'div_column_class' => $widget->div_column_class,
                    'link'             => $widget->link,
                    'config'           => $widget->config,
                    'data'             => $data
                ];
            } else {
                $data = [];
            }
        }
        return response()->json($final_data);
    }
}
