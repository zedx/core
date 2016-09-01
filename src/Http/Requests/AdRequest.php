<?php

namespace ZEDx\Http\Requests;

class AdRequest extends Request
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
            'content.title' => 'required|min:3',
            'content.body'  => 'required|min:3',
            'category_id'   => 'required|integer|exists:categories,id,is_visible,1',
        ];
    }
}
