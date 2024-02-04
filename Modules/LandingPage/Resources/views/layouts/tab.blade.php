

    <a href="{{ route('landingpage.index') }}" class="list-group-item list-group-item-action {{ (Request::route()->getName() == 'landingpage.index') ? ' active' : '' }}">{{ __('Top Bar') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

    <a href="{{ route('custom_page.index') }}" class="list-group-item list-group-item-action {{ (Request::route()->getName() == 'custom_page.index') ? ' active' : '' }}">{{ __('Custom Page') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

    <a href="{{ route('homesection.index') }}" class="list-group-item list-group-item-action {{ (Request::route()->getName() == 'homesection.index') ? ' active' : '' }}">{{ __('Home') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

    <a href="{{ route('features.index') }}" class="list-group-item list-group-item-action {{ (Request::route()->getName() == 'features.index') ? ' active' : '' }}">{{ __('Features') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

    <a href="{{ route('discover.index') }}" class="list-group-item list-group-item-action {{ (Request::route()->getName() == 'discover.index') ? ' active' : '' }}">{{ __('Discover') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

    <a href="{{ route('screenshots.index') }}" class="list-group-item list-group-item-action {{ (Request::route()->getName() == 'screenshots.index') ? ' active' : '' }}">{{ __('Screenshots') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

    <a href="{{ route('pricing_plan.index') }}" class="list-group-item list-group-item-action {{ (Request::route()->getName() == 'pricing_plan.index') ? ' active' : '' }}">{{ __('Pricing Plan') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

    <a href="{{ route('faq.index') }}" class="list-group-item list-group-item-action {{ (Request::route()->getName() == 'faq.index') ? ' active' : '' }}">{{ __('FAQ') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

    <a href="{{ route('testimonials.index') }}" class="list-group-item list-group-item-action {{ (Request::route()->getName() == 'testimonials.index') ? ' active' : '' }}">{{ __('Testimonials') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

    <a href="{{ route('join_us.index') }}" class="list-group-item list-group-item-action {{ (Request::route()->getName() == 'join_us.index') ? ' active' : '' }}">{{ __('Join Us') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>


