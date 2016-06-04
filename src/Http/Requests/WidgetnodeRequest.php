<?php

namespace ZEDx\Http\Requests;

class WidgetnodeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        //return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'namespace' => 'required',
            'title'     => 'required',
            'config'    => 'required',
        ];
    }
}
