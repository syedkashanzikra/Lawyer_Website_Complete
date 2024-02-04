@extends('layouts.app')

@section('page-title', __('My Today\'s Case Diary'))

@section('action-button')
@can('manage diary')
<div class="text-sm-end d-flex all-button-box justify-content-sm-end">
    <a href="{{ route('calendar.index') }}" class="btn btn-md btn-primary mx-1" data-bs-toggle="tooltip"
        data-bs-placement="top" data-toggle="tooltip" data-title="{{__('Calendar View')}}"
        data-bs-original-title="{{__('Calendar View')}}">
        {{__('Calendar View  ')}}
        <i class="ti ti-calendar"></i>
    </a>
</div>
@endcan
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">{{ __('My Today\'s Case Diary') }}</li>
@endsection

@section('content')
<div class="row p-0 g-0">
    <div class="col-sm-12"></div>
        <div class="mt-2 " id="multiCollapseExample1">
            <div class="card shadow-none border-bottom">
                <div class="card-body p-2 pb-3 rounded-0">

                    <form method="GET" action="{{route('casediary.index')}}" accept-charset="UTF-8"
                        id="customer_submit"> @csrf
                        <div class="row d-flex align-items-center justify-content-end">
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                                <div class="btn-box">
                                    <label for="from" class="form-label">{{__('From:')}}</label>
                                    <input type="date" name="from" class="form-control">


                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                                <div class="btn-box">
                                    <label for="to" class="form-label">{{__('To:')}}</label>
                                    <input type="date" class="form-control" name="to">


                                </div>
                            </div>

                            <div class="col-auto float-end ms-2 mt-4">

                                <a href="#" class="btn btn-sm btn-primary"
                                    onclick="document.getElementById('customer_submit').submit(); return false;"
                                    data-toggle="tooltip" data-original-title="apply">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row p-0">

    <div class="col-xl-12">
        <div class="card shadow-none">
            <div class="card-header">
                <h5>{{__('Cases')}}</h5>
                <span class="d-block m-t-5">{{__('Today\'s Cases')}}</span>
            </div>
            <div class="card-header card-body table-border-style">
                <div class="table-responsive">
                    <table class="table dataTable data-table">
                        <thead>
                            <tr>
                                <th>{{ __('#') }}</th>
                                <th>{{ __('Courts/Tribunal') }}</th>
                                <th>{{ __('Case') }}</th>
                                <th>{{ __('Client(s) / Advocate(s)') }}</th>
                                <th>{{ __('Team Member') }}</th>
                                <th>{{ __('Court Hall') }}</th>
                                <th>{{ __('Judges') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cases as $key => $case)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <a href="{{ route('cases.show', $case->id) }}" class="btn btn-sm" >
                                        {{ App\Models\CauseList::getCourtById($case->court) }} -
                                        {{ App\Models\CauseList::getHighCourtById($case->highcourt) == '-' ?
                                        $case->casenumber : App\Models\CauseList::getHighCourtById($case->highcourt) }}
                                        - {{ App\Models\CauseList::getBenchById($case->bench) }}
                                    </a>

                                </td>
                                <td>{{ $case->priority }}</td>
                                <td>{{ App\Models\Advocate::getAdvocates($case->your_advocates) }}</td>
                                <td>{{ strlen(App\Models\User::getTeams($case->your_team)) > 25 ?
                                    substr(App\Models\User::getTeams($case->your_team), 0, 25) . '...' :
                                    App\Models\User::getTeams($case->your_team) }}
                                </td>
                                <td>{{ $case->court_hall }}</td>
                                <td>{{ $case->before_judges }}</td>

                            </tr>
                            @endforeach


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow-none border-end">
                <div class="card-header">
                    <h5>{{__('To-Dos')}}</h5>
                    <span class="d-block m-t-5">{{__('Today\'s Tasks')}}</span>
                </div>
                <div class="card-header card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table dataTable2 data-table">
                            <thead>
                                <tr>
                                    <th>{{ __('#') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Due Date & Time') }}</th>
                                    <th>{{ __('Case') }}</th>
                                    <th>{{ __('Assigned by') }}</th>
                                    <th>{{ __('Assigned to') }}</th>
                                </tr>

                            </thead>
                            <tbody>
                                @foreach ($todos as $key => $todo)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm" data-url="{{ route('to-do.show', $todo['id']) }}"
                                            data-size="md" data-ajax-popup="true" data-title="{{ __(" View ToDo") }}">
                                            {{ strlen($todo['description']) > 20 ? substr($todo['description'], 0, 20) .
                                            '...' : $todo['description'] }}
                                        </a>

                                    <td>{{ $todo['start_date'] }}</td>
                                    <td>{{ strlen(App\Models\Cases::getCasesById($todo['relate_to'])) > 20 ?
                                        substr(App\Models\Cases::getCasesById($todo['relate_to']), 0, 20) . '...' :
                                        App\Models\Cases::getCasesById($todo['relate_to']) }}
                                    </td>
                                    <td>{{ App\Models\User::find($todo['assign_by'])['name'] }}</td>
                                    <td>{{ strlen(App\Models\User::getTeams($todo['assign_to'])) > 20 ?
                                        substr(App\Models\User::getTeams($todo['assign_to']), 0, 20) . '...' :
                                        App\Models\User::getTeams($todo['assign_to']) }}
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
        @endsection
