@if ($customFields)
    @foreach ($customFields as $customField)
        @if ($customField->id == '1')
            <div class="col-lg-6">
                <div class="form-group mb-3 {{ $customField->width }}">
                    <label for="name" class="form-label">{{ __($customField->name) }}</label>
                    <div class="form-icon-user">
                        <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                            id="name" name="name" placeholder="{{ __($customField->placeholder) }}" required=""
                            value="{{ old('name') }}">
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('name') }}
                        </div>
                    </div>
                </div>
            </div>
        @elseif($customField->id == '2')
            <div class="col-lg-6">
                <div class="form-group mb-3 {{ $customField->width }}">
                    <label for="email" class="form-label">{{ __($customField->name) }}</label>
                    <div class="form-icon-user">
                        <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                            id="email" name="email" placeholder="{{ __($customField->placeholder) }}"
                            required="" value="{{ old('email') }}">
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('email') }}
                        </div>
                    </div>
                </div>
            </div>
        @elseif($customField->id == '3')
            <div class="col-lg-6">
                <div class="form-group mb-3 {{ $customField->width }}">
                    <label for="category" class="form-label">{{ __($customField->name) }}</label>
                    <select class="form-select" id="category" name="category"
                        data-placeholder="{{ __($customField->placeholder) }}">
                        <option value="">{{ __($customField->placeholder) }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @if (old('category') == $category->id) selected @endif>
                                {{ $category->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback d-block">
                        {{ $errors->first('category') }}
                    </div>
                </div>
            </div>
        @elseif($customField->id == '4')
            <div class="col-lg-6">
                <div class="form-group mb-3 {{ $customField->width }}">
                    <label for="subject" class="form-label">{{ __($customField->name) }}</label>
                    <div class="form-icon-user">
                        <input type="text" class="form-control {{ $errors->has('subject') ? ' is-invalid' : '' }}"
                            id="subject" name="subject" placeholder="{{ __($customField->placeholder) }}"
                            required="" value="{{ old('subject') }}">
                        <div class="invalid-feedback d-block">
                            {{ $errors->first('subject') }}
                        </div>
                    </div>
                </div>
            </div>
        @elseif($customField->id == '5')
            <div class="col-lg-12">
                <div class="form-group mb-3 {{ $customField->width }}">
                    <label for="description" class="form-label">{{ __('Description') }}</label>
                    <textarea name="description" class="form-control summernote {{ $errors->has('description') ? ' is-invalid' : '' }}"
                        placeholder="{{ __($customField->placeholder) }}">{{ old('description') }}</textarea>
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                </div>
            </div>
        @elseif($customField->custom_id == '6')
            <div class="col-lg-6">
                <div class="form-group mb-3 {{ $customField->width }}">
                    <label for="company" class="form-label">{{ __($customField->name) }}</label>
                    <select class="form-select" id="company" name="company"
                        data-placeholder="{{ __($customField->placeholder) }}">
                        <option value="">{{ __($customField->placeholder) }}</option>
                        @foreach ($companies as $priority)
                            <option value="{{ $priority->id }}" @if (old('company') == $priority->id) selected @endif>
                                {{ $priority->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback d-block">
                        {{ $errors->first('company') }}
                    </div>
                </div>
            </div>
        @elseif($customField->custom_id == '7')
            <div class="col-lg-6">
                <div class="form-group mb-3 {{ $customField->width }}">
                    <label for="priority" class="form-label">{{ __($customField->name) }}</label>
                    <select class="form-select" id="priority" name="priority"
                        data-placeholder="{{ __($customField->placeholder) }}">
                        <option value="">{{ __($customField->placeholder) }}</option>
                        @foreach ($priorities as $priority)
                            <option value="{{ $priority->id }}" @if (old('priority') == $priority->id) selected @endif>
                                {{ $priority->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback d-block">
                        {{ $errors->first('priority') }}
                    </div>
                </div>
            </div>
        @elseif($customField->id == '8')
            <div class="col-lg-6">
                <div class="form-group mb-3 {{ $customField->width }}">
                    <label class="form-label">{{ $customField->name }}
                        <small>({{ $customField->placeholder }})</small></label>
                    <div class="choose-file form-group">
                        <label for="file" class="form-label">
                            <div>{{ __('Choose File Here') }}</div>
                            <input type="file"
                                class="form-control {{ $errors->has('attachments.') ? 'is-invalid' : '' }}"
                                multiple="" name="attachments[]" id="file"
                                data-filename="multiple_file_selection">
                        </label>
                        <p class="multiple_file_selection"></p>
                    </div>
                </div>
                <div class="invalid-feedback d-block">
                    {{ $errors->first('attachments.*') }}
                </div>
            </div>
        @elseif($customField->type == 'text')
            <div class="col-lg-6">
                <div class="form-group mb-3{{ $customField->width }}">
                    {{ Form::label('customField-' . $customField->id, __($customField->name), ['class' => 'form-label']) }}
                    @if ($customField->is_required == 1)
                        {{ Form::text('customField[' . $customField->id . ']', null, ['class' => 'form-control', 'placeholder' => __($customField->placeholder), 'required']) }}
                    @else
                        {{ Form::text('customField[' . $customField->id . ']', null, ['class' => 'form-control', 'placeholder' => __($customField->placeholder)]) }}
                    @endif
                </div>
            </div>
        @elseif($customField->type == 'email')
            <div class="col-lg-6">
                <div class="form-group mb-3 {{ $customField->width }}">
                    {{ Form::label('customField-' . $customField->id, __($customField->name), ['class' => 'form-label']) }}
                    @if ($customField->is_required == 1)
                        {{ Form::email('customField[' . $customField->id . ']', null, ['class' => 'form-control', 'placeholder' => __($customField->placeholder), 'required']) }}
                    @else
                        {{ Form::email('customField[' . $customField->id . ']', null, ['class' => 'form-control', 'placeholder' => __($customField->placeholder)]) }}
                    @endif
                </div>
            </div>
        @elseif($customField->type == 'number')
            <div class="col-lg-6">
                <div class="form-group mb-3 {{ $customField->width }}">
                    {{ Form::label('customField-' . $customField->id, __($customField->name), ['class' => 'form-label']) }}
                    @if ($customField->is_required == 1)
                        {{ Form::number('customField[' . $customField->id . ']', null, ['class' => 'form-control', 'placeholder' => __($customField->placeholder), 'required']) }}
                    @else
                        {{ Form::number('customField[' . $customField->id . ']', null, ['class' => 'form-control', 'placeholder' => __($customField->placeholder)]) }}
                    @endif
                </div>
            </div>
        @elseif($customField->type == 'date')
            <div class="col-lg-6">
                <div class="form-group mb-3 {{ $customField->width }}">
                    {{ Form::label('customField-' . $customField->id, __($customField->name), ['class' => 'form-label']) }}
                    @if ($customField->is_required == 1)
                        {{ Form::date('customField[' . $customField->id . ']', null, ['class' => 'form-control', 'placeholder' => __($customField->placeholder), 'required']) }}
                    @else
                        {{ Form::date('customField[' . $customField->id . ']', null, ['class' => 'form-control', 'placeholder' => __($customField->placeholder)]) }}
                    @endif
                </div>
            </div>
        @elseif($customField->type == 'textarea')
            <div class="col-lg-6">
                <div class="form-group mb-3 {{ $customField->width }}">
                    {{ Form::label('customField-' . $customField->id, __($customField->name), ['class' => 'form-label']) }}
                    @if ($customField->is_required == 1)
                        {{ Form::textarea('customField[' . $customField->id . ']', null, ['class' => 'form-control summernote', 'placeholder' => __($customField->placeholder), 'required']) }}
                    @else
                        {{ Form::textarea('customField[' . $customField->id . ']', null, ['class' => 'form-control summernote', 'placeholder' => __($customField->placeholder)]) }}
                    @endif
                </div>
            </div>
        @endif
    @endforeach
@endif
