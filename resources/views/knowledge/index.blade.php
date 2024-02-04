@extends('layouts.app')

@section('page-title', __('Manage Knowledge'))

@section('action-button')
@if (Auth::user()->super_admin_employee == '1')

    <div class="row justify-content-end">
        <div class="col-auto">

            <div class="btn btn-sm btn-primary btn-icon m-1 float-end" data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ __('Create Knowledge') }}">
                <a href="{{ route('knowledge.create') }}" class=""><i class="ti ti-plus text-white"></i></a>
            </div>
            <div class="btn btn-sm btn-primary btn-icon m-1 float-end" data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ __('Create Knowledge Category') }}">
                <a href="{{ route('knowledgecategory') }}" class=""><i class="ti ti-vector-bezier text-white"></i></a>
            </div>
        </div>
    </div>
    @endif
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Knowledge') }}</li>
@endsection

@section('content')
    <div class="col-lg-12 col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="pc-dt-simple" class="table">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th class="w-25">{{ __('Title') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Category') }}</th>
                                @if(\Auth::user()->super_admin_employee == 1)

                                <th class="text-end me-3">{{ __('Action') }}</th>
                                @else
                                <th class="text-end me-3"></th>
                            @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($knowledges as $index => $knowledge)
                                <tr>
                                    <th scope="row">{{ ++$index }}</th>
                                    <td><span class="font-weight-bold white-space">{{ $knowledge->title }}</span>
                                    </td>
                                    <td class="knowledge_desc space_desc">{!! $knowledge->description !!}</td>
                                    <td>
                                        <span class="font-weight-normal">
                                            {{ !empty($knowledge->getCategoryInfo) ? $knowledge->getCategoryInfo->title : '-' }}
                                        </span>
                                    </td>

                                    <td class="text-end">

                                        @if (\Auth::user()->super_admin_employee == 1)
                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="{{ route('knowledge.edit', $knowledge->id) }}"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                    data-bs-toggle="tooltip" title="{{ __('Edit') }}"> <span
                                                        class=""> <i class="ti ti-edit"></i></span></a>
                                            </div>
                                        @endif
                                        @if (\Auth::user()->super_admin_employee == 1)
                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="#"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                    data-confirm-yes="delete-form-{{ $knowledge->id }}"
                                                    title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            </div>
                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'route' => ['knowledge.destroy', $knowledge->id],
                                                'id' => 'delete-form-' . $knowledge->id,
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
@endsection
