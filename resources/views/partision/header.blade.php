

@php
    $users = \Auth::user();
    $logo = App\Models\Utility::get_file('uploads/profile/');

    $currantLang = $users->currentLanguage();

    $settings = \App\Models\Utility::settings();

    $languages = App\Models\Utility::languages();
    $LangName = \App\Models\Languages::where('code',$currantLang)->first();
    if (empty($LangName)) {
        $LangName  = new App\Models\Utility();
        $LangName->fullName = 'English';
    }
    $notifications = App\Models\Utility::notification();

@endphp

    <header class="dash-header {{(isset($settings['cust_theme_bg']) && $settings['cust_theme_bg'] == 'on')?'transprent-bg':''}}">
    <div class="header-wrapper">
        <div class="me-auto dash-mob-drp">
            <ul class="list-unstyled">

                <li class="dash-h-item mob-hamburger">
                    <a href="#!" class="dash-head-link" id="mobile-collapse">
                        <div class="hamburger hamburger--arrowturn">
                            <div class="hamburger-box">
                                <div class="hamburger-inner"></div>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="dropdown dash-h-item drp-company">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0 " data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="theme-avtar">
                            <img alt="#" style="width:30px;"
                                src="{{ !empty(\Auth::user()->avatar) ? $logo.  \Auth::user()->avatar : $logo . '/avatar.png' }}"
                                class="header-avtar">
                        </span>
                        <span class="hide-mob ms-2">
                            @if (!Auth::guest())
                                {{ __('Hi, ') }}{{ Auth::user()->name }}!
                            @else
                                {{ __('Guest') }}
                            @endif
                        </span>
                        <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                    </a>

                    <div class="dropdown-menu dash-h-dropdown">
                        <a href="{{route('users.edit', Auth::user()->id)}}" class="dropdown-item">
                            <i class="ti ti-user"></i>
                            <span>{{ __('Profile') }}</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" id="form_logout">
                            @csrf
                            <a href="#"  class="dropdown-item" id="logout-form">
                                <i class="ti ti-power"></i>
                                {{ __('Log Out') }}
                            </a>
                        </form>
                    </div>
                </li>



                <li class="dropdown dash-h-item drp-company">
                    <a class="dash-head-link  me-0 " href="{{ route('user.ticket.create') }}"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="theme-avtar">
                            <i class="ti ti-help"></i>
                        </span>
                        <span class="hide-mob ms-2">
                            {{ __('Contact Us') }}
                        </span>
                    </a>

                </li>

                @impersonating($guard = null)

                    <li class="dropdown dash-h-item drp-company">
                        <a class="dash-head-link  me-0 bg-danger text-white"  href="{{ route('exit.admin') }}"
                            role="button" aria-haspopup="false" aria-expanded="false">
                            <span class="theme-avtar">
                                <i class="ti ti-ban"></i>
                            </span>
                            <span class="hide-mob ms-2">
                                {{ __('Exit Company Login') }}
                            </span>
                        </a>

                    </li>
                @endImpersonating

            </ul>
        </div>


        <div class="ms-auto">
            <ul class="list-unstyled">
                <li class="dropdown dash-h-item drp-notification">
                    <a class="dash-head-link dropdown-toggle arrow-none show" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="true">
                        <i class="ti ti-bell"></i>
                        @if (count($notifications) > 0)
                            <span class="bg-danger dash-h-badge dots"><span class="sr-only"></span></span>
                        @endif
                    </a>
                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end " data-popper-placement="bottom-end"
                        style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(-8px, 58px);">
                        <div class="noti-header">
                            <h5 class="m-0">{{ __('Notification') }}</h5>
                        </div>
                        <div class="noti-body" data-simplebar="init">
                            <div class="simplebar-wrapper" style="margin: -10px -20px;">
                                <div class="simplebar-height-auto-observer-wrapper">
                                    <div class="simplebar-height-auto-observer"></div>
                                </div>
                                <div class="simplebar-mask">
                                    <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                        <div class="simplebar-content-wrapper" tabindex="0" role="region"
                                            aria-label="scrollable content"
                                            style="height: auto; overflow: hidden scroll;">
                                            <div class="simplebar-content" style="padding: 10px 20px;">
                                                <hr class="dropdown-divider">
                                                @if (count($notifications) > 0)
                                                    @foreach ($notifications as $notification)
                                                        @php $bill=App\Models\Notification::getBillDetail($notification->bill_id);@endphp
                                                        <div class="d-flex align-items-start my-4">
                                                            <div class="theme-avtar bg-primary">
                                                                <i class="ti ti-file-analytics"></i>
                                                            </div>
                                                            <div class="ms-3 flex-grow-1">
                                                                <div
                                                                    class="d-flex align-items-start justify-content-between">
                                                                    <a href="{{ route('bills.show', $bill->id) }}">
                                                                        @if ($users->type == 'client')
                                                                            <h6>{{ $bill->bill_number }}
                                                                                {{ 'Please pay bill before due date' }}
                                                                            </h6>
                                                                        @else
                                                                            <h6>{{ $bill->bill_number }}
                                                                                {{ 'This bill payment is not receive yet' }}
                                                                            </h6>
                                                                        @endif
                                                                    </a>
                                                                </div>
                                                                <div
                                                                    class="d-flex align-items-end justify-content-between">
                                                                    @if ($users->type == 'client')
                                                                        {{ __('Complete the payment by the due date to avoid any inconvenience.') }}
                                                                    @else
                                                                        <p class="mb-0 text-muted">
                                                                            {{ __('you not received your payment yet please kindly remind to your client before the due date to avoid any inconvenience') }}
                                                                    @endif
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="d-grid">
                                                        <a
                                                            class="btn dash-head-link justify-content-center bg-light-primary text-primary mx-0">{{ 'No Notification.' }}
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="simplebar-placeholder" style="width: auto; height: 753px;"></div>
                            </div>
                            <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                            </div>
                            <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                                <div class="simplebar-scrollbar"
                                    style="height: 292px; display: block; transform: translate3d(0px, 0px, 0px);">
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="dropdown dash-h-item drp-language">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                        href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="ti ti-world nocolor"></i>
                        <span class="drp-text hide-mob">{{ Str::upper($LangName->fullName) }}</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end " aria-labelledby="dropdownLanguage">
                        @foreach ($languages as $code => $lang)
                            <a href="{{ route('change.language', $code) }}" class="dropdown-item {{ $currantLang == $code ? 'text-danger' : '' }}">
                                {{ Str::upper($lang) }}
                            </a>
                        @endforeach
                        @can('create language')
                            <div class="dropdown-divider m-0"></div>

                            <a href="#" data-url="{{ route('create.language') }}" data-size="md" data-ajax-popup="true" data-title="{{__('Create New Language')}}"
                            class="dropdown-item  text-primary text-primary" >{{ __('Create Language') }}</a>
                            <div class="dropdown-divider m-0"></div>
                            <a href="{{ route('manage.language', $currantLang) }}"
                                class="dropdown-item text-primary">{{ __('Manage Language') }}</a>
                        @endcan
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>

@push('custom-script')
    <script>
        $('#logout-form').on('click',function(){
            event.preventDefault();
            $('#form_logout').trigger('submit');
        });
    </script>
@endpush
