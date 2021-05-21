<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;
use anlutro\LaravelSettings\Facade as Setting;

class DefaultPaymentGateway extends Component
{
    public $defaultPaymentGateway;

    public function mount()
    {
        $this->defaultPaymentGateway = setting("payment_getaway", "authorize_net");
    }

    public function update()
    {
        $this->resetErrorBag();

        $this->validate([
            "defaultPaymentGateway" => "required|in:authorize_net,braintree,squareup,stripe",
        ]);
        setting(["payment_getaway" => $this->defaultPaymentGateway]);
        \setting()->save();

        \Artisan::call("route:cache");

        $this->emit("saved");
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('profile.default-payment-gateway');
    }
}