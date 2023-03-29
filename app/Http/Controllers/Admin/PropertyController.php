<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class PropertyController extends CRUDCrontroller
{
    public function __construct(Request $request)
    {
        parent::__construct('Property');
        $this->__request    = $request;
        $this->__data['page_title'] = 'Property';
        $this->__indexView  = 'property.index';
        $this->__createView = 'property.add';
        $this->__editView   = 'property.edit';
        $this->__detailView = 'property.detail';
    }

    /**
     * This function is used for validate data
     * @param string $action
     * @param string $slug
     * @return array|\Illuminate\Contracts\Validation\Validator
     */
    public function validation(string $action, string $slug='')
    {
        $validator = [];
        if (Auth::guard('cms_user')->user()->cms_role_id == 3) {
            $custom_messages = [
                'customer_id.required' => 'Customer Name field is required',
                'agent_id.required'    => 'Agent Name field is required',
            ];
        }else{
            $custom_messages = [
                'customer_id.required' => 'Customer Name field is required',
            ];
        }
        
        switch ($action){ 
            case 'POST':
                $validator = Validator::make($this->__request->all(), [
                    'customer_id'      =>'required',
                    'agent_id'         =>'sometimes|required',
                    'title'            => 'required|min:3|max:50',
                    'image_url'        => 'required|image|file|max:50000',
                    'address'          => 'required|min:3',
                    'city'             => 'required|min:3|max:50',
                    'state'            => 'required|min:2|max:50',
                    'zipcode'          => 'required|min:2|max:50',
                    'mls_detail'       => 'required|min:3|max:1000',
                    'asking_price'     => 'required|min:2|max:50',
                    'sell_date'        => 'required|date_format:Y-m-d',
                    'cma_appointment'  => 'required|min:2|max:100',
                    'property_type'    =>'required',

            ],$custom_messages);
                break;
            case 'PUT':
                $validator = Validator::make($this->__request->all(), [
                    '_method'          => 'required|in:PUT',
                    'customer_id'      => 'required',
                    'agent_id'         => 'sometimes|required',
                    'title'            => 'required|min:3|max:50',
                    'image_url'        => 'image|file|max:50000',
                    'address'          => 'required|min:3',
                    'city'             => 'required|min:3|max:50',
                    'state'            => 'required|min:2|max:50',
                    'zipcode'          => 'required|min:2|max:50',
                    'mls_detail'       => 'required|min:3|max:1000',
                    'asking_price'     => 'required|min:2|max:50',
                    'sell_date'        => 'required|date_format:Y-m-d',
                    'cma_appointment'  => 'required|min:2|max:100',
                    'property_type'    =>'required',
                ],$custom_messages);
                break;
        }
        return $validator;
    }

    /**
     * This function is used for before the index view render
     * data pass on view eg: $this->__data['title'] = 'Title';
     */
    public function beforeRenderIndexView()
    {

    }

    /**
     * This function is used to add data in datatable
     * @param object $record
     * @return array
     */
    public function dataTableRecords($record) 
    {
        $options  = '<a href="'. route('admin-property.edit',['admin_property' => $record->slug]) .'" title="Edit" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>';
        $options  .= '<a href="'. route('admin-property.show',['admin_property' => $record->slug]) .'" title="View" class="btn btn-xs btn-success"><i class="fa fa-eye"></i></a>';
        return [
            // '<input type="checkbox" name="record_id[]" class="record_id" value="'. $record->slug .'">',
            $record->customer->name,
            $record->title,
            $record->state,
            $options
        ];
    }

    /**
     * This function is used for before the create view render
     * data pass on view eg: $this->__data['title'] = 'Title';
     */
    public function beforeRenderCreateView()
    {
        $this->__data['leads'] = User::where('parent_id',Auth::guard('cms_user')->user()->user_ref_id)->get();
 
        $this->__data['agents'] = User::where('parent_id',0)->get();

    }

    /**
     * This function is called before a model load
     */
    public function beforeStoreLoadModel()
    {

    }

    /**
     * This function is used for before the edit view render
     * data pass on view eg: $this->__data['title'] = 'Title';
     * @param string @slug
     */
    public function beforeRenderEditView($slug)
    {
        $this->__data['leads'] = User::where('parent_id',Auth::guard('cms_user')->user()->user_ref_id)->get();
        $this->__data['agents'] = User::where('parent_id',0)->get();

    }

    /**
     * This function is called before a model load
     */
    public function beforeUpdateLoadModel()
    {
    }

    /**
     * This function is called before a model load
     */
    public function beforeRenderDetailView()
    {
    }

    /**
     * This function is called before a model load
     */
    public function beforeDeleteLoadModel()
    {
    }

    public function getLeadList($id)
    {
        $data = User::where('parent_id',$id)->where('user_group_id',2)->get();
        return ['data'=>$data];
    }
}
