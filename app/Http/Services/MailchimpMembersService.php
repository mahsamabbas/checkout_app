<?php


namespace App\Http\Services;


use Log;
use Exception;
use Illuminate\Http\Response;
use MailchimpMarketing\ApiClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

class MailchimpMembersService
{
    private $listId;
    private $client;
    private $server;

    public function __construct($server = null, $apiKey = null, $listId = null)
    {
        $this->listId = $listId;
        $this->server = $server;
        $this->client = new ApiClient();
        $this->client->setConfig([
            'apiKey' => $apiKey,
            'server' => $server,
        ]);
    }

    public function importMember(array $member)
    {
        try {
            $hashedEmail = md5(strtolower($member["email_address"]));
            $this->client->lists->setListMember($this->listId, $hashedEmail, [
                "email_address" => $member["email_address"],
                "email_type"    => "text",
                "status"        => "subscribed",
                "status_if_new" => "subscribed",
            ], true);

            $merge_fields = [
                "RESERVED" => $member["reserved"] ?? null,
                "AUDIENCE" => $member["audience"] ?? null,
                "PAGETITS" => $member["page_title"] ?? null,
                "SEGMENT"  => $member["segment"] ?? null,
                "AMOUNT"  => $member["amount"] ?? null,
            ];
            if (isset($member["data"])) {
                $merge_fields = array_merge($merge_fields, $member["data"]);
                $merge_fields = array_change_key_case($merge_fields, CASE_UPPER);
            }
            $this->client->lists->setListMember($this->listId, $hashedEmail, [
                "email_address" => $member["email_address"],
                "email_type"    => "text",
                "status"        => "subscribed",
                "status_if_new" => "subscribed",
                "merge_fields"  => $merge_fields
            ], false);

        } catch (RequestException $exception) {
            if ($exception->getCode() == 400)
                Log::critical("${member['email_address']} looks fake or invalid, please enter a real email address.");
            else
                Log::error($exception);
        } catch (Exception $exception) {
            Log::error($exception);
        }
    }

    public function importMembers(array $members)
    {
        foreach ($members as $member) {
            $this->importMember($member);
        }
    }

    /**
     * Check if the giving api key and server correct
     *
     * @param $server
     * @param $apiKey
     * @return bool
     */
    public function checkIfKeyAndServerCorrect($server, $apiKey)
    {
        try {
            $instance = new self($server, $apiKey, null);
            $instance->client->root->getRoot();
            return true;
        } catch (ClientException $exception) {
            if ($exception->getCode() != Response::HTTP_UNAUTHORIZED)
                Log::error("error in checkIfKeyAndServerCorrect: " . $exception);
            return false;
        } catch (Exception $exception) {
            Log::error("error in checkIfKeyAndServerCorrect: " . $exception);
            return false;
        }

    }

    /**
     * Check if the giving api key is correct
     *
     * @param $server
     * @param $listId
     * @param $apiKey
     * @return bool
     */
    public function checkIfListIdIsCorrect($server, $listId, $apiKey)
    {
        try {
            $instance = new self($server, $apiKey, null);
            $instance->client->lists->getListMembersInfo($listId);
            return true;
        } catch (ClientException $exception) {
            if ($exception->getCode() != Response::HTTP_NOT_FOUND && $exception->getCode() != Response::HTTP_UNAUTHORIZED)
                Log::error("error in checkIfListIdIsCorrect: " . $exception);
            return false;
        } catch (Exception $exception) {
            Log::error("error in checkIfListIdIsCorrect: " . $exception);
            return false;
        }
    }

    public function getAllMembers()
    {
        return $this->client->lists->getListMembersInfo($this->listId);
    }
}