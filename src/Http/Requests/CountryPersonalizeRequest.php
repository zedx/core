<?php

namespace ZEDx\Http\Requests;

class CountryPersonalizeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'fill'         => 'required|size:7',
            'stroke'       => 'required|size:7',
            'animate-fill' => 'required|size:7',
            'stroke-width' => 'required|numeric',
        ];
    }
}
