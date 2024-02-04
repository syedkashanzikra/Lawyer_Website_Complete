@php
    use App\Models\Utility;
    $settings = \Modules\LandingPage\Entities\LandingPageSetting::settings();

    $logo = Utility::get_file('uploads/landing_page_image');
    $sup_logo = Utility::get_file('uploads/logo');
    $setting = \App\Models\Utility::settings();

    $metatitle = isset($setting['meta_title']) ? $setting['meta_title'] : '';
    $metsdesc = isset($setting['meta_desc']) ? $setting['meta_desc'] : '';
    $meta_image = \App\Models\Utility::get_file('uploads/meta/');
    $meta_logo = isset($setting['meta_image']) ? $setting['meta_image'] : '';

    $SITE_RTL = $setting['SITE_RTL'] ? $setting['SITE_RTL'] : '';

    $color = !empty($setting['color']) ? $setting['color'] : 'theme-3';

@endphp


<!DOCTYPE html>
<html lang="en" dir="{{ $setting['SITE_RTL'] == 'on' ? 'rtl' : '' }}">

<head>
    <title>{{ env('APP_NAME') }}</title>
    <!-- Meta -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />

    <meta name="title" content="{{ $metatitle }}">
    <meta name="description" content="{{ $metsdesc }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:title" content="{{ $metatitle }}">
    <meta property="og:description" content="{{ $metsdesc }}">
    <meta property="og:image" content="{{ $meta_image . $meta_logo }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ env('APP_URL') }}">
    <meta property="twitter:title" content="{{ $metatitle }}">
    <meta property="twitter:description" content="{{ $metsdesc }}">
    <meta property="twitter:image" content="{{ $meta_image . $meta_logo }}">
    <!-- Favicon icon -->
    <link rel="icon" href="{{ $sup_logo . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') . '?' . time() }}" type="image/x-icon" />
    <!-- font css -->
    <link rel="stylesheet" href=" {{ Module::asset('LandingPage:Resources/assets/fonts/tabler-icons.min.css') }}" />
    <link rel="stylesheet" href=" {{ Module::asset('LandingPage:Resources/assets/fonts/feather.css') }}" />
    <link rel="stylesheet" href="  {{ Module::asset('LandingPage:Resources/assets/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ Module::asset('LandingPage:Resources/assets/fonts/material.css') }}" />


    <!-- vendor css -->
    @if ($SITE_RTL == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}">
    @endif

    @if ($setting['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
    @else
        <link rel="stylesheet" href="{{ Module::asset('LandingPage:Resources/assets/css/style.css') }}"
            id="main-style-link">
    @endif

    <link rel="stylesheet" href=" {{ Module::asset('LandingPage:Resources/assets/css/customizer.css') }}" />
    <link rel="stylesheet" href=" {{ Module::asset('LandingPage:Resources/assets/css/landing-page.css') }}" />
    <link rel="stylesheet" href=" {{ Module::asset('LandingPage:Resources/assets/css/custom.css') }}" />


</head>

@if ($setting['cust_darklayout'] == 'on')

    <body class="{{ $color }} landing-dark">
    @else

        <body class="{{ $color }}">
@endif
<!-- [ Header ] start -->
<header class="main-header">
    @if ($settings['topbar_status'] == 'on')
        <div class="announcement bg-dark text-center p-2">
            <p class="mb-0">{!! $settings['topbar_notification_msg'] !!}</p>
        </div>
    @endif
    @if ($settings['menubar_status'] == 'on')
        <div class="container">
            <nav class="navbar navbar-expand-md  default top-nav-collapse">
                <div class="header-left">
                    <a class="navbar-brand bg-transparent" href="#">
                        <img src="{{ $logo . '/' . $settings['site_logo'] }}" alt="logo" class="landing_logo">
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" href="#home">{{ $settings['home_title'] }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#features">{{ $settings['feature_title'] }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#plan">{{ $settings['plan_title'] }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#faq">{{ $settings['faq_title'] }}</a>
                        </li>


                        @if (is_array(json_decode($settings['menubar_page'])) || is_object(json_decode($settings['menubar_page'])))
                            @foreach (json_decode($settings['menubar_page']) as $key => $value)
                                @if ($value->header == 'on' && $value->template_name == 'page_content')
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ route('custom.page', $value->page_slug) }}">{{ $value->menubar_page_name }}</a>
                                    </li>
                                @elseif($value->header == 'on')
                                    <li class="nav-item">
                                        <a class="nav-link"
                                            href="{{ $value->page_url }}">{{ $value->menubar_page_name }}</a>
                                    </li>
                                @endif
                            @endforeach
                        @endif

                    </ul>
                    <button class="navbar-toggler bg-primary" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
                <div class="ms-auto d-flex justify-content-end gap-2">
                    <a href="{{ route('login') }}" class="btn btn-outline-dark rounded">
                        <span class="hide-mob me-2">
                            {{ __('Login') }}
                        </span>
                        <i data-feather="log-in"></i>
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-dark rounded">
                        <span class="hide-mob me-2">
                            {{ __('Register') }}
                        </span>
                        <i data-feather="user-check"></i>
                    </a>
                    <button class="navbar-toggler " type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
            </nav>
        </div>
    @endif

</header>
<!-- [ Header ] End -->
<!-- [ Banner ] start -->
@if ($settings['home_status'] == 'on')
    <section class="main-banner bg-primary" id="home">
        <div class="container-offset">
            <div class="row gy-3 g-0 align-items-center">
                <div class="col-xxl-4 col-md-6">
                    <span class="badge py-2 px-3 bg-white text-dark rounded-pill fw-bold mb-3">
                        {{ $settings['home_offer_text'] }}
                    </span>
                    <h1 class="mb-3">
                        {{ $settings['home_heading'] }}
                    </h1>
                    <h6 class="mb-0">{{ $settings['home_description'] }}</h6>
                    <div class="d-flex gap-3 mt-4 banner-btn">
                        @if ($settings['home_live_demo_link'])
                            <a href="{{ $settings['home_live_demo_link'] }}" class="btn btn-outline-dark">
                                {{ __('Live Demo') }}
                                <i data-feather="play-circle" class="ms-2"></i>
                            </a>
                        @endif
                        @if ($settings['home_buy_now_link'])
                            <a href="{{ $settings['home_buy_now_link'] }}"
                                class="btn btn-outline-dark">{{ __('Buy Now') }} <i data-feather="lock"
                                    class="ms-2"></i></a>
                        @endif
                    </div>
                </div>
                <div class="col-xxl-8 col-md-6">
                    <div class="dash-preview">
                        <img class="img-fluid preview-img" src="{{ $logo . '/' . $settings['home_banner'] }}"
                            alt="">
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row g-0 gy-2 mt-4 align-items-center">
                <div class="col-xxl-3">
                    <p class="mb-0">{{ __('Trusted by') }} <b
                            class="fw-bold">{{ $settings['home_trusted_by'] }}</b></p>
                </div>
                <div class="col-xxl-9">

                    <div class="row gy-3 row-cols-9">
                        
                        @foreach (explode(',', $settings['home_logo']) as $k => $home_logo)
                            <div class="col-auto">
                                <img src="{{ $logo . '/' . $home_logo }}" alt="" class="landing_logo"
                                    style="width: 130px;">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </section>
@endif
<!-- [ Banner ] start -->
<!-- [ features ] start -->
@if ($settings['feature_status'] == 'on')
    <section class="features-section section-gap bg-dark" id="features">
        <div class="container">
            <div class="row gy-3">
                <div class="col-xxl-4">
                    <span class="d-block mb-2 text-uppercase">{{ $settings['feature_title'] }}</span>
                    <div class="title mb-4">
                        <h2><b class="fw-bold">{!! $settings['feature_heading'] !!}</b></h2>
                    </div>
                    <p class="mb-3">{!! $settings['feature_description'] !!}</p>
                    @if ($settings['feature_buy_now_link'])
                        <a href="{{ $settings['feature_buy_now_link'] }}"
                            class="btn btn-primary rounded-pill d-inline-flex align-items-center">{{ __('Buy Now') }}
                            <i data-feather="lock" class="ms-2"></i></a>
                    @endif
                </div>
                <div class="col-xxl-8">
                    <div class="row">
                        @if (is_array(json_decode($settings['feature_of_features'], true)) ||
                                is_object(json_decode($settings['feature_of_features'], true)))
                            @foreach (json_decode($settings['feature_of_features'], true) as $key => $value)
                                <div class="col-lg-4 col-sm-6 d-flex">
                                    <div class="card {{ $key == 0 ? 'bg-primary' : '' }}">
                                        <div class="card-body">
                                            <span class="theme-avtar avtar avtar-xl mb-4">
                                                <img src="{{ $logo . '/' . $value['feature_logo'] }}" alt="">
                                            </span>
                                            <h3 class="mb-3 {{ $key == 0 ? '' : 'text-white' }}">
                                                {!! $value['feature_heading'] !!}</h3>
                                            <p class=" f-w-600 mb-0 {{ $key == 0 ? 'text-body' : '' }}">
                                                {!! $value['feature_description'] !!}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="mt-5">
                    <div class="title text-center mb-4">
                        <span class="d-block mb-2 text-uppercase">{{ $settings['feature_title'] }}</span>
                        <h2 class="mb-4">{!! $settings['highlight_feature_heading'] !!}</h2>
                        <p>{!! $settings['highlight_feature_description'] !!}</p>
                    </div>
                    <div class="features-preview">
                        <img class="img-fluid m-auto d-block"
                            src="{{ $logo . '/' . $settings['highlight_feature_image'] }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
<!-- [ features ] start -->
<!-- [ element ] start -->
@if ($settings['feature_status'] == 'on')
    <section class="element-section  section-gap ">
        <div class="container">
            @if (is_array(json_decode($settings['other_features'], true)) ||
                    is_object(json_decode($settings['other_features'], true)))
                @foreach (json_decode($settings['other_features'], true) as $key => $value)
                    @if ($key % 2 == 0)
                        <div class="row align-items-center justify-content-center mb-4">
                            <div class="col-lg-4 col-md-6">
                                <div class="title mb-4">
                                    <span class="d-block fw-bold mb-2 text-uppercase">{{ __('Features') }}</span>
                                    <h2>
                                        {!! $value['other_features_heading'] !!}
                                    </h2>
                                </div>
                                <p class="mb-3">{!! $value['other_featured_description'] !!}</p>
                                <a href="{{ $value['other_feature_buy_now_link'] }}"
                                    class="btn btn-primary rounded-pill d-inline-flex align-items-center">{{ __('Buy Now ') }}
                                    <i data-feather="lock" class="ms-2"></i></a>
                            </div>
                            <div class="col-lg-7 col-md-6 res-img">
                                <div class="img-wrapper">
                                    <img src="{{ $logo . '/' . $value['other_features_image'] }}" alt=""
                                        class="img-fluid header-img">
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="row align-items-center justify-content-center mb-4">
                            <div class="col-lg-7 col-md-6">
                                <div class="img-wrapper">
                                    <img src="{{ $logo . '/' . $value['other_features_image'] }}" alt=""
                                        class="img-fluid header-img">
                                </div>
                            </div>
                            <div class="col-lg-4  col-md-6">
                                <div class="title mb-4">
                                    <span class="d-block fw-bold mb-2 text-uppercase">{{ __('Features') }}</span>
                                    <h2>
                                        {!! $value['other_features_heading'] !!}
                                    </h2>
                                </div>
                                <p class="mb-3">{!! $value['other_featured_description'] !!}</p>
                                <a href="{{ $value['other_feature_buy_now_link'] }}"
                                    class="btn btn-primary rounded-pill d-inline-flex align-items-center">{{ __('Buy Now ') }}
                                    <i data-feather="lock" class="ms-2"></i></a>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif

        </div>
    </section>
@endif
<!-- [ element ] end -->
<!-- [ element ] start -->
@if ($settings['discover_status'] == 'on')
    <section class="bg-dark section-gap">
        <div class="container">
            <div class="row mb-2 justify-content-center">
                <div class="col-xxl-6">
                    <div class="title text-center mb-4">
                        <span class="d-block mb-2 text-uppercase">{{ __('DISCOVER') }}</span>
                        <h2 class="mb-4">{!! $settings['discover_heading'] !!}</h2>
                        <p>{!! $settings['discover_description'] !!}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                @if (is_array(json_decode($settings['discover_of_features'], true)) ||
                        is_object(json_decode($settings['discover_of_features'], true)))
                    @foreach (json_decode($settings['discover_of_features'], true) as $key => $value)
                        <div class="col-xxl-3 col-sm-6 col-lg-4 ">
                            <div class="card   border {{ $key == 1 ? 'bg-primary' : 'bg-transparent' }}">
                                <div class="card-body text-center">
                                    <span class="theme-avtar avtar avtar-xl mx-auto mb-4">
                                        <img src="{{ $logo . '/' . $value['discover_logo'] }}" alt="">
                                    </span>
                                    <h3 class="mb-3 {{ $key == 1 ? '' : 'text-white' }} ">{!! $value['discover_heading'] !!}
                                    </h3>
                                    <p class="{{ $key == 1 ? 'text-body' : '' }}">
                                        {!! $value['discover_description'] !!}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
            <div class="d-flex flex-column justify-content-center flex-sm-row gap-3 mt-3">
                @if ($settings['discover_live_demo_link'])
                    <a href="{{ $settings['discover_live_demo_link'] }}"
                        class="btn btn-outline-light rounded-pill">{{ __('Live Demo') }}
                        <i data-feather="play-circle" class="ms-2"></i>
                    </a>
                @endif

                @if ($settings['discover_buy_now_link'])
                    <a href="{{ $settings['discover_buy_now_link'] }}"
                        class="btn btn-primary rounded-pill">{{ __('Buy Now ') }} <i data-feather="lock"
                            class="ms-2"></i> </a>
                @endif
            </div>
        </div>
    </section>
@endif
<!-- [ element ] end -->
<!-- [ Screenshots ] start -->
@if ($settings['screenshots_status'] == 'on')
    <section class="screenshots section-gap">
        <div class="container">
            <div class="row mb-2 justify-content-center">
                <div class="col-xxl-6">
                    <div class="title text-center mb-4">
                        <span class="d-block mb-2 fw-bold text-uppercase">{{ __('SCREENSHOTS') }}</span>
                        <h2 class="mb-4">{!! $settings['screenshots_heading'] !!}</h2>
                        <p>{!! $settings['screenshots_description'] !!}</p>
                    </div>
                </div>
            </div>
            <div class="row gy-4 gx-4">
                @if (is_array(json_decode($settings['screenshots'], true)) || is_object(json_decode($settings['screenshots'], true)))
                    @foreach (json_decode($settings['screenshots'], true) as $value)
                        <div class="col-md-4 col-sm-6">
                            <div class="screenshot-card">
                                <div class="img-wrapper">
                                    <img src="{{ $logo . '/' . $value['screenshots'] }}"
                                        class="img-fluid header-img mb-4 shadow-sm" alt="">
                                </div>
                                <h5 class="mb-0">{!! $value['screenshots_heading'] !!}</h5>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
@endif
<!-- [ Screenshots ] start -->
<!-- [ subscription ] start -->
@if ($settings['plan_status'])
    <section class="subscription bg-primary section-gap" id="plan">
        <div class="container">
            <div class="row mb-2 justify-content-center">
                <div class="col-xxl-6">
                    <div class="title text-center mb-4">
                        <span class="d-block mb-2 fw-bold text-uppercase">{{ __('PLAN') }}</span>
                        <h2 class="mb-4">{!! $settings['plan_heading'] !!}</h2>
                        <p>{!! $settings['plan_description'] !!}</p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">

                @php
                    $collection = \App\Models\Plan::orderBy('price', 'ASC')->get();
                    $admin_payment_setting = Utility::set_payment_settings();
                @endphp
                @foreach ($collection as $key => $value)
                    <div class="col-xxl-3 col-lg-4 col-md-6">
                        <div class="card price-card shadow-none {{ $key == 2 ? 'bg-dark' : '' }}">
                            <div class="card-body">
                                <span class="price-badge bg-dark">{{ $value->name }}</span>
                                <span
                                    class="mb-4 f-w-00 p-price">{{ isset($admin_payment_setting['currency_symbol']) ? $admin_payment_setting['currency_symbol'] : '$' }}{{ intval($value->price) }}<small
                                        class="text-sm">/{{ $value->duration }}</small></span>
                                <p>
                                    {!! $value->description !!}
                                </p>
                                <ul class="list-unstyled my-3">
                                    <li>
                                        <div class="form-check text-start">
                                            <label class="form-check-label"
                                                for="customCheckc1">{{ $value->max_users == -1 ? 'Unlimited' : $value->max_users }}
                                                {{ __('User') }}</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check text-start">
                                            <label class="form-check-label"
                                                for="customCheckc1">{{ $value->max_customers == -1 ? 'Unlimited' : $value->max_customers }}
                                                {{ __('Customer') }}</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check text-start">
                                            <label class="form-check-label"
                                                for="customCheckc1">{{ $value->max_venders == -1 ? 'Unlimited' : $value->max_venders }}
                                                {{ __('Vendors') }}</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check text-start">
                                            <label class="form-check-label"
                                                for="customCheckc1">{{ $value->max_clients == -1 ? 'Unlimited' : $value->max_clients }}
                                                {{ __('Clients') }}</label>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="form-check text-start">
                                            <label class="form-check-label"
                                                for="customCheckc1">{{ $value->account == 1 ? 'Enable' : 'Disable' }}
                                                {{ __('Account') }}</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check text-start">
                                            <label class="form-check-label"
                                                for="customCheckc1">{{ $value->crm == 1 ? 'Enable' : 'Disable' }}
                                                {{ __('CRM') }}</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check text-start">
                                            <label class="form-check-label"
                                                for="customCheckc1">{{ $value->hrm == 1 ? 'Enable' : 'Disable' }}
                                                {{ __('HRM') }}</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check text-start">
                                            <label class="form-check-label"
                                                for="customCheckc1">{{ $value->project == 1 ? 'Enable' : 'Disable' }}
                                                {{ __('Project') }}</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check text-start">
                                            <label class="form-check-label"
                                                for="customCheckc1">{{ $value->pos == 1 ? 'Enable' : 'Disable' }}
                                                {{ __('POS') }}</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check text-start">
                                            <label class="form-check-label"
                                                for="customCheckc1">{{ $value->chatgpt == 1 ? 'Enable' : 'Disable' }}
                                                {{ __('ChatGPT') }}</label>
                                        </div>
                                    </li>

                                </ul>
                                <div class="d-grid">
                                    <a href="{{ route('register') }}"
                                        class="btn btn-primary rounded-pill">{{ __('Start with Starter') }} <i
                                            data-feather="log-in" class="ms-2"></i> </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>
@endif
<!-- [ subscription ] end -->
<!-- [ FAqs ] start -->

@if ($settings['faq_status'] == 'on')
    <section class="faqs section-gap bg-gray-100" id="faq">
        <div class="container">
            <div class="row mb-2">
                <div class="col-xxl-6">
                    <div class="title mb-4">
                        <span class="d-block mb-2 fw-bold text-uppercase">{{ $settings['faq_title'] }}</span>
                        <h2 class="mb-4">{!! $settings['faq_heading'] !!}</h2>
                        <p>{!! $settings['faq_description'] !!}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        @if (is_array(json_decode($settings['faqs'], true)) || is_object(json_decode($settings['faqs'], true)))
                            @foreach (json_decode($settings['faqs'], true) as $key => $value)
                                @if ($key % 2 == 0)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="{{ 'flush-heading' . $key }}">
                                            <button class="accordion-button collapsed fw-bold" type="button"
                                                data-bs-toggle="collapse" data-bs-target="{{ '#flush-' . $key }}"
                                                aria-expanded="false" aria-controls="{{ 'flush-collapse' . $key }}">
                                                {!! $value['faq_questions'] !!}
                                            </button>
                                        </h2>
                                        <div id="{{ 'flush-' . $key }}" class="accordion-collapse collapse"
                                            aria-labelledby="{{ 'flush-heading' . $key }}"
                                            data-bs-parent="#accordionFlushExample">
                                            <div class="accordion-body">
                                                {!! $value['faq_answer'] !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="accordion accordion-flush" id="accordionFlushExample2">
                        @if (is_array(json_decode($settings['faqs'], true)) || is_object(json_decode($settings['faqs'], true)))
                            @foreach (json_decode($settings['faqs'], true) as $key => $value)
                                @if ($key % 2 != 0)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="{{ 'flush-heading' . $key }}">
                                            <button class="accordion-button collapsed fw-bold" type="button"
                                                data-bs-toggle="collapse" data-bs-target="{{ '#flush-' . $key }}"
                                                aria-expanded="false" aria-controls="{{ 'flush-collapse' . $key }}">
                                                {!! $value['faq_questions'] !!}
                                            </button>
                                        </h2>
                                        <div id="{{ 'flush-' . $key }}" class="accordion-collapse collapse"
                                            aria-labelledby="{{ 'flush-heading' . $key }}"
                                            data-bs-parent="#accordionFlushExample2">
                                            <div class="accordion-body">
                                                {!! $value['faq_answer'] !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif


                    </div>
                </div>

            </div>
        </div>
    </section>
@endif
<!-- [ FAqs ] end -->
<!-- [ testimonial ] start -->
@if ($settings['testimonials_status'] == 'on')
    <section class="testimonial section-gap">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4">
                    <div class="title mb-4">
                        <span class="d-block mb-2 fw-bold text-uppercase">{{ __('TESTIMONIALS') }}</span>
                        <h2 class="mb-2">{!! $settings['testimonials_heading'] !!}</h2>
                        <p>{!! $settings['testimonials_description'] !!}</p>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="row justify-content-center gy-3">


                        @if (is_array(json_decode($settings['testimonials'])) || is_object(json_decode($settings['testimonials'])))
                            @foreach (json_decode($settings['testimonials']) as $key => $value)
                                <div class="col-xxl-4 col-sm-6 col-lg-6 col-md-4">
                                    <div class="card bg-dark shadow-none mb-0">
                                        <div class="card-body p-3">
                                            <div class="d-flex mb-3 align-items-center justify-content-between">
                                                <span class="theme-avtar avtar avtar-sm bg-light-dark rounded-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="36"
                                                        height="23" viewBox="0 0 36 23" fill="none">
                                                        <path
                                                            d="M12.4728 22.6171H0.770508L10.6797 0.15625H18.2296L12.4728 22.6171ZM29.46 22.6171H17.7577L27.6669 0.15625H35.2168L29.46 22.6171Z"
                                                            fill="white" />
                                                    </svg>
                                                </span>
                                                <span>
                                                    @for ($i = 1; $i <= (int) $value->testimonials_star; $i++)
                                                        <i data-feather="star"></i>
                                                    @endfor
                                                </span>
                                            </div>
                                            <h3 class="text-white">{{ $value->testimonials_title }}</h3>
                                            <p class="hljs-comment">
                                                {{ $value->testimonials_description }}
                                            </p>
                                            <div class="d-flex  align-items-center ">
                                                <img src="{{ $logo . '/' . $value->testimonials_user_avtar }}"
                                                    class="wid-40 rounded-circle me-3" alt="">
                                                <span>
                                                    <b class="fw-bold d-block">{{ $value->testimonials_user }}</b>
                                                    {{ $value->testimonials_designation }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif




                    </div>
                </div>
                <div class="col-12">
                    <p class="mb-0 f-w-600">
                        {!! $settings['testimonials_long_description'] !!}
                    </p>
                </div>
            </div>
        </div>
    </section>
@endif
<!-- [ testimonial ] end -->
<!-- [ Footer ] start -->
<footer class="site-footer bg-gray-100">
    <div class="container">
        <div class="footer-row">
            <div class="ftr-col cmp-detail">
                <div class="footer-logo mb-3">
                    <a href="#">
                        <img src="{{ $logo . '/' . $settings['site_logo'] }}" alt="logo">
                    </a>
                </div>
                <p>
                    {!! $settings['site_description'] !!}
                </p>

            </div>
            <div class="ftr-col">
                <ul class="list-unstyled">

                    @if (is_array(json_decode($settings['menubar_page'])) || is_object(json_decode($settings['menubar_page'])))
                        @foreach (json_decode($settings['menubar_page']) as $key => $value)
                            @if ($value->footer == 'on' && $value->header == 'off' && $value->template_name == 'page_content')
                                <li><a
                                        href="{{ route('custom.page', $value->page_slug) }}">{!! $value->menubar_page_name !!}</a>
                                </li>
                            @endif
                            @if ($value->footer == 'on' && $value->header == 'on' && $value->template_name == 'page_content')
                                <li><a
                                        href="{{ route('custom.page', $value->page_slug) }}">{!! $value->menubar_page_name !!}</a>
                                </li>
                            @endif
                            @if ($value->footer == 'on' && $value->header == 'on' && $value->template_name == 'page_url')
                                <li><a href="{{ $value->page_url }}">{!! $value->menubar_page_name !!}</a></li>
                            @endif
                            @if ($value->footer == 'on' && $value->header == 'off' && $value->template_name == 'page_url')
                                <li><a href="{{ $value->page_url }}">{!! $value->menubar_page_name !!}</a></li>
                            @endif
                        @endforeach
                    @endif

                </ul>
            </div>

            @if ($settings['joinus_status'] == 'on')
                <div class="ftr-col ftr-subscribe">
                    <h2>{!! $settings['joinus_heading'] !!}</h2>
                    <p>{!! $settings['joinus_description'] !!}</p>
                    <form method="post" action="{{ route('join_us_store') }}">
                        @csrf
                        <div class="input-wrapper border border-dark">
                            <input type="text" name="email" placeholder="Type your email address...">
                            <button type="submit" class="btn btn-dark rounded-pill">{{ __('Join Us') }}!</button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
    <div class="border-top border-dark text-center p-2">
        <p class="mb-0"> &copy;
            {{ date('Y') }}
            {{ Utility::getValByName('footer_text') ? Utility::getValByName('footer_text') : config('app.name', 'ERPGo') }}
        </p>

    </div>
</footer>
<!-- [ Footer ] end -->
<!-- Required Js -->


<script src="{{ Module::asset('LandingPage:Resources/assets/js/plugins/popper.min.js') }}"></script>
<script src="{{ Module::asset('LandingPage:Resources/assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ Module::asset('LandingPage:Resources/assets/js/plugins/feather.min.js') }}"></script>

<script>
    // Start [ Menu hide/show on scroll ]
    let ost = 0;
    document.addEventListener("scroll", function() {
        let cOst = document.documentElement.scrollTop;
        if (cOst == 0) {
            document.querySelector(".navbar").classList.add("top-nav-collapse");
        } else if (cOst > ost) {
            document.querySelector(".navbar").classList.add("top-nav-collapse");
            document.querySelector(".navbar").classList.remove("default");
        } else {
            document.querySelector(".navbar").classList.add("default");
            document
                .querySelector(".navbar")
                .classList.remove("top-nav-collapse");
        }
        ost = cOst;
    });
    // End [ Menu hide/show on scroll ]

    var scrollSpy = new bootstrap.ScrollSpy(document.body, {
        target: "#navbar-example",
    });
    feather.replace();
</script>
@if ($setting['enable_cookie'] == 'on')
    @include('layouts.cookie_consent')
@endif
</body>

</html>
