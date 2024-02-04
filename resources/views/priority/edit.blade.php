
<form method="post" action="{{ route('priority.update', $priority->id) }}">
    @csrf
    @method('PUT')

    <div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6">
            <label class="form-label">{{ __('Name') }}</label>
            <div class="col-sm-12 col-md-12">
                <input type="text" placeholder="{{ __('Name of the Category') }}" name="name"
                    class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ $priority->name }}"
                    autofocus>
                <div class="invalid-feedback">
                    {{ $errors->first('name') }}
                </div>
            </div>
        </div>

        <div class="form-group col-md-6">
            <label for="exampleColorInput" class="form-label">{{ __('Color') }}</label>
            <div class="col-sm-12 col-md-12">
                <input name="color" type="color"
                    class="form-control form-control-color {{ $errors->has('color') ? ' is-invalid' : '' }}"
                    value="{{ $priority->color }}">
                <div class="invalid-feedback">
                    {{ $errors->first('color') }}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <a class="btn btn-secondary btn-light btn-submit" href="">{{ __('Cancel') }}</a>
    <button class="btn btn-primary btn-submit ms-2" type="submit">{{ __('Update') }}</button>
</div>
</form>
