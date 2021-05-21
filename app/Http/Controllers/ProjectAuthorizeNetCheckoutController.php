<?php

namespace App\Http\Controllers;

use Omnipay\Omnipay;
use App\Models\Project;
use Illuminate\Http\Request;
use Omnipay\Common\CreditCard;
use App\Http\Services\Facebook;
use Illuminate\Support\Facades\Log;
use Omnipay\AuthorizeNetApi\ApiGateway;

class ProjectAuthorizeNetCheckoutController extends Controller
{
    public function create(Request $request, Project $project)
    {
        if ($request->has('email') && $request->has('AddToCart')) {
            Facebook::sendEvent($project, $request->email, 'AddToCart');
            $request->query->remove('AddToCart');
            return redirect($request->fullUrlWithQuery($request->query->all()));
        }

        return view("checkout.authorize-net", compact("project"));
    }

    public function store(Project $project)
    {
        /**@var ApiGateway $gateway */
        $gateway = Omnipay::create('AuthorizeNetApi_Api');
        $gateway->setAuthName(config("services.authorize_net.api_login_id"));
        $gateway->setTransactionKey(config("services.authorize_net.transaction_key"));
        $gateway->setSignatureKey(config("services.authorize_net.signature_key"));

        if (app()->environment("local"))
            $gateway->setTestMode(true);

        // Generate a unique merchant site transaction ID.
        $response = $gateway->purchase([
            'amount'               => $project->reservation_cost,
            'currency'             => 'USD',
            'transactionId'        => rand(100000000, 999999999),
            "card"                 => new CreditCard(),
            'opaqueDataDescriptor' => request("opaqueDataDescriptor"),
            'opaqueDataValue'      => request("opaqueDataValue"),
        ])->send();

        if ($response->getResultCode() == "Error") {
            if ($response->getData()["messages"]["message"][0]['code'] == "E00007") {

                return redirect()->back()->with("error", "Something went wrong, please try again later");
            }
            return redirect()->back()->with("error", "Something went wrong, please check your credit info and try again");
        }

        $this->sendToMailchimpAndDataApps(request()->email, $project);

        return redirect($project->reservation_redirect_url);
    }

}