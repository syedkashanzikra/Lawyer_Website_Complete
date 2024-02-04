@extends('layouts.app')

@section('page-title', __('Hearing Type'))

@section('action-button')
    @can('create group')
        <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
            <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="md" data-title="Add Hearing Type"
                data-url="{{ route('hearingType.create') }}" data-toggle="tooltip" title="{{ __('Add Hearing Type') }}">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endcan

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Hearing Type') }}</li>
@endsection

@section('content')
    <div class="row p-0">
        <div class="col-xl-12">
            <div class="card shadow-none">
                <div class="card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table dataTable data-table">
                            <thead>
                                <tr>

                                    <th>{{ __('Hearing Type') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th width="100px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($HearingType as $court)
                                    <tr>
                                        <td>
                                            <a href="#" class="btn btn-sm d-inline-flex align-items-center "
                                                    data-url="{{ route('hearingType.edit', $court->id) }}" data-size="md"
                                                    data-ajax-popup="true" data-title="{{ __('Update Hearing Type') }}">
                                                {{ ucfirst($court->type) }}
                                            </a>
                                        </td>
                                        <td> {{ $court->description }} </td>
                                        <td>

                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                    data-url="{{ route('hearingType.edit', $court->id) }}" data-size="md"
                                                    data-ajax-popup="true" data-title="{{ __('Update Hearing Type') }}"
                                                    title="{{ __('Edit') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top">
                                                    <i class="ti ti-edit "></i></a>
                                            </div>

                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="#"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                    data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                    data-confirm-yes="delete-form-{{ $court->id }}"
                                                    title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            </div>

                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'route' => ['hearingType.destroy', $court->id],
                                                'id' => 'delete-form-' . $court->id,
                                            ]) !!}
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
    </div>
@endsection
