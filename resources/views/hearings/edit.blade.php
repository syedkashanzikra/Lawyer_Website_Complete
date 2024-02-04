@php
    $docfile = \App\Models\Utility::get_file('uploads/documents/');
    $file_validation = App\Models\Utility::file_upload_validation();
@endphp
{{ Form::model($hearing,['route' => ['hearing.update',$hearing->id], 'method' => 'put', 'enctype' => 'multipart/form-data']) }}
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-12">
                {!! Form::label('date', __('Hearing date'), ['class' => 'form-label']) !!}
                {!! Form::date('date', null, ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-12">
                {{ Form::label('remarks', __('Remarks'), ['class' => 'form-label']) }}
                {{ Form::textarea('remarks', null, ['class' => 'form-control', 'rows' => '3','maxlength'=>"250"]) }}
            </div>
            <div class="col-md-6 choose-files fw-semibold">
                <label for="profile_pic">
                    {{ Form::label('profile_pic', __('Order Sheet'), ['class' => 'form-label']) }}
                    <div class="bg-primary profile_update" style="max-width: 100% !important;"> <i
                            class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                    </div>
                    <input type="file" class="file" name="file" id="profile_pic"  onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0]);$('#fileName').html($('input[id=profile_pic]').val().split('\\').pop());image_upload_bar($('input[id=profile_pic]').val().split('.')[1])"style="width: 0px !important">
                    <p><span class="text-muted m-0">{{ __('Allowed file extension : ') }}{{ $file_validation['mimes'] }}</span>
                        <span class="text-muted">({{ __('Max Size: ') }}{{ $file_validation['max_size'] }})</span></p>
                    <div id="progressContainer" class="p-0" style="display: none;">
                        <progress class="bg-primary progress rounded" id="progressBar" style="width: 300px !important" value="0" max="100"></progress>
                        <span id="progressText">0%</span>
                    </div>
                    <img class="img_setting" id="blah" src="{{ !empty($hearing->order_seet) ? $docfile.$hearing->order_seet : '' }}"  width="200px" class="big-logo">
                     <p id="fileName"></p>

                </label>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Update')}}" class="btn btn-primary ms-2">
    </div>
{{Form::close()}}

