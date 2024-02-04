<div class="modal-body">
<div class="table-responsive">
    <table class="table dataTable data-table ">
        <thead>
            <tr>
                <th>{{ __('#') }}</th>
                <th>{{ __('Full Name') }}</th>
                <th>{{ __('Email Address') }}</th>
                <th>{{ __('Phone Number') }}</th>
                <th>{{ __('Designation') }}</th>
            </tr>
        </thead>
        <tbody>


            @foreach ($contacts as $key => $val)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $val->contact_name }}</td>
                    <td>{{ $val->contact_email }}</td>
                    <td>{{ $val->contact_phone }}</td>
                    <td>{{ $val->contact_designation }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
