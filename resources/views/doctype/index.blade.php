@extends('layouts.app')

@section('page-title', __('Document Type'))

@section('action-button')
    @can('create doctype')
        <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
            <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="md"
                data-title="Add Document Type" data-url="{{ route('doctype.create') }}" data-toggle="tooltip"
                title="{{ __('Create New Document Type') }}" data-bs-original-title="{{__('Create New Document Type')}}" data-bs-placement="top" data-bs-toggle="tooltip">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endcan

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Document Type') }}</li>
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
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th width="100px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($types as $type)
                                    <tr>
                                        <td><a href="{{ route('doctsubype.index', ['doctyp_id'=>$type->id]) }}" class="btn btn-sm" >
                                            {{ $type->name }}
                                            </a>
                                        </td>
                                        <td> {{ $type->description }} </td>
                                        <td>
                                            @can('edit doctype')
                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                        data-url="{{ route('doctype.edit', $type->id) }}" data-size="md"
                                                        data-ajax-popup="true" data-title="{{ __('Update Document Type') }}"
                                                        title="{{ __('Edit') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top">
                                                        <i class="ti ti-edit "></i></a>
                                                </div>
                                            @endcan
                                            @can('delete doctype')
                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="#"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                        data-confirm-yes="delete-form-{{ $type->id }}"
                                                        title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                </div>
                                            @endcan
                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'route' => ['doctype.destroy', $type->id],
                                                'id' => 'delete-form-' . $type->id,
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
