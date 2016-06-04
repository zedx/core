<?php

namespace ZEDx\Http\Requests;

class DashboardWidgetCreateRequest extends Request
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
            'namespace' => 'required',
            'title'     => 'required',
            'config'    => 'required',
            'size'      => 'required|integer|between:1,12',
        ];
    }
}
