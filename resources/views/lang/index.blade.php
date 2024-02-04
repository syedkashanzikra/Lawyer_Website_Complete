@extends('layouts.app')
@section('page-title')
    {{ __('Manage Language') }}
@endsection

@section('action-button')
<div class="float-end">
    <div class="row">

            @if($currantLang != (!empty( $settings['default_language']) ?  $settings['default_language'] : 'en'))
                <div class="col-auto">
                    <div class="form-check form-switch custom-switch-v1">
                        <input type="hidden" name="disable_lang" value="off">
                        <input type="checkbox" class="form-check-input input-primary" name="disable_lang" data-bs-placement="top" title="{{ __('Enable/Disable') }}" id="disable_lang" data-bs-toggle="tooltip" {{ !in_array($currantLang,$disabledLang) ? 'checked':'' }} >
                        <label class="form-check-label" for="disable_lang"></label>
                    </div>
                </div>
            @endif
            @if($currantLang != \App\Models\Utility::settings()['default_language'])
                <div class="col-auto">
                    {!! Form::open(['method' => 'DELETE', 'route' => ['destroy.language', $currantLang],'id'=>'delete-form-'.$currantLang]) !!}
                    <a href="#" class="btn-submit btn btn-sm btn-danger btn-icon bs-pass-para" data-toggle="tooltip" data-original-title="{{__('Delete This Language')}}"
                        data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}"
                        data-confirm-yes="delete-form-{{$currantLang}}" title="{{__('Delete')}}" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti ti-trash text-white"></i></a>
                    {!! Form::close() !!}
                </div>
            @endif

    </div>
</div>

@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Manage Language') }}</li>
@endsection
@section('content')
<div class="row g-0 p-0">
    <div class="col-sm-12">
        <div class="row g-0">
            <div class="col-xl-3 border-end border-bottom">
                <div class="card shadow-none bg-transparent sticky-top  ">
                    <div class="list-group list-group-flush rounded-0">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            @foreach ($languages as $code => $lang)
                                <a href="{{ route('manage.language', [$code]) }}"
                                    class="list-group-item list-group-item-action @if ($currantLang == $code) active @endif">
                                    <i class="d-lg-none d-block mr-1"></i>
                                    <span class=" d-lg-block">{{ Str::upper($lang) }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="p-3 card rounded-0 shadow-none border-bottom border-start">
                    <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-user-tab-1" data-bs-toggle="pill" data-bs-target="#labels"
                                type="button">{{ __('Labels') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-user-tab-2" data-bs-toggle="pill" data-bs-target="#messages"
                                type="button">{{ __('Messages') }}</button>
                        </li>

                    </ul>
                </div>
                <div class="card rounded-0 shadow-none border-start border-bottom">
                    <div class="card-body p-3">
                        <form method="post" action="{{ route('store.language.data', [$currantLang]) }}">
                            @csrf
                            <div class="tab-content">
                                <div class="tab-pane active" id="labels">
                                    <div class="row">
                                        @foreach ($arrLabel as $label => $value)
                                            <div class="col-lg-6">
                                                <div class="form-group mb-3">
                                                    <label class="form-label text-dark">{{ $label }}</label>
                                                    <input type="text" class="form-control"
                                                        name="label[{{ $label }}]" value="{{ $value }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="tab-pane" id="messages">
                                    @foreach ($arrMessage as $fileName => $fileValue)
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h6>{{ ucfirst($fileName) }}</h6>
                                            </div>
                                            @foreach ($fileValue as $label => $value)
                                                @if (is_array($value))
                                                    @foreach ($value as $label2 => $value2)
                                                        @if (is_array($value2))
                                                            @foreach ($value2 as $label3 => $value3)
                                                                @if (is_array($value3))
                                                                    @foreach ($value3 as $label4 => $value4)
                                                                        @if (is_array($value4))
                                                                            @foreach ($value4 as $label5 => $value5)
                                                                                <div class="col-lg-6">
                                                                                    <div class="form-group mb-3">
                                                                                        <label
                                                                                            class="form-label text-dark">{{ $fileName }}.{{ $label }}.{{ $label2 }}.{{ $label3 }}.{{ $label4 }}.{{ $label5 }}</label>
                                                                                        <input type="text"
                                                                                            class="form-control"
                                                                                            name="message[{{ $fileName }}][{{ $label }}][{{ $label2 }}][{{ $label3 }}][{{ $label4 }}][{{ $label5 }}]"
                                                                                            value="{{ $value5 }}">
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        @else
                                                                            <div class="col-lg-6">
                                                                                <div class="form-group mb-3">
                                                                                    <label
                                                                                        class="form-label text-dark">{{ $fileName }}.{{ $label }}.{{ $label2 }}.{{ $label3 }}.{{ $label4 }}</label>
                                                                                    <input type="text" class="form-control"
                                                                                        name="message[{{ $fileName }}][{{ $label }}][{{ $label2 }}][{{ $label3 }}][{{ $label4 }}]"
                                                                                        value="{{ $value4 }}">
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    <div class="col-lg-6">
                                                                        <div class="form-group mb-3">
                                                                            <label
                                                                                class="form-label text-dark">{{ $fileName }}.{{ $label }}.{{ $label2 }}.{{ $label3 }}</label>
                                                                            <input type="text" class="form-control"
                                                                                name="message[{{ $fileName }}][{{ $label }}][{{ $label2 }}][{{ $label3 }}]"
                                                                                value="{{ $value3 }}">
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            <div class="col-lg-6">
                                                                <div class="form-group mb-3">
                                                                    <label
                                                                        class="form-label text-dark">{{ $fileName }}.{{ $label }}.{{ $label2 }}</label>
                                                                    <input type="text" class="form-control"
                                                                        name="message[{{ $fileName }}][{{ $label }}][{{ $label2 }}]"
                                                                        value="{{ $value2 }}">
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <div class="col-lg-6">
                                                        <div class="form-group mb-3">
                                                            <label
                                                                class="form-label text-dark">{{ $fileName }}.{{ $label }}</label>
                                                            <input type="text" class="form-control"
                                                                name="message[{{ $fileName }}][{{ $label }}]"
                                                                value="{{ $value }}">
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="text-end">
                                <input type="submit" value="{{ __('Save Changes') }}" class="btn btn-primary">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('custom-script')
<script>
    $(document).on('change','#disable_lang',function(){
       var val = $(this).prop("checked");
       if(val == true){
            var langMode = 'on';
       }
       else{
        var langMode = 'off';
       }
       $.ajax({
            type:'POST',
            url: "{{route('disablelanguage')}}",
            datType: 'json',
            data:{
                "_token": "{{ csrf_token() }}",
                "mode":langMode,
                "lang":"{{ $currantLang }}"
            },
            success : function(data){
                show_toastr('Success',data.message, 'success')
            }
       });
    });
</script>
@endpush

