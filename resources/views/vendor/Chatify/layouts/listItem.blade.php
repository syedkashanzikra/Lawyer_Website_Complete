{{-- -------------------- Saved Messages -------------------- --}}
@if($get == 'saved')
<table class="messenger-list-item mt-2" data-contact="{{ Auth::user()->id }}">
    <tr data-action="0">
        {{-- Avatar side --}}
        <td>
            <div class="avatar av-m" style="background-color: #D9EFFF; text-align: center;">
                <span class="ti ti-bookmark" style="font-size: 22px; color: #6FD943;"></span>
            </div>
        </td>
        {{-- center side --}}
        <td>
            <p data-id="{{ Auth::user()->id }}" data-type="user">Saved Messages <span>You</span></p>
            <span>Save messages secretly</span>
        </td>
    </tr>
</table>
@endif

{{-- -------------------- Contact list -------------------- --}}
@if($get == 'users' && !!$lastMessage)
<?php
    $lastMessageBody = mb_convert_encoding($lastMessage->body, 'UTF-8', 'UTF-8');
    $lastMessageBody = strlen($lastMessageBody) > 30 ? mb_substr($lastMessageBody, 0, 30, 'UTF-8').'..' : $lastMessageBody;
?>
<table class="messenger-list-item" data-contact="{{ $user->id }}">
    <tr data-action="0">
        {{-- Avatar side --}}
        <td style="position: relative">
            @if($user->active_status)
            <span class="activeStatus"></span>
            @endif
            @if(!empty($user->avatar))
                <div class="avatar av-m"
                    style="background-image: url('{{ asset('/storage/'.config('chatify.user_avatar.folder').'/'.$user->avatar) }}');">
                </div>
            @else
                <div class="avatar av-m"
                    style="background-image: url('{{ asset('/storage/'.config('chatify.user_avatar.folder').'/avatar.png') }}');">
                </div>
            @endif

        </td>
        {{-- center side --}}
        <td>
            <p data-id="{{ $user->id }}" data-type="user">
                {{ strlen($user->name) > 12 ? trim(substr($user->name,0,12)).'..' : $user->name }}
                <span class="contact-item-time" data-time="{{$lastMessage->created_at}}">{{ $lastMessage->timeAgo
                    }}</span>
            </p>
            <span>
                {{-- Last Message user indicator --}}
                {!!
                $lastMessage->from_id == Auth::user()->id
                ? '<span class="lastMessageIndicator">You :</span>'
                : ''
                !!}
                {{-- Last message body --}}
                @if($lastMessage->attachment == null)
                {!!
                $lastMessageBody
                !!}
                @else
                <span class="fas fa-file"></span> Attachment
                @endif
            </span>
            {{-- New messages counter --}}
            {!! $unseenCounter > 0 ? "<b>".$unseenCounter."</b>" : '' !!}
        </td>
    </tr>
</table>
@endif

{{-- -------------------- Search Item -------------------- --}}
@if($get == 'search_item')
    <table class="messenger-list-item" data-contact="{{ $user->id }}">
        <tr data-action="0">
            {{-- Avatar side --}}
            <td>
                @if(!empty($user->avatar))
            <div class="avatar av-m"
                style="background-image: url('{{ asset('/storage/'.config('chatify.user_avatar.folder').'/'.$user->avatar) }}');">
            </div>
            @else
            <div class="avatar av-m"
                style="background-image: url('{{ asset('/storage/'.config('chatify.user_avatar.folder').'/avatar.png') }}');">
            </div>
            @endif
            </td>
            {{-- center side --}}
            <td>
                <p data-id="{{ $user->id }}" data-type="user">
                    {{ strlen($user->name) > 12 ? trim(substr($user->name,0,12)).'..' : $user->name }}
            </td>

        </tr>
    </table>
@endif
@if($get == 'all_members')

<table class="messenger-list-item" data-contact="{{ $user->id }}">
    <tr data-action="0">
        {{-- Avatar side --}}
        <td style="position: relative">
            @if($user->active_status)
            <span class="activeStatus"></span>
            @endif
            @if(!empty($user->avatar))
            <div class="avatar av-m"
                style="background-image: url('{{ asset('/storage/'.config('chatify.user_avatar.folder').'/'.$user->avatar) }}');">
            </div>
            @else
            <div class="avatar av-m"
                style="background-image: url('{{ asset('/storage/'.config('chatify.user_avatar.folder').'/avatar.png') }}');">
            </div>
            @endif
        </td>
        {{-- center side --}}
        <td>
            <p data-id="{{ $user->id }}">
                {{ strlen($user->name) > 12 ? trim(substr($user->name,0,12)).'..' : $user->name }}
        </td>

    </tr>
</table>
@endif

{{-- -------------------- Shared photos Item -------------------- --}}
@if($get == 'sharedPhoto')
<div class="shared-photo chat-image" style="background-image: url('{{ $image }}')"></div>
@endif
