@extends('layouts.app')

@section('page-title', __('Case Type'))

@section('action-button')
<div class="text-sm-end d-flex all-button-box justify-content-sm-end">
    <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="md" data-title="Add Case Type"
        data-url="{{ route('casetypes.create') }}" data-toggle="tooltip" title="{{ __('Create New Case Type') }}">
        <i class="ti ti-plus"></i>
    </a>
</div>

@endsection

@section('breadcrumb')
<li class="breadcrumb-item">{{ __('Case Type') }}</li>
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
                                <th>{{ __('Name') }}</th>
                                <th width="100px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i =1 ; $i <=10 ; $i++)
                                <tr>
                                    <td>{{ __('Civil Appeal') }}</td>
                                    <td>
                                        <div class="action-btn bg-info ms-2">
                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center " data-url="#" data-size="lg" data-ajax-popup="true"  data-title="{{__('Update Role')}}" title="{{__('Update')}}" data-bs-toggle="tooltip" data-bs-placement="top"><span class="text-white"><i class="ti ti-edit text-white"></i></span></a>
                                        </div>
                                        <div class="action-btn bg-danger ms-2">
                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para" data-confirm="{{__('Are You Sure?')}}"
                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                            data-confirm-yes="delete-form-" title="{{__('Delete')}}" data-bs-toggle="tooltip" data-bs-placement="top">
                                                <span class="text-white"><i class="ti ti-trash"></i></span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</div>
@endsection
