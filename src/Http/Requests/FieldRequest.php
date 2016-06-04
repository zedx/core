<?php

namespace ZEDx\Http\Requests;

class FieldRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //dd(\Auth::user());
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
            'name'     => 'required|min:3',
            'type'     => 'required|integer|between:1,5',
            'is_in_ad' => 'required|integer|between:0,1',
        'is_in_search' => 'required|integer|between:0,1',
        ];
    }
}
