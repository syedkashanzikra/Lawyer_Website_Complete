@extends('layouts.app')

@section('page-title')
    {{ __('Edit Knowledge Category') }} ({{ $knowledge_category->title }})
@endsection

@section('action-button')

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('knowledge') }}">{{ __('Knowledge') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('knowledgecategory') }}">{{ __('Category') }}</a></li>
    <li class="breadcrumb-item">{{ __('Edit') }}</li>
@endsection

@section('content')
<div class="col-12">
    <div class="card shadow-none rounded-0 border-bottom">
        <div class="card-body">
            
                <form method="post" action="{{ route('knowledgecategory.update', $knowledge_category->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="form-label">{{ __('Title') }}</label>
                            <div class="col-sm-12 col-md-12">
                                <input type="text" placeholder="{{ __('Title of the Knowledge') }}" name="title"
                                    class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}"
                                    value="{{ $knowledge_category->title }}" autofocus>
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
                                    class="btn btn-primary btn-block btn-submit"><span>{{ __('Update') }}</span></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
