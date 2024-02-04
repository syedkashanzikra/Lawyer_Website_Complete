@extends('layouts.guest')

@section('content')
        <div class="card-body">
            <div class="">
                <h2 class="mb-3 f-w-600"> {{ __('Reset Password') }} </h2>
            </div>
            <div class="">
                <!-- Validation Errors -->
                <x-auth-validation-errors class="mb-4" :errors="$errors" />
        <div class="custom-login-form">

                <form method="POST" action="{{ route('password.update') }}">
                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                    @csrf

                    <div class="form-group mb-3">
                        <label class="form-label" for="email">{{ __('Email') }}</label>
                        <x-input id="email" class="form-control" type="email" name="email" :value="old('email', $request->email)"
                            required autofocus />
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label" for="password">{{ __('Password') }}</label>
                        <x-input id="password" class="form-control" type="password" name="password" required />
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label" for="password_confirmation">{{ __('Confirm Password') }}</label>
                        <x-input id="password_confirmation" class="form-control" type="password"
                            name="password_confirmation" required />
                    </div>

                    <div class="form-group mb-4">
                        {!! Form::hidden('type', 'admin') !!}
                        <button class="btn btn-primary btn-block mt-2" type="submit">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </form>
            </div>
            </div>
        </div>
@endsection
