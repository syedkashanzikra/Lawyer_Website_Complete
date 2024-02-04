

<div class="modal-body">
    <div class="table-responsive">
        <table class="table dataTable1 table modal-table">
            <tr>
                <th>{{__('Invoice Id ')}}</th>
                <td>{{$banktransfer->invoice_id }}</td>
            </tr>
            <tr>
                <th>{{__('Order Id')}}</th>
                <td>{{ $banktransfer->order_id }}</td>
            </tr>
            <tr>
                <th>{{__('Amount')}}</th>
                <td>{{ $banktransfer->amount }}</td>
            </tr>
            <tr>
                <th>{{__('Payment Status')}}</th>
                <td>{{ $banktransfer->status }}</td>
            </tr>
            @if(isset($payment_setting['bank_details']) && !empty($payment_setting['bank_details']))
            <tr>
                <th>{{__('Bank Details')}}</th>
                <td>{!! $payment_setting['bank_details'] !!}</td>
            </tr>
            @endif
            <tr>
                <th>{{__('Payment Receipt')}}</th>
                <td>
                    <div class="action-btn bg-primary p-0 w-auto">
                        <a href="{{ \App\Models\Utility::get_file($banktransfer->receipt) }}"
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
{{ Form::open(['route' => ['invoice.status',$banktransfer->id],'method' => 'post']) }}
<div class="modal-footer pr-0">
   <input type="submit" value="{{__('Approval')}}" class="btn btn-primary" data-bs-dismiss="modal" name="status">
    <input type="submit" value="{{__('Reject')}}" class="btn btn-danger" name="status">
</div>
{{Form::close()}}
