<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\AppFaq;

class FaqsController extends CRUDCrontroller
{
    public function __construct(Request $request)
    {
        parent::__construct('AppFaq');
        $this->__request    = $request;
        $this->__data['page_title'] = 'Page Title';
        $this->__indexView  = 'faqs.index'; 
        $this->__createView = 'faqs.add';
        $this->__editView   = 'faqs.edit';
        $this->__detailView = 'faqs.detail';
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
        switch ($action){
            case 'POST':
                $validator = Validator::make($this->__request->all(), [
                    'question' => 'required|min:2',
                    'answer' => 'required|min:2',
                    'status' => 'required|in:0,1',
                ]);
                break;
            case 'PUT':
                $validator = Validator::make($this->__request->all(), [
                    '_method'   => 'required|in:PUT',
                    'question' => 'required|min:2',
                    'answer' => 'required|min:2',
                    'status' => 'required|in:0,1',
                ]);
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

        $options  = '<a href="'. route('admin-faqs.edit',['admin_faq' => $record->slug ]) .'" title="Edit" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i></a>';
        $options .= '<a href="'. route('admin-faqs.destroy',['admin_faq' => $record->slug]) .'" title="Delete" class="btn btn-xs btn-danger _delete_record"><i class="fa fa-trash"></i></a>';
        return [
            // '<input type="checkbox" name="record_id[]" class="record_id" value="'. $record->slug .'">',
            $record->question,
            $record->answer,
            $record->status == 1 ? '<span class="btn btn-xs btn-success">Active</span>' : '<span class="btn btn-xs btn-danger">Disabled</span>',
            $record->created_at,
            $options,
        ];
    }

    /**
     * This function is used for before the create view render
     * data pass on view eg: $this->__data['title'] = 'Title';
     */
    public function beforeRenderCreateView()
    {

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
        $this->__data['data'] = AppFaq::where('slug',$slug)->first();
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
}
