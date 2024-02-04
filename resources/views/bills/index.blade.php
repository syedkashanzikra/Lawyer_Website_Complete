@extends('layouts.app')

@section('page-title', __('Bills'))

@section('action-button')
    @can('create bill')
        <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
            <a href="{{ route('bills.export') }}" class="btn btn-sm btn-primary mx-1" data-bs-toggle="tooltip"
                data-title=" {{ __('Export') }}" title="{{ __('Export') }}">
                <i class="ti ti-file-export"></i>
            </a>
            <a href="{{ route('bills.create') }}" class="btn btn-sm btn-primary mx-1" data-toggle="tooltip"
                title="{{ __('Create New Bill') }}" data-bs-original-title="{{__('Create New Bill')}}" data-bs-placement="top" data-bs-toggle="tooltip">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endcan
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Bills') }}</li>
@endsection

@section('content')
    <div class="row p-0">
        <div class="col-xl-12">

                <div class="card-header card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table dataTable-desc data-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Bill Number') }}</th>
                                    <th>{{ __('Bill From') }}</th>
                                    <th>{{ __('Date Of Reciept') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th width="100px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($bills as $bill)
                                    <tr>
                                        <td> <a href="{{ route('bills.show', $bill->id) }}" class="btn btn-sm">
                                            {{ $bill->bill_number }}
                                        </a> </td>
                                        <td> {{ $bill->bill_from }} </td>
                                        <td> {{ $bill->due_date }} </td>
                                        <td> {{ $bill->status }} </td>
                                        <td>
                                            @can('view bill')
                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="{{ route('bills.show', $bill->id) }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        title="{{ __('View') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"><i
                                                                class="ti ti-eye "></i></a>
                                                </div>
                                            @endcan

                                            @can('edit bill')
                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="{{ route('bills.edit', $bill->id) }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                        title="{{ __('Edit') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"><i
                                                                class="ti ti-edit "></i></a>
                                                </div>
                                            @endcan

                                            @can('delete bill')
                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="#"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                        data-confirm-yes="delete-form-{{ $bill->id }}"
                                                        title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                </div>
                                            @endcan


                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'route' => ['bills.destroy', $bill->id],
                                                'id' => 'delete-form-' . $bill->id,
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
    @endsection
