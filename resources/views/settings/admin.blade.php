@extends('layouts.app')
@section('page-title')
{{ __('Settings') }}
@endsection

@php

    use App\Models\Utility;
    $color = isset($settings['color']) ? $settings['color'] : 'theme-4';
    $logo = asset('storage/uploads/logo/');
    $logo_light = Utility::getValByName('company_logo_light');
    $logo_dark = Utility::getValByName('company_logo_dark');
    $company_favicon = Utility::getValByName('company_favicon');
    $lang = Utility::getValByName('default_language');
    $file_type = config('files_types');
    $setting = Utility::settings();
    $meta_image = asset('storage/uploads/metaevent/');

    $local_storage_validation = $setting['local_storage_validation'];
    $local_storage_validations = explode(',', $local_storage_validation);

    $s3_storage_validation = $setting['s3_storage_validation'];
    $s3_storage_validations = explode(',', $s3_storage_validation);

    $wasabi_storage_validation = $setting['wasabi_storage_validation'];
    $wasabi_storage_validations = explode(',', $wasabi_storage_validation);
@endphp

@push('custom-script')
    <script>
        $(document).on("click", '.send_email', function(e) {

            e.preventDefault();
            var title = $(this).attr('data-title');

            var size = 'md';
            var url = $(this).attr('data-url');

            if (typeof url != 'undefined') {
                $("#commanModel .modal-title").html(title);
                $("#commanModel .modal-dialog").addClass('modal-' + size);
                $("#commanModel").modal('show');

                $.post(url, {
                    _token: '{{ csrf_token() }}',
                    mail_driver: $("#mail_driver").val(),
                    mail_host: $("#mail_host").val(),
                    mail_port: $("#mail_port").val(),
                    mail_username: $("#mail_username").val(),
                    mail_password: $("#mail_password").val(),
                    mail_encryption: $("#mail_encryption").val(),
                    mail_from_address: $("#mail_from_address").val(),
                    mail_from_name: $("#mail_from_name").val(),
                }, function(data) {
                    $('#commanModel .extra').html(data);
                });
            }
        });


        $(document).on('submit', '#test_email', function(e) {
            e.preventDefault();
            $("#email_sending").show();
            var post = $(this).serialize();
            var url = $(this).attr('action');
            $.ajax({
                type: "post",
                url: url,
                data: post,
                cache: false,
                beforeSend: function() {
                    $('#test_email .btn-create').attr('disabled', 'disabled');
                },
                success: function(data) {
                    if (data.is_success) {
                        show_toastr('success', data.message, 'success');
                    } else {
                        show_toastr('Error', data.message, 'error');
                    }
                    $("#email_sending").hide();
                },
                complete: function() {
                    $('#test_email .btn-create').removeAttr('disabled');
                },
            });
        });

        var themescolors = document.querySelectorAll(".themes-color > a");
        for (var h = 0; h < themescolors.length; h++) {
            var c = themescolors[h];
            c.addEventListener("click", function(event) {
                var targetElement = event.target;
                if (targetElement.tagName == "SPAN") {
                    targetElement = targetElement.parentNode;
                }
                var temp = targetElement.getAttribute("data-value");
                removeClassByPrefix(document.querySelector("body"), "theme-");
                document.querySelector("body").classList.add(temp);
            });
        }

        if ($('#cust-theme-bg').length > 0) {
            var custthemebg = document.querySelector("#cust-theme-bg");
            custthemebg.addEventListener("click", function() {
                if (custthemebg.checked) {
                    document.querySelector(".dash-sidebar").classList.add("transprent-bg");
                    document
                        .querySelector(".dash-header:not(.dash-mob-header)")
                        .classList.add("transprent-bg");
                } else {
                    document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
                    document
                        .querySelector(".dash-header:not(.dash-mob-header)")
                        .classList.remove("transprent-bg");
                }
            });
        }

        if ($('#cust-darklayout').length > 0) {
            var custthemedark = document.querySelector("#cust-darklayout");
            custthemedark.addEventListener("click", function() {
                if (custthemedark.checked) {
                    $('#style').attr('href','{{env("APP_URL")}}'+'/public/assets/css/style-dark.css');
                    $('#custom-dark').attr('href','{{env("APP_URL")}}'+'/public/assets/css/custom-dark.css');
                    $('.dash-sidebar .main-logo a img').attr('src','{{$logo.$logo_light}}');

                } else {
                    $('#style').attr('href','{{env("APP_URL")}}'+'/public/assets/css/style.css');
                    $('.dash-sidebar .main-logo a img').attr('src','{{$logo.$logo_dark}}');
                }
            });
        }

        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300,
        })

        $(document).ready(function(){
            $(".list-group-item").first().addClass('active');

            $(".list-group-item").on('click',function() {
                $(".list-group-item").removeClass('active')
                $(this).addClass('active');
            });
        })

        function check_theme(color_val) {
            $('input[value="' + color_val + '"]').prop('checked', true);
            $('a[data-value]').removeClass('active_color');
            $('a[data-value="' + color_val + '"]').addClass('active_color');
        }

        $(document).on('change', '[name=storage_setting]', function()
        {
            if ($(this).val() == 's3') {
                $('.s3-setting').removeClass('d-none');
                $('.wasabi-setting').addClass('d-none');
                $('.local-setting').addClass('d-none');
            } else if ($(this).val() == 'wasabi') {
                $('.s3-setting').addClass('d-none');
                $('.wasabi-setting').removeClass('d-none');
                $('.local-setting').addClass('d-none');
            } else {
                $('.s3-setting').addClass('d-none');
                $('.wasabi-setting').addClass('d-none');
                $('.local-setting').removeClass('d-none');
            }
        });
    </script>

    <script type="text/javascript">
        function enablecookie() {
            const element = $('#enable_cookie').is(':checked');
            $('.cookieDiv').addClass('disabledCookie');
            if (element==true) {
                $('.cookieDiv').removeClass('disabledCookie');
                $("#cookie_logging").attr('checked', true);
            } else {
                $('.cookieDiv').addClass('disabledCookie');
                $("#cookie_logging").attr('checked', false);
            }
        }
    </script>
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Settings') }}</li>
@endsection

@section('content')
<style>
    .list-group-item.active {
        border: none !important;
    }
</style>

<div class="row ">
    <!-- [ sample-page ] start -->
    <div class="col-sm-12">
        <div class="row g-0">
            <div class="col-xl-3 border-end border-bottom">
                <div class="card shadow-none bg-transparent sticky-top" style="top:70px">
                    <div class="list-group list-group-flush rounded-0" id="useradd-sidenav">
                        <a href="#useradd-1" class="list-group-item list-group-item-action">{{ __('Brand Settings') }}
                            <div class="float-end dark"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-2" class="list-group-item list-group-item-action">{{ __('Email Settings') }}
                            <div class="float-end dark"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-3" class="list-group-item list-group-item-action">{{ __('Payment Settings') }}
                            <div class="float-end "><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-4" class="list-group-item list-group-item-action">{{ __('SEO Settings') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-5" class="list-group-item list-group-item-action">{{ __('ReCaptcha Settings') }}
                            <div class="float-end "><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-6" class="list-group-item list-group-item-action">{{ __('Cache Settings') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-7" class="list-group-item list-group-item-action">{{ __('Storage Settings') }}
                            <div class="float-end dark"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-8" class="list-group-item list-group-item-action">{{ __('Cookie Settings') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                        <a href="#useradd-9" class="list-group-item list-group-item-action">{{ __('Pusher Settings') }}
                            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                        </a>
                         <a href="#location-list" class="list-group-item list-group-item-action border-0" id="contry-city-state">{{ __('Country/ State/ City Settings') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                    </div>
                </div>
            </div>

            <div class="col-xl-9" data-bs-spy="scroll" data-bs-target="#useradd-sidenav" data-bs-offset="0" tabindex="0">

                <!--Business Setting-->
                <div class="card shadow-none rounded-0 border-bottom" id="useradd-1">
                    {{ Form::model($settings, ['route' => 'business.setting', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                    <div class="card-header">
                        <h5>{{ __('Brand Settings') }}</h5>
                        <small class="text-muted">{{ __('Edit your brand details') }}</small>
                    </div>

                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-lg-4 col-sm-6 col-md-6 dashboard-card">
                                <div class="card shadow-none border rounded-0">
                                    <div class="card-header">
                                        <h5>{{ __('Logo dark') }}</h5>
                                    </div>
                                    <div class="card-body ">
                                        <div class=" setting-card">
                                            <div class="d-flex flex-column justify-content-between align-items-center h-100">
                                                <div class="logo-content mt-4">
                                                    <a href="{{ $logo .'/'. (isset($logo_dark) && !empty($logo_dark) ? $logo_dark : 'logo-dark.png') }}" target="_blank">
                                                        <img id="blah" alt="your image"
                                                            src="{{ $logo .'/'. (isset($logo_dark) && !empty($logo_dark) ? $logo_dark : 'logo-dark.png') .'?'.time()}}" width="200px"
                                                            class="big-logo img_setting">
                                                    </a>
                                                </div>
                                                <div class="choose-files mt-5">
                                                    <label for="company_logo">
                                                        <div class=" bg-primary company_logo_update m-auto"> <i class="ti ti-upload px-1"></i>{{ __('Choose file here')
                                                            }}
                                                        </div>
                                                        <input type="file" name="company_logo_dark" id="company_logo" class="form-control file"
                                                            data-filename="company_logo_update"
                                                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                                                    </label>
                                                </div>
                                                @error('company_logo')
                                                <div class="row">
                                                    <span class="invalid-logo" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                </div>
                                                @enderror
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-md-6 dashboard-card">
                                <div class="card shadow-none border rounded-0">
                                    <div class="card-header">
                                        <h5>{{ __('Logo Light') }}</h5>
                                    </div>
                                    <div class="card-body ">
                                        <div class=" setting-card">
                                            <div class="d-flex flex-column justify-content-between align-items-center h-100">
                                            <div class="logo-content mt-4">
                                                <a href="{{ $logo .'/'. (isset($logo_light) && !empty($logo_light) ? $logo_light : 'logo-light.png') }}"
                                                    target="_blank">
                                                    <img id="blah1" alt="your image"
                                                        src="{{ $logo .'/'. (isset($logo_light) && !empty($logo_light) ? $logo_light : 'logo-light.png').'?'.time() }}"
                                                        width="200px" class="big-logo img_setting">
                                                </a>
                                            </div>
                                            <div class="choose-files mt-5">
                                                <label for="company_logo_light">
                                                    <div class=" bg-primary dark_logo_update m-auto"> <i
                                                            class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                    </div>
                                                    <input type="file" name="company_logo_light" id="company_logo_light"
                                                        class="form-control file" data-filename="dark_logo_update"
                                                        onchange="document.getElementById('blah1').src = window.URL.createObjectURL(this.files[0])">
                                                </label>
                                            </div>
                                            @error('company_logo_light')
                                            <div class="row">
                                                <span class="invalid-logo" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            </div>
                                            @enderror
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-md-6 dashboard-card">
                                <div class="card shadow-none border rounded-0">
                                    <div class="card-header">
                                        <h5>{{ __('Favicon') }}</h5>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class=" setting-card">
                                            <div class="d-flex flex-column justify-content-between align-items-center h-100">
                                            <div class="logo-content mt-4">
                                                <a href="{{ $logo .'/'. (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') }}"
                                                    target="_blank">
                                                    <img id="blah2" alt="your image"
                                                        src="{{ $logo .'/'. (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png').'?'.time()}}"
                                                        width="80px" class="big-logo img_setting">
                                                </a>

                                            </div>
                                            <div class="choose-files mt-4">
                                                <label for="company_favicon">
                                                    <div class="bg-primary company_favicon_update m-auto"> <i
                                                            class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                    </div>
                                                    <input type="file" name="company_favicon" id="company_favicon"
                                                        class="form-control file" data-filename="company_favicon_update"
                                                        onchange="document.getElementById('blah2').src = window.URL.createObjectURL(this.files[0])">
                                                </label>
                                            </div>
                                            @error('logo')
                                            <div class="row">
                                                <span class="invalid-logo" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            </div>
                                            @enderror
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('title_text', __('Title Text'), ['class' => 'form-label']) }}
                                        {{ Form::text('title_text', Utility::getValByName('title_text'), ['class' =>
                                        'form-control', 'placeholder' => __('Title Text')]) }}
                                        @error('title_text')
                                        <span class="invalid-title_text" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('footer_text', __('Footer Text'), ['class' => 'form-label']) }}
                                        {{ Form::text('footer_text', Utility::getValByName('footer_text'), ['class' =>
                                        'form-control', 'placeholder' => __('Enter Footer Text')]) }}
                                        @error('footer_text')
                                        <span class="invalid-footer_text" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        {{ Form::label('default_language', __('Default Language'), ['class' =>
                                        'form-label']) }}
                                        <div class="changeLanguage">

                                            <select name="default_language" id="default_language"
                                                class="form-control select">
                                                @foreach (\App\Models\Utility::languages() as $code => $language)
                                                                <option @if ($lang == $code) selected @endif
                                                                    value="{{ $code }}">
                                                                    {{ ucFirst($language) }}</option>
                                                            @endforeach
                                            </select>
                                        </div>
                                        @error('default_language')
                                        <span class="invalid-default_language" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="row">
                                        <div class="col-3 my-auto">
                                            <div class="form-group">
                                                <label class="text-dark mb-1 mt-3" for="SITE_RTL">{{ __('Enable RTL') }}</label>
                                                <div class="">
                                                    <input type="checkbox" name="SITE_RTL" id="SITE_RTL"
                                                        data-toggle="switchbutton" {{ $settings['SITE_RTL']=='on'
                                                        ? 'checked="checked"' : '' }} data-onstyle="primary">
                                                    <label class="form-check-labe" for="SITE_RTL"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="col switch-width">
                                                <div class="form-group ml-2 mr-3">
                                                    {{Form::label('signup_button',__('Enable Sign-Up Page'),array('class'=>'col-form-label')) }}
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" data-toggle="switchbutton"
                                                            data-onstyle="primary" class="" name="signup_button"
                                                            id="signup_button" {{ $settings['signup_button']=='on'
                                                            ? 'checked="checked"' : '' }}>
                                                        <label class="custom-control-label mb-1"
                                                            for="signup_button"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3  ">
                                            <div class="form-group">
                                                <label class="text-dark mb-1 mt-3" for="email_verification">{{ __('Email Verification') }}</label>
                                                <div class="">
                                                    <input type="checkbox" name="email_verification" id="email_verification" data-toggle="switchbutton" {{
                                                        $settings['email_verification']=='on' ? 'checked="checked"' : '' }} data-onstyle="primary">
                                                    <label class="form-check-label" for="email_verification"></label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-3">
                                            <div class="form-group ">
                                                    <label class="text-dark mb-1 mt-3" for="display_landing_page">{{ __('Enable Landing Page') }}</label>
                                                <div class="">
                                                    <input type="checkbox" name="display_landing_page" class="form-check-input"
                                                        id="display_landing_page" data-toggle="switchbutton"
                                                        {{ $settings['display_landing_page'] == 'on' ? 'checked="checked"' : '' }}
                                                        data-onstyle="primary">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <h4 class="small-title">{{ __('Theme Customizer') }}</h4>
                            <div class="setting-card setting-logo-box p-3">
                                <div class="row">
                                    <div class="col-4 my-auto">
                                        <h6 class="mt-2">
                                            <i data-feather="credit-card" class="me-2"></i>{{ __('Primary color
                                            settings') }}
                                        </h6>
                                        <hr class="my-2" />
                                        <div class="theme-color themes-color">

                                            <a href="#!" class="{{ $settings['color'] == 'theme-1' ? 'active_color' : '' }}" data-value="theme-1"
                                                onclick="check_theme('theme-1')"></a>
                                            <input type="radio" class="theme_color" name="color" value="theme-1" style="display: none;">
                                            <a href="#!" class="{{ $settings['color'] == 'theme-2' ? 'active_color' : '' }} " data-value="theme-2"
                                                onclick="check_theme('theme-2')"></a>
                                            <input type="radio" class="theme_color" name="color" value="theme-2" style="display: none;">
                                            <a href="#!" class="{{ $settings['color'] == 'theme-3' ? 'active_color' : '' }}" data-value="theme-3"
                                                onclick="check_theme('theme-3')"></a>
                                            <input type="radio" class="theme_color" name="color" value="theme-3" style="display: none;">
                                            <a href="#!" class="{{ $settings['color'] == 'theme-4' ? 'active_color' : '' }}" data-value="theme-4"
                                                onclick="check_theme('theme-4')"></a>
                                            <input type="radio" class="theme_color" name="color" value="theme-4" style="display: none;">
                                            <a href="#!" class="{{ $settings['color'] == 'theme-5' ? 'active_color' : '' }}" data-value="theme-5"
                                                onclick="check_theme('theme-5')"></a>
                                            <input type="radio" class="theme_color" name="color" value="theme-5" style="display: none;">
                                            <br>
                                            <a href="#!" class="{{ $settings['color'] == 'theme-6' ? 'active_color' : '' }}" data-value="theme-6"
                                                onclick="check_theme('theme-6')"></a>
                                            <input type="radio" class="theme_color" name="color" value="theme-6" style="display: none;">
                                            <a href="#!" class="{{ $settings['color'] == 'theme-7' ? 'active_color' : '' }}" data-value="theme-7"
                                                onclick="check_theme('theme-7')"></a>
                                            <input type="radio" class="theme_color" name="color" value="theme-7" style="display: none;">
                                            <a href="#!" class="{{ $settings['color'] == 'theme-8' ? 'active_color' : '' }}" data-value="theme-8"
                                                onclick="check_theme('theme-8')"></a>
                                            <input type="radio" class="theme_color" name="color" value="theme-8" style="display: none;">
                                            <a href="#!" class="{{ $settings['color'] == 'theme-9' ? 'active_color' : '' }}" data-value="theme-9"
                                                onclick="check_theme('theme-9')"></a>
                                            <input type="radio" class="theme_color" name="color" value="theme-9" style="display: none;">
                                            <a href="#!" class="{{ $settings['color'] == 'theme-10' ? 'active_color' : '' }}" data-value="theme-10"
                                                onclick="check_theme('theme-10')"></a>
                                            <input type="radio" class="theme_color" name="color" value="theme-10" style="display: none;">
                                        </div>
                                    </div>
                                    <div class="col-4 ">
                                        <h6 class="mt-2">
                                            <i data-feather="layout" class="me-2"></i>{{ __('Sidebar settings') }}
                                        </h6>
                                        <hr class="my-2" />
                                        <div class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input" id="cust-theme-bg"
                                                name="cust_theme_bg" {{ Utility::getValByName('cust_theme_bg')=='on'
                                                ? 'checked' : '' }} />
                                            <label class="form-check-label f-w-600 pl-1" for="cust-theme-bg">{{
                                                __('Transparent layout') }}</label>
                                        </div>
                                    </div>
                                    <div class="col-4 ">
                                        <h6 class="mt-2">
                                            <i data-feather="sun" class="me-2"></i>{{ __('Layout settings') }}
                                        </h6>
                                        <hr class="my-2" />
                                        <div class="form-check form-switch mt-2">
                                            <input type="checkbox" class="form-check-input" id="cust-darklayout"
                                                name="cust_darklayout" {{ Utility::getValByName('cust_darklayout')=='on'
                                                ? 'checked' : '' }} />
                                            <label class="form-check-label f-w-600 pl-1" for="cust-darklayout">{{
                                                __('Dark Layout') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-end pb-0 pe-0">
                                <div class="form-group">
                                    <input class="btn btn-print-invoice btn-primary m-r-10" type="submit"
                                        value="{{ __('Save Changes') }}">
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>

                <!--Email Setting-->
                <div class="card shadow-none rounded-0 border-bottom" id="useradd-2">
                    <div class="card-header">
                        <h5>{{ __('Email Settings') }}</h5>
                    </div>
                    {{ Form::open(['route' => 'email.settings', 'method' => 'post']) }}
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('mail_driver', __('Mail Driver'), ['class' => 'form-label']) }}
                                    {{ Form::text('mail_driver', $settings['mail_driver'], ['class' => 'form-control','id'=>'mail_driver', 'placeholder' => __('Enter Mail Driver')]) }}
                                    @error('mail_driver')
                                    <span class="invalid-mail_driver" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('mail_host', __('Mail Host'), ['class' => 'form-label']) }}
                                    {{ Form::text('mail_host', $settings['mail_host'], ['class' => 'form-control ','id'=>'mail_host',
                                    'placeholder' => __('Enter Mail Host')]) }}
                                    @error('mail_host')
                                    <span class="invalid-mail_driver" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('mail_port', __('Mail Port'), ['class' => 'form-label']) }}
                                    {{ Form::text('mail_port', $settings['mail_port'], ['class' => 'form-control','id'=>'mail_port',
                                    'placeholder' => __('Enter Mail Port')]) }}
                                    @error('mail_port')
                                    <span class="invalid-mail_port" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('mail_username', __('Mail Username'), ['class' => 'form-label']) }}
                                    {{ Form::text('mail_username', $settings['mail_username'], ['class' => 'form-control','id'=>'mail_username',
                                    'placeholder' => __('Enter Mail Username')]) }}
                                    @error('mail_username')
                                    <span class="invalid-mail_username" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('mail_password', __('Mail Password'), ['class' => 'form-label']) }}
                                    {{ Form::text('mail_password', $settings['mail_password'], ['class' => 'form-control','id'=>'mail_password',
                                    'placeholder' => __('Enter Mail Password')]) }}
                                    @error('mail_password')
                                    <span class="invalid-mail_password" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('mail_encryption', __('Mail Encryption'), ['class' => 'form-label'])
                                    }}
                                    {{ Form::text('mail_encryption', $settings['mail_encryption'], ['class' => 'form-control','id'=>'mail_encryption',
                                    'placeholder' => __('Enter Mail Encryption')]) }}
                                    @error('mail_encryption')
                                    <span class="invalid-mail_encryption" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('mail_from_address', __('Mail From Address'), ['class' =>
                                    'form-label']) }}
                                    {{ Form::text('mail_from_address', $settings['mail_from_address'], ['class' =>
                                    'form-control','id'=>'mail_from_address', 'placeholder' => __('Enter Mail From Address')]) }}
                                    @error('mail_from_address')
                                    <span class="invalid-mail_from_address" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('mail_from_name', __('Mail From Name'), ['class' => 'form-label']) }}
                                    {{ Form::text('mail_from_name', $settings['mail_from_name'], ['class' => 'form-control','id'=>'mail_from_name',
                                    'placeholder' => __('Enter Mail From Name')]) }}
                                    @error('mail_from_name')
                                    <span class="invalid-mail_from_name" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer pb-0">
                        <div class="row">
                            <div class="form-group col-md-6 col-6">
                                <a href="#" class="btn btn-primary  send_email" data-title="{{ __('Send Test Mail') }}"
                                    data-url="{{ route('test.mail') }}" >
                                    {{ __('Send Test Mail') }}
                                </a>
                            </div>
                            <div class="form-group col-md-6 col-6 text-end">
                                <input class="btn btn-primary" type="submit" value="{{ __('Save Changes') }}">
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>

                <div class="card shadow-none rounded-0 border-bottom" id="useradd-3">
                    <div class="card-header">
                        <h5>{{__('Payment Settings')}}</h5>
                        <small class="text-secondary font-weight-bold">{{__("These details will be used to collect subscription plan payments.Each subscription plan will have a payment button based on the below configuration.")}}</small>
                    </div>
                    <form id="setting-form" method="post" action="{{route('admin.payment.settings')}}">
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label class="col-form-label">{{__('Currency')}} *</label>
                                            <input type="text" name="currency" class="form-control" id="currency"
                                                value="{{(!isset($payment['currency']) || is_null($payment['currency'])) ? '' : $payment['currency']}}"
                                                required>
                                            <small class="text-xs">
                                                {{ __('Note: Add currency code as per three-letter ISO code') }}.
                                                <a href="https://stripe.com/docs/currencies" target="_blank">{{ __('You can find out how to do that here.') }}</a>
                                            </small>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="currency_symbol" class="col-form-label">{{__('Currency Symbol')}} *</label>
                                            <input type="text" name="currency_symbol" class="form-control"
                                                id="currency_symbol"
                                                value="{{(!isset($payment['currency_symbol']) || is_null($payment['currency_symbol'])) ? '' : $payment['currency_symbol']}}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="faq justify-content-center">
                                        <div class="row">
                                            <div class="accordion accordion-flush setting-accordion"
                                                id="accordionExample">
                                                {{-- maually --}}
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="heading-2-15">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse15"
                                                            aria-expanded="false" aria-controls="collapse15">
                                                            <span class="d-flex align-items-center">

                                                                {{ __('Manually') }}
                                                            </span>

                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{ __('Enable') }}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_manually_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input input-primary" name="is_manually_enabled"
                                                                    id="is_manually_enabled" {{ isset($payment['is_manually_enabled']) &&
                                                                    $payment['is_manually_enabled']=='on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label" for="customswitchv1-1"></label>
                                                            </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse15" class="accordion-collapse collapse" aria-labelledby="heading-2-15"
                                                        data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pb-4">
                                                                    <div class="row pt-2">
                                                                        <label class="pb-2" for="is_manually_enabled">{{ __('Requesting manual payment for the planned
                                                                            amount for the subscriptions paln.') }}</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- bank-transfer --}}
                                                <div class="accordion-item ">
                                                    <h2 class="accordion-header" id="heading-2-16">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse16"
                                                            aria-expanded="false" aria-controls="collapse16">
                                                            <span class="d-flex align-items-center">

                                                                {{ __('Bank Transfer') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{ __('Enable') }}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_bank_enabled" value="off">
                                                                    <input type="checkbox" class="form-check-input input-primary" name="is_bank_enabled"
                                                                        id="is_bank_enabled" {{ isset($payment['is_bank_enabled']) && $payment['is_bank_enabled']=='on'
                                                                        ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label" for="customswitchv1-1"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse16" class="accordion-collapse collapse" aria-labelledby="heading-2-16"
                                                        data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row gy-4">
                                                                <div class="col-md-6 mt-3">
                                                                    <div class="form-group">
                                                                        {!! Form::label('inputname', 'Bank Details', ['class' => 'col-form-label']) !!}

                                                                        @php
                                                                        $bank_details = !empty($payment['bank_details']) ? $payment['bank_details'] : '';
                                                                        @endphp
                                                                        {!! Form::textarea('bank_details', $bank_details, [
                                                                        'class' => 'form-control',
                                                                        'rows' => '6']) !!}
                                                                        <small class="text-xs">
                                                                            {{ __('Example : Bank : Bank Name <br> Account Number : 0000 0000 <br>') }}.
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- Stripe --}}
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingOne">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                                            aria-expanded="false" aria-controls="collapseOne">
                                                            <span class="d-flex align-items-center">
                                                                <i class=""></i> {{
                                                                __('Stripe') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{ __('Enable') }}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_stripe_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        name="is_stripe_enabled" id="is_stripe_enabled"
                                                                        {{ isset($payment['is_stripe_enabled']) &&
                                                                        $payment['is_stripe_enabled']=='on'
                                                                        ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="customswitchv1-1"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseOne" class="accordion-collapse collapse"
                                                        aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="stripe_key"
                                                                            class="col-form-label">{{__('Stripe
                                                                            Key')}}</label>
                                                                        <input class="form-control"
                                                                            placeholder="{{__('Stripe Key')}}"
                                                                            name="stripe_key" type="text"
                                                                            value="{{(!isset($payment['stripe_key']) || is_null($payment['stripe_key'])) ? '' : $payment['stripe_key']}}"
                                                                            id="stripe_key">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="stripe_secret"
                                                                            class="col-form-label">{{__('Stripe
                                                                            Secret')}}</label>
                                                                        <input class="form-control "
                                                                            placeholder="{{ __('Stripe Secret') }}"
                                                                            name="stripe_secret" type="text"
                                                                            value="{{(!isset($payment['stripe_secret']) || is_null($payment['stripe_secret'])) ? '' : $payment['stripe_secret']}}"
                                                                            id="stripe_secret">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- Paypal --}}
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingTwo">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                            aria-expanded="false" aria-controls="collapseTwo">
                                                            <span class="d-flex align-items-center">
                                                                <i class=""></i> {{
                                                                __('Paypal') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{ __('Enable') }}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_paypal_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        id="customswitchv1-2" name="is_paypal_enabled"
                                                                        id="is_paypal_enabled" {{
                                                                        isset($payment['is_paypal_enabled']) &&
                                                                        $payment['is_paypal_enabled']=='on'
                                                                        ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="customswitchv1-2"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>

                                                    <div id="collapseTwo" class="accordion-collapse collapse"
                                                        aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="d-flex">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label
                                                                                    class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="paypal_mode"
                                                                                        value="sandbox"
                                                                                        class="form-check-input" {{
                                                                                        !isset($payment['paypal_mode'])
                                                                                        ||
                                                                                        $payment['paypal_mode']==''
                                                                                        ||
                                                                                        $payment['paypal_mode']=='sandbox'
                                                                                        ? 'checked="checked"' : '' }}>
                                                                                    {{__('Sandbox')}}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label
                                                                                    class="form-check-labe text-dark">
                                                                                    <input type="radio"
                                                                                        name="paypal_mode" value="live"
                                                                                        class="form-check-input" {{
                                                                                        isset($payment['paypal_mode'])
                                                                                        &&
                                                                                        $payment['paypal_mode']=='live'
                                                                                        ? 'checked="checked"' : '' }}>
                                                                                    {{__('Live')}}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="col-form-label"
                                                                            for="paypal_client_id">{{ __('Client ID')
                                                                            }}</label>
                                                                        <input type="text" name="paypal_client_id"
                                                                            id="paypal_client_id" class="form-control"
                                                                            value="{{(!isset($payment['paypal_client_id']) || is_null($payment['paypal_client_id'])) ? '' : $payment['paypal_client_id']}}"
                                                                            placeholder="{{ __('Client ID') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="col-form-label"
                                                                            for="paypal_secret_key">{{ __('Secret Key')
                                                                            }}</label>
                                                                        <input type="text" name="paypal_secret_key"
                                                                            id="paypal_secret_key" class="form-control"
                                                                            value="{{(!isset($payment['paypal_secret_key']) || is_null($payment['paypal_secret_key'])) ? '' : $payment['paypal_secret_key']}}"
                                                                            placeholder="{{ __('Secret Key') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- Paystack --}}
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingThree">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                                            aria-expanded="false" aria-controls="collapseTwo">
                                                            <span class="d-flex align-items-center">
                                                                <i class=""></i>
                                                                {{ __('Paystack') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{ __('Enable') }}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_paystack_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        id="customswitchv1-2" name="is_paystack_enabled"
                                                                        id="is_paystack_enabled" {{
                                                                        isset($payment['is_paystack_enabled']) &&
                                                                        $payment['is_paystack_enabled']=='on'
                                                                        ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="customswitchv1-2"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseThree" class="accordion-collapse collapse"
                                                        aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paypal_client_id"
                                                                            class="col-form-label">{{ __('Public
                                                                            Key')}}</label>
                                                                        <input type="text" name="paystack_public_key"
                                                                            id="paystack_public_key"
                                                                            class="form-control"
                                                                            value="{{isset($payment['paystack_public_key']) ? $payment['paystack_public_key']:''}}"
                                                                            placeholder="{{ __('Public Key') }}" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paystack_secret_key"
                                                                            class="col-form-label">{{ __('Secret Key')
                                                                            }}</label>
                                                                        <input type="text" name="paystack_secret_key"
                                                                            id="paystack_secret_key"
                                                                            class="form-control"
                                                                            value="{{isset($payment['paystack_secret_key']) ? $payment['paystack_secret_key']:''}}"
                                                                            placeholder="{{ __('Secret Key') }}" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- Flutterwave --}}
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingFour">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                                            aria-expanded="false" aria-controls="collapseTwo">
                                                            <span class="d-flex align-items-center">
                                                                <i class=""></i> {{
                                                                __('Flutterwave') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('Enable')}}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_flutterwave_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        id="customswitchv1-2"
                                                                        name="is_flutterwave_enabled"
                                                                        id="is_flutterwave_enabled" {{
                                                                        isset($payment['is_flutterwave_enabled']) &&
                                                                        $payment['is_flutterwave_enabled']=='on'
                                                                        ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="customswitchv1-2"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseFour" class="accordion-collapse collapse"
                                                        aria-labelledby="headingFour"
                                                        data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paypal_client_id"
                                                                            class="col-form-label">{{ __('Public
                                                                            Key')}}</label>
                                                                        <input type="text" name="flutterwave_public_key"
                                                                            id="flutterwave_public_key"
                                                                            class="form-control"
                                                                            value="{{(!isset($payment['flutterwave_public_key']) || is_null($payment['flutterwave_public_key'])) ? '' : $payment['flutterwave_public_key']}}"
                                                                            placeholder="Public Key">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paystack_secret_key"
                                                                            class="col-form-label">{{ __('Secret Key')
                                                                            }}</label>
                                                                        <input type="text" name="flutterwave_secret_key"
                                                                            id="flutterwave_secret_key"
                                                                            class="form-control"
                                                                            value="{{(!isset($payment['flutterwave_secret_key']) || is_null($payment['flutterwave_secret_key'])) ? '' : $payment['flutterwave_secret_key']}}"
                                                                            placeholder="Secret Key">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- Razorpay --}}
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingFive">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseFive"
                                                            aria-expanded="false" aria-controls="collapseFive">
                                                            <span class="d-flex align-items-center">
                                                                <i class=""></i> {{
                                                                __('Razorpay') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2"> {{__('Enable')}} </span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_razorpay_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        id="customswitchv1-2" name="is_razorpay_enabled"
                                                                        id="is_razorpay_enabled" {{
                                                                        isset($payment['is_razorpay_enabled']) &&
                                                                        $payment['is_razorpay_enabled']=='on'
                                                                        ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="customswitchv1-2"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseFive" class="accordion-collapse collapse"
                                                        aria-labelledby="headingFive"
                                                        data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paypal_client_id"
                                                                            class="col-form-label">{{ __('Public
                                                                            Key')}}</label>

                                                                        <input type="text" name="razorpay_public_key"
                                                                            id="razorpay_public_key"
                                                                            class="form-control"
                                                                            value="{{(!isset($payment['razorpay_public_key']) || is_null($payment['razorpay_public_key'])) ? '' : $payment['razorpay_public_key']}}"
                                                                            placeholder="Public Key">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paystack_secret_key"
                                                                            class="col-form-label"> {{ __('Secret
                                                                            Key')}}</label>
                                                                        <input type="text" name="razorpay_secret_key"
                                                                            id="razorpay_secret_key"
                                                                            class="form-control"
                                                                            value="{{(!isset($payment['razorpay_secret_key']) || is_null($payment['razorpay_secret_key'])) ? '' : $payment['razorpay_secret_key']}}"
                                                                            placeholder="Secret Key">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- Paytm --}}
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingSix">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseSix"
                                                            aria-expanded="false" aria-controls="collapseSix">
                                                            <span class="d-flex align-items-center">
                                                                <i class=""></i> {{
                                                                __('Paytm') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('Enable')}}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_paytm_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        name="is_paytm_enabled" id="is_paytm_enabled" {{
                                                                        isset($payment['is_paytm_enabled']) &&
                                                                        $payment['is_paytm_enabled']=='on'
                                                                        ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="customswitchv1-2"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseSix" class="accordion-collapse collapse"
                                                        aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-12 pb-4">
                                                                    <label class="paypal-label col-form-label"
                                                                        for="paypal_mode">{{ __('Paytm
                                                                        Environment')}}</label> <br>
                                                                    <div class="d-flex">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="paytm_mode"
                                                                                            value="local"
                                                                                            class="form-check-input" {{
                                                                                            !isset($payment['paytm_mode'])
                                                                                            ||
                                                                                            $payment['paytm_mode']==''
                                                                                            ||
                                                                                            $payment['paytm_mode']=='local'
                                                                                            ? 'checked="checked"' : ''
                                                                                            }}>
                                                                                        {{__('Local')}}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="paytm_mode"
                                                                                            value="production"
                                                                                            class="form-check-input" {{
                                                                                            isset($payment['paytm_mode'])
                                                                                            &&
                                                                                            $payment['paytm_mode']=='production'
                                                                                            ? 'checked="checked"' : ''
                                                                                            }}>
                                                                                        {{__('Production')}}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="paytm_public_key"
                                                                            class="col-form-label">{{ __('Merchant
                                                                            ID')}}</label>
                                                                        <input type="text" name="paytm_merchant_id"
                                                                            id="paytm_merchant_id" class="form-control"
                                                                            value="{{isset($payment['paytm_merchant_id'])? $payment['paytm_merchant_id']:''}}"
                                                                            placeholder="{{ __('Merchant ID') }}" />
                                                                        @if ($errors->has('paytm_merchant_id'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('paytm_merchant_id') }}
                                                                        </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="paytm_secret_key"
                                                                            class="col-form-label">{{ __('Merchant Key')
                                                                            }}</label>
                                                                        <input type="text" name="paytm_merchant_key"
                                                                            id="paytm_merchant_key" class="form-control"
                                                                            value="{{ isset($payment['paytm_merchant_key']) ? $payment['paytm_merchant_key']:''}}"
                                                                            placeholder="{{ __('Merchant Key') }}" />
                                                                        @if ($errors->has('paytm_merchant_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('paytm_merchant_key') }}
                                                                        </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label for="paytm_industry_type"
                                                                            class="col-form-label">{{ __('Industry
                                                                            Type') }}</label>
                                                                        <input type="text" name="paytm_industry_type"
                                                                            id="paytm_industry_type"
                                                                            class="form-control"
                                                                            value="{{isset($payment['paytm_industry_type']) ?$payment['paytm_industry_type']:''}}"
                                                                            placeholder="{{ __('Industry Type') }}" />
                                                                        @if ($errors->has('paytm_industry_type'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('paytm_industry_type') }}
                                                                        </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- Mercado Pago --}}
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingseven">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseseven"
                                                            aria-expanded="false" aria-controls="collapseseven">
                                                            <span class="d-flex align-items-center">
                                                                <i class=""></i> {{
                                                                __('Mercado Pago') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('Enable')}}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_mercado_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        name="is_mercado_enabled"
                                                                        id="is_mercado_enabled"
                                                                        {{isset($payment['is_mercado_enabled']) &&
                                                                        $payment['is_mercado_enabled']=='on'
                                                                        ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="customswitchv1-2"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseseven" class="accordion-collapse collapse"
                                                        aria-labelledby="headingseven"
                                                        data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">

                                                                <div class="col-md-12 pb-4">
                                                                    <label class="coingate-label col-form-label"
                                                                        for="mercado_mode">{{__('Mercado
                                                                        Mode')}}</label> <br>
                                                                    <div class="d-flex">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="mercado_mode"
                                                                                            value="sandbox"
                                                                                            class="form-check-input" {{
                                                                                            isset($payment['mercado_mode'])
                                                                                            &&
                                                                                            $payment['mercado_mode']==''
                                                                                            ||
                                                                                            isset($payment['mercado_mode'])
                                                                                            &&
                                                                                            $payment['mercado_mode']=='sandbox'
                                                                                            ? 'checked="checked"' : ''
                                                                                            }}>
                                                                                        {{__('Sandbox')}}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="mercado_mode"
                                                                                            value="live"
                                                                                            class="form-check-input" {{
                                                                                            isset($payment['mercado_mode'])
                                                                                            &&
                                                                                            $payment['mercado_mode']=='live'
                                                                                            ? 'checked="checked"' : ''
                                                                                            }}>
                                                                                        {{__('Live')}}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="mercado_access_token"
                                                                            class="col-form-label">{{ __('Access Token')
                                                                            }}</label>
                                                                        <input type="text" name="mercado_access_token"
                                                                            id="mercado_access_token"
                                                                            class="form-control"
                                                                            value="{{isset($payment['mercado_access_token']) ? $payment['mercado_access_token']:''}}"
                                                                            placeholder="{{ __('Access Token') }}" />
                                                                        @if ($errors->has('mercado_secret_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('mercado_access_token') }}
                                                                        </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingeight">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseeight"
                                                            aria-expanded="false" aria-controls="collapseeight">
                                                            <span class="d-flex align-items-center">
                                                                <i class=""></i> {{
                                                                __('Mollie') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('Enable')}}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_mollie_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        name="is_mollie_enabled" id="is_mollie_enabled"
                                                                        {{ isset($payment['is_mollie_enabled']) &&
                                                                        $payment['is_mollie_enabled']=='on'
                                                                        ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="customswitchv1-2"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseeight" class="accordion-collapse collapse"
                                                        aria-labelledby="headingeight"
                                                        data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="mollie_api_key"
                                                                            class="col-form-label">{{ __('Mollie Api
                                                                            Key')}}</label>
                                                                        <input type="text" name="mollie_api_key"
                                                                            id="mollie_api_key" class="form-control"
                                                                            value="{{(!isset($payment['mollie_api_key']) || is_null($payment['mollie_api_key'])) ? '' : $payment['mollie_api_key']}}"
                                                                            placeholder="Mollie Api Key">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="mollie_profile_id"
                                                                            class="col-form-label">{{ __('Mollie Profile
                                                                            Id')}}</label>
                                                                        <input type="text" name="mollie_profile_id"
                                                                            id="mollie_profile_id" class="form-control"
                                                                            value="{{(!isset($payment['mollie_profile_id']) || is_null($payment['mollie_profile_id'])) ? '' : $payment['mollie_profile_id']}}"
                                                                            placeholder="Mollie Profile Id">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="mollie_partner_id"
                                                                            class="col-form-label">{{ __('Mollie Partner
                                                                            Id')}}</label>
                                                                        <input type="text" name="mollie_partner_id"
                                                                            id="mollie_partner_id" class="form-control"
                                                                            value="{{(!isset($payment['mollie_partner_id']) || is_null($payment['mollie_partner_id'])) ? '' : $payment['mollie_partner_id']}}"
                                                                            placeholder="Mollie Partner Id">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingnine">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapsenine"
                                                            aria-expanded="false" aria-controls="collapsenine">
                                                            <span class="d-flex align-items-center">
                                                                <i class=""></i> {{
                                                                __('Skrill') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{ __('Enable') }}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_skrill_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        name="is_skrill_enabled" id="is_skrill_enabled"
                                                                        {{ isset($payment['is_skrill_enabled']) &&
                                                                        $payment['is_skrill_enabled']=='on'
                                                                        ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="customswitchv1-2"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapsenine" class="accordion-collapse collapse"
                                                        aria-labelledby="headingnine"
                                                        data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="mollie_api_key"
                                                                            class="col-form-label">{{ __('Skrill
                                                                            Email')}}</label>
                                                                        <input type="email" name="skrill_email"
                                                                            id="skrill_email" class="form-control"
                                                                            value="{{ isset($payment['skrill_email'])?$payment['skrill_email']:''}}"
                                                                            placeholder="{{ __('Mollie Api Key') }}" />
                                                                        @if ($errors->has('skrill_email'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('skrill_email') }}
                                                                        </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="headingten">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapseten"
                                                            aria-expanded="false" aria-controls="collapseten">
                                                            <span class="d-flex align-items-center">
                                                                <i class=""></i> {{
                                                                __('CoinGate') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('Enable')}}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_coingate_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        name="is_coingate_enabled"
                                                                        id="is_coingate_enabled" {{
                                                                        isset($payment['is_coingate_enabled']) &&
                                                                        $payment['is_coingate_enabled']=='on'
                                                                        ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="customswitchv1-2"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapseten" class="accordion-collapse collapse"
                                                        aria-labelledby="headingten" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-12 pb-4">
                                                                    <label class="col-form-label" for="coingate_mode">{{
                                                                        __('CoinGate Mode')}}</label> <br>
                                                                    <div class="d-flex">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio" name="coingate_mode"
                                                                                            value="sandbox"
                                                                                            class="form-check-input" {{
                                                                                            !isset($payment['coingate_mode'])
                                                                                            ||
                                                                                            $payment['coingate_mode']==''
                                                                                            ||
                                                                                            $payment['coingate_mode']=='sandbox'
                                                                                            ? 'checked="checked"' : ''
                                                                                            }}>
                                                                                        {{__('Sandbox')}}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="coingate_mode"
                                                                                            value="live"
                                                                                            class="form-check-input" {{
                                                                                            isset($payment['coingate_mode'])
                                                                                            &&
                                                                                            $payment['coingate_mode']=='live'
                                                                                            ? 'checked="checked"' : ''
                                                                                            }}>
                                                                                        {{__('Live')}}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="coingate_auth_token"
                                                                            class="col-form-label">{{ __('CoinGate Auth
                                                                            Token')}}</label>
                                                                        <input type="text" name="coingate_auth_token"
                                                                            id="coingate_auth_token"
                                                                            class="form-control"
                                                                            value="{{(!isset($payment['coingate_auth_token']) || is_null($payment['coingate_auth_token'])) ? '' : $payment['coingate_auth_token']}}"
                                                                            placeholder="CoinGate Auth Token">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="accordion-item ">
                                                    <h2 class="accordion-header" id="heading11">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapse11"
                                                            aria-expanded="false" aria-controls="collapse11">
                                                            <span class="d-flex align-items-center">
                                                                <i class=""></i> {{
                                                                __('PaymentWall') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{ __('Enable') }}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_paymentwall_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        name="is_paymentwall_enabled"
                                                                        id="is_paymentwall_enabled" {{
                                                                        isset($payment['is_paymentwall_enabled']) &&
                                                                        $payment['is_paymentwall_enabled']=='on'
                                                                        ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="customswitchv1-2"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse11" class="accordion-collapse collapse"
                                                        aria-labelledby="heading11" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paymentwall_public_key"
                                                                            class="col-form-label">{{ __('Public
                                                                            Key')}}</label>
                                                                        <input type="text" name="paymentwall_public_key"
                                                                            id="paymentwall_public_key"
                                                                            class="form-control"
                                                                            value="{{(!isset($payment['paymentwall_public_key']) || is_null($payment['paymentwall_public_key'])) ? '' : $payment['paymentwall_public_key']}}"
                                                                            placeholder="{{ __('Public Key')}}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paymentwall_private_key"
                                                                            class="col-form-label">{{ __('Private Key')
                                                                            }}</label>
                                                                        <input type="text"
                                                                            name="paymentwall_private_key"
                                                                            id="paymentwall_private_key"
                                                                            class="form-control"
                                                                            value="{{(!isset($payment['paymentwall_private_key']) || is_null($payment['paymentwall_private_key'])) ? '' : $payment['paymentwall_private_key']}}"
                                                                            placeholder="{{ __('Private Key') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- Toyyibpay --}}
                                                <div class="accordion-item ">
                                                    <h2 class="accordion-header" id="heading-2-13">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse12"
                                                            aria-expanded="false" aria-controls="collapse12">
                                                            <span class="d-flex align-items-center">

                                                                {{ __('Toyyibpay') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{ __('Enable') }}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_toyyibpay_enabled" value="off">
                                                                    <input type="checkbox" class="form-check-input input-primary" name="is_toyyibpay_enabled"
                                                                        id="is_toyyibpay_enabled" {{ isset($payment['is_toyyibpay_enabled']) &&
                                                                        $payment['is_toyyibpay_enabled']=='on' ? 'checked="checked"' : '' }}>
                                                                    <label for="customswitch1-2" class="form-check-label"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse12" class="accordion-collapse collapse" aria-labelledby="heading-2-13"
                                                        data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="toyyibpay_secret_key" class="form-label">{{ __('Secret Key') }}</label>
                                                                        <input type="text" name="toyyibpay_secret_key" id="toyyibpay_secret_key" class="form-control"
                                                                            value="{{ !isset($payment['toyyibpay_secret_key']) || is_null($payment['toyyibpay_secret_key']) ? '' : $payment['toyyibpay_secret_key'] }}"
                                                                            placeholder="{{ __('Secret Key') }}">

                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="category_code" class="form-label">{{ __('Category Code') }}</label>
                                                                        <input type="text" name="category_code" id="category_code" class="form-control"
                                                                            value="{{ !isset($payment['category_code']) || is_null($payment['category_code']) ? '' : $payment['category_code'] }}"
                                                                            placeholder="{{ __('Category Code') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- PayFast --}}
                                                <div class="accordion-item ">
                                                    <h2 class="accordion-header" id="heading-2-14">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse14"
                                                            aria-expanded="true" aria-controls="collapse14">
                                                            <span class="d-flex align-items-center">

                                                                {{ __('Payfast') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{ __('Enable') }}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_payfast_enabled" value="off">
                                                                <input type="checkbox" class="form-check-input input-primary" name="is_payfast_enabled"
                                                                    id="is_payfast_enabled" {{ isset($payment['is_payfast_enabled']) &&
                                                                    $payment['is_payfast_enabled']=='on' ? 'checked="checked"' : '' }}>
                                                                <label class="form-check-label" for="customswitchv1-2"></label>
                                                            </div>
                                                            </div>
                                                        </button>
                                                    </h2>

                                                    <div id="collapse14" class="accordion-collapse collapse" aria-labelledby="heading-2-14"
                                                        data-bs-parent="#accordionExample">

                                                        <div class="accordion-body">
                                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pb-4">
                                                                <label class="col-form-label" for="payfast_mode">{{
                                                                    __('Payfast Mode')}}</label> <br>
                                                                <div class="d-flex">
                                                                    <div class="mr-2" style="margin-right: 15px;">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio" name="payfast_mode" value="sandbox" class="form-check-input" {{
                                                                                        !isset($payment['payfast_mode']) || $payment['payfast_mode']=='' ||
                                                                                        $payment['payfast_mode']=='sandbox' ? 'checked="checked"' : '' }}>
                                                                                    {{__('Sandbox')}}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mr-2">
                                                                        <div class="border card p-3">
                                                                            <div class="form-check">
                                                                                <label class="form-check-labe text-dark">
                                                                                    <input type="radio" name="payfast_mode" value="live" class="form-check-input" {{
                                                                                        isset($payment['payfast_mode']) && $payment['payfast_mode']=='live' ? 'checked="checked"' : ''
                                                                                        }}>
                                                                                    {{__('Live')}}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="payfast_merchant_id" class="form-label">{{ __('Merchant Id') }}</label>
                                                                        <input type="text" name="payfast_merchant_id" id="payfast_merchant_id" class="form-control"
                                                                            value="{{ !isset($payment['payfast_merchant_id']) || is_null($payment['payfast_merchant_id']) ? '' : $payment['payfast_merchant_id'] }}"
                                                                            placeholder="{{ __('Merchant Id') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="payfast_merchant_key" class="form-label">{{ __('Merchant Key') }}</label>
                                                                        <input type="text" name="payfast_merchant_key" id="payfast_merchant_key" class="form-control"
                                                                            value="{{ !isset($payment['payfast_merchant_key']) || is_null($payment['payfast_merchant_key']) ? '' : $payment['payfast_merchant_key'] }}"
                                                                            placeholder="{{ __('Merchant Key') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="payfast_signature" class="form-label">{{ __('Salt Passphrase') }}</label>
                                                                        <input type="text" name="payfast_signature" id="payfast_signature" class="form-control"
                                                                            value="{{ !isset($payment['payfast_signature']) || is_null($payment['payfast_signature']) ? '' : $payment['payfast_signature'] }}"
                                                                            placeholder="{{ __('Salt Passphrase') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- iyzipay --}}
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="heading-2-15">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#headingiyzi"
                                                            aria-expanded="false" aria-controls="headingiyzi">
                                                            <span class="d-flex align-items-center">
                                                                <i class=""></i> {{ __('Iyzipay') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('Enable')}}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_iyzipay_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        name="is_iyzipay_enabled" id="is_iyzipay_enabled" {{
                                                                        isset($payment['is_iyzipay_enabled']) &&
                                                                        $payment['is_iyzipay_enabled']=='on'
                                                                        ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="customswitchv1-2"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="headingiyzi" class="accordion-collapse collapse"
                                                        aria-labelledby="heading-2-15" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-12 pb-4">
                                                                    <label class="paypal-label col-form-label"
                                                                        for="paypal_mode">{{ __('IyziPay Mode')}}</label> <br>
                                                                    <div class="d-flex">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="iyzipay_mode"
                                                                                            value="local"
                                                                                            class="form-check-input" {{
                                                                                            !isset($payment['iyzipay_mode'])
                                                                                            ||
                                                                                            $payment['iyzipay_mode']==''
                                                                                            ||
                                                                                            $payment['iyzipay_mode']=='local'
                                                                                            ? 'checked="checked"' : ''
                                                                                            }}>
                                                                                        {{__('Local')}}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="iyzipay_mode"
                                                                                            value="production"
                                                                                            class="form-check-input" {{ isset($payment['iyzipay_mode']) && $payment['iyzipay_mode']=='production' ? 'checked="checked"' : '' }}>
                                                                                        {{__('Production')}}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="iyzipay_key"
                                                                            class="col-form-label">{{ __('IyziPay Key')}}</label>
                                                                        <input type="text" name="iyzipay_key"
                                                                            id="iyzipay_key" class="form-control"
                                                                            value="{{isset($payment['iyzipay_key'])? $payment['iyzipay_key']:''}}"
                                                                            placeholder="{{ __('IyziPay Key') }}" />
                                                                        @if ($errors->has('iyzipay_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('iyzipay_key') }}
                                                                        </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="iyzipay_secret"
                                                                            class="col-form-label">{{ __('IyziPay Secret') }}</label>
                                                                        <input type="text" name="iyzipay_secret"
                                                                            id="iyzipay_secret" class="form-control"
                                                                            value="{{ isset($payment['iyzipay_secret']) ? $payment['iyzipay_secret']:''}}"
                                                                            placeholder="{{ __('IyziPay Secret') }}" />
                                                                        @if ($errors->has('iyzipay_secret'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('iyzipay_secret') }}
                                                                        </span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- SSPay --}}
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="heading-2-16">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#headingssp"
                                                            aria-expanded="false" aria-controls="headingssp">
                                                            <span class="d-flex align-items-center">
                                                                <i class=""></i> {{ __('SSPay') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('Enable')}}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_sspay_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        name="is_sspay_enabled" id="is_sspay_enabled" {{
                                                                        isset($payment['is_sspay_enabled']) &&
                                                                        $payment['is_sspay_enabled']=='on'
                                                                        ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="customswitchv1-2"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="headingssp" class="accordion-collapse collapse"
                                                        aria-labelledby="heading-2-16" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="sspay_secret_key"
                                                                            class="col-form-label">{{ __('Secret Key')}}</label>
                                                                        <input type="text" name="sspay_secret_key"
                                                                            id="sspay_secret_key" class="form-control"
                                                                            value="{{isset($payment['sspay_secret_key'])? $payment['sspay_secret_key']:''}}"
                                                                            placeholder="{{ __('Secret Key') }}" />
                                                                        @if ($errors->has('sspay_secret_key'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('sspay_secret_key') }}
                                                                        </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="sspay_category_code"
                                                                            class="col-form-label">{{ __('Category Code') }}</label>
                                                                        <input type="text" name="sspay_category_code"
                                                                            id="sspay_category_code" class="form-control"
                                                                            value="{{ isset($payment['sspay_category_code']) ? $payment['sspay_category_code']:''}}"
                                                                            placeholder="{{ __('Category Code') }}" />
                                                                        @if ($errors->has('sspay_category_code'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('sspay_category_code') }}
                                                                        </span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="accordion-item card shadow-none ">
                                                    <h2 class="accordion-header" id="heading-2-17">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapse17"
                                                            aria-expanded="true" aria-controls="collapse17">

                                                            <span class="d-flex align-items-center">
                                                                <i class=""></i> {{ __('PayTab') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('Enable')}}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_paytab_enabled"
                                                                        value="off">
                                                                    <input type="checkbox" class="form-check-input input-primary"
                                                                        name="is_paytab_enabled" id="is_paytab_enabled"
                                                                        {{ isset($payment['is_paytab_enabled']) && $payment['is_paytab_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label for="customswitch1-2" class="form-check-label"></label>
                                                                </div>
                                                            </div>

                                                        </button>
                                                    </h2>
                                                    <div id="collapse17"
                                                        class="accordion-collapse collapse"aria-labelledby="heading-2-17"data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paytab_profile_id"
                                                                            class="form-label">{{ __('Profile Id') }}</label>
                                                                        <input type="text" name="paytab_profile_id"
                                                                            id="paytab_profile_id" class="form-control"
                                                                            value="{{ !isset($payment['paytab_profile_id']) || is_null($payment['paytab_profile_id']) ? '' : $payment['paytab_profile_id'] }}"
                                                                            placeholder="{{ __('Profile Id') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paytab_server_key"
                                                                            class="form-label">{{ __('Server Key') }}</label>
                                                                        <input type="text" name="paytab_server_key"
                                                                            id="paytab_server_key" class="form-control"
                                                                            value="{{ !isset($payment['paytab_server_key']) || is_null($payment['paytab_server_key']) ? '' : $payment['paytab_server_key'] }}"
                                                                            placeholder="{{ __('Server Key') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paytab_region"
                                                                            class="form-label">{{ __('Paytab Region') }}</label>
                                                                        <input type="text" name="paytab_region"
                                                                            id="paytab_region" class="form-control"
                                                                            value="{{ !isset($payment['paytab_region']) || is_null($payment['paytab_region']) ? '' : $payment['paytab_region'] }}"
                                                                            placeholder="{{ __('Paytab Region') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Benefit  --}}
                                                <div class="accordion-item card shadow-none">
                                                    <h2 class="accordion-header" id="heading-2-18">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapse18"
                                                            aria-expanded="true" aria-controls="collapse18">
                                                            <span class="d-flex align-items-center">
                                                                {{ __('Benefit') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('Enable')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_benefit_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input input-primary"
                                                                    name="is_benefit_enabled" id="is_benefit_enabled"
                                                                    {{ isset($payment['is_benefit_enabled']) && $payment['is_benefit_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label for="customswitch1-2" class="form-check-label"></label>
                                                            </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse18"
                                                        class="accordion-collapse collapse"aria-labelledby="heading-2-18"data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="benefit_api_key"
                                                                            class="form-label">{{ __('Benefit Key') }}</label>
                                                                        <input type="text" name="benefit_api_key"
                                                                            id="benefit_api_key" class="form-control"
                                                                            value="{{ !isset($payment['benefit_api_key']) || is_null($payment['benefit_api_key']) ? '' : $payment['benefit_api_key'] }}"
                                                                            placeholder="{{ __('Enter Benefit Key') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="benefit_secret_key"
                                                                            class="form-label">{{ __('Benefit Secret Key') }}</label>
                                                                        <input type="text" name="benefit_secret_key"
                                                                            id="benefit_secret_key" class="form-control"
                                                                            value="{{ !isset($payment['benefit_secret_key']) || is_null($payment['benefit_secret_key']) ? '' : $payment['benefit_secret_key'] }}"
                                                                            placeholder="{{ __('Enter Benefit Secret key') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Cashfree  --}}
                                                <div class="accordion-item card shadow-none">
                                                    <h2 class="accordion-header" id="heading-2-19">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapse19"
                                                            aria-expanded="true" aria-controls="collapse19">
                                                            <span class="d-flex align-items-center">
                                                                {{ __('Cashfree') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('Enable')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_cashfree_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input input-primary"
                                                                    name="is_cashfree_enabled" id="is_cashfree_enabled"
                                                                    {{ isset($payment['is_cashfree_enabled']) && $payment['is_cashfree_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label for="customswitch1-2" class="form-check-label"></label>
                                                            </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse19"
                                                        class="accordion-collapse collapse"aria-labelledby="heading-2-19"data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="cashfree_api_key"
                                                                            class="form-label">{{ __(' Cashfree Key') }}</label>
                                                                        <input type="text" name="cashfree_api_key"
                                                                            id="cashfree_api_key" class="form-control"
                                                                            value="{{ !isset($payment['cashfree_api_key']) || is_null($payment['cashfree_api_key']) ? '' : $payment['cashfree_api_key'] }}"
                                                                            placeholder="{{ __('Enter Cashfree Key') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="cashfree_secret_key"
                                                                            class="form-label">{{ __('Cashfree Secret Key') }}</label>
                                                                        <input type="text" name="cashfree_secret_key"
                                                                            id="cashfree_secret_key" class="form-control"
                                                                            value="{{ !isset($payment['cashfree_secret_key']) || is_null($payment['cashfree_secret_key']) ? '' : $payment['cashfree_secret_key'] }}"
                                                                            placeholder="{{ __('Enter Cashfree Secret Key') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Aamarpay  --}}
                                                <div class="accordion-item card shadow-none">
                                                    <h2 class="accordion-header" id="heading-2-20">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapse20"
                                                            aria-expanded="true" aria-controls="collapse20">
                                                            <span class="d-flex align-items-center">
                                                                {{ __('Aamarpay') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('Enable')}}</span>
                                                            <div class="form-check form-switch custom-switch-v1">
                                                                <input type="hidden" name="is_aamarpay_enabled"
                                                                    value="off">
                                                                <input type="checkbox" class="form-check-input input-primary"
                                                                    name="is_aamarpay_enabled" id="is_aamarpay_enabled"
                                                                    {{ isset($payment['is_aamarpay_enabled']) && $payment['is_aamarpay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label for="customswitch1-2" class="form-check-label"></label>
                                                            </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse20"
                                                        class="accordion-collapse collapse"aria-labelledby="heading-2-20"data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="aamarpay_store_id"
                                                                            class="form-label">{{ __(' Store Id') }}</label>
                                                                        <input type="text" name="aamarpay_store_id"
                                                                            id="aamarpay_store_id" class="form-control"
                                                                            value="{{ !isset($payment['aamarpay_store_id']) || is_null($payment['aamarpay_store_id']) ? '' : $payment['aamarpay_store_id'] }}"
                                                                            placeholder="{{ __('Enter Store Id') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="aamarpay_signature_key"
                                                                            class="form-label">{{ __('Signature Key') }}</label>
                                                                        <input type="text" name="aamarpay_signature_key"
                                                                            id="aamarpay_signature_key" class="form-control"
                                                                            value="{{ !isset($payment['aamarpay_signature_key']) || is_null($payment['aamarpay_signature_key']) ? '' : $payment['aamarpay_signature_key'] }}"
                                                                            placeholder="{{ __('Enter Signature Key') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="aamarpay_description"
                                                                            class="form-label">{{ __('Description') }}</label>
                                                                        <input type="text" name="aamarpay_description"
                                                                            id="aamarpay_description" class="form-control"
                                                                            value="{{ !isset($payment['aamarpay_description']) || is_null($payment['aamarpay_description']) ? '' : $payment['aamarpay_description'] }}"
                                                                            placeholder="{{ __('Enter Signature Key') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- PayTR --}}
                                                <div class="accordion-item card shadow-none">
                                                    <h2 class="accordion-header" id="heading-2-21">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapse21"
                                                            aria-expanded="true" aria-controls="collapse21">
                                                            <span class="d-flex align-items-center">
                                                                {{ __('Pay TR') }}
                                                            </span>


                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('Enable')}}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_paytr_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        name="is_paytr_enabled" id="is_paytr_enabled"
                                                                        {{ isset($payment['is_paytr_enabled']) && $payment['is_paytr_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="customswitchv1-2"></label>
                                                                </div>
                                                            </div>

                                                        </button>
                                                    </h2>

                                                    <div id="collapse21"
                                                        class="accordion-collapse collapse"aria-labelledby="heading-2-21"data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paytr_merchant_id"
                                                                            class="form-label">{{ __('Merchant Id') }}</label>
                                                                        <input type="text" name="paytr_merchant_id"
                                                                            id="paytr_merchant_id" class="form-control"
                                                                            value="{{ !isset($payment['paytr_merchant_id']) || is_null($payment['paytr_merchant_id']) ? '' : $payment['paytr_merchant_id'] }}"
                                                                            placeholder="{{ __('Merchant Id') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paytr_merchant_key"
                                                                            class="form-label">{{ __('Merchant Key') }}</label>
                                                                        <input type="text" name="paytr_merchant_key"
                                                                            id="paytr_merchant_key" class="form-control"
                                                                            value="{{ !isset($payment['paytr_merchant_key']) || is_null($payment['paytr_merchant_key']) ? '' : $payment['paytr_merchant_key'] }}"
                                                                            placeholder="{{ __('Merchant Key') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="paytr_merchant_salt"
                                                                            class="form-label">{{ __('Salt Passphrase') }}</label>
                                                                        <input type="text" name="paytr_merchant_salt"
                                                                            id="paytr_merchant_salt" class="form-control"
                                                                            value="{{ !isset($payment['paytr_merchant_salt']) || is_null($payment['paytr_merchant_salt']) ? '' : $payment['paytr_merchant_salt'] }}"
                                                                            placeholder="{{ __('Salt Passphrase') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- Yookassa --}}
                                                <div class="accordion-item card shadow-none">
                                                    <h2 class="accordion-header" id="heading-2-22">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapse22"
                                                            aria-expanded="true" aria-controls="collapse22">
                                                            <span class="d-flex align-items-center">
                                                                {{ __('Yookassa') }}
                                                            </span>

                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('Enable')}}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_yookassa_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        name="is_yookassa_enabled" id="is_yookassa_enabled"
                                                                        {{ isset($payment['is_yookassa_enabled']) && $payment['is_yookassa_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="customswitchv1-2"></label>
                                                                </div>
                                                            </div>

                                                        </button>
                                                    </h2>

                                                    <div id="collapse22"
                                                        class="accordion-collapse collapse"aria-labelledby="heading-2-22"data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="yookassa_shop_id"
                                                                            class="form-label">{{ __('Shop ID Key') }}</label>
                                                                        <input type="text" name="yookassa_shop_id"
                                                                            id="yookassa_shop_id" class="form-control"
                                                                            value="{{ !isset($payment['yookassa_shop_id']) || is_null($payment['yookassa_shop_id']) ? '' : $payment['yookassa_shop_id'] }}"
                                                                            placeholder="{{ __('Shop ID Key') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="yookassa_secret"
                                                                            class="form-label">{{ __('Secret Key') }}</label>
                                                                        <input type="text" name="yookassa_secret"
                                                                            id="yookassa_secret" class="form-control"
                                                                            value="{{ !isset($payment['yookassa_secret']) || is_null($payment['yookassa_secret']) ? '' : $payment['yookassa_secret'] }}"
                                                                            placeholder="{{ __('Secret Key') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{--     --}}
                                                <div class="accordion-item card shadow-none">
                                                    <h2 class="accordion-header" id="heading-2-23">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapse23"
                                                            aria-expanded="true" aria-controls="collapse23">
                                                            <span class="d-flex align-items-center">
                                                                {{ __('Midtrans') }}
                                                            </span>

                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('Enable')}}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_midtrans_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        name="is_midtrans_enabled" id="is_midtrans_enabled"
                                                                        {{ isset($payment['is_midtrans_enabled']) && $payment['is_midtrans_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="customswitchv1-2"></label>
                                                                </div>
                                                            </div>

                                                        </button>
                                                    </h2>

                                                    <div id="collapse23" class="accordion-collapse collapse"aria-labelledby="heading-2-23"data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">

                                                                <div class="col-md-12 pb-4">
                                                                    <label class="paypal-label col-form-label"
                                                                        for="paypal_mode">{{ __('Midtrans Mode')}}</label> <br>
                                                                    <div class="d-flex">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="midtrans_mode"
                                                                                            value="local"
                                                                                            class="form-check-input"
                                                                                            {{!isset($payment['midtrans_mode']) || $payment['midtrans_mode']=='' ||
                                                                                                $payment['midtrans_mode']=='local' ? 'checked="checked"' : '' }}>
                                                                                        {{__('Local')}}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="midtrans_mode"
                                                                                            value="production"
                                                                                            class="form-check-input" {{ isset($payment['midtrans_mode']) && $payment['midtrans_mode']=='production' ? 'checked="checked"' : '' }}>
                                                                                        {{__('Production')}}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="midtrans_secret"
                                                                            class="form-label">{{ __('Secret Key') }}</label>
                                                                        <input type="text" name="midtrans_secret"
                                                                            id="midtrans_secret" class="form-control"
                                                                            value="{{ !isset($payment['midtrans_secret']) || is_null($payment['midtrans_secret']) ? '' : $payment['midtrans_secret'] }}"
                                                                            placeholder="{{ __('Secret Key') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Xendit --}}
                                                <div class="accordion-item card shadow-none">
                                                    <h2 class="accordion-header" id="heading-2-24">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#collapse24"
                                                            aria-expanded="true" aria-controls="collapse24">
                                                            <span class="d-flex align-items-center">
                                                                {{ __('Xendit') }}
                                                            </span>

                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('Enable')}}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_xendit_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        name="is_xendit_enabled" id="is_xendit_enabled"
                                                                        {{ isset($payment['is_xendit_enabled']) && $payment['is_xendit_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="customswitchv1-2"></label>
                                                                </div>
                                                            </div>

                                                        </button>
                                                    </h2>

                                                    <div id="collapse24" class="accordion-collapse collapse"aria-labelledby="heading-2-24"data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="xendit_api"
                                                                            class="form-label">{{ __('API Key') }}</label>
                                                                        <input type="text" name="xendit_api"
                                                                            id="xendit_api" class="form-control"
                                                                            value="{{ !isset($payment['xendit_api']) || is_null($payment['xendit_api']) ? '' : $payment['xendit_api'] }}"
                                                                            placeholder="{{ __('API Key') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="xendit_token"
                                                                            class="form-label">{{ __('Token') }}</label>
                                                                        <input type="text" name="xendit_token"
                                                                            id="xendit_token" class="form-control"
                                                                            value="{{ !isset($payment['xendit_token']) || is_null($payment['xendit_token']) ? '' : $payment['xendit_token'] }}"
                                                                            placeholder="{{ __('Token') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- payhere --}}
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="heading-2-15">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse" data-bs-target="#headingPayhere"
                                                            aria-expanded="false" aria-controls="headingPayhere">
                                                            <span class="d-flex align-items-center">
                                                                <i class=""></i> {{ __('PayHere') }}
                                                            </span>
                                                            <div class="d-flex align-items-center">
                                                                <span class="me-2">{{__('Enable')}}</span>
                                                                <div class="form-check form-switch custom-switch-v1">
                                                                    <input type="hidden" name="is_payhere_enabled"
                                                                        value="off">
                                                                    <input type="checkbox"
                                                                        class="form-check-input input-primary"
                                                                        name="is_payhere_enabled" id="is_payhere_enabled" {{
                                                                        isset($payment['is_payhere_enabled']) &&
                                                                        $payment['is_payhere_enabled']=='on'
                                                                        ? 'checked="checked"' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="customswitchv1-2"></label>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </h2>
                                                    <div id="headingPayhere" class="accordion-collapse collapse"
                                                        aria-labelledby="heading-2-15" data-bs-parent="#accordionExample">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                <div class="col-md-12 pb-4">
                                                                    <label class="paypal-label col-form-label"
                                                                        for="paypal_mode">{{ __('PayHere Mode')}}</label> <br>
                                                                    <div class="d-flex">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="payhere_mode"
                                                                                            value="local"
                                                                                            class="form-check-input" {{
                                                                                            !isset($payment['payhere_mode'])
                                                                                            ||
                                                                                            $payment['payhere_mode']==''
                                                                                            ||
                                                                                            $payment['payhere_mode']=='local'
                                                                                            ? 'checked="checked"' : ''
                                                                                            }}>
                                                                                        {{__('Local')}}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="payhere_mode"
                                                                                            value="production"
                                                                                            class="form-check-input" {{ isset($payment['payhere_mode']) && $payment['payhere_mode']=='production' ? 'checked="checked"' : '' }}>
                                                                                        {{__('Production')}}
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="merchant_id"
                                                                            class="col-form-label">{{ __('Merchant ID')}}</label>
                                                                        <input type="text" name="merchant_id"
                                                                            id="merchant_id" class="form-control"
                                                                            value="{{isset($payment['merchant_id'])? $payment['merchant_id']:''}}"
                                                                            placeholder="{{ __('Merchant ID') }}" />
                                                                        @if ($errors->has('merchant_id'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('merchant_id') }}
                                                                        </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="merchant_secret"
                                                                            class="col-form-label">{{ __('Merchant Secret') }}</label>
                                                                        <input type="text" name="merchant_secret"
                                                                            id="merchant_secret" class="form-control"
                                                                            value="{{ isset($payment['merchant_secret']) ? $payment['merchant_secret']:''}}"
                                                                            placeholder="{{ __('Merchant Secret') }}" />
                                                                        @if ($errors->has('merchant_secret'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('merchant_secret') }}
                                                                        </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="payhere_app_id"
                                                                            class="col-form-label">{{ __('App ID') }}</label>
                                                                        <input type="text" name="payhere_app_id"
                                                                            id="payhere_app_id" class="form-control"
                                                                            value="{{ isset($payment['payhere_app_id']) ? $payment['payhere_app_id']:''}}"
                                                                            placeholder="{{ __('App ID') }}" />
                                                                        @if ($errors->has('payhere_app_id'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('payhere_app_id') }}
                                                                        </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="payhere_app_secret"
                                                                            class="col-form-label">{{ __('App Secret') }}</label>
                                                                        <input type="text" name="payhere_app_secret"
                                                                            id="payhere_app_secret" class="form-control"
                                                                            value="{{ isset($payment['payhere_app_secret']) ? $payment['payhere_app_secret']:''}}"
                                                                            placeholder="{{ __('App Secret') }}" />
                                                                        @if ($errors->has('payhere_app_secret'))
                                                                        <span class="invalid-feedback d-block">
                                                                            {{ $errors->first('payhere_app_secret') }}
                                                                        </span>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button class="btn-submit btn btn-primary" type="submit">
                                {{__('Save Changes')}}
                            </button>
                        </div>
                    </form>
                </div>

                {{-- SEO Settings --}}
                <div class="card shadow-none rounded-0 border-bottom" id="useradd-4">
                    {{ Form::open(['url' => route('seo.settings'), 'enctype' => 'multipart/form-data']) }}
                    <div class="card-header">
                        <h5>{{ __('SEO Settings') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    {{ Form::label('meta_keywords', __('Meta Keywords'), ['class' =>
                                    'col-form-label'])}}
                                    {{ Form::text('meta_keywords', !empty($settings['meta_keywords']) ?
                                    $settings['meta_keywords'] : '', ['class' => 'form-control ', 'placeholder' => __('Meta Keywords')]) }}
                                </div>

                                <div class="form-group">
                                    {{ Form::label('meta_description', __('Meta Description'), ['class' =>
                                    'form-label']) }}
                                    {{ Form::textarea('meta_description', !empty($settings['meta_description']) ?
                                    $settings['meta_description'] : '', ['class' => 'form-control ', 'row' => 2,
                                    'placeholder' => __('Enter Meta Description')]) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('Meta Image', __('Meta Image'), ['class' => 'col-form-label ms-4']) }}
                                    <div class="card-body pt-0">
                                        <div class="setting-card">
                                            <div class="logo-content ">
                                                <a href="{{ $meta_image .'/'. (isset($settings['meta_image']) && !empty($settings['meta_image']) ? $settings['meta_image'] : '/meta_image.png') }}"
                                                    target="_blank">
                                                    <img id="meta"
                                                        src="{{ $meta_image .'/'. (isset($settings ['meta_image']) && !empty($settings['meta_image']) ? $settings ['meta_image'] : '/meta_image.png') }}"
                                                        width="250px" class="img_setting seo_image">
                                                </a>
                                            </div>
                                            <div class="choose-files mt-4">
                                                <label for="meta_image">
                                                    <div class=" bg-primary logo"> <i class="ti ti-upload px-1"></i>{{
                                                        __('Choose file here') }}
                                                    </div>
                                                    <input style="margin-top: -40px;" type="file"
                                                        class="form-control file" name="meta_image" id="meta_image"
                                                        data-filename="meta_image"
                                                        onchange="document.getElementById('meta').src = window.URL.createObjectURL(this.files[0])">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <button class="btn-submit btn btn-primary" type="submit">
                            {{ __('Save Changes') }}
                        </button>
                    </div>
                    {{ Form::close() }}
                </div>

                {{-- recaptcha Settings --}}
                <div class="card shadow-none rounded-0 border-bottom" id="useradd-5">
                    <div class="col-md-12">
                        <form method="POST" action="{{ route('recaptcha.settings.store') }}" accept-charset="UTF-8">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-6">
                                        <h5 class="">{{ __('ReCaptcha Settings') }}</h5><small
                                            class="text-secondary font-weight-bold">({{__('How to Get Google reCaptcha Site and Secret key')}})</small>
                                    </div>

                                    <div class="col-lg-4 col-md-4 col-sm-4 text-end col-6">
                                        <div class="col switch-width">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary"
                                                    class="" name="recaptcha_module" id="recaptcha_module"
                                                    {{!empty($settings['recaptcha_module']) && $settings['recaptcha_module']=='on'
                                                    ? 'checked="checked"' : '' }}>
                                                <label class="custom-control-label form-control-label px-2"
                                                    for="recaptcha_module "></label><br>
                                                <a href="https://phppot.com/php/how-to-get-google-recaptcha-site-and-secret-key/"
                                                    target="_blank" class="text-blue">

                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                        <label for="google_recaptcha_key" class="form-label">{{ __('Google Recaptcha
                                            Key') }}</label>
                                        <input class="form-control" placeholder="{{ __('Enter Google Recaptcha Key') }}"
                                            name="google_recaptcha_key" type="text" value="{{$settings['google_recaptcha_key']}}"
                                            id="google_recaptcha_key">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                        <label for="google_recaptcha_secret" class="form-label">{{ __('Google Recaptcha Secret') }}</label>
                                        <input class="form-control "
                                            placeholder="{{ __('Enter Google Recaptcha Secret') }}"
                                            name="google_recaptcha_secret" type="text"
                                            value="{{$settings['google_recaptcha_secret']}}" id="google_recaptcha_secret">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">

                                {{Form::submit(__('Save Changes'),array('class'=>'btn btn-xs btn-primary'))}}

                            </div>
                        </form>
                    </div>
                </div>

                {{-- Cache Settings --}}
                <div class="card shadow-none rounded-0 border-bottom" id="useradd-6">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12">
                                <h5 class="h6 md-0">{{ __('Cache Settings') }}</h5>
                                <small> {{__('This is a page meant for more advanced users, simply ignore it if you don\'t understand what cache is.')}} </small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for=""> {{__('Current cache size')}} </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="input-group search-form">
                                <input type="text" value="{{ Utility::GetCacheSize() }}" class="form-control" readonly>
                                <span class="input-group-text bg-transparent"> {{ __('MB') }} </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ url('clear-cache') }}" class="btn btn-print-invoice btn-primary m-r-10">{{
                            __('Clear Cache') }}</a>
                    </div>
                </div>

                {{-- storage Setting --}}
                <div class="card shadow-none rounded-0 border-bottom" id="useradd-7">
                    {{ Form::open(['route' => 'storage.setting.store', 'enctype' => 'multipart/form-data']) }}
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-10 col-md-10 col-sm-10">
                                <h5 class="">{{ __('Storage Settings') }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="pe-2">
                                <input type="radio" class="btn-check" name="storage_setting" id="local-outlined"
                                    autocomplete="off" {{ $setting['storage_setting']=='local' ? 'checked' : '' }}
                                    value="local" checked>
                                <label class="btn btn-outline-primary" for="local-outlined">{{ __('Local') }}</label>
                            </div>
                            <div class="pe-2">
                                <input type="radio" class="btn-check" name="storage_setting" id="s3-outlined"
                                    autocomplete="off" {{ $setting['storage_setting']=='s3' ? 'checked' : '' }}
                                    value="s3">
                                <label class="btn btn-outline-primary" for="s3-outlined">
                                    {{ __('AWS S3') }}</label>
                            </div>

                            <div class="pe-2">
                                <input type="radio" class="btn-check" name="storage_setting" id="wasabi-outlined"
                                    autocomplete="off" {{ $setting['storage_setting']=='wasabi' ? 'checked' : '' }}
                                    value="wasabi">
                                <label class="btn btn-outline-primary" for="wasabi-outlined">{{ __('Wasabi') }}</label>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div
                                class="local-setting row {{ $setting['storage_setting'] == 'local' ? ' ' : 'd-none' }}">

                                <div class="form-group col-8 switch-width">
                                    {{ Form::label('local_storage_validation', __('Only Upload Files'), ['class' => '
                                    form-label']) }}
                                    <select name="local_storage_validation[]" class="multi-select "
                                        id="choices-multiple" id="local_storage_validation" multiple>
                                        @foreach ($file_type as $f)
                                        <option @if (in_array($f, $local_storage_validations)) selected @endif>
                                            {{ $f }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label" for="local_storage_max_upload_size">{{ __('Max upload size ( In KB)') }}</label>
                                        <input type="number" name="local_storage_max_upload_size" class="form-control"
                                            value="{{ !isset($setting['local_storage_max_upload_size']) || is_null($setting['local_storage_max_upload_size']) ? '' : $setting['local_storage_max_upload_size'] }}"
                                            placeholder="{{ __('Max upload size') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="s3-setting row {{ $setting['storage_setting'] == 's3' ? ' ' : 'd-none' }}">

                                <div class=" row ">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_key">{{ __('S3 Key') }}</label>
                                            <input type="text" name="s3_key" class="form-control"
                                                value="{{ !isset($setting['s3_key']) || is_null($setting['s3_key']) ? '' : $setting['s3_key'] }}"
                                                placeholder="{{ __('S3 Key') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_secret">{{ __('S3 Secret') }}</label>
                                            <input type="text" name="s3_secret" class="form-control"
                                                value="{{ !isset($setting['s3_secret']) || is_null($setting['s3_secret']) ? '' : $setting['s3_secret'] }}"
                                                placeholder="{{ __('S3 Secret') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_region">{{ __('S3 Region') }}</label>
                                            <input type="text" name="s3_region" class="form-control"
                                                value="{{ !isset($setting['s3_region']) || is_null($setting['s3_region']) ? '' : $setting['s3_region'] }}"
                                                placeholder="{{ __('S3 Region') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_bucket">{{ __('S3 Bucket') }}</label>
                                            <input type="text" name="s3_bucket" class="form-control"
                                                value="{{ !isset($setting['s3_bucket']) || is_null($setting['s3_bucket']) ? '' : $setting['s3_bucket'] }}"
                                                placeholder="{{ __('S3 Bucket') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_url">{{ __('S3 URL') }}</label>
                                            <input type="text" name="s3_url" class="form-control"
                                                value="{{ !isset($setting['s3_url']) || is_null($setting['s3_url']) ? '' : $setting['s3_url'] }}"
                                                placeholder="{{ __('S3 URL') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_endpoint">{{ __('S3 Endpoint') }}</label>
                                            <input type="text" name="s3_endpoint" class="form-control"
                                                value="{{ !isset($setting['s3_endpoint']) || is_null($setting['s3_endpoint']) ? '' : $setting['s3_endpoint'] }}"
                                                placeholder="{{ __('S3 Bucket') }}">
                                        </div>
                                    </div>
                                    <div class="form-group col-8 switch-width">
                                        {{ Form::label('s3_storage_validation', __('Only Upload Files'), ['class' => '
                                        form-label']) }}
                                        <select name="s3_storage_validation[]" class=" multi-select"
                                            id="choises-multiple1" id="s3_storage_validation" multiple>
                                            @foreach ($file_type as $f)
                                            <option @if (in_array($f, $s3_storage_validations)) selected @endif>
                                                {{ $f }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_max_upload_size">{{ __('Max upload size (
                                                In KB)')
                                                }}</label>
                                            <input type="number" name="s3_max_upload_size" class="form-control"
                                                value="{{ !isset($setting['s3_max_upload_size']) || is_null($setting['s3_max_upload_size']) ? '' : $setting['s3_max_upload_size'] }}"
                                                placeholder="{{ __('Max upload size') }}">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div
                                class="wasabi-setting row {{ $setting['storage_setting'] == 'wasabi' ? ' ' : 'd-none' }}">
                                <div class=" row ">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_key">{{ __('Wasabi Key') }}</label>
                                            <input type="text" name="wasabi_key" class="form-control"
                                                value="{{ !isset($setting['wasabi_key']) || is_null($setting['wasabi_key']) ? '' : $setting['wasabi_key'] }}"
                                                placeholder="{{ __('Wasabi Key') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_secret">{{ __('Wasabi Secret') }}</label>
                                            <input type="text" name="wasabi_secret" class="form-control"
                                                value="{{ !isset($setting['wasabi_secret']) || is_null($setting['wasabi_secret']) ? '' : $setting['wasabi_secret'] }}"
                                                placeholder="{{ __('Wasabi Secret') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="s3_region">{{ __('Wasabi Region') }}</label>
                                            <input type="text" name="wasabi_region" class="form-control"
                                                value="{{ !isset($setting['wasabi_region']) || is_null($setting['wasabi_region']) ? '' : $setting['wasabi_region'] }}"
                                                placeholder="{{ __('Wasabi Region') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="wasabi_bucket">{{ __('Wasabi Bucket')
                                                }}</label>
                                            <input type="text" name="wasabi_bucket" class="form-control"
                                                value="{{ !isset($setting['wasabi_bucket']) || is_null($setting['wasabi_bucket']) ? '' : $setting['wasabi_bucket'] }}"
                                                placeholder="{{ __('Wasabi Bucket') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="wasabi_url">{{ __('Wasabi URL') }}</label>
                                            <input type="text" name="wasabi_url" class="form-control"
                                                value="{{ !isset($setting['wasabi_url']) || is_null($setting['wasabi_url']) ? '' : $setting['wasabi_url'] }}"
                                                placeholder="{{ __('Wasabi URL') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label" for="wasabi_root">{{ __('Wasabi Root') }}</label>
                                            <input type="text" name="wasabi_root" class="form-control"
                                                value="{{ !isset($setting['wasabi_root']) || is_null($setting['wasabi_root']) ? '' : $setting['wasabi_root'] }}"
                                                placeholder="{{ __('Wasabi Bucket') }}">
                                        </div>
                                    </div>
                                    <div class="form-group col-8 switch-width">
                                        {{ Form::label('wasabi_storage_validation', __('Only Upload Files'), ['class' =>
                                        'form-label'])
                                        }}

                                        <select name="wasabi_storage_validation[]" class=" multi-select"
                                            id="choises-multiple2" id="wasabi_storage_validation" multiple>
                                            @foreach ($file_type as $f)
                                            <option @if (in_array($f, $wasabi_storage_validations)) selected @endif>
                                                {{ $f }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label" for="wasabi_root">{{ __('Max upload size ( In
                                                KB)') }}</label>
                                            <input type="number" name="wasabi_max_upload_size" class="form-control"
                                                value="{{ !isset($setting['wasabi_max_upload_size']) || is_null($setting['wasabi_max_upload_size']) ? '' : $setting['wasabi_max_upload_size'] }}"
                                                placeholder="{{ __('Max upload size') }}">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end pb-0 pe-0">
                            <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit"
                                value="{{ __('Save Changes') }}">
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>

                {{-- Cookie Consent --}}
                <div class="card shadow-none rounded-0 border-bottom" id="useradd-8">
                    {{Form::model($settings,array('route'=>'cookie.setting','method'=>'post'))}}
                    <div
                        class="card-header flex-column flex-lg-row  d-flex align-items-lg-center gap-2 justify-content-between align-items-center flex--column flex-sm-row">

                        <h5>{{ __('Cookie Settings') }}</h5>
                        <div class="d-flex align-items-center">
                            {{ Form::label('enable_cookie', __('Enable cookie'), ['class' => 'col-form-label p-0 fw-bold
                            me-3']) }}
                            <div class="custom-control custom-switch" onclick="enablecookie()">
                                <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary"
                                    name="enable_cookie" class="form-check-input input-primary " id="enable_cookie" {{
                                    $settings['enable_cookie']=='on' ? ' checked ' : '' }}>
                                <label class="custom-control-label mb-1" for="enable_cookie"></label>
                            </div>
                        </div>
                    </div>
                    <div class="card-body cookieDiv {{ $settings['enable_cookie'] == 'off' ? 'disabledCookie ' : '' }}">
                        <div class="row ">
                            <div class="col-md-6">
                                <div class="form-check form-switch custom-switch-v1" id="cookie_log">
                                    <input type="checkbox" name="cookie_logging"
                                        class="form-check-input input-primary cookie_setting" id="cookie_logging"
                                        onclick="enableButton()" {{ $settings['cookie_logging']=='on' ? ' checked ' : ''
                                        }}>
                                    <label class="form-check-label" for="cookie_logging">{{__('Enable logging')}}</label>
                                </div>
                                <div class="form-group">
                                    {{ Form::label('cookie_title', __('Cookie Title'), ['class' => 'col-form-label' ])
                                    }}
                                    {{ Form::text('cookie_title', null, ['class' => 'form-control cookie_setting'] ) }}
                                </div>
                                <div class="form-group ">
                                    {{ Form::label('cookie_description', __('Cookie Description'), ['class' => '
                                    form-label']) }}
                                    {!! Form::textarea('cookie_description', null, ['class' => 'form-control
                                    cookie_setting', 'rows' => '3']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch custom-switch-v1 ">
                                    <input type="checkbox" name="necessary_cookies"
                                        class="form-check-input input-primary" id="necessary_cookies" checked
                                        onclick="return false">
                                    <label class="form-check-label" for="necessary_cookies">{{__('Strictly necessary cookies')}}</label>
                                </div>
                                <div class="form-group ">
                                    {{ Form::label('strictly_cookie_title', __(' Strictly Cookie Title'), ['class' =>
                                    'col-form-label']) }}
                                    {{ Form::text('strictly_cookie_title', null, ['class' => 'form-control
                                    cookie_setting']) }}
                                </div>
                                <div class="form-group ">
                                    {{ Form::label('strictly_cookie_description', __('Strictly Cookie Description'),
                                    ['class' => ' form-label']) }}
                                    {!! Form::textarea('strictly_cookie_description', null, ['class' => 'form-control
                                    cookie_setting ', 'rows' => '3']) !!}
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h5>{{__('More Information')}}</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    {{ Form::label('more_information_description', __('Contact Us Description'),
                                    ['class' => 'col-form-label']) }}
                                    {{ Form::text('more_information_description', null, ['class' => 'form-control
                                    cookie_setting']) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    {{ Form::label('contactus_url', __('Contact Us URL'), ['class' =>
                                    'col-form-label']) }}
                                    {{ Form::text('contactus_url', null, ['class' => 'form-control cookie_setting'])
                                    }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="card-footer d-flex align-items-center gap-2 flex--column flex-sm-row justify-content-between">
                        <div>
                            @if(isset($settings['cookie_logging']) && $settings['cookie_logging'] == 'on')
                            <label for="file" class="form-label">{{__('Download cookie accepted data')}}</label>
                            <a href="{{ asset('storage/uploads/sample') . '/data.csv' }}"
                                class="btn btn-primary mr-2 ">
                                <i class="ti ti-download"></i>
                            </a>
                            @endif
                        </div>
                        <input type="submit" value="{{ __('Save Changes') }}" class="btn btn-primary">
                    </div>
                    {{ Form::close() }}
                </div>

                <!--Pusher Settings-->
                <div class="card shadow-none rounded-0 border-bottom" id="useradd-9">
                    <div class="card-header">
                        <h5>{{ __('Pusher Settings') }}</h5>
                    </div>
                    {{Form::model($settings,array('route'=>'pusher.setting','method'=>'post'))}}
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{Form::label('pusher_app_id',__('Pusher App Id'),array('class'=>'form-label')) }}
                                    {{Form::text('pusher_app_id',$settings['pusher_app_id'],array('class'=>'form-control font-style'))}}
                                    @error('pusher_app_id')
                                    <span class="invalid-pusher_app_id" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{Form::label('pusher_app_key',__('Pusher App Key'),array('class'=>'form-label')) }}
                                    {{Form::text('pusher_app_key',$settings['pusher_app_key'],array('class'=>'form-control font-style'))}}
                                    @error('pusher_app_key')
                                    <span class="invalid-pusher_app_key" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{Form::label('pusher_app_secret',__('Pusher App Secret'),array('class'=>'form-label')) }}
                                    {{Form::text('pusher_app_secret',$settings['pusher_app_secret'],array('class'=>'form-control
                                    font-style'))}}
                                    @error('pusher_app_secret')
                                    <span class="invalid-pusher_app_secret" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{Form::label('pusher_app_cluster',__('Pusher App Cluster'),array('class'=>'form-label')) }}
                                    {{Form::text('pusher_app_cluster',$settings['pusher_app_cluster'],array('class'=>'form-control
                                    font-style'))}}
                                    @error('pusher_app_cluster')
                                    <span class="invalid-pusher_app_cluster" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end pb-0">
                        <div class="form-group">
                            <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{__('Save Changes')}}">
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>

                <div id="location-list" class="card shadow-none rounded-0 border-bottom">
                    <div class="col-md-12 border-bottom">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6">
                                    <h5 class="mb-2">{{ __('Country Settings') }}</h5>
                                </div>

                                <div class="col-6 text-end">


                                    <a href="#location-list" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Add Country') }}"
                                        data-url="{{ route('country.create') }}" data-toggle="tooltip" title="{{ __('Create') }}" data-bs-original-title="{{__('Create New Counrty')}}" data-bs-placement="top" data-bs-toggle="tooltip">
                                        <i class="ti ti-plus"></i>
                                    </a>

                                </div>

                            </div>
                        </div>
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table dataTable-5 data-table">
                                    <thead>
                                    <tr>
                                        <th>{{__('Name')}}</th>


                                        <th class="text-center">{{__('Action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody class="font-style">
                                        @forelse ($countries as $country)

                                            <tr>
                                                <td>{{ucwords($country->country)}}</td>

                                                <td class="Action text-center" >
                                                    <span>
                                                        @if (Auth::user()->type == 'super admin')
                                                            <div class="action-btn bg-light-secondary ms-2">
                                                                <a href="#location-list" class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                                    data-url="{{ route('country.edit', $country->id) }}" data-size="md"
                                                                    data-ajax-popup="true" data-title="{{ __('Edit Country') }}"
                                                                    title="{{ __('Edit Country') }}" data-bs-toggle="tooltip"
                                                                    data-bs-placement="top"><i
                                                                            class="ti ti-edit "></i>
                                                                </a>
                                                            </div>

                                                            <div class="action-btn bg-light-secondary ms-2">
                                                                <a href="#location-list"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                                    data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                                    data-confirm-yes="delete-form-{{ $country->id }}"
                                                                    title="{{ __('Delete') }}" data-bs-toggle="tooltip" data-bs-placement="top">
                                                                    <i class="ti ti-trash"></i>
                                                                </a>
                                                            </div>

                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['country.destroy',
                                                            $country->id], 'id' => 'delete-form-'.$country->id]) !!}
                                                            {!! Form::close() !!}
                                                        @endif

                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="text-center">
                                                <td colspan="4">{{__('No Data Found.!')}}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 border-bottom">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6">
                                    <h5 class="mt-2">{{ __('State Settings') }}</h5>
                                </div>

                                <div class="col-6 text-end row">

                                    <form method="GET" action="{{route('admin.settings')}}" accept-charset="UTF-8" id="customer_submit">
                                        @csrf
                                        <div class=" d-flex align-items-center justify-content-end">

                                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 me-2">
                                                <div class="btn-box">

                                                   {{ Form::label('country', __('Country: '), ['class' => 'col-form-label mr-2']) }}
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                                                <div class="btn-box">
                                                    <select class="form-control" id="country" name="country" >
                                                        <option value="" disabled selected>{{ __('Select Country') }}</option>
                                                    </select>

                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">

                                                <a href="#location-list" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Add State') }}"
                                                data-url="{{ route('state.create') }}" data-toggle="tooltip" title="{{ __('Create') }}" data-bs-original-title="{{__('Create New Counrty')}}" data-bs-placement="top" data-bs-toggle="tooltip">
                                                <i class="ti ti-plus"></i>
                                            </a>
                                            </div>

                                        </div>
                                    </form>


                                </div>

                            </div>
                        </div>
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table dataTable-5 data-table">
                                    <thead>
                                    <tr>
                                        <th>{{__('Name')}}</th>


                                        <th class="text-center">{{__('Action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody class="font-style">
                                        @forelse (array_chunk($states, 50) as $state)
                                            @foreach ($state as $stat)
                                                <tr>
                                                    <td>{{ucwords($stat['region'])}}</td>

                                                    <td class="Action text-center" >
                                                        <span>
                                                            @if (Auth::user()->type == 'super admin')
                                                                <div class="action-btn bg-light-secondary ms-2">
                                                                    <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                                        data-url="{{ route('state.edit', $stat['id']) }}" data-size="md"
                                                                        data-ajax-popup="true" data-title="{{ __('Edit State') }}"
                                                                        title="{{ __('Edit State') }}" data-bs-toggle="tooltip"
                                                                        data-bs-placement="top"><i
                                                                                class="ti ti-edit "></i>
                                                                    </a>
                                                                </div>

                                                                <div class="action-btn bg-light-secondary ms-2">
                                                                    <a href="#"
                                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                                        data-confirm-yes="delete-form-{{ $stat['id'] }}"
                                                                        title="{{ __('Delete') }}" data-bs-toggle="tooltip" data-bs-placement="top">
                                                                        <i class="ti ti-trash"></i>
                                                                    </a>
                                                                </div>

                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['state.destroy',
                                                                $stat['id']], 'id' => 'delete-form-'.$stat['id']]) !!}
                                                                {!! Form::close() !!}
                                                            @endif

                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @empty
                                            <tr class="text-center">
                                                <td colspan="4">{{__('No Data Found.!')}}</td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 border-bottom">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6">
                                    <h5 class="mt-2">{{ __('City Settings') }}</h5>
                                </div>

                                <div class="col-6 text-end row">

                                    <form method="GET" action="{{route('admin.settings')}}" accept-charset="UTF-8"
                                        id="state_filter_submit"> @csrf
                                        <div class=" d-flex align-items-center justify-content-end">

                                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 me-2">
                                                <div class="btn-box">

                                                   {{ Form::label('city', __('State: '), ['class' => 'col-form-label mr-2']) }}
                                                </div>
                                            </div>

                                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                                                <div class="btn-box">
                                                    <select class="form-control" id="state_filter" name="state_id" >
                                                        <option value="" disabled selected>{{ __('Select State') }}</option>
                                                    </select>

                                                </div>
                                            </div>

                                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">

                                                <a href="#location-list" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Add City') }}"
                                                data-url="{{ route('city.create') }}" data-toggle="tooltip" title="{{ __('Create') }}" data-bs-original-title="{{__('Create New City')}}" data-bs-placement="top" data-bs-toggle="tooltip">
                                                    <i class="ti ti-plus"></i>
                                                </a>
                                            </div>

                                        </div>
                                    </form>


                                </div>

                            </div>
                        </div>
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table dataTable-5 data-table">
                                    <thead>
                                    <tr>
                                        <th>{{__('Name')}}</th>


                                        <th class="text-center">{{__('Action')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody class="font-style">
                                        @forelse (array_chunk($cities, 50) as $city)
                                            @foreach ($city as $cit)
                                                <tr>
                                                    <td>{{ucwords($cit['city'])}}</td>

                                                    <td class="Action text-center" >
                                                        <span>
                                                            @if (Auth::user()->type == 'super admin')
                                                                <div class="action-btn bg-light-secondary ms-2">
                                                                    <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                                        data-url="{{ route('city.edit', $cit['id']) }}" data-size="md"
                                                                        data-ajax-popup="true" data-title="{{ __('Edit City') }}"
                                                                        title="{{ __('Edit City') }}" data-bs-toggle="tooltip"
                                                                        data-bs-placement="top"><i class="ti ti-edit "></i>
                                                                    </a>
                                                                </div>

                                                                <div class="action-btn bg-light-secondary ms-2">
                                                                    <a href="#"
                                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                                        data-confirm-yes="delete-form-{{ $cit['id'] }}"
                                                                        title="{{ __('Delete') }}" data-bs-toggle="tooltip" data-bs-placement="top">
                                                                        <i class="ti ti-trash"></i>
                                                                    </a>
                                                                </div>

                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['city.destroy',
                                                                $cit['id']], 'id' => 'delete-form-'.$cit['id']]) !!}
                                                                {!! Form::close() !!}
                                                            @endif

                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @empty
                                            <tr class="text-center">
                                                <td colspan="4">{{__('No Data Found.!')}}</td>
                                            </tr>
                                        @endforelse

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- [ Main Content ] end -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('custom-script')
<script>

$(document).ready(function(){

    $.ajax({
        url: "{{ route('get.country') }}",
        type: "GET",
        success: function(result) {

            $.each(result.data, function(key, value) {

                setTimeout(function() {
                    if (value.id == '{{ $country_id }}') {
                        $("#country").append('<option value="'+value.id+'" selected class="counties_list">' + value.country + '</option>');
                    }else{
                        $("#country").append('<option value="'+value.id+'" class="counties_list">' + value.country + '</option>');
                    }
                }, 1000);

            });

        },
    });

    $.ajax({
        url: "{{ route('get.all.state') }}",
        type: "GET",
        success: function(result) {
            setTimeout(function() {
                $.each(result, function(key, value) {

                    if (value.id == '{{ $country_id }}') {

                        $("#state_filter").append('<option value="' + value.id + '" selected>' + value.region + "</option>");
                    }else{
                        $("#state_filter").append('<option value="' + value.id + '">' + value.region + "</option>");
                    }
                });
            }, 1000);

        },
    });

})

$(document).on("click",'a[data-ajax-popup="true"], button[data-ajax-popup="true"], div[data-ajax-popup="true"]',function () {

    $.ajax({
        url: "{{ route('get.country') }}",
        type: "GET",
        success: function(result) {

            $.each(result.data, function(key, value) {
                setTimeout(function() {
                    $("#state_country").append('<option value="' + value.id + '" >' + value.country + '</option>');
                }, 1000);

            });


        },
    });



});
$(document).on("change",'#city_country',function () {

    var country_id = this.value;

    $("#city_state").html("");
    $.ajax({
        url: "{{ route('get.state') }}",
        type: "POST",
        data: {
            country_id: country_id,
            _token: "{{ csrf_token() }}",
        },
        dataType: "json",
        success: function(result) {
            setTimeout(function() {
                console.log(result);
                $.each(result.data, function(key, value) {
                    $("#city_state").append('<option value="' + value.id + '">' +
                        value.region + "</option>");
                });
                $("#city").html('<option value="">Select State First</option>');
            }, 1000);
        },
    });
});
$('#country').on('change',function(){
    $('#customer_submit').trigger('submit');
    return false;
})
$('#state_filter').on('change',function(){
    $('#state_filter_submit').trigger('submit');
    return false;
})

@if($filter_data == 'filtered')
    $([document.documentElement, document.body]).animate({
        scrollTop: $("#location-list").offset().top
    }, 2000);
@endif

</script>
@endpush
