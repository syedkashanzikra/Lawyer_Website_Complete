@extends('layouts.guest')

@section('page-title')
    {{ __('Forget password') }}
@endsection
@php
    use App\Models\Utility;
    $languages = Utility::languages();
    $logo = Utility::get_file('uploads/logo');

@endphp
@section('language-bar')

    <div class="lang-dropdown-only-desk">
        <li class="dropdown dash-h-item drp-language">
            <a class="dash-head-link dropdown-toggle btn" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="drp-text"> {{ ucFirst($languages[$lang]) }}
                </span>
            </a>
            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                @foreach($languages as $code => $language)
                    <a href="{{ route('password.request',$code) }}" tabindex="0" class="dropdown-item {{ $code == $lang ? 'active':'' }}">
                        <span>{{ ucFirst($language)}}</span>
                    </a>
                @endforeach
            </div>
        </li>
    </div>
@endsection
@section('content')
    <div class="card-body">

        <h2 class="mb-3 f-w-600">{{ __('Forgot Password') }}</h2>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <span class="text-danger">{{ $error }}</span>
            @endforeach
        @endif
        <div class="custom-login-form">

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <h5 for="" class="form-label">{{ __('Email') }}</h5>
                    <x-input id="email" class="form-control" type="email" name="email" :value="old('email')" required
                        autofocus placeholder="{{ __('Enter email Address') }}" />
                </div>
                {!! Form::hidden('type', 'admin') !!}
                <button class="btn btn-primary btn-block mt-3 w-100" type="submit">
                    {{ __('Send Password Reset Link') }}
                </button>
                <div class="mt-3 text-center">
                    <a class="underline text-gray-600 hover:text-gray-900"  href="{{ route('login', !empty($lang) ? $lang : 'en') }}">
                        {{ __('Back to login') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
