@extends('layouts.app')

@section('page-title', __('Create Ticket'))

@section('action-button')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('tickets.index') }}">{{ __('Ticket') }}</a></li>
    <li class="breadcrumb-item">{{ __('Create') }}</li>
@endsection

@section('content')
    {{ Form::open(['route' => 'tickets.store', 'method' => 'post', 'id' => 'frmTarget', 'enctype' => 'multipart/form-data', 'autocomplete' => 'off']) }}
    <div class="row g-0 p-0">
        <div class="col-lg-12">
            <div class="p-3">
                <div class="card shadow-none rounded-0 border">
                    <div class="card-header">{{ __('Ticket Information') }}</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Name') }}</label>
                                <input class="form-control {{(!empty($errors->first('name')) ? 'is-invalid' : '')}}" type="text" name="name" required="" placeholder="{{ __('Name') }}">
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Email') }}</label>
                                <input class="form-control {{(!empty($errors->first('email')) ? 'is-invalid' : '')}}" type="email" name="email" required="" placeholder="{{ __('Email') }}">
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Category') }}</label>
                                <select class="form-control {{(!empty($errors->first('category')) ? 'is-invalid' : '')}}" name="category" required="">
                                    <option value="">{{ __('Select Category') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    {{ $errors->first('category') }}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Status') }}</label>
                                <select class="form-control {{(!empty($errors->first('status')) ? 'is-invalid' : '')}}" name="status" required="">
                                    <option value="">{{ __('Select Status') }}</option>
                                    <option value="New Ticket">{{ __('New Ticket') }}</option>
                                    <option value="In Progress">{{ __('In Progress') }}</option>
                                    <option value="On Hold">{{ __('On Hold') }}</option>
                                    <option value="Closed">{{ __('Closed') }}</option>
                                    <option value="Resolved">{{ __('Resolved') }}</option>
                                </select>
                                <div class="invalid-feedback">
                                    {{ $errors->first('status') }}
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Subject') }}</label>
                                <input class="form-control {{(!empty($errors->first('subject')) ? 'is-invalid' : '')}}" type="text" name="subject" required="" placeholder="{{ __('Subject') }}">
                                <div class="invalid-feedback">
                                    {{ $errors->first('subject') }}
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="require form-label">{{ __('Attachments') }} <small>({{__('You can select multiple files')}})</small> </label>
                                <div class="choose-file form-group">
                                    <label for="file" class="form-label d-block">
                                        {{-- <input type="file" class="form-control {{ $errors->has('attachments') ? ' is-invalid' : '' }}" multiple="" name="attachments[]" id="file" data-filename="multiple_file_selection"> --}}

                                        <input type="file" name="attachments[]" id="file" class="form-control mb-2 {{ $errors->has('attachments') ? ' is-invalid' : '' }}" multiple=""  data-filename="multiple_file_selection" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                                        <img src="" id="blah" width="20%"/>
                                        <div class="invalid-feedback">
                                            {{ $errors->first('attachments.*') }}
                                        </div>
                                    </label>
                                </div>
                                <p class="multiple_file_selection mx-4"></p>
                            </div>
                            <div class="form-group col-md-6">

                                <label class="require form-label">{{ __('Priority') }}</label>
                                <select class="form-control {{(!empty($errors->first('priority')) ? 'is-invalid' : '')}} multi-select" name="priority" required="" id="priority">
                                    <option value="">{{ __('Select Priority') }}</option>

                                    @foreach($priorities as $priority)

                                        <option value="{{$priority->id}}">{{$priority->name}}</option>
                                    @endforeach

                                </select>
                                <div class="invalid-feedback">
                                    {{ $errors->first('priority') }}
                                </div>

                            </div>
                            <div class="form-group col-md-12">
                                <label class="require form-label">{{ __('Description') }}</label>
                                <textarea name="description" id="description" class="form-control summernote {{(!empty($errors->first('description')) ? 'is-invalid' : '')}}"></textarea>
                                <div class="invalid-feedback">
                                    {{ $errors->first('description') }}
                                </div>
                            </div>
                            @if(!$customFields->isEmpty())
                                @include('customFields.formBuilder')
                            @endif
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('advocate.index') }}"
                            class="btn btn-secondary btn-light ms-3">{{ __('Cancel') }}</a>
                            <input type="submit" value="{{ __('Save') }}" id="advocate-store" class="btn btn-primary ms-2">
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ Form::close() }}
@endsection

@push('custom-script')
<script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>

<script>
    $('.summernote').summernote({
        dialogsInBody: !0,
        minHeight: 250,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'strikethrough']],
            ['list', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'unlink']],
        ]
    });
</script>
@endpush
