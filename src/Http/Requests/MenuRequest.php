<?php

namespace ZEDx\Http\Requests;

class MenuRequest extends Request
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
            'name'       => 'required|min:1',
            'group_name' => 'required',
            'link'       => 'required',
            'type'       => 'required|in:link,page,route',
        ];
    }
}
