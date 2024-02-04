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

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $SITE_RTL == 'on' ? 'rtl' : '' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="keywords" content="Dashboard Template" />
    <meta name="author" content="Rajodiya Infotech" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base-url" content="{{ URL::to('/') }}">
    <title>
        {{ Utility::getValByName('title_text') ? Utility::getValByName('title_text') : config('app.name', 'Smart Chamber-SaaS') }}
        - {{ __('Plan') }} </title>

    <!-- Primary Meta Tags -->
    <meta name="title" content={{ $seo_setting['meta_keywords'] }}>
    <meta name="description" content={{ $seo_setting['meta_description'] }}>

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content={{ env('APP_URL') }}>
    <meta property="og:title" content={{ $seo_setting['meta_keywords'] }}>
    <meta property="og:description" content={{ $seo_setting['meta_description'] }}>
    <meta property="og:image" content={{ asset(Storage::url('uploads/metaevent/' . $seo_setting['meta_image'])) }}>

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content={{ env('APP_URL') }}>
    <meta property="twitter:title" content={{ $seo_setting['meta_keywords'] }}>
    <meta property="twitter:description" content={{ $seo_setting['meta_description'] }}>
    <meta property="twitter:image"
        content={{ asset(Storage::url('uploads/metaevent/' . $seo_setting['meta_image'])) }}>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Favicon icon -->

    <link rel="icon"
        href="{{ $logo . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') . '?timestamp=' . time() }}"
        type="image" sizes="800x800">

    <!-- font css -->
    <!-- notification css -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/notifier.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
    <!-- vendor css -->

    <style>
        .price-card {
            overflow: inherit !important;
            margin-top: 0px !important;
        }
    </style>
    <style>
        .auth-wrapper.auth-v1 .bg-auth-side {
            content: "";
            top: 0;
            left: 0;
            right: 0;
            bottom: 40%;
            position: absolute;
        }

        .auth-wrapper.auth-v1 .navbar {
            background: rgb(255, 255, 255) !important;
        }
    </style>
    @if ($SITE_RTL == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}" id="main-style-link">
    @endif

    @if ($setting['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    @endif

    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
</head>

<body class="{{ $color }}">
    <!-- [ auth-signup ] start -->
    <div class="auth-wrapper auth-v1">
        <div class="bg-auth-side bg-primary"></div>
        <div class="auth-content">
            @yield('nav-content')

            @yield('title-content')
            <div class="row g-0 p-0">
                <div class="col-12">

                    <div class="row" style="margin:auto">
                        <div class="col-12">
                            <div class="row">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="auth-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-6">

                            <p class="text-black">
                                &copy; {{ __('Copyright') }}
                                {{ App\Models\Utility::getValByName('footer_text')
                                    ? App\Models\Utility::getValByName('footer_text')
                                    : config('app.name', 'AdvocateGo SaaS') }}
                                {{ date('Y') }}
                            </p>
                        </div>
                        <div class="col-6 text-end">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ auth-signup ] end -->
    @include('layouts.cookie_consent')

    <script src="{{ asset('assets/js/vendor-all.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.js') }}"></script>
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/notifier.js') }}"></script>
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
    @stack('custom-scripts')


    <script>
        var site_url = $('meta[name="base-url"]').attr("content");
        function show_toastr(title, message, type) {
            var o, i;
            var icon = "";
            var cls = "";
            if (type == "success") {
                cls = "primary";
                notifier.show(
                    "Success",
                    message,
                    "success",
                    site_url + "/public/assets/images/notification/ok-48.png",
                    4000
                );
            } else {
                cls = "danger";
                notifier.show(
                    "Error",
                    message,
                    "danger",
                    site_url +
                    "/public/assets/images/notification/high_priority-48.png",
                    4000
                );
            }
        }
    </script>

    @if ($message = Session::get('success'))
        <script>
            show_toastr('{{ __('Success') }}', '{!! $message !!}', 'success')
        </script>
    @endif

    @if ($message = Session::get('error'))
        <script>
            show_toastr('{{ __('Error') }}', '{!! $message !!}', 'error')
        </script>
    @endif
</body>

</html>
