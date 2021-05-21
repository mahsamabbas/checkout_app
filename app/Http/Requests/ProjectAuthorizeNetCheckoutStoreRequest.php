<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectAuthorizeNetCheckoutStoreRequest extends FormRequest
{
    public function rules()
    {
        return [
            "email" => "email|required",
            "opaqueDataDescriptor" => "required",
            "opaqueDataValue" => "required",
        ];
    }
}