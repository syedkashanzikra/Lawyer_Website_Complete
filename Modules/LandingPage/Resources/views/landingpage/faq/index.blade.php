@extends('layouts.app')
@section('page-title')
    {{ __('Landing Page') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item">{{__('Landing Page')}}</li>
@endsection

@php
    $settings = \Modules\LandingPage\Entities\LandingPageSetting::landingPageSetting();
    $logo=\App\Models\Utility::get_file('uploads/landing_page_image');
@endphp


@push('css-page')
    <link rel="stylesheet" href=" {{ Module::asset('LandingPage:Resources/assets/css/summernote/summernote-bs4.css')}}" />
@endpush

@push('script-page')
    <script src="{{ Module::asset('LandingPage:Resources/assets/js/plugins/summernote-bs4.js')}}" referrerpolicy="origin"></script>
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Landing Page')}}</li>
@endsection


@section('content')
        <div class="col-sm-12">
            <div class="row g-0">
                <div class="col-xl-3 border-end border-bottom">
                    <div class="card shadow-none bg-transparent sticky-top" style="top:30px">
                        <div class="list-group list-group-flush rounded-0" id="useradd-sidenav">

                            @include('landingpage::layouts.tab')

                        </div>
                    </div>
                </div>

                <div class="col-xl-9">
                    {{--  Start for all settings tab --}}


                        <div class="card rounded-0 shadow-none bg-transparent">
                            {{ Form::open(array('route' => 'faq.store', 'method'=>'post', 'enctype' => "multipart/form-data")) }}
                            @csrf
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col-6">
                                            <h5 class="mb-2">{{ __('FAQ') }}</h5>
                                        </div>
                                        <div class="col switch-width text-end">
                                            <div class="form-group mb-0">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary" class="" name="faq_status"
                                                        id="faq_status"  {{ $settings['faq_status'] == 'on' ? 'checked="checked"' : '' }}>
                                                    <label class="custom-control-label" for="faq_status"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('Title', __('Title'), ['class' => 'form-label']) }}
                                                {{ Form::text('faq_title',$settings['faq_title'], ['class' => 'form-control ', 'placeholder' => __('Enter Title')]) }}
                                                @error('mail_host')
                                                <span class="invalid-mail_driver" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('Heading', __('Heading'), ['class' => 'form-label']) }}
                                                {{ Form::text('faq_heading',$settings['faq_heading'], ['class' => 'form-control ', 'placeholder' => __('Enter Heading')]) }}
                                                @error('mail_host')
                                                <span class="invalid-mail_driver" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('Description', __('Description'), ['class' => 'form-label']) }}
                                                {{ Form::text('faq_description', $settings['faq_description'], ['class' => 'form-control', 'placeholder' => __('Enter Description')]) }}
                                                @error('mail_port')
                                                <span class="invalid-mail_port" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>



                                    </div>
                                </div>
                                <div class="card-footer text-end border-bottom rounded-0">
                                    <button class="btn btn-print-invoice btn-primary m-r-10" type="submit" >{{ __('Save Changes') }}</button>
                                </div>
                            {{ Form::close() }}

                        </div>


                        <div class="card rounded-0 shadow-none bg-transparent border-bottom">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                        <h5>{{ __('FAQ List') }}</h5>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 justify-content-end d-flex">
                                        <a data-size="lg" data-url="{{ route('faq_create') }}" data-ajax-popup="true"  data-bs-toggle="tooltip" data-title="{{__('Create FAQ')}}"  title="{{__('Create')}}"  class="btn btn-sm btn-primary">
                                            <i class="ti ti-plus text-light"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">

                                {{-- <div class="justify-content-end d-flex">

                                    <a data-size="lg" data-url="{{ route('users.create') }}" data-ajax-popup="true"  data-bs-toggle="tooltip" title="{{__('Create')}}"  class="btn btn-sm btn-primary">
                                        <i class="ti ti-plus text-light"></i>
                                    </a>
                                </div> --}}

                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>{{__('No')}}</th>
                                                <th>{{__('Name')}}</th>
                                                <th>{{__('Action')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           @if (is_array($faqs) || is_object($faqs))
                                            @php
                                                $no = 1
                                            @endphp
                                                @foreach ($faqs as $key => $value)
                                                    <tr>
                                                        <td>{{ $no++ }}</td>
                                                        <td>{{ $value['faq_questions'] }}</td>
                                                        <td>
                                                            <span>
                                                                <div class="action-btn bg-primary ms-2">
                                                                    <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="{{ route('faq_edit',$key) }}" data-ajax-popup="true" data-title="{{__('Edit FAQ')}}"  title="{{__('Edit')}}" data-size="lg" data-bs-toggle="tooltip"  title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                                        <i class="ti ti-pencil text-white"></i>
                                                                    </a>
                                                                </div>

                                                                    <div class="action-btn bg-danger ms-2">
                                                                    {!! Form::open(['method' => 'GET', 'route' => ['faq_delete', $key],'id'=>'delete-form-'.$key]) !!}

                                                                        <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{ $key }}">
                                                                        <i class="ti ti-trash text-white"></i>
                                                                        </a>
                                                                        {!! Form::close() !!}
                                                                    </div>
                                                                </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>




                    {{--  End for all settings tab --}}
                </div>
            </div>
        </div>
@endsection



