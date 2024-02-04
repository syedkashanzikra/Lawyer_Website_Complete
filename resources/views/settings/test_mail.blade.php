<form class="pl-3 pr-3" method="post" action="{{ route('test.send.mail') }}" id="test_email">
    @csrf
    <div class="modal-body">
        <div class="form-group">
            <label for="email" class="col-form-label">{{ __('E-Mail Address') }}</label>
            <input type="email" class="form-control" id="email" name="email"
                placeholder="{{ __('Please enter Email Address') }}" required />
        </div>
        <div class="form-group">
            <input type="hidden" name="mail_driver" value="{{ $data['mail_driver'] }}" />
            <input type="hidden" name="mail_host" value="{{ $data['mail_host'] }}" />
            <input type="hidden" name="mail_port" value="{{ $data['mail_port'] }}" />
            <input type="hidden" name="mail_username" value="{{ $data['mail_username'] }}" />
            <input type="hidden" name="mail_password" value="{{ $data['mail_password'] }}" />
            <input type="hidden" name="mail_encryption" value="{{ $data['mail_encryption'] }}" />
            <input type="hidden" name="mail_from_address" value="{{ $data['mail_from_address'] }}" />
            <input type="hidden" name="mail_from_name" value="{{ $data['mail_from_name'] }}" />
            <button class="btn btn-primary float-end mb-3" type="submit">{{ __('Send Test Mail') }}</button>
            <label id="email_sending" class="float-left" style="display: none;"><i class="ti ti-clock"></i>
                {{ __('Sending ...') }} </label>
        </div>
    </div>
</form>
