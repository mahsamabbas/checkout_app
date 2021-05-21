<?php


namespace App\Http\Services;

use FacebookAds\Api;
use App\Models\Project;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\ServerSide\CustomData;
use FacebookAds\Object\ServerSide\Event;
use FacebookAds\Object\ServerSide\EventRequest;
use FacebookAds\Object\ServerSide\UserData;
use FacebookAds\Object\ServerSide\ActionSource;

class Facebook
{
    public static function sendEvent(Project $project, $email, $event, $event_id = null)
    {
        try {
            $access_token = config('general.facebook_access_token');
            $pixel_id = $project->pixel_id;

            Api::init(null, null, $access_token);
            $api = Api::instance();
            $api->setLogger(new CurlLogger());

            $events = [];

            $userData = (new UserData())
                ->setClientUserAgent($_SERVER['HTTP_USER_AGENT'])
                ->setClientIpAddress($_SERVER['REMOTE_ADDR'])
                ->setEmail($email);

            $customData = null;
            if ($event == "Purchase") {
                $customData = (new CustomData())
                    ->setValue($project->reservation_cost * 100)
                    ->setCurrency("USD");
            }


            $event_0 = (new Event())
                ->setEventName($event)
                ->setEventId($event_id)
                ->setEventTime(now()->timestamp)
                ->setUserData($userData)
                ->setCustomData($customData)
                ->setActionSource("email")
                ->setEventSourceUrl(route('projects.checkouts.create', $project))
                ->setActionSource(ActionSource::WEBSITE);

            array_push($events, $event_0);

            $data = [];
            if (app()->environment('local')) {
                $data = ['test_event_code' => 'TEST58941'];
            }

            $request = (new EventRequest($pixel_id, $data))
                ->setEvents($events);

            $request->execute();
        } catch (\Exception $exception) {
            \Log::error('error for facebook', [
                'error'     => $exception->getMessage(),
                'exception' => $exception
            ]);
        }

    }
}