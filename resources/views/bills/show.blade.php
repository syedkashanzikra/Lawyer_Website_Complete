@extends('layouts.app')

@section('page-title', __('Bills'))

@section('action-button')

    <div class="row justify-content-between align-items-center mb-3">
        <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
            <div class="all-button-box mx-2 mr-2">
                <a href="{{ route('bills.edit', $bill->id) }}" class="btn btn-sm btn-primary" title="{{ __('Edit') }}"
                    data-bs-toggle="tooltip" data-bs-placement="top">
                    {{ __('Edit') }}
                </a>
            </div>

            @if ($bill->status != 'PAID')
                <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
                    <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="md"
                        data-title="{{ __('Bill: ') }} {{ $bill->bill_number }}"
                        data-url="{{ route('create.payment', $bill->id) }}" data-toggle="tooltip"
                        title="{{ __('Add Payment') }}">
                        {{ __('Add Payment') }}
                    </a>
                </div>
            @endif

            <div class="all-button-box mx-2">
                <a data-bs-toggle="tooltip" onclick="saveAsPDF2()" class="btn btn-sm btn-primary " data-bs-placement="top"
                    title="{{ __('Download') }}" href="#!" target="_blanks"
                    data-bs-original-title="{{ __('Download') }}">
                    {{ __('Download') }}
                </a>
            </div>

            <div class="all-button-box ">
                <a href="#" class="btn btn-sm btn-primary  cp_link"
                    data-link="{{ route('pay.invoice', \Illuminate\Support\Facades\Crypt::encrypt($bill->id)) }}"
                    data-bs-toggle="tooltip" title="{{ __('Copy') }}"
                    data-original-title="{{ __('Click to copy invoice link') }}">
                    <span class="btn-inner--icon text-white"><i class="ti ti-file"></i></span>
                </a>
            </div>
        </div>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Bills') }}</li>
@endsection
@php
    $settings = App\Models\Utility::settings();
    $logo=asset('storage/uploads/logo/');
    $company_logo=App\Models\Utility::get_company_logo();
@endphp
@section('content')

    <div class="row" id="printableArea2">
        <div class="col-md-2 col-md-2"></div>
        <div class="col-sm-12 col-md-8 col-md-8  ">
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
                                    <strong>Bill From :</strong><br>
                                    @if ($bill->bill_from == 'advocate')
                                        {{ App\Models\Advocate::getAdvocates($bill->advocate) }}
                                        <br>

                                    @else
                                        {{ $bill->custom_advocate }}
                                        <br>
                                        {{ $bill->custom_address }}
                                    @endif


                                </small>
                            </div>
                            <div class="col ">
                                <small>
                                    <strong>Bill To :</strong><br>
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
                                        <span
                                            class="badge fix_badge rounded p-1 px-3 bg-warning">{{ $bill->status }}</span>
                                    @else
                                        <span
                                            class="badge fix_badge rounded p-1 px-3 bg-success">{{ $bill->status }}</span>
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
                                                <th data-width="40" class="text-dark"> {{ __('#') }} </th>
                                                <th class="text-dark">{{ __('PARTICULARS') }}</th>
                                                <th class="text-dark">{{ __('NUMBERS') }}</th>
                                                <th class="text-dark">
                                                    {{ __('RATE/UNIT COST') . '(' . $settings['site_currency'] . ')' }}</th>
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
                                                        <span
                                                            class="d-none tax-rate">{{ App\Models\Tax::getTax($item['tax'])->rate }}</span>
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
                        <h5>{{ __('Payments') }}</h5>
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
                                        <th> {{ __('Action') }} </th>
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
                                            <td>{{ !empty($payment->transacrion_id) ? $payment->transacrion_id : ' --- ' }} </td>
                                        </tr>
                                    @endforeach
                                    @foreach ($bankPayments as $bankPayment)
                                        <tr>

                                            <td>{{ $bankPayment->date }}</td>
                                            <td class="text-right">
                                                {{ $bankPayment->amount }}</td>
                                            <td>{{ 'Bank Transfer' }}</td>
                                            <td>{{ !empty($bankPayment->notes) ? $bankPayment->notes : '-' }}</td>
                                            <td>
                                                <a href="{{ \App\Models\Utility::get_file($bankPayment->receipt) }}"
                                                    class="btn  btn-outline-primary btn-sm" target="_blank"><i
                                                        class="fas fa-file-invoice"></i>
                                                </a>
                                            </td>

                                            <td>{{ sprintf('%05d', $bankPayment->transaction_id) }}</td>
                                            <td>

                                                @if ($bankPayment->status == 'Pending')
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a href="#" data-size="lg"
                                                            data-url="{{ route('bankpayment.show', $bankPayment->id) }}"
                                                            data-bs-toggle="tooltip" title="{{ __('Details') }}"
                                                            data-ajax-popup="true"
                                                            data-title="{{ __('Payment Status') }}"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center text-white">
                                                            <i class="ti ti-caret-right text-white"></i>
                                                        </a>
                                                    </div>
                                                @endif

                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['invoice.bankpayment.delete', $bankPayment->id],
                                                        'id' => 'delete-form-' . $bankPayment->id,
                                                    ]) !!}
                                                    <a href="#!"
                                                        class="mx-3 btn btn-sm  align-items-center text-white bs-pass-para"
                                                        data-bs-toggle="tooltip"
                                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                        data-confirm-yes="delete-form-{{ $bankPayment->id }}"
                                                        title='Delete'>
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                    {!! Form::close() !!}
                                                </div>
                                            </td>
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

@endsection

@push('custom-script')
    <script src="{{ asset('public/assets/js/html2pdf.bundle.js') }}"></script>
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

        var filename = '#BILL-{{ $bill->bill_number }}';

        function saveAsPDF2() {
            var element = document.getElementById('printableArea2');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {
                    type: 'jpeg',
                    quality: 1
                },
                html2canvas: {
                    scale: 4,
                    dpi: 72,
                    letterRendering: true
                },
                jsPDF: {
                    unit: 'in',
                    format: 'A3'
                }
            };
            html2pdf().set(opt).from(element).save();
        }

        $('.cp_link').on('click', function() {
            var value = $(this).attr('data-link');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(value).select();
            document.execCommand("copy");
            $temp.remove();
            show_toastr('success', '{{ __('Link Copy on Clipboard') }}', 'success')
        });
    </script>
@endpush
