@extends('layouts.app')

@section('page-title', __('Lead'))
<link rel="stylesheet" href="{{ asset('assets/css/plugins/dragula.min.css') }}">
@section('action-button')
@if(\Auth::user()->super_admin_employee == 1)
    <div class="row align-items-center mb-3">
        <div class="col-md-12 d-flex justify-content-sm-end">
            @if (!empty($deal))
                <div class="col-md-12 d-flex justify-content-sm-end">
                    <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
                        <a href="@if ($deal->is_active) {{ route('deal.show', \Crypt::encrypt($deal->id)) }} @else # @endif"
                            data-bs-toggle="tooltip" data-bs-original-title="{{ __('Already Convert To Deal') }}"
                            class="btn btn-sm btn-primary mx-1">
                            <i class="ti ti-eye text-white"></i>
                        </a>
                    </div>
                </div>
            @else
                <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
                    <a href="#" class="btn btn-sm btn-warning mx-1" data-ajax-popup="true" data-size="md"
                        data-title="{{ __('Convert [' . $lead->subject . '] To Deal') }}"
                        data-url="{{ route('lead.convert.deal', $lead->id) }}" data-toggle="tooltip"
                        title="{{ __('Convert [' . $lead->subject . '] To Deal') }}"
                        data-bs-original-title="{{ __('Convert To Deal') }}" data-bs-placement="bottom"
                        data-bs-toggle="tooltip">
                        <i class="ti ti-exchange text-white"></i>
                    </a>
                </div>
            @endif
            <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
                <a href="#" class="btn btn-sm btn-dark mx-1" data-ajax-popup="true" data-size="md"
                data-title="{{ __('Add Label') }}"
                data-url="{{ route('lead.label', $lead->id) }}" data-toggle="tooltip"
                title="{{ __('Add Label') }}"
                data-bs-original-title="{{ __('Label') }}" data-bs-placement="bottom"
                data-bs-toggle="tooltip">
                <i class="ti ti-tag text-white"></i>
                </a>
            </div>
            <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
                <a href="#" class="btn btn-sm btn-info mx-1" data-ajax-popup="true" data-size="md"
                data-bs-whatever="{{ __('Edit Lead') }}"
                data-title="{{ __('Edit Lead') }}"
                    data-url="{{ route('lead.edit', $lead->id) }}" data-toggle="tooltip"
                    title="{{ __('Edit Lead') }}"
                    data-bs-original-title="{{ __('Edit') }}" data-bs-placement="bottom"
                    data-bs-toggle="tooltip">
                    <i class="ti ti-edit text-white"></i>
                </a>
            </div>
            <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
                <a href="#"
                    class="btn btn-sm btn-danger mx-1 bs-pass-para"
                    data-confirm="{{ __('Are You Sure?') }}"
                    data-confirm-yes="delete-form-{{ $lead->id }}-{{ $lead->id }}"
                    title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                    data-bs-placement="bottom">
                    <i class="ti ti-trash"></i>
                </a>
            </div>
            {!! Form::open([
                'method' => 'DELETE',
                'route' => ['lead.destroy', $lead->id, $lead->id],
                'id' => 'delete-form-' . $lead->id . '-' . $lead->id,
            ]) !!}
            {!! Form::close() !!}
        </div>
    </div>
@endif
@endsection
@push('custom-script')
    <style>
        .btn-sm {
            --bs-btn-padding-y: 0.45rem;
            --bs-btn-padding-x: 0.5rem;
            --bs-btn-font-size: 0.76563rem;
            --bs-btn-border-radius: 4px;

        }
    </style>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Lead') }}</li>
@endsection
<link rel="stylesheet" href="{{ asset('assets/css/plugins/dropzone.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/summernote/summernote-bs4.css') }}">
@section('content')
    @php
        $lead_files = \App\Models\Utility::get_file('uploads/lead_files/');

        $sources = $lead->sources();
        $calls = $lead->calls;
        $emails = $lead->emails;
        $files = $lead->files;

    @endphp
    <!-- [ sample-page ] start -->
    <div class="col-sm-12">
        <div class="row g-0">
            <div class="col-xl-3 border-end border-bottom">
                <div class="card shadow-none bg-transparent sticky-top" style="top:70px">
                    <div class="list-group list-group-flush rounded-0" id="useradd-sidenav">
                        <a href="#useradd-1" class="list-group-item list-group-item-action ">{{ __('General') }} <div
                                class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                        <a href="#useradd-2" class="list-group-item list-group-item-action">{{ __('Sources') }} <div
                                class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                        <a href="#useradd-3" class="list-group-item list-group-item-action">{{ __('Files') }} <div
                                class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                        <a href="#useradd-4" class="list-group-item list-group-item-action">{{ __('Discussion') }} <div
                                class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                        <a href="#useradd-5" class="list-group-item list-group-item-action">{{ __('Notes') }} <div
                                class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                        <a href="#useradd-6" class="list-group-item list-group-item-action">{{ __('Calls') }} <div
                                class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                        <a href="#useradd-7" class="list-group-item list-group-item-action">{{ __('Emails') }} <div
                                class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                    </div>
                </div>
            </div>


            <div class="col-xl-9">
                <div id="useradd-1" class="card  shadow-none rounded-0 border-bottom">
                    <div class="row g-0">
                        <div class="col-xl-5">
                            <div class="row g-0">
                                <div class="col border-end border-bottom">
                                    <div class="p-3">
                                        <div class="d-flex justify-content-between mb-3">
                                            <div>
                                                <div class="theme-avtar bg-info">
                                                    <i class="ti ti-click"></i>
                                                </div>

                                                <h6 class="mb-3 mt-2">{{ __('Source') }}</h6>
                                            </div>
                                            <h3 class="mb-0">{{ count($sources) }} </h3>

                                        </div>
                                    </div>
                                </div>
                                <div class="col border-end border-bottom">
                                    <div class="p-3">
                                        <div class="d-flex justify-content-between mb-3">
                                            <div>
                                                <div class="theme-avtar bg-warning">
                                                    <i class="ti ti-file"></i>
                                                </div>
                                            </div>
                                            <h6 class="mb-3 mt-2">{{ __('Files') }}</h6>
                                            <h3 class="mb-0">{{ count($lead->files) }} </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>




                            <div class="card  shadow-none bg-transparent border rounded-0">
                                <div class="card-header">
                                    <div class="row g-0">
                                        <div class="col-md-10">
                                            <h5 class="mb-0">{{ __('Users') }}</h5>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="float-end">

                                                @if (\Auth::user()->super_admin_employee == 1)
                                                <a href="#" class="btn btn-sm btn-primary mx-1"
                                                    data-ajax-popup="true" data-size="md"
                                                    data-title="{{ __('Add New User') }}"
                                                    data-url="{{ route('lead.users.edit', $lead->id) }}"
                                                    data-toggle="tooltip" title="{{ __('Add New User') }}"
                                                    data-bs-original-title="{{ __('Add New User') }}"
                                                    data-bs-placement="top" data-bs-toggle="tooltip">
                                                    <i class="ti ti-plus"></i>
                                                </a>
                                                @endif

                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        @foreach ($lead->users as $user)
                                            <li class="list-group-item px-0">
                                                <div class="row align-items-center justify-content-between">
                                                    <div class="col-sm-auto mb-3 mb-sm-0">
                                                        <div class="d-flex align-items-center ">
                                                            <img @if (!empty($user->avatar)) src="{{ asset('storage/uploads/avatar/' . $user->avatar) }}" @else avatar="{{ $user->name }}" @endif
                                                                class="avatar  rounded-circle avatar-sm">
                                                            <div class="div">
                                                                <h6 class="m-0 ms-3"> {{ $user->name }} </h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if(Auth::user()->type!="company")
                                                    <div class="col-sm-auto text-sm-end d-flex align-items-center">
                                                        <div class="action-btn bg-light-secondary ms-2">
                                                            <a href="#"
                                                                class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                                data-confirm="{{ __('Are You Sure?') }}"
                                                                data-confirm-yes="delete-form-{{ $lead->id }}-{{ $user->id }}"
                                                                title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                                data-bs-placement="top">
                                                                <i class="ti ti-trash"></i>
                                                            </a>
                                                        </div>
                                                        {!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['lead.users.destroy', $lead->id, $user->id],
                                                            'id' => 'delete-form-' . $lead->id . '-' . $user->id,
                                                        ]) !!}
                                                        {!! Form::close() !!}
                                                    </div>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <div class="card  shadow-none bg-transparent border rounded-0">
                                <div class="card-header">
                                    <h5>{{ __('Attachments') }}</h5>
                                </div>
                                <div class="card-body Attachments">
                                    <ul class="list-group list-group-flush mt-2 w-100">
                                        @foreach ($files as $file)
                                            <li class="list-group-item bg-transparent border rounded-0 mb-3">
                                                <div class="row align-items-center justify-content-between">
                                                    <div class="col-sm-1 mb-3 mb-sm-0">
                                                        <div class="d-flex align-items-center">
                                                            <img src="../assets/images/pages/pdf.svg" class="wid-30 me-3"
                                                                alt="images">
                                                            <div class="div">
                                                                <h5 class="m-0">{{ $file->file_name }}</h5>
                                                                <small
                                                                    class="text-muted">{{ number_format(\File::size(storage_path('uploads/lead_files/' . $file->file_path)) / 1048576, 2) . ' ' . __('MB') }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-auto text-sm-end d-flex align-items-center">
                                                        <a class="btn btn-sm btn-primary d-flex align-items-center"
                                                            href="{{ asset(Storage::url('uploads/lead_files')) . '/' . $file->file_path }}"
                                                            download="">
                                                            <i
                                                                class="ti ti-arrow-bar-to-down me-2"></i>{{ __('Download') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-7">
                            <div class="card  shadow-none bg-transparent border rounded-0">
                                <div class="card-header">
                                    <h5>{{ __('Lead Detail') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row  mt-2">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                {{ $lead->subject }}
                                            </div>
                                            <div class="col">
                                                <div class="progress-wrapper">
                                                    <span class="progress-percentage"><small
                                                            class="font-weight-bold">{{ __('Completed') }}:
                                                        </small>{{ $precentage }}%</span>
                                                    <div class="progress progress-xs mt-2">
                                                        <div class="progress-bar bg-primary" role="progressbar"
                                                            aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"
                                                            style="width:{{ $precentage }}%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 mt-2">
                                            <div class="d-flex align-items-start">
                                                <div class="theme-avtar bg-primary mt-2">
                                                    <i class="ti ti-calendar-stats "></i>
                                                </div>
                                                <div class="ms-2 mt-2">
                                                    <p class="text-muted text-sm mb-0">{{ __('Created') }}</p>
                                                    <h6 class="mb-0 text-primary">
                                                        {{ \Auth::user()->dateFormat($lead->created_at) }}</h6>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 my-3 my-sm-0 mt-4">
                                            <div class="d-flex align-items-start">
                                                <div class="theme-avtar bg-info mt-3">
                                                    <i class="ti ti-thumb-up"></i>
                                                </div>
                                                <div class="ms-2 mt-3">
                                                    <p class="text-muted text-sm mb-0">{{ __('Pipeline') }}:</p>
                                                    <h6 class="mb-0 text-info">
                                                        {{ !empty($lead->pipeline) ? $lead->pipeline->name : '' }}</h6>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 my-3 my-sm-0 mt-4">
                                            <div class="d-flex align-items-start">
                                                <div class="theme-avtar bg-warning mt-3">
                                                    <i class="ti ti-heart"></i>
                                                </div>
                                                <div class="ms-2 mt-3">
                                                    <p class="text-muted text-sm mb-0">{{ __('Stage') }}:</p>
                                                    <h6 class="mb-0 text-warning">
                                                        {{ !empty($lead->stage) ? $lead->stage->name : '' }}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="activity" class="card  shadow-none bg-transparent border rounded-0">
                                <div class="card-header">
                                    <h5>{{ __('Activity') }}</h5>
                                </div>
                                <div class="card-body height-450">

                                    <div class="row" style="height:450px !important;overflow-y: scroll;">
                                        <ul class="event-cards list-group list-group-flush mt-3 w-100">
                                            @if (!$lead->activities->isEmpty())
                                                @foreach ($lead->activities as $k => $activity)
                                                    @if ($activity->log_type == 'Move')
                                                        <li class="list-group-item bg-transparent border rounded-0 mb-3">
                                                            <div class="row align-items-center justify-content-between">
                                                                <div class="col-auto mb-3 mb-sm-0">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="theme-avtar bg-primary">
                                                                            <i class="ti ti-activity text-white"
                                                                                class="fas {{ $k + 1 }}"></i>
                                                                        </div>
                                                                        <div class="ms-3">
                                                                            <span
                                                                                class="text-dark text-sm">{{ __($activity->log_type) }}</span>
                                                                            <h6 class="m-0">
                                                                                {!! $activity->getLeadRemark() !!}</h6>
                                                                            <small
                                                                                class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-auto">

                                                                </div>
                                                            </div>
                                                        </li>
                                                    @elseif($activity->log_type == 'Add Product')
                                                        <li class="list-group-item bg-transparent border rounded-0 mb-3">
                                                            <div class="row align-items-center justify-content-between">
                                                                <div class="col-auto mb-3 mb-sm-0">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="theme-avtar bg-primary">
                                                                            <i class="ti ti-activity text-white"
                                                                                class="fas {{ $k + 1 }}"></i>
                                                                        </div>
                                                                        <div class="ms-3">
                                                                            <span
                                                                                class="text-dark text-sm">{{ __($activity->log_type) }}</span>
                                                                            <h6 class="m-0">
                                                                                {!! $activity->getLeadRemark() !!}</h6>
                                                                            <small
                                                                                class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-auto">

                                                                </div>
                                                            </div>
                                                        </li>
                                                    @elseif($activity->log_type == 'Create Invoice')
                                                        <li class="list-group-item bg-transparent border rounded-0 mb-3">
                                                            <div class="row align-items-center justify-content-between">
                                                                <div class="col-auto mb-3 mb-sm-0">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="theme-avtar bg-primary">
                                                                            <i class="ti ti-activity text-white"
                                                                                class="fas {{ $k + 1 }}"></i>
                                                                        </div>
                                                                        <div class="ms-3">
                                                                            <span
                                                                                class="text-dark text-sm">{{ __($activity->log_type) }}</span>
                                                                            <h6 class="m-0">
                                                                                {!! $activity->getLeadRemark() !!}</h6>
                                                                            <small
                                                                                class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-auto">

                                                                </div>
                                                            </div>
                                                        </li>
                                                    @elseif($activity->log_type == 'Add Contact')
                                                        <li class="list-group-item bg-transparent border rounded-0 mb-3">
                                                            <div class="row align-items-center justify-content-between">
                                                                <div class="col-auto mb-3 mb-sm-0">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="theme-avtar bg-primary">
                                                                            <i class="ti ti-activity text-white"
                                                                                class="fas {{ $k + 1 }}"></i>
                                                                        </div>
                                                                        <div class="ms-3">
                                                                            <span
                                                                                class="text-dark text-sm">{{ __($activity->log_type) }}</span>
                                                                            <h6 class="m-0">
                                                                                {!! $activity->getLeadRemark() !!}</h6>
                                                                            <small
                                                                                class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-auto">

                                                                </div>
                                                            </div>
                                                        </li>
                                                    @elseif($activity->log_type == 'Create Task')
                                                        <li class="list-group-item bg-transparent border rounded-0 mb-3">
                                                            <div class="row align-items-center justify-content-between">
                                                                <div class="col-auto mb-3 mb-sm-0">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="theme-avtar bg-primary">
                                                                            <i class="ti ti-activity text-white"
                                                                                class="fas {{ $k + 1 }}"></i>
                                                                        </div>
                                                                        <div class="ms-3">
                                                                            <span
                                                                                class="text-dark text-sm">{{ __($activity->log_type) }}</span>
                                                                            <h6 class="m-0">
                                                                                {!! $activity->getLeadRemark() !!}</h6>
                                                                            <small
                                                                                class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-auto">

                                                                </div>
                                                            </div>
                                                        </li>
                                                    @elseif($activity->log_type == 'Upload File')
                                                        <li class="list-group-item bg-transparent border rounded-0 mb-3">
                                                            <div class="row align-items-center justify-content-between">
                                                                <div class="col-auto mb-3 mb-sm-0">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="theme-avtar bg-primary">
                                                                            <i class="ti ti-activity text-white"
                                                                                class="fas {{ $k + 1 }}"></i>
                                                                        </div>
                                                                        <div class="ms-3">
                                                                            <span
                                                                                class="text-dark text-sm">{{ __($activity->log_type) }}</span>
                                                                            <h6 class="m-0">
                                                                                {!! $activity->getLeadRemark() !!}</h6>
                                                                            <small
                                                                                class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-auto">

                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            @else
                                                {{ __('No activity found yet.') }}
                                            @endif
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="useradd-2" class="card  shadow-none rounded-0 border-bottom">
                    <div class="card">
                        <div class="card-header">
                            <div class="row g-0">
                                <div class="col-md-10">
                                    <h5 class="mb-0">{{ __('Sources') }}</h5>
                                </div>
                                <div class="col-md-2">
                                    <div class="float-end">
                                        @if (\Auth::user()->super_admin_employee == 1)
                                        <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true"
                                            data-size="md" data-title="{{ __('Create New Source') }}"
                                            data-url="{{ route('lead.sources.edit', $lead->id) }}" data-toggle="tooltip"
                                            title="{{ __('Create New Source') }}"
                                            data-bs-original-title="{{ __('Create New Source') }}"
                                            data-bs-placement="top" data-bs-toggle="tooltip">
                                            <i class="ti ti-plus"></i>
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @if ($sources)
                                    @foreach ($sources as $source)
                                        <li class="list-group-item px-0">
                                            <div class="row align-items-center justify-content-between">
                                                <div class="col-sm-auto mb-3 mb-sm-0">
                                                    <div class="d-flex align-items-center">
                                                        <div class="div">
                                                            <h6 class="m-0">{{ $source->name }}</h6>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-auto text-sm-end d-flex align-items-center">
                                                    <div class="action-btn bg-light-secondary ms-2">
                                                        <a href="#"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-confirm-yes="delete-form-{{ $lead->id }}-{{ $source->id }}"
                                                            title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                            data-bs-placement="top">
                                                            <i class="ti ti-trash"></i>
                                                        </a>
                                                    </div>
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['lead.sources.destroy', $lead->id, $source->id],
                                                        'id' => 'delete-form-' . $lead->id . '-' . $source->id,
                                                    ]) !!}
                                                    {!! Form::close() !!}

                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <div id="useradd-3" class="card  shadow-none rounded-0 border-bottom">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Lead attachments') }}</h5>
                            <small> {{ __('Drag and drop lead files') }} </small>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="dropzone dropzone-multiple" data-toggle="dropzone"
                                    data-dropzone-url="http://" data-dropzone-multiple>
                                    <div class="fallback">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="dropzone-1" multiple>
                                            <label class="custom-file-label"
                                                for="customFileUpload">{{ __('Choose file') }}</label>
                                        </div>
                                    </div>
                                    <ul class="dz-preview dz-preview-multiple list-group list-group-lg list-group-flush">
                                        <li class="list-group-item px-0">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <img class="dropzone" src="" alt="Image placeholder"
                                                        data-dz-thumbnail style="width:150px;">

                                                </div>
                                                <div class="col">
                                                    {{-- <h6 class="text-sm mb-1" data-dz-name></h6> --}}
                                                    <p class="small text-muted mb-0" data-dz-size></p>
                                                </div>
                                                <div class="col-auto">

                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                                <div class="scrollbar-inner">
                                    <div class="card-wrapper pt-3 lead-common-box">

                                        @foreach ($files as $file)
                                            <div class="card mb-3 border shadow-none rounded-0">
                                                <div class="px-3 py-3">
                                                    <div class="row align-items-center">
                                                        <div class="col ml-n2">
                                                            <h6 class="text-sm mb-0">
                                                                <a href="#!">{{ $file->file_name }}</a>
                                                            </h6>
                                                            <p class="card-text small text-muted">
                                                                {{ number_format(\File::size(storage_path('uploads/lead_files/' . $file->file_path)) / 1048576, 2) . ' ' . __('MB') }}
                                                            </p>
                                                        </div>

                                                        <div class="col-auto actions">
                                                            <div class="action-btn bg-light-secondary ms-2">
                                                                <a href="{{ $lead_files . $file->file_path }}"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                                    download="" data-bs-toggle="tooltip"
                                                                    title="Download"><i class="ti ti-download"></i></a>
                                                            </div>
                                                        </div>

                                                        <div class="col-auto actions">
                                                            <div class="action-btn bg-light-secondary ms-2 mt-2 ">
                                                                <a href="#"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                                    data-confirm-yes="delete-form-{{ $lead->id }}-{{ $file->id }}"
                                                                    title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                                    data-bs-placement="top">
                                                                    <i class="ti ti-trash"></i>
                                                                </a>
                                                            </div>

                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'route' => ['lead.file.delete', $lead->id, $file->id],
                                                                'id' => 'delete-form-' . $lead->id . '-' . $file->id,
                                                            ]) !!}
                                                            {!! Form::close() !!}

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="useradd-4" class="card  shadow-none rounded-0 border-bottom">
                    <div class="card">
                        <div class="card-header">
                            <div class="row g-0">
                                <div class="col-md-10">
                                    <h5 class="mb-0">{{ __('Discussion') }}</h5>
                                </div>
                                <div class="col-md-2 text-end">
                                    <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true"
                                        data-size="md" data-title="{{ __('Create New Discussion') }}"
                                        data-url="{{ route('lead.discussions.create', $lead->id) }}"
                                        data-toggle="tooltip" title="{{ __('Create New Discussion') }}"
                                        data-bs-original-title="{{ __('Create New Discussion') }}"
                                        data-bs-placement="top" data-bs-toggle="tooltip">
                                        <i class="ti ti-plus"></i>
                                    </a>

                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @foreach ($lead->discussions as $discussion)
                                    <a href="#" class="list-group-item bg-transparent border rounded-0 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <img @if (!empty($discussion->user) && !empty($discussion->user->avatar)) src="{{ asset(Storage::url('uploads/avatar')) . '/' . $discussion->user->avatar }}" @else
                                                avatar="{{ !empty($discussion->user) ? $discussion->user->name : '' }}" @endif
                                                    class="avatar  rounded-circle avatar-sm">
                                            </div>
                                            <div class="flex-fill ml-3">
                                                <div class="h6 text-sm mb-0 ms-3">
                                                    {{ !empty($discussion->user) ? $discussion->user->name : '' }} <small
                                                        class="float-end text-muted">{{ $discussion->created_at->diffForHumans() }}</small>
                                                </div>
                                                <p class="text-sm lh-140 mb-0 ms-3">
                                                    {{ $discussion->comment }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div id="useradd-5" class="card  shadow-none rounded-0 border-bottom">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Notes') }}</h5>
                        </div>
                        <div class="card-body p-0">
                            {{ Form::open(['route' => ['lead.note.store', $lead->id]]) }}
                            <div class="col-md-12">
                                <div class="form-group mt-3">
                                    <textarea class="tox-target summernote-simple" name="notes" id="pc_demo1" rows="8">{!! $lead->notes !!}</textarea>
                                </div>
                            </div>
                            @if (\Auth::user()->super_admin_employee == 1)
                            <div class="col-md-12 text-end">
                                <div class="form-group mt-3 me-3">
                                    {{ Form::submit(__('Add'), ['class' => 'btn  btn-primary']) }}
                                </div>
                            </div>
                            @endif
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>

                <div id="useradd-6" class="card  shadow-none rounded-0 border-bottom">
                    <div class="card">
                        <div class="card-header">
                            <div class="row p-0">
                                <div class="col-md-10">
                                    <h5 class="mb-0">{{ __('Calls') }}</h5>
                                </div>
                                <div class="col-md-2 text-end">
                                    @if (\Auth::user()->super_admin_employee == 1)
                                    <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true"
                                        data-size="md" data-title="{{ __('Create New Call') }}"
                                        data-url="{{ route('lead.call.create', $lead->id) }}" data-toggle="tooltip"
                                        title="{{ __('Create New Call') }}"
                                        data-bs-original-title="{{ __('Create New Call') }}" data-bs-placement="top"
                                        data-bs-toggle="tooltip">
                                        <i class="ti ti-plus"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="table">
                            <table class="table align-items-center">
                                <thead>
                                    <tr>
                                        <th>{{ __('Subject') }}</th>
                                        <th>{{ __('Call Type') }}</th>
                                        <th>{{ __('Duration') }}</th>
                                        <th>{{ __('User') }}</th>
                                        @if(\Auth::user()->super_admin_employee == 1)
                                        <th class="text-end">{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($calls as $call)
                                        <tr>
                                            <td>{{ $call->subject }}</td>
                                            <td>{{ ucfirst($call->call_type) }}</td>
                                            <td>{{ $call->duration }}</td>
                                            <td>{{ !empty($call->getLeadCallUser) ? $call->getLeadCallUser->name : '' }}
                                            </td>
                                            @if(\Auth::user()->super_admin_employee == 1)
                                            <td class="text-end">
                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="#"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                        data-url="{{ route('lead.call.edit', [$lead->id, $call->id]) }}"
                                                        data-size="md" data-ajax-popup="true"
                                                        data-title="{{ __('Edit Call') }}" title="{{ __('Edit') }}"
                                                        data-bs-toggle="tooltip" data-bs-placement="top">
                                                        <i class="ti ti-edit "></i></a>
                                                </div>
                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="#"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                        data-confirm-yes="delete-form-{{ $lead->id }}-{{ $call->id }}"
                                                        title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                </div>

                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'route' => ['lead.call.destroy', $lead->id, $call->id],
                                                    'id' => 'delete-form-' . $lead->id . '-' . $call->id,
                                                ]) !!}
                                                {!! Form::close() !!}
                                            </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr class="text-center">
                                            <td></td>
                                            <td></td>
                                            <td class="text-center h5">{{ __('No data found') }}</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="useradd-7" class="card  shadow-none rounded-0 border-bottom">
                    <div class="card">
                        <div class="card-header">
                            <div class="row p-0">
                                <div class="col-md-10">
                                    <h5 class="mb-0">{{ __('Email') }}</h5>
                                </div>
                                <div class="col-md-2 text-end">
                                    @if (\Auth::user()->super_admin_employee == 1)
                                    <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true"
                                        data-size="md" data-title="{{ __('Create New Email') }}"
                                        data-url="{{ route('lead.email.create', $lead->id) }}" data-toggle="tooltip"
                                        title="{{ __('Create New Email') }}"
                                        data-bs-original-title="{{ __('Create New Email') }}" data-bs-placement="top"
                                        data-bs-toggle="tooltip">
                                        <i class="ti ti-plus"></i>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @foreach ($emails as $email)
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <img src="" avatar="{{ $email->to }}"
                                                    class="avatar  rounded-circle avatar-sm">
                                            </div>
                                            <div class="flex-fill ml-3">
                                                <div class="h6 text-sm mb-0 ms-3">{{ $email->to }} <small
                                                        class="float-end text-muted">
                                                        {{ $email->created_at->diffForHumans() }}</small></div>
                                                <p class="text-sm lh-140 mb-0 ms-3">
                                                    {{ $email->subject }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-script')
    <script>
        $(document).ready(function() {
            $(".list-group-item-action").first().addClass('active');

            $(".list-group-item-action").on('click', function() {
                $(".list-group-item-action").removeClass('active')
                $(this).addClass('active');
            });
        })
    </script>
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })
    </script>
    <script src="{{ asset('css/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dropzone-amd-module.min.js') }}"></script>


    <script>
        $('.summernote-simple').summernote({
            dialogsInBody: !0,
            minHeight: 200,
            toolbar: [
                ['style', ['style']],
                ["font", ["bold", "italic", "underline", "clear", "strikethrough"]],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ["para", ["ul", "ol", "paragraph"]],
            ]
        });
        var Dropzones = function() {
            var e = $('[data-toggle="dropzone"]'),
                t = $(".dz-preview");
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            e.length && (Dropzone.autoDiscover = !1, e.each(function() {
                var e, a, n, o, i;
                e = $(this), a = void 0 !== e.data("dropzone-multiple"), n = e.find(t), o = void 0, i = {
                    url: "{{ route('lead.file.upload', $lead->id) }}",
                    headers: {
                        'x-csrf-token': CSRF_TOKEN,
                    },
                    thumbnailWidth: null,
                    thumbnailHeight: null,
                    previewsContainer: n.get(0),
                    previewTemplate: n.html(),
                    maxFiles: a ? null : 1,

                    success: function(file, response) {
                        location.reload()
                        if (response.is_success) {
                            show_toastr('{{ __('Success') }}', 'Attachment Create Successfully!',
                                'success');
                            dropzoneBtn(file, response);
                        } else {
                            // Dropzones.removeFile(file);
                            show_toastr('{{ __('Error') }}',
                                'The attachment must be same as storage setting.', 'error');
                        }
                    },
                    error: function(file, response) {
                        // Dropzones.removeFile(file);
                        if (response.error) {
                            show_toastr('{{ __('Error') }}',
                                'The attachment must be same as storage setting.', 'error');
                        } else {
                            show_toastr('{{ __('Error') }}',
                                'The attachment must be same as storage setting.', 'error');
                        }
                    },
                    init: function() {
                        this.on("addedfile", function(e) {
                            !a && o && this.removeFile(o), o = e
                        })
                    }
                }, n.html(""), e.dropzone(i)
            }))
        }()
    </script>
@endpush
