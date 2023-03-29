<?php

namespace App\Http\Controllers\Api;

use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Http\Controllers\RestController;
use App\Models\Appointment;

class AppointmentController extends RestController
{

    public function __construct(Request $request)
    {
        parent::__construct('Appointment');
        $this->__request     = $request;
        $this->__apiResource = 'Appointment';
    }

    /**
     * This function is used for validate restfull request
     * @param $action
     * @param string $slug
     * @return array
     */
    public function validation($action,$slug=0)
    {
        $param = $this->__request->all();
        $validator = [];
        switch ($action){
            case 'POST':
                $validator = Validator::make($this->__request->all(), [
                    'property_id'=> 'required|exists:properties,id,deleted_at,NULL',
                    'buyer_id' => 'required|exists:buyers,id,deleted_at,NULL',
                    'appointment_date' => 'required|date',
                    'appointment_time' => 'required|date_format:H:i:s',
                ]);
                break;
            case 'PUT':
                if ($this->__request->user->user_group_id == 1) {
                    $validator = Validator::make($this->__request->all(), [
                        'status' => 'required|in:pending,accept,reject',
                    ]);
                }else{ 
                    $validator = Validator::make($this->__request->all(), [
                        'property_id'=> 'required|exists:properties,id,deleted_at,NULL',
                        'buyer_id' => 'required|exists:buyers,id,deleted_at,NULL',
                        'appointment_date' => 'required|date',
                        'appointment_time' => 'required|date_format:H:i:s',
                    ]);
                }
                break;
            case 'INDEX':
                if ($this->__request->user->user_group_id == 1) {
                    $validator = Validator::make($this->__request->all(), [
                        'status' => 'in:pending,accept,reject',
                        'year' => 'digits:4|integer|min:1900|max:'.(date('Y')+1),
                        'month' => 'digits:2|between:1,12'
                    ]);
                }
                break;
        }
        return $validator;
    }

    /**
     * @param $request
     */
    public function beforeIndexLoadModel($request)
    {

    }

    /**
     * @param $request
     */
    public function beforeStoreLoadModel($request)
    {

    }

    /**
     * @param $request
     */
    public function beforeShowLoadModel($request,$slug)
    {

    }

    /**
     * @param $request
     */
    public function beforeUpdateLoadModel($request,$slug)
    {
        if ($request['user']->user_group_id == 2) {
            $checkAppointmentStatus = Appointment::checkAppointmentStatus($slug);
            if(isset($checkAppointmentStatus->id) && !empty($checkAppointmentStatus->id) ){
                $this->__is_error = true;
                return $this->__sendError('Validation Message',['message' => __('app.appointment_status')],400);
            }
        }
    }

    /**
     * @param $request
     */
    public function beforeDestroyLoadModel($request,$slug)
    {
        $appointmentChecking = Appointment::appointmentChecking($request['user']->id,$slug);
        if( !isset($appointmentChecking->id) ){
            $this->__is_error = true;
            return $this->__sendError('Validation Message',['message' => __('app.invalid_request')],400);
        }
    }
}
