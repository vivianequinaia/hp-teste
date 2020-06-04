<?php

namespace App\Http\Requests\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
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
            "product_id" => 'required|integer',
            "quantity_purchased" => 'required|integer',
            "card.owner" => 'required|string',
            "card.card_number" => 'required|string',
            "card.date_expiration" => 'required|string',
            "card.flag" => 'required|string',
            "card.cvv" => 'required|string|size:3'
        ];
    }
}
