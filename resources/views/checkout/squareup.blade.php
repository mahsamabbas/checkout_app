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
        .sq-input {
            border-radius: 6px !important;
            border: 2px solid #CDD8E0 !important;
            height: 3.5em !important;
            padding: .5rem .75rem !important;
            font-size: 1rem;
        }

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

        #card-image.mastercard {
            background-position: 0 -281px;
            background-size: 86px 458px;
        }

        #card-image.amex {
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

        #card-image.diners_club_carte_blanche {
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
    <div class="h-100 d-md-flex ">
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
        <div class="p-4 p-md-0 checkout-right  col-md-6 p-75x">
            <div class="row">
                <form action="{{route('projects.checkouts.store',$project)}}" method="POST"
                      id="payment-form">
                    @csrf
                    <x-custom-payment-fields></x-custom-payment-fields>
                    <input type="hidden" id="nonce" name="payment_method_nonce">
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
                                <label class="text-muted" for="cc-number">Credit card number</label>
                                <input type="text" id="cc-number" class="form-control ccFormatMonitor"
                                       data-rule-ccNumber="true"
                                       required>
                                <div id="card-image"></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8">
                                    <div>
                                        <label class="text-muted">
                                            <span class="hidden-xs">Expiration</span>
                                        </label>
                                        <input type="number" id="exp" placeholder="MM/YY"
                                               class="form-control"
                                               data-rule-required="true"
                                               required>
                                        <div class="invalid-feedback d-block"></div>
                                    </div>
                                </div>
                                <div class="form-group col-sm-4 mb-2">
                                    <label class="text-muted">CVV</label>
                                    <input id="cardCode" type="text" required class="form-control">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-creditcardvalidator@1.2.0/jquery.creditCardValidator.js"></script>
<script src="https://rawgit.com/lopezton/jquery-creditcard-formatter/master/ccFormat.js"></script>
@production
    <script type="text/javascript" src="https://js.squareup.com/v2/paymentform"></script>
    @else
        <script type="text/javascript" src="https://js.squareupsandbox.com/v2/paymentform"></script>
        @endproduction
        <script type="text/javascript">
            const paymentForm = new SqPaymentForm({
                applicationId: @json($application_id),
                postalCode: false,
                inputClass: 'sq-input',
                inputStyles: [
                    {
                        lineHeight: '24px',
                        backgroundColor: 'transparent',
                        fontSize: '16px',
                        color: '#495057',
                        padding: '8px 0px',
                    }
                ],
                cardNumber: {
                    elementId: 'cc-number',
                    placeholder: 'Card Number'
                },
                cvv: {
                    elementId: 'cardCode',
                    placeholder: 'CVV'
                },
                expirationDate: {
                    elementId: 'exp',
                    placeholder: 'MM/YY'
                },
                // SqPaymentForm callback functions
                callbacks: {
                    cardNonceResponseReceived: function (errors, nonce, cardData) {
                        if (errors) {
                            // Log errors from nonce generation to the Javascript console
                            @if(config('app.debug')==true)
                            errors.forEach(function (error) {
                                console.log('  ' + error.message);
                            });
                            @endif
                            alert("Something went wrong, please check your credentials and try again later")
                            return;
                        }
                        document.getElementById("nonce").value = nonce
                        document.getElementById("payment-form").submit()
                    }
                }
            });
        </script>
        <script>
            jQuery.validator.addMethod("ccNumber", function (value, element) {
                return $(element).validateCreditCard().valid;
            }, "Please enter a valid credit card number");

            jQuery.validator.addMethod("ccExpYear", function (value, element, params) {
                var regEx = /^\d{4}$/;
                if (!value.match(regEx))
                    return false;
                // Invalid format
                var d = new Date(value);
                var dNum = d.getTime();
                if (!dNum && dNum !== 0)
                    return false;
                // NaN value, Invalid date
                return d.toISOString().slice(0, 4) === value;
            }, "Invalid expiration year!");

            jQuery.validator.addMethod("ccExpMonth", function (value, element, params) {
                var regEx = /^\d{2}$/;
                if (!value.match(regEx))
                    return false;
                // Invalid format
                var d = new Date(value);
                var dNum = d.getTime();
                if (!dNum && dNum !== 0)
                    return false;
                // NaN value, Invalid date
                return d.toISOString().slice(0, 4) === value;
            }, "Invalid expiration year!");

            function sendPaymentDataToAnet() {
                paymentForm.requestCardNonce();
            }

            $('#cc-number').validateCreditCard(function (result) {
                if (result.card_type) {
                    $('#card-image').removeClass().addClass(result.card_type.name);
                } else $('#card-image').removeClass();
                if (result.valid) {
                    $(this).addClass('is-valid');
                    return true;
                } else {
                    $(this).removeClass('is-invalid');
                    return false;
                }
            });
            $('#payment-form').validate({

                errorElement: 'div',
                errorClass: "is-invalid",
                validClass: "is-valid",
                errorPlacement: function (div, element) {
                    div.addClass('invalid-feedback');
                    div.insertAfter(element);
                },
                submitHandler: function () {
                    sendPaymentDataToAnet()
                }
            });

        </script>
</html>
