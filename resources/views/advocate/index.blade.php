@extends('layouts.app')

@section('page-title', __('Advocate'))

@section('action-button')
    @can('create advocate')
        <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
            <a href="{{ route('advocates.export') }}" class="btn btn-sm btn-primary mx-1" data-bs-toggle="tooltip"
                data-title=" {{ __('Export') }}" title="{{ __('Export') }}">
                <i class="ti ti-file-export"></i>
            </a>
            <a href="{{ route('advocate.create') }}" class="btn btn-sm btn-primary mx-1" data-toggle="tooltip"
                title="{{ __('Add New Advocate') }}" data-bs-toggle="tooltip" data-bs-placement="top">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endcan

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Advocate') }}</li>
@endsection

@section('content')
    <div class="row p-0">
        <div class="col-xl-12">

            <div class="card-header card-body table-border-style">
                <h5></h5>
                <div class="table-responsive">
                    <table class="table dataTable data-table">
                        <thead>
                            <tr>
                                <th>{{ __('#') }}</th>
                                <th>{{ __('Advocate Name') }}</th>
                                <th>{{ __('Company Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Contact') }}</th>
                                <th width="100px" class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($advocates as $key => $advocate)

                                <tr>
                                    <td>
                                        {{ $key + 1 }}
                                    </td>
                                    <td>{{ $advocate->getAdvUser->name }}</td>
                                    <td>{{ $advocate->company_name }}</td>
                                    <td>{{ $advocate->getAdvUser->email }}</td>
                                    <td>{{ $advocate->phone_number }}</td>
                                    <td>
                                        @if ($advocate->getAdvUser->is_disable == 0 )

                                            <i class="ti ti-lock"></i>
                                        @else
                                            @can('view advocate')
                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                        data-url="{{ route('advocate.show', $advocate->id) }}" data-size="xl"
                                                        data-ajax-popup="true"
                                                        data-title="{{ $advocate->getAdvUser->name }}{{ __("'s Cases") }}"
                                                        title="{{ __('View Case') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top">
                                                        <i class="ti ti-clipboard-list "></i>
                                                    </a>
                                                </div>
                                            @endcan
                                            @can('edit advocate')
                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="{{ route('advocate.edit', $advocate->id) }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                        title="{{ __('Edit') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"><i class="ti ti-edit "></i></a>
                                                </div>
                                            @endcan

                                            @if (Auth::user()->type == 'company')
                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="#"
                                                        data-url="{{ route('company.reset', \Crypt::encrypt($advocate->user_id)) }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                        data-tooltip="Edit" data-ajax-popup="true"
                                                        data-title="{{ __('Reset Password') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="{{ __('Reset Password') }}">

                                                        <i class="ti ti-key "></i>

                                                    </a>
                                                </div>
                                            @endif
                                            @can('view advocate')
                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                        data-url="{{ route('advocate.view', $advocate->id) }}" data-size="lg"
                                                        data-ajax-popup="true"
                                                        data-title="{{ $advocate->getAdvUser->name }}"
                                                        title="{{ __('View') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top">
                                                        <i class=" ti ti-eye "></i>
                                                    </a>
                                                </div>
                                            @endcan


                                            @can('delete advocate')
                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="#"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                        data-confirm-yes="delete-form-{{ $advocate->id }}"
                                                        title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                </div>
                                            @endcan

                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'route' => ['advocate.destroy', $advocate->id],
                                                'id' => 'delete-form-' . $advocate->id,
                                            ]) !!}
                                            {!! Form::close() !!}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
