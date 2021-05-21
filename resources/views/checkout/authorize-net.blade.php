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
            <di class="row">
                <form action="{{route('projects.checkouts.store',$project)}}" method="POST"
                      id="payment-form">
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
                    <div class="tab-content">
                        <div id="nav-tab-card" class="tab-pane fade show active">
                            <div class="form-group">
                                <label class="text-muted" for="email">Email Address</label>
                                <input type="email" class="form-control" name="email" id="email"
                                       data-rule-required="true"
                                       data-rule-email="true"
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
                                        <div class="d-flex">
                                            <div class="form-group w-50 mr-1">
                                                <input type="number" id="expMonth" placeholder="MM"
                                                       class="form-control"
                                                       data-rule-required="true"
                                                       {{--                                                           data-rule-ccExpMonth="true"--}}
                                                       required>
                                                <div class="invalid-feedback d-block"></div>
                                            </div>
                                            <div class="form-group w-50">
                                                <input type="number" id="expYear" placeholder="YY"
                                                       class="form-control"
                                                       data-rule-required="true"
                                                       {{--                                                           data-rule-ccExpYear="true"--}}
                                                       required>
                                                <div class="invalid-feedback d-block"></div>
                                            </div>
                                        </div>
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
                                <input id="cc-name" type="text" required class="form-control">
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
    {{--         <div class="row">
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
                        </ul>
                        <!-- End -->
                        <form action="{{route('projects.checkouts.store',$project)}}" method="POST"
                              id="payment-form">
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
                                <div id="nav-tab-card" class="tab-pane fade show active">
                                    <div class="form-group">
                                        <label for="email">Email Address</label>
                                        <input type="email" class="form-control" name="email" id="email"
                                               data-rule-required="true"
                                               data-rule-email="true"
                                               required>
                                    </div>

                                    <div class="form-group position-relative">
                                        <label for="cc-number">Credit card number</label>
                                        <input type="text" id="cc-number" class="form-control ccFormatMonitor"
                                               data-rule-ccNumber="true"
                                               required>
                                        <div id="card-image"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <div>
                                                <label>
                                                    <span class="hidden-xs">Expiration</span>
                                                </label>
                                                <div class="d-flex">
                                                    <div class="form-group w-50">
                                                        <input type="number" id="expMonth" placeholder="MM"
                                                               class="form-control"
                                                               data-rule-required="true"
                                                               {{ - -                                                           data-rule-ccExpMonth="true"-- }}
                                                               required>
                                                        <div class="invalid-feedback d-block"></div>
                                                    </div>
                                                    <div class="form-group w-50">
                                                        <input type="number" id="expYear" placeholder="YY"
                                                               class="form-control"
                                                               data-rule-required="true"
                                                               {{- -                                                           data-rule-ccExpYear="true"-- }}
                                                               required>
                                                        <div class="invalid-feedback d-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-4 mb-2">
                                            <label>CVV</label>
                                            <input id="cardCode" type="text" required class="form-control">
                                            <div class="invalid-feedback d-block"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="cc-name">Cardholder Name</label>
                                        <input id="cc-name" type="text" required class="form-control">
                                    </div>
                                    <div class="checkout-security">
                                        <p>We take your privacy seriously. This website is protected by a 256 bit SSL security encryption.</p>
                                        <img src="" alt="">
                                    </div>
                                    <button type="submit"
                                            class="subscribe btn btn-primary btn-block rounded-pill shadow-sm">
                                        Complete Reservation for ${{$project->reservation_cost}}
                                    </button>
                                </div>
                            </div>
                            <input type="hidden" name="opaqueDataDescriptor" id="opaqueDataDescriptor">
                            <input type="hidden" name="opaqueDataValue" id="opaqueDataValue">
                        </form>
                        <!-- End -->

                    </div>
                </div>
            </div> --}}
    </div>


</body>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script src="{{asset("/jquery.validate.js")}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-creditcardvalidator@1.2.0/jquery.creditCardValidator.js"></script>
<script src="https://rawgit.com/lopezton/jquery-creditcard-formatter/master/ccFormat.js"></script>

{{--@formatter:off--}}
@production
    <script type="text/javascript" src="https://js.authorize.net/v1/Accept.js" charset="utf-8"></script>
@else
    <script type="text/javascript" src="https://jstest.authorize.net/v1/Accept.js" charset="utf-8"></script>
@endproduction
{{--@formatter:on--}}
    <script>

        const authenticationFailedErrorCode = ["E_WC_03", "E_WC_02", "E_WC_01", "E_WC_10", "E_WC_13", "E_WC_14", "E_WC_18", "E_WC_19", "E_WC_21"]
        const expirationDateError = 'E_WC_08';
        const cardNumberError = "E_WC_05";
        const expirationMonthError = "E_WC_06";
        const expirationYearError = "E_WC_07";
        const CVVError = "E_WC_15";

        function validate(dateString) {

        }

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
            $("#payment-form button[type='submit']").prop('disabled', true);
            // Set up authorisation to access the gateway.
            var authData = {};
            authData.clientKey = @json(config("services.authorize_net.public_client_key"));
            authData.apiLoginID = @json(config("services.authorize_net.api_login_id"));

            // Capture the card details from the payment form.
            // The cardCode is the CVV.
            // You can include fullName and zip fields too, for added security.
            // You can pick up bank account fields in a similar way, if using
            // that payment method.
            var cardData = {};
            cardData.cardNumber = document.getElementById("cc-number").value.replace(/ /g, '');
            cardData.month = document.getElementById("expMonth").value;
            cardData.year = document.getElementById("expYear").value;
            cardData.cardCode = document.getElementById("cardCode").value;

            // Now send the card data to the gateway for tokenisation.
            // The responseHandler function will handle the response.
            var secureData = {};
            secureData.authData = authData;
            secureData.cardData = cardData;
            Accept.dispatchData(secureData, responseHandler);
        }

        function responseHandler(response) {
            $(".invalid-feedback").text("");

            if (response.messages.resultCode === "Error") {
                var i = 0;
                while (i < response.messages.message.length) {

                    if (authenticationFailedErrorCode.includes(response.messages.message[i].code)) {
                        alert("Something went wrong, please try again later")
                        @production
                        @php Log::channel("slack")->error("Authorize-net js error: ") @endphp
                        @endproduction
                    }

                @local
                    console.log(
                        response.messages.message[i].code + ": " +
                        response.messages.message[i].text
                    );
                    {{--@formatter:off--}}
                    @endlocal
                    {{--@formatter:off--}}

                    let message=response.messages.message[i].text;

                    switch (response.messages.message[i].code) {
                            case CVVError:
                                $("#cardCode").siblings(".invalid-feedback").text(message);
                                $("#cardCode").addClass("is-invalid");
                                break;
                        case expirationDateError:
                            $("#expMonth").siblings(".invalid-feedback").text(message)
                            $("#expMonth").addClass("is-invalid");
                            $("#expYear").siblings(".invalid-feedback").text(message)
                            $("#expYear").addClass("is-invalid");
                            break;
                        case expirationMonthError:
                            $("#expMonth").siblings(".invalid-feedback").text(message)
                            $("#expMonth").addClass("is-invalid");
                            break;
                        case expirationYearError:
                            $("#expYear").siblings(".invalid-feedback").text(message)
                            $("#expYear").addClass("is-invalid");
                            break;
                    }
                    i = i + 1;
                }
                $("#payment-form button[type='submit']").prop('disabled', false);

                return false;
            } else {
                paymentFormUpdate(response.opaqueData);
            }
        }
        function paymentFormUpdate(opaqueData) {
            document.getElementById("opaqueDataDescriptor").value = opaqueData.dataDescriptor;
            document.getElementById("opaqueDataValue").value = opaqueData.dataValue;
            document.getElementById("payment-form").submit();
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

            errorElement : 'div',
            errorClass:"is-invalid",
            validClass: "is-valid",
            errorPlacement: function(div, element) {
                div.addClass('invalid-feedback');
                div.insertAfter(element);
            },
            submitHandler: function() {
               sendPaymentDataToAnet()
            }
        });

    </script>
</html>
