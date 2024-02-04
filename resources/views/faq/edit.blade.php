@extends('layouts.app')

@section('page-title')
    {{ __('Edit FAQ') }} ({{ $faq->title }})
@endsection
@section('action-button')
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('faq.index') }}">{{ __('FAQ') }}</a></li>
    <li class="breadcrumb-item">{{ __('Edit') }}</li>
@endsection

@section('content')
    <div class="col-12">
        <div class="card">

            <div class="card-body">
                <form method="post" action="{{ route('faq.update', $faq->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="form-label">{{ __('Title') }}</label>
                            <div class="col-sm-12 col-md-12">
                                <input type="text" placeholder="{{ __('Title of the Faq') }}" name="title"
                                    class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}"
                                    value="{{ $faq->title }}" autofocus>
                                <div class="invalid-feedback">
                                    {{ $errors->first('title') }}
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="form-label">{{ __('Description') }}</label>
                            <div class="col-sm-12 col-md-12">
                                <textarea name="description" class="form-control summernote {{ $errors->has('description') ? ' is-invalid' : '' }}">{{ $faq->description }}</textarea>
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
