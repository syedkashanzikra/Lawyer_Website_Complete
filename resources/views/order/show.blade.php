<div class="modal-body">
    <div class="table-responsive">
        <table class="table dataTable table modal-table">
            <tr>
                <th>{{__('Order Id')}}</th>
                <td>{{ $order->order_id }}</td>
            </tr>
            <tr>
                <th>{{__('Plan Name ')}}</th>
                <td>{{$order->plan_name }}</td>
            </tr>
            <tr>
                <th>{{__('Plan Price')}}</th>
                <td>{{$order->price }}</td>
            </tr>
            <tr>
                <th>{{__('Payment Type')}}</th>
                <td>{{ $order->payment_type }}</td>
            </tr>
            <tr>
                <th>{{__('Payment Status')}}</th>
                <td>{{ $order->payment_status }}</td>
            </tr>
            <tr>
                <th>{{__('Bank Details')}}</th>
                <td>{!! $admin_payment_setting['bank_details'] !!}</td>
            </tr>
            <tr>
                <th>{{__('Payment Receipt')}}</th>
                <td>
                    <div class="action-btn bg-primary p-0 w-auto">
                        <a href="{{ \App\Models\Utility::get_file($order->receipt) }}"
                            class=" btn btn-sm d-inline-flex align-items-center" download="" data-bs-toggle="tooltip"
                            title="Download">
                            <span class="text-white"><i class="ti ti-download"></i></span>
                        </a>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="modal-footer pr-0">
    <a href="{{ route('order.approve', [$order->id]) }}" class="btn btn-primary">{{ __('Approval') }}</a>
    <a href="{{ route('order.reject', [$order->id]) }}" class="btn btn-danger">{{ __('Reject') }}</a>
</div>
