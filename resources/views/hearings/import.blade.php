{{ Form::open(array('route' => array('hearing.import'),'method'=>'post', 'enctype' => "multipart/form-data")) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12 mb-6">
            {{Form::label('file',__('Download sample case CSV file'),['class'=>'form-label'])}}
            <a href="{{asset(Storage::url('uploads/sample')).'/sample-hearings.csv'}}"  class="btn btn-sm btn-primary">
                <i class="ti ti-download"></i> {{__('Download')}}
            </a>
        </div>
        <input type="hidden" name="case_id" class="form-control" id="case_id" value="{{ $case_id }}">
        <div class="col-md-12">
            {{Form::label('file',__('Select CSV File'),['class'=>'form-label'])}}
            <div class="choose-file form-group">
                <label for="file" class="form-label">
                    <input type="file" class="form-control" name="file" id="file" data-filename="upload_file" required>
                </label>
                <p class="upload_file"></p>
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Upload')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}
