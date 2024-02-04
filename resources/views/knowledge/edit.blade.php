@extends('layouts.app')

@section('page-title')
    {{ __('Edit Knowledge') }} ({{ $knowledge->title }})
@endsection

@section('action-button')
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('knowledge') }}">{{ __('Knowledge') }}</a></li>
    <li class="breadcrumb-item">{{ __('Edit') }}</li>
@endsection

@section('content')i
    <div class="col-12">
        <div class="card shadow-none rounded-0 border-bottom">
            <div class="card-body">
                <div class="row">
                    @if (isset($setting['is_enabled']) && $setting['is_enabled'] == 'on')
                        <div class="float-end" style="margin-top: 18px;">
                            <a class="btn btn-primary btn-sm float-end ms-2" href="#" data-size="lg"
                                data-ajax-popup-over="true" data-url="{{ route('generate', ['knowledge']) }}"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Generate') }}"
                                data-title="{{ __('Generate Content with AI') }}"><i class="fas fa-robot">
                                    {{ __('Generate with AI') }}</i></a>
                        </div>
                    @endif
                </div>
                <form method="post" action="{{ route('knowledge.update', $knowledge->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="form-label">{{ __('Title') }}</label>
                            <div class="col-sm-12 col-md-12">
                                <input type="text" placeholder="{{ __('Title of the Knowledge') }}" name="title"
                                    class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}"
                                    value="{{ $knowledge->title }}" autofocus>
                                <div class="invalid-feedback">
                                    {{ $errors->first('title') }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="form-label">{{ __('Category') }}</label>
                            <div class="col-sm-12 col-md-12">
                                <select class="form-select" name="category">
                                    @foreach ($category as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="form-label">{{ __('Description') }}</label>
                            <div class="col-sm-12 col-md-12">
                                <textarea name="description" class="form-control summernote {{ $errors->has('description') ? ' is-invalid' : '' }}">{!! $knowledge->description !!}</textarea>
                                <div class="invalid-feedback">
                                    {{ $errors->first('description') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="form-label"></label>
                            <div class="col-sm-12 col-md-12 text-end">
                                <button
                                    class="btn btn-primary btn-block btn-submit"><span>{{ __('Update') }}</span></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
