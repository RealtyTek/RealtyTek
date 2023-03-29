<?php

namespace App\Http\Controllers\Api;

use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Http\Controllers\RestController;
use App\Models\Rating;

class RatingController extends RestController
{

    public function __construct(Request $request)
    {
        parent::__construct('Rating');
        $this->__request     = $request;
        $this->__apiResource = 'Rating';
    }

    /**
     * This function is used for validate restfull request
     * @param $action
     * @param string $slug
     * @return array
     */
    public function validation($action,$slug=0)
    {
        $validator = [];
        switch ($action){
            case 'POST':
                $validator = Validator::make($this->__request->all(), [
                    'property_id'        => 'required|exists:buyer_properties,id',
                    'buyer_id'           => 'required|exists:buyers,id,customer_id,'.$this->__request->user->id.'',
                    'rating'             => 'required|integer|between:1,5',
                    'review'             => 'required',

                ]);
                break;
            case 'PUT':
                $validator = Validator::make($this->__request->all(), [
                    'attribute'     => 'required',
                ]);
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
        $checkRating = Rating::checkingRating($request['property_id'],$request["user"]->id);
        if ($checkRating > 0) {
            $this->__is_error = true;
            return $this->__sendError('Validation Message',['message' => __('app.you_already_rated')],400);
        }
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

    }

    /**
     * @param $request
     */
    public function beforeDestroyLoadModel($request,$slug)
    {

    }
}
