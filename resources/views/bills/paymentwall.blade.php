@php
use App\Models\Utility;
$logo = asset(Storage::url('uploads/logo'));
$company_favicon = Utility::getValByName('company_favicon');
@endphp

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Salesy Saas- Business Sales CRM">
    <meta name="author" content="Rajodiya Infotech">
    <title>
        {{ Utility::getValByName('title_text') ? Utility::getValByName('title_text') : config('app.name', 'SalesGo') }}
        - @yield('page-title')</title>
    <link rel="icon"
        href="{{ $logo . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') }}"
        type="image" sizes="16x16">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
@php
$invoice_id = decrypt($data['invoice_id']);
$invoice = \App\Models\Bill::find($invoice_id);

@endphp

<script src="https://api.paymentwall.com/brick/build/brick-default.1.5.0.min.js"> </script>
<div id="payment-form-container"> </div>
<script>
    var brick = new Brick({
        public_key: '{{ $admin_payment_setting->public_key }}', // please update it to Brick live key before launch your project
        amount: '{{ $data["amount"] }}',
        currency: '{{ $admin_payment_setting->currancy }}',
        container: 'payment-form-container',
        action: '{{ route('invoice-pay-with-paymentwall', [$data['invoice_id'], 'amount' => $data['amount']]) }}',

        form: {
            merchant: 'Paymentwall',
            product: '{{ $invoice->name }}',
            pay_button: 'Pay',
            show_zip: true, // show zip code
            show_cardholder: true // show card holder name
        },
    });
    brick.showPaymentForm(function(data) {

        if (data.flag == 1) {
            window.location.href = '{{ route("error.invoice.show", [1, "invoice_id"]) }}'.replace('invoice_id',data.invoice);
        } else {
            window.location.href = '{{ route('error.invoice.show', [2, 'invoice_id']) }}'.replace('invoice_id',data.invoice);
        }
    }, function(errors) {
        if (errors.flag == 1) {
            window.location.href = '{{ route('error.invoice.show', [1, 'invoice_id']) }}'.replace('invoice_id',
                errors.invoice);
        } else {
            window.location.href = '{{ route('error.invoice.show', [2, 'invoice_id']) }}'.replace('invoice_id',
                errors.invoice);
        }
    });
</script>
