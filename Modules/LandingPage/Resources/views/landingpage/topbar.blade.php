@extends('layouts.app')
@section('page-title')
    {{ __('Landing Page') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">{{__('Landing Page')}}</li>
@endsection

@php
    $logo=\App\Models\Utility::get_file('uploads/logo');
    $settings = \Modules\LandingPage\Entities\LandingPageSetting::settings();
@endphp

@push('css-page')
    <link rel="stylesheet" href=" {{ Module::asset('LandingPage:Resources/assets/css/summernote/summernote-bs4.css')}}" />
@endpush

@push('script-page')
    <script src="{{ Module::asset('LandingPage:Resources/assets/js/plugins/summernote-bs4.js')}}" referrerpolicy="origin"></script>
@endpush



@section('content')
        <div class="col-sm-12">
            <div class="row g-0">
                <div class="col-xl-3 border-end border-bottom">
                    <div class="card shadow-none bg-transparent sticky-top" style="top:30px">
                        <div class="list-group list-group-flush rounded-0" id="useradd-sidenav">

                            @include('landingpage::layouts.tab')

                        </div>
                    </div>
                </div>

                <div class="col-xl-9 border-end ">
                {{--  Start for all settings tab --}}
                    {{Form::model(null, array('route' => array('landingpage.store'), 'method' => 'POST')) }}
                    @csrf
                        <div class="card rounded-0 shadow-none bg-transparent">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col-6">
                                        <h5 class="mb-2">{{ __('Top Bar') }}</h5>
                                    </div>
                                    <div class="col switch-width text-end">
                                        <div class="form-group mb-0">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary" class="" name="topbar_status"
                                                    id="topbar_status" {{ $settings['topbar_status'] == 'on' ? 'checked="checked"' : '' }}>
                                                <label class="custom-control-label" for="topbar_status"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-12">
                                        {{ Form::label('content', __('Message'), ['class' => 'col-form-label text-dark']) }}
                                        {{ Form::textarea('topbar_notification_msg',$settings['topbar_notification_msg'], ['class' => 'summernote-simple form-control', 'required' => 'required']) }}
                                    </div>

                                </div>
                            </div>
                            <div class="card-footer text-end border-bottom rounded-0">
                                <input class="btn btn-print-invoice btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
                            </div>
                        </div>
                    {{ Form::close() }}

                {{--  End for all settings tab --}}
                </div>
            </div>
        </div>
@endsection



