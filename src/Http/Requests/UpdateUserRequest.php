<?php

namespace ZEDx\Http\Requests;

use Auth;
use Route;

class UpdateUserRequest extends Request
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
        $rules = [];

        if (Route::is('user.update')) {
            $user = Auth::user();
            if ($user->is_validate) {
                $rules = [
                    'current_password' => 'required',
                ];
            }
        } else {
            $user = $this->route('user');
        }

        return array_merge($rules, [
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users,email,'.$user->id,
            'password' => 'confirmed|min:6',
            'status'   => 'required|integer|between:0,1',
        ]);
    }
}
