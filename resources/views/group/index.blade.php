@extends('layouts.app')

@section('page-title', __('Group'))

@section('action-button')
    @can('create group')
        <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
            <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="md" data-title="Add Group"
                data-url="{{ route('groups.create') }}" data-toggle="tooltip" title="{{ __('Create New Group') }}">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endcan

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Group') }}</li>
@endsection

@section('content')
    <div class="row p-0">
        <div class="col-xl-12">

                <div class="card-header card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table dataTable data-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Group Name') }}</th>
                                    <th>{{ __('# of member(s)') }}</th>
                                    <th>{{ __('Created By') }}</th>
                                    <th>{{ __('Created At') }}</th>
                                    <th width="100px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($groups as $key => $group)
                                        @php
                                            $count_member = trim($group->members,',');
                                            $count_member = explode(',', $count_member);
                                        @endphp

                                    <tr>
                                        <td>
                                            <a href="#" class="btn btn-sm " data-url="{{ route('groups.show', $group->id) }}" data-size="md" data-ajax-popup="true"
                                                data-title="{{ $group->name }}{{ __("'s Group Members") }}" data-bs-placement="top">
                                                    {{ $group->name }}
                                            </a>
                                        </td>
                                        <td>{{ count($count_member) }}</td>
                                        <td>{{ $group->creator->name }}</td>
                                        <td>{{ date('j F, Y', strtotime($group->created_at)) }}
                                            {{ $group->created_at->format('H:i') }}</td>
                                        <td>
                                            @can('show group')
                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                        data-url="{{ route('groups.show', $group->id) }}" data-size="md"
                                                        data-ajax-popup="true"
                                                        data-title="{{ $group->name }}{{ __("'s Group Members") }}"
                                                        title="{{ __('View Group Member') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top">
                                                        <i class="ti ti-eye "></i>
                                                    </a>
                                                </div>
                                            @endcan
                                            @can('edit group')
                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                        data-url="{{ route('groups.edit', $group->id) }}" data-size="md"
                                                        data-ajax-popup="true" data-title="{{ __('Edit Group') }}"
                                                        title="{{ __('Edit Group') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top">
                                                        <i class="ti ti-edit "></i>
                                                    </a>
                                                </div>
                                            @endcan
                                            @can('delete group')

                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="#"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                        data-confirm-yes="delete-form-{{ $group->id }}" title="{{ __('Delete') }}"
                                                        data-bs-toggle="tooltip" data-bs-placement="top">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                </div>
                                            @endcan
                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'route' => ['groups.destroy', $group->id],
                                                'id' => 'delete-form-' . $group->id,
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
