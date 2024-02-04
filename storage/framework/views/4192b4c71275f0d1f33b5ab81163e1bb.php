<?php
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

?>

<!-- [ Pre-loader ] start -->
<div class="loader-bg">
    <div class="loader-track">
        <div class="loader-fill"></div>
    </div>
</div>
<!-- [ Pre-loader ] End -->

<!-- [ navigation menu ] start -->
<nav class="dash-sidebar light-sidebar <?php echo e(isset($mode_setting['cust_theme_bg']) && $mode_setting['cust_theme_bg'] == 'on' ? 'transprent-bg' : ''); ?>">

    <div class="navbar-wrapper">
        <div class="m-header main-logo">
            <a href="<?php echo e(route('dashboard')); ?>" class="b-brand">
                <!-- ========   change your logo hear   ============ -->
                <img src="<?php echo e($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') . '?' . time()); ?>"
                    alt="" class="logo logo-lg" />
            </a>
        </div>
        <div class="navbar-content">
            <ul class="dash-navbar">


                <li class="dash-item dash-hasmenu <?php echo e(\Request::route()->getName() == 'dashboard' ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('dashboard')); ?>" class="dash-link ">
                        <span class="dash-micon"><i class="ti ti-home"></i>
                        </span><span class="dash-mtext"><?php echo e(__('Dashboard')); ?></span>
                        <span class="dash-arrow"></span>
                    </a>
                </li>

                <?php if(Auth::user()->type == 'super admin'): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage user')): ?>
                        <li class="dash-item dash-hasmenu <?php echo e(request()->is('users*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('users.list')); ?>" class="dash-link"><span class="dash-micon"><i
                                        class="ti ti-users"></i></span><span class="dash-mtext"><?php echo e(__('Companies')); ?></span>
                            </a>
                        </li>
                        <li class="dash-item dash-hasmenu <?php echo e(request()->is('employee*') ? 'active' : ''); ?>">
                            <a href="<?php echo e(route('employee.index')); ?>" class="dash-link"><span class="dash-micon">
                                    <i class="ti ti-user-check"></i></span><span
                                    class="dash-mtext"><?php echo e(__('User')); ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['manage member', 'manage group', 'manage role'])): ?>
                        <li
                            class="dash-item dash-hasmenu <?php echo e(Request::route()->getName() == 'users.edit' || Request::route()->getName() == 'users.list' || Request::route()->getName() == 'userlog.index' ? 'active dash-trigger' : ''); ?>">
                            <a href="#!" class="dash-link ">
                                <span class="dash-micon"><i class="ti ti-users"></i>
                                </span><span class="dash-mtext"><?php echo e(__('Staff')); ?></span>
                                <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                            </a>
                            <ul
                                class="dash-submenu <?php echo e(Request::segment(1) == 'roles' || Request::segment(1) == 'users' || Request::route()->getName() == 'users.list' || Request::segment(1) == 'groups' ? 'show' : ''); ?>">

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage role')): ?>
                                    <li class="dash-item <?php echo e(in_array(Request::segment(1), ['roles', '']) ? ' active' : ''); ?>">
                                        <a class="dash-link" href="<?php echo e(route('roles.index')); ?>"><?php echo e(__('Role')); ?></a>
                                    </li>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage member')): ?>
                                    <li
                                        class="dash-item <?php echo e(Request::route()->getName() == 'users.edit' || Request::route()->getName() == 'users.list' || Request::route()->getName() == 'userlog.index' ? 'active' : ''); ?>">
                                        <a class="dash-link" href="<?php echo e(route('users.index')); ?>"><?php echo e(__('Employees')); ?></a>
                                    </li>
                                <?php endif; ?>

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage group')): ?>
                                    <li class="dash-item <?php echo e(in_array(Request::segment(1), ['groups', '']) ? ' active' : ''); ?>">
                                        <a class="dash-link" href="<?php echo e(route('groups.index')); ?>"><?php echo e(__('Group')); ?></a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage client')): ?>
                    <li
                        class="dash-item dash-hasmenu <?php echo e(in_array(Request::segment(1), ['client', 'client-list']) ? ' active' : ''); ?>">
                        <a href="<?php echo e(route('client.index')); ?>" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-user-check"></i></span>
                            <span class="dash-mtext"><?php echo e(__('Client')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage advocate')): ?>
                    <li class="dash-item dash-hasmenu <?php echo e(in_array(Request::segment(1), ['advocate']) ? ' active' : ''); ?>">
                        <a href="<?php echo e(route('advocate.index')); ?>" class="dash-link">
                            <span class="dash-micon"><i class="fa fa-tasks"></i></span>
                            <span class="dash-mtext"><?php echo e(__('Advocate')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage case')): ?>
                    <li class="dash-item dash-hasmenu <?php echo e(in_array(Request::segment(1), ['cases']) ? ' active' : ''); ?>">
                        <a href="<?php echo e(route('cases.index')); ?>" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-file-text"></i></span>
                            <span class="dash-mtext"><?php echo e(__('Cases')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage todo')): ?>
                    <li class="dash-item dash-hasmenu <?php echo e(in_array(Request::segment(1), ['todo']) ? ' active' : ''); ?>">
                        <a href="<?php echo e(route('to-do.index')); ?>" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-file-plus"></i></span>
                            <span class="dash-mtext"><?php echo e(__('To-Do')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                



                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage diary')): ?>
                    <li
                        class="dash-item dash-hasmenu <?php echo e(in_array(Request::segment(1), ['casediary']) || in_array(Request::segment(1), ['calendar']) ? ' active' : ''); ?>">
                        <a href="<?php echo e(route('casediary.index')); ?>" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-license"></i></span>
                            <span class="dash-mtext"><?php echo e(__('Case Diary/Calendar')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage document')): ?>
                    <li class="dash-item dash-hasmenu <?php echo e(in_array(Request::segment(1), ['documents']) ? ' active' : ''); ?>">
                        <a href="<?php echo e(route('documents.index')); ?>" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-files"></i></span>
                            <span class="dash-mtext"><?php echo e(__('Documents')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage bill')): ?>
                    <li class="dash-item dash-hasmenu <?php echo e(in_array(Request::segment(1), ['bills']) ? ' active' : ''); ?>">
                        <a href="<?php echo e(route('bills.index')); ?>" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-file-analytics"></i></span>
                            <span class="dash-mtext"><?php echo e(__('Bills / Invoices')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage cause')): ?>
                    <li class="dash-item dash-hasmenu <?php echo e(in_array(Request::segment(1), ['cause']) ? ' active' : ''); ?>">
                        <a href="<?php echo e(route('cause.index')); ?>" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-clipboard-list"></i></span>
                            <span class="dash-mtext"><?php echo e(__('Cause List')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>



                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage timesheet')): ?>
                    <li class="dash-item dash-hasmenu <?php echo e(in_array(Request::segment(1), ['timesheet']) ? ' active' : ''); ?>">
                        <a href="<?php echo e(route('timesheet.index')); ?>" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-list-check"></i></span>
                            <span class="dash-mtext"><?php echo e(__('Timesheet')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage expense')): ?>
                    <li class="dash-item dash-hasmenu <?php echo e(in_array(Request::segment(1), ['expenses']) ? ' active' : ''); ?>">
                        <a href="<?php echo e(route('expenses.index')); ?>" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-report"></i></span>
                            <span class="dash-mtext"><?php echo e(__('Expense')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage feereceived')): ?>
                    <li
                        class="dash-item dash-hasmenu <?php echo e(in_array(Request::segment(1), ['fee-receive']) ? ' active' : ''); ?>">
                        <a href="<?php echo e(route('fee-receive.index')); ?>" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-receipt-2"></i></span>
                            <span class="dash-mtext"><?php echo e(__('Fee Received')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if(\Auth::user()->type != 'super admin'): ?>
                    <li class="dash-item <?php echo e(\Request::route()->getName() == 'chats' ? ' active' : ''); ?>">
                        <a href="<?php echo e(url('chats')); ?>"
                            class="dash-link <?php echo e(Request::segment(1) == 'chats' ? 'active' : ''); ?>">
                            <span class="dash-micon"><i class="ti ti-brand-messenger"></i></span><span
                                class="dash-mtext"><?php echo e(__('Messenger')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(\Auth::user()->type == 'company' || \Auth::user()->type == 'super admin'): ?>
                    <li
                        class="dash-item <?php echo e(Request::segment(1) == 'plans' || Request::route()->getName() == 'payment' ? 'active' : ''); ?>">
                        <a class="dash-link" href="<?php echo e(route('plans.index')); ?>">
                            <span class="dash-micon"><i class="ti ti-trophy"></i></span><span
                                class="dash-mtext"><?php echo e(__('Plan')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(
                    (Auth::user()->super_admin_employee == 1 && array_search('manage crm', $premission_arr)) ||
                        (Auth::user()->type == 'advocate') || (Auth::user()->type == 'company')): ?>
                    <li class="dash-item dash-hasmenu">
                        <a href="#!" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-affiliate"></i>
                            </span><span class="dash-mtext"><?php echo e(__('CRM')); ?></span>
                            <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="dash-submenu">
                            <?php if(
                                (Auth::user()->super_admin_employee == 1 && array_search('manage crm', $premission_arr)) || (Auth::user()->type == 'company') ): ?>
                                <li
                                    class="dash-item dash-hasmenu <?php echo e(Request::segment(1) == 'lead' ? 'active' : ''); ?>">
                                    <a class="dash-link"
                                        href="<?php echo e(route('lead.index')); ?>"><?php echo e(__('Lead')); ?></a>
                                </li>
                            <?php endif; ?>
                            <?php if(
                                (Auth::user()->super_admin_employee == 1 && array_search('manage crm', $premission_arr)) ||
                                    Auth::user()->type == 'advocate' || (Auth::user()->type == 'company')): ?>
                                <li
                                    class="dash-item dash-hasmenu <?php echo e(Request::segment(1) == 'deal' ? 'active' : ''); ?>">
                                    <a class="dash-link"
                                        href="<?php echo e(route('deal.index')); ?>"><?php echo e(__('Deal')); ?></a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if(Auth::user()->super_admin_employee == 1 && array_search('manage support ticket', $premission_arr) || (Auth::user()->type == 'company')): ?>
                    <li class="dash-item dash-hasmenu">
                        <a href="#!" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-ticket"></i>
                            </span><span class="dash-mtext"><?php echo e(__('Support Ticket')); ?></span>
                            <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="dash-submenu">
                            <li class="dash-item dash-hasmenu <?php echo e(request()->is('*ticket*') ? ' active' : ''); ?>">
                                <a class="dash-link" href="<?php echo e(route('tickets.index')); ?>"><?php echo e(__('Tickets')); ?></a>
                            </li>
                            <li class="dash-item dash-hasmenu <?php echo e(request()->is('*faq*') ? ' active' : ''); ?>">
                                <a class="dash-link" href="<?php echo e(route('faqs.index')); ?>"><?php echo e(__('FAQ')); ?></a>
                            </li>
                            <li class="dash-item dash-hasmenu <?php echo e(request()->is('*knowledge*') ? ' active' : ''); ?>">
                                <a class="dash-link" href="<?php echo e(route('knowledge')); ?>"><?php echo e(__('Knowledge Base')); ?></a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>


                <?php if(Auth::user()->super_admin_employee == 1 && array_search('manage support ticket', $premission_arr) ): ?>
                    <li class="dash-item <?php echo e(request()->is('category*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('category.index')); ?>" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-layout-2"></i></span><span
                                class="dash-mtext"><?php echo e(__('Setup')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if(Auth::user()->type == 'company' ||
                        Auth::user()->type == 'advocate' ||
                        (Auth::user()->super_admin_employee == 1 && array_search('manage crm', $premission_arr))): ?>
                    <li class="dash-item dash-hasmenu">
                        <a href="#!" class="dash-link ">
                            <span class="dash-micon"><i class="fa fa-spinner"></i>
                            </span><span class="dash-mtext"><?php echo e(__('Functional Setup')); ?></span>
                            <span class="dash-arrow"><i data-feather="chevron-right"></i></span>
                        </a>
                        <ul class="dash-submenu">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['manage court', 'manage highcourt', 'manage bench'])): ?>
                                <li class="dash-item dash-hasmenu   dash-trigger">
                                    <a class="dash-link" href="#"><?php echo e(__('Courts Categories')); ?> <span
                                            class="dash-arrow">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-chevron-right">
                                                <polyline points="9 18 15 12 9 6"></polyline>
                                            </svg></span></a>
                                    <ul class="dash-submenu">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage court')): ?>
                                            <li class="dash-item ">
                                                <a class="dash-link" href="<?php echo e(route('courts.index')); ?>">
                                                    <?php echo e(__('Courts/Tribunal')); ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage highcourt')): ?>
                                            <li class="dash-item ">
                                                <a class="dash-link" href="<?php echo e(route('highcourts.index')); ?>">
                                                    <?php echo e(__('High Court')); ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage bench')): ?>
                                            <li class="dash-item ">
                                                <a class="dash-link" href="<?php echo e(route('bench.index')); ?>">
                                                    <?php echo e(__('Circuit/Devision')); ?></a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>

                                </li>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage tax')): ?>
                                <li class="dash-item ">
                                    <a class="dash-link" href="<?php echo e(route('taxs.index')); ?>"><?php echo e(__('Tax')); ?></a>
                                </li>
                            <?php endif; ?>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage doctype')): ?>
                                <li class="dash-item ">
                                    <a class="dash-link"
                                        href="<?php echo e(route('doctype.index')); ?>"><?php echo e(__('Document Type')); ?></a>
                                </li>

                                <li class="dash-item ">
                                    <a class="dash-link"
                                        href="<?php echo e(route('doctsubype.index')); ?>"><?php echo e(__('Document Sub-type')); ?></a>
                                </li>
                            <?php endif; ?>
                            
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage motions')): ?>
                                <li class="dash-item ">
                                    <a class="dash-link"
                                        href="<?php echo e(route('motions.index')); ?>"><?php echo e(__('Motions Types')); ?></a>
                                </li>
                            <?php endif; ?>

                            <?php if(Auth::user()->super_admin_employee == 1 && array_search('manage crm', $premission_arr)): ?>
                                <li class="dash-item ">
                                    <a class="dash-link"
                                        href="<?php echo e(route('pipeline.index')); ?>"><?php echo e(__('Pipeline')); ?></a>
                                </li>
                                <li class="dash-item ">
                                    <a class="dash-link"
                                        href="<?php echo e(route('leadStage.index')); ?>"><?php echo e(__('Lead Stage')); ?></a>
                                </li>
                                <li class="dash-item ">
                                    <a class="dash-link"
                                        href="<?php echo e(route('dealStage.index')); ?>"><?php echo e(__('Deal Stage')); ?></a>
                                </li>
                                <li class="dash-item ">
                                    <a class="dash-link"
                                        href="<?php echo e(route('source.index')); ?>"><?php echo e(__('Source')); ?></a>
                                </li>
                                <li class="dash-item ">
                                    <a class="dash-link" href="<?php echo e(route('label.index')); ?>"><?php echo e(__('Label')); ?></a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>



                <?php if(\Auth::user()->type == 'super admin'): ?>
                    <li class="dash-item <?php echo e(request()->is('plan_request*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('plan_request.index')); ?>" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-git-pull-request"></i></span><span
                                class="dash-mtext"><?php echo e(__('Plan Request')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage coupon')): ?>
                    <li class="dash-item <?php echo e(Request::segment(1) == 'coupons' ? 'active' : ''); ?>">
                        <a class="dash-link" href="<?php echo e(route('coupons.index')); ?>">
                            <span class="dash-micon"><i class="ti ti-gift"></i></span><span
                                class="dash-mtext"><?php echo e(__('Coupons')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage order')): ?>
                    <li class="dash-item <?php echo e(Request::segment(1) == 'orders' ? 'active' : ''); ?>">
                        <a class="dash-link" href="<?php echo e(route('order.index')); ?>">
                            <span class="dash-micon"><i class="ti ti-credit-card"></i></span><span
                                class="dash-mtext"><?php echo e(__('Order')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage setting')): ?>
                    <li
                        class="dash-item dash-hasmenu <?php echo e(in_array(Request::segment(1), ['app-setting']) ? ' active' : ''); ?>">
                        <a href="<?php echo e(route('settings.index')); ?>" class="dash-link">
                            <span class="dash-micon"><i class="ti ti-settings"></i></span>
                            <span class="dash-mtext"><?php echo e(__('Setting')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(\Auth::user()->type == 'super admin'): ?>
                    <?php echo $__env->make('landingpage::menu.landingpage', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage system settings')): ?>
                    <li class="dash-item <?php echo e(Request::route()->getName() == 'admin.settings' ? ' active' : ''); ?>">
                        <a class="dash-link" href="<?php echo e(route('admin.settings')); ?>">
                            <span class="dash-micon"><i class="ti ti-settings"></i></span><span
                                class="dash-mtext"><?php echo e(__('System Settings')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>
<!-- [ navigation menu ] end -->
<?php /**PATH C:\wamp64\www\advocatego-saas-legal-practice-management\main-file\resources\views/partision/sidebar.blade.php ENDPATH**/ ?>