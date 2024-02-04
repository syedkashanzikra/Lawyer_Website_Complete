@extends('layouts.app')
@section('page-title')
    {{ __('User Logs') }}
@endsection
@section('title')
    {{ __('Users Logs') }}
@endsection
@section('breadcrumb')

    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{__('User')}}</a></li>
    <li class="breadcrumb-item">{{ __('User Logs') }}</li>
@endsection

@section('filter')
@endsection
@section('content')
    <div class="row pt-0">
        <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
            <div class="card shadow-none rounded-0 border-bottom">
                <div class="card-body">
                    {{ Form::open(['url' => ['userlog'], 'method' => 'get', 'id' => 'userlogs_filter']) }}
                    <div class="d-flex align-items-center justify-content-end">
                        <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                            {{ Form::month('month', isset($_GET['month']) ? $_GET['month'] : '', ['class' => 'form-control']) }}
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                            {{ Form::select('user', $usersList, isset($_GET['users']) ? $_GET['users'] : '', ['class' => 'form-control select ', 'id' => 'id']) }}
                        </div>
                        <div class="action-btn bg-light-secondary  col-auto float-end">
                            <button type="submit" class="m-1 btn btn-sm align-items-center text-dark "
                                onclick="document.getElementById('userlogs_filter').submit(); return false;"
                                data-bs-toggle="tooltip" title="{{ __('Apply') }}" data-title="{{ __('Apply') }}"><i
                                    class="ti ti-search"></i></button>
                        </div>
                        <div class="action-btn bg-light-secondary col-auto float-end" style="margin-left:5px">
                            <a href="{{ url('userlog') }}" data-bs-toggle="tooltip"
                                title="{{ __('Reset') }}"data-title="{{ __('Reset') }}"
                                class="m-1 btn btn-sm align-items-center text-dark "><i class="ti ti-trash-off"></i></a>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>

            <div class="">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table id="datatable" class="table dataTable data-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ __('User Name') }}</th>
                                    <th>{{ __('Role') }}</th>
                                    <th>{{ __('IP') }}</th>
                                    <th>{{ __('Last Login At') }}</th>
                                    <th>{{ __('Country') }}</th>
                                    <th>{{ __('Device Type') }}</th>
                                    <th>{{ __('Os Name') }}</th>
                                    <th>{{ __('Details') }}</th>
                                </tr>
                            </thead>
                                @foreach ($users as $user)

                                    <tr>
                                        @php
                                            $json = json_decode($user->details);

                                            $userType = App\Models\User::find($user->user_id);

                                        @endphp
                                        <td>{{ $user->user_name }}</td>
                                        <td> <span class="me-5 badge p-2 px-3 rounded bg-primary">{{ $userType->type}}</span></td>
                                        <td>{{ $user->ip }}</td>
                                        <td>{{ $user->date }}</td>
                                        <td>{{ $json->country }}</td>
                                        <td>{{ $json->device_type }}</td>
                                        <td>{{ $json->os_name }}</td>
                                        <td>
                                            <div class="action-btn bg-light-secondary text-dark ms-2">
                                                <a href="#" data-size="md"
                                                    data-url="{{ route('userlog.view', $user->id) }}" data-bs-toggle="tooltip"
                                                    title="{{ __('View') }}" data-ajax-popup="true"
                                                    data-title="{{ __('View User Logs') }}"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center ">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                            </div>
                                            <div class="action-btn bg-light-secondary text-dark ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['userlog.destroy', $user->id],'id' => 'delete-form-' . $user->id]) !!}
                                                    <a href="#!" class="mx-3 btn btn-sm align-items-center bs-pass-para " data-id="{{ $user->id }}"
                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                        data-confirm-yes="delete-form-{{ $user->id }}"
                                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}">
                                                        <i class="ti ti-trash " data-bs-toggle="tooltip"
                                                            data-bs-original-title="{{ __('Delete') }}"></i>
                                                    </a>
                                                {!! Form::close() !!}
                                            </div>
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
