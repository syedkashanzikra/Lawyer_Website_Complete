@extends('layouts.app')

@php
    $user = Auth::user();
    $settings = App\Models\Utility::settings();
@endphp

@push('custom-script')
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>

    <script>
        var type = window.location.hash.substr(1);
        $('.list-group-item').removeClass('active');
        $('.list-group-item').removeClass('text-primary');
        if (type != '') {
            $('a[href="#' + type + '"]').addClass('active').removeClass('text-primary');
        } else {
            $('.list-group-item:eq(0)').addClass('active').removeClass('text-primary');
        }

        $(document).on('click', '.list-group-item', function() {
            $('.list-group-item').removeClass('active');
            $('.list-group-item').removeClass('text-primary');
            setTimeout(() => {
                $(this).addClass('active').removeClass('text-primary');
            }, 10);
        });

        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })
    </script>

    <script type="text/javascript">
        $(document).ready(function() {

        });

        $(document).on('click', '.apply-coupon', function(e) {
            e.preventDefault();
            var where = $(this).attr('data-from');

            applyCoupon($('#' + where + '_coupon').val(), where);
        })
        function applyCoupon(coupon_code, where) {

            if (coupon_code != '') {
                $.ajax({
                    url: '{{ route('apply.coupon') }}',
                    datType: 'json',
                    data: {
                        plan_id: '{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}',
                        coupon: coupon_code,
                        frequency:$('input[name="' + where + '_payment_frequency"]:checked').val()
                    },
                    success: function(data) {


                        if (data.is_success) {
                            $('.' + where + '-coupon-tr').show().find('.' + where + '-coupon-price').text(data.discount_price);
                            $('.' + where + '-final-price').text(data.final_price);

                            show_toastr('success', data.message, 'success');
                        } else {
                            $('.' + where + '-coupon-tr').hide().find('.' + where + '-coupon-price').text('');
                            $('.' + where + '-final-price').text(data.final_price);
                            show_toastr('error', data.message,'error');
                        }
                    }
                })
            }
            else
            {
                show_toastr('error', '{{ __('Invalid Coupon Code.') }}');
                $('.' + where + '-coupon-tr').hide().find('.' + where + '-coupon-price').text('');
            }
        }
    </script>

    <script type="text/javascript">
        @if(isset($admin_payment_setting['is_stripe_enabled']) && $admin_payment_setting['is_stripe_enabled']== 'on' && !empty($admin_payment_setting['stripe_key']) && !empty($admin_payment_setting['stripe_secret']))

            var stripe = Stripe('{{ $admin_payment_setting['stripe_key'] }}');
            var elements = stripe.elements();

            // Custom styling can be passed to options when creating an Element.
            var style = {
                base: {
                    // Add your base input styles here. For example:
                    fontSize: '14px',
                    color: '#32325d',
                },
            };

            // Create an instance of the card Element.
            var card = elements.create('card', {
                style: style
            });

            // Add an instance of the card Element into the `card-element` <div>.
            card.mount('#card-element');
            // Create a token or display an error when the form is submitted.
            var form = document.getElementById('payment-form');

            form.addEventListener('submit',(event) => {
                    event.preventDefault();
                stripe.createToken(card).then(function (result) {
                    if (result.error) {
                        $("#card-errors").html(result.error.message);
                        toastrs('Error', result.error.message, 'error');
                    } else {
                        // Send the token to your server.
                        stripeTokenHandler(result.token);
                    }
                });
            });

            function stripeTokenHandler(token) {
                // Insert the token ID into the form so it gets submitted to the server
                var form = document.getElementById('payment-form');
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token.id);
                form.appendChild(hiddenInput);
                // Submit the form
                form.submit();
            }
        @endif
    </script>

    @if (!empty($admin_payment_setting['is_paystack_enabled']) && isset($admin_payment_setting['is_paystack_enabled']) && $admin_payment_setting['is_paystack_enabled'] == 'on')
        <script src="https://js.paystack.co/v1/inline.js"></script>
        <script>
            $(document).on("click", "#pay_with_paystack", function() {
                $('#paystack-payment-form').ajaxForm(function(res) {
                    if (res.flag == 1)
                    {
                        var coupon_id = res.coupon;
                        var paystack_callback = "{{ url('/plan/paystack') }}";
                        var order_id = '{{ time() }}';
                        var handler = PaystackPop.setup({
                            key: '{{ $admin_payment_setting['paystack_public_key'] }}',
                            email: res.email,
                            amount: res.total_price * 100,
                            currency: res.currency,
                            ref: 'pay_ref_id' + Math.floor((Math.random() * 1000000000) +
                                1
                            ), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
                            metadata: {
                                custom_fields: [{
                                    display_name: "Email",
                                    variable_name: "email",
                                    value: res.email,
                                }]
                            },

                            callback: function(response) {
                                console.log(response.reference, order_id);
                                window.location.href = paystack_callback + '/' + response
                                    .reference + '/' + '{{ encrypt($plan->id) }}' +
                                    '?coupon_id=' + coupon_id
                            },
                            onClose: function() {
                                alert('window closed');
                            }
                        });
                        handler.openIframe();
                    } else if (res.flag == 2) {

                    } else {
                        show_toastr('error', data.message);
                    }

                }).trigger('submit');
            });
        </script>
    @endif

    @if(!empty($admin_payment_setting['is_flutterwave_enabled']) && isset($admin_payment_setting['is_flutterwave_enabled']) && $admin_payment_setting['is_flutterwave_enabled'] == 'on')
        <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>

        <script>
            //    is_flutterwave_enabled Payment
            $(document).on("click", "#pay_with_flaterwave", function () {

                $('#flaterwave-payment-form').ajaxForm(function (res) {
                    if(res.flag == 1){
                        var coupon_id = res.coupon;
                        var API_publicKey = '';
                        if("{{ isset($admin_payment_setting['flutterwave_public_key'] ) }}"){
                            API_publicKey = "{{$admin_payment_setting['flutterwave_public_key']}}";
                        }
                        var nowTim = "{{ date('d-m-Y-h-i-a') }}";
                        var flutter_callback = "{{ url('/plan/flaterwave') }}";
                        var x = getpaidSetup({
                            PBFPubKey: API_publicKey,
                            customer_email: '{{Auth::user()->email}}',
                            amount: res.total_price,
                            currency: res.currency,
                            txref: nowTim + '__' + Math.floor((Math.random() * 1000000000)) + 'fluttpay_online-' +
                                {{ date('Y-m-d') }},
                            meta: [{
                                metaname: "payment_id",
                                metavalue: "id"
                            }],
                            onclose: function () {
                            },
                            callback: function (response) {
                                var txref = response.tx.txRef;
                                if (
                                    response.tx.chargeResponseCode == "00" ||
                                    response.tx.chargeResponseCode == "0"
                                ) {
                                    window.location.href = flutter_callback + '/' + txref + '/' + '{{\Illuminate\Support\Facades\Crypt::encrypt($plan->id)}}?coupon_id=' + coupon_id+'&payment_frequency='+res.payment_frequency;
                                } else {
                                    // redirect to a failure page.
                                }
                                x.close(); // use this to close the modal immediately after payment.
                            }});
                    }else if(res.flag == 2){

                    }else{
                        show_toastr('Error', data.message, 'msg');
                    }

                }).trigger('submit');
            });
        </script>
    @endif

    @if (!empty($admin_payment_setting['is_razorpay_enabled']) && isset($admin_payment_setting['is_razorpay_enabled']) && $admin_payment_setting['is_razorpay_enabled'] == 'on')
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script>
            // Razorpay Payment

            $(document).on("click", "#pay_with_razorpay", function() {
                $('#razorpay-payment-form').ajaxForm(function(res) {
                    if (res.flag == 1) {

                        var razorPay_callback = '{{ url('/plan/razorpay') }}';
                        var totalAmount = res.total_price * 100;
                        var coupon_id = res.coupon;
                        var API_publicKey = '';
                        if ("{{ isset($admin_payment_setting['razorpay_public_key']) }}") {
                            API_publicKey = "{{ $admin_payment_setting['razorpay_public_key'] }}";
                        }
                        var options = {
                            "key": API_publicKey, // your Razorpay Key Id
                            "amount": totalAmount,
                            "name": 'Plan',
                            "currency": res.currency,
                            "description": "",
                            "handler": function(response) {
                                window.location.href = razorPay_callback + '/' + response
                                    .razorpay_payment_id + '/' +
                                    '{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}?coupon_id=' +
                                    coupon_id + '&payment_frequency=' + res.payment_frequency;
                            },
                            "theme": {
                                "color": "#528FF0"
                            }
                        };
                        var rzp1 = new Razorpay(options);
                        rzp1.open();
                    } else if (res.flag == 2) {

                    } else {
                        show_toastr('Error', data.message, 'msg');
                    }

                }).trigger('submit');
            });
        </script>
    @endif

    @if ($admin_payment_setting['is_payfast_enabled'] == 'on' && !empty($admin_payment_setting['payfast_merchant_id']) && !empty($admin_payment_setting['payfast_merchant_key']))
        <script>
            $(document).ready(function() {
                get_payfast_status(amount = 0, coupon = null);
            })

            function get_payfast_status(amount, coupon) {

                var plan_id = $('#plan_id').val();

                $.ajax({
                    url: '{{ route('payfast.payment') }}',
                    method: 'POST',
                    data: {
                        'plan_id': plan_id,
                        'coupon_amount': amount,
                        'coupon_code': coupon
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        if (data.success == true) {
                            $('#get-payfast-inputs').append(data.inputs);

                        } else {
                            show_toastr('error', data.inputs, 'error')
                        }
                    }
                });
            }
        </script>
    @endif

    <script src="https://code.jquery.com/jquery-3.5.1.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.js"
        integrity="sha384-qlmct0AOBiA2VPZkMY3+2WqkHtIQ9lSdAsAn5RUJD/3vA5MKDgSGcdmIv4ycVxyn" crossorigin="anonymous">
    </script>
@endpush


@section('page-title')
    {{ __('Plan Payment') }}
@endsection

@section('breadcrumb')

    <li class="breadcrumb-item"><a href="{{ route('plans.index') }}">{{ __('Plan') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Plan Payment') }}</li>
@endsection

@section('content')

        <div class="col-sm-12">
            <div class="row g-0">
                <div class="col-xl-3 border-end border-bottom">
                    <div class="card shadow-none bg-transparent sticky-top">

                            <div class="list-group list-group-flush rounded-0" id="useradd-sidenav">
                                @if (isset($admin_payment_setting['is_manually_enabled']) && $admin_payment_setting['is_manually_enabled'] == 'on')
                                    <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                        href="#manually-payment" role="tab" aria-controls="manually"
                                        aria-selected="true">{{ __('Manually') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payment_setting['is_bank_enabled']) && $admin_payment_setting['is_bank_enabled'] == 'on')
                                    <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                        href="#bank-payment" role="tab" aria-controls="manually"
                                        aria-selected="true">{{ __('Bank Transfer') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payment_setting['is_stripe_enabled']) && $admin_payment_setting['is_stripe_enabled'] == 'on')
                                    <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                        href="#stripe-payment" role="tab" aria-controls="stripe"
                                        aria-selected="true">{{ __('Stripe') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payment_setting['is_paypal_enabled']) && $admin_payment_setting['is_paypal_enabled'] == 'on')
                                    <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                        href="#paypal-payment" role="tab" aria-controls="paypal"
                                        aria-selected="false">{{ __('Paypal') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payment_setting['is_paystack_enabled']) && $admin_payment_setting['is_paystack_enabled'] == 'on')
                                    <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                        href="#paystack-payment" role="tab" aria-controls="paystack"
                                        aria-selected="false">{{ __('Paystack') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payment_setting['is_flutterwave_enabled']) && $admin_payment_setting['is_flutterwave_enabled'] == 'on')
                                    <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                        href="#flutterwave-payment" role="tab" aria-controls="flutterwave"
                                        aria-selected="false">{{ __('Flutterwave') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payment_setting['is_razorpay_enabled']) && $admin_payment_setting['is_razorpay_enabled'] == 'on')
                                    <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                        href="#razorpay-payment" role="tab" aria-controls="razorpay"
                                        aria-selected="false">{{ __('Razorpay') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payment_setting['is_paytm_enabled']) && $admin_payment_setting['is_paytm_enabled'] == 'on')
                                    <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                        href="#paytm-payment" role="tab" aria-controls="paytm"
                                        aria-selected="false">{{ __('Paytm') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payment_setting['is_mercado_enabled']) && $admin_payment_setting['is_mercado_enabled'] == 'on')
                                    <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                        href="#mercadopago-payment" role="tab" aria-controls="mercadopago"
                                        aria-selected="false">{{ __('Mercado Pago') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payment_setting['is_mollie_enabled']) && $admin_payment_setting['is_mollie_enabled'] == 'on')
                                    <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                        href="#mollie-payment" role="tab" aria-controls="mollie"
                                        aria-selected="false">{{ __('Mollie') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payment_setting['is_skrill_enabled']) && $admin_payment_setting['is_skrill_enabled'] == 'on')
                                    <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                        href="#skrill-payment" role="tab" aria-controls="skrill"
                                        aria-selected="false">{{ __('Skrill') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payment_setting['is_coingate_enabled']) && $admin_payment_setting['is_coingate_enabled'] == 'on')
                                    <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                        href="#coingate-payment" role="tab" aria-controls="coingate"
                                        aria-selected="false">{{ __('Coingate') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payment_setting['is_paymentwall_enabled']) && $admin_payment_setting['is_paymentwall_enabled'] == 'on')
                                    <a class="list-group-item list-group-item-action border-0" data-toggle="tab"
                                        href="#paymentwall-payment" role="tab" aria-controls="paymentwall"
                                        aria-selected="true">{{ __('Paymentwall') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payment_setting['is_toyyibpay_enabled']) && $admin_payment_setting['is_toyyibpay_enabled'] == 'on')
                                    <a href="#toyyibpay_payment" class="list-group-item list-group-item-action border-0"
                                        data-toggle="tab" role="tab" aria-controls="toyyibpay"
                                        aria-selected="true">{{ __('Toyyibpay') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payment_setting['is_payfast_enabled']) && $admin_payment_setting['is_payfast_enabled'] == 'on')
                                    <a href="#payfast_payment" class="list-group-item list-group-item-action border-0"
                                        data-toggle="tab" role="tab" aria-controls="payfast" aria-selected="true">
                                        {{ __('Payfast') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                                    </a>
                                @endif
                                @if (isset($admin_payment_setting['is_iyzipay_enabled']) && $admin_payment_setting['is_iyzipay_enabled'] == 'on')
                                    <a href="#useradd-16" class="list-group-item list-group-item-action border-0">
                                        {{ __('IyziPay') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payment_setting['is_sspay_enabled']) && $admin_payment_setting['is_sspay_enabled'] == 'on')
                                    <a href="#useradd-17" class="list-group-item list-group-item-action border-0">
                                        {{ __('SSPay') }}
                                        <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payment_setting['is_paytab_enabled']) && $admin_payment_setting['is_paytab_enabled'] == 'on')
                                    <a href="#useradd-18"
                                        class="list-group-item list-group-item-action border-0">{{ __('PayTab') }} <div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payment_setting['is_benefit_enabled']) && $admin_payment_setting['is_benefit_enabled'] == 'on')
                                    <a href="#useradd-19"
                                        class="list-group-item list-group-item-action border-0">{{ __('Benefit') }} <div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payment_setting['is_cashfree_enabled']) && $admin_payment_setting['is_cashfree_enabled'] == 'on')
                                    <a href="#useradd-20"
                                        class="list-group-item list-group-item-action border-0">{{ __('Cashfree') }} <div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payment_setting['is_aamarpay_enabled']) && $admin_payment_setting['is_aamarpay_enabled'] == 'on')
                                    <a href="#useradd-21"
                                        class="list-group-item list-group-item-action border-0">{{ __('Aamarpay') }} <div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payment_setting['is_paytr_enabled']) && $admin_payment_setting['is_paytr_enabled'] == 'on')
                                    <a href="#useradd-22"
                                        class="list-group-item list-group-item-action border-0">{{ __('Pay TR') }} <div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payment_setting['is_yookassa_enabled']) && $admin_payment_setting['is_yookassa_enabled'] == 'on')
                                    <a href="#useradd-23"
                                        class="list-group-item list-group-item-action border-0">{{ __('Yookassa') }} <div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payment_setting['is_midtrans_enabled']) && $admin_payment_setting['is_midtrans_enabled'] == 'on')
                                    <a href="#useradd-24"
                                        class="list-group-item list-group-item-action border-0">{{ __('Midtrans') }} <div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payment_setting['is_xendit_enabled']) && $admin_payment_setting['is_xendit_enabled'] == 'on')
                                    <a href="#useradd-25"
                                        class="list-group-item list-group-item-action border-0">{{ __('Xendit') }} <div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                                @if (isset($admin_payment_setting['is_payhere_enabled']) && $admin_payment_setting['is_payhere_enabled'] == 'on')
                                    <a href="#useradd-26"
                                        class="list-group-item list-group-item-action border-0">{{ __('PayHere') }} <div
                                            class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                                @endif
                            </div>

                        <div class="card shadow-none rounded-0 border price-card price-1 wow animate__fadeInUp" data-wow-delay="0.2s"
                            style="visibility: visible; animation-delay: 0.2s; animation-name: fadeInUp;">
                            <div class="card-body">
                                <span class="price-badge bg-primary">{{ $plan->name }}</span>

                                <span
                                    class="mb-4 f-w-600 p-price">{{ number_format($plan->price) }}<small
                                        class="text-sm">/ {{ $plan->duration }}</small></span>

                                <p class="mb-0">
                                    {{ $plan->description }}
                                </p>

                                <ul class="list-unstyled my-4">
                                    <li>
                                        <span class="theme-avtar">
                                            <i class="text-primary ti ti-circle-plus"></i></span>
                                        {{ $plan->max_users < 0 ? __('Unlimited') : $plan->max_users }}
                                        {{ __('Users') }}
                                    </li>
                                    <li>
                                        <span class="theme-avtar">
                                            <i class="text-primary ti ti-circle-plus"></i></span>
                                        {{ $plan->max_employee < 0 ? __('Unlimited') : $plan->max_employee }}
                                        {{ __('Employee') }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    @if ($admin_payment_setting['is_manually_enabled'] == 'on')
                    <div id="useradd-14" class="card shadow-none rounded-0 border-bottom">
                        <div class="card-header">
                            <h5 class=" h6 mb-0">{{ __('Manually') }}</h5>
                        </div>
                        <div class="card-body">
                            <label>{{__('Requesting manual payment for the planned amount for the subscriptions plan.')}}</label>
                        </div>
                        <div class="card-footer text-end">


                            @if (empty($planReqs))
                                <a href="{{ route('send.request', [\Illuminate\Support\Facades\Crypt::encrypt($plan->id)]) }}"
                                    class="btn btn-primary btn-icon m-1" data-title="{{ __('Send Request') }}" data-bs-toggle="tooltip">
                                    <span class="btn-inner--icon">{{__('Send Request')}}</span>
                                </a>
                            @else
                                <a href="{{ route('response.request', [$planReqs->id,0]) }}"
                                    class="btn btn-danger btn-icon m-1" data-title="{{ __('Cancel Request') }}" data-bs-toggle="tooltip">
                                    <span class="btn-inner--icon">{{__('Cancel Request')}}</span>
                                </a>
                            @endif
                        </div>
                    </div>
                    @endif
                    @if ($admin_payment_setting['is_bank_enabled'] == 'on')
                        <div id="bank-payment" class="card shadow-none rounded-0 border-bottom">
                            <form class="w3-container w3-display-middle w3-card-4" method="POST" action="{{ route('plan.pay.with.bank') }}"
                                enctype='multipart/form-data'>
                                @csrf
                                <div class="card-header">
                                    <h5 class=" h6 mb-0">{{ __('Bank Transfer') }}</h5>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form-label"><b>{{ __('Bank Details:') }}</b></label>
                                                <div class="form-group">

                                                    @if(isset($admin_payment_setting['bank_details']) &&
                                                    !empty($admin_payment_setting['bank_details']))
                                                    {!! $admin_payment_setting['bank_details'] !!}
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label"> {{ __('Payment Receipt') }}</label>
                                                <div class="form-group">
                                                    <input type="file" name="payment_receipt" class="form-control mb-3" required>
                                                </div>
                                            </div>
                                        </div>
                                        <form>
                                            <div class="row mt-3">
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <label for="bank_coupon" class="form-label">{{ __('Coupon') }}</label>
                                                        <input type="text" id="bank_coupon" name="coupon" class="form-control coupon"
                                                            placeholder="{{ __('Enter Coupon Code') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-2 coupon-apply-btn mt-4">
                                                    <div class="form-group apply-bank-btn-coupon">
                                                        <a href="#" class="btn btn-primary align-items-center apply-coupon" data-from="bank">{{ __('Apply') }}</a>
                                                    </div>
                                                </div>
                                                <div class="col-6 text-right">
                                                    <b>{{ __('Plan Price') }}</b> : ${{$plan->price}}<b class="bank-coupon-price"></b>
                                                </div>
                                                <div class="col-6 text-right ">
                                                    <b>{{ __('Net Amount') }}</b> : $<span class="bank-final-price"> {{$plan->price}}
                                                    </span></b>
                                                    <small>(After coupon apply)</small>
                                                </div>

                                                <div class="row mt-2">
                                                    <div class="col-sm-12">
                                                        <div class="float-end">
                                                            <input type="hidden" name="plan_id"
                                                                value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                            <button class="btn btn-primary d-flex align-items-center" type="submit">
                                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                                (<span class="bank-final-price">{{ $settings['site_currency_symbol'] ?
                                                                    $settings['site_currency_symbol'] : '$' }}{{ $plan->price }}</span>)
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="error" style="display: none;">
                                                        <div class='alert-danger alert'>
                                                            {{ __('Please correct the errors and try again.') }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @endif
                    @if (isset($admin_payment_setting['is_stripe_enabled']) && $admin_payment_setting['is_stripe_enabled'] == 'on')
                        <div id="stripe-payment" class="card  shadow-none rounded-0 border-bottom">
                            <div class="card-header">
                                <h5>{{ __('Pay Using Stripe') }}</h5>
                                <small class="text-muted">{{ __('Details about your plan Stripe payment') }}</small>
                            </div>
                            <div class="card-body">
                                <form role="form" action="{{ route('stripe.post') }}" method="post" class="require-validation" id="payment-form">
                                    @csrf
                                    <div class=" rounded stripe-payment-div">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label class="form-label" for="card-name-on"
                                                    class="form-label">{{ __('Name on card') }}</label>
                                                    <input type="text" name="name" id="card-name-on"
                                                    class="form-control required"
                                                    placeholder="{{ \Auth::user()->name }}">
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <div id="card-element"></div>
                                                <div id="card-errors" role="alert"></div>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="stripe_coupon"
                                                        class="form-label text-dark">{{ __('Coupon') }}</label>
                                                    <input type="text" id="stripe_coupon" name="coupon"
                                                        class="form-control coupon" data-from="stripe"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn mt-4">
                                                <div class="form-group apply-stripe-btn-coupon">
                                                    <a href="#" class="btn btn-primary align-items-center apply-coupon"
                                                        data-from="stripe">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right stripe-coupon-tr" style="display: none">
                                                <b>{{ __('Coupon Discount') }}</b> : <b class="stripe-coupon-price"></b>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-sm-12">
                                                    <div class="float-end">
                                                        <input type="hidden" name="plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">

                                                        <button class="btn btn-primary d-flex align-items-center"
                                                            type="submit">
                                                            <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                            (<span
                                                                class="stripe-final-price">{{$admin_payment_setting['currency_symbol']}}{{ $plan->price }}</span>)
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if (isset($admin_payment_setting['is_paypal_enabled']) && $admin_payment_setting['is_paypal_enabled'] == 'on')
                        <div class="card  shadow-none rounded-0 border-bottom" id="paypal-payment">
                            <form role="form" action="{{ route('plan.pay.with.paypal') }}" method="post"
                                id="paypal-payment-form" class="w3-container w3-display-middle w3-card-4">
                                @csrf
                                <div class="card-header">
                                    <h5>{{ __('Pay Using Paypal') }}</h5>
                                    <small class="text-muted">{{ __('Details about your plan Paypal payment') }}</small>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row mt-3">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="paypal_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="paypal_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn">
                                                <div class="form-group apply-paypal-btn-coupon mt-4">
                                                    <a href="#" class="btn btn-primary align-items-center apply-coupon"
                                                        data-from="paypal">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right paypal-coupon-tr" style="display: none">
                                                <b>{{ __('Coupon Discount') }}</b> : <b class="paypal-coupon-price"></b>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-sm-12">
                                                    <div class="float-end">
                                                        <input type="hidden" name="plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                        <button class="btn btn-primary d-flex align-items-center"
                                                            type="submit">
                                                            <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                            (<span
                                                                class="paypal-final-price">{{$admin_payment_setting['currency_symbol']}}{{ $plan->price }}</span>)
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @endif

                    @if (isset($admin_payment_setting['is_paystack_enabled']) && $admin_payment_setting['is_paystack_enabled'] == 'on')
                        <div id="paystack-payment" class="card  shadow-none rounded-0 border-bottom ">
                            <form role="form" action="{{ route('plan.pay.with.paystack') }}" method="post"
                                id="paystack-payment-form" class="w3-container w3-display-middle w3-card-4">
                                @csrf
                                <div class="card-header">
                                    <h5>{{ __('Paystack') }}</h5>
                                    <small class="text-muted">{{ __('Details about your plan Paystack payment') }}</small>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row mt-3">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="paystack_coupon"
                                                        class="form-label text-dark">{{ __('Coupon') }}</label>
                                                    <input type="text" id="paystack_coupon" name="coupon"
                                                        class="form-control coupon" data-from="paystack"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group pt-3 mt-3">
                                                    <a href="#" class="btn btn-primary align-items-center apply-coupon"
                                                        data-from="paystack">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right paystack-coupon-tr" style="display: none">
                                                <b>{{ __('Coupon Discount') }}</b> : <b class="paystack-coupon-price"></b>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-sm-12">
                                                    <div class="float-end">
                                                        <input type="hidden" name="plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                        <button class="btn btn-primary d-flex align-items-center"
                                                            type="button" id="pay_with_paystack">
                                                            <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                            (<span
                                                                class="paystack-final-price">{{$admin_payment_setting['currency_symbol']}}{{ $plan->price }}</span>)
                                                        </button>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @endif

                    @if (isset($admin_payment_setting['is_flutterwave_enabled']) && $admin_payment_setting['is_flutterwave_enabled'] == 'on')
                        <div id="flutterwave-payment" class="card  shadow-none rounded-0 border-bottom ">
                            <form role="form" action="{{ route('plan.pay.with.flaterwave') }}" method="post"
                                class="w3-container w3-display-middle w3-card-4" id="flaterwave-payment-form">
                                @csrf <div class="card-header">
                                    <h5>{{ __('Flutterwave') }}</h5>
                                    <small
                                        class="text-muted">{{ __('Details about your plan Flutterwave payment') }}</small>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row mt-3">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="flaterwave_coupon"
                                                        class="form-label text-dark">{{ __('Coupon') }}</label>
                                                    <input type="text" id="flaterwave_coupon" name="coupon"
                                                        class="form-control coupon" data-from="flaterwave"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn">
                                                <div class="form-group pt-3 mt-3">
                                                    <a href="#" class="btn btn-primary align-items-center apply-coupon"
                                                        data-from="flaterwave">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right flaterwave-coupon-tr" style="display: none">
                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                    class="flaterwave-coupon-price"></b>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-sm-12">
                                                    <div class="float-end">
                                                        <input type="hidden" name="plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                        <button class="btn btn-primary d-flex align-items-center"
                                                            type="button" id="pay_with_flaterwave">
                                                            <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                            (<span
                                                                class="flaterwave-final-price">{{$admin_payment_setting['currency_symbol']}}{{ $plan->price }}</span>)
                                                        </button>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @endif

                    @if (isset($admin_payment_setting['is_razorpay_enabled']) && $admin_payment_setting['is_razorpay_enabled'] == 'on')
                        <div id="razorpay-payment" class="card  shadow-none rounded-0 border-bottom ">
                            <form role="form" action="{{ route('plan.pay.with.razorpay') }}" method="post"
                                class="w3-container w3-display-middle w3-card-4" id="razorpay-payment-form">
                                @csrf
                                <div class="card-header">
                                    <h5>{{ __('Razorpay') }}</h5>
                                    <small class="text-muted">{{ __('Details about your plan Razorpay payment') }}</small>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row mt-3">
                                            <div class="col-10">
                                                <div class="form-group">
                                                    <label for="razorpay_coupon"
                                                        class="form-label text-dark">{{ __('Coupon') }}</label>
                                                    <input type="text" id="razorpay_coupon" name="coupon"
                                                        class="form-control coupon" data-from="razorpay"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn">
                                                <div class="form-group pt-3 mt-3">
                                                    <a href="#" class="btn btn-primary  align-items-center apply-coupon"
                                                        data-from="razorpay">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right razorpay-coupon-tr" style="display: none">
                                                <b>{{ __('Coupon Discount') }}</b> : <b class="razorpay-coupon-price"></b>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-sm-12">
                                                    <div class="float-end">
                                                        <input type="hidden" name="plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                        <button class="btn btn-primary d-flex align-items-center"
                                                            type="button" id="pay_with_razorpay">
                                                            <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                            (<span
                                                                class="razorpay-final-price">{{$admin_payment_setting['currency_symbol']}}{{ $plan->price }}</span>)
                                                        </button>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @endif

                    @if (isset($admin_payment_setting['is_paytm_enabled']) && $admin_payment_setting['is_paytm_enabled'] == 'on')
                        <div id="paytm-payment" class="card  shadow-none rounded-0 border-bottom ">
                            <form role="form" action="{{ route('plan.pay.with.paytm') }}" method="post"
                                class="w3-container w3-display-middle w3-card-4" id="paytm-payment-form">
                                @csrf
                                <div class="card-header">
                                    <h5>{{ __('Paytm') }}</h5>
                                    <small class="text-muted">{{ __('Details about your plan Paytm payment') }}</small>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row mt-3">
                                            <div class="col-10">
                                                <div class="form-group">
                                                    <label for="paytm_coupon"
                                                        class="form-label text-dark">{{ __('Mobile Number') }}</label>
                                                    <input type="text" id="mobile" name="mobile" class="form-control mobile"
                                                        data-from="mobile" placeholder="{{ __('Enter Mobile Number') }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-10">
                                                <div class="form-group">
                                                    <label for="paytm_coupon"
                                                        class="form-label text-dark">{{ __('Coupon') }}</label>
                                                    <input type="text" id="paytm_coupon" name="coupon"
                                                        class="form-control coupon" data-from="paytm"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group pt-3 mt-3">
                                                    <a href="#" class="btn btn-primary align-items-center apply-coupon"
                                                        data-from="paytm">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right paytm-coupon-tr" style="display: none">
                                                <b>{{ __('Coupon Discount') }}</b> : <b class="paytm-coupon-price"></b>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-sm-12">
                                                    <div class="float-end">
                                                        <input type="hidden" name="plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                        <button class="btn btn-primary d-flex align-items-center"
                                                            type="submit" id="pay_with_paytm">
                                                            <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                            (<span
                                                                class="paytm-final-price">{{$admin_payment_setting['currency_symbol']}}{{ $plan->price }}</span>)
                                                        </button>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @endif

                    @if (isset($admin_payment_setting['is_mercado_enabled']) && $admin_payment_setting['is_mercado_enabled'] == 'on')
                        <div id="mercadopago-payment" class="card  shadow-none rounded-0 border-bottom ">
                            <form role="form" action="{{ route('plan.pay.with.mercado') }}" method="post"
                                class="w3-container w3-display-middle w3-card-4" id="mercado-payment-form">
                                @csrf
                                <div class="card-header">
                                    <h5>{{ __('Mercado Pago') }}</h5>
                                    <small
                                        class="text-muted">{{ __('Details about your plan Mercado Pago payment') }}</small>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row mt-3">
                                            <div class="col-10">
                                                <div class="form-group">
                                                    <label for="mercado_coupon"
                                                        class="form-label text-dark">{{ __('Coupon') }}</label>
                                                    <input type="text" id="mercado_coupon" name="coupon"
                                                        class="form-control coupon" data-from="mercado"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group pt-3 mt-3">
                                                    <a href="#" class="btn btn-primary align-items-center apply-coupon"
                                                        data-from="mercado">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right mercado-coupon-tr" style="display: none">
                                                <b>{{ __('Coupon Discount') }}</b> : <b class="mercado-coupon-price"></b>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-sm-12">
                                                    <div class="float-end">
                                                        <input type="hidden" name="plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                        <button class="btn btn-primary d-flex align-items-center"
                                                            type="submit" id="pay_with_paytm">
                                                            <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                            (<span
                                                                class="mercado-final-price">{{$admin_payment_setting['currency_symbol']}}{{ $plan->price }}</span>)
                                                        </button>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @endif

                    @if (isset($admin_payment_setting['is_mollie_enabled']) && $admin_payment_setting['is_mollie_enabled'] == 'on')
                        <div id="mollie-payment" class="card  shadow-none rounded-0 border-bottom ">
                            <form role="form" action="{{ route('plan.pay.with.mollie') }}" method="post"
                                class="w3-container w3-display-middle w3-card-4" id="mollie-payment-form">
                                @csrf
                                <div class="card-header">
                                    <h5>{{ __('Mollie') }}</h5>
                                    <small class="text-muted">{{ __('Details about your plan Mollie payment') }}</small>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row mt-3">
                                            <div class="col-10">
                                                <div class="form-group">
                                                    <label for="mollie_coupon"
                                                        class="form-label text-dark">{{ __('Coupon') }}</label>
                                                    <input type="text" id="mollie_coupon" name="coupon"
                                                        class="form-control coupon" data-from="mollie"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group pt-3 mt-3">
                                                    <a href="#" class="btn btn-primary align-items-center apply-coupon"
                                                        data-from="mollie">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right mollie-coupon-tr" style="display: none">
                                                <b>{{ __('Coupon Discount') }}</b> : <b class="mollie-coupon-price"></b>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-sm-12">
                                                    <div class="float-end">
                                                        <input type="hidden" name="plan_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($plan->id)}}">
                                            <button class="btn btn-primary d-flex align-items-center" type="submit" id="pay_with_mollie">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{__('Pay Now')}} (<span class="mollie-final-price">{{$admin_payment_setting['currency_symbol']}}{{$plan->price }}</span>)
                                            </button>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @endif

                    @if (isset($admin_payment_setting['is_skrill_enabled']) && $admin_payment_setting['is_skrill_enabled'] == 'on')
                        <div id="skrill-payment" class="card  shadow-none rounded-0 border-bottom ">
                            <form role="form" action="{{ route('plan.pay.with.skrill') }}" method="post"
                                class="w3-container w3-display-middle w3-card-4" id="skrill-payment-form">
                                @csrf
                                <div class="card-header">
                                    <h5>{{ __('Skrill') }}</h5>
                                    <small class="text-muted">{{ __('Details about your plan Skrill payment') }}</small>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row mt-3">
                                            <div class="col-10">
                                                <div class="form-group">
                                                    <label for="skrill_coupon"
                                                        class="form-label text-dark">{{ __('Coupon') }}</label>
                                                    <input type="text" id="skrill_coupon" name="coupon"
                                                        class="form-control coupon" data-from="skrill"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group pt-3 mt-3">
                                                    <a href="#" class="btn btn-primary align-items-center apply-coupon"
                                                        data-from="skrill">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right skrill-coupon-tr" style="display: none">
                                                <b>{{ __('Coupon Discount') }}</b> : <b class="skrill-coupon-price"></b>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-sm-12">
                                                    <div class="float-end">
                                                        <input type="hidden" name="plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                        <button class="btn btn-primary d-flex align-items-center"
                                                            type="submit" id="pay_with_skrill">
                                                            <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                            (<span
                                                                class="skrill-final-price">{{$admin_payment_setting['currency_symbol']}}{{ $plan->price }}</span>)
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @php
                                            $skrill_data = [
                                                'transaction_id' => md5(date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id'),
                                                'user_id' => 'user_id',
                                                'amount' => 'amount',
                                                'currency' => 'currency',
                                            ];
                                            session()->put('skrill_data', $skrill_data);
                                        @endphp
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @endif

                    @if (isset($admin_payment_setting['is_coingate_enabled']) && $admin_payment_setting['is_coingate_enabled'] == 'on')
                        <div id="coingate-payment" class="card  shadow-none rounded-0 border-bottom ">
                            <form role="form" action="{{ route('plan.pay.with.coingate') }}" method="post"
                                class="w3-container w3-display-middle w3-card-4" id="coingate-payment-form">
                                @csrf
                                <div class="card-header">
                                    <h5>{{ __('Coingate') }}</h5>
                                    <small class="text-muted">{{ __('Details about your plan Coingate payment') }}</small>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row mt-3">
                                            <div class="col-10">
                                                <div class="form-group">
                                                    <label for="coingate_coupon"
                                                        class="form-label text-dark">{{ __('Coupon') }}</label>
                                                    <input type="text" id="coingate_coupon" name="coupon"
                                                        class="form-control coupon" data-from="coingate"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group pt-3 mt-3">
                                                    <a href="#" class="btn btn-primary align-items-center apply-coupon"
                                                        data-from="coingate">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right coingate-coupon-tr" style="display: none">
                                                <b>{{ __('Coupon Discount') }}</b> : <b class="coingate-coupon-price"></b>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-sm-12">
                                                    <div class="float-end">
                                                        <input type="hidden" name="plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                        <button class="btn btn-primary d-flex align-items-center"
                                                            type="submit" id="pay_with_coingate">
                                                            <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                            (<span
                                                                class="coingate-final-price">{{$admin_payment_setting['currency_symbol']}}{{ $plan->price }}</span>)
                                                        </button>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @endif

                    @if (isset($admin_payment_setting['is_paymentwall_enabled']) && $admin_payment_setting['is_paymentwall_enabled'] == 'on')
                        <div id="paymentwall-payment" class="card shadow-none rounded-0 border-bottom">
                            <form role="form" action="{{ route('paymentwall') }}" method="post" id="paymentwall-payment-form" class="w3-container w3-display-middle w3-card-4">
                                @csrf
                                <div class="card-header">
                                    <h5>{{ __('PaymentWall') }}</h5>
                                    <small
                                        class="text-muted">{{ __('Details about your plan PaymentWall payment') }}</small>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row mt-3">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="paymentwall_coupon"
                                                        class="form-label text-dark">{{ __('Coupon') }}</label>
                                                    <input type="text" id="paymentwall_coupon" name="coupon"
                                                        class="form-control coupon" data-from="paymentwall"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group pt-3 mt-3">
                                                    <a href="#" class="btn btn-primary align-items-center apply-coupon"
                                                        data-from="paymentwall">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right paymentwall-coupon-tr" style="display: none">
                                                <b>{{ __('Coupon Discount') }}</b> : <b
                                                    class="paymentwall-coupon-price"></b>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-sm-12">
                                                    <div class="float-end">
                                                        <input type="hidden" name="plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                        <button class="btn btn-primary d-flex align-items-center" type="submit" id="pay_with_paymentwall">
                                                            <i class="mdi mdi-cash-multiple mr-1"></i> {{ __('Pay Now') }}
                                                            (<span
                                                                class="paymentwall-final-price">{{$admin_payment_setting['currency_symbol']}}{{ $plan->price }}</span>)
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @endif

                    @if (isset($admin_payment_setting['is_toyyibpay_enabled']) && $admin_payment_setting['is_toyyibpay_enabled'] == 'on')
                        <div id="toyyibpay_payment" class="card shadow-none rounded-0 border-bottom">
                            <form role="form" action="{{ route('plan.pay.with.toyyibpay') }}" method="post" id="toyyibpay-payment-form"
                                class="w3-container w3-display-middle w3-card-4">
                                @csrf
                                <div class="card-header">
                                    <h5>{{ __('Toyyibpay') }}</h5>
                                    <small class="text-muted">{{ __('Details about your plan Toyyibpay payment') }}</small>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row mt-3">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="toyyibpay_coupon" class="form-label text-dark">{{ __('Coupon') }}</label>
                                                    <input type="text" id="toyyibpay_coupon" name="coupon" class="form-control coupon"
                                                        data-from="toyyibpay" placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn">
                                                <div class="form-group pt-3 mt-3 apply-toyyibpay-btn-coupon">
                                                    <a href="#" class="btn btn-primary align-items-center apply-coupon" data-from="toyyibpay">{{ __('Apply')
                                                        }}</a>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right toyyibpay-coupon-tr" style="display: none">
                                                <b>{{ __('Coupon Discount') }}</b> : <b class="toyyibpay-coupon-price"></b>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-sm-12">
                                                    <div class="float-end">
                                                        <input type="hidden" name="plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                        <button class="btn btn-primary d-flex align-items-center" type="submit"
                                                            id="pay_with_toyyibpay">
                                                            <i class="mdi mdi-cash-multiple mr-1"></i>
                                                            {{ __('Pay Now') }} (<span class="toyyibpay-final-price">{{ $settings['site_currency_symbol'] ?
                                                                $settings['site_currency_symbol'] : '$' }}{{ $plan->price }}</span>)</button>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @endif

                    @if (isset($admin_payment_setting['is_payfast_enabled']) && $admin_payment_setting['is_payfast_enabled'] == 'on')
                        <div id="payfast_payment" class="card shadow-none rounded-0 border-bottom">
                            <div class="card-header">
                                <h5>{{ __('Payfast') }}</h5>
                            </div>

                                @if (
                                $admin_payment_setting['is_payfast_enabled'] == 'on' &&
                                !empty($admin_payment_setting['payfast_merchant_id']) &&
                                !empty($admin_payment_setting['payfast_merchant_key']) &&
                                !empty($admin_payment_setting['payfast_signature']) &&
                                !empty($admin_payment_setting['payfast_mode']))
                                    <div {{ ($admin_payment_setting['is_payfast_enabled']=='on' &&
                                        !empty($admin_payment_setting['payfast_merchant_id']) &&
                                        !empty($admin_payment_setting['payfast_merchant_key']))=='on' ? 'active' : '' }}>
                                        @php
                                        $pfHost = $admin_payment_setting['payfast_mode'] == 'sandbox' ? 'sandbox.payfast.co.za' :
                                        'www.payfast.co.za';
                                        @endphp
                                        <form role="form" action={{ 'https://' . $pfHost . '/eng/process' }} method="post"
                                            class="require-validation" id="payfast-form">
                                            <div class="card-body  ">

                                                <div class="row mt-3">
                                                    <div class="col-md-10">
                                                        <div class="form-group">
                                                            <label for="payfast_coupon" class="form-label text-dark">{{ __('Coupon') }}</label>
                                                            <input type="text" id="payfast_coupon" name="coupon" class="form-control coupon"
                                                                data-from="payfast" placeholder="{{ __('Enter Coupon Code') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2 coupon-apply-btn">
                                                        <div class="form-group pt-3 mt-3 apply-payfast-btn-coupon">
                                                            <a href="#" class="btn btn-primary align-items-center apply-coupon" data-from="payfast">{{ __('Apply')
                                                                }}</a>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 text-right payfast-coupon-tr" style="display: none">
                                                        <b>{{ __('Coupon Discount') }}</b> : <b class="payfast-coupon-price"></b>
                                                    </div>

                                                    <div id="get-payfast-inputs"></div>
                                                    <div class="row mt-2">
                                                        <div class="col-sm-12">
                                                            <div class="float-end">
                                                                <input type="hidden" name="plan_id" id="plan_id"
                                                                    value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                                <button class="btn btn-primary d-flex align-items-center" type="submit"
                                                                    id="pay_with_payfast">
                                                                    <i class="mdi mdi-cash-multiple mr-1"></i>
                                                                    {{ __('Pay Now') }} (<span class="payfast-final-price">{{ $settings['site_currency_symbol']
                                                                        ? $settings['site_currency_symbol'] : '$' }}{{ $plan->price }}</span>)</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                        </div>
                    @endif

                    @if (isset($admin_payment_setting['is_iyzipay_enabled']) && $admin_payment_setting['is_iyzipay_enabled'] == 'on')
                        <div id="useradd-16" class="card shadow-none rounded-0 border-bottom">
                            <form class="w3-container w3-display-middle w3-card-4" method="POST"
                                id="iyzipay-payment-form" action="{{ route('iyzipay.payment.init') }}">
                                @csrf <div class="card-header">
                                    <h5>{{ __('IyziPay') }}</h5>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row mt-3">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="iyzipay_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="iyzipay_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn">
                                                <div class="form-group pt-3 mt-3  apply-iyzipay-btn-coupon">
                                                    <a href="#"
                                                        class="btn btn-primary align-items-center apply-coupon"
                                                        data-from="iyzipay">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right iyzipay-coupon-tr" style="display: none">
                                                <b>{{ __('Coupon Discount') }}</b> : <b class="iyzipay-coupon-price"></b>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-sm-12">
                                                    <div class="float-end">
                                                        <input type="hidden" name="plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                        <button class="btn btn-primary d-flex align-items-center"
                                                            type="submit">
                                                            <i class="mdi mdi-cash-multiple mr-1"></i>
                                                            {{ __('Pay Now') }}
                                                            (<span
                                                                class="iyzipay-final-price">{{ $settings['site_currency_symbol'] ? $settings['site_currency_symbol'] : '$' }}{{ $plan->price }}</span>)
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @endif

                    @if (isset($admin_payment_setting['is_sspay_enabled']) && $admin_payment_setting['is_sspay_enabled'] == 'on')
                        <div id="useradd-17" class="card shadow-none rounded-0 border-bottom">
                            <form class="w3-container w3-display-middle w3-card-4" method="POST" id="sspay-payment-form"
                                action="{{ route('plan.sspaypayment') }}">
                                @csrf <div class="card-header">
                                    <h5>{{ __('SSPay') }}</h5>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row mt-3">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="sspay_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="sspay_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn">
                                                <div class="form-group pt-3 mt-3 apply-sspay-btn-coupon">
                                                    <a href="#"
                                                        class="btn btn-primary align-items-center apply-coupon"
                                                        data-from="sspay">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right sspay-coupon-tr" style="display: none">
                                                <b>{{ __('Coupon Discount') }}</b> : <b class="iyzipay-coupon-price"></b>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-sm-12">
                                                    <div class="float-end">
                                                        <input type="hidden" name="plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                        <button class="btn btn-primary d-flex align-items-center"
                                                            type="submit">
                                                            <i class="mdi mdi-cash-multiple mr-1"></i>
                                                            {{ __('Pay Now') }}
                                                            (<span
                                                                class="sspay-final-price">{{ $settings['site_currency_symbol'] ? $settings['site_currency_symbol'] : '$' }}{{ $plan->price }}</span>)
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @endif

                    @if (isset($admin_payment_setting['is_paytab_enabled']) && $admin_payment_setting['is_paytab_enabled'] == 'on')
                        <div id="useradd-18" class="card shadow-none rounded-0 border-bottom">
                            <form class="w3-container w3-display-middle w3-card-4" method="POST"
                                id="paytab-payment-form" action="{{ route('plan.pay.with.paytab') }}">
                                @csrf <div class="card-header">
                                    <h5>{{ __('PayTab') }}</h5>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row mt-3">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="sspay_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="sspay_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn">
                                                <div class="form-group pt-3 mt-3 apply-sspay-btn-coupon">
                                                    <a href="#"
                                                        class="btn btn-primary align-items-center apply-coupon"
                                                        data-from="sspay">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right sspay-coupon-tr" style="display: none">
                                                <b>{{ __('Coupon Discount') }}</b> : <b class="iyzipay-coupon-price"></b>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-sm-12">
                                                    <div class="float-end">
                                                        <input type="hidden" name="plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                        <button class="btn btn-primary d-flex align-items-center"
                                                            type="submit">
                                                            <i class="mdi mdi-cash-multiple mr-1"></i>
                                                            {{ __('Pay Now') }}
                                                            (<span
                                                                class="sspay-final-price">{{ $settings['site_currency_symbol'] ? $settings['site_currency_symbol'] : '$' }}{{ $plan->price }}</span>)
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @endif

                    @if (isset($admin_payment_setting['is_benefit_enabled']) && $admin_payment_setting['is_benefit_enabled'] == 'on')
                        <div id="useradd-19" class="card shadow-none rounded-0 border-bottom">
                            <form class="w3-container w3-display-middle w3-card-4" method="POST"
                                id="benefit-payment-form" action="{{ route('benefit.initiate') }}">
                                @csrf <div class="card-header">
                                    <h5>{{ __('Benefit') }}</h5>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row mt-3">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="sspay_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="sspay_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn">
                                                <div class="form-group pt-3 mt-3 apply-sspay-btn-coupon">
                                                    <a href="#"
                                                        class="btn btn-primary align-items-center apply-coupon"
                                                        data-from="sspay">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right sspay-coupon-tr" style="display: none">
                                                <b>{{ __('Coupon Discount') }}</b> : <b class="iyzipay-coupon-price"></b>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-sm-12">
                                                    <div class="float-end">
                                                        <input type="hidden" name="plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                        <button class="btn btn-primary d-flex align-items-center"
                                                            type="submit">
                                                            <i class="mdi mdi-cash-multiple mr-1"></i>
                                                            {{ __('Pay Now') }}
                                                            (<span
                                                                class="sspay-final-price">{{ $settings['site_currency_symbol'] ? $settings['site_currency_symbol'] : '$' }}{{ $plan->price }}</span>)
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @endif

                    @if (isset($admin_payment_setting['is_cashfree_enabled']) && $admin_payment_setting['is_cashfree_enabled'] == 'on')
                        <div id="useradd-20" class="card shadow-none rounded-0 border-bottom">
                            <form class="w3-container w3-display-middle w3-card-4" method="POST"
                                id="cashfree-payment-form" action="{{ route('plan.pay.with.cashfree') }}">
                                @csrf <div class="card-header">
                                    <h5>{{ __('Cashfree') }}</h5>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row mt-3">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="sspay_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="sspay_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn">
                                                <div class="form-group pt-3 mt-3 apply-sspay-btn-coupon">
                                                    <a href="#"
                                                        class="btn btn-primary align-items-center apply-coupon"
                                                        data-from="sspay">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right sspay-coupon-tr" style="display: none">
                                                <b>{{ __('Coupon Discount') }}</b> : <b class="iyzipay-coupon-price"></b>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-sm-12">
                                                    <div class="float-end">
                                                        <input type="hidden" name="plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                        <button class="btn btn-primary d-flex align-items-center"
                                                            type="submit">
                                                            <i class="mdi mdi-cash-multiple mr-1"></i>
                                                            {{ __('Pay Now') }}
                                                            (<span
                                                                class="sspay-final-price">{{ $settings['site_currency_symbol'] ? $settings['site_currency_symbol'] : '$' }}{{ $plan->price }}</span>)
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @endif

                    @if (isset($admin_payment_setting['is_aamarpay_enabled']) && $admin_payment_setting['is_aamarpay_enabled'] == 'on')
                    <div id="useradd-21" class="card shadow-none rounded-0 border-bottom">
                        <form class="w3-container w3-display-middle w3-card-4" method="POST"
                            id="aamarpay-payment-form" action="{{ route('plan.pay.with.aamarpay') }}">
                            @csrf <div class="card-header">
                                <h5>{{ __('Aamarpay') }}</h5>
                            </div>
                            <div class="card-body">
                                <form>
                                    <div class="row mt-3">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label for="sspay_coupon"
                                                    class="form-label">{{ __('Coupon') }}</label>
                                                <input type="text" id="sspay_coupon" name="coupon"
                                                    class="form-control coupon"
                                                    placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2 coupon-apply-btn">
                                            <div class="form-group pt-3 mt-3 apply-sspay-btn-coupon">
                                                <a href="#"
                                                    class="btn btn-primary align-items-center apply-coupon"
                                                    data-from="sspay">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                        <div class="col-12 text-right sspay-coupon-tr" style="display: none">
                                            <b>{{ __('Coupon Discount') }}</b> : <b class="iyzipay-coupon-price"></b>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-sm-12">
                                                <div class="float-end">
                                                    <input type="hidden" name="plan_id"
                                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                    <button class="btn btn-primary d-flex align-items-center"
                                                        type="submit">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i>
                                                        {{ __('Pay Now') }}
                                                        (<span
                                                            class="sspay-final-price">{{ $settings['site_currency_symbol'] ? $settings['site_currency_symbol'] : '$' }}{{ $plan->price }}</span>)
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="error" style="display: none;">
                                                <div class='alert-danger alert'>
                                                    {{ __('Please correct the errors and try again.') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </form>
                    </div>
                    @endif

                    @if (isset($admin_payment_setting['is_paytr_enabled']) && $admin_payment_setting['is_paytr_enabled'] == 'on')
                        <div id="useradd-22" class="card  shadow-none rounded-0 border-bottom ">
                            <form class="w3-container w3-display-middle w3-card-4" method="POST"
                                id="paytr-payment-form" action="{{ route('plan.pay.with.paytr') }}">
                                @csrf <div class="card-header">
                                    <h5>{{ __('Pay TR') }}</h5>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row mt-3">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="sspay_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="sspay_coupon" name="coupon"
                                                        class="form-control coupon"
                                                        placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn">
                                                <div class="form-group pt-3 mt-3 apply-sspay-btn-coupon">
                                                    <a href="#"
                                                        class="btn btn-primary align-items-center apply-coupon"
                                                        data-from="sspay">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right sspay-coupon-tr" style="display: none">
                                                <b>{{ __('Coupon Discount') }}</b> : <b class="iyzipay-coupon-price"></b>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-sm-12">
                                                    <div class="float-end">
                                                        <input type="hidden" name="plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                        <button class="btn btn-primary d-flex align-items-center"
                                                            type="submit">
                                                            <i class="mdi mdi-cash-multiple mr-1"></i>
                                                            {{ __('Pay Now') }}
                                                            (<span
                                                                class="paytr-final-price">{{ $settings['site_currency_symbol'] ? $settings['site_currency_symbol'] : '$' }}{{ $plan->price }}</span>)
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @endif
                    @if (isset($admin_payment_setting['is_yookassa_enabled']) && $admin_payment_setting['is_yookassa_enabled'] == 'on')
                        <div id="useradd-23" class="card  shadow-none rounded-0 border-bottom ">
                            <form class="w3-container w3-display-middle w3-card-4" method="get"
                                id="paytr-payment-form" action="{{ route('plan.pay.with.yookassa') }}">
                                @csrf <div class="card-header">
                                    <h5>{{ __('Yookassa') }}</h5>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row mt-3">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="sspay_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="sspay_coupon" name="coupon" class="form-control coupon" placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn">
                                                <div class="form-group pt-3 mt-3 apply-sspay-btn-coupon">
                                                    <a href="#" class="btn btn-primary align-items-center apply-coupon" data-from="sspay">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right sspay-coupon-tr" style="display: none">
                                                <b>{{ __('Coupon Discount') }}</b> : <b class="iyzipay-coupon-price"></b>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-sm-12">
                                                    <div class="float-end">
                                                        <input type="hidden" name="plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                        <button class="btn btn-primary d-flex align-items-center"
                                                            type="submit">
                                                            <i class="mdi mdi-cash-multiple mr-1"></i>
                                                            {{ __('Pay Now') }}
                                                            (<span
                                                                class="paytr-final-price">{{ $settings['site_currency_symbol'] ? $settings['site_currency_symbol'] : '$' }}{{ $plan->price }}</span>)
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @endif
                    @if (isset($admin_payment_setting['is_midtrans_enabled']) && $admin_payment_setting['is_midtrans_enabled'] == 'on')
                        <div id="useradd-24" class="card  shadow-none rounded-0 border-bottom ">
                            <form class="w3-container w3-display-middle w3-card-4" method="get"
                                id="midtrans-payment-form" action="{{ route('plan.get.midtrans') }}">
                                @csrf <div class="card-header">
                                    <h5>{{ __('Midtrans') }}</h5>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row mt-3">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="sspay_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="sspay_coupon" name="coupon" class="form-control coupon" placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn">
                                                <div class="form-group pt-3 mt-3 apply-sspay-btn-coupon">
                                                    <a href="#" class="btn btn-primary align-items-center apply-coupon" data-from="sspay">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right sspay-coupon-tr" style="display: none">
                                                <b>{{ __('Coupon Discount') }}</b> : <b class="iyzipay-coupon-price"></b>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-sm-12">
                                                    <div class="float-end">
                                                        <input type="hidden" name="plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                        <button class="btn btn-primary d-flex align-items-center"
                                                            type="submit">
                                                            <i class="mdi mdi-cash-multiple mr-1"></i>
                                                            {{ __('Pay Now') }}
                                                            (<span
                                                                class="paytr-final-price">{{ $settings['site_currency_symbol'] ? $settings['site_currency_symbol'] : '$' }}{{ $plan->price }}</span>)
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @endif
                    @if (isset($admin_payment_setting['is_xendit_enabled']) && $admin_payment_setting['is_xendit_enabled'] == 'on')
                        <div id="useradd-25" class="card  shadow-none rounded-0 border-bottom ">
                            <form class="w3-container w3-display-middle w3-card-4" method="get"
                                id="midtrans-payment-form" action="{{ route('plan.xendit.payment') }}">
                                @csrf <div class="card-header">
                                    <h5>{{ __('Xendit') }}</h5>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row mt-3">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="sspay_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="sspay_coupon" name="coupon" class="form-control coupon" placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn">
                                                <div class="form-group pt-3 mt-3 apply-sspay-btn-coupon">
                                                    <a href="#" class="btn btn-primary align-items-center apply-coupon" data-from="sspay">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right sspay-coupon-tr" style="display: none">
                                                <b>{{ __('Coupon Discount') }}</b> : <b class="iyzipay-coupon-price"></b>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-sm-12">
                                                    <div class="float-end">
                                                        <input type="hidden" name="plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                        <button class="btn btn-primary d-flex align-items-center"
                                                            type="submit">
                                                            <i class="mdi mdi-cash-multiple mr-1"></i>
                                                            {{ __('Pay Now') }}
                                                            (<span
                                                                class="paytr-final-price">{{ $settings['site_currency_symbol'] ? $settings['site_currency_symbol'] : '$' }}{{ $plan->price }}</span>)
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @endif
                    @if (isset($admin_payment_setting['is_payhere_enabled']) && $admin_payment_setting['is_payhere_enabled'] == 'on')
                        <div id="useradd-26" class="card  shadow-none rounded-0 border-bottom ">
                            <form class="w3-container w3-display-middle w3-card-4" method="post" id="midtrans-payment-form" action="{{ route('plan.payhere.payment') }}">
                                @csrf <div class="card-header">
                                    <h5>{{ __('PayHere') }}</h5>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row mt-3">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="sspay_coupon"
                                                        class="form-label">{{ __('Coupon') }}</label>
                                                    <input type="text" id="sspay_coupon" name="coupon" class="form-control coupon" placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn">
                                                <div class="form-group pt-3 mt-3 apply-sspay-btn-coupon">
                                                    <a href="#" class="btn btn-primary align-items-center apply-coupon" data-from="sspay">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                            <div class="col-12 text-right sspay-coupon-tr" style="display: none">
                                                <b>{{ __('Coupon Discount') }}</b> : <b class="iyzipay-coupon-price"></b>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-sm-12">
                                                    <div class="float-end">
                                                        <input type="hidden" name="plan_id"
                                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($plan->id) }}">
                                                        <button class="btn btn-primary d-flex align-items-center"
                                                            type="submit">
                                                            <i class="mdi mdi-cash-multiple mr-1"></i>
                                                            {{ __('Pay Now') }}
                                                            (<span
                                                                class="paytr-final-price">{{ $settings['site_currency_symbol'] ? $settings['site_currency_symbol'] : '$' }}{{ $plan->price }}</span>)
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>
                                                        {{ __('Please correct the errors and try again.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

@endsection
