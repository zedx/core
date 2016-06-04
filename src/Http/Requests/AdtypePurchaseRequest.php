<?php

namespace ZEDx\Http\Requests;

class AdtypePurchaseRequest extends Request
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
            'quantity' => 'required|integer|min:1',
      'gateway'        => 'required|integer|exists:gateways,id',
        ];
    }
}
