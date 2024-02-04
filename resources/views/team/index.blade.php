@extends('layouts.app')

@section('page-title', __('Team Members'))

@section('action-button')
    @can('create team')
        <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
            <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="md" data-title="Add Team Member"
                data-url="{{ route('teams.create') }}" data-toggle="tooltip" title="{{ __('Create New Team Member') }}">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endcan

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Team Members') }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                <h5></h5>
                <div class="table-responsive">
                    <table class="table dataTable data-table">
                        <thead>
                            <tr>
                                <th>{{ __('First Name') }}</th>
                                <th>{{ __('Last Name') }}</th>
                                <th>{{ __('Designation') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Contact') }}</th>
                                <th width="100px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($teams as $key => $team)
                                <tr>
                                    <td>{{ $team->first_name }}</td>
                                    <td>{{ $team->last_name }}</td>
                                    <td>{{ $team->designation }}</td>
                                    <td>{{ $team->email }}</td>
                                    <td>{{ $team->mobile_number }}</td>
                                    <td>
                                        <div class="action-btn bg-success ms-2">
                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center " data-url="#" data-size="lg" data-ajax-popup="true"  data-title="{{__('Update Role')}}" title="{{__('View case')}}" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white"><i class="ti ti-eye text-white"></i></span></a>
                                        </div>
                                        <div class="action-btn bg-info ms-2">
                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center " data-url="#" data-size="lg" data-ajax-popup="true"  data-title="{{__('Update Role')}}" title="{{__('Update')}}" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white"><i class="ti ti-edit text-white"></i></span></a>
                                        </div>
                                        <div class="action-btn bg-danger ms-2">
                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}" data-confirm="{{__('Are You Sure?')}}" data-confirm-yes="delete-form-" title="{{__('Delete')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                                                <span class="text-white"><i class="ti ti-trash"></i></span>
                                            </a>
                                        </div>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['cases'],'id'=>'delete-form-']) !!}
                                        {!! Form::close() !!}
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



