<?php


namespace App\Http\Controllers;


use Stripe\Stripe;
use Stripe\Charge;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Services\Facebook;
use Stripe\Exception\CardException;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ProjectCheckoutStoreRequest;

class ProjectStripeCheckoutController extends Controller
{

    public function create(Request $request, Project $project)
    {
        Stripe::setApiKey($project->stripe_secret ?? config('cashier.secret'));

        if ($request->has('email')&& $request->has('AddToCart')) {
            Facebook::sendEvent($project, $request->email, 'AddToCart');
            $request->query->remove('AddToCart');
            return redirect($request->fullUrlWithQuery($request->query->all()));
        }

        return view("checkout.stripe", [
            'intent'     => User::query()->first()->createSetupIntent(),
            "project"    => $project,
            'email'      => request()->email,
            'page_title' => request()->page_title,
            'audience'   => request()->audience,
            'stripe_key' => $project->stripe_key ?? config('cashier.key')
        ]);
    }


    public function store(ProjectCheckoutStoreRequest $request, Project $project)
    {
        Stripe::setApiKey($project->stripe_secret ?? config('cashier.secret'));
        $event_id = idate("U");
        try {
            Charge::create([
                'amount'      => $project->reservation_cost * 100,
                'currency'    => 'usd',
                'card'        => $request->payment_method_nonce,
                'description' => "Project: $project->name ,Email: $request->email",
            ]);
            $this->sendReceiptEmail(request()->email, request()->client_name, $project);
        } catch (CardException $exception) {
            return back()->with('error', "Something went wrong, Please try another card");
        } catch (\Exception $exception) {
            \Log::error($exception);
            return back()->with('error', "Something went wrong, please try again later");
        }

        $this->sendToMailchimpAndDataApps(request()->email, $project, $event_id);


        return redirect($project->reservation_redirect_url  . "?event_id=" . $event_id);
    }
}