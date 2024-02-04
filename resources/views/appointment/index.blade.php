@extends('layouts.app')

@section('page-title', __('Appointments'))

@section('action-button')
    @can('create appointment')
        <div class="text-sm-end d-flex all-button-box justify-content-sm-end">

            <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="lg" data-title="{{ __('Add Appointment') }}"
                data-url="{{ route('appointments.create') }}" data-toggle="tooltip" title="{{ __('Create New Appointment') }}" data-bs-original-title="{{__('Create New Appointment')}}" data-bs-placement="top" data-bs-toggle="tooltip" >
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endcan

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Appointments') }}</li>
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
                            </tbody>
                        </table>
                    </div>
                </div>

        </div>
    </div>
    @endsection

