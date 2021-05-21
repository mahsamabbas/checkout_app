<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Http\Services\MailchimpMembersService;

class MailchimpListIdCorrect implements Rule
{
    private $server;
    private $apiKey;

    public function __construct($server, $apiKey)
    {
        $this->server = $server;
        $this->apiKey = $apiKey;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return app(MailchimpMembersService::class)->checkIfListIdIsCorrect($this->server, $value, $this->apiKey);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Mailchimp list id is wrong';
    }
}