@extends('layouts.app')


@section('page-title', __('Dashboard'))

@section('content')
    <div class="col-sm-12">

        <div class="row g-0">
            <!-- [ sample-page ] start -->
            <div class="col-12">
                <div
                    class="row overflow-hidden g-0 pt-0 g-0 pt-0 row-cols-1  row-cols-md-2 row-cols-xxl-5 row-cols-lg-4 row-cols-sm-2">
                    <a href="{{ route('cases.index') }}">
                        <div class="col border-end border-bottom">
                            <div class="p-3">
                                <div class="d-flex justify-content-between mb-3">
                                    <div class="theme-avtar bg-primary">
                                        <i class="ti ti-home"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted text-sm mb-0">{{ __('Total') }}</p>
                                        <h6 class="mb-0">{{ __('Cases') }}</h6>
                                    </div>
                                </div>
                                <h3 class="mb-0">{{ $cases }} </h3>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('advocate.index') }}">
                        <div class="col border-end border-bottom">
                            <div class="p-3">
                                <div class="d-flex justify-content-between mb-3">
                                    <div class="theme-avtar bg-info">
                                        <i class="ti ti-click"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted text-sm mb-0">
                                            {{ __('Total') }}
                                        </p>
                                        <h6 class="mb-0">{{ __('Advocates') }}</h6>
                                    </div>
                                </div>
                                <h3 class="mb-0"> {{ count($advocate) }} </h3>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('documents.index') }}">
                        <div class="col border-end border-bottom">
                            <div class="p-3">
                                <div class="d-flex justify-content-between mb-3">
                                    <div class="theme-avtar bg-warning">
                                        <i class="ti ti-report-money"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted text-sm mb-0">{{ __('Total') }}</p>
                                        <h6 class="mb-0">{{ __('Documents') }}</h6>
                                    </div>
                                </div>
                                <h3 class="mb-0"> {{ count($docs) }} </h3>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('users.index') }}">
                        <div class="col border-end border-bottom">
                            <div class="p-3">
                                <div class="d-flex justify-content-between mb-3">
                                    <div class="theme-avtar bg-secondary">
                                        <i class="ti ti-users"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted text-sm mb-0">{{ __('Total') }}</p>
                                        <h6 class="mb-0">{{ __('Team Members') }}</h6>
                                    </div>
                                </div>
                                <h3 class="mb-0">{{ count($members) }}</h3>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('to-do.index') }}">
                        <div class="col border-end border-bottom">
                            <div class="p-3">
                                <div class="d-flex justify-content-between mb-3">
                                    <div class="theme-avtar bg-danger">
                                        <i class="ti ti-thumb-up"></i>
                                    </div>
                                    <div>
                                        <p class="text-muted text-sm mb-0">{{ __('Total') }}</p>
                                        <h6 class="mb-0">{{ __('To-Dos') }}</h6>
                                    </div>
                                </div>
                                <h3 class="mb-0">{{ count($todos) }} </h3>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-xxl-12">
                    <div class="row g-0">
                        <!-- [ sample-page ] start -->
                        <div class="col-md-6 col-lg-3 border-end border-bottom scrollbar">
                            <div class="card shadow-none bg-transparent force-overflow">
                                <div class="card-header card-header border-bottom-0">
                                    <h5>{{ __('Today Hearing Dates') }}</h5>

                                </div>
                                <div class="card-body p-0">
                                    <div class="scroll-add">
                                        <ul class="list-group list-group-flush" id="todayhear">
                                            @if (count($todatCases) > 0)
                                                @foreach ($todatCases as $key => $upcoming)
                                                    <li class="list-group-item">
                                                        <div class="row align-items-center justify-content-between">
                                                            <div class="col-sm-auto mb-3 mb-sm-0">

                                                                {{ $upcoming['title'] }}
                                                            </div>
                                                            <div class="col-sm-auto text-sm-end d-flex align-items-center">
                                                                {{ $todayHear[$key]->date }}
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="list-group-item">
                                                    {{ __('No record found') }}
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3 border-end border-bottom scrollbar">
                            <div class="card shadow-none bg-transparent force-overflow">
                                <div class="card-header card-header border-bottom-0">
                                    <h5>{{ __('Today To-Dos') }}</h5>

                                </div>
                                <div class="card-body p-0">
                                    <div class="scroll-add">
                                        <ul class="list-group list-group-flush" id="todaytodo">

                                            @if (!empty($todayTodos))
                                                @foreach ($todayTodos as $upcoming)
                                                    <li class="list-group-item">
                                                        <div class="row align-items-center justify-content-between">
                                                            <div class="col-sm-auto mb-3 mb-sm-0">

                                                                {{ $upcoming['description'] }}
                                                            </div>
                                                            <div class="col-sm-auto text-sm-end d-flex align-items-center">

                                                                {{ $upcoming['start_date'] }}
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="list-group-item">
                                                    {{ __('No record found') }}
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 border-end border-bottom scrollbar">
                            <div class="card shadow-none bg-transparent force-overflow">
                                <div class="card-header card-header border-bottom-0">
                                    <h5>{{ __('Upcoming Hearing Dates') }}</h5>

                                </div>
                                <div class="card-body p-0">
                                    <div class="scroll-add">
                                        <ul class="list-group list-group-flush" id="cominghere">
                                            @if (!empty($upcoming_case))
                                                @foreach ($upcoming_case as $upcoming)
                                                    <li class="list-group-item">
                                                        <div class="row align-items-center justify-content-between">
                                                            <div class="col-sm-auto mb-3 mb-sm-0">

                                                                {{ $upcoming['title'] }}
                                                            </div>
                                                            <div class="col-sm-auto text-sm-end d-flex align-items-center">

                                                                {{ $upcoming['upcoming_case'] }}
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="list-group-item">
                                                    {{ __('No record found') }}
                                                </li>
                                            @endif

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3 border-end border-bottom scrollbar">
                            <div class="card shadow-none bg-transparent force-overflow">
                                <div class="card-header card-header border-bottom-0">
                                    <h5>{{ __('Upcoming To-Dos') }}</h5>

                                </div>
                                <div class="card-body p-0">
                                    <div class="scroll-add">
                                        <ul class="list-group list-group-flush "id="comingtodo">
                                            @if (!empty($upcoming_todo))
                                                @foreach ($upcoming_todo as $upcoming)
                                                    <li class="list-group-item">
                                                        <div class="row align-items-center justify-content-between">
                                                            <div class="col-sm-auto mb-3 mb-sm-0">

                                                                {{ $upcoming['description'] }}
                                                            </div>
                                                            <div class="col-sm-auto text-sm-end d-flex align-items-center">

                                                                {{ $upcoming['start_date'] }}
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="list-group-item">
                                                    {{ __('No record found') }}
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- [ sample-page ] end -->
                    </div>
                </div>

            </div>
            <!-- [ sample-page ] end  -->
        </div>

        <div class="row p-0 g-0 col-12">

            @if (Auth::user()->type == 'company')
                <div class="col-6">
                    <div class="card shadow-none bg-transparent border-end border-bottom rounded-0">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Storage Status') }} <small>({{ $users->storage_limit . 'MB' }} /
                                    {{ $plan->storage_limit . 'MB' }})</small></h5>
                        </div>
                        <div class="card-body device-chart">
                            <div id="device-chart"></div>

                        </div>
                    </div>
                </div>
            @endif

            <div class="col-6 border">
                <div class="card shadow-none">
                    <div class="card-header ">
                        <h5 style="width: 150px;">{{ __('Calendar') }}</h5>
                        @if (isset($setting['is_enabled']) && $setting['is_enabled'] == 'on')
                            <select class="form-control" name="calender_type" id="calender_type"
                                style="float: right;width: 150px;" onchange="get_data()">
                                <option value="goggle_calender">{{ __('Google Calender') }}</option>
                                <option value="local_calender" selected="true">{{ __('Local Calender') }}</option>
                            </select>
                        @endif
                        <input type="hidden" id="path_admin" value="{{ url('/') }}">
                    </div>
                    <div class="card-body">
                        <div id='calendar' class='calendar'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-script')
    <script>
        var px = new SimpleBar(document.querySelector("#todaytodo"), {
            autoHide: true
        });
        var px = new SimpleBar(document.querySelector("#cominghere"), {
            autoHide: true
        });
        var px = new SimpleBar(document.querySelector("#comingtodo"), {
            autoHide: true
        });
        var px = new SimpleBar(document.querySelector("#todayhear"), {
            autoHide: true
        });
    </script>

    <script>
        (function() {
            var options = {
                series: [{{ $storage_limit }}],
                chart: {
                    height: 350,
                    type: 'radialBar',
                    offsetY: -20,
                    sparkline: {
                        enabled: true
                    }
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -90,
                        endAngle: 90,
                        track: {
                            background: "#e7e7e7",
                            strokeWidth: '97%',
                            margin: 5, // margin is in pixels
                        },
                        dataLabels: {
                            name: {
                                show: true
                            },
                            value: {
                                offsetY: -50,
                                fontSize: '20px'
                            }
                        }
                    }
                },
                grid: {
                    padding: {
                        top: -10
                    }
                },
                colors: ["#6FD943"],
                labels: ['Used'],
            };
            var chart = new ApexCharts(document.querySelector("#device-chart"), options);
            chart.render();
        })();
    </script>

<link rel="stylesheet" href="{{ asset('assets/css/plugins/main.css') }}">

<script src="{{ asset('assets/js/main.js') }}"></script>

<script>
    $(document).ready(function() {
        get_data();
    });

    function get_data() {
        var calender_type = $('#calender_type :selected').val();

        $('#calendar').removeClass('local_calender');
        $('#calendar').removeClass('goggle_calender');

        if (calender_type == undefined) {
            calender_type = 'local_calender';
        }

        $('#calendar').addClass(calender_type);
        var urls = $("#path_admin").val() + "/data/get_all_data";

        $.ajax({
            url: urls,
            method: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                'calender_type': calender_type
            },
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
                        events: data
                    });

                    calendar.render();
                })();
            }
        });
    }

    $(".fc-daygrid-event").on('click', function(e) {
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
                e.preventDefault();
                url
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

