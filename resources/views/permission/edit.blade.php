{{ Form::model($permission, ['route' => ['permissions.update', $permission->id], 'method' => 'PUT']) }}
<div class="modal-body">
<div class="card-body">
    <div class="form-group">
        {{ Form::label('name', __('Name')) }}
        {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Permission Name')]) }}
        @error('name')
            <span class="invalid-name" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Update'), ['class' => 'btn btn-primary']) }}
</div>
{{ Form::close() }}
