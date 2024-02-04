@php
    use App\Models\Utility;
    $logo = asset('storage/uploads/logo');

    $setting = Utility::colorset();


    $mode_setting = Utility::mode_layout();


    $company_logo = Utility::get_company_logo();
    $company_logos = Utility::getValByName('company_logo_light');
    $settings = Utility::settings();
@endphp
@extends('layouts.custom_guest')
@section('title-content')
    <h2 class="text-center p-0 m-5 " style="color: #fff">{{ __('Create Ticket') }}</h2>
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
                        <a class="nav-link" href="#">{{ __('Create Ticket') }}</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="{{ route('user.ticket.search') }}">{{ __('Search Ticket') }}</a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('user.faq') }}" class="nav-link">{{ __('FAQ') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.knowledge') }}">{{ __('Knowledge') }}</a>
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

                <form method="post" action="{{ route('home.store') }}" class="create-form" enctype="multipart/form-data">
                    @csrf

                    <div class="text-start row">
                        @if (!$customFields->isEmpty())
                            @include('customFields.formBuilder')
                        @endif

                        @if ($settings['recaptcha_module'] == 'on')
                            <div class="form-group mb-3">
                                {!! NoCaptcha::display() !!}
                                @error('g-recaptcha-response')
                                    <span class="small text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        @endif
                        <div class="text-center">
                            <div class="d-block ">
                                <input type="hidden" name="status" value="New Ticket" />
                                <button class="btn btn-primary btn-block mt-2">
                                    {{ __('Create Ticket') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
