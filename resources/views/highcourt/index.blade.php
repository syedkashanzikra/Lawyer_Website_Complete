@extends('layouts.app')

@section('page-title', __('High Court'))

@section('action-button')
    @can('create highcourt')
        <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
            <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="md" data-title="Add High Court"
                data-url="{{ route('highcourts.create') }}" data-toggle="tooltip" title="{{ __('Create New High Court') }}" data-bs-original-title="{{__('Create New High Court')}}" data-bs-placement="top" data-bs-toggle="tooltip">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endcan
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('High Court') }}</li>
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
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th width="100px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($hicrts as $court)
                                    <tr>
                                        <td><a href="#" class="btn btn-sm" data-url="{{ route('highcourts.edit',$court->id) }}" data-size="md"
                                            data-ajax-popup="true" data-title="{{ __('Update High Court') }}">
                                            {{ $court->name }}
                                        </a></td>
                                        <td>{{ $court->getCourtType($court->court_id) }}</td>
                                        <td>
                                            @can('edit highcourt')
                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                        data-url="{{ route('highcourts.edit',$court->id) }}" data-size="md" data-ajax-popup="true"
                                                        data-title="{{ __('Update High Court') }}" title="{{ __('Edit') }}"
                                                        data-bs-toggle="tooltip" data-bs-placement="top">
                                                        <i class="ti ti-edit "></i></a>
                                                </div>
                                            @endcan

                                            @can('delete highcourt')
                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="#"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                        data-confirm="{{ __('Are You Sure?') }}" data-confirm-yes="delete-form-{{ $court->id }}"
                                                        title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                </div>
                                            @endcan

                                            {!! Form::open(['method' => 'DELETE', 'route' => ['highcourts.destroy',$court->id], 'id' => 'delete-form-'.$court->id]) !!}
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

