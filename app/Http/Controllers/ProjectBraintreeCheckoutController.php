<?php

namespace App\Http\Controllers;

use Braintree\Gateway;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Services\Facebook;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\ProjectCheckoutStoreRequest;

class ProjectBraintreeCheckoutController extends Controller
{
    private $gateway;

    public function __construct()
    {
        $this->gateway = new Gateway([
            'environment' => config("services.braintree.environment"),
            'merchantId'  => config("services.braintree.merchant_id"),
            'publicKey'   => config("services.braintree.public_key"),
            'privateKey'  => config("services.braintree.private_key")
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Project $project
     * @return Response
     */
    public function create(Request $request,Project $project)
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
        if ($request->has('email')&& $request->has('AddToCart')) {
            Facebook::sendEvent($project, $request->email, 'AddToCart');
            $request->query->remove('AddToCart');
            return redirect($request->fullUrlWithQuery($request->query->all()));
        }
        return view("checkout.braintree", [
            "clientToken" => $this->gateway->clientToken()->generate(),
            "project"     => $project,
            'environment' => config("services.braintree.environment")
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProjectCheckoutStoreRequest $request
     * @param Project $project
     * @return void
     */
    public function store(ProjectCheckoutStoreRequest $request, Project $project)
    {
        $result = $this->gateway->transaction()->sale([
            'amount'             => $project->reservation_cost,
            'paymentMethodNonce' => $request->payment_method_nonce,
        ]);
        if (!$result->success) {
            $errors = "";
            foreach ($result->errors->deepAll() as $error) {
                if ($error->code == 91565)
                    $errors .= "Page token expired, please refresh the page and try again <br>";
                else
                    $errors .= $error->message . "<br>";
            }
            return redirect()->back()->with('error', $errors);
        }
        $this->sendToMailchimpAndDataApps(request()->email, $project);

        return redirect($project->reservation_redirect_url);
    }


}