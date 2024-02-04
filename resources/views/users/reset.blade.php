{{Form::model($employee,array('route' => array('member.change.password', $employee->id), 'method' => 'post')) }}
<div class="modal-body">
<div class="form-group col-md-12">
    {{ Form::label('password', __('Password'),['class'=>'form-label']) }}
    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"
        required autocomplete="new-password">
    @error('password')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>
<div class="form-group col-md-12">
    {{ Form::label('confirm_password', __('Confirm Password'),['class'=>'form-label']) }}
    <input id="password-confirm" type="password" class="form-control" name="confirm_password" required
        autocomplete="new-password">
</div>
</div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal"> {{ __('Close') }} </button>
    {{Form::submit(__('Reset'),array('class'=>'btn btn-primary'))}}
</div>

{{ Form::close() }}
</div>
