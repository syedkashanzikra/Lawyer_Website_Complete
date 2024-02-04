@extends('layouts.app')

@section('page-title', __('Manage KnowledgeBase Category'))

@section('action-button')
    <div class="row justify-content-end">
        <div class="col-auto">

            <div class="btn btn-sm btn-primary btn-icon m-1 float-end" data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ __('Create Knowledgebase Category') }}">
                <a href="{{ route('knowledgecategory.create') }}" class=""><i class="ti ti-plus text-white"></i></a>
            </div>
        </div>
    </div>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('knowledge') }}">{{ __('Knowledge') }}</a></li>
    <li class="breadcrumb-item">{{ __('Category') }}</li>
@endsection

@section('content')
    <div class="col-lg-12 col-md-12">
        <div class="card shadow-none rounded-0 border-bottom">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="pc-dt-simple" class="table">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th class="w-50">{{ __('Title') }}</th>
                                <th class="text-end me-3">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($knowledges_category as $index => $knowledge)
                                <tr>
                                    <th scope="row">{{ ++$index }}</th>
                                    <td><span class="font-weight-bold white-space">{{ $knowledge->title }}</span></td>
                                    <td class="text-end">
                                        @if (\Auth::user()->super_admin_employee == 1)
                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="{{ route('knowledgecategory.edit', $knowledge->id) }}"
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
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['knowledgecategory.destroy', $knowledge->id], 'id' => 'delete-form-' . $knowledge->id]) !!}
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
