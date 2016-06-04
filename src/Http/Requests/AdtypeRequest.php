<?php

namespace ZEDx\Http\Requests;

class AdtypeRequest extends Request
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
            'title'              => 'required|min:3',
            'nbr_days'           => 'required|integer',
            'is_headline'        => 'required|integer|between:0,1',
            'can_renew'          => 'required|integer|between:0,1',
            'can_edit'           => 'required|integer|between:0,1',
                'can_update_pic' => 'required|integer|between:0,1',
            'nbr_pic'            => 'required|integer',
            'can_add_video'      => 'required|integer|between:0,1',
                'nbr_video'      => 'required|integer',
            'can_update_video'   => 'required|integer|between:0,1',
            'price'              => 'required|numeric',
            'can_add_pic'        => 'required|integer|between:0,1',
        ];
    }
}
