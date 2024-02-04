@extends('layouts.app')
@section('page-title')
    {{ __('Landing Page') }}
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
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                        <h5>{{ __('Feature') }}</h5>
                                    </div>
                                </div>
                            </div>

                            {{ Form::open(array('route' => 'features.store', 'method'=>'post', 'enctype' => "multipart/form-data")) }}
                                @csrf
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('Title', __('Title'), ['class' => 'form-label']) }}
                                                {{ Form::text('feature_title',$settings['feature_title'], ['class' => 'form-control ', 'placeholder' => __('Enter Title')]) }}
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
                                                {{ Form::text('feature_heading',$settings['feature_heading'], ['class' => 'form-control ', 'placeholder' => __('Enter Heading')]) }}
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
                                                {{ Form::text('feature_description', $settings['feature_description'], ['class' => 'form-control', 'placeholder' => __('Enter Description')]) }}
                                                @error('mail_port')
                                                <span class="invalid-mail_port" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('Buy Now Link', __('Buy Now Link'), ['class' => 'form-label']) }}
                                                {{ Form::text('feature_buy_now_link', $settings['feature_buy_now_link'], ['class' => 'form-control', 'placeholder' => __('Enter Link')]) }}
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


                        <div class="card rounded-0 shadow-none bg-transparent">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                        <h5>{{ __('Features List') }}</h5>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 justify-content-end d-flex">
                                        <a data-size="lg" data-url="{{ route('feature_create') }}" data-ajax-popup="true"  data-bs-toggle="tooltip" data-title="{{__('Create Feature')}}"  title="{{__('Create')}}"  class="btn btn-sm btn-primary">
                                            <i class="ti ti-plus text-light"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
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
                                           @if (is_array($feature_of_features) || is_object($feature_of_features))
                                           @php
                                               $ff_no = 1
                                           @endphp
                                                @foreach ($feature_of_features as $key => $value)
                                                    <tr>
                                                        <td>{{ $ff_no++ }}</td>
                                                        <td>{{ $value['feature_heading'] }}</td>
                                                        <td>
                                                            <span>
                                                                <div class="action-btn bg-primary ms-2">
                                                                        <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="{{ route('feature_edit',$key) }}" data-ajax-popup="true" data-title="{{__('Edit Feature')}}" data-size="lg" data-bs-toggle="tooltip"  title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                                        <i class="ti ti-pencil text-white"></i>
                                                                    </a>
                                                                </div>

                                                                <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open(['method' => 'GET', 'route' => ['feature_delete', $key],'id'=>'delete-form-'.$key]) !!}

                                                                    <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}"
                                                                    data-confirm-yes="delete-form-{{ $key }}"
                                                                    >
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


                        <div class="card rounded-0 shadow-none bg-transparent">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-10 col-md-10 col-sm-10">
                                        <h5>{{ __('Feature') }}</h5>
                                    </div>
                                </div>
                            </div>



                            {{ Form::open(array('route' => 'feature_highlight_create', 'method'=>'post', 'enctype' => "multipart/form-data")) }}
                                @csrf
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('highlight_feature_heading', __('Heading'), ['class' => 'form-label']) }}
                                                {{ Form::text('highlight_feature_heading', $settings['highlight_feature_heading'], ['class' => 'form-control', 'placeholder' => __('Enter Link')]) }}
                                                @error('highlight_feature_heading')
                                                    <span class="invalid-mail_port" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('highlight_feature_heading', __('Description'), ['class' => 'form-label']) }}
                                                {{ Form::text('highlight_feature_description', $settings['highlight_feature_description'], ['class' => 'form-control', 'placeholder' => __('Enter Link')]) }}
                                                @error('highlight_feature_description')
                                                <span class="invalid-mail_port" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                {{ Form::label('Logo', __('Logo'), ['class' => 'form-label']) }}
                                                <div class="logo-content mt-4">
                                                    <img id="image1" src="{{ $logo.'/'. $settings['highlight_feature_image'] }}"
                                                        class="big-logo img_setting" style="width: 150px; height:60px;">
                                                </div>
                                                <div class="choose-files mt-5">
                                                    <label for="highlight_feature_image">
                                                        <div class=" bg-primary dark_logo_update" style="cursor: pointer;"> <i class="ti ti-upload px-1">
                                                            </i>{{ __('Choose file here') }}
                                                        </div>
                                                        <input type="file" name="highlight_feature_image" id="highlight_feature_image" class="form-control file" data-filename="highlight_feature_image">
                                                    </label>
                                                </div>
                                                @error('highlight_feature_image')
                                                <div class="row">
                                                    <span class="invalid-logo" role="alert">
                                                        <strong class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                </div>
                                                @enderror
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <div class="card-footer text-end border-bottom rounded-0">
                                    <input class="btn btn-print-invoice btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
                                </div>
                            {{ Form::close() }}



                        </div>


                        <div class="card rounded-0 shadow-none bg-transparent border-bottom">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col-lg-9 col-md-9 col-sm-9">
                                        <h5>{{ __('Features Block') }}</h5>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 justify-content-end d-flex">
                                        <a data-size="lg" data-url="{{ route('features_create') }}" data-ajax-popup="true"  data-bs-toggle="tooltip" data-title="{{__('Create Feature Block')}}"  title="{{__('Create')}}"  class="btn btn-sm btn-primary">
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
                                            @if (is_array($feature_of_features) || is_object($feature_of_features))
                                            @php
                                                $of_no = 1
                                            @endphp
                                                @foreach ($other_features as $key => $value)
                                                    <tr>
                                                        <td>{{ $of_no++ }}</td>
                                                        <td>{{ $value['other_features_heading'] }}</td>
                                                        <td>
                                                            <span>
                                                                <div class="action-btn bg-primary ms-2">
                                                                        <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="{{ route('features_edit',$key) }}" data-ajax-popup="true" data-title="{{__('Edit Feature Block')}}" data-size="lg" data-bs-toggle="tooltip"  title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                                        <i class="ti ti-pencil text-white"></i>
                                                                    </a>
                                                                </div>

                                                                    <div class="action-btn bg-danger ms-2">
                                                                    {!! Form::open(['method' => 'GET', 'route' => ['features_delete', $key],'id'=>'delete-form-'.$key]) !!}

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



