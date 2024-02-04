{{ Form::model($lead, ['route' => ['lead.convert.to.deal', $lead->id], 'method' => 'POST']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('name', __('Deal Name'), ['class' => 'col-form-label']) }}
                {{ Form::text('name', $lead->subject, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                {{ Form::label('price', __('Price'), ['class' => 'col-form-label']) }}
                {{ Form::number('price', 0, ['class' => 'form-control', 'min' => 0]) }}
            </div>
        </div>
        <div class="col-sm-12 col-md-12 mb-2">
            <div id="custom-control-inline-component" class="tab-pane tab-example-result fade show active" role="tabpanel"
                aria-labelledby="custom-control-inline-component-tab">
                <div class="custom-control custom-radio form-check-inline">
                    <input type="radio" id="customRadioInline1" name="client_check" value="new"
                        class="form-check-input client_check" @if (empty($exist_client)) checked @endif>
                    <label class="custom-control-label" for="customRadioInline1">{{ __('New Client') }}</label>
                </div>
                <div class="custom-control custom-radio form-check-inline">
                    <input type="radio" id="customRadioInline2" name="client_check" value="exist"
                        class="form-check-input client_check" @if (empty($exist_client)) checked @endif>
                    <label class="custom-control-label" for="customRadioInline2">{{ __('Existing Client') }}</label>
                </div>
            </div>
        </div>
        <div class="col-6 exist_client d-none">
            <div class="form-group">
                {{ Form::label('clients', __('Client'), ['class' => 'col-form-label']) }}
                <select name="clients" id="clients" class="form-control multi-select" data-toggle="select">
                    <option value="">{{ __('Select Client') }}</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->email }}" @if ($lead->email == $client->email) selected @endif>
                            {{ $client->name }} ({{ $client->email }})</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-6 new_client">
            <div class="form-group">
                {{ Form::label('client_name', __('Client Name'), ['class' => 'col-form-label']) }}
                {{ Form::text('client_name', $lead->name, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-6 new_client">
            <div class="form-group">
                {{ Form::label('client_email', __('Client Email'), ['class' => 'col-form-label']) }}
                {{ Form::text('client_email', $lead->email, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-6 new_client">
            <div class="form-group">
                {{ Form::label('client_password', __('Client Password'), ['class' => 'col-form-label']) }}
                {{ Form::text('client_password', null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-6 new_client">
            <div class="form-group">
                {{ Form::label('company', __('Company'), ['class' => 'col-form-label']) }}
                <select name="company" class="form-control multi-select" data-toggle="select">
                    <option disabled selected>{{ __('Selete company') }}</option>
                    @foreach ($company as $company)
                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row px-3 pt-2">
        <div class="col-12 pl-0 pb-2">
            <b>Copy To</b>
        </div>
        <div class="col-3 custom-control custom-checkbox">
            {{ Form::checkbox('is_transfer[]', 'sources', false, ['class' => 'form-check-input ', 'id' => 'is_transfer_sources', 'checked' => 'checked']) }}
            {{ Form::label('is_transfer_sources', __('Sources'), ['class' => 'custom-control-label']) }}
        </div>
        <div class="col-3 custom-control custom-checkbox">
            {{ Form::checkbox('is_transfer[]', 'files', false, ['class' => 'form-check-input ', 'id' => 'is_transfer_files', 'checked' => 'checked']) }}
            {{ Form::label('is_transfer_files', __('Files'), ['class' => 'custom-control-label']) }}
        </div>
        <div class="col-3 custom-control custom-checkbox">
            {{ Form::checkbox('is_transfer[]', 'discussion', false, ['class' => 'form-check-input ', 'id' => 'is_transfer_discussion', 'checked' => 'checked']) }}
            {{ Form::label('is_transfer_discussion', __('Discussion'), ['class' => 'custom-control-label']) }}
        </div>
        <div class="col-3 custom-control custom-checkbox">
            {{ Form::checkbox('is_transfer[]', 'notes', false, ['class' => 'form-check-input ', 'id' => 'is_transfer_notes', 'checked' => 'checked']) }}
            {{ Form::label('is_transfer_notes', __('Notes'), ['class' => 'custom-control-label']) }}
        </div>
        <div class="col-3 custom-control custom-checkbox">
            {{ Form::checkbox('is_transfer[]', 'calls', false, ['class' => 'form-check-input ', 'id' => 'is_transfer_calls', 'checked' => 'checked']) }}
            {{ Form::label('is_transfer_calls', __('Calls'), ['class' => 'custom-control-label']) }}
        </div>
        <div class="col-3 custom-control custom-checkbox">
            {{ Form::checkbox('is_transfer[]', 'emails', false, ['class' => 'form-check-input ', 'id' => 'is_transfer_emails', 'checked' => 'checked']) }}
            {{ Form::label('is_transfer_emails', __('Emails'), ['class' => 'custom-control-label']) }}
        </div>
    </div>
</div>
<div class="modal-footer pr-0">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    {{ Form::submit(__('Convert'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}


<script>
    $(document).ready(function() {
        var is_client = $("input[name='client_check']:checked").val();
        $(document).on('click', '.client_check', function() {
            is_client = $(this).val();
            console.log(is_client);
            if (is_client == "exist") {
                $('.exist_client').removeClass('d-none');
                $('#client_name').removeAttr('required');
                $('#client_email').removeAttr('required');
                $('#client_password').removeAttr('required');
                $('.new_client').addClass('d-none');
            } else {
                $('.new_client').removeClass('d-none');
                $('#client_name').attr('required', 'required');
                $('#client_email').attr('required', 'required');
                $('#client_password').attr('required', 'required');
                $('.exist_client').addClass('d-none');
            }
        });
        if (is_client == "exist") {
            $('.exist_client').removeClass('d-none');
            $('#client_name').removeAttr('required');
            $('#client_email').removeAttr('required');
            $('#client_password').removeAttr('required');
            $('.new_client').addClass('d-none');
        } else {
            $('.new_client').removeClass('d-none');
            $('#client_name').attr('required', 'required');
            $('#client_email').attr('required', 'required');
            $('#client_password').attr('required', 'required');
            $('.exist_client').addClass('d-none');
        }
    })
</script>
<script>
    $(document).on('click', '.client_check', function() {
        var is_client = $(this).val();
        if (is_client == "exist") {
            $('.exist_client').removeClass('d-none');
            $('#client_name').removeAttr('required');
            $('#client_email').removeAttr('required');
            $('#client_password').removeAttr('required');
            $('.new_client').addClass('d-none');
        } else {
            $('.new_client').removeClass('d-none');
            $('#client_name').attr('required', 'required');
            $('#client_email').attr('required', 'required');
            $('#client_password').attr('required', 'required');
            $('.exist_client').addClass('d-none');
        }
    });
    var is_client = $("input[name='client_check']:checked").val();
    if (is_client == "exist") {
        $('.exist_client').removeClass('d-none');
        $('#client_name').removeAttr('required');
        $('#client_email').removeAttr('required');
        $('#client_password').removeAttr('required');
        $('.new_client').addClass('d-none');
    } else {
        $('.new_client').removeClass('d-none');
        $('#client_name').attr('required', 'required');
        $('#client_email').attr('required', 'required');
        $('#client_password').attr('required', 'required');
        $('.exist_client').addClass('d-none');
    }
</script>

<script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
<script>
    if ($(".multi-select").length > 0) {
        $($(".multi-select")).each(function(index, element) {
            var id = $(element).attr('id');
            var multipleCancelButton = new Choices(
                '#' + id, {
                    removeItemButton: true,
                }
            );
        });
    }
</script>
