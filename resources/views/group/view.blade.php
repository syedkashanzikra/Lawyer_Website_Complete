<div class="modal-body">
<div class="table-responsive">
    <table class="table dataTable data-table ">
        <thead>
            <tr>
                <th>{{ __('#') }}</th>
                <th>{{ __('Name') }}</th>
            </tr>
        </thead>
        <tbody>


            @foreach ($my_members as $key => $user)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $user->name }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
