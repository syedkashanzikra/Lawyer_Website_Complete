@extends('layouts.app')

@include('Chatify::layouts.headLinks')

@section('page-title', __('Messenger'))

@section('breadcrumb')

<li class="breadcrumb-item active" aria-current="page">{{__('Messenger')}}</li>
@endsection

@section('content')

    <div class="col-xl-12">
        <div class="card shadow-none border-0 mt-4">
            <div class="card-body">
                <div class="messenger min-h-750 overflow-hidden " style="border: 1px solid #eee; border-right: 0;">
                    {{-- ----------------------Users/Groups lists side---------------------- --}}
                    <div class="messenger-listView">
                        {{-- Header and search bar --}}
                        <div class="m-header">
                            <nav>
                                <nav class="m-header-right">
                                    <a href="#" class="listView-x"><i class="fas fa-times"></i></a>
                                </nav>
                            </nav>
                            {{-- Search input --}}
                            <input type="text" class="messenger-search" placeholder="Search" />
                            {{-- Tabs --}}
                            <div class="messenger-listView-tabs">
                                <a href="#" @if ($route == 'user') class="active-tab" @endif
                                    data-view="users">
                                    <svg class="svg-inline--fa fa-clock fa-w-16" title="Recent"
                                        aria-labelledby="svg-inline--fa-title-JoqPdtylaC0E" data-prefix="fas"
                                        data-icon="clock" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 512 512" data-fa-i2svg="">
                                        <title id="svg-inline--fa-title-JoqPdtylaC0E">Recent</title>
                                        <path fill="currentColor"
                                            d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm57.1 350.1L224.9 294c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h48c6.6 0 12 5.4 12 12v137.7l63.5 46.2c5.4 3.9 6.5 11.4 2.6 16.8l-28.2 38.8c-3.9 5.3-11.4 6.5-16.8 2.6z">
                                        </path>
                                    </svg>
                                    {{-- <span class="ti ti-clock" title="{{__('Recent')}}"></span> --}}
                                </a>
                                <a href="#" @if ($route == 'group') class="active-tab" @endif
                                    data-view="groups">
                                    <svg class="svg-inline--fa fa-users fa-w-20" title="Members"
                                        aria-labelledby="svg-inline--fa-title-uU5Ic3YEEZcH" data-prefix="fas"
                                        data-icon="users" role="img" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 640 512" data-fa-i2svg="">
                                        <title id="svg-inline--fa-title-uU5Ic3YEEZcH">Members</title>
                                        <path fill="currentColor"
                                            d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6 40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z">
                                        </path>
                                    </svg>
                                    {{-- <span class="ti ti-users" title="{{__('Members')}}"></span> --}}
                                </a>
                            </div>
                        </div>
                        {{-- tabs and lists --}}
                        <div class="m-body">
                            {{-- Lists [Users/Group] --}}
                            {{-- ---------------- [ User Tab ] ---------------- --}}
                            <div class="@if ($route == 'user') show @endif messenger-tab app-scroll mt-2"
                                data-view="users">

                                {{-- Favorites --}}
                                <div class="favorites-section mt-2">
                                    <p class="messenger-title">{{ __('Favorites') }}</p>
                                    <div class="messenger-favorites app-scroll-thin"></div>
                                </div>

                                {{-- Saved Messages --}}
                                {!! view('Chatify::layouts.listItem', ['get' => 'saved', 'id' => $id])->render() !!}

                                {{-- Contact --}}
                                <div class="listOfContacts"
                                    style="width: 100%;height: calc(100% - 200px);position: relative;"></div>


                            </div>

                            {{-- ---------------- [ Group Tab ] ---------------- --}}

                            <div class="all_members @if ($route == 'group') show @endif messenger-tab app-scroll mt-2"
                                data-view="groups">
                                {{-- items --}}
                                <p style="text-align: center;color:grey;" class="mt-5">
                                    {{ __('Soon will be available') }}</p>
                            </div>
                            {{-- ---------------- [ Search Tab ] ---------------- --}}
                            <div class=" messenger-tab app-scroll mt-2" data-view="search">
                                {{-- items --}}
                                <p class="messenger-title">Search</p>
                                <div class="search-records">
                                    <p class="message-hint center-el"><span>Type to search..</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ----------------------Messaging side---------------------- --}}
                    <div class="messenger-messagingView">
                        {{-- header title [conversation name] amd buttons --}}
                        <div class="m-header m-header-messaging">
                            <nav class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                                {{-- header back button, avatar and user name --}}
                                <div class="chatify-d-flex chatify-justify-content-between chatify-align-items-center">
                                    <a href="#" class="show-listView"><i class="fas fa-arrow-left"></i></a>
                                    <div class="avatar av-s header-avatar"
                                        style="margin: 0px 10px; margin-top: -5px; margin-bottom: -5px;">
                                    </div>
                                    <a href="#" class="user-name">{{ config('chatify.name') }}</a>
                                </div>
                                {{-- header buttons --}}
                                <nav class="m-header-right">
                                    <a href="#" class="add-to-favorite"><i class="fas fa-star"></i></a>
                                    {{-- <a href="/"><i class="fas fa-home"></i></a> --}}
                                    <a href="#" class="show-infoSide"><i class="fas fa-info-circle"></i></a>
                                </nav>
                            </nav>
                            {{-- Internet connection --}}
                            <div class="internet-connection">
                                <span class="ic-connected">{{ __('Connected') }}</span>
                                <span class="ic-connecting">{{ __('Connecting...') }}</span>
                                <span
                                    class="ic-noInternet">{{ __('Please add pusher settings for using messenger') }}</span>
                            </div>
                        </div>

                        {{-- Messaging area --}}
                        <div class="m-body messages-container app-scroll">
                            <div class="messages">
                                <p class="message-hint center-el"><span>Please select a chat to start messaging</span>
                                </p>
                            </div>
                            {{-- Typing indicator --}}
                            <div class="typing-indicator">
                                <div class="message-card typing">
                                    <div class="message">
                                        <span class="typing-dots">
                                            <span class="dot dot-1"></span>
                                            <span class="dot dot-2"></span>
                                            <span class="dot dot-3"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        {{-- Send Message Form --}}
                        @include('Chatify::layouts.sendForm')
                    </div>
                    {{-- ---------------------- Info side ---------------------- --}}
                    <div class="messenger-infoView app-scroll">
                        {{-- nav actions --}}
                        <nav>
                            <p>User Details</p>
                            <a href="#"><i class="fas fa-times"></i></a>
                        </nav>
                        {!! view('Chatify::layouts.info')->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@include('Chatify::layouts.modals')

@push('custom-script')
    @include('Chatify::layouts.footerLinks')
@endpush
