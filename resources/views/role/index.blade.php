@extends('layouts.app')

@section('page-title', __('Role'))

@section('action-button')
@can('create role')
<div class="text-sm-end d-flex all-button-box justify-content-sm-end">
    <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="lg" data-title="Add Role"
        data-url="{{ route('roles.create') }}" data-toggle="tooltip" title="{{ __('Create New Role') }}">
        <i class="ti ti-plus"></i>
    </a>
</div>
@endcan
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Role') }}</li>
@endsection

@section('content')
<div class="row p-0">
    <div class="col-md-12">
        <div class="card-body table-border-style ">
            <div class="table-responsive">
                <table class="table dataTable">
                    <thead>
                        <th>{{__('Role')}} </th>
                        <th>{{__('Permissions')}} </th>
                        <th width="100px">{{__('Action')}} </th>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)

                            <tr>
                                <td class="Role">{{ ucfirst($role->name) }}</td>
                                <td class="" style="white-space: inherit">
                                    {{-- @for($j=0;$j<count($role->permissions()->pluck('name'));$j++)
                                        <span class="badge rounded p-2 m-1 px-3 bg-primary "><a href="#"
                                                class="text-white">{{$role->permissions()->pluck('name')[$j]}}</a></span>
                                    @endfor --}}
                                    @foreach ($role->permissions as $permission)
                                        <span class="badge rounded p-2 m-1 px-3 bg-primary">
                                            <a href="#" class="text-white">{{ $permission->name }}</a>
                                        </span>
                                    @endforeach
                                </td>
                                <td>

                                    @can('edit role')
                                        <div class="action-btn bg-light-secondary ms-2">
                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                data-url="{{ route('roles.edit',$role->id) }}" data-size="lg"
                                                data-ajax-popup="true" data-title="{{__('Update Role')}}"
                                                title="{{__('Edit')}}" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti ti-edit"></i></a>
                                        </div>
                                    @endcan
                                    @if ($role->name != 'advocate' && $role->name != 'client' )

                                        @can('delete role')
                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                    data-confirm="{{__('Are You Sure?')}}" data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                    data-confirm-yes="delete-form-{{$role->id}}" title="{{__('Delete')}}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            </div>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy',
                                            $role->id],'id'=>'delete-form-'.$role->id]) !!}
                                            {!! Form::close() !!}
                                        @endcan
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
@endsection

