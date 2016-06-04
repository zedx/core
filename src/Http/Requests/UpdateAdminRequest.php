<?php

namespace ZEDx\Http\Requests;

use Auth;

class UpdateAdminRequest extends Request
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
        $admin = Auth::guard('admin')->user();

        return [
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:admins,email,'.$admin->id,
            'password' => 'confirmed|min:6',
        ];
    }
}
