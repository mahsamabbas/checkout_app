<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use App\Http\Services\Facebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Services\MailchimpMembersService;
use App\Http\Requests\Api\ProjectReserveStoreRequest;

class ProjectReserveController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param ProjectReserveStoreRequest $request
     * @param Project $project
     * @return void
     */
    public function store(ProjectReserveStoreRequest $request, Project $project)
    {
        setting(["segment_count" => setting('segment_count', 0)])->save();

        //send the email to mailchimp
        $email = $request->email;
        $event_id = $request->event_id;
        app(MailchimpMembersService::class, ["server" => $project->mailchimp_server, "apiKey" => $project->mailchimp_api_key, "listId" => $project->mailchimp_list_id])->importMember([
            "email_address" => $email,
            "audience"      => $request->audience,
            "page_title"    => $request->page_title,
            'segment'       => (bool) setting('segment_count', 0) % 2 == 0
        ]);

        Facebook::sendEvent($project, $email, 'Lead', $event_id);

        //@todo refactor this code
        //send the email to data.launchboom
        Http::withBasicAuth(config('general.data_app.email'), config('general.data_app.password'))->post("https://data.launchboom.co/api/add-action", [
            "email"   => $email,
            "site_id" => $project->data_app_project_id
        ]);

        return response()->json([
            "url" => $project->lead_redirect_url . "?event_id=" . $event_id
        ]);
    }

    public function checkout(Request $request, Project $project)
    {
        if ($request->has('email') && $request->has('data') && $request->get('data') != null) {
            try {
                $this->sendToMailchimpData($request->get('email'), $project);
            } catch (\Exception $exception) {
                Log::error($exception);
            }
            $request->query->remove('data');
            return response()->json([
                "url" => route('projects.checkouts.create', $project)
            ]);
        }
        return response()->json([], 422);
    }

    function cryptoJSAesEncrypt($passphrase, $plain_text)
    {

        $salt = openssl_random_pseudo_bytes(256);
        $iv = openssl_random_pseudo_bytes(16);
        //on PHP7 can use random_bytes() istead openssl_random_pseudo_bytes()
        //or PHP5x see : https://github.com/paragonie/random_compat

        $iterations = 999;
        $key = hash_pbkdf2("sha512", $passphrase, $salt, $iterations, 64);

        $encrypted_data = openssl_encrypt($plain_text, 'aes-256-cbc', hex2bin($key), OPENSSL_RAW_DATA, $iv);

        $data = ["ciphertext" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "salt" => bin2hex($salt)];
        return json_encode($data);
    }

}