<div class="modal-body">
<div class="table-responsive">
    <table class="table dataTable data-table">
        <thead>
            <tr>
                <th>{{ __('Bill Number') }}</th>
                <th>{{ __('Bill From') }}</th>
                <th>{{ __('Date Of Reciept') }}</th>
                <th>{{ __('Status') }}</th>

            </tr>
        </thead>
        <tbody>

            @foreach ($bills as $bill)
                <tr>
                    <td> {{ $bill->bill_number }} </td>
                    <td> {{ $bill->bill_from }} </td>
                    <td> {{ $bill->due_date }} </td>
                    <td> {{ $bill->status }} </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
