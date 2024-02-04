@php
use App\Models\Utility;

$logo=asset(Storage::url('uploads/logo/'));
$company_favicon=Utility::getValByName('company_favicon');
$SITE_RTL = env('SITE_RTL');
$setting = Utility::colorset();
$seo_setting = Utility::getSeoSetting();
$color = 'theme-1';
if (!empty($company_setting['color'])) {
    $color = $company_setting['color'];
}

$SITE_RTL = 'off';
if (!empty($company_setting['SITE_RTL'])) {
    $SITE_RTL = $company_setting['SITE_RTL'];
}
$mode_setting = Utility::mode_layout();
@endphp


<!DOCTYPE html>
<html lang="en" dir="{{$company_setting['SITE_RTL'] == 'on'?'rtl':''}}">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="base-url" content="{{URL::to('/')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Primary Meta Tags -->
    <meta name="title" content={{$seo_setting['meta_keywords']}}>
    <meta name="description" content={{$seo_setting['meta_description']}}>

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content={{env('APP_URL')}}>
    <meta property="og:title" content={{$seo_setting['meta_keywords']}}>
    <meta property="og:description" content={{$seo_setting['meta_description']}}>
    <meta property="og:image" content={{asset('/'.$seo_setting['meta_image'])}}>

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content={{env('APP_URL')}}>
    <meta property="twitter:title" content={{$seo_setting['meta_keywords']}}>
    <meta property="twitter:description" content={{$seo_setting['meta_description']}}>
    <meta property="twitter:image" content={{asset(Storage::url('uploads/metaevent/'.$seo_setting['meta_image']))}}>

    <title>{{(Utility::getValByName('title_text')) ? Utility::getValByName('title_text') :
        config('app.name', 'AdvocateGo SaaS')}} - @yield('page-title')</title>

    <link rel="icon"
        href="{{$logo.'/'.(isset($company_favicon) && !empty($company_favicon)?$company_favicon:'favicon.png').'?'.time()}}"
        type="image" sizes="16x16">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">


    <!-- notification css -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/notifier.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-switch-button.css') }}">
    <!-- datatable css -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/style.css') }}">

    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">

    @if($SITE_RTL == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}" id="style">
    @endif
    @if($company_setting['cust_darklayout'] == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}" id="style">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="style">
    @endif

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}{{ " ?v=".time() }}">
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
    </style>
</head>



    <body class="{{ $color }}">
        <div class="container-fluid container-application">
            <div class="main-content position-relative">
                <div class="page-content">

                    @yield('content')
                </div>
            </div>
        </div>

        <!-- Required Js -->
        <script src="{{ asset('js/jquery.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/popper.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/simplebar.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/bootstrap.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/feather.js') }}"></script>
        <script src="{{asset('assets/js/plugins/bootstrap-switch-button.js')}}"></script>
        <script src="{{ asset('assets/js/plugins/apexcharts.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/notifier.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/sweetalert2.all.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/choices.js') }}"></script>
        <script src="{{ asset('js/jquery.form.js') }}"></script>

        @if ($message = Session::get('success'))
            <script>
                show_toastr('{{ __('Success') }}', '{!! $message !!}', 'success')
            </script>
        @endif

        @if ($message = Session::get('error'))
            <script>
                console.log('{!! $message !!}');
                show_toastr('{{ __('Error') }}', '{!! $message !!}', 'error')
            </script>
        @endif
        @stack('script-page')
    </body>

</html>
