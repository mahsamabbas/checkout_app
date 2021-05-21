<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{asset('/favicon.ico')}}" type="image/png">
    <title>{{$project->name}}</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.3.4/dist/select2-bootstrap4.min.css"
          rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('/css/checkout.css')}}">

    <style>
        .StripeElement div.__PrivateStripeElement {
            color: red;
            font-size: 100px;
            margin: 10px;
        }

        .StripeElement {
            display: block;
            width: 100%;
            /*height: 3.5em !important;*/
            padding: 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .StripeElement.is-invalid {
            border-color: #dc3545;
            padding-right: calc(1.5em + .75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23dc3545' viewBox='-2 -2 7 7'%3e%3cpath stroke='%23dc3545' d='M0 0l3 3m0-3L0 3'/%3e%3ccircle r='.5'/%3e%3ccircle cx='3' r='.5'/%3e%3ccircle cy='3' r='.5'/%3e%3ccircle cx='3' cy='3' r='.5'/%3e%3c/svg%3E");
            background-repeat: no-repeat;
            background-position: center right calc(.375em + .1875rem);
            background-size: calc(.75em + .375rem) calc(.75em + .375rem);
        }

        .StripeElement--focus {
            box-shadow: 0 1px 3px 0 #cfd7df;
        }

        .StripeElement--invalid {
            border-color: #fa755a;

        }

        .StripeElement--webkit-autofill {
            background-color: #fefde5 !important;
        }

        .text-highlight {
            color: #6fc3d0 !important;
            font-weight: bold;
            font-family: "Open Sans", "Helvetica", sans-serif;
        }

        body {
            background: #f5f5f5;
        }

        .rounded-lg {
            border-radius: 1rem;
        }

        .nav-pills .nav-link {
            color: #555;
        }

        .nav-pills .nav-link.active {
            color: #fff;
        }
    </style>
</head>
<body class="checkout h-100">
    <div class="h-100 d-md-flex">
        <div class="p-4 p-md-0 checkout-left bg-white col-md-6 p-75x d-flex flex-wrap">
            <div class="checkout-left-inner">
                <h2 class="uppercase blue font-weight-bold mb-3">{{$project->name}}</h2>
                <div class="checkout-progress">
                    <span class="opacity-75">Reservation</span>
                    <span class="font-weight-bold checkout-progress--active">Checkout</span>
                    <span class="opacity-75">Confirmation</span>
                </div>
                <div class="checkout-support-text mt-4 mt-md-5 mb-4 mb-md-5">
                    <p class="font-weight-bold">After you checkout you’ll receive an email from us inviting you to join
                                                the exclusive VIP Facebook Group.</p>
                </div>
                <hr>
                <h3 class="font-weight-normal mt-4 mt-md-0 mb-4">Reservation Review</h3>
                <div class="checkout-summary row">
                    <p class="font-weight-bold text-uppercase col-8">{{$project->name}} Reservation</p>
                    <h2 class="blue font-weight-bold col-4 text-right mt-n2">${{$project->reservation_cost}}</h2>
                </div>
            </div>
            <div class="d-none d-md-block checkout-left-bottom-content align-self-end">
                <hr>
                <p class="text-muted small pt-2">Disclaimer</p>
                <p class="text-muted small">{{$project->name}} is still in development and the final designs may change.
                                                               The images for limited editions are the latest prototypes
                                                               that we have, and we will update you with any changes to
                                                               appearance, cost, or function. Final VIP price is subject
                                                               to change, we will notify you before the campaign
                                                               launches.</p>
            </div>
        </div>
        <div class="p-4 p-md-0 checkout-right  col-md-6 p-75x payment">
            <div class="row">
                <form action="{{route('projects.checkouts.store',$project)}}" method="POST"
                      id="payment-form">
                    @csrf
                    <x-custom-payment-fields></x-custom-payment-fields>
                    <input type="hidden" id="token" name="payment_method_nonce">
                    <!-- Credit card form content -->
                    @if (session()->has("error"))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {!! session('error') !!}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="error alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="message"></div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="tab-content">
                        <div id="nav-tab-card" class="tab-pane fade show active">
                            <div class="form-group">
                                <label class="text-muted" for="email">Email Address</label>
                                <input type="email" class="form-control" name="email" id="email"
                                       placeholder="Email Address"
                                       data-rule-required="true"
                                       data-rule-email="true"
                                       value="{{$email}}"
                                       required>
                            </div>

                            <div class="form-group position-relative">
                                <label for="cc-number">Credit card number</label>
                                <div id="cc-number"></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8">
                                    <div>
                                        <label>
                                            <span class="hidden-xs">Expiration</span>
                                        </label>
                                        <div class="form-group">
                                            <div id="exp"></div>
                                        </div>

                                    </div>
                                </div>
                                <div class="form-group col-sm-4 mb-2">
                                    <label>CVV</label>
                                    <div id="cardCode"></div>
                                    <div class="invalid-feedback d-block"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="text-muted" for="cc-name">Cardholder Name</label>
                                <input id="cc-name" name="client_name" type="text" placeholder="Cardholder Name"
                                       required class="form-control">
                            </div>
                            <div class="checkout-security pt-3 pb-4 d-none d-md-flex">
                                <p class="small mt-3">We take your privacy seriously. This website is protected by a 256
                                                      bit SSL security encryption.</p>
                                <img class="w45" src="{{asset('/trust.svg')}}" alt="Secure">
                            </div>
                            <div class="text-center mt-4 mt-md-0">
                                <button type="submit"
                                        class="subscribe btn btn-primary btn-block rounded-pill shadow-sm checkout-button">
                                    Complete Reservation for ${{$project->reservation_cost}}
                                </button>
                            </div>
                            <div class="checkout-security d-flex pt-4 pb-4 d-md-none">
                                <p class="small mt-3">We take your privacy seriously. This website is protected by a 256
                                                      bit SSL security encryption.</p>
                                <img class="w45" src="{{asset('/trust.svg')}}" alt="Secure">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="opaqueDataDescriptor" id="opaqueDataDescriptor">
                    <input type="hidden" name="opaqueDataValue" id="opaqueDataValue">
                </form>
                <div class="d-md-none checkout-left-bottom-content align-self-end">
                    <hr>
                    <p class="text-muted small pt-2">Disclaimer</p>
                    <p class="text-muted small">{{$project->name}} is still in development and the final designs may
                                                                   change. The images for limited editions are the
                                                                   latest prototypes that we have, and we will update
                                                                   you with any changes to appearance, cost, or
                                                                   function. Final VIP price is subject to change, we
                                                                   will notify you before the campaign launches.</p>
                </div>
            </div>
            <!-- End -->
        </div>
        <!-- End -->

    </div>
</body>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="{{asset("/jquery.validate.js")}}"></script>
<script src="https://js.stripe.com/v3/"></script>
<script>
    let stripe = Stripe("{{ $stripe_key }}")

    function registerElements(elements, exampleName) {
        var formClass = '.' + exampleName;
        var example = document.querySelector(formClass);

        var form = example.querySelector('form');
        var error = form.querySelector('.error');
        var errorMessage = error.querySelector('.message');
        error.hidden = true;

        function enableInputs() {
            Array.prototype.forEach.call(
                form.querySelectorAll(
                    "input[type='tel']"
                ),
                function (input) {
                    input.removeAttribute('disabled');
                    input.removeAttribute('is-invalid');
                }
            );
        }

        function disableInputs() {
            Array.prototype.forEach.call(
                form.querySelectorAll(
                    "input[type='tel']"
                ),
                function (input) {
                    input.setAttribute('disabled', 'true');
                }
            );
        }

        function triggerBrowserValidation() {
            // The only way to trigger HTML5 form validation UI is to fake a user submit
            // event.
            var submit = document.createElement('input');
            submit.type = 'submit';
            submit.style.display = 'none';
            form.appendChild(submit);
            submit.click();
            submit.remove();
        }

        // Listen for errors from each Element, and show error messages in the UI.
        var savedErrors = {};
        elements.forEach(function (element, idx) {
            element.on('change', function (event) {
                if (event.error) {
                    error.classList.add('visible');
                    error.hidden = false;
                    savedErrors[idx] = event.error.message;
                    errorMessage.innerText = event.error.message;
                } else {
                    savedErrors[idx] = null;

                    // Loop over the saved errors and find the first one, if any.
                    var nextError = Object.keys(savedErrors)
                        .sort()
                        .reduce(function (maybeFoundError, key) {
                            return maybeFoundError || savedErrors[key];
                        }, null);

                    if (nextError) {
                        // Now that they've fixed the current error, show another one.
                        errorMessage.innerText = nextError;
                    } else {
                        // The user fixed the last error; no more errors.
                        error.classList.remove('visible');
                        error.hidden = true;
                    }
                }
            });
        });

        // Listen on the form's 'submit' handler...
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            $('.subscribe').prop('disabled', true);
            let oldText = $('.subscribe').text();
            $('.subscribe').text("Processing");
            // Trigger HTML5 validation UI on the form if any of the inputs fail
            // validation.
            var plainInputsValid = true;
            Array.prototype.forEach.call(form.querySelectorAll('input'), function (
                input
            ) {
                if (input.checkValidity && !input.checkValidity()) {
                    plainInputsValid = false;
                    return;
                }
            });
            if (!plainInputsValid) {
                triggerBrowserValidation();
                return;
            }

            // Show a loading screen...
            example.classList.add('submitting');

            // Disable all inputs.
            disableInputs();

            // Gather additional customer data we may have collected in our form.
            var name = form.querySelector('#' + exampleName + '-name');
            var address1 = form.querySelector('#' + exampleName + '-address');
            var city = form.querySelector('#' + exampleName + '-city');
            var state = form.querySelector('#' + exampleName + '-state');
            var zip = form.querySelector('#' + exampleName + '-zip');
            var additionalData = {
                name: name ? name.value : undefined,
                address_line1: address1 ? address1.value : undefined,
                address_city: city ? city.value : undefined,
                address_state: state ? state.value : undefined,
                address_zip: zip ? zip.value : undefined,
            };

            // Use Stripe.js to create a token. We only need to pass in one Element
            // from the Element group in order to create a token. We can also pass
            // in the additional customer data we collected in our form.
            stripe.createToken(elements[0], additionalData).then(function (result) {
                // Stop loading!
                example.classList.remove('submitting');
                if (result.token) {
                    // If we received a token, show the token ID.
                    document.querySelector('#token').value = result.token.id;
                    document.querySelector("#payment-form").submit();
                } else {
                    enableInputs();
                    $('.subscribe').prop('disabled', false);
                    $('.subscribe').text(oldText);
                    if (result.error.type != 'validation_error') {
                        alert('Something went wrong, please try again later')
                    }
                }
            });
        });
    }

    var elements = stripe.elements({
        fonts: [
            {
                cssSrc: 'https://fonts.googleapis.com/css?family=Quicksand',
            },
        ],
        // Stripe's examples are localized to specific languages, but if
        // you wish to have Elements automatically detect your user's locale,
        // use `locale: 'auto'` instead.
        locale: 'auto',
    });
    var style = {
        base: {
            'lineHeight': '1.5',
            'fontSize': '16px',
            'color': '#495057',
            'padding': '10px 10px !important',
            'height': '3.5em !important',
            'border': '1px solid #ced4da',
            'fontFamily': 'apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif'
        },
        invalid: {
            'border-color': '#dc3545',
            'padding-right': 'calc(1.5em + .75rem)',
            'background-image': "url(data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23dc3545' viewBox='-2 -2 7 7'%3e%3cpath stroke='%23dc3545' d='M0 0l3 3m0-3L0 3'/%3e%3ccircle r='.5'/%3e%3ccircle cx='3' r='.5'/%3e%3ccircle cy='3' r='.5'/%3e%3ccircle cx='3' cy='3' r='.5'/%3e%3c/svg%3E)",
            'background-repeat': "no-repeat",
            "background-position": "center right calc(.375em + .1875rem)",
            "background-size": "calc(.75em + .375rem) calc(.75em + .375rem)",
        }
    };
    var elementClasses = {
        focus: 'focus',
        empty: 'empty',
        invalid: 'is-invalid',
        valid: 'is-valid',
    };

    var cardNumber = elements.create('cardNumber', {
        'style': style,
        classes: elementClasses,
    });
    cardNumber.mount('#cc-number');

    var cardExpiry = elements.create('cardExpiry', {
        'style': style,
        classes: elementClasses,
    });
    cardExpiry.mount('#exp');

    var cardCvc = elements.create('cardCvc', {
        'style': style,
        classes: elementClasses,
    });
    cardCvc.mount('#cardCode');

    registerElements([cardNumber, cardExpiry, cardCvc], 'payment');
</script>
</html>
