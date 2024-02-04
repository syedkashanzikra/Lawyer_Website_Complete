{{ Form::open(array('route' => array('store.language'))) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-12">
            {{ Form::label('code', __('Language Code'),['class'=>'form-label']) }}
            {{ Form::text('code', '', array('class' => 'form-control','required'=>'required')) }}
            @error('code')
            <span class="invalid-code" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="form-group">
            {{ Form::label('fullname', __('Language Full Name'),['class' => 'col-form-label']) }}
            {{ Form::text('fullname', '', array('class' => 'form-control','required'=>'required')) }}
            @error('fullname')
            <span class="invalid-code" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn btn-primary ms-2">
</div>
{{ Form::close() }}
