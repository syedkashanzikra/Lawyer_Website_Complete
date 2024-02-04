@extends('layouts.custom_guest')
@section('title-content')

@endsection
@section('content')
<div class="col-xl-12 text-start">
    <div class="mx-3 mx-md-5">
        <div class="card-header">
            <h5 class="text-white">{{ __('Ticket') }} - {{$ticket->ticket_id}}</h5>
        </div>
    </div>

    <div class="card p-4">
        @csrf
        <div class="card mb-3">
            <div class="card-header"><h6>{{$ticket->name}} <small>({{$ticket->created_at->diffForHumans()}})</small></h6></div>
            <div class="card-body w-100">
                <div>
                    <p>{!! $ticket->description !!}</p>
                </div>
                @php
                    $attachments=json_decode($ticket->attachments);
                @endphp
                @if(!is_null($attachments) && count($attachments)>0)
                    <div class="m-1 ml-3">
                        <b>{{ __('Attachments') }} :</b>
                        <ul class="list-group list-group-flush">
                            @foreach($attachments as $index => $attachment)
                                <li class="list-group-item px-0">
                                    {{$attachment}}<a download="" href="{{ asset(Storage::url('tickets/'.$ticket->ticket_id."/".$attachment)) }}" class="edit-icon py-1 ml-2" title="{{ __('Download') }}"><i class="fa fa-download ms-2"></i></a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
        @foreach($ticket->conversions as $conversion)
            <div class="card mb-3">
                <div class="card-header"><h6>{{$conversion->replyBy()->name}} <small>({{$conversion->created_at->diffForHumans()}})</small></h6></div>
                <div class="card-body w-100">
                    <div>{!! $conversion->description !!}</div>
                    @php
                        $attachments=json_decode($conversion->attachments);
                    @endphp
                    @if(count($attachments))
                        <div class="m-1">
                            <b>{{ __('Attachments') }} :</b>
                            <ul class="list-group list-group-flush">

                                @foreach($attachments as $index => $attachment)
                                
                                    <li class="list-group-item px-0">
                                        {{$attachment}}<a download="" href="@if($conversion->sender!='user'){{ asset(Storage::url('reply_tickets/'.$ticket->id."/".$attachment))  }} @else {{ asset(Storage::url('tickets/'.$ticket->ticket_id."/".$attachment))  }} @endif" class="edit-icon py-1 ml-2" title="{{ __('Download') }}"><i class="fa fa-download ms-2"></i></a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        @if($ticket->status != 'Closed')
            <div class="card mb-3">
                <div class="card-body w-100">
                    <form method="post" action="{{route('user.ticket.reply',$ticket->ticket_id)}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label class="require form-label">{{ __('Description') }}</label>
                                <textarea name="reply_description" class="form-control {{ $errors->has('reply_description') ? ' is-invalid' : '' }}">{{old('reply_description')}}</textarea>
                                <div class="invalid-feedback">
                                    {{ $errors->first('reply_description') }}
                                </div>
                            </div>
                            <div class="form-group col-md-12 file-group">
                                <label class="require form-label">{{ __('Attachments') }}</label>
                                <label class="form-label"><small>({{__('You can select multiple files')}})</small></label>
                                <div class="choose-file form-group">
                                    <label for="file" class="form-label">
                                        <div>{{ __('Choose File Here') }}</div>
                                        <input type="file" class="form-control {{ $errors->has('reply_attachments') ? 'is-invalid' : '' }}" multiple="" name="reply_attachments[]" id="file" data-filename="multiple_reply_file_selection">
                                        <div class="invalid-feedback">
                                            {{ $errors->first('reply_attachments') }}
                                        </div>
                                    </label>
                                    <p class="multiple_reply_file_selection"></p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <div class="text-center">
                                <input type="hidden" name="status" value="New Ticket"/>
                                <button class="btn btn-submit btn-primary btn-block mt-2">{{ __('Submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body">
                    <p class="text-blue font-weight-bold text-center mb-0">{{ __('Ticket is closed you cannot replay.') }}</p>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection