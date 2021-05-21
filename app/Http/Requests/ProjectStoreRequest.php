<?php

namespace App\Http\Requests;

use App\Rules\MailchimpApiKeyExists;
use App\Rules\MailchimpListIdCorrect;
use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
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
            'name'                     => ['required', 'string', 'max:255'],
            'url'                      => ['required', 'string', 'max:255', 'regex:/^\S*$/u', 'unique:projects,url'],
            'reservation_cost'         => ['required', 'numeric', "min:1", 'max:255'],
            'mailchimp_api_key'        => ['required', 'string', 'max:255', 'unique:projects,mailchimp_api_key', new MailchimpApiKeyExists($this->mailchimp_server, $this->mailchimp_api_key)],
            'mailchimp_server'         => ['required', 'string', 'max:191', new MailchimpApiKeyExists($this->mailchimp_server, $this->mailchimp_api_key)],
            'mailchimp_list_id'        => ['required', 'max:255', new MailchimpListIdCorrect($this->mailchimp_server, $this->mailchimp_api_key)],
            'lead_redirect_url'        => ['required', 'string', 'max:255'],
            'reservation_redirect_url' => ['required', 'string', 'max:255'],
            'data_app_project_id'      => ['required', "numeric"],
            'stripe_key'               => ['required_with:stripe_secret'],
            'stripe_secret'            => ['required_with:stripe_key'],
        ];
    }

}
