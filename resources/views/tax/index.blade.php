@extends('layouts.app')

@section('page-title', __('Tax'))

@section('action-button')
    @can('create tax')
        <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
            <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="md" data-title="Add Tax"
                data-url="{{ route('taxs.create') }}" data-toggle="tooltip" title="{{ __('Create New Tax') }}" data-bs-original-title="{{__('Create New Tax')}}" data-bs-placement="top" data-bs-toggle="tooltip">
                <i class="ti ti-plus"></i>
            </a>
        </div>

    @endsection
@endcan

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Tax') }}</li>
@endsection

@section('content')
    <div class="row p-0">
        <div class="col-md-12">
            <div class="card shadow-none">
                <div class="card-body table-border-style">

                    <div class="table-responsive">
                        <table class="table dataTable data-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Tax Name') }}</th>
                                    <th>{{ __('Rate (%)') }}</th>
                                    <th width="100px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($taxes as $key => $tax)

                                    <tr>

                                        <td><a href="#" class="btn btn-sm " data-url="{{ route('taxs.edit', $tax->id) }}" data-size="md" data-ajax-popup="true"
                                            data-title="{{ __('Edit Tax') }}">
                                            {{ $tax->name }}
                                        </a></td>
                                        <td>{{ $tax->rate }}</td>
                                        <td>
                                            @can('edit tax')
                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                        data-url="{{ route('taxs.edit', $tax->id) }}" data-size="md"
                                                        data-ajax-popup="true" data-title="{{ __('Edit Tax') }}"
                                                        title="{{ __('Edit Tax') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"><i
                                                                class="ti ti-edit "></i></a>
                                                </div>
                                            @endcan
                                            @can('delete tax')
                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="#"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                        data-confirm-yes="delete-form-{{ $tax->id }}"
                                                        title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                </div>
                                            @endcan
                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'route' => ['taxs.destroy', $tax->id],
                                                'id' => 'delete-form-' . $tax->id,
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
