@php
    use App\Models\Utility;
    $logo = asset('storage/uploads/logo');

    $company_favicon = Utility::getValByName('company_favicon');
    $setting = Utility::colorset();
    $SITE_RTL = $setting['SITE_RTL'];
    $seo_setting = Utility::getSeoSetting();
    $color = 'theme-1';

    if (!empty($setting['color'])) {
        $color = $setting['color'];
    }

    $SITE_RTL = 'off';
    if (!empty($setting['SITE_RTL'])) {
        $SITE_RTL = $setting['SITE_RTL'];
    }

    $mode_setting = Utility::mode_layout();

    $logo_light = Utility::getValByName('company_logo_light');
    $logo_dark = Utility::getValByName('company_logo_dark');

    $company_logo = Utility::get_company_logo();
    $company_logos = Utility::getValByName('company_logo_light');

@endphp
@extends('layouts.custom_guest')
@section('title-content')
    <h2 class="text-center p-0 m-5 " style="color: #fff">{{ __('Knowledge') }}</h2>
@endsection
@section('nav-content')
    <nav class="navbar navbar-expand-md navbar-dark default dark_background_color">
        <div class="container-fluid pe-2">

            <a class="navbar-brand" href="{{ route('user.ticket.create') }}">
                @if ($mode_setting['cust_darklayout'] && $mode_setting['cust_darklayout'] == 'on')
                    <img src="{{ $logo . '/' . (isset($company_logos) && !empty($company_logos) ? $company_logos : 'logo-dark.png') . '?' . time() }}"
                        alt="{{ config('app.name', 'Smart Chamber-SaaS') }}" class="logo "
                        style="height: 30px; width: 180px;">
                @else
                    <img src="{{ $logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') . '?' . time() }}"
                        alt="{{ config('app.name', 'Smart Chamber-SaaS') }}" class="logo "
                        style="height: 30px; width: 180px;">
                @endif
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01"
                aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarTogglerDemo01" style="flex-grow: 0;">
                <ul class="navbar-nav align-items-center ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <li class="nav-item ">
                            <a class="nav-link" href="{{ route('user.ticket.create') }}">{{ __('Create Ticket') }}</a>
                        </li> 
                        <li class="nav-item ">
                            <a class="nav-link" href="{{ route('user.ticket.search') }}">{{ __('Search Ticket') }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.faq') }}" class="nav-link">{{ __('FAQ') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">{{ __('Knowledge') }}</a>
                        </li>
                    </li>
                </ul>
            </div>

        </div>
    </nav>
@endsection

@section('content')
    <div class="col-xl-12 text-center">
        <div class="mx-3 mx-md-5 mt-3">

        </div>
        @if (Session::has('create_ticket'))
            <div class="alert alert-success">
                <p>{!! session('create_ticket') !!}</p>
            </div>
        @endif
        <div class="card rounded-0 ">
            <div class="card-body w-100">
                <div class="text-start">
                    @if ($knowledges->count())
                        <div class="row">
                            @foreach ($knowledges as $index => $knowledge)
                                <div class="col-md-4">
                                    <div class="card" style="min-height: 200px;">
                                        <div class="card-header py-3 mb-3" id="heading-{{ $index }}"role="button"
                                            aria-expanded="{{ $index == 0 ? 'true' : 'false' }}">
                                            <div class="row m-auto">
                                                <h6 class="mr-3">
                                                    {{ App\Models\Knowledge::knowlege_details($knowledge->category) }} (
                                                    {{ App\Models\Knowledge::category_count($knowledge->category) }} )
                                                </h6>
                                            </div>
                                        </div>
                                        <ul class="knowledge_ul">
                                            @foreach ($knowledges_detail as $details)
                                                @if ($knowledge->category == $details->category)
                                                    <li style="list-style: none;" class="child">
                                                        <a href="{{ route('knowledgedesc', ['id' => $details->id]) }}">
                                                            <i class="far fa-file-alt ms-3"></i>
                                                            {{ !empty($details->title) ? $details->title : '-' }}
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0 text-center">{{ __('No Knowledges found.') }}</h6>
                            </div>
                        </div>
                    @endif
                </div>
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
                ["font", ["bold", "italic", "underline", "clear", "strikethrough"]],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ["para", ["ul", "ol", "paragraph"]],
            ]
        });
    </script>
@endpush
