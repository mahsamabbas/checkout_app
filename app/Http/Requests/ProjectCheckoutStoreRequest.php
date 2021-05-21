<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectCheckoutStoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            "payment_method_nonce" => "required",
            "email"                => "required|email",
            "client_name"          => "required",
        ];
    }
}