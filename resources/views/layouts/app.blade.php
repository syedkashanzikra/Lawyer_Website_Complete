@php
    use App\Models\Utility;
    $settings = Utility::settings();
    $logo = asset('storage/uploads/logo/');

    $company_favicon = $settings['company_favicon'] ?? '';

    $SITE_RTL = env('SITE_RTL');

    $color = 'theme-1';
    if (!empty($settings['color'])) {
        $color = $settings['color'];
    }

    $SITE_RTL = '';
    if (!empty($settings['SITE_RTL'])) {
        $SITE_RTL = $settings['SITE_RTL'];
    }

    $lang = \App::getLocale('lang');
    if($lang == 'ar' || $lang == 'he'){
        $SITE_RTL = 'on';
    }

@endphp

<!DOCTYPE html>
<html lang="en" dir="{{ $SITE_RTL == 'on' ? 'rtl' : '' }}">

<head>
    <title>{{ $settings['title_text'] ? $settings['title_text'] : config('app.name', 'AdvocateGo - SaaS') }} -
        @yield('page-title') </title>
    <!-- Meta -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="keywords" content="Dashboard Template" />
    <meta name="author" content="Rajodiya Infotech" />
    <meta name="base-url" content="{{ URL::to('/') }}">


    <!-- Primary Meta Tags -->
    <meta name="title" content={{ $settings['meta_keywords'] ?? '' }}>
    <meta name="description" content={{ $settings['meta_description'] ?? '' }}>

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content={{ env('APP_URL') }}>
    <meta property="og:title" content={{ $settings['meta_keywords'] ?? '' }}>
    <meta property="og:description" content={{ $settings['meta_description'] ?? '' }}>
    <meta property="og:image" content={{ asset(Storage::url('uploads/metaevent/' . $settings['meta_image'] ?? '')) }}>

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content={{ env('APP_URL') }}>
    <meta property="twitter:title" content={{ $settings['meta_keywords'] ?? '' }}>
    <meta property="twitter:description" content={{ $settings['meta_description'] ?? '' }}>
    <meta property="twitter:image"
        content={{ asset(Storage::url('uploads/metaevent/' . $settings['meta_image'] ?? '')) }}>

    <!-- Favicon icon -->
    <link rel="icon"
        href="{{ $logo . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') . '?' . time() }}"
        type="image">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

    <!-- notification css -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/notifier.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-switch-button.css') }}">

    <!-- datatable css -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dataTables.bootstrap5.css') }}">


    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">

    @stack('style')

    @if ($SITE_RTL == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}">
    @endif
    @if ($settings['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}" id="style">
        <link rel="stylesheet" href="{{ asset('assets/css/custom-dark.css') }}" id="">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="style">
        <link rel="stylesheet" href="" id="custom-dark">
    @endif


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.css">
    <style>
        [dir="rtl"] .dash-sidebar {
            left: auto !important;
        }

        [dir="rtl"] .dash-header {
            left: 0;
            right: 280px;
        }

        [dir="rtl"] .dash-header:not(.transprent-bg) .header-wrapper {
            padding: 0 0 0 30px;
        }

        [dir="rtl"] .dash-header:not(.transprent-bg):not(.dash-mob-header)~.dash-container {
            margin-left: 0px !important;
        }

        [dir="rtl"] .me-auto.dash-mob-drp {
            margin-right: 10px !important;
        }

        [dir="rtl"] .me-auto {
            margin-left: 10px !important;
        }


        #progressBar {

            height: 8px;
            border: 1px solid #ccc;
            border-radius: 10px;
            overflow: hidden;
            padding-top: 0px;
        }

        #progressBar>div {
            width: 0;
            height: 100%;
            background-color: #3498db;
            border-radius: 8px;
        }

        .file-info {
            word-break: break-all;
        }

        .table-responsive {
            overflow-x: auto !important;
        }

        .colorinput-input {
            position: absolute;
            z-index: -1;
            opacity: 0;
        }

        .colorinput {
            margin: 0;
            position: relative;
            cursor: pointer;
        }

        .colorinput-color {
            background-color: #fdfdff;
            border-color: #e4e6fc;
            border-width: 1px;
            border-style: solid;
            display: inline-block;
            width: 1.75rem;
            height: 1.75rem;
            border-radius: 3px;
            color: #fff;
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 5%);
        }

        .fix_badges {
            min-width: 95px !important;
        }
    </style>
</head>

<body class="{{ $color }}">
    @include('partision.sidebar')

    @include('partision.header')

    <!-- [ Main Content ] start -->
    <div class="dash-container">
        <div class="dash-content p-0">
            <!-- [ breadcrumb ] start -->
            <div class="page-header px-4 py-4 border-bottom">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <div class="page-header-title">
                                <h4 class="m-b-10">@yield('page-title')</h4>
                            </div>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                        href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                                @yield('breadcrumb')
                            </ul>
                        </div>
                        <div class="col-sm-7">
                            @yield('action-button')
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            @yield('content')
        </div>
    </div>
    <!-- [ Main Content ] end -->

    <div id="commanModel" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modelCommanModelLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content ">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelCommanModelLabel"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="extra"></div>
            </div>
        </div>
    </div>

    @include('partision.footerlink')

    @stack('custom-script')
    @stack('custom-script1')

    @include('layouts.cookie_consent')

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
