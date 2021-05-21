<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Project;
use App\Mail\ReceiptEmail;
use App\Http\Services\Facebook;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Http\Services\MailchimpMembersService;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendVipToDataApp($email, $project)
    {
        Http::withBasicAuth(config('general.data_app.email'), config('general.data_app.password'))->post("https://data.launchboom.co/api/add-action", [
            "email"   => $email,
            "site_id" => $project->data_app_project_id,
            "type"    => "vip",
            "value"   => $project->reservation_cost
        ]);
    }

    public function updateEmailReservedOnMailchimp($email, $project)
    {
        //send the email to mailchimp
        app(MailchimpMembersService::class, ["server" => $project->mailchimp_server, "apiKey" => $project->mailchimp_api_key, "listId" => $project->mailchimp_list_id])->importMember([
            "email_address" => $email,
            'reserved'      => true,
            'audience'      => request()->audience,
            'page_title'    => request()->page_title,
            'amount'      => $project->reservation_cost * 100,
        ]);
    }

    public function sendToMailchimpAndDataApps($email, Project $project, $event_id = null): void
    {
        try {
            $this->updateEmailReservedOnMailchimp($email, $project);
            $this->sendVipToDataApp($email, $project);
            Facebook::sendEvent($project, $email, 'Purchase', $event_id);

        } catch (Exception $exception) {
            Log::error($exception);
        }
    }

    public function sendReceiptEmail($email, $client_name, Project $project)
    {
        try {
            Mail::to($email)->send(new ReceiptEmail($email, $client_name, $project));
        } catch (Exception $exception) {
            Log::error($exception);
        }
    }

    public function sendToMailchimpData($email, $project)
    {
        //send the email to mailchimp
        app(MailchimpMembersService::class, ["server" => $project->mailchimp_server, "apiKey" => $project->mailchimp_api_key, "listId" => $project->mailchimp_list_id])->importMember([
            "email_address" => $email,
            'audience'      => request()->audience,
            'page_title'    => request()->page_title,
            'data'          => request()->data
        ]);
    }
}
