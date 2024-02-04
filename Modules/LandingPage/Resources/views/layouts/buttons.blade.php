@php
    $settings = \Modules\LandingPage\Entities\LandingPageSetting::settings();
@endphp
@if ($settings['menubar_status'] == 'on')
    @if (is_array(json_decode($settings['menubar_page'])) || is_object(json_decode($settings['menubar_page'])))
        @foreach (json_decode($settings['menubar_page']) as $key => $value)
            @if ((isset($value->login) && $value->login == "on") && (isset($value->template_name) && $value->template_name == 'page_content'))
                <li class="nav-item">
                    <a class="nav-link"
                       href="{{ route('custom.page', $value->page_slug) }}">{{ $value->menubar_page_name }}</a>
                </li>
            @elseif ( (isset($value->login) && $value->login == "on") && (isset($value->template_name) && $value->template_name == 'page_url'))
                <li class="nav-item">
                    <a class="nav-link" target="_blank" href="{{ $value->page_url }}">{{ $value->menubar_page_name }}</a>
                </li>
            @endif
        @endforeach
    @endif
@endif
