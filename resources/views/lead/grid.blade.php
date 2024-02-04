@extends('layouts.app')

@section('page-title', __('Lead'))
<link rel="stylesheet" href="{{ asset('assets/css/plugins/dragula.min.css') }}">
@php
    $premission = [];
    $premission_arr = [];
    if (\Auth::user()->super_admin_employee == 1) {
        $premission = json_decode(\Auth::user()->permission_json);
        $premission_arr = get_object_vars($premission);
    }
@endphp
@section('action-button')
    @if (Auth::user()->super_admin_employee == 1 || array_search('manage crm', $premission_arr) ||
    Auth::user()->type == 'company')
        <div class="row align-items-center mb-3">
            <div class="col-md-12 d-flex justify-content-sm-end">
                <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
                    <a href="{{ route('lead.index') }}" class="btn btn-sm btn-primary mx-1">
                        <i class="ti ti-layout-kanban text-white" data-bs-toggle="tooltip"
                            data-bs-original-title="{{ __('Kanban View') }}">
                        </i>
                    </a>
                </div>

                @if (Auth::user()->type != 'company')

                    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
                        <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="md"
                            data-title="Add Lead" data-url="{{ route('lead.create') }}" data-toggle="tooltip"
                            title="{{ __('Create New Lead') }}" data-bs-original-title="{{ __('Create New Lead') }}"
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
    <li class="breadcrumb-item">{{ __('Lead') }}</li>
@endsection

@section('content')
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                <div class="table-responsive">
                    <table class="table dataTable data-table" >
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Subject') }}</th>
                                <th>{{ __('Stage') }}</th>
                                <th>{{ __('Users') }}</th>
                                {{-- @if (\Auth::user()->super_admin_employee == 1) --}}
                                    <th class="text-right">{{ __('Action') }}</th>
                                {{-- @endif --}}
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($leads) > 0)
                                @foreach ($leads as $lead)
                                    <tr>
                                        <td>{{ $lead->name }}</td>
                                        <td>{{ $lead->subject }}</td>
                                        <td>{{ !empty($lead->stage) ? $lead->stage->name : '' }}</td>
                                        <td>
                                            <div class="user-group">
                                                @foreach ($lead->users as $user)
                                                    <img @if (!empty($user->avatar)) src="{{ asset('/storage/uploads/profile/' . $user->avatar) }}" @else src="{{ asset('/storage/uploads/profile/avatar.png') }}" @endif
                                                        class="" data-bs-toggle="tooltip"
                                                        title="{{ $user->name }}">
                                                @endforeach
                                            </div>
                                        </td>

                                        <td class="text-right">

                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="{{ route('lead.show', \Crypt::encrypt($lead->id)) }}"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('View Lead') }}">
                                                    <i class="ti ti-eye "></i>
                                                </a>
                                            </div>
                                            @if (\Auth::user()->super_admin_employee == 1 || Auth::user()->type == 'company')
                                                @if (Auth::user()->type != 'company')

                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="#"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                        data-url="{{ route('lead.edit', $lead->id) }}" data-size="md"
                                                        data-ajax-popup="true" data-title="{{ __('Edit') }}"
                                                        title="{{ __('Edit') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top">
                                                        <i class="ti ti-edit "></i></a>
                                                </div>
                                                @endif


                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="#"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                        data-confirm-yes="delete-form-{{ $lead->id }}"
                                                        title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                </div>

                                                {!! Form::open([
                                                    'method' => 'DELETE',
                                                    'route' => ['lead.destroy', $lead->id],
                                                    'id' => 'delete-form-' . $lead->id,
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
