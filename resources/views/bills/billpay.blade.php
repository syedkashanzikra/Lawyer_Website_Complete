  @php
use App\Models\Utility;
@endphp
@extends('layouts.invoice')

@section('page-title', __('Bills'))

@php
    $settings = App\Models\Utility::settings();
    $logo = asset('storage/uploads/logo/');
    $company_logo = App\Models\Utility::get_company_logo();
@endphp
@section('content')

<div class="row justify-content-between align-items-center mb-3 mt-3">
    <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">

        @if ($bill->status != 'PAID')
            <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center mx-2">
                <a href="#" class="btn btn-xs btn-primary btn-icon-only width-auto" title="{{ __('Pay Now') }}"
                    data-bs-toggle="modal" data-bs-target="#paymentModal"><i class="fas fa-plus"></i>
                    {{ __('Pay Now') }}</a>
            </div>
        @endif
        @if ($bill->status == 'PAID')
            <div class="all-button-box mr-3">
                <a href="javascript:;" class="btn btn-primary" id="download">{{ __('Download') }}</a>
            </div>
        @endif

    </div>
</div>


<div class="row" id="printableArea2">
    <div class="col-md-2 col-md-2"></div>
    <div class="col-sm-12 col-md-8 col-md-8">
        <div class="card border rounded-0 card-body shadow-none ">
            <div class="invoice">
                <div class="invoice-print">
                    <div class="row invoice-title mt-2">
                        <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12">
                            <h2>{{ __('Bill') }}</h2>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12 text-end">
                            <h3 class="invoice-number">{{ $bill->bill_number }}</h3>
                        </div>
                        <div class="col-12">
                            <hr>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col text-start">
                            <div class="page-header-title">
                                    <img src="{{ $logo .'/'. (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png').'?'.time() }}" id="navbar-logo" style="height: 40px;">
                            </div>
                        </div>
                        <div class="col text-end">
                            <div class="d-flex align-items-center justify-content-end">
                                <div>
                                    <small>
                                        <strong>{{ __('Due Date :') }}</strong><br>
                                        {{ date('M d, Y', strtotime($bill->due_date)) }}<br><br>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <small class="font-style">
                                <strong>{{ __('Bill From :') }}</strong><br>
                                @if ($bill->bill_from == 'advocate')
                                {{ App\Models\Advocate::getAdvocates($bill->advocate) }}
                                <br>

                                 {{-- {{
                                App\Models\Advocate::getCityName(App\Models\Advocate::getAdvocat($bill->advocate)->ofc_city)
                                }},
                                &nbsp;
                                {{
                                App\Models\Advocate::getStateName(App\Models\Advocate::getAdvocat($bill->advocate)->ofc_state)
                                }},
                                &nbsp;
                                {{
                                App\Models\Advocate::getCountry(App\Models\Advocate::getAdvocat($bill->advocate)->ofc_country)
                                }} --}}


                                @else
                                {{ $bill->custom_advocate }}
                                <br>
                                {{ $bill->custom_address }}
                                @endif


                            </small>
                        </div>
                        <div class="col ">
                            <small>
                                <strong>{{ __('Bill To :') }}</strong><br>
                                {{ App\Models\User::getUser($bill->bill_to)->name }} <br>

                                @if (!empty(App\Models\UserDetail::getUserDetail(App\Models\User::getUser($bill->bill_to)->id)->address))
                                                {{ App\Models\UserDetail::getUserDetail(App\Models\User::getUser($bill->bill_to)->id)->address }},

                                            @endif

                                            @if (!empty(App\Models\UserDetail::getUserDetail(App\Models\User::getUser($bill->bill_to)->id)->city))
                                                {{ App\Models\UserDetail::getUserDetail(App\Models\User::getUser($bill->bill_to)->id)->city }},

                                            @endif

                                            @if (!empty(App\Models\UserDetail::getUserDetail(App\Models\User::getUser($bill->bill_to)->id)->state))
                                                {{ App\Models\UserDetail::getUserDetail(App\Models\User::getUser($bill->bill_to)->id)->state }}

                                            @endif

                            </small>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <small>
                                <strong>{{ __('Status :') }}</strong><br>
                                @if ($bill->status == 'PENDING')
                                <span class="badge fix_badge rounded p-1 px-3 bg-danger">{{ $bill->status }}</span>
                                @elseif ($bill->status == 'Partialy Paid')
                                <span class="badge fix_badge rounded p-1 px-3 bg-warning">{{ $bill->status }}</span>
                                @else
                                <span class="badge fix_badge rounded p-1 px-3 bg-success">{{ $bill->status }}</span>
                                @endif
                            </small>
                        </div>



                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="font-weight-bold"> {{ __('Summary') }} </div>

                            <div class="table-responsive mt-2">
                                <table class="table mb-0 table-striped">
                                    <tbody>
                                        <tr>
                                            <th data-width="40" class="text-dark">#</th>
                                            <th class="text-dark">{{ __('PARTICULARS') }}</th>
                                            <th class="text-dark">{{ __('NUMBERS') }}</th>
                                            <th class="text-dark">{{ __('RATE/UNIT COST ').'(' .$settings['site_currency'].')' }}</th>
                                            <th class="text-dark">{{ __('TAX') }}</th>
                                            <th class="text-right text-dark" width="12%">{{ __('Amount') }}<br>

                                            </th>
                                        </tr>

                                        @foreach ($items as $key => $item)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $item['particulars'] }}</td>
                                            <td class="numbers">{{ $item['numbers'] }}</td>
                                            <td class="cost">{{ $item['cost'] }}</td>
                                            <td>
                                                {{ App\Models\Tax::getTax($item['tax'])->name }}
                                                {{ '(' . App\Models\Tax::getTax($item['tax'])->rate . '%)' }}
                                                <span class="d-none tax-rate">{{
                                                    App\Models\Tax::getTax($item['tax'])->rate
                                                    }}</span>
                                            </td>
                                            <td class="amount">
                                                <b>$0.00</b>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>

                                    <tfoot>


                                        <tr>
                                            <td colspan="4"></td>
                                            <td class="text-right"><b>{{ __('Sub Total') }}</b></td>
                                            <td class="text-right">{{ $bill->subtotal }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"></td>
                                            <td class="blue-text text-right"><b>{{ __('Total Tax') }}</b></td>
                                            <td class="blue-text text-right">{{ $bill->total_tax }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"></td>
                                            <td class="blue-text text-right"><b>{{ __('Total Amount') }}</b></td>
                                            <td class="blue-text text-right">{{ $bill->total_amount }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"></td>
                                            <td class="blue-text text-right"><b>{{ __('Due Amount') }}</b></td>
                                            <td class="blue-text text-right">{{ $bill->due_amount }}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 pl-0 mt-3">
            <div class="card border rounded-0 card-body shadow-none p-0">
                <div class="card-header">
                    <h5>{{__('Payments')}}</h5>
                </div>
                <div class="card-body table-border-style pb-0">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th> {{ __('Date') }} </th>
                                    <th> {{ __('Amount') }} </th>
                                    <th> {{ __('Payment Type') }} </th>
                                    <th> {{ __('Description') }} </th>
                                    <th> {{ __('Receipt') }} </th>
                                    <th> {{ __('Transaction ID') }} </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                <tr>
                                    <td> {{ $payment->date }} </td>
                                    <td> {{ $payment->amount }} </td>
                                    <td> {{ $payment->method }} </td>
                                    <td> {{ !empty($payment->description) ? $payment->description : ' --- ' }} </td>
                                    <td>{{ !empty($payment->receipt) ? $payment->receipt : ' --- ' }}</td>
                                    <td>{{ !empty($payment->transacrion_id) ? $payment->transacrion_id : ' --- ' }}</td>
                                </tr>
                                @endforeach

                                @foreach ($bankPayments as $bankPayment)
                                <tr>
                                    <td>{{ sprintf('%05d', $bankPayment->transaction_id) }}</td>
                                    <td>{{ $bankPayment->date }}</td>
                                    <td>{{ 'Bank Transfer' }}</td>
                                    <td>{{ !empty($bankPayment->notes) ? $bankPayment->notes : '-' }}</td>
                                    <td>

                                        <a href="{{ \App\Models\Utility::get_file($bankPayment->receipt) }}"
                                            class="btn btn-sm btn-outline-primary" target="_blank"><i
                                                class="fas fa-file-invoice"></i>
                                            </a>

                                    </td>

                                    <td class="text-right">
                                        {{ $bankPayment->amount }}</td>
                                </tr>
                                @endforeach


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-md-2"></div>
        </div>

        <!-- [ Main Content ] end -->
    </div>
</div>


<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">{{ __('Add Payment') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="card bg-none card-body">
                    <section class="">
                        @if (
                        (isset($company_payment_setting['is_stripe_enabled']) &&
                        $company_payment_setting['is_stripe_enabled'] == 'on') ||
                        (isset($company_payment_setting['is_paypal_enabled']) &&
                        $company_payment_setting['is_paypal_enabled'] == 'on') ||
                        (isset($company_payment_setting['is_paystack_enabled']) &&
                        $company_payment_setting['is_paystack_enabled'] == 'on') ||
                        (isset($company_payment_setting['is_flutterwave_enabled']) &&
                        $company_payment_setting['is_flutterwave_enabled'] == 'on') ||
                        (isset($company_payment_setting['is_razorpay_enabled']) &&
                        $company_payment_setting['is_razorpay_enabled'] == 'on') ||
                        (isset($company_payment_setting['is_mercado_enabled']) &&
                        $company_payment_setting['is_mercado_enabled'] == 'on') ||
                        (isset($company_payment_setting['is_paytm_enabled']) &&
                        $company_payment_setting['is_paytm_enabled'] == 'on') ||
                        (isset($company_payment_setting['is_mollie_enabled']) &&
                        $company_payment_setting['is_mollie_enabled'] == 'on') ||
                        (isset($company_payment_setting['is_skrill_enabled']) &&
                        $company_payment_setting['is_skrill_enabled'] == 'on') ||
                        (isset($company_payment_setting['is_coingate_enabled']) &&
                        $company_payment_setting['is_coingate_enabled'] == 'on') ||
                        (isset($company_payment_setting['is_paymentwall_enabled']) &&
                        $company_payment_setting['is_paymentwall_enabled'] == 'on') ||
                        (isset($company_payment_setting['is_toyyibpay_enabled']) &&
                        $company_payment_setting['is_toyyibpay_enabled'] == 'on') ||
                        (isset($company_payment_setting['is_payfast_enabled']) &&
                        $company_payment_setting['is_payfast_enabled'] == 'on')||
                        (isset($company_payment_setting['is_bank_enabled']) &&
                        $company_payment_setting['is_bank_enabled'] == 'on')||
                        (isset($company_payment_setting['is_paytab_enabled']) &&
                        $company_payment_setting['is_paytab_enabled'] == 'on') ||
                        (isset($company_payment_setting['is_benefit_enabled']) &&
                        $company_payment_setting['is_benefit_enabled'] == 'on')||
                        (isset($company_payment_setting['is_paytr_enabled']) &&
                        $company_payment_setting['is_paytr_enabled'] == 'on') ||
                        (isset($company_payment_setting['is_yookassa_enabled']) &&
                        $company_payment_setting['is_yookassa_enabled'] == 'on') ||
                        (isset($company_payment_setting['is_midtrans_enabled']) &&
                        $company_payment_setting['is_midtrans_enabled'] == 'on') ||
                        (isset($company_payment_setting['is_xendit_enabled']) &&
                        $company_payment_setting['is_xendit_enabled'] == 'on') ||
                        (isset($company_payment_setting['is_payhere_enabled']) &&
                        $company_payment_setting['is_payhere_enabled'] == 'on')
                        )

                        <ul class="nav nav-pills  mb-3" id="pills-tab" role="tablist">

                            @if (isset($company_payment_setting['is_bank_enabled']) &&
                            $company_payment_setting['is_bank_enabled'] == 'on')
                            @if (isset($company_payment_setting['bank_details']))
                            <li class="nav-item mb-2 mx-2">
                                <button class="active btn btn-outline-primary btn-sm" id="pills-home-tab"
                                    data-bs-toggle="pill" data-bs-target="#bank-payment" role="tab"
                                    aria-controls="pills-home" aria-selected="true" type="button">{{ __('Bank Transfer')
                                    }}</button>
                            </li>
                            @endif
                            @endif

                            @if (
                            $company_payment_setting['is_stripe_enabled'] == 'on' &&
                            !empty($company_payment_setting['stripe_key']) &&
                            !empty($company_payment_setting['stripe_secret']))
                            <li class="nav-item mb-2 mx-2">
                                <button class=" btn btn-outline-primary btn-sm" id="pills-home-tab"
                                    data-bs-toggle="pill" data-bs-target="#stripe-payment" type="button" role="tab"
                                    aria-controls="pills-home" aria-selected="true">{{ __('Stripe') }}</button>
                            </li>
                            @endif
                            @if (
                            $company_payment_setting['is_paypal_enabled'] == 'on' &&
                            !empty($company_payment_setting['paypal_client_id']) &&
                            !empty($company_payment_setting['paypal_secret_key']))
                            <li class="nav-item mb-2 mx-2">
                                <button class=" btn btn-outline-primary btn-sm" id="pills-home-tab"
                                    data-bs-toggle="pill" data-bs-target="#paypal-payment" type="button" role="tab"
                                    aria-controls="pills-home" aria-selected="true">{{ __('Paypal') }}</button>
                            </li>
                            @endif

                            @if (
                            $company_payment_setting['is_paystack_enabled'] == 'on' &&
                            !empty($company_payment_setting['paystack_public_key']) &&
                            !empty($company_payment_setting['paystack_secret_key']))
                            <li class="nav-item mb-2 mx-2">

                                <button class=" btn btn-outline-primary btn-sm" id="pills-home-tab"
                                    data-bs-toggle="pill" data-bs-target="#paystack-payment" type="button" role="tab"
                                    aria-controls="pills-home" aria-selected="true">{{ __('Paystack') }}</button>
                            </li>
                            @endif

                            @if (isset($company_payment_setting['is_flutterwave_enabled']) &&
                            $company_payment_setting['is_flutterwave_enabled'] == 'on')
                            <li class="nav-item mb-2 mx-2">

                                <button class=" btn btn-outline-primary btn-sm" id="pills-home-tab"
                                    data-bs-toggle="pill" data-bs-target="#flutterwave-payment" type="button" role="tab"
                                    aria-controls="pills-home" aria-selected="true">{{ __('Flutterwave') }}</button>
                            </li>
                            @endif

                            @if (isset($company_payment_setting['is_razorpay_enabled']) &&
                            $company_payment_setting['is_razorpay_enabled'] == 'on')
                            <li class="nav-item mb-2 mx-2">

                                <button class=" btn btn-outline-primary btn-sm" id="pills-home-tab"
                                    data-bs-toggle="pill" data-bs-target="#razorpay-payment" type="button" role="tab"
                                    aria-controls="pills-home" aria-selected="true">{{ __('Razorpay') }}</button>
                            </li>
                            @endif


                            @if (isset($company_payment_setting['is_mercado_enabled']) &&
                            $company_payment_setting['is_mercado_enabled'] == 'on')
                            <li class="nav-item mb-2 mx-2">

                                <button class=" btn btn-outline-primary btn-sm" id="pills-home-tab"
                                    data-bs-toggle="pill" data-bs-target="#mercado-payment" type="button" role="tab"
                                    aria-controls="pills-home" aria-selected="true">{{ __('Mercado') }}</button>
                            </li>
                            @endif

                            @if (isset($company_payment_setting['is_paytm_enabled']) &&
                            $company_payment_setting['is_paytm_enabled'] == 'on')
                            <li class="nav-item mb-2 mx-2">

                                <button class=" btn btn-outline-primary btn-sm" id="pills-home-tab"
                                    data-bs-toggle="pill" data-bs-target="#paytm-payment" type="button" role="tab"
                                    aria-controls="pills-home" aria-selected="true">{{ __('Paytm') }}</button>
                            </li>
                            @endif

                            @if (isset($company_payment_setting['is_mollie_enabled']) &&
                            $company_payment_setting['is_mollie_enabled'] == 'on')
                            <li class="nav-item mb-2 mx-2">

                                <button class=" btn btn-outline-primary btn-sm" id="pills-home-tab"
                                    data-bs-toggle="pill" data-bs-target="#mollie-payment" type="button" role="tab"
                                    aria-controls="pills-home" aria-selected="true">{{ __('Mollie') }}</button>
                            </li>
                            @endif

                            @if (isset($company_payment_setting['is_skrill_enabled']) &&
                            $company_payment_setting['is_skrill_enabled'] == 'on')
                            <li class="nav-item mb-2 mx-2">

                                <button class=" btn btn-outline-primary btn-sm" id="pills-home-tab"
                                    data-bs-toggle="pill" data-bs-target="#skrill-payment" type="button" role="tab"
                                    aria-controls="pills-home" aria-selected="true">{{ __('Skrill') }}</button>
                            </li>
                            @endif

                            @if (isset($company_payment_setting['is_coingate_enabled']) &&
                            $company_payment_setting['is_coingate_enabled'] == 'on')
                            <li class="nav-item mb-2 mx-2">

                                <button class=" btn btn-outline-primary btn-sm" id="pills-home-tab"
                                    data-bs-toggle="pill" data-bs-target="#coingate-payment" type="button" role="tab"
                                    aria-controls="pills-home" aria-selected="true">{{ __('Coingate') }}</button>
                            </li>
                            @endif

                            @if (isset($company_payment_setting['is_paymentwall_enabled']) &&
                            $company_payment_setting['is_paymentwall_enabled'] ==
                            'on')
                            <li class="nav-item mb-2 mx-2">

                                <button class=" btn btn-outline-primary btn-sm" id="pills-home-tab"
                                    data-bs-toggle="pill" data-bs-target="#paymentwall-payment" type="button" role="tab"
                                    aria-controls="pills-home" aria-selected="true">{{ __('Paymentwall') }}</button>
                            </li>
                            @endif

                            @if (isset($company_payment_setting['is_toyyibpay_enabled']) &&
                            $company_payment_setting['is_toyyibpay_enabled'] ==
                            'on')
                            <li class="nav-item mb-2 mx-2">

                                <button class=" btn btn-outline-primary btn-sm" id="pills-home-tab"
                                    data-bs-toggle="pill" data-bs-target="#toyyibpay-payment" type="button" role="tab"
                                    aria-controls="pills-home" aria-selected="true">{{
                                    __('Toyyibpay') }}</button>
                            </li>
                            @endif

                            @if (isset($company_payment_setting['is_payfast_enabled']) &&
                                $company_payment_setting['is_payfast_enabled'] == 'on')
                                @if (isset($company_payment_setting['payfast_merchant_id']) &&
                                !empty($company_payment_setting['payfast_merchant_id']) &&
                                (isset($company_payment_setting['payfast_merchant_key']) &&
                                !empty($company_payment_setting['payfast_merchant_key'])) &&
                                (isset($company_payment_setting['payfast_signature']) &&
                                !empty($company_payment_setting['payfast_signature'])) &&
                                (isset($company_payment_setting['payfast_mode']) &&
                                !empty($company_payment_setting['payfast_mode'])))

                                <li class="nav-item mb-2 mx-2">

                                    <button class=" btn btn-outline-primary btn-sm" id="pills-home-tab"
                                        data-bs-toggle="pill" data-bs-target="#payfast-payment" type="button" role="tab"
                                        aria-controls="pills-home" aria-selected="true">{{
                                        __('Payfast') }}</button>
                                </li>
                                @endif
                            @endif

                            @if (isset($company_payment_setting['is_iyzipay_enabled']) && $company_payment_setting['is_iyzipay_enabled'] == 'on')
                                @if (isset($company_payment_setting['iyzipay_key']) && !empty($company_payment_setting['iyzipay_key']) && (isset($company_payment_setting['iyzipay_secret']) && !empty($company_payment_setting['iyzipay_secret'])))
                                    <li class="nav-item mb-2 mx-2">
                                        <button class="btn btn-outline-primary btn-sm" id="pills-home-tab" data-bs-toggle="pill"
                                        data-bs-target="#iyzipay-payment" role="tab" aria-controls="iyzipay" type="button"
                                            aria-selected="false">{{ __('IyziPay') }}</button>
                                    </li>
                                @endif
                            @endif

                            @if (isset($company_payment_setting['is_sspay_enabled']) && $company_payment_setting['is_sspay_enabled'] == 'on')
                                @if (isset($company_payment_setting['sspay_secret_key']) && !empty($company_payment_setting['sspay_secret_key']) && (isset($company_payment_setting['sspay_category_code']) && !empty($company_payment_setting['sspay_category_code'])))
                                    <li class="nav-item mb-2 mx-2">
                                        <button class="btn btn-outline-primary btn-sm" id="pills-home-tab" data-bs-toggle="pill"
                                        data-bs-target="#sspay-payment" role="tab" aria-controls="sspay" type="button"
                                            aria-selected="false">{{ __('SSPay') }}</button>
                                    </li>
                                @endif
                            @endif

                            @if (isset($company_payment_setting['is_paytab_enabled']) && $company_payment_setting['is_paytab_enabled'] == 'on')
                                @if (isset($company_payment_setting['paytab_profile_id']) && !empty($company_payment_setting['paytab_profile_id']) && (isset($company_payment_setting['paytab_server_key']) && !empty($company_payment_setting['paytab_server_key'])) && (isset($company_payment_setting['paytab_region']) && !empty($company_payment_setting['paytab_region'])))
                                    <li class="nav-item mb-2 mx-2">

                                        <button class="btn btn-outline-primary btn-sm" id="pills-home-tab" data-bs-toggle="pill"
                                        data-bs-target="#paytab-payment" role="tab" aria-controls="paytab" type="button"
                                            aria-selected="false">{{ __('PayTab') }}</button>
                                    </li>
                                @endif
                            @endif
                            @if (isset($company_payment_setting['is_benefit_enabled']) && $company_payment_setting['is_benefit_enabled'] == 'on')
                                @if (isset($company_payment_setting['benefit_api_key']) &&
                                !empty($company_payment_setting['benefit_api_key']) &&
                                (isset($company_payment_setting['benefit_secret_key']) && !empty($company_payment_setting['benefit_secret_key'])))
                                    <li class="nav-item mb-2 mx-2">

                                        <button class="btn btn-outline-primary btn-sm" id="pills-home-tab" data-bs-toggle="pill"
                                        data-bs-target="#benefit-payment" role="tab" aria-controls="benefit" type="button"
                                            aria-selected="false">{{ __('Benefit') }}</button>
                                    </li>
                                @endif
                            @endif

                            @if (isset($company_payment_setting['is_cashfree_enabled']) && $company_payment_setting['is_cashfree_enabled'] == 'on')
                                @if (isset($company_payment_setting['cashfree_api_key']) &&
                                        !empty($company_payment_setting['cashfree_api_key']) &&
                                        (isset($company_payment_setting['cashfree_secret_key']) && !empty($company_payment_setting['cashfree_secret_key'])))

                                    <li class="nav-item mb-2 mx-2">

                                        <button class="btn btn-outline-primary btn-sm" id="pills-home-tab" data-bs-toggle="pill"
                                        data-bs-target="#cashfree-payment" role="tab" aria-controls="benefit" type="button"
                                            aria-selected="false">{{ __('Cashfree') }}</button>
                                    </li>
                                @endif
                            @endif

                            @if (isset($company_payment_setting['is_aamarpay_enabled']) && $company_payment_setting['is_aamarpay_enabled'] == 'on')
                                @if (isset($company_payment_setting['aamarpay_store_id']) &&
                                !empty($company_payment_setting['aamarpay_store_id']) &&
                                (isset($company_payment_setting['aamarpay_signature_key']) && !empty($company_payment_setting['aamarpay_signature_key'])) &&
                                (isset($company_payment_setting['aamarpay_description']) && !empty($company_payment_setting['aamarpay_description'])))

                                    <li class="nav-item mb-2 mx-2">

                                        <button class="btn btn-outline-primary btn-sm" id="pills-home-tab" data-bs-toggle="pill"
                                        data-bs-target="#aamarpay-payment" role="tab" aria-controls="benefit" type="button"
                                            aria-selected="false">{{ __('Aamarpay') }}</button>
                                    </li>
                                @endif
                            @endif
                            @if (isset($company_payment_setting['is_paytr_enabled']) && $company_payment_setting['is_paytr_enabled'] == 'on')
                                @if (isset($company_payment_setting['paytr_merchant_id']) &&
                                !empty($company_payment_setting['paytr_merchant_id']) &&
                                (isset($company_payment_setting['paytr_merchant_key']) && !empty($company_payment_setting['paytr_merchant_key'])) &&
                                (isset($company_payment_setting['paytr_merchant_salt']) && !empty($company_payment_setting['paytr_merchant_salt'])))

                                    <li class="nav-item mb-2 mx-2">

                                        <button class="btn btn-outline-primary btn-sm" id="pills-home-tab" data-bs-toggle="pill"
                                        data-bs-target="#paytr-payment" role="tab" aria-controls="paytr" type="button"
                                            aria-selected="false">{{ __('Pay TR') }}</button>
                                    </li>
                                @endif
                            @endif
                            @if (isset($company_payment_setting['is_yookassa_enabled']) && $company_payment_setting['is_yookassa_enabled'] == 'on')
                                @if (isset($company_payment_setting['yookassa_shop_id']) &&
                                !empty($company_payment_setting['yookassa_shop_id']) &&
                                (isset($company_payment_setting['yookassa_secret']) && !empty($company_payment_setting['yookassa_secret'])))
                                    <li class="nav-item mb-2 mx-2">
                                        <button class="btn btn-outline-primary btn-sm" id="pills-home-tab" data-bs-toggle="pill"
                                        data-bs-target="#yookassa-payment" role="tab" aria-controls="yookassa" type="button"
                                            aria-selected="false">{{ __('Yookassa') }}</button>
                                    </li>
                                @endif
                            @endif

                            @if (isset($company_payment_setting['is_midtrans_enabled']) && $company_payment_setting['is_midtrans_enabled'] == 'on')
                                @if (isset($company_payment_setting['midtrans_secret']) && !empty($company_payment_setting['midtrans_secret']) )
                                    <li class="nav-item mb-2 mx-2">
                                        <button class="btn btn-outline-primary btn-sm" id="pills-home-tab" data-bs-toggle="pill"
                                        data-bs-target="#midtrans-payment" role="tab" aria-controls="midtrans" type="button"
                                            aria-selected="false">{{ __('Midtrans') }}</button>
                                    </li>
                                @endif
                            @endif

                            @if (isset($company_payment_setting['is_xendit_enabled']) && $company_payment_setting['is_xendit_enabled'] == 'on')
                                @if (isset($company_payment_setting['xendit_api']) && !empty($company_payment_setting['xendit_api']) )
                                    <li class="nav-item mb-2 mx-2">
                                        <button class="btn btn-outline-primary btn-sm" id="pills-home-tab" data-bs-toggle="pill"
                                        data-bs-target="#xendit-payment" role="tab" aria-controls="xendit" type="button"
                                            aria-selected="false">{{ __('Xendit') }}</button>
                                    </li>
                                @endif
                            @endif

                            @if (isset($company_payment_setting['is_payhere_enabled']) && $company_payment_setting['is_payhere_enabled'] == 'on')
                                @if (isset($company_payment_setting['merchant_id']) && !empty($company_payment_setting['merchant_id']) )
                                    <li class="nav-item mb-2 mx-2">
                                        <button class="btn btn-outline-primary btn-sm" id="pills-home-tab" data-bs-toggle="pill"
                                        data-bs-target="#payhere-payment" role="tab" aria-controls="payhere" type="button"
                                            aria-selected="false">{{ __('PayHere') }}</button>
                                    </li>
                                @endif
                            @endif


                        </ul>
                        @endif



                        <div class="tab-content" id="pills-tabContent">
                            @if (isset($company_payment_setting['is_bank_enabled']) &&
                            $company_payment_setting['is_bank_enabled'] == 'on')
                            @if (isset($company_payment_setting['bank_details']))
                            <div class="tab-pane fade {{ isset($company_payment_setting['is_bank_enabled']) && $company_payment_setting['is_bank_enabled'] == 'on' ? 'show active' : '' }}"
                                id="bank-payment" role="tabpanel" aria-labelledby="bank-payment">
                                <form class="w3-container w3-display-middle w3-card-4 "
                                    action="{{ route('invoice.pay.with.bank') }}" method="POST"
                                    enctype="multipart/form-data" id="bank-payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label"><b>{{ __('Bank Details:') }}</b></label>
                                            <div class="form-group">
                                                @if (isset($company_payment_setting['bank_details']) &&
                                                !empty($company_payment_setting['bank_details']))
                                                {!! $company_payment_setting['bank_details'] !!}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label"> {{ __('Payment Receipt') }}</label>
                                            <div class="form-group">
                                                <input type="file" name="payment_receipt" class="form-control mb-3"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="amount" class="col-form-label">{{ __('Amount') }}</label>
                                                <div class="input-group col-md-12">
                                                    <div class="input-group-text">
                                                        {{ $company_payment_setting['site_currency'] }}</div>
                                                    <input class="form-control" required="required" min="0"
                                                        name="amount" type="number" value="{{ $bill->due_amount }}"
                                                        min="0" step="0.01" max="{{ $bill->due_amount }}" id="amount">
                                                    <input type="hidden" value="{{ $bill->id }}" name="invoice_id">
                                                </div>
                                                @error('amount')
                                                <span class="invalid-amount text-danger text-xs" role="alert">{{
                                                    $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-12 form-group mt-3 text-end">
                                                <input type="submit" value="{{ __('Make Payment') }}"
                                                    class="btn btn-print-invoice  btn-primary m-r-10">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @endif
                            @endif

                            @if (
                            !empty($company_payment_setting) &&
                            ($company_payment_setting['is_stripe_enabled'] == 'on' &&
                            !empty($company_payment_setting['stripe_key']) &&
                            !empty($company_payment_setting['stripe_secret'])))
                            <div class="tab-pane fade " id="stripe-payment" role="tabpanel"
                                aria-labelledby="stripe-payment">
                                <form method="post"
                                    action="{{ route('invoice.payment', \Illuminate\Support\Facades\Crypt::encrypt($bill->id)) }}"
                                    class="require-validation" id="payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-8">
                                            <div class="custom-radio">
                                                <label class="font-16 font-weight-bold">{{ __('Credit / Debit Card')
                                                    }}</label>
                                            </div>
                                            <p class="mb-0 pt-1 text-sm">
                                                {{ __('Safe money transfer using your bank account. We support
                                                Mastercard, Visa, Discover and American express.') }}
                                            </p>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="card-name-on">{{ __('Name on card') }}</label>
                                                <input type="text" name="name" id="card-name-on"
                                                    class="form-control required" placeholder="{{ __('Your Name') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div id="card-element">

                                            </div>
                                            <div id="card-errors" role="alert"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <br>
                                            <label for="amount">{{ __('Amount') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-prepend"><span class="input-group-text">{{
                                                        $company_payment_setting['site_currency'] }}</span></span>
                                                <input class="form-control" required="required" min="0" name="amount"
                                                    type="number" value="{{ $bill->due_amount }}" min="0" step="0.01"
                                                    max="{{ $bill->total_amount }}" id="amount">
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
                                    <div class="col-12 form-group mt-3 text-end">
                                        <button class="btn btn-sm btn-primary m-r-10" type="submit">{{ __('Make
                                            Payment') }}</button>
                                    </div>
                                </form>
                            </div>
                            @endif

                            @if (
                            !empty($company_payment_setting) &&
                            ($company_payment_setting['is_paypal_enabled'] == 'on' &&
                            !empty($company_payment_setting['paypal_client_id']) &&
                            !empty($company_payment_setting['paypal_secret_key'])))
                            <div class="tab-pane fade " id="paypal-payment" role="tabpanel"
                                aria-labelledby="paypal-payment">
                                <form class="w3-container w3-display-middle w3-card-4 " method="POST" id="payment-form"
                                    action="{{ route('bill.with.paypal', \Illuminate\Support\Facades\Crypt::encrypt($bill->id)) }}">

                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="amount">{{ __('Amount') }}</label>
                                            <div class="input-group">
                                                <span class="input-group-prepend"><span class="input-group-text">{{
                                                        $company_payment_setting['site_currency'] }}</span></span>
                                                <input class="form-control" required="required" min="0" name="amount"
                                                    type="number" value="{{ $bill->due_amount }}" min="0" step="0.01"
                                                    max="{{ $bill->total_amount }}" id="amount">
                                                @error('amount')
                                                <span class="invalid-amount" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 form-group mt-3 text-end">
                                        <button class="btn btn-sm btn-primary m-r-10" name="submit" type="submit">{{
                                            __('Make Payment') }}</button>
                                    </div>
                                </form>
                            </div>
                            @endif

                            @if (
                            !empty($company_payment_setting) &&
                            isset($company_payment_setting['is_paystack_enabled']) &&
                            $company_payment_setting['is_paystack_enabled'] == 'on' &&
                            !empty($company_payment_setting['paystack_public_key']) &&
                            !empty($company_payment_setting['paystack_secret_key']))
                            <div class="tab-pane fade " id="paystack-payment" role="tabpanel"
                                aria-labelledby="paypal-payment">
                                <form class="w3-container w3-display-middle w3-card-4" method="POST"
                                    id="paystack-payment-form" action="{{ route('invoice.pay.with.paystack') }}">
                                    @csrf
                                    <input type="hidden" name="invoice_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($bill->id) }}">

                                    <div class="form-group col-md-12">
                                        <label for="amount">{{ __('Amount') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-prepend"><span class="input-group-text">{{
                                                    $company_payment_setting['site_currency'] }}</span></span>
                                            <input class="form-control" required="required" min="0" name="amount"
                                                type="number" value="{{ $bill->due_amount }}" min="0" step="0.01"
                                                max="{{ $bill->total_amount }}" id="amount">

                                        </div>
                                    </div>
                                    <div class="col-12 form-group mt-3 text-end">
                                        <input class="btn btn-sm btn-primary m-r-10" id="pay_with_paystack"
                                            type="button" value="{{ __('Make Payment') }}">
                                    </div>

                                </form>
                            </div>
                            @endif

                            @if (
                            !empty($company_payment_setting) &&
                            isset($company_payment_setting['is_flutterwave_enabled']) &&
                            $company_payment_setting['is_flutterwave_enabled'] == 'on' &&
                            !empty($company_payment_setting['paystack_public_key']) &&
                            !empty($company_payment_setting['paystack_secret_key']))
                            <div class="tab-pane fade " id="flutterwave-payment" role="tabpanel"
                                aria-labelledby="paypal-payment">
                                <form role="form" action="{{ route('invoice.pay.with.flaterwave') }}" method="post"
                                    class="require-validation" id="flaterwave-payment-form">
                                    @csrf
                                    <input type="hidden" name="invoice_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($bill->id) }}">

                                    <div class="form-group col-md-12">
                                        <label for="amount">{{ __('Amount') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-prepend"><span class="input-group-text">{{
                                                    $company_payment_setting['site_currency'] }}</span></span>
                                            <input class="form-control" required="required" min="0" name="amount"
                                                type="number" value="{{ $bill->due_amount }}" min="0" step="0.01"
                                                max="{{ $bill->total_amount }}" id="amount">

                                        </div>
                                    </div>
                                    <div class="col-12 form-group mt-3 text-end">
                                        <input class="btn btn-sm btn-primary m-r-10" id="pay_with_flaterwave"
                                            type="button" value="{{ __('Make Payment') }}">
                                    </div>

                                </form>
                            </div>
                            @endif

                            @if (
                            !empty($company_payment_setting) &&
                            isset($company_payment_setting['is_razorpay_enabled']) &&
                            $company_payment_setting['is_razorpay_enabled'] == 'on')
                            <div class="tab-pane fade " id="razorpay-payment" role="tabpanel"
                                aria-labelledby="paypal-payment">
                                <form role="form" action="{{ route('invoice.pay.with.razorpay') }}" method="post"
                                    class="require-validation" id="razorpay-payment-form">
                                    @csrf
                                    <input type="hidden" name="invoice_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($bill->id) }}">

                                    <div class="form-group col-md-12">
                                        <label for="amount">{{ __('Amount') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-prepend"><span class="input-group-text">{{
                                                    $company_payment_setting['site_currency'] }}</span></span>
                                            <input class="form-control" required="required" min="0" name="amount"
                                                type="number" value="{{ $bill->due_amount }}" min="0" step="0.01"
                                                max="{{ $bill->total_amount }}" id="amount">

                                        </div>
                                    </div>
                                    <div class="col-12 form-group mt-3 text-end">
                                        <input class="btn btn-sm btn-primary m-r-10" id="pay_with_razorpay"
                                            type="button" value="{{ __('Make Payment') }}">
                                    </div>

                                </form>
                            </div>
                            @endif

                            @if (
                            !empty($company_payment_setting) &&
                            isset($company_payment_setting['is_mercado_enabled']) &&
                            $company_payment_setting['is_mercado_enabled'] == 'on')
                            <div class="tab-pane fade " id="mercado-payment" role="tabpanel"
                                aria-labelledby="mercado-payment">
                                <form role="form" action="{{ route('invoice.pay.with.mercado') }}" method="post"
                                    class="require-validation" id="mercado-payment-form">
                                    @csrf
                                    <input type="hidden" name="invoice_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($bill->id) }}">

                                    <div class="form-group col-md-12">
                                        <label for="amount">{{ __('Amount') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-prepend"><span class="input-group-text">{{
                                                    $company_payment_setting['site_currency'] }}</span></span>
                                            <input class="form-control" required="required" min="0" name="amount"
                                                type="number" value="{{ $bill->due_amount }}" min="0" step="0.01"
                                                max="{{ $bill->total_amount }}" id="amount">

                                        </div>
                                    </div>
                                    <div class="col-12 form-group mt-3 text-end">
                                        <input type="submit" id="pay_with_mercado" value="{{ __('Make Payment') }}"
                                            class="btn btn-sm btn-primary m-r-10">
                                    </div>

                                </form>
                            </div>
                            @endif

                            @if (!empty($company_payment_setting) && isset($company_payment_setting['is_paytm_enabled'])
                            && $company_payment_setting['is_paytm_enabled'] == 'on')
                            <div class="tab-pane fade" id="paytm-payment" role="tabpanel"
                                aria-labelledby="paytm-payment">
                                <form role="form" action="{{ route('invoice.pay.with.paytm') }}" method="post"
                                    class="require-validation" id="paytm-payment-form">
                                    @csrf
                                    <input type="hidden" name="invoice_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($bill->id) }}">

                                    <div class="form-group col-md-12">
                                        <label for="amount">{{ __('Amount') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-prepend"><span class="input-group-text">{{
                                                    $company_payment_setting['site_currency'] }}</span></span>
                                            <input class="form-control" required="required" min="0" name="amount"
                                                type="number" value="{{ $bill->due_amount }}" min="0" step="0.01"
                                                max="{{ $bill->total_amount }}" id="amount">

                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="flaterwave_coupon" class=" text-dark">{{ __('Mobile Number')
                                                }}</label>
                                            <input type="text" id="mobile" name="mobile" class="form-control mobile"
                                                data-from="mobile" placeholder="{{ __('Enter Mobile Number') }}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-12 form-group mt-3 text-end">
                                        <input type="submit" id="pay_with_paytm" value="{{ __('Make Payment') }}"
                                            class="btn btn-sm btn-primary m-r-10">
                                    </div>

                                </form>
                            </div>
                            @endif

                            @if (!empty($company_payment_setting) &&
                            isset($company_payment_setting['is_mollie_enabled']) &&
                            $company_payment_setting['is_mollie_enabled'] == 'on')
                            <div class="tab-pane fade " id="mollie-payment" role="tabpanel"
                                aria-labelledby="mollie-payment">
                                <form role="form" action="{{ route('invoice.pay.with.mollie') }}" method="post"
                                    class="require-validation" id="mollie-payment-form">
                                    @csrf
                                    <input type="hidden" name="invoice_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($bill->id) }}">

                                    <div class="form-group col-md-12">
                                        <label for="amount">{{ __('Amount') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-prepend"><span class="input-group-text">{{
                                                    $company_payment_setting['site_currency'] }}</span></span>
                                            <input class="form-control" required="required" min="0" name="amount"
                                                type="number" value="{{ $bill->due_amount }}" min="0" step="0.01"
                                                max="{{ $bill->total_amount }}" id="amount">

                                        </div>
                                    </div>
                                    <div class="col-12 form-group mt-3 text-end">
                                        <input type="submit" id="pay_with_mollie" value="{{ __('Make Payment') }}"
                                            class="btn btn-sm btn-primary m-r-10">
                                    </div>
                                </form>
                            </div>
                            @endif


                            @if (!empty($company_payment_setting) &&
                            isset($company_payment_setting['is_skrill_enabled']) &&
                            $company_payment_setting['is_skrill_enabled'] == 'on')
                            <div class="tab-pane fade " id="skrill-payment" role="tabpanel"
                                aria-labelledby="skrill-payment">
                                <form role="form" action="{{ route('invoice.pay.with.skrill') }}" method="post"
                                    class="require-validation" id="skrill-payment-form">
                                    @csrf
                                    <input type="hidden" name="invoice_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($bill->id) }}">

                                    <div class="form-group col-md-12">
                                        <label for="amount">{{ __('Amount') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-prepend"><span class="input-group-text">{{
                                                    $company_payment_setting['site_currency'] }}</span></span>
                                            <input class="form-control" required="required" min="0" name="amount"
                                                type="number" value="{{ $bill->due_amount }}" min="0" step="0.01"
                                                max="{{ $bill->total_amount }}" id="amount">

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
                                    <div class="col-12 form-group mt-3 text-end">
                                        <input type="submit" id="pay_with_skrill" value="{{ __('Make Payment') }}"
                                            class="btn btn-sm btn-primary m-r-10">
                                    </div>

                                </form>
                            </div>
                            @endif

                            @if (!empty($company_payment_setting) &&
                            isset($company_payment_setting['is_coingate_enabled']) &&
                            $company_payment_setting['is_coingate_enabled'] == 'on')
                            <div class="tab-pane fade " id="coingate-payment" role="tabpanel"
                                aria-labelledby="coingate-payment">
                                <form role="form" action="{{ route('invoice.pay.with.coingate') }}" method="post"
                                    class="require-validation" id="coingate-payment-form">
                                    @csrf
                                    <input type="hidden" name="invoice_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($bill->id) }}">

                                    <div class="form-group col-md-12">
                                        <label for="amount">{{ __('Amount') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-prepend"><span class="input-group-text">{{
                                                    $company_payment_setting['site_currency'] }}</span></span>
                                            <input class="form-control" required="required" min="0" name="amount"
                                                type="number" value="{{ $bill->due_amount }}" min="0" step="0.01"
                                                max="{{ $bill->total_amount }}" id="amount">

                                        </div>
                                    </div>
                                    <div class="col-12 form-group mt-3 text-end">
                                        <input type="submit" id="pay_with_coingate" value="{{ __('Make Payment') }}"
                                            class="btn btn-sm btn-primary m-r-10">
                                    </div>

                                </form>
                            </div>
                            @endif

                            @if (!empty($company_payment_setting) &&
                            isset($company_payment_setting['is_paymentwall_enabled']) &&
                            $company_payment_setting['is_paymentwall_enabled'] == 'on')
                            <div class="tab-pane fade " id="paymentwall-payment" role="tabpanel"
                                aria-labelledby="paymentwall-payment">
                                <form role="form" action="{{ route('paymentwall.invoice') }}" method="post"
                                    class="require-validation" id="paymentwall-payment-form">
                                    @csrf
                                    <input type="hidden" name="invoice_id"
                                        value="{{ \Illuminate\Support\Facades\Crypt::encrypt($bill->id) }}">

                                    <div class="form-group col-md-12">
                                        <label for="amount">{{ __('Amount') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-prepend"><span class="input-group-text">{{
                                                    $company_payment_setting['site_currency'] }}</span></span>
                                            <input class="form-control" required="required" min="0" name="amount"
                                                type="number" value="{{ $bill->due_amount }}" min="0" step="0.01"
                                                max="{{ $bill->total_amount }}" id="amount">

                                        </div>
                                    </div>
                                    <div class="col-12 form-group mt-3 text-end">
                                        <input type="submit" id="pay_with_coingate" value="{{ __('Make Payment') }}"
                                            class="btn btn-sm btn-primary m-r-10">
                                    </div>

                                </form>
                            </div>
                            @endif


                            <div class="tab-pane fade" id="toyyibpay-payment" role="tabpanel"
                                aria-labelledby="toyyibpay-payment">

                                @if (isset($company_payment_setting['is_toyyibpay_enabled']) &&
                                $company_payment_setting['is_toyyibpay_enabled'] == 'on')
                                @if (isset($company_payment_setting['toyyibpay_secret_key']) &&
                                !empty($company_payment_setting['toyyibpay_secret_key']) &&
                                (isset($company_payment_setting['category_code']) &&
                                !empty($company_payment_setting['category_code'])))
                                <form method="post" action="{{ route('invoice.with.toyyibpay') }}"
                                    class="require-validation" id="toyyibpay-payment-form">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="amount" class="col-form-label">{{ __('Amount') }}</label>
                                            <div class="input-group col-md-12">
                                                <div class="input-group-text">
                                                    {{ isset($company_payment_setting['site_currency']) ?
                                                    $company_payment_setting['site_currency'] : '$' }}
                                                </div>
                                                <input class="form-control" required="required" min="0" name="amount"
                                                    type="number" value="{{ $bill->due_amount }}" min="0" step="0.01"
                                                    max="{{ $bill->due_amount }}" id="amount">
                                                <input type="hidden" value="{{ $bill->id }}" name="invoice_id">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 form-group mt-3 text-end">
                                        <input type="submit" value="{{ __('Make Payment') }}"
                                            class="btn btn-print-invoice  btn-primary m-r-10" id="pay_with_toyyibpay">
                                    </div>
                                </form>
                                @endif
                                @endif
                            </div>

                            <div class="tab-pane fade" id="payfast-payment" role="tabpanel"
                                aria-labelledby="payfast-payment">

                                @if (isset($company_payment_setting['is_payfast_enabled']) &&
                                $company_payment_setting['is_payfast_enabled'] == 'on')
                                @if (isset($company_payment_setting['payfast_merchant_id']) &&
                                !empty($company_payment_setting['payfast_merchant_id']) &&
                                (isset($company_payment_setting['payfast_merchant_key']) &&
                                !empty($company_payment_setting['payfast_merchant_key'])) &&
                                (isset($company_payment_setting['payfast_signature']) &&
                                !empty($company_payment_setting['payfast_signature'])) &&
                                (isset($company_payment_setting['payfast_mode']) &&
                                !empty($company_payment_setting['payfast_mode'])))
                                @php
                                $pfHost = $company_payment_setting['payfast_mode'] == 'sandbox' ?
                                'sandbox.payfast.co.za' : 'www.payfast.co.za';
                                @endphp
                                <form method="post" action={{ 'https://' . $pfHost . '/eng/process' }}
                                    class="require-validation" id="payfast-payment-form">
                                    @csrf
                                    <div class="row">

                                        <div class="form-group col-md-12">
                                            <label for="amount" class="col-form-label">{{ __('Amount') }}</label>
                                            <div class="input-group col-md-12">
                                                <div class="input-group-text">
                                                    {{ isset($company_payment_setting['site_currency']) ?
                                                    $company_payment_setting['site_currency'] : '$' }}
                                                </div>
                                                <input class="form-control input_payfast" required="required" min="0"
                                                    name="amount" type="number" value="{{ $bill->due_amount }}" min="0"
                                                    step="0.01" max="{{ $bill->due_amount }}" id="amount">

                                                <input type="hidden" value="{{ $bill->id }}" name="invoice_id">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <input type="hidden" name="invoice_id" id="invoice_id" class=""
                                            value="{{ \Illuminate\Support\Facades\Crypt::encrypt($bill->id) }}">
                                        <div id="get-payfast-inputs"></div>
                                        <button class="btn btn-primary" type="submit">{{ __('Make Payment') }}</button>
                                    </div>
                                </form>
                                @endif
                                @endif
                            </div>

                            @if (isset($company_payment_setting['is_iyzipay_enabled']) && $company_payment_setting['is_iyzipay_enabled'] == 'on')
                                @if (isset($company_payment_setting['iyzipay_key']) &&
                                        !empty($company_payment_setting['iyzipay_key']) &&
                                        (isset($company_payment_setting['iyzipay_secret']) && !empty($company_payment_setting['iyzipay_secret'])))
                                    <div class="tab-pane fade" id="iyzipay-payment" role="tabpanel"
                                        aria-labelledby="iyzipay-payment">

                                        <form class="w3-container w3-display-middle w3-card-4 " method="POST"
                                            id="payment-form" action="{{ route('invoice.with.iyzipay') }}">
                                            @csrf
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="amount"
                                                        class="col-form-label">{{ __('Amount') }}</label>
                                                    <div class="input-group col-md-12">
                                                        <div class="input-group-text">
                                                            {{ isset($company_payment_setting['site_currency_symbol']) ? $company_payment_setting['site_currency_symbol'] : '$' }}
                                                        </div>
                                                        <input class="form-control" required="required" min="0"
                                                            name="amount" type="number"
                                                            value="{{ $bill->due_amount }}" min="0"
                                                            step="0.01" max="{{ $bill->due_amount }}"
                                                            id="amount">
                                                        <input type="hidden" value="{{ $bill->id }}"
                                                            name="invoice_id">
                                                    </div>
                                                    @error('amount')
                                                        <span class="invalid-amount text-danger text-xs"
                                                            role="alert">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-12 form-group mt-3 text-end">
                                                    <input type="submit" value="{{ __('Make Payment') }}"
                                                        class="btn btn-print-invoice  btn-primary m-r-10">
                                                </div>
                                            </div>
                                        </form>


                                    </div>
                                @endif
                            @endif

                            @if (isset($company_payment_setting['is_sspay_enabled']) && $company_payment_setting['is_sspay_enabled'] == 'on')
                                @if (isset($company_payment_setting['sspay_secret_key']) &&
                                        !empty($company_payment_setting['sspay_secret_key']) &&
                                        (isset($company_payment_setting['sspay_category_code']) && !empty($company_payment_setting['sspay_category_code'])))
                                    <div class="tab-pane fade" id="sspay-payment" role="tabpanel"
                                        aria-labelledby="sspay-payment">

                                        <form class="w3-container w3-display-middle w3-card-4 " method="POST"
                                            id="payment-form"
                                            action="{{ route('customer.pay.with.sspay', $bill->id) }}">
                                            @csrf
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="amount"
                                                        class="col-form-label">{{ __('Amount') }}</label>
                                                    <div class="input-group col-md-12">
                                                        <div class="input-group-text">
                                                            {{ isset($company_payment_setting['currency_symbol']) ? $company_payment_setting['currency_symbol'] : '$' }}
                                                        </div>
                                                        <input class="form-control" required="required" min="0"
                                                            name="amount" type="number"
                                                            value="{{ $bill->due_amount }}" min="0"
                                                            step="0.01" max="{{ $bill->due_amount }}"
                                                            id="amount">
                                                        <input type="hidden" value="{{ $bill->id }}"
                                                            name="invoice_id">
                                                    </div>
                                                    @error('amount')
                                                        <span class="invalid-amount text-danger text-xs"
                                                            role="alert">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-12 form-group mt-3 text-end">
                                                    <input type="submit" value="{{ __('Make Payment') }}"
                                                        class="btn btn-print-invoice  btn-primary m-r-10">
                                                </div>
                                            </div>
                                        </form>


                                    </div>
                                @endif
                            @endif

                            @if (isset($company_payment_setting['is_paytab_enabled']) && $company_payment_setting['is_paytab_enabled'] == 'on')
                                @if (isset($company_payment_setting['paytab_profile_id']) &&
                                        !empty($company_payment_setting['paytab_profile_id']) &&
                                        (isset($company_payment_setting['paytab_server_key']) && !empty($company_payment_setting['paytab_server_key'])) &&
                                        (isset($company_payment_setting['paytab_region']) && !empty($company_payment_setting['paytab_region'])))
                                    <div class="tab-pane fade" id="paytab-payment" role="tabpanel" aria-labelledby="paytab-payment">

                                        <form class="w3-container w3-display-middle w3-card-4 " method="POST"
                                            id="payment-form" action="{{ route('pay.with.paytab', $bill->id) }}">
                                            @csrf
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="amount"
                                                        class="col-form-label">{{ __('Amount') }}</label>
                                                    <div class="input-group col-md-12">
                                                        <div class="input-group-text">
                                                            {{ isset($company_payment_setting['currency_symbol']) ? $company_payment_setting['currency_symbol'] : '$' }}
                                                        </div>
                                                        <input class="form-control" required="required" min="0"
                                                            name="amount" type="number"
                                                            value="{{ $bill->due_amount }}" min="0"
                                                            step="0.01" max="{{ $bill->due_amount }}"
                                                            id="amount">
                                                        <input type="hidden" value="{{ $bill->id }}"
                                                            name="invoice_id">
                                                    </div>
                                                    @error('amount')
                                                        <span class="invalid-amount text-danger text-xs"
                                                            role="alert">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-12 form-group mt-3 text-end">
                                                    <input type="submit" value="{{ __('Make Payment') }}"
                                                        class="btn btn-print-invoice  btn-primary m-r-10">
                                                </div>
                                            </div>
                                        </form>


                                    </div>
                                @endif
                            @endif

                            @if (isset($company_payment_setting['is_benefit_enabled']) && $company_payment_setting['is_benefit_enabled'] == 'on')
                                @if (isset($company_payment_setting['benefit_api_key']) &&
                                        !empty($company_payment_setting['benefit_api_key']) &&
                                        (isset($company_payment_setting['benefit_secret_key']) && !empty($company_payment_setting['benefit_secret_key'])))
                                    <div class="tab-pane fade" id="benefit-payment" role="tabpanel"
                                        aria-labelledby="benefit-payment">

                                        <form class="w3-container w3-display-middle w3-card-4 " method="POST"
                                            id="payment-form" action="{{ route('pay.with.paytab', $bill->id) }}">
                                            @csrf
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="amount"
                                                        class="col-form-label">{{ __('Amount') }}</label>
                                                    <div class="input-group col-md-12">
                                                        <div class="input-group-text">
                                                            {{ isset($company_payment_setting['currency_symbol']) ? $company_payment_setting['currency_symbol'] : '$' }}
                                                        </div>
                                                        <input class="form-control" required="required" min="0"
                                                            name="amount" type="number"
                                                            value="{{ $bill->due_amount }}" min="0"
                                                            step="0.01" max="{{ $bill->due_amount }}"
                                                            id="amount">
                                                        <input type="hidden" value="{{ $bill->id }}"
                                                            name="invoice_id">
                                                    </div>
                                                    @error('amount')
                                                        <span class="invalid-amount text-danger text-xs"
                                                            role="alert">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-12 form-group mt-3 text-end">
                                                    <input type="submit" value="{{ __('Make Payment') }}"
                                                        class="btn btn-print-invoice  btn-primary m-r-10">
                                                </div>
                                            </div>
                                        </form>


                                    </div>
                                @endif
                            @endif

                            @if (isset($company_payment_setting['is_cashfree_enabled']) && $company_payment_setting['is_cashfree_enabled'] == 'on')
                                @if (isset($company_payment_setting['cashfree_api_key']) &&
                                        !empty($company_payment_setting['cashfree_api_key']) &&
                                        (isset($company_payment_setting['cashfree_secret_key']) && !empty($company_payment_setting['cashfree_secret_key'])))
                                    <div class="tab-pane fade" id="cashfree-payment" role="tabpanel"
                                        aria-labelledby="cashfree-payment">

                                        <form class="w3-container w3-display-middle w3-card-4 " method="POST"
                                            id="payment-form" action="{{ route('pay.with.cashfree', $bill->id) }}">
                                            @csrf
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="amount"
                                                        class="col-form-label">{{ __('Amount') }}</label>
                                                    <div class="input-group col-md-12">
                                                        <div class="input-group-text">
                                                            {{ isset($company_payment_setting['currency_symbol']) ? $company_payment_setting['currency_symbol'] : '$' }}
                                                        </div>
                                                        <input class="form-control" required="required" min="0"
                                                            name="amount" type="number"
                                                            value="{{ $bill->due_amount }}" min="0"
                                                            step="0.01" max="{{ $bill->due_amount }}"
                                                            id="amount">
                                                        <input type="hidden" value="{{ $bill->id }}"
                                                            name="invoice_id">
                                                    </div>
                                                    @error('amount')
                                                        <span class="invalid-amount text-danger text-xs"
                                                            role="alert">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-12 form-group mt-3 text-end">
                                                    <input type="submit" value="{{ __('Make Payment') }}"
                                                        class="btn btn-print-invoice  btn-primary m-r-10">
                                                </div>
                                            </div>
                                        </form>


                                    </div>
                                @endif
                            @endif

                            @if (isset($company_payment_setting['is_aamarpay_enabled']) && $company_payment_setting['is_aamarpay_enabled'] == 'on')
                                @if (isset($company_payment_setting['aamarpay_store_id']) &&
                                        !empty($company_payment_setting['aamarpay_store_id']) &&
                                        (isset($company_payment_setting['aamarpay_signature_key']) && !empty($company_payment_setting['aamarpay_signature_key'])) &&
                                        (isset($company_payment_setting['aamarpay_description']) && !empty($company_payment_setting['aamarpay_description'])))
                                    <div class="tab-pane fade" id="aamarpay-payment" role="tabpanel"
                                        aria-labelledby="aamarpay-payment">

                                        <form class="w3-container w3-display-middle w3-card-4 " method="POST"
                                            id="payment-form" action="{{ route('pay.with.aamarpay', $bill->id) }}">
                                            @csrf
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="amount"
                                                        class="col-form-label">{{ __('Amount') }}</label>
                                                    <div class="input-group col-md-12">
                                                        <div class="input-group-text">
                                                            {{ isset($company_payment_setting['currency_symbol']) ? $company_payment_setting['currency_symbol'] : '$' }}
                                                        </div>
                                                        <input class="form-control" required="required" min="0"
                                                            name="amount" type="number"
                                                            value="{{ $bill->due_amount }}" min="0"
                                                            step="0.01" max="{{ $bill->due_amount }}"
                                                            id="amount">
                                                        <input type="hidden" value="{{ $bill->id }}"
                                                            name="invoice_id">
                                                    </div>
                                                    @error('amount')
                                                        <span class="invalid-amount text-danger text-xs"
                                                            role="alert">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-12 form-group mt-3 text-end">
                                                    <input type="submit" value="{{ __('Make Payment') }}"
                                                        class="btn btn-print-invoice  btn-primary m-r-10">
                                                </div>
                                            </div>
                                        </form>


                                    </div>
                                @endif
                            @endif

                            @if (isset($company_payment_setting['is_paytr_enabled']) && $company_payment_setting['is_paytr_enabled'] == 'on')
                                @if (isset($company_payment_setting['paytr_merchant_id']) &&
                                    !empty($company_payment_setting['paytr_merchant_id']) &&
                                    (isset($company_payment_setting['paytr_merchant_key']) && !empty($company_payment_setting['paytr_merchant_key'])) &&
                                    (isset($company_payment_setting['paytr_merchant_salt']) && !empty($company_payment_setting['paytr_merchant_salt'])))
                                    <div class="tab-pane fade" id="paytr-payment" role="tabpanel"
                                        aria-labelledby="paytr-payment">

                                        <form class="w3-container w3-display-middle w3-card-4 " method="POST"
                                            id="payment-form" action="{{ route('invoice.with.paytr', $bill->id) }}">
                                            @csrf
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="amount"
                                                        class="col-form-label">{{ __('Amount') }}</label>
                                                    <div class="input-group col-md-12">
                                                        <div class="input-group-text">
                                                            {{ isset($company_payment_setting['currency_symbol']) ? $company_payment_setting['currency_symbol'] : '$' }}
                                                        </div>
                                                        <input class="form-control" required="required" min="0"
                                                            name="amount" type="number"
                                                            value="{{ $bill->due_amount }}" min="0"
                                                            step="0.01" max="{{ $bill->due_amount }}"
                                                            id="amount">
                                                        <input type="hidden" value="{{ $bill->id }}"
                                                            name="invoice_id">
                                                    </div>
                                                    @error('amount')
                                                        <span class="invalid-amount text-danger text-xs" role="alert">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-12 form-group mt-3 text-end">
                                                    <input type="submit" value="{{ __('Make Payment') }}"
                                                        class="btn btn-print-invoice  btn-primary m-r-10">
                                                </div>
                                            </div>
                                        </form>


                                    </div>
                                @endif
                            @endif

                            @if (isset($company_payment_setting['is_yookassa_enabled']) && $company_payment_setting['is_yookassa_enabled'] == 'on')
                                @if (isset($company_payment_setting['yookassa_shop_id']) &&
                                !empty($company_payment_setting['yookassa_shop_id']) &&
                                (isset($company_payment_setting['yookassa_secret']) && !empty($company_payment_setting['yookassa_secret'])))
                                    <div class="tab-pane fade" id="yookassa-payment" role="tabpanel"
                                        aria-labelledby="yookassa-payment">

                                        <form class="w3-container w3-display-middle w3-card-4 " method="POST"
                                            id="payment-form" action="{{ route('invoice.with.yookassa', $bill->id) }}">
                                            @csrf
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="amount"
                                                        class="col-form-label">{{ __('Amount') }}</label>
                                                    <div class="input-group col-md-12">
                                                        <div class="input-group-text">
                                                            {{ isset($company_payment_setting['currency_symbol']) ? $company_payment_setting['currency_symbol'] : '$' }}
                                                        </div>
                                                        <input class="form-control" required="required" min="0"
                                                            name="amount" type="number"
                                                            value="{{ $bill->due_amount }}" min="0"
                                                            step="0.01" max="{{ $bill->due_amount }}"
                                                            id="amount">
                                                        <input type="hidden" value="{{ $bill->id }}"
                                                            name="invoice_id">
                                                    </div>
                                                    @error('amount')
                                                        <span class="invalid-amount text-danger text-xs"
                                                            role="alert">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-12 form-group mt-3 text-end">
                                                    <input type="submit" value="{{ __('Make Payment') }}"
                                                        class="btn btn-print-invoice  btn-primary m-r-10">
                                                </div>
                                            </div>
                                        </form>


                                    </div>
                                @endif
                            @endif

                            @if (isset($company_payment_setting['is_midtrans_enabled']) && $company_payment_setting['is_midtrans_enabled'] == 'on')
                                @if (isset($company_payment_setting['midtrans_secret']) && !empty($company_payment_setting['midtrans_secret']))
                                    <div class="tab-pane fade" id="midtrans-payment" role="tabpanel"
                                        aria-labelledby="midtrans-payment">

                                        <form class="w3-container w3-display-middle w3-card-4 " method="POST"
                                            id="payment-form" action="{{ route('invoice.with.midtrans', $bill->id) }}">
                                            @csrf
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="amount"
                                                        class="col-form-label">{{ __('Amount') }}</label>
                                                    <div class="input-group col-md-12">
                                                        <div class="input-group-text">
                                                            {{ isset($company_payment_setting['currency_symbol']) ? $company_payment_setting['currency_symbol'] : '$' }}
                                                        </div>
                                                        <input class="form-control" required="required" min="0"
                                                            name="amount" type="number"
                                                            value="{{ $bill->due_amount }}" min="0"
                                                            step="0.01" max="{{ $bill->due_amount }}"
                                                            id="amount">
                                                        <input type="hidden" value="{{ $bill->id }}"
                                                            name="invoice_id">
                                                    </div>
                                                    @error('amount')
                                                        <span class="invalid-amount text-danger text-xs"
                                                            role="alert">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-12 form-group mt-3 text-end">
                                                    <input type="submit" value="{{ __('Make Payment') }}"
                                                        class="btn btn-print-invoice  btn-primary m-r-10">
                                                </div>
                                            </div>
                                        </form>


                                    </div>
                                @endif
                            @endif

                            @if (isset($company_payment_setting['is_xendit_enabled']) && $company_payment_setting['is_xendit_enabled'] == 'on')
                                @if (isset($company_payment_setting['xendit_api']) && !empty($company_payment_setting['xendit_api']))
                                    <div class="tab-pane fade" id="xendit-payment" role="tabpanel"
                                        aria-labelledby="xendit-payment">

                                        <form class="w3-container w3-display-middle w3-card-4 " method="POST"
                                            id="payment-form" action="{{ route('invoice.with.xendit', $bill->id) }}">
                                            @csrf
                                            <div class="row">
                                                <div class="form-group col-md-12">
                                                    <label for="amount"
                                                        class="col-form-label">{{ __('Amount') }}</label>
                                                    <div class="input-group col-md-12">
                                                        <div class="input-group-text">
                                                            {{ isset($company_payment_setting['currency_symbol']) ? $company_payment_setting['currency_symbol'] : '$' }}
                                                        </div>
                                                        <input class="form-control" required="required" min="0"
                                                            name="amount" type="number"
                                                            value="{{ $bill->due_amount }}" min="0"
                                                            step="0.01" max="{{ $bill->due_amount }}"
                                                            id="amount">
                                                        <input type="hidden" value="{{ $bill->id }}"
                                                            name="invoice_id">
                                                    </div>
                                                    @error('amount')
                                                        <span class="invalid-amount text-danger text-xs"
                                                            role="alert">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-12 form-group mt-3 text-end">
                                                    <input type="submit" value="{{ __('Make Payment') }}"
                                                        class="btn btn-print-invoice  btn-primary m-r-10">
                                                </div>
                                            </div>
                                        </form>


                                    </div>
                                @endif
                            @endif

                            @if (
                                isset($company_payment_setting['is_payhere_enabled']) &&
                                $company_payment_setting['is_payhere_enabled'] == 'on' &&
                                isset($company_payment_setting['merchant_id']) &&
                                !empty($company_payment_setting['merchant_id']) &&
                                isset($company_payment_setting['merchant_secret']) &&
                                !empty($company_payment_setting['merchant_secret']) &&
                                isset($company_payment_setting['payhere_app_id']) &&
                                !empty($company_payment_setting['payhere_app_id']) &&
                                isset($company_payment_setting['payhere_app_secret']) &&
                                !empty($company_payment_setting['payhere_app_secret'])
                            )
                                <div class="tab-pane fade" id="payhere-payment" role="tabpanel"
                                    aria-labelledby="payhere-payment">
                                    <form class="w3-container w3-display-middle w3-card-4 " method="POST"
                                        id="payment-form" action="{{ route('invoice.payhere.payment', $bill->id) }}">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-md-12">
                                                <label for="amount"
                                                    class="col-form-label">{{ __('Amount') }}</label>
                                                <div class="input-group col-md-12">
                                                    <div class="input-group-text">
                                                        {{ isset($company_payment_setting['currency_symbol']) ? $company_payment_setting['currency_symbol'] : '$' }}
                                                    </div>
                                                    <input class="form-control" required="required" min="0"
                                                        name="amount" type="number"
                                                        value="{{ $bill->due_amount }}" min="0"
                                                        step="0.01" max="{{ $bill->due_amount }}"
                                                        id="amount">
                                                    <input type="hidden" value="{{ $bill->id }}"
                                                        name="invoice_id">
                                                </div>
                                                @error('amount')
                                                    <span class="invalid-amount text-danger text-xs"
                                                        role="alert">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-12 form-group mt-3 text-end">
                                                <input type="submit" value="{{ __('Make Payment') }}"
                                                    class="btn btn-print-invoice  btn-primary m-r-10">
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            @endif




                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script-page')

<script src="https://js.stripe.com/v3/"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.2.61/jspdf.debug.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
<script>
    $(document).ready(function() {
            $('.numbers').each(function() {
                var el = $(this).parent();
                var cost = $(el.find('.numbers')).html();
                var numbers = $(el.find('.cost')).html();
                var tax = $(el.find('.tax-rate')).html();

                var totalItemPrice = (numbers * cost);
                totalItemPrice = totalItemPrice + totalItemPrice * tax / 100;


                $(el.find('.amount')).html(totalItemPrice.toFixed(2));
            });

        })

        @if (
            !empty($company_payment_setting) &&
                $company_payment_setting['is_stripe_enabled'] == 'on' &&
                !empty($company_payment_setting['stripe_key']) &&
                !empty($company_payment_setting['stripe_secret']))

            var stripe = Stripe('{{ $company_payment_setting['stripe_key'] }}');
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
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                stripe.createToken(card).then(function(result) {
                    if (result.error) {
                        $("#card-errors").html(result.error.message);
                        show_toastr('error', result.error.message, 'error');
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

        var doc = new jsPDF();
        var specialElementHandlers = {
            '#editor': function(element, renderer) {
                return true;
            }
        };

        $('#download').on('click',function() {
            doc.fromHTML($('#printableArea2').html(), 15, 15, {
                'width': 170,
                'elementHandlers': specialElementHandlers
            });
            doc.save('Bill-{{ $bill->bill_number }}.pdf');
        });

        @if (isset($company_payment_setting['is_paystack_enabled']) && $company_payment_setting['is_paystack_enabled'] == 'on')
            $(document).on("click", "#pay_with_paystack", function() {
                $('#paystack-payment-form').ajaxForm(function(res) {
                    var amount = res.total_price;
                    if (res.flag == 1) {


                        var handler = PaystackPop.setup({
                            key: '{{ $company_payment_setting['paystack_public_key'] }}',
                            email: res.email,
                            amount: res.total_price * 100,
                            currency: res.currency,
                            ref: 'pay_ref_id' + Math.floor((Math.random() * 1000000000) +
                                1
                            ),
                            metadata: {
                                custom_fields: [{
                                    display_name: "Email",
                                    variable_name: "email",
                                    value: res.email,
                                }]
                            },

                            callback: function(response) {
                                window.location.href =
                                    '{{ url('/invoice/paystack') }}' + '/' +
                                    '{{ \Illuminate\Support\Facades\Crypt::encrypt($bill->id) }}' +
                                    '/' + amount + '/' + response.reference;

                            },
                            onClose: function() {
                                alert('window closed');
                            }
                        });
                        handler.openIframe();
                    } else if (res.flag == 2) {
                        show_toastr('error', res.msg, 'msg');
                    } else {
                        show_toastr('error', res.message, 'msg');
                    }

                }).trigger('submit');
            });
        @endif

        @php
            if (!empty($bill->advocate)) {
                $advocate = App\Models\User::find($bill->advocate);
                $email = $advocate->email;
            } else {
                $email = $bill->custom_email;
            }
        @endphp

        @if (isset($company_payment_setting['is_flutterwave_enabled']) &&
                $company_payment_setting['is_flutterwave_enabled'] == 'on')
            // Flaterwave Payment
            $(document).on("click", "#pay_with_flaterwave", function() {
                $('#flaterwave-payment-form').ajaxForm(function(res) {

                    if (res.flag == 1) {

                        var amount = res.total_price;
                        var API_publicKey = '{{ $company_payment_setting['flutterwave_public_key'] }}';
                        var nowTim = "{{ date('d-m-Y-h-i-a') }}";

                        var x = getpaidSetup({
                            PBFPubKey: API_publicKey,
                            customer_email: '{{ $email }}',
                            amount: res.total_price,
                            currency: '{{ $company_payment_setting['site_currency'] }}',
                            txref: nowTim + '__' + Math.floor((Math.random() * 1000000000)) +
                                'fluttpay_online-' + '{{ date('Y-m-d') }}' + '?amount=' + amount,
                            meta: [{
                                metaname: "payment_id",
                                metavalue: "id"
                            }],
                            onclose: function() {},
                            callback: function(response) {
                                var txref = response.tx.txRef;

                                if (
                                    response.tx.chargeResponseCode == "00" ||
                                    response.tx.chargeResponseCode == "0"
                                ) {
                                    window.location.href =
                                        '{{ url('/invoice/flaterwave') }}' + '/' +
                                        '{{ \Illuminate\Support\Facades\Crypt::encrypt($bill->id) }}' +
                                        '/' +
                                        txref



                                } else {

                                    // redirect to a failure page.
                                }
                                x
                                    .close(); // use this to close the modal immediately after payment.
                            }
                        });

                    } else if (res.flag == 2) {
                        show_toastr('error', res.msg, 'msg');
                    } else {
                        show_toastr('error', data.message, 'msg');
                    }

                }).trigger('submit');
            });
        @endif

        @if (isset($company_payment_setting['is_razorpay_enabled']) && $company_payment_setting['is_razorpay_enabled'] == 'on')
            $(document).on("click", "#pay_with_razorpay", function() {
                $('#razorpay-payment-form').ajaxForm(function(res) {
                    if (res.flag == 1) {

                        var razorPay_callback = '{{ url('/invoice/razorpay') }}';
                        var totalAmount = res.total_price * 100;
                        var coupon_id = res.coupon;
                        var options = {
                            "key": "{{ $company_payment_setting['razorpay_public_key'] }}", // your Razorpay Key Id
                            "amount": totalAmount,
                            "name": 'Plan',
                            "currency": res.currency,
                            "description": "",
                            "handler": function(response) {
                                window.location.href = razorPay_callback + '/' + response
                                    .razorpay_payment_id + '/' +
                                    '{{ \Illuminate\Support\Facades\Crypt::encrypt($bill->id) }}?coupon_id=' +
                                    coupon_id + '&payment_frequency=' + res.payment_frequency;
                            },
                            "theme": {
                                "color": "#528FF0"
                            }
                        };
                        var rzp1 = new Razorpay(options);
                        rzp1.open();
                    } else {
                        show_toastr('error', res.msg, 'msg');
                    }

                }).trigger('submit');
            });
        @endif


        @if ($bill->due_amount > 0 && isset($company_payment_setting['is_payfast_enabled']) && $company_payment_setting['is_payfast_enabled'] == 'on')
            $(".input_payfast").keyup(function() {
                var invoice_amount = $('#amount').val();
                get_payfast_status(invoice_amount);
            });

            $(document).ready(function() {
                get_payfast_status(amount = 0);
            })

            function get_payfast_status(amount) {

                var invoice_id = $('#invoice_id').val();
                var invoice_amount = $('#amount').val();

                $.ajax({
                    url: '{{ route('invoice.with.payfast') }}',
                    method: 'POST',
                    data: {
                        'invoice_id': invoice_id,
                        'amount': invoice_amount,

                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {

                        if (data.success == true) {
                            $('#get-payfast-inputs').append(data.inputs);

                        } else {
                            show_toastr('Error', data.inputs, 'error')
                        }
                    }
                });
            }
        @endif


</script>

@endpush
