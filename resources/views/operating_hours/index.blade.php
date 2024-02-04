@extends('layouts.app')

@section('page-title', __('Manage Opearating Hours'))
@php
    $logos = \App\Models\Utility::get_file('uploads/profile/');
@endphp
@section('action-button')
    <div class="float-end">
        <div class="col-auto=">
            <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="lg"
                data-title="{{ __('Create Operating Hours') }}" data-url="{{ route('operating_hours.create') }}"
                data-toggle="tooltip" title="{{ __('Create Operating Hours') }}"
                data-bs-original-title="{{ __('Create Operating Hours') }}" data-bs-placement="top"
                data-bs-toggle="tooltip">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Manage Opearating Hours') }}</li>
@endsection

@section('content')
    <div class="col-sm-12">
        <div class="row g-0">
            <div class="col-xl-3 border-end border-bottom">
                @include('layouts.setup')
            </div>
            <div class="col-xl-9" data-bs-spy="scroll" data-bs-target="#useradd-sidenav" data-bs-offset="0" tabindex="0">
                <div class="card shadow-none rounded-0 border-bottom" id="useradd-1">
                    <div class="card-body pb-0">
                        <div class="table-responsive">
                            <table id="DataTables_Table_0" class="table dataTable data-table user-datatable no-footer">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th scope="col">{{ __('Name') }}</th>

                                        <th scope="col" class="text-end me-3">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($opeatings as $index => $opeating)
                                        <tr>
                                            <th scope="row">{{ ++$index }}</th>
                                            <td>{{ $opeating->name }}</td>
                                            <td class="text-end">

                                                @if (\Auth::user()->super_admin_employee == 1)
                                                    <div class="action-btn bg-light-secondary ms-2">
                                                        <a href="#"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                            data-url="{{ route('operating_hours.show', $opeating->id) }}"
                                                            data-size="md" data-ajax-popup="true"
                                                            data-title="{{$opeating->name}}"
                                                            title="{{ __('View') }}" data-bs-toggle="tooltip"
                                                            data-bs-placement="top">
                                                            <i class="ti ti-eye "></i></a>
                                                    </div>
                                                    <div class="action-btn bg-light-secondary ms-2">
                                                        <a href="#"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                            data-url="{{ route('operating_hours.edit', $opeating->id) }}"
                                                            data-size="lg" data-ajax-popup="true"
                                                            data-title="{{ __('Edit Operating Hour') }}"
                                                            title="{{ __('Edit') }}" data-bs-toggle="tooltip"
                                                            data-bs-placement="top">
                                                            <i class="ti ti-edit "></i></a>
                                                    </div>
                                                    <div class="action-btn bg-light-secondary ms-2">
                                                        <a href="#"
                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-confirm-yes="delete-form-{{ $opeating->id }}"
                                                            title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                            data-bs-placement="top">
                                                            <i class="ti ti-trash"></i>
                                                        </a>
                                                    </div>

                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['operating_hours.destroy', $opeating->id],
                                                        'id' => 'delete-form-' . $opeating->id,
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
        </div>
    </div>
@endsection
