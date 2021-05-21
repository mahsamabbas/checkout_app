<x-jet-form-section submit="update">
    <x-slot name="title">
        {{ __('Default Payment Gateway') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Manage your default payment gateway.') }}
    </x-slot>


    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4 relative">
            <select required name="default_payment_gateway" wire:model.defer="defaultPaymentGateway"
                    class="block appearance-none w-full bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded
                         shadow leading-tight focus:outline-none focus:shadow-outline">
                <option value="squareup"
                        @if(setting("payment_getaway", "squareup")=="squareup") selected @endif>Square
                </option>
                <option value="stripe"
                        @if(setting("payment_getaway", "squareup")=="stripe") selected @endif>Stripe
                </option>
                <option value="braintree" @if(setting("payment_getaway", "squareup")=="braintree") selected @endif>
                    Braintree
                </option>
                <option value="authorize_net"
                        @if(setting("payment_getaway", "squareup")=="authorize_net") selected @endif>Authorize.Net
                </option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 top-0 bottom-0 flex items-center px-2 text-gray-700">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 20 20">
                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                </svg>
            </div>
            <x-jet-input-error for="defaultPaymentGateway" class="mt-2"/>
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>

        <x-jet-button>
            {{ __('Save') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
