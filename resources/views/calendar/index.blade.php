@extends('layouts.app')

@section('page-title', __('Calendar'))

@push('style')
    <link rel="stylesheet" href="{{asset('public/assets/css/plugins/main.css')}}">
@endpush

@section('action-button')
@can('manage diary')
<div class="text-sm-end d-flex all-button-box justify-content-sm-end">
    <a href="{{ route('casediary.index') }}" class="btn btn-md btn-primary mx-1" data-bs-toggle="tooltip"
        data-bs-placement="top" data-toggle="tooltip" data-title="{{__('Diary View')}}" data-bs-original-title="{{__('Diary View')}}">
        {{__('Diary View')}}
        <i class="ti ti-license"></i>
    </a>
</div>
@endcan
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Calendar') }}</li>
@endsection
@php

$setting = App\Models\Utility::settings();

$segment=Request::segment(2);

@endphp
@section('content')
    <div class="row p-0 g-0">
        <div class="col-lg-8 border-end border-bottom">
            <div class="card shadow-none">
                <div class="card-header ">
                    <h5 style="width: 150px;">{{ __('Calendar') }}</h5>
                    @if (isset($setting['is_enabled']) && $setting['is_enabled'] == 'on')
                        <select class="form-control" name="calender_type" id="calender_type" style="float: right;width: 150px;" onchange="get_data()">
                            <option value="goggle_calender">{{__('Google Calender')}}</option>
                            <option value="local_calender" selected="true">{{__('Local Calender')}}</option>
                        </select>
                    @endif
                    <input type="hidden" id="path_admin" value="{{url('/')}}">
                </div>
                <div class="card-body">
                    <div id='calendar' class='calendar'></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 border-bottom">

            <div class="card  shadow-none rounded-0 border-bottom">
                <div class="card-body">
                    <div class="row justify-content-around">
                        <div class="col-md-4 col-6 my-2">
                            <div class="d-flex align-items-start">
                                <div class="theme-avtar bg-info">
                                    <i class="ti ti-calendar-event"></i>
                                </div>
                                <div class="ms-2">
                                    <p class="text-muted text-sm mb-0">{{ __('To-Dos') }}</p>
                                    <h4 class="mb-0 text-primary">{{ count($todo_data) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 my-2">
                            <div class="d-flex align-items-start">
                                <div class="theme-avtar bg-warning">
                                    <i class="ti ti-calendar-event"></i>
                                </div>
                                <div class="ms-2">
                                    <p class="text-muted text-sm mb-0">{{ __('Case Hearing') }}</p>
                                    <h4 class="mb-0 text-info">{{ count($hearings) }}</h4>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card  shadow-none rounded-0">
                <div class="card-body px-0">
                    <h4 class="mb-4 px-3">{{ __('Tasks') }}</h4>
                    <ul class="event-cards list-group list-group-flush w-100">

                        @foreach ($events as $calenderData)
                            @php
                                $month = date('m', strtotime($calenderData['start']));
                            @endphp
                            @if ($month == date('m'))
                                <li class="list-group-item card bg-transparent border-top border-bottom rounded-0 shadow-none mb-3">
                                    <div class="row align-items-center justify-content-between">
                                        <div class="col-auto mb-3 mb-sm-0">
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="theme-avtar {{ $calenderData['data_name'] == 'hearing' ? 'bg-warning' : 'bg-primary' }}">
                                                    <i class="ti ti-calendar-event"></i>
                                                </div>
                                                <div class="ms-3">
                                                    <h6 class="m-0">
                                                        <a class="fc-daygrid-event" style="white-space: inherit;">
                                                            <div class="fc-event-title-container">
                                                                <div class="fc-event-title text-dark">
                                                                    {!! $calenderData['description'] !!}
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </h6>
                                                    <small class="text-muted">{{ $calenderData['start'] }} to
                                                        {{ $calenderData['end'] }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('custom-script')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/main.css') }}">

    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script>
        $(document).ready(function() {
            get_data();
        });

        function get_data()
        {
            var calender_type=$('#calender_type :selected').val();

            $('#calendar').removeClass('local_calender');
            $('#calendar').removeClass('goggle_calender');

            if(calender_type == undefined){
                calender_type = 'local_calender';
            }

            $('#calendar').addClass(calender_type);
            var urls=$("#path_admin").val()+"/data/get_all_data";

            $.ajax({
                url: urls ,
                method:"POST",
                data: {"_token": "{{ csrf_token() }}",'calender_type':calender_type},
                success: function(data) {

                    (function() {
                        var etitle;
                        var etype;
                        var etypeclass;
                        var calendar = new FullCalendar.Calendar(document.getElementById(
                        'calendar'), {
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,timeGridWeek,timeGridDay'
                            },
                            buttonText: {
                                timeGridDay: "{{ __('Day') }}",
                                timeGridWeek: "{{ __('Week') }}",
                                dayGridMonth: "{{ __('Month') }}"
                            },
                            slotLabelFormat: {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: false,
                            },
                            themeSystem: 'bootstrap',
                            navLinks: true,
                            droppable: true,
                            selectable: true,
                            selectMirror: true,
                            editable: true,
                            dayMaxEvents: true,
                            handleWindowResize: true,
                            events: data,
                        });
                        calendar.render();
                    })();
                }
            });
        }

        $(".fc-daygrid-event").on('click',function(e) {
            alert("The paragraph was clicked.");
        });

        $(document).on('click', '.fc-daygrid-event', function(e) {

            if ($(this).attr('href') != undefined) {
                // Sample string
            var mainString = $(this).attr('href');

            // Substring to find
            var substringToFind = "case";

            // Using indexOf() to find the index of the substring
            var index = mainString.indexOf(substringToFind);

            if (index !== -1) {

            } else {
                e.preventDefault();url
                var event = $(this);
                var title = $(this).find('.fc-event-title-container .fc-event-title').html();

                var size = 'md';
                var url = $(this).attr('href');
                var parts = url.split("/");

                $("#commanModel .modal-title").html(title);
                $("#commanModel .modal-dialog").addClass('modal-' + size);

                $.ajax({
                    url: url,
                    success: function(data) {

                        $('#commanModel .extra').html(data);
                        $("#commanModel").modal('show');
                    },
                    error: function(data) {

                        data = data.responseJSON;
                        toastr('error', data.error, 'error')
                    }
                });
            }
            }
        });


    </script>
@endpush
