<?php

namespace ZEDx\Http\Requests;

class UpdateAdUserRequest extends Request
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
        $setting = setting();

        return [
            'content.title' => 'required|min:3',
            'content.body'  => 'required|min:'.$setting->ad_descr_min.'|max:'.$setting->ad_descr_max,
            'category_id'   => 'required|exists:categories,id',
        ];
    }
}
