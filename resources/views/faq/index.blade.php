@extends('layouts.app')

@section('page-title', __('Manage FAQ'))

@section('action-button')
@if (Auth::user()->super_admin_employee == '1')

<div class="row justify-content-end">
    <div class="col-auto">
        <div class="btn btn-sm btn-primary btn-icon m-1 float-end"  data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Create FAQ') }}">
            <a href="{{route('faq.create')}}" class=""><i class="ti ti-plus text-white"></i></a>
        </div>
    </div>
</div>
@endif

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('FAQ') }}</li>
@endsection

@section('content')
    <div class="col-lg-12 col-md-12">
        <div class="card shadow-none rounded-0 border-bottom ">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="pc-dt-simple" class="table">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th class="w-25">{{ __('Title') }}</th>
                                <th>{{ __('Description') }}</th>

                                @if(\Auth::user()->super_admin_employee == 1)
                                    <th class="text-end me-3">{{ __('Action') }}</th>
                                @else
                                    <th class="text-end me-3"></th>
                                @endif

                            </tr>
                        </thead>
                        <tbody>
                            @foreach($faqs as $index => $faq)
                                <tr>
                                    <th scope="row">{{++$index}}</th>
                                    <td><span class="font-weight-bold white-space">{{$faq->title}}</span></td>
                                    <td class="faq_desc">{!! $faq->description !!}</td>
                                    <td class="text-end">
                                        @if(\Auth::user()->super_admin_employee == 1)
                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="{{ route('faq.edit',$faq->id) }}"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                    data-bs-toggle="tooltip" title="{{ __('Edit') }}"> <span
                                                        class=""> <i class="ti ti-edit"></i></span></a>
                                            </div>
                                        @endif
                                        @if(\Auth::user()->super_admin_employee == 1)
                                        <div class="action-btn bg-light-secondary ms-2">
                                            <a href="#"
                                                class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                data-confirm="{{ __('Are You Sure?') }}"
                                                data-confirm-yes="delete-form-{{ $faq->id }}"
                                                title="{{ __('Delete') }}" data-bs-toggle="tooltip" data-bs-placement="top">
                                                <i class="ti ti-trash"></i>
                                            </a>
                                        </div>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['faq.destroy',$faq->id], 'id' => 'delete-form-'.$faq->id]) !!}
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
