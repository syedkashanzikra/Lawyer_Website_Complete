@php
$file_validation = App\Models\Utility::file_upload_validation();
@endphp
@extends('layouts.app')
@section('page-title', __('User Detail'))
@php
    $logo = asset('storage/uploads/profile/');

    $settings = App\Models\Utility::settings();
@endphp
@section('breadcrumb')
<li class="breadcrumb-item">{{ __('User Detail') }}</li>
@endsection

@section('content')
    <div class="row p-0 g-0">
        <div class="col-sm-12">
            <div class="row g-0">
                <div class="col-xl-3 border-end border-bottom">
                    <div class="card shadow-none bg-transparent sticky-top" style="top:70px">
                        <div class="list-group list-group-flush rounded-0" id="useradd-sidenav">
                            <a href="#useradd-1"
                                class="list-group-item list-group-item-action">{{ __('Information') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-2"
                                class="list-group-item list-group-item-action">{{ __('Usage Statistics') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-3"
                                class="list-group-item list-group-item-action">{{ __('Employees') }}
                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9">
                    <div id="useradd-1" class="card  shadow-none rounded-0 border-bottom">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Personal Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class=" setting-card">
                                <div class="row">
                                    <div class="col-lg-4 col-sm-6 col-md-6">
                                        <div class="card-body text-center">
                                            <div class="logo-content">
                                                <a href="{{ !empty($user->avatar) ? $logo .'/'. $user : $logo . '/avatar.png' }}" target="_blank">
                                                    <img src="{{ !empty($user->avatar) ? $logo .'/'. $user->avatar : $logo . '/avatar.png' }}" width="180" id="profile">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-sm-6 col-md-6">
                                        <div class="card-body">
                                            <div class="col-lg-12">
                                                    <dl class="row col-md-12 p-0">
                                                        <dt class="col-sm-3"><span class="h6 text-md mb-0">{{ __('Name:') }}</span></dt>
                                                        <dd class="col-sm-9"><span class="text-md">{{ $user->name }}</span></dd>
                                                        <dt class="col-md-3"><span class="h6 text-md mb-0">{{ __('Email:') }}</span></dt>
                                                        <dd class="col-md-9"><span class="text-md">{{ $user->email }}</span></dd>
                                                        <dt class="col-md-3"><span class="h6 text-md mb-0">{{ __('Mobile No:') }}</span></dt>
                                                        <dd class="col-md-9"><span class="text-md">{{ !empty($user_detail->mobile_number) ? $user_detail->mobile_number : '-' }}</span></dd>
                                                        <dt class="col-md-3"><span class="h6 text-md mb-0">{{ __('Address:') }}</span></dt>
                                                        <dd class="col-md-9"><span class="text-md">{{ !empty($user_detail->address) ? $user_detail->address : '-' }}</span></dd>
                                                        <dt class="col-md-3"><span class="h6 text-md mb-0">{{ __('City:') }}</span></dt>
                                                        <dd class="col-md-9"><span class="text-md">{{ !empty($user_detail->city) ? $user_detail->city : '-' }}</span></dd>
                                                        <dt class="col-md-3"><span class="h6 text-md mb-0">{{ __('State:') }}</span></dt>
                                                        <dd class="col-md-9"><span class="text-md">{{ !empty($user_detail->state) ? $user_detail->state : '-' }}</span></dd>
                                                        <dt class="col-md-3"><span class="h6 text-md mb-0">{{ __('Zip/Postal Code:') }}</span></dt>
                                                        <dd class="col-md-9"><span class="text-md">{{ !empty($user_detail->zip_code) ? $user_detail->zip_code : '-' }}</span></dd>
                                                        <dt class="col-md-3"><span class="h6 text-md mb-0">{{ __('Landmark') }}</span></dt>
                                                        <dd class="col-md-9"><span class="text-md">{{ !empty($user_detail->landmark) ? $user_detail->landmark : '-' }}</span></dd>
                                                    </dl>
                                            </div>
                                        </div>
                                    </div>
                                     <hr>
                                    <div class="col-md-12">
                                        <h6>{{ __('Description') }}</h6>
                                        <p>{{ !empty($user_detail->about) ? $user_detail->about : '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="useradd-2" class="card  shadow-none rounded-0 border-bottom">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Usage Statistics') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class=" setting-card">
                                <div class="row">
                                    <div class="col-xxl-3 col-lg-5 col-md-6 col-sm-6 plan_card mb-0 border-bottom border-end">
                                        <div class="card shadow-none  price-card price-1 rounded-0">
                                            <div class="card-body ">
                                                <span class="price-badge bg-primary">{{ $plan->name }}</span>

                                                <span class="mb-4 f-w-500 p-price">
                                                    {{($settings['site_currency_symbol'] ? $settings['site_currency_symbol'] : '$')}} {{ number_format($plan->price)
                                                    }} <small class="text-sm">/ {{$plan->duration}}</small>
                                                </span>
                                                <p class="mb-0">
                                                </p>
                                                <p class="mb-0">
                                                    {{ $plan->description }}
                                                </p>

                                                <ul class="list-unstyled ">
                                                    <li>
                                                        <span class="theme-avtar">
                                                            <i class="text-primary ti ti-circle-plus"></i></span>
                                                        {{ ($plan->max_users < 0) ? __('Unlimited'):$plan->max_users }} {{__('Users')}}
                                                    </li>
                                                    <li>
                                                        <span class="theme-avtar">
                                                            <i class="text-primary ti ti-circle-plus"></i></span>
                                                        {{ __('Unlimited')}} {{__('Clients')}}
                                                    </li>

                                                </ul>
                                                <div class="p-0">
                                                    <a href="#" class="btn btn-sm btn-light-primary" data-url="{{ route('plan.upgrade',$user->id) }}"
                                                        data-size="lg" data-ajax-popup="true" data-title="{{__('Upgrade Plan')}}">
                                                        {{__('Upgrade Plan')}}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-9 col-lg-7 col-md-6 col-sm-6">
                                        <div class="row">
                                            <div class="col-xxl-6 col-lg-12 col-md-6 col-sm-6 ">
                                                <a href="#">
                                                    <div class="col border-end border-bottom">
                                                        <div class="">
                                                            <div class="d-flex justify-content-between mb-3">
                                                                <div class="theme-avtar bg-primary">
                                                                    <i class="ti ti-users"></i>
                                                                </div>
                                                                <div>
                                                                    <p class="text-muted text-sm mb-0">{{__('Total')}}</p>
                                                                    <h6 class="mb-0">{{__('Number of Employees ')}}</h6>
                                                                </div>
                                                            </div>
                                                            <h3 class="mb-0">{{ count($members) }}</h3>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-xxl-6 col-lg-12 col-md-6 col-sm-6 pt-1">
                                                <a href="#">
                                                    <div class="col border-end border-bottom">
                                                        <div class="">
                                                            <div class="d-flex justify-content-between mb-3">
                                                                <div class="theme-avtar bg-info">
                                                                    <i class="ti ti-users"></i>
                                                                </div>
                                                                <div>
                                                                    <p class="text-muted text-sm mb-0">{{__('Total')}}</p>
                                                                    <h6 class="mb-0">{{__('Number of Clients')}}</h6>
                                                                </div>
                                                            </div>
                                                            <h3 class="mb-0">{{ count($client) }}</h3>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-xxl-6 col-lg-12 col-md-6 col-sm-6 pt-1">
                                                <a href="#">
                                                    <div class="col border-end border-bottom">
                                                        <div class="">
                                                            <div class="d-flex justify-content-between mb-3">
                                                                <div class="theme-avtar bg-warning">
                                                                    <i class="ti ti-report-money"></i>
                                                                </div>
                                                                <div>
                                                                    <p class="text-muted text-sm mb-0">{{__('Total')}}</p>
                                                                    <h6 class="mb-0">{{__('Number of Cases')}}</h6>
                                                                </div>
                                                            </div>
                                                            <h3 class="mb-0">{{ count($cases) }}</h3>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-xxl-6 col-lg-12 col-md-6 col-sm-6 pt-1">
                                                <a href="#">
                                                    <div class="col border-end border-bottom">
                                                        <div class="">
                                                            <div class="d-flex justify-content-between mb-3">
                                                                <div class="theme-avtar bg-danger">
                                                                    <i class="ti ti-database"></i>
                                                                </div>
                                                                <div>
                                                                    <p class="text-muted text-sm mb-0">{{__('Total')}}</p>
                                                                    <h6 class="mb-0">{{__('Data - Consumed / Allotted')}}</h6>
                                                                </div>
                                                            </div>
                                                            <h3 class="mb-0">{{ count($cases) }}</h3>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="useradd-3" class="card  shadow-none rounded-0 border-bottom">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Employees') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class=" setting-card">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table dataTable data-table ">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('#') }}</th>
                                                        <th>{{ __('Name') }}</th>
                                                        <th>{{ __('Role') }}</th>
                                                        <th>{{ __('Email') }}</th>
                                                        <th width="100px">{{ __('Action') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @foreach ($employee as $key => $employee)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $employee->name }}</td>
                                                        <td>{{ $employee->type }}</td>
                                                        <td>{{ $employee->email }}</td>
                                                        <td>

                                                            <div class="action-btn bg-light-secondary ms-2">
                                                                <a href="#" data-url="{{route('company.reset',\Crypt::encrypt($employee->id))}}"
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
                                                                        data-confirm-yes="delete-form-{{ $employee->id }}" title="{{ __('Delete') }}"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top">
                                                                        <i class="ti ti-trash"></i>
                                                                    </a>
                                                                </div>
                                                            @endcan

                                                            {!! Form::open([
                                                            'method' => 'DELETE',
                                                            'route' => ['users.destroy', $employee->id],
                                                            'id' => 'delete-form-' . $employee->id,
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('custom-script')
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300,

        })
        $(".list-group-item").on('click',function() {
            $('.list-group-item').filter(function() {
                return this.href == id;
            }).parent().removeClass('text-primary');
        });
    </script>

@if(Auth::user()->type == 'advocate')
<script>
    $(document).ready(function() {

            var get_selected = '{{!empty($advocate->ofc_country) ? $advocate->getCountryName($advocate->ofc_country) : $advocate->getCountryName(113)}}';
            var home_selected = '{{!empty($advocate->home_country) ? $advocate->getCountryName($advocate->home_country) : $advocate->getCountryName(113)}}';

            $.ajax({
                url: "{{ route('get.country') }}",
                type: "GET",
                success: function(result) {

                    $.each(result.data, function(key, value) {
                        if(value.id == get_selected){
                            var selected = 'selected';
                        }else{
                            var selected = '';
                        }

                        if(value.id == home_selected){
                            var selected_home = 'selected';
                        }else{
                            var selected_home = '';
                        }

                        $("#country").append('<option value="' + value.id + '" '+ selected +' >' + value
                            .country + "</option>");

                        $("#home_country").append('<option value="' + value.id + '" '+ selected_home +'>' + value
                            .country + "</option>");
                    });
                },
            });


            $("#country").on("change", function() {
                var country_id = this.value;

                $("#state").html("");
                $.ajax({
                    url: "{{ route('get.state') }}",
                    type: "POST",
                    data: {
                        country_id: country_id,
                        _token: "{{ csrf_token() }}",
                    },
                    dataType: "json",
                    success: function(result) {
                        $.each(result.data, function(key, value) {
                            $("#state").append('<option value="' + value.id + '">' +
                                value.region + "</option>");
                        });
                        $("#city").html('<option value="">Select State First</option>');
                    },
                });
            });

            $("#home_country").on("change", function() {
                var country_id = this.value;
                $("#home_state").html("");
                $.ajax({
                    url: "{{ route('get.state') }}",
                    type: "POST",
                    data: {
                        country_id: country_id,
                        _token: "{{ csrf_token() }}",
                    },
                    dataType: "json",
                    success: function(result) {
                        $.each(result.data, function(key, value) {
                            $("#home_state").append('<option value="' + value.id + '">' +
                                value.region + "</option>");
                        });
                        $("#home_city").html('<option value="">Select State First</option>');
                    },
                });
            });

            $("#state").on("change", function() {
                var state_id = this.value;
                $("#city").html("");
                $.ajax({
                    url: "{{ route('get.city') }}",
                    type: "POST",
                    data: {
                        state_id: state_id,
                        _token: "{{ csrf_token() }}",
                    },
                    dataType: "json",
                    success: function(result) {
                        $.each(result.data, function(key, value) {
                            $("#city").append('<option value="' + value.id + '">' +
                                value.city + "</option>");
                        });
                    },
                });
            });

            $("#home_state").on("change", function() {
                var state_id = this.value;
                $("#home_city").html("");
                $.ajax({
                    url: "{{ route('get.city') }}",
                    type: "POST",
                    data: {
                        state_id: state_id,
                        _token: "{{ csrf_token() }}",
                    },
                    dataType: "json",
                    success: function(result) {
                        $.each(result.data, function(key, value) {
                            $("#home_city").append('<option value="' + value.id + '">' +
                                value.city + "</option>");
                        });
                    },
                });
            });
        });
</script>

<script src="{{ asset('public/assets/js/jquery-ui.js') }}"></script>
<script src="{{ asset('public/assets/js/repeater.js') }}"></script>
<script>
    var selector = "body";
        if ($(selector + " .repeater").length) {
            var $dragAndDrop = $("body .repeater tbody").sortable({
                handle: '.sort-handler'
            });
            var $repeater = $(selector + ' .repeater').repeater({
                initEmpty: false,
                defaultValues: {
                    'status': 1
                },
                show: function() {
                    $(this).slideDown();
                    var file_uploads = $(this).find('input.multi');
                    if (file_uploads.length) {
                        $(this).find('input.multi').MultiFile({
                            max: 3,
                            accept: 'png|jpg|jpeg',
                            max_size: 2048
                        });
                    }
                    if ($('.select2').length) {
                        $('.select2').select2();
                    }

                },
                hide: function(deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        if ($('.disc_qty').length < 6) {
                            $(".add-row").show();

                        }
                        $(this).slideUp(deleteElement);
                        $(this).remove();

                        var inputs = $(".amount");
                        var subTotal = 0;
                        for (var i = 0; i < inputs.length; i++) {
                            subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                        }
                        $('.subTotal').html(subTotal.toFixed(2));
                        $('.totalAmount').html(subTotal.toFixed(2));
                    }
                },
                ready: function(setIndexes) {
                    $dragAndDrop.on('drop', setIndexes);
                },
                isFirstItemUndeletable: true
            });
            var value = $(selector + " .repeater").attr('data-value');

            if (typeof value != 'undefined' && value.length != 0) {
                value = JSON.parse(value);
                $repeater.setList(value);
            }

        }

        $(".add-row").on('click',function(event){
            var $length = $('.disc_qty').length;
            if ($length == 5) {
                $(this).hide();
            }
        });
        $(".desc_delete").on('click',function(event) {

            var $length = $('.disc_qty').length;
        });
</script>
@endif
@endpush
