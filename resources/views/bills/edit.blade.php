@extends('layouts.app')

@section('page-title', __('Edit Bill'))

@section('action-button')
<div class="text-sm-end d-flex all-button-box justify-content-sm-end">
    <a href="{{ route('bills.show', $bill->id) }}" class="btn btn-sm btn-primary mx-1" data-toggle="tooltip"
        title="{{ __('Create New Bill') }}">
        {{ __('View') }}
    </a>
</div>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">{{ __('Edit Bill') }}</li>
@endsection

@php
    use App\Models\Utility;
    $settings = Utility::getCompanyPaymentSetting(Auth::user()->id);

@endphp
@section('content')

<div class="row g-0 p-0">
    <div class="col-12">
        <div class="p-3">
            <div>
                <div class="card shadow-none bg-transparent border rounded-0 mb-3">
                    <div class="card-body">
                        {{ Form::model($bill,['route' => ['bills.update',$bill->id], 'class' =>
                        'w-100','method'=>'put']) }}
                        <div class="col-12">
                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                    <div class="form-group" id="customer-box">
                                        {{ Form::label('customer_id', __('Bill From:'), ['class' => 'form-label']) }}

                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="bill_from" id="company" value="company" class="form-check-input" {{ $bill['bill_from'] == 'company' ?  'checked' : ''}}>
                                            {{ Form::label('company', __('Company'), ['class' => 'form-label']) }}
                                        </div>

                                        <div class="form-check form-check-inline">
                                            <input type="radio" name="bill_from" id="advocate" value="advocate" class="form-check-input" {{ $bill['bill_from'] == 'advocate' ?  'checked' : ''}}>
                                            {{ Form::label('advocate', __('Advocate'), ['class' => 'form-label']) }}
                                        </div>
                                    </div>

                                    <div class="form-group" id="advocate_div">
                                        {{ Form::label('advocate', __('Advocate'), ['class' => 'form-label']) }}
                                        {{ Form::select('advocate', $advocates, $bill->advocate, ['class' => 'form-control select2 item','id'=>'selected-advocate']) }}
                                    </div>
                                    <div id="advocate_detail" class="d-none form-group"></div>
                                    <input type="hidden" name="company" value="{{Utility::getcompanyValByName('id')}}">
                                    <div class="form-group  " id="custom_advocate_div">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label class="col-3 mb-2">{{__('Name:')}}</label>
                                                <span>{{Utility::getcompanyValByName('name')}}</span><br>
                                                <label class="col-3 mb-2">{{__('Phone Number:')}}</label>
                                                <span>{{Utility::getcompanydetailValByName('phone_number')}}</span><br>
                                                <label class="col-3 mb-2">{{__('Address:')}} </label>
                                                <span> {{Utility::getcompanydetailValByName('ofc_address_line_1') }}</span>
                                        </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                    <div class="form-group" id="customer-box">
                                        {{ Form::label('customer_id', __('Bill To: '), ['class' => 'form-label']) }}

                                    </div>

                                    <div class="form-group" >
                                        {{ Form::label('clients', __('Clients'), ['class' => 'form-label']) }}
                                        {{ Form::select('client', $clients, $bill->bill_to, ['class' => 'form-control multi-select item','id'=>'clients']) }}

                                    </div>

                                    <div id="client_detail" class="d-none form-group"></div>

                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                                    <div class="row flex-column items-end align-items-end">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{ Form::label('title', __('Title'), ['class' => 'form-label']) }}
                                                <div class="form-icon-user">
                                                    <span><i class="ti ti-joint"></i></span>
                                                    {{ Form::text('title', null, ['class' => 'form-control']) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                {{ Form::label('bill_number', __('Bill Number'), ['class' =>
                                                'form-label']) }}
                                                <div class="form-icon-user">
                                                    {{ Form::text('bill_number', null, ['class' =>
                                                    'form-control','readonly']) }}

                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-12 row ps-0">
                                            <div class="col-md-6 ps-1">
                                                <div class="form-group">
                                                    {{ Form::label('reciept_date', __('Date of Receipt'), ['class' =>
                                                    'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::date('reciept_date', null, ['class' => 'form-control']) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 ps-1">
                                                <div class="form-group">
                                                    {{ Form::label('due_date', __('Due Date'), ['class' =>
                                                    'form-label']) }}
                                                    <div class="form-icon-user">
                                                        {{ Form::date('due_date', null, ['class' => 'form-control']) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card repeater shadow-none border" data-value='{!! $bill->items !!}'>
                                    <div class="item-section py-2">
                                        <div class="row ">
                                            <div
                                                class="col-md-12 d-flex items-end justify-content-end">
                                                <div class="all-button-box me-2">
                                                    <a href="#" data-repeater-create="" class="btn btn-primary"
                                                        data-bs-toggle="modal" data-target="#add-bank">
                                                        <i class="ti ti-plus"></i> {{ __('Add item') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body table-border-style mt-2 ">
                                        <div class="table-responsive">
                                            <table class="table  mb-0 table-custom-style" data-repeater-list="items"
                                                id="sortable-table">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('Particulars') }}</th>
                                                        <th>{{ __('Numbers') }}</th>
                                                        <th>{{ __('Rate/Unit Cost ').'(' .$settings['site_currency'].')' }} </th>
                                                        <th>{{ __('Discount') .'(' .$settings['site_currency'].')'  }} </th>
                                                        <th>{{ __('Tax') }} (%)</th>
                                                        <th class="text-end">{{ __('Amount') }} <br><small
                                                                class="text-danger font-weight-bold">{{ __('tax
                                                                included') }}</small>
                                                        </th>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>

                                                <tbody class="ui-sortable" data-repeater-item>
                                                    <tr>
                                                        {{ Form::hidden('id', null, ['class' => 'form-control id']) }}


                                                        <td width="25%" class="form-group pt-0">
                                                            {{ Form::text('particulars', null, ['class' => 'form-control
                                                            particulars', 'required' => 'required']) }}

                                                        </td>
                                                        <td>
                                                            <div class="form-group price-input input-group search-form">
                                                                {{ Form::number('numbers', '', ['class' => 'form-control
                                                                numbers', 'required' => 'required', 'required' =>
                                                                'required']) }}

                                                            </div>
                                                        </td>


                                                        <td>
                                                            <div class="form-group price-input input-group search-form">
                                                                {{ Form::number('cost', '', ['class' => 'form-control
                                                                cost', 'required' => 'required', 'placeholder' =>
                                                                __('Price'), 'required' => 'required']) }}

                                                            </div>
                                                        </td>

                                                        <td width="10%">
                                                            <div class="form-group price-input input-group search-form">
                                                                {{ Form::number('discount', '', ['class' => 'form-control discount', 'required' => 'required', 'placeholder' => __('Discount'), 'required' => 'required']) }}
                                                            </div>
                                                        </td>

                                                        <td>
                                                            <div class="form-group price-input input-group search-form">
                                                                {{ Form::select('tax', $taxes,'', ['class' =>
                                                                'form-control ptax ', 'required' => 'required']) }}

                                                            </div>
                                                        </td>

                                                        <td class="text-end amount">0.00</td>
                                                        <td>
                                                            <a href="#"
                                                                class="ti ti-trash text-white repeater-action-btn bg-danger ms-2 bs-pass-para"
                                                                data-repeater-delete></a>
                                                        </td>
                                                        <td></td>
                                                    </tr>

                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td><strong>{{ __('Sub Total') }} </strong>
                                                        </td>
                                                        <td class="text-end subTotal">0.00</td>
                                                        <td></td>

                                                    </tr>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td><strong>{{ __('Tax') }} </strong></td>
                                                        <td class="text-end totalTax">0.00</td>
                                                        <td></td>
                                                    </tr>

                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td><strong>{{ __('Discount') }} </strong>
                                                        </td>
                                                        <td class="text-end TotalDiscount">0.00</td>
                                                        <td></td>

                                                    </tr>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td class="blue-text">
                                                            <strong>{{ __('Total Amount') }}</strong>
                                                        </td>
                                                        <td class="text-end totalAmount blue-text"></td>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <div class="form-group">
                                                                {{ Form::textarea('description', null, ['class' =>
                                                                'form-control', 'rows' => '2', 'placeholder' =>
                                                                __('Description')]) }}
                                                            </div>
                                                        </td>
                                                        <td colspan="5"></td>
                                                    </tr>
                                                    <input type="hidden" name="subtotal" id="subtotal">
                                                    <input type="hidden" name="total_tax" id="total_tax">
                                                    <input type="hidden" name="total_disc" id="total_disc">
                                                    <input type="hidden" name="total_amount" id="total_amount">
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer mt-3">
                            <a href="{{ url('/bills') }}" class="btn btn-light"> {{ __('Cancel') }} </a>
                            <input type="submit" value="{{ __('Update') }}" class="btn btn-primary">
                        </div>
                        {{ Form::close() }}

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('custom-script')
<script>

    $(document).ready(function() {
            $('.subTotal').html('{{$bill->subtotal}}')
            $('.totalAmount').html('{{$bill->total_amount}}')
            $('.totalTax').html('{{$bill->total_tax}}')
            $('.TotalDiscount').html('{{$bill->total_disc}}')

            bill_select();
            $('#clients').trigger('change');


        })
        $('input[name="bill_from"]').on('click', function() {
            bill_select()

        })

        function bill_select() {
            $('input[name="bill_from"]:checked').each(function() {
                if (this.value == 'company') {
                    $('#advocate_detail').addClass('d-none')
                    $('#advocate_div').addClass('d-none')
                    $('#custom_advocate_div').removeClass('d-none')
                } else {
                    $('#custom_advocate_div').addClass('d-none')
                    $('#advocate_div').removeClass('d-none')
                    $('#selected-advocate').trigger('change');
                }
                console.log(this.value);
            });
        }
</script>
<script>
    $(document).on('click', '[data-repeater-delete]', function() {
            $(".price").change();
            $(".discount").change();
        });
</script>
<script src="{{ asset('assets/js/jquery-ui.js') }}"></script>
<script src="{{ asset('assets/js/jquery.repeater.js') }}"></script>
<script>
    var selector = "body";
        if ($(selector + " .repeater").length) {
            var $dragAndDrop = $("body .repeater tbody").sortable({
                handle: '.sort-handler'
            });
            var $repeater = $(selector + ' .repeater').repeater({
                initEmpty: false,
                defaultValues: {
                    'status': 1
                },
                show: function() {
                    $(this).slideDown();
                    var file_uploads = $(this).find('input.multi');
                    if (file_uploads.length) {
                        $(this).find('input.multi').MultiFile({
                            max: 3,
                            accept: 'png|jpg|jpeg',
                            max_size: 2048
                        });
                    }



                },
                hide: function(deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        $(this).slideUp(deleteElement);
                        $(this).remove();

                        var inputs = $(".amount");
                        var subTotal = 0;
                        for (var i = 0; i < inputs.length; i++) {
                            subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                        }
                        $('.subTotal').html(subTotal.toFixed(2));
                        $('.totalAmount').html(subTotal.toFixed(2));
                    }

                },
                ready: function(setIndexes) {

                    $dragAndDrop.on('drop', setIndexes);
                },
                isFirstItemUndeletable: true
            });

            var value = $(selector + " .repeater").attr('data-value');

            if (typeof value != 'undefined' && value.length != 0) {

                value = JSON.parse(value);
                $repeater.setList(value);

                for (var i = 0; i < value.length; i++) {
                    var tr = $('#sortable-table .id[value="' + value[i].id + '"]').parent();
                    add_tax(tr.find('.ptax'));
                }
            }
        }

        $(document).on('click', '[data-repeater-create]', function() {
            $('.ptax').each(function(ind,obj){

                $(this).val($(".ptax option:first").val());

            })
        })

        $(document).on('keyup', '.numbers', function () {
            var el = $(this).parent().parent().parent();

            if(!el.find('.particulars').val()){

                if(el.find('.particulars').parent().find('small').length == 0){
                    el.find('.particulars').parent().append('<small class="text-danger">{{__('Please Enter Value')}}</small>');
                }

            }else{
                el.find('.particulars').parent().find('small').remove()
            }

            add_tax(el.find('.ptax'))
        });

        $(document).on('keyup', '.cost', function () {
            var el = $(this).parent().parent().parent();

            if(!el.find('.particulars').val()){

                if(el.find('.particulars').parent().find('small').length == 0){
                    el.find('.particulars').parent().append('<small class="text-danger">{{__('Please Enter Value')}}</small>');
                }

            }else{
                el.find('.particulars').parent().find('small').remove()
            }


            add_tax(el.find('.ptax'))

        });

        $(document).on('keyup', '.discount', function () {
            var el = $(this).parent().parent().parent();

            if(!el.find('.particulars').val()){
                if(el.find('.particulars').parent().find('small').length == 0){
                    el.find('.particulars').parent().append('<small class="text-danger">{{__('Please Enter Value')}}</small>');
                }
            }else{
                el.find('.particulars').parent().find('small').remove()
            }

            add_tax(el.find('.ptax'))

        });

        $(document).on('change', '.ptax', function () {
            add_tax($(this));
        });

        function add_tax(taxbox){
            var selected = taxbox.val();
            var el = taxbox.parent().parent().parent();

            if (selected > 0) {
                $.ajax({
                    url: "{{ route('get.tax') }}",
                    type: "POST",
                    data: {
                        selected: selected,
                        _token: "{{ csrf_token() }}",
                    },
                    dataType: "json",
                    success: function(result) {
                        var tr_total = $(el.find('.amount')).html();
                        var tax_cal = (tr_total * result.rate) / 100;
                        var included_tax = parseFloat(tr_total) + parseFloat(tax_cal);


                        var cost = $(el.find('.numbers')).val();
                        var numbers = $(el.find('.cost')).val();
                        var discount = $(el.find('.discount')).val();
                        var totalItemPrice = (numbers * cost) ;

                        totalItemPrice = totalItemPrice + totalItemPrice * result.rate / 100;
                        totalItemPrice = totalItemPrice - discount;

                        $(el.find('.amount')).html(totalItemPrice.toFixed(2));

                        var totol_amount = 0;
                        var inputs_quantity = $(".cost");
                        var priceInput = $('.numbers');
                        var discInput = $('.discount');
                        var total_tax = 0;
                        var totalDisc = 0;


                        for (var j = 0; j < priceInput.length; j++) {
                            totol_amount += (parseFloat(priceInput[j].value) * parseFloat(inputs_quantity[j].value));
                            total_tax = totol_amount * result.rate / 100

                            totalDisc = totalDisc + parseFloat(discInput[j].value);
                        }

                        $('.totalAmount').html((parseFloat(totol_amount) + parseFloat(total_tax)).toFixed(2) - totalDisc);
                        $('.totalTax').html(total_tax)
                        $('.subTotal').html(totol_amount)
                        $('.TotalDiscount').html(totalDisc)

                        $('#total_amount').val((parseFloat(totol_amount) + parseFloat(total_tax)).toFixed(2) - totalDisc);
                        $('#total_tax').val(total_tax)
                        $('#subtotal').val(totol_amount)
                        $('#total_disc').val(totalDisc);

                    },
                });
            }
        }
</script>
<script>
    $('#clients').on('change',function(){
        var client_id = this.value;
        $.ajax({
            url: "{{ route('get.client.detail') }}",
            type: "POST",
            data: {
                client_id: client_id,
                _token: "{{ csrf_token() }}",
            },
            dataType: "json",
            success: function(result) {
                if (result.success == true) {
                    $('#client_detail').removeClass('d-none').html(result.html)
                }

            },
        });
    })
    $('#selected-advocate').on('change',function(){
        var avd_id = this.value;
        $.ajax({
            url: "{{ route('get.advocate.detail') }}",
            type: "POST",
            data: {
                avd_id: avd_id,
                _token: "{{ csrf_token() }}",
            },
            dataType: "json",
            success: function(result) {
                if (result.success == true) {
                    $('#advocate_detail').removeClass('d-none').html(result.html)
                }

            },
        });
    })
</script>
@endpush
