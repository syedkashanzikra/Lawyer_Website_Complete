

<?php
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

?>

    <header class="dash-header <?php echo e((isset($settings['cust_theme_bg']) && $settings['cust_theme_bg'] == 'on')?'transprent-bg':''); ?>">
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
                                src="<?php echo e(!empty(\Auth::user()->avatar) ? $logo.  \Auth::user()->avatar : $logo . '/avatar.png'); ?>"
                                class="header-avtar">
                        </span>
                        <span class="hide-mob ms-2">
                            <?php if(!Auth::guest()): ?>
                                <?php echo e(__('Hi, ')); ?><?php echo e(Auth::user()->name); ?>!
                            <?php else: ?>
                                <?php echo e(__('Guest')); ?>

                            <?php endif; ?>
                        </span>
                        <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                    </a>

                    <div class="dropdown-menu dash-h-dropdown">
                        <a href="<?php echo e(route('users.edit', Auth::user()->id)); ?>" class="dropdown-item">
                            <i class="ti ti-user"></i>
                            <span><?php echo e(__('Profile')); ?></span>
                        </a>
                        <form method="POST" action="<?php echo e(route('logout')); ?>" id="form_logout">
                            <?php echo csrf_field(); ?>
                            <a href="#"  class="dropdown-item" id="logout-form">
                                <i class="ti ti-power"></i>
                                <?php echo e(__('Log Out')); ?>

                            </a>
                        </form>
                    </div>
                </li>



                <li class="dropdown dash-h-item drp-company">
                    <a class="dash-head-link  me-0 " href="<?php echo e(route('user.ticket.create')); ?>"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <span class="theme-avtar">
                            <i class="ti ti-help"></i>
                        </span>
                        <span class="hide-mob ms-2">
                            <?php echo e(__('Contact Us')); ?>

                        </span>
                    </a>

                </li>

                <?php if (is_impersonating($guard = null)) : ?>

                    <li class="dropdown dash-h-item drp-company">
                        <a class="dash-head-link  me-0 bg-danger text-white"  href="<?php echo e(route('exit.admin')); ?>"
                            role="button" aria-haspopup="false" aria-expanded="false">
                            <span class="theme-avtar">
                                <i class="ti ti-ban"></i>
                            </span>
                            <span class="hide-mob ms-2">
                                <?php echo e(__('Exit Company Login')); ?>

                            </span>
                        </a>

                    </li>
                <?php endif; ?>

            </ul>
        </div>


        <div class="ms-auto">
            <ul class="list-unstyled">
                <li class="dropdown dash-h-item drp-notification">
                    <a class="dash-head-link dropdown-toggle arrow-none show" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="true">
                        <i class="ti ti-bell"></i>
                        <?php if(count($notifications) > 0): ?>
                            <span class="bg-danger dash-h-badge dots"><span class="sr-only"></span></span>
                        <?php endif; ?>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end " data-popper-placement="bottom-end"
                        style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(-8px, 58px);">
                        <div class="noti-header">
                            <h5 class="m-0"><?php echo e(__('Notification')); ?></h5>
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
                                                <?php if(count($notifications) > 0): ?>
                                                    <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php $bill=App\Models\Notification::getBillDetail($notification->bill_id);?>
                                                        <div class="d-flex align-items-start my-4">
                                                            <div class="theme-avtar bg-primary">
                                                                <i class="ti ti-file-analytics"></i>
                                                            </div>
                                                            <div class="ms-3 flex-grow-1">
                                                                <div
                                                                    class="d-flex align-items-start justify-content-between">
                                                                    <a href="<?php echo e(route('bills.show', $bill->id)); ?>">
                                                                        <?php if($users->type == 'client'): ?>
                                                                            <h6><?php echo e($bill->bill_number); ?>

                                                                                <?php echo e('Please pay bill before due date'); ?>

                                                                            </h6>
                                                                        <?php else: ?>
                                                                            <h6><?php echo e($bill->bill_number); ?>

                                                                                <?php echo e('This bill payment is not receive yet'); ?>

                                                                            </h6>
                                                                        <?php endif; ?>
                                                                    </a>
                                                                </div>
                                                                <div
                                                                    class="d-flex align-items-end justify-content-between">
                                                                    <?php if($users->type == 'client'): ?>
                                                                        <?php echo e(__('Complete the payment by the due date to avoid any inconvenience.')); ?>

                                                                    <?php else: ?>
                                                                        <p class="mb-0 text-muted">
                                                                            <?php echo e(__('you not received your payment yet please kindly remind to your client before the due date to avoid any inconvenience')); ?>

                                                                    <?php endif; ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php else: ?>
                                                    <div class="d-grid">
                                                        <a
                                                            class="btn dash-head-link justify-content-center bg-light-primary text-primary mx-0"><?php echo e('No Notification.'); ?>

                                                        </a>
                                                    </div>
                                                <?php endif; ?>
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
                        <span class="drp-text hide-mob"><?php echo e(Str::upper($LangName->fullName)); ?></span>
                        <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end " aria-labelledby="dropdownLanguage">
                        <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('change.language', $code)); ?>" class="dropdown-item <?php echo e($currantLang == $code ? 'text-danger' : ''); ?>">
                                <?php echo e(Str::upper($lang)); ?>

                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create language')): ?>
                            <div class="dropdown-divider m-0"></div>

                            <a href="#" data-url="<?php echo e(route('create.language')); ?>" data-size="md" data-ajax-popup="true" data-title="<?php echo e(__('Create New Language')); ?>"
                            class="dropdown-item  text-primary text-primary" ><?php echo e(__('Create Language')); ?></a>
                            <div class="dropdown-divider m-0"></div>
                            <a href="<?php echo e(route('manage.language', $currantLang)); ?>"
                                class="dropdown-item text-primary"><?php echo e(__('Manage Language')); ?></a>
                        <?php endif; ?>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>

<?php $__env->startPush('custom-script'); ?>
    <script>
        $('#logout-form').on('click',function(){
            event.preventDefault();
            $('#form_logout').trigger('submit');
        });
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\wamp64\www\advocatego-saas-legal-practice-management\main-file\resources\views/partision/header.blade.php ENDPATH**/ ?>