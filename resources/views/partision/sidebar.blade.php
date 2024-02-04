@php
    use App\Models\Utility;
    $settings = Utility::settings();

    $company_logo = $settings['company_logo'] ?? '';
    $company_small_logo = $settings['company_small_logo'] ?? '';
    $mode_setting = \App\Models\Utility::mode_layout();
    $logo = asset('storage/uploads/logo/');
    $company_logo = Utility::get_company_logo();
    $SITE_RTL = !empty($settings['SITE_RTL']) ? $settings['SITE_RTL'] : 'off';

    $premission = [];
    $premission_arr = [];
    if (\Auth::user()->super_admin_employee == 1) {
        $premission = json_decode(\Auth::user()->permission_json);
        $premission_arr = get_object_vars($premission);
    }

@endphp

<!-- [ Pre-loader ] start -->
<div class="loader-bg">
    <div class="loader-track">
        <div class="loader-fill"></div>
    </div>
</div>
<!-- [ Pre-loader ] End -->

<!-- [ navigation menu ] start -->
<nav class="dash-sidebar light-sidebar {{ isset($mode_setting['cust_theme_bg']) && $mode_setting['cust_theme_bg'] == 'on' ? 'transprent-bg' : '' }}">

    <div class="navbar-wrapper">
        <div class="m-header main-logo">
            <a href="{{ route('dashboard') }}" class="b-brand">
                <!-- ========   change your logo hear   ============ -->
                <img src="{{ $logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') . '?' . time() }}"
                    alt="" class="logo logo-lg" />
            </a>
        </div>
        <div class="navbar-content">
            <ul class="dash-navbar">


                <li class="dash-item dash-hasmenu {{ \Request::route()->getName() == 'dashboard' ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="dash-link ">
                        <span class="dash-micon"><i class="ti ti-home"></i>
                        </span><span class="dash-mtext">{{ __('Dashboard') }}</span>
                        <span class="dash-arrow"></span>
                    </a>
                </li>

                @if (Auth::user()->type == 'super admin')
                    @can('manage user')
                        <li class="dash-item dash-hasmenu {{ request()->is('users*') ? 'active' : '' }}">
                            <a href="{{ route('users.list') }}" class="dash-link"><span class="dash-micon"><i
                                        class="ti ti-users"></i></span><span class="dash-mtext">{{ __('Companies') }}</span>
                            </a>
                        </li>
                        <li class="dash-item dash-hasmenu {{ request()->is('employee*') ? 'active' : '' }}">
                            <a href="{{ route('employee.index') }}" class="dash-link"><span class="dash-micon">
                                    <i class="ti ti-user-check"></i></span><span
                                    class="dash-mtext">{{ __('User') }}</span>
                            </a>
                        </li>
                    @endcan
                @else
                    @canany(['manage member', 'manage group', 'manage role'])
                        <li
                            class="dash-item dash-hasmenu {{ Request::route()->getName() == 'users.edit' || Request::route()->getName() == 'users.list' || Request::route()->getName() == 'userlog.index' ? 'active dash-trigger' : '' }}">
                            <a href="#!" class="dash-link ">
                                <span class="dash-micon"><i class="ti ti-users"></i>
                                </span><span class="dash-mtext">{{ __('Staff') }}</span>
                                <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                            </a>
                            <ul
                                class="dash-submenu {{ Request::segment(1) == 'roles' || Request::segment(1) == 'users' || Request::route()->getName() == 'users.list' || Request::segment(1) == 'groups' ? 'show' : '' }}">

                                @can('manage role')
                                    <li class="dash-item {{ in_array(Request::segment(1), ['roles', '']) ? ' active' : '' }}">
                                        <a class="dash-link" href="{{ route('roles.index') }}">{{ __('Role') }}</a>
                                    </li>
                                @endcan

                                @can('manage member')
                                    <li
                                        class="dash-item {{ Request::route()->getName() == 'users.edit' || Request::route()->getName() == 'users.list' || Request::route()->getName() == 'userlog.index' ? 'active' : '' }}">
                                        <a class="dash-link" href="{{ route('users.index') }}">{{ __('Employees') }}</a>
                                    </li>
                                @endcan

                                @can('manage group')
                                    <li class="dash-item {{ in_array(Request::segment(1), ['groups', '']) ? ' active' : '' }}">
                                        <a class="dash-link" href="{{ route('groups.index') }}">{{ __('Group') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                @endif

                @can('manage client')
                    <li
                        class="dash-item dash-hasmenu {{ in_array(Request::segment(1), ['client', 'client-list']) ? ' active' : '' }}">
                        <a href="{{ route('client.index') }}" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-user-check"></i></span>
                            <span class="dash-mtext">{{ __('Client') }}</span>
                        </a>
                    </li>
                @endcan

                @can('manage advocate')
                    <li class="dash-item dash-hasmenu {{ in_array(Request::segment(1), ['advocate']) ? ' active' : '' }}">
                        <a href="{{ route('advocate.index') }}" class="dash-link">
                            <span class="dash-micon"><i class="fa fa-tasks"></i></span>
                            <span class="dash-mtext">{{ __('Advocate') }}</span>
                        </a>
                    </li>
                @endcan

                @can('manage case')
                    <li class="dash-item dash-hasmenu {{ in_array(Request::segment(1), ['cases']) ? ' active' : '' }}">
                        <a href="{{ route('cases.index') }}" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-file-text"></i></span>
                            <span class="dash-mtext">{{ __('Cases') }}</span>
                        </a>
                    </li>
                @endcan

                @can('manage todo')
                    <li class="dash-item dash-hasmenu {{ in_array(Request::segment(1), ['todo']) ? ' active' : '' }}">
                        <a href="{{ route('to-do.index') }}" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-file-plus"></i></span>
                            <span class="dash-mtext">{{ __('To-Do') }}</span>
                        </a>
                    </li>
                @endcan

                {{-- @can('manage appointment')
                    <li class="dash-item dash-hasmenu {{ in_array(Request::segment(1), ['appointments']) ? ' active' : '' }}">
                        <a href="{{ route('appointments.index') }}" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-bookmarks" aria-hidden="true"></i>

                            </span>
                            <span class="dash-mtext">{{ __('Appointment') }}</span>
                        </a>
                    </li>
                @endcan --}}



                @can('manage diary')
                    <li
                        class="dash-item dash-hasmenu {{ in_array(Request::segment(1), ['casediary']) || in_array(Request::segment(1), ['calendar']) ? ' active' : '' }}">
                        <a href="{{ route('casediary.index') }}" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-license"></i></span>
                            <span class="dash-mtext">{{ __('Case Diary/Calendar') }}</span>
                        </a>
                    </li>
                @endcan


                @can('manage document')
                    <li class="dash-item dash-hasmenu {{ in_array(Request::segment(1), ['documents']) ? ' active' : '' }}">
                        <a href="{{ route('documents.index') }}" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-files"></i></span>
                            <span class="dash-mtext">{{ __('Documents') }}</span>
                        </a>
                    </li>
                @endcan

                @can('manage bill')
                    <li class="dash-item dash-hasmenu {{ in_array(Request::segment(1), ['bills']) ? ' active' : '' }}">
                        <a href="{{ route('bills.index') }}" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-file-analytics"></i></span>
                            <span class="dash-mtext">{{ __('Bills / Invoices') }}</span>
                        </a>
                    </li>
                @endcan
                @can('manage cause')
                    <li class="dash-item dash-hasmenu {{ in_array(Request::segment(1), ['cause']) ? ' active' : '' }}">
                        <a href="{{ route('cause.index') }}" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-clipboard-list"></i></span>
                            <span class="dash-mtext">{{ __('Cause List') }}</span>
                        </a>
                    </li>
                @endcan



                @can('manage timesheet')
                    <li class="dash-item dash-hasmenu {{ in_array(Request::segment(1), ['timesheet']) ? ' active' : '' }}">
                        <a href="{{ route('timesheet.index') }}" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-list-check"></i></span>
                            <span class="dash-mtext">{{ __('Timesheet') }}</span>
                        </a>
                    </li>
                @endcan

                @can('manage expense')
                    <li class="dash-item dash-hasmenu {{ in_array(Request::segment(1), ['expenses']) ? ' active' : '' }}">
                        <a href="{{ route('expenses.index') }}" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-report"></i></span>
                            <span class="dash-mtext">{{ __('Expense') }}</span>
                        </a>
                    </li>
                @endcan

                @can('manage feereceived')
                    <li
                        class="dash-item dash-hasmenu {{ in_array(Request::segment(1), ['fee-receive']) ? ' active' : '' }}">
                        <a href="{{ route('fee-receive.index') }}" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-receipt-2"></i></span>
                            <span class="dash-mtext">{{ __('Fee Received') }}</span>
                        </a>
                    </li>
                @endcan


                @if (\Auth::user()->type != 'super admin')
                    <li class="dash-item {{ \Request::route()->getName() == 'chats' ? ' active' : '' }}">
                        <a href="{{ url('chats') }}"
                            class="dash-link {{ Request::segment(1) == 'chats' ? 'active' : '' }}">
                            <span class="dash-micon"><i class="ti ti-brand-messenger"></i></span><span
                                class="dash-mtext">{{ __('Messenger') }}</span>
                        </a>
                    </li>
                @endif

                @if (\Auth::user()->type == 'company' || \Auth::user()->type == 'super admin')
                    <li
                        class="dash-item {{ Request::segment(1) == 'plans' || Request::route()->getName() == 'payment' ? 'active' : '' }}">
                        <a class="dash-link" href="{{ route('plans.index') }}">
                            <span class="dash-micon"><i class="ti ti-trophy"></i></span><span
                                class="dash-mtext">{{ __('Plan') }}</span>
                        </a>
                    </li>
                @endif

                @if (
                    (Auth::user()->super_admin_employee == 1 && array_search('manage crm', $premission_arr)) ||
                        (Auth::user()->type == 'advocate') || (Auth::user()->type == 'company'))
                    <li class="dash-item dash-hasmenu">
                        <a href="#!" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-affiliate"></i>
                            </span><span class="dash-mtext">{{ __('CRM') }}</span>
                            <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="dash-submenu">
                            @if (
                                (Auth::user()->super_admin_employee == 1 && array_search('manage crm', $premission_arr)) || (Auth::user()->type == 'company') )
                                <li
                                    class="dash-item dash-hasmenu {{ Request::segment(1) == 'lead' ? 'active' : '' }}">
                                    <a class="dash-link"
                                        href="{{  route('lead.index') }}">{{ __('Lead') }}</a>
                                </li>
                            @endif
                            @if (
                                (Auth::user()->super_admin_employee == 1 && array_search('manage crm', $premission_arr)) ||
                                    Auth::user()->type == 'advocate' || (Auth::user()->type == 'company'))
                                <li
                                    class="dash-item dash-hasmenu {{ Request::segment(1) == 'deal' ? 'active' : '' }}">
                                    <a class="dash-link"
                                        href="{{  route('deal.index') }}">{{ __('Deal') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (Auth::user()->super_admin_employee == 1 && array_search('manage support ticket', $premission_arr) || (Auth::user()->type == 'company'))
                    <li class="dash-item dash-hasmenu">
                        <a href="#!" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-ticket"></i>
                            </span><span class="dash-mtext">{{ __('Support Ticket') }}</span>
                            <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="dash-submenu">
                            <li class="dash-item dash-hasmenu {{ request()->is('*ticket*') ? ' active' : '' }}">
                                <a class="dash-link" href="{{ route('tickets.index') }}">{{ __('Tickets') }}</a>
                            </li>
                            <li class="dash-item dash-hasmenu {{ request()->is('*faq*') ? ' active' : '' }}">
                                <a class="dash-link" href="{{ route('faqs.index') }}">{{ __('FAQ') }}</a>
                            </li>
                            <li class="dash-item dash-hasmenu {{ request()->is('*knowledge*') ? ' active' : '' }}">
                                <a class="dash-link" href="{{ route('knowledge') }}">{{ __('Knowledge Base') }}</a>
                            </li>
                        </ul>
                    </li>
                @endif


                @if (Auth::user()->super_admin_employee == 1 && array_search('manage support ticket', $premission_arr) )
                    <li class="dash-item {{ request()->is('category*') ? 'active' : '' }}">
                        <a href="{{ route('category.index') }}" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-layout-2"></i></span><span
                                class="dash-mtext">{{ __('Setup') }}</span>
                        </a>
                    </li>
                @endif
                @if (Auth::user()->type == 'company' ||
                        Auth::user()->type == 'advocate' ||
                        (Auth::user()->super_admin_employee == 1 && array_search('manage crm', $premission_arr)))
                    <li class="dash-item dash-hasmenu">
                        <a href="#!" class="dash-link ">
                            <span class="dash-micon"><i class="fa fa-spinner"></i>
                            </span><span class="dash-mtext">{{ __('Functional Setup') }}</span>
                            <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="dash-submenu">
                            @canany(['manage court', 'manage highcourt', 'manage bench'])
                                <li class="dash-item dash-hasmenu   dash-trigger">
                                    <a class="dash-link" href="#">{{ __('Courts Categories') }} <span
                                            class="dash-arrow">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-chevron-right">
                                                <polyline points="9 18 15 12 9 6"></polyline>
                                            </svg></span></a>
                                    <ul class="dash-submenu">
                                        @can('manage court')
                                            <li class="dash-item ">
                                                <a class="dash-link" href="{{ route('courts.index') }}">
                                                    {{ __('Courts/Tribunal') }}</a>
                                            </li>
                                        @endcan
                                        @can('manage highcourt')
                                            <li class="dash-item ">
                                                <a class="dash-link" href="{{ route('highcourts.index') }}">
                                                    {{ __('High Court') }}</a>
                                            </li>
                                        @endcan
                                        @can('manage bench')
                                            <li class="dash-item ">
                                                <a class="dash-link" href="{{ route('bench.index') }}">
                                                    {{ __('Circuit/Devision') }}</a>
                                            </li>
                                        @endcan
                                    </ul>

                                </li>
                            @endcan

                            @can('manage tax')
                                <li class="dash-item ">
                                    <a class="dash-link" href="{{ route('taxs.index') }}">{{ __('Tax') }}</a>
                                </li>
                            @endcan

                            @can('manage doctype')
                                <li class="dash-item ">
                                    <a class="dash-link"
                                        href="{{ route('doctype.index') }}">{{ __('Document Type') }}</a>
                                </li>

                                <li class="dash-item ">
                                    <a class="dash-link"
                                        href="{{ route('doctsubype.index') }}">{{ __('Document Sub-type') }}</a>
                                </li>
                            @endcan
                            {{-- <li class="dash-item ">
                                <a class="dash-link" href="{{ route('hearingType.index') }}">{{ __('Hearing Type') }}</a>
                            </li> --}}
                            @can('manage motions')
                                <li class="dash-item ">
                                    <a class="dash-link"
                                        href="{{ route('motions.index') }}">{{ __('Motions Types') }}</a>
                                </li>
                            @endcan

                            @if (Auth::user()->super_admin_employee == 1 && array_search('manage crm', $premission_arr))
                                <li class="dash-item ">
                                    <a class="dash-link"
                                        href="{{ route('pipeline.index') }}">{{ __('Pipeline') }}</a>
                                </li>
                                <li class="dash-item ">
                                    <a class="dash-link"
                                        href="{{ route('leadStage.index') }}">{{ __('Lead Stage') }}</a>
                                </li>
                                <li class="dash-item ">
                                    <a class="dash-link"
                                        href="{{ route('dealStage.index') }}">{{ __('Deal Stage') }}</a>
                                </li>
                                <li class="dash-item ">
                                    <a class="dash-link"
                                        href="{{ route('source.index') }}">{{ __('Source') }}</a>
                                </li>
                                <li class="dash-item ">
                                    <a class="dash-link" href="{{ route('label.index') }}">{{ __('Label') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif



                @if (\Auth::user()->type == 'super admin')
                    <li class="dash-item {{ request()->is('plan_request*') ? 'active' : '' }}">
                        <a href="{{ route('plan_request.index') }}" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-git-pull-request"></i></span><span
                                class="dash-mtext">{{ __('Plan Request') }}</span>
                        </a>
                    </li>
                @endif

                @can('manage coupon')
                    <li class="dash-item {{ Request::segment(1) == 'coupons' ? 'active' : '' }}">
                        <a class="dash-link" href="{{ route('coupons.index') }}">
                            <span class="dash-micon"><i class="ti ti-gift"></i></span><span
                                class="dash-mtext">{{ __('Coupons') }}</span>
                        </a>
                    </li>
                @endcan

                @can('manage order')
                    <li class="dash-item {{ Request::segment(1) == 'orders' ? 'active' : '' }}">
                        <a class="dash-link" href="{{ route('order.index') }}">
                            <span class="dash-micon"><i class="ti ti-credit-card"></i></span><span
                                class="dash-mtext">{{ __('Order') }}</span>
                        </a>
                    </li>
                @endcan

                @can('manage setting')
                    <li
                        class="dash-item dash-hasmenu {{ in_array(Request::segment(1), ['app-setting']) ? ' active' : '' }}">
                        <a href="{{ route('settings.index') }}" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-settings"></i></span>
                            <span class="dash-mtext">{{ __('Setting') }}</span>
                        </a>
                    </li>
                @endcan

                @if (\Auth::user()->type == 'super admin')
                    @include('landingpage::menu.landingpage')
                @endif

                @can('manage system settings')
                    <li class="dash-item {{ Request::route()->getName() == 'admin.settings' ? ' active' : '' }}">
                        <a class="dash-link" href="{{ route('admin.settings') }}">
                            <span class="dash-micon"><i class="ti ti-settings"></i></span><span
                                class="dash-mtext">{{ __('System Settings') }}</span>
                        </a>
                    </li>
                @endcan

            </ul>
        </div>
    </div>
</nav>
<!-- [ navigation menu ] end -->
