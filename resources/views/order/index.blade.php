@extends('layouts.app')

@section('page-title')
{{__('Manage Orders')}}
@endsection

@section('breadcrumb')

<li class="breadcrumb-item active" aria-current="page">{{__('Orders')}}</li>
@endsection

@section('content')
<div class="row pt-0">

    <div class="col-xl-12">
        <div class="">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table dataTable">
                        <thead>
                            <tr>
                                <th> {{__('Order Id')}}</th>
                                <th> {{__('Date')}}</th>
                                <th> {{__('Name')}}</th>
                                <th> {{__('Plan Name')}}</th>
                                <th> {{__('Price')}}</th>
                                <th> {{__('Payment Type')}}</th>
                                <th> {{__('Status')}}</th>
                                <th> {{__('Coupon')}}</th>
                                <th width="100px" class="text-center"> {{__('Invoice')}}</th>
                                <th width="100px" class="text-center"> {{__('Action')}}</th>

                            </tr>
                        </thead>

                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>
                                    {{$order->order_id}}
                                </td>
                                <td>{{$order->created_at->format('d M Y')}}</td>
                                <td>{{$order->user_name}}</td>
                                <td>{{$order->plan_name}}</td>
                                <td>$ {{number_format($order->price)}}
                                </td>
                                <td>{{$order->payment_type}}</td>
                                <td>
                                    @if($order->payment_status == 'succeeded')
                                    <span class="">
                                        <span class="ms-1">{{ucfirst($order->payment_status)}}</span>
                                    </span>

                                    @else
                                    <span class="d-flex align-items-center">
                                        <span class="ms-1">{{ucfirst($order->payment_status)}}</span>
                                    </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{!empty($order->use_coupon)?$order->use_coupon->coupon_detail->code:''}}
                                </td>
                                <td class="text-center">
                                    @if($order->receipt != 'free coupon' && $order->payment_type == 'STRIPE')
                                    <a href="{{$order->receipt}}" title="Invoice" target="_blank" class="btn btn-sm btn-outline-primary"><i
                                            class="fas fa-file-invoice"></i> </a>
                                    @elseif($order->receipt =='free coupon')
                                    <p>{{__('Used 100 % discount coupon code.')}}</p>
                                    @elseif($order->payment_type == 'Manually')
                                    <p>{{__('Manually plan upgraded by super admin')}}</p>
                                    @elseif ($order->payment_type == 'Bank Transfer')
                                    <a href="{{ \App\Models\Utility::get_file($order->receipt) }}" class="btn btn-sm btn-outline-primary"
                                        target="_blank"><i class="fas fa-file-invoice"></i></a>
                                    @else
                                    -
                                    @endif
                                </td>
                                <td>
                                    @if ($order->payment_status == 'Pending' && $order->payment_type == 'Bank Transfer')
                                    <div class="action-btn bg-light-secondary ms-2">
                                        <a href="#" data-size="lg" data-url="{{ route('order.show', $order->id) }}"
                                            data-bs-toggle="tooltip" title="{{ __('Details') }}" data-ajax-popup="true"
                                            data-title="{{ __('Payment Status') }}"
                                            class="mx-3 btn btn-sm d-inline-flex align-items-center text-white">
                                            <i class="ti ti-caret-right text-dark"></i>
                                        </a>
                                    </div>
                                    @endif

                                    <div class="action-btn bg-light-secondary ms-2">

                                        <a href="#!" class="mx-3 btn btn-sm align-items-center bs-pass-para "  data-confirm="{{ __('Are You Sure?') }}"
                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}" data-confirm-yes="delete-form-{{ $order->id }}">
                                            <i class="ti ti-trash" data-bs-toggle="tooltip"
                                                data-bs-original-title="{{ __('Delete') }}"></i>
                                        </a>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['bank_transfer.destroy',$order->id],'id' => 'delete-form-' . $order->id]) !!}
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
    </div>
</div>

@endsection
