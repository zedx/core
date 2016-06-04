<?php

namespace ZEDx\Http\Requests;

class CreatePageRequest extends Request
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
            'template_id' => 'required|integer|exists:templates,id',
            'name'        => 'required|min:1',
            'shortcut'    => 'required|min:1',
        ];
    }
}
