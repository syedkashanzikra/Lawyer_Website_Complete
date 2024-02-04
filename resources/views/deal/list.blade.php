@extends('layouts.app')
@section('page-title', __('Deal'))
@php
    $premission = [];
    $premission_arr = [];
    if (\Auth::user()->super_admin_employee == 1) {
        $premission = json_decode(\Auth::user()->permission_json);
        $premission_arr = get_object_vars($premission);
    }
@endphp
<link rel="stylesheet" href="{{ asset('assets/css/plugins/dragula.min.css') }}">
@section('action-button')
    @if (Auth::user()->super_admin_employee == 1 ||
            array_search('manage crm', $premission_arr) ||
            Auth::user()->type == 'advocate' || Auth::user()->type == 'company')
        <div class="row align-items-center mb-3">
            <div class="col-md-12 d-flex justify-content-sm-end">
                <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
                    <a href="{{ route('deal.index') }}" class="btn btn-sm btn-primary mx-1">
                        <i class="ti ti-layout-grid" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Grid View') }}">
                        </i>
                    </a>
                </div>
                @if (Auth::user()->super_admin_employee == 1 || array_search('manage crm', $premission_arr) )
                    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
                        <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="md"
                            data-title="Add Deal" data-url="{{ route('deal.create') }}" data-toggle="tooltip"
                            title="{{ __('Create New Deal') }}" data-bs-original-title="{{ __('Create New Deal') }}"
                            data-bs-placement="top" data-bs-toggle="tooltip">
                            <i class="ti ti-plus"></i>
                        </a>
                    </div>
                @endif

            </div>
        </div>
    @endif
    @endsection{{--  --}}
@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Deal') }}</li>
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
@section('content')
    <div class="row mb-3">
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-none rounded-0 border">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <div class="d-flex align-items-center">
                                <div class="theme-avtar bg-warning">
                                    <i class="ti ti-shopping-cart"></i>
                                </div>
                                <div class="ms-3">
                                    <!-- <small class="text-muted">{{ __('Statistics') }}</small> -->
                                    <h6 class="m-0">{{ __('Total Deals') }}</h6>
                                    <h4 class="m-0">{{ $cnt_deal['total'] }}</h4>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-auto text-end">
                                </div> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-none rounded-0 border">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <div class="d-flex align-items-center">
                                <div class="theme-avtar bg-success">
                                    <i class="ti ti-users"></i>
                                </div>
                                <div class="ms-3">
                                    <!-- <small class="text-muted">{{ __('Statistics') }}</small> -->
                                    <h6 class="m-0">{{ __('This Month  Deals') }}</h6>
                                    <h4 class="m-0">{{ $cnt_deal['this_month'] }}</h4>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-auto text-end">
                                </div> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-12">
            <div class="card shadow-none rounded-0 border">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <div class="d-flex align-items-center">
                                <div class="theme-avtar bg-danger">
                                    <i class="ti ti-report-money"></i>
                                </div>
                                <div class="ms-3">
                                    <!-- <small class="text-muted">{{ __('Statistics') }}</small> -->
                                    <h6 class="m-0">{{ __('This Week  Deals') }}</h6>
                                    <h4 class="m-0">{{ $cnt_deal['this_week'] }}</h4>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-auto text-end">
                                </div> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-12">
            <div class="card shadow-none rounded-0 border">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <div class="d-flex align-items-center">
                                <div class="theme-avtar bg-info">
                                    <i class="ti ti-report-money"></i>
                                </div>
                                <div class="ms-3">
                                    <!-- <small class="text-muted">{{ __('Statistics') }}</small> -->
                                    <h6 class="m-0">{{ __('Last 30 Days  Deals') }}</h6>
                                    <h4 class="m-0">{{ $cnt_deal['last_30days'] }}</h4>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-auto text-end">
                                </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-12 mt-2">
        <div class="card">
            <div class="card-header card-body table-border-style">
                <!-- <h5></h5> -->
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Price') }}</th>
                                <th>{{ __('Stage') }}</th>
                                <th>{{ __('Tasks') }}</th>
                                <th>{{ __('Users') }}</th>
                                <th class="text-end">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($deals) > 0)
                                @foreach ($deals as $deal)
                                    <tr>
                                        <td>{{ $deal->name }}</td>
                                        <td>{{ \Auth::user()->priceFormat($deal->price) }}</td>
                                        <td>{{ !empty($deal->stage) ? $deal->stage->name : '' }}</td>
                                        <td>{{ count($deal->tasks) }}/{{ count($deal->complete_tasks) }}</td>
                                        <td>
                                            <div class="user-group">
                                                @foreach ($deal->users as $user)
                                                    <a href="#" class=""
                                                        data-bs-original-title="{{ $user->name }}"
                                                        data-bs-toggle="tooltip">
                                                        <img
                                                            @if (!empty($user->avatar)) src="{{ asset('/storage/uploads/profile/' . $user->avatar) }}" @else src="{{ asset('/storage/uploads/profile/avatar.png') }}" avatar="{{ $user->name }}" @endif>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            @if (\Auth::user()->super_admin_employee == '1')
                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="{{ route('deal.show', \Crypt::encrypt($deal->id)) }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('View deal') }}">
                                                        <i class="ti ti-eye "></i>
                                                    </a>
                                                </div>
                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="#"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                        data-url="{{ route('deal.edit', $deal->id) }}" data-size="md"
                                                        data-ajax-popup="true" data-title="{{ __('Edit Deal') }}"
                                                        title="{{ __('Edit Deal') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top">
                                                        <i class="ti ti-edit "></i></a>
                                                </div>
                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="#"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                        data-confirm-yes="delete-form-{{ $deal->id }}"
                                                        title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                </div>

                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'route' => ['deal.destroy', $deal->id],
                                                    'id' => 'delete-form-' . $deal->id,
                                                ]) !!}
                                                {!! Form::close() !!}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="font-style">
                                    <td colspan="6" class="text-center">{{ __('No data available in table') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
