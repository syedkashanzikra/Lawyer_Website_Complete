@extends('layouts.guest')
@section('page-title')
    {{ __('Verify Email') }}
@endsection

@section('content')
        <div class="card-body">
            <div class="col-xl-12">
                <div class="">
                    @if (session('status') == 'verification-link-sent')
                        <div class="mb-4 font-medium text-sm text-green-600 text-primary">
                            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                        </div>
                    @endif
                    <div class="mb-4 text-sm text-gray-600">
                        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the
                                    link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <div class="row">
                            <div class="col-auto">
                                <form method="POST" action="{{ route('verification.send') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        {{ __('Resend Verification Email') }}
                                    </button>
                                </form>
                            </div>
                            <div class="col-auto">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">{{ __('Logout') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection()
