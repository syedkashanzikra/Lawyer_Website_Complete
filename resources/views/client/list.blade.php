@extends('layouts.app')

@section('page-title', __('Client'))

@section('action-button')
<div class="row align-items-end mb-3">
    <div class="col-md-12 d-flex justify-content-sm-end">
        <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
            <a href="{{ route('client.index') }}" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true"
                data-size="md" data-title="Add User" data-toggle="tooltip" title="{{ __('Grid View') }}" data-bs-original-title="{{__('Grid View')}}" data-bs-placement="top" data-bs-toggle="tooltip">
                <i class="ti ti-border-all"></i>
            </a>
        </div>


        @canany(['create member','create user'])
        <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
            <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="md"
                data-title="Add User" data-url="{{ route('users.create') }}" data-toggle="tooltip"
                title="{{ __('Create New User') }}">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    </div>
</div>
@endcan

@endsection

@section('breadcrumb')

<li class="breadcrumb-item">{{ __('Client') }}</li>

@endsection

@section('content')
<div class="row p-0">
    <div class="col-xl-12">
        <div class="">
            <div class="card-header card-body table-border-style">
                <h5></h5>
                <div class="table-responsive">
                    <table class="table dataTable data-table ">
                        <thead>
                            <tr>
                                <th>{{ __('#') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Designation') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Mobile Number') }}</th>
                                <th width="100px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($users as $key => $user)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->type }}</td>
                                <td>{{ $user->email }}</td>

                                <td>{{ $user_details[$key]->mobile_number ?? '-' }}</td>
                                <td>
                                    @if ($user->is_disable == 0 && Auth::user()->type != 'super admin')

                                        <i class="ti ti-lock"></i>
                                    @else
                                        @if (Auth::user()->type == 'company')

                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a data-url="{{route('users.show', $user->id)}}" href="#"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                    data-ajax-popup="true" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('View Groups') }}" data-size="md"
                                                    data-title="{{$user->name . __("'s Group")}}">

                                                                        <i class="ti ti-eye "></i>

                                                </a>
                                            </div>

                                        @endif

                                        @canany(['edit member','edit user'])
                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="{{route('users.edit', $user->id)}}"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ __('Edit') }}">

                                                    <i class="ti ti-edit "></i>

                                                </a>
                                            </div>
                                        @endcan

                                        @if(\Auth::user()->type == "super admin")
                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="#" data-url="{{route('plan.upgrade',$user->id)}}"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                        data-tooltip="Edit" data-ajax-popup="true" data-title="{{__('Upgrade Plan')}}" data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{__('Upgrade Plan')}}">

                                                    <i class="ti ti-trophy "></i>

                                                </a>
                                            </div>
                                        @endif

                                        <div class="action-btn bg-light-secondary ms-2">
                                            <a href="#" data-url="{{route('company.reset',\Crypt::encrypt($user->id))}}"
                                                class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                    data-tooltip="Edit" data-ajax-popup="true" data-title="{{__('Reset Password')}}" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{__('Reset Password')}}">

                                                <i class="ti ti-key "></i>

                                            </a>
                                        </div>


                                        @canany(['delete member','delete user'])
                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="#"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para "
                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                    data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                    data-confirm-yes="delete-form-{{ $user->id }}" title="{{ __('Delete') }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            </div>
                                        @endcan

                                        {!! Form::open([
                                        'method' => 'DELETE',
                                        'route' => ['users.destroy', $user->id],
                                        'id' => 'delete-form-' . $user->id,
                                        ]) !!}
                                        {!! Form::close() !!}
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
</div>

@endsection

