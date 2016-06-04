<?php

namespace ZEDx\Http\Requests;

class SwitchTemplateRequest extends Request
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
            'connected_blocks' => 'required|json',
            'template_id'      => 'required|integer|exists:templates,id',
        ];
    }
}
