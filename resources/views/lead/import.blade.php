{{ Form::open(['route' => ['leads.import'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12 mb-6">
            {{ Form::label('file', __('Download sample client CSV file'), ['class' => 'col-form-label']) }}
            <a href="{{ asset(Storage::url('uploads/sample')) . '/lead-sample.csv' }}"
                class="btn btn-sm btn-primary btn-icon">
                <i class="fa fa-download"></i>
            </a>
        </div>
        <div class="col-md-12">
            {{ Form::label('file', __('Select CSV File'), ['class' => 'form-label']) }}
            <div class="choose-file form-group">
                {{-- <label for="file" class="form-label choose-files bg-primary "><i
                        class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</label>
                <input type="file" class="custom-input-file d-none" name="file" id="file"
                    data-filename="upload_file" required>
                <p class="upload_file"></p> --}}
                <label for="file" class="form-label">
                    <input type="file" class="form-control" name="file" id="file" data-filename="upload_file" required>
                </label>
                <p class="upload_file"></p>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <button type="submit" class="btn  btn-primary">{{ __('Update') }}</button>
</div>
{{ Form::close() }}
