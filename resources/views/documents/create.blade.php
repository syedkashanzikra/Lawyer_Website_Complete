@php
    $file_validation = App\Models\Utility::file_upload_validation();
@endphp
{{ Form::open(['route' => 'documents.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}

<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('name', __('Name of the Document'), ['class' => 'form-label']) }}
                {{ Form::text('name', null, ['class' => 'form-control']) }}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('type', __('Document Type'), ['class' => 'form-label']) }}
                <select name="type" id="type" class="form-control multi-select">
                    <option value="" disabled selected>{{ __('Please select') }}</option>
                    @foreach ($types as $key => $typ)
                        <option value="{{ $key }}">{{ $typ }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('cases', __('Case'), ['class' => 'col-form-label']) }}
                {!! Form::select('cases', $cases, null, [
                    'class' => 'form-control ',
                    'id' => 'choices-multiple',
                    'data-role' => 'tagsinput',
                ]) !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('purpose', __('Purpose'), ['class' => 'form-label']) }}
                {{ Form::text('purpose', null, ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
                {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => '1', 'maxlength' => '250']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('doc_link', __('Document Link'), ['class' => 'form-label']) }}
                {{ Form::text('doc_link', null, ['class' => 'form-control']) }}
            </div>
        </div>

        <div class="col-md-6 choose-files fw-semibold">
            <label for="profile_pic">
                {{ Form::label('profile_pic', __('Document Upload'), ['class' => 'form-label']) }}
                <div class="bg-primary profile_update" style="max-width: 100% !important;"> <i
                        class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                </div>

                <input type="file" class="file" name="file" id="profile_pic" style="width: 0px !important"
                    onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0]);
                $('#fileName').html($('input[id=profile_pic]').val().split('\\').pop());image_upload_bar($('input[id=profile_pic]').val().split('.')[1])
                ">
                <p><span
                        class="text-muted m-0">{{ __('Allowed file extension : ') }}{{ $file_validation['mimes'] }}</span>
                    <span class="text-muted">({{ __('Max Size: ') }}{{ $file_validation['max_size'] }})</span>
                </p>
                <div id="progressContainer" class="p-0" style="display: none;">
                    <progress class="bg-primary progress rounded" id="progressBar" value="0"
                        max="100"></progress>
                    <span id="progressText">0%</span>
                </div>
                <img class="img_setting" id="blah" src="" width="200px" class="big-logo">
                <p id="fileName"></p>
            </label>
        </div>


    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
