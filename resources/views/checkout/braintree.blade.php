<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{asset('/favicon.png')}}" type="image/png">
    <title>{{$project->name}}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.3.4/dist/select2-bootstrap4.min.css"
          rel="stylesheet"/>

    <style>
        #card-image {
            position: absolute;
            top: 2.2em;
            right: 1.5em;
            width: 44px;
            height: 28px;
            background-image: url(https://s3-us-west-2.amazonaws.com/s.cdpn.io/346994/card_sprite.png);
            background-size: 86px 458px;
            border-radius: 4px;
            background-position: -100px 0;
            background-repeat: no-repeat;
            margin-bottom: 1em;
        }

        #card-image.visa {
            background-position: 0 -398px;
        }

        #card-image.master-card {
            background-position: 0 -281px;
        }

        #card-image.american-express {
            background-position: 0 -370px;
        }

        #card-image.discover {
            background-position: 0 -163px;
        }

        #card-image.maestro {
            background-position: 0 -251px;
        }

        #card-image.jcb {
            background-position: 0 -221px;
        }

        #card-image.diners-club {
            background-position: 0 -133px;
        }

        .text-highlight {
            color: #6fc3d0 !important;
            font-weight: bold;
            font-family: "Open Sans", "Helvetica", sans-serif;
        }

        body {
            background: #f5f5f5;
        }

        .braintree-hosted-fields-focused {
            color: #495057;
            background-color: #fff;
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .braintree-hosted-fields-focused.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
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

        [aria-label="PayPal Checkout"] {
            background-color: red;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <!-- For demo purpose -->
        <div class="row mb-4">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4"></h1>
                <p class="lead mb-0">
                    After you checkout you’ll receive an email from us inviting you to join the
                    exclusive VIP Facebook Group.
                </p>
            </div>
        </div>
        <!-- End -->
        <div class="row">
            <div class="col-lg-7 mx-auto">
                <div class="bg-white rounded-lg shadow-sm p-5">
                    <!-- Credit card form tabs -->
                    <ul role="tablist" class="nav bg-light nav-pills rounded-pill nav-fill mb-4">
                        <li class="nav-item">
                            <a data-toggle="pill" href="#nav-tab-card" class="nav-link active rounded-pill">
                                <i class="fa fa-credit-card"></i>
                                Credit Card
                            </a>
                        </li>
                        <li class="nav-item">
                            <a data-toggle="pill" href="#nav-tab-paypal" class="nav-link rounded-pill">
                                <i class="fa fa-paypal"></i>
                                Paypal
                            </a>
                        </li>
                    </ul>
                    <!-- End -->
                    <form action="{{route('projects.checkouts.store',$project)}}" novalidate="" method="POST"
                          id="payment-form">
                        <input type="hidden" id="payment-method-nonce" name="payment_method_nonce">
                        @csrf
                        <x-custom-payment-fields></x-custom-payment-fields>
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
                        <div>
                            <h4>RESERVATION REVIEW</h4>
                            <div class="row">
                                <div class="col-6">
                                    PRODUCT
                                </div>
                                <div class="col-6 text-right">
                                    TOTAL
                                </div>
                                <div class="col-6 my-2 text-uppercase text-highlight">
                                    {{$project->name}}
                                </div>
                                <div class="col-6 my-2 text-right text-highlight">
                                    ${{$project->reservation_cost}}
                                </div>
                            </div>
                        </div>
                        <hr style="">

                        <div class="tab-content">
                            <!-- credit card info-->
                            <div id="nav-tab-card" class="tab-pane fade show active">
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" class="form-control" name="email" id="email">
                                    <div class="invalid-feedback">
                                        Email Address field is required
                                    </div>
                                </div>
                                <div class="form-group position-relative">
                                    <label for="cc-number">Credit card number</label>
                                    <div class="form-control" id="cc-number"></div>
                                    <div id="card-image"></div>
                                    <div class="invalid-feedback">
                                        Credit card number is required
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8">
                                        <label for="cc-expiration">Expiration</label>
                                        <div class="form-control" id="cc-expiration"></div>
                                        <div class="invalid-feedback">
                                            Expiration date required
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="cc-expiration">CVV</label>
                                        <div class="form-control" id="cc-cvv"></div>
                                        <div class="invalid-feedback">
                                            Security code required
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="cc-name">Cardholder Name</label>
                                    <div class="form-control" id="cc-name"></div>
                                    <div class="invalid-feedback">
                                        Cardholder Name is required
                                    </div>
                                </div>
                                <button type="submit"
                                        class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">
                                    Confirm
                                </button>
                            </div>
                            <!-- End -->

                            <!-- Paypal info -->
                            <div id="nav-tab-paypal" class="tab-pane fade">
                                <p id="paypal-button"></p>
                            </div>

                        </div>
                    </form>
                    <!-- End -->

                </div>
            </div>
        </div>
    </div>


</body>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<script src="https://js.braintreegateway.com/web/3.69.0/js/client.min.js"></script>
<script src="https://js.braintreegateway.com/web/3.69.0/js/hosted-fields.min.js"></script>

<!-- Load PayPal's checkout.js Library. -->
<script src="https://www.paypalobjects.com/api/checkout.js" data-version-4 log-level="warn"></script>

<!-- Load the PayPal Checkout component. -->
<script src="https://js.braintreegateway.com/web/3.69.0/js/paypal-checkout.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
    var form = $('#payment-form');

    braintree.client.create({
        authorization: @json($clientToken)
    }, function (err, clientInstance) {
        if (err) {
            console.error(err);
            return;
        }

        braintree.hostedFields.create({
            client: clientInstance,
            styles: {
                input: {
                    // change input styles to match
                    // bootstrap styles
                    'font-size': '1rem',
                    color: '#495057'
                }
            },
            fields: {
                cardholderName: {
                    selector: '#cc-name',
                },
                number: {
                    selector: '#cc-number',
                },
                cvv: {
                    selector: '#cc-cvv',
                },
                expirationDate: {
                    selector: '#cc-expiration',
                    placeholder: 'MM / YY'
                }
            }
        }, function (err, hostedFieldsInstance) {
            if (err) {
                console.error(err);
                return;
            }

            function createInputChangeEventListener(element) {
                return function () {
                    validateInput(element);
                }
            }

            function setValidityClasses(element, validity) {
                if (validity) {
                    element.removeClass('is-invalid');
                    element.addClass('is-valid');
                } else {
                    element.addClass('is-invalid');
                    element.removeClass('is-valid');
                }
            }

            function validateInput(element) {
                if (!element.val() || !element.val().trim()) {
                    setValidityClasses(element, false);
                    return false;
                }
                setValidityClasses(element, true);
                return true;
            }

            function validateEmail() {
                var baseValidity = validateInput(email);
                if (!baseValidity) {
                    return false;
                }
                if (email.val().indexOf('@') === -1) {
                    setValidityClasses(email, false);
                    return false;
                }
                setValidityClasses(email, true);
                return true;
            }

            //validate all fields
            $(".validate-field").on('change', function () {
                validateInput($(this));
            });

            //custom field validations
            var ccName = $('#cc-name');
            var email = $('#email');
            ccName.on('change', function () {
                validateInput(ccName);
            });

            email.on('change', validateEmail);


            hostedFieldsInstance.on('validityChange', function (event) {
                var field = event.fields[event.emittedBy];

                // Remove any previously applied error or warning classes
                $(field.container).removeClass('is-valid');
                $(field.container).removeClass('is-invalid');

                if (field.isValid) {
                    $(field.container).addClass('is-valid');
                } else if (field.isPotentiallyValid) {
                    // skip adding classes if the field is
                    // not valid, but is potentially valid
                } else {
                    $(field.container).addClass('is-invalid');
                }
            });

            hostedFieldsInstance.on('cardTypeChange', function (event) {
                var cardBrand = $('#card-brand');
                var cvvLabel = $('[for="cc-cvv"]');

                if (event.cards.length === 1) {
                    $('#card-image').removeClass().addClass(event.cards[0].type);
                    var card = event.cards[0];

                    // change pay button to specify the type of card
                    // being used
                    cardBrand.text(card.niceType);
                    // update the security code label
                    cvvLabel.text(card.code.name);
                } else {
                    // reset to defaults
                    $('#card-image').removeClass()
                    cardBrand.text('Card');
                    cvvLabel.text('CVV');
                }
            });

            form.submit(function (event) {
                event.preventDefault();
                var formIsInvalid = false;
                var state = hostedFieldsInstance.getState();

                // perform validations on the non-Hosted Fields
                // inputs
                if (!validateEmail()) {
                    formIsInvalid = true;
                }
                //validate custom fields
                $('#payment-form input, #payment-form select').each(function (index) {
                    if (!validateInput($(this)) && !$(this).is(":hidden"))
                        formIsInvalid = true;
                });
                // Loop through the Hosted Fields and check
                // for validity, apply the is-invalid class
                // to the field container if invalid
                Object.keys(state.fields).forEach(function (field) {
                    if (!state.fields[field].isValid) {
                        $(state.fields[field].container).addClass('is-invalid');
                        formIsInvalid = true;
                    }
                });

                if (formIsInvalid) {
                    // skip tokenization request if any fields are invalid
                    return;
                }

                hostedFieldsInstance.tokenize(function (err, payload) {
                    if (err) {
                        @if(app()->environment('production'))
                        alert("Something went wrong, please try again later")
                        @else
                        alert(err);
                        @endif

                            return;
                    }
                    // on your form and submit the form programatically
                    $('#payment-method-nonce').val(payload.nonce);
                    document.getElementById("payment-form").submit();

                });
            });
        });
        // Create a PayPal Checkout component.
        braintree.paypalCheckout.create({
            client: clientInstance
        }, function (paypalCheckoutErr, paypalCheckoutInstance) {

            // Stop if there was a problem creating PayPal Checkout.
            // This could happen if there was a network error or if it's incorrectly
            // configured.
            if (paypalCheckoutErr) {
                @if(app()->environment('production'))
                alert("Something went wrong, please try again later")
                @else
                console.error('Error creating PayPal Checkout:', paypalCheckoutErr);
                @endif
                    return;
            }

            // Set up PayPal with the checkout.js library
            paypal.Button.render({
                env: @json($environment), // or 'production'
                commit: true,

                payment: function () {
                    return paypalCheckoutInstance.createPayment({
                        // http://braintree.github.io/braintree-web/current/PayPalCheckout.html#createPayment
                        flow: 'checkout', // Required
                        amount: @json($project->reservation_cost), // Required
                        currency: 'USD', // Required
                    });
                },

                onAuthorize: function (data, actions) {
                    return paypalCheckoutInstance.tokenizePayment(data, function (err, payload) {
                        // Submit `payload.nonce` to your server.
                        document.querySelector('#email').value = payload.details.email;
                        document.querySelector('#payment-method-nonce').value = payload.nonce;
                        document.getElementById("payment-form").submit();
                    });
                },

                onCancel: function (data) {
                    // console.log('checkout.js payment cancelled', JSON.stringify(data, 0, 2));
                },

                onError: function (err) {
                    @if(app()->environment('production'))
                    alert("Something went wrong, please try again later")
                    @else
                    alert('checkout.js error', err);
                    @endif
                }
            }, '#paypal-button').then(function () {

            });

        });
    });
</script>
</html>
