@extends('layouts.app')

@section('page-title', __('Create Knowledge Category'))

@section('action-button')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('knowledge') }}">{{ __('Knowledge') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('knowledgecategory') }}">{{ __('Category') }}</a></li>
    <li class="breadcrumb-item">{{ __('Create') }}</li>
@endsection

@section('content')
    <div class="col-12">
        <div class="card shadow-none rounded-0 border-bottom">
            <div class="card-body">
                <form method="post" class="needs-validation" action="{{ route('knowledgecategory.store') }}">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="form-label">{{ __('Title') }}</label>
                            <div class="col-sm-12 col-md-12">
                                <input type="text" placeholder="{{ __('Title of the Knowledge') }}" name="title"
                                    class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}"
                                    value="{{ old('title') }}" autofocus>
                                <div class="invalid-feedback">
                                    {{ $errors->first('title') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="form-label"></label>
                            <div class="col-sm-12 col-md-12 text-end">
                                <button
                                    class="btn btn-primary btn-block btn-submit"><span>{{ __('Add') }}</span></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
