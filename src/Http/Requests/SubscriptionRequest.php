<?php

namespace ZEDx\Http\Requests;

class SubscriptionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //return false;
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'       => 'required|min:3',
            'description' => 'required|min:3',
            'days'        => 'required|integer',
            'is_default'  => 'integer',
            'price'       => 'numeric',
        ];
    }
}
