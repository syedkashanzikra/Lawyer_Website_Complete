@extends('layouts.app')

@section('page-title', __('Permission'))

@section('action-button')

    @can('create permission')

        <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
            <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="md" data-title="Add Permission"
                data-url="{{ route('permissions.create') }}" data-toggle="tooltip" title="{{ __('Create New Permission') }}">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endcan

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Permission') }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-container">
                    <table class="table table-striped table-bordered table-hover" id="dataTable">
                        <thead>
                            <tr>
                                <th> {{__('Permissions')}}</th>
                                <th class="text-right" width="200px"> {{__('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $permission)
                                <tr>
                                    <td>{{ $permission->name }}</td>
                                    <td class="action">

                                        @can('edit permission')
                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="#" data-url="{{ route('permissions.edit',$permission->id) }}"
                                                    data-size="lg" data-ajax-popup="true"
                                                    data-title="{{__('Update permission')}}" class="btn btn-outline btn-sm ">
                                                    <i class="ti ti-pencil"></i>
                                                </a>
                                            </div>
                                        @endcan

                                        @can('delete permission')
                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="#"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                    data-confirm="{{__('Are You Sure?')}}"
                                                    data-confirm-yes="delete-form-{{$permission->id}}" title="{{__('Delete')}}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            </div>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['permissions.destroy', $permission->id],'id'=>'delete-form-'.$permission->id]) !!}
                                            {!! Form::close() !!}

                                        @endcan
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
