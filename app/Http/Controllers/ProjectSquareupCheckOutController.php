<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Square\Environment;
use Square\Models\Money;
use Square\SquareClient;
use Illuminate\Support\Str;
use Square\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Services\Facebook;
use Illuminate\Support\Facades\Log;
use Square\Models\CreatePaymentRequest;
use App\Http\Requests\ProjectCheckoutStoreRequest;

class ProjectSquareupCheckOutController extends Controller
{
    public function create(Request $request, Project $project)
    {
        if ($request->has('email') && $request->has('data') && $request->get('data') != null) {
            try {
                $this->sendToMailchimpData($request->get('email'), $project);
            } catch (\Exception $exception) {
                Log::error($exception);
            }
            $request->query->remove('data');
            return response()->json([
                "url" => $request->fullUrlWithQuery($request->query->all())
            ]);
        }
        if ($request->has('email') && $request->has('AddToCart')) {
            Facebook::sendEvent($project, $request->email, 'AddToCart');
            $request->query->remove('AddToCart');
            return redirect($request->fullUrlWithQuery($request->query->all()));
        }
        return view("checkout.squareup", [
            'application_id' => config("services.squareup.application_id"),
            "project"        => $project,
            'email'          => request()->email,
            'page_title'     => request()->page_title,
            'audience'       => request()->audience,
        ]);
    }

    public function store(ProjectCheckoutStoreRequest $request, Project $project)
    {
        $client = new SquareClient([
            'accessToken' => config("services.squareup.token"),
            'environment' => app()->environment('local', 'staging') ? Environment::SANDBOX : Environment::PRODUCTION,
        ]);

        $paymentsApi = $client->getPaymentsApi();
        $body_sourceId = $request->payment_method_nonce;
        $body_idempotencyKey = Str::uuid();
        $body_amountMoney = new Money;
        $body_amountMoney->setAmount($project->reservation_cost * 100);
        $body_amountMoney->setCurrency(Currency::USD);
        $body = new CreatePaymentRequest(
            $body_sourceId,
            $body_idempotencyKey,
            $body_amountMoney
        );
        $body->setNote("Project: $project->name ,Email: $request->email");
        $apiResponse = $paymentsApi->createPayment($body);

        if ($apiResponse->isSuccess()) {
            $this->sendToMailchimpAndDataApps(request()->email, $project);
            $this->sendReceiptEmail(request()->email, request()->client_name, $project);

            return redirect($project->reservation_redirect_url);
        } else {
            $errors = $apiResponse->getErrors();
            Log::error($errors);
            return redirect()->back()->with("error", 'Something went wrong, please try again later');
        }

    }
}