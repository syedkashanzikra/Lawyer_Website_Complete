@extends('layouts.app')

@section('page-title', __('To-Dos'))

@section('action-button')
    @can('create todo')
        <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
            <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="md" data-title="Add To-Do"
                data-url="{{ route('to-do.create') }}" data-toggle="tooltip" title="{{ __('Create New To-Do') }}" data-bs-original-title="{{__('Create New To-Do')}}" data-bs-placement="top" data-bs-toggle="tooltip">
                <i class="ti ti-plus"></i>
            </a>
        </div>

    @endcan
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('To-Dos') }}</li>
@endsection

@section('content')
<div class="row p-0 g-0 justify-content-center">
    <div class=" border-bottom">
        <div class="card shadow-none rounded-0 border-bottom">
            <div class="card-body">

                <div class="d-flex align-items-center justify-content-end">
                    <span> {{ __('Priority: ') }} </span>
                    <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                        <form action="{{ route('to-do.index') }}" method="GET" id="priority-form">
                            <select name="filter" class="form-control select" id="priorities">
                                @foreach ($priorities as $priority)
                                    <option value="{{ $priority }}" {{ (isset(request()->filter) && request()->filter == $priority) ? 'selected' : '' }}>{{ $priority }}</option>
                                @endforeach
                            </select>
                        </form>

                    </div>

                </div>

            </div>
        </div>

        <div class="p-2 border-bottom">
            <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-user-tab-1" data-bs-toggle="pill"
                        data-bs-target="#pills-user-1" type="button">{{ __('All') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-user-tab-2" data-bs-toggle="pill" data-bs-target="#pills-user-2"
                        type="button">{{ __('Pending') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-user-tab-3" data-bs-toggle="pill" data-bs-target="#pills-user-3"
                        type="button">{{ __('Upcoming') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-user-tab-4" data-bs-toggle="pill" data-bs-target="#pills-user-4"
                        type="button">{{ __('Completed') }}</button>
                </li>
            </ul>
        </div>
        <div class="card shadow-none bg-transparent">
            <div class="">
                <div class="tab-content table-border-style" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-user-1" role="tabpanel"
                        aria-labelledby="pills-user-tab-1 table-responsive">
                        <table class="table dataTable data-table ">
                            <thead>
                                <tr>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Due Date') }}</th>
                                    <th>{{ __('Assigned by') }}</th>
                                    <th>{{ __('Assign to') }}</th>
                                    <th width="100px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($todos as $todo)
                                <tr>
                                    <td>
                                        <a href="#" class="btn btn-sm" data-url="{{ route('to-do.show', $todo->id) }}" data-size="md" data-ajax-popup="true"
                                            data-title="{{ __(" View ToDo") }}">
                                            {{ strlen($todo->description) > 20 ? substr($todo->description, 0, 20) . '...' :
                                            $todo->description }}
                                        </a>
                                    </td>
                                    <td>{{ $todo->due_date }}</td>

                                    <td>{{ $todo->assignedByUser->name}}</td>

                                    <td>
                                        @php
                                            $assign_to = App\Models\User::getTeams($todo->assign_to);
                                        @endphp

                                        {{ strlen($assign_to) > 20 ? substr($assign_to, 0, 20) . '...' : $assign_to }}

                                    </td>
                                    <td>



                                        @can('edit todo')

                                            <div class="action-btn  disabled-form-switch">
                                                <a href="#" data-size="md" data-url="{{ route('to-do.status', $todo->id) }}" data-ajax-popup="true"
                                                    class="action-item" data-title="{{$todo->status == 1 ? __('Mark Completed?') : __('Completed') }}" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="" data-bs-original-title="{{$todo->status == 1 ? __('Mark Completed?') : __('Completed') }}">
                                                    @if ($todo->status==0)
                                                        <input type="checkbox" class="form-check-input " disabled="disabled" name="status" id="{{ $todo->id }}" {{ $todo->status==0 ? 'checked' : '' }}>
                                                    @else

                                                    <i class="ti ti-clock text-warning"></i>
                                                    @endif
                                                    <label class="form-check-label" for="{{ $todo->id }}"></label>
                                                </a>
                                            </div>
                                        @endcan

                                        @can('view todo')
                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                    data-url="{{ route('to-do.show', $todo->id) }}" data-size="md"
                                                    data-ajax-popup="true" data-title="{{ __(" View ToDo") }}"
                                                    title="{{ __('View ToDo') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top"><i class="ti ti-eye "></i></a>
                                            </div>
                                        @endcan

                                        @can('edit todo')
                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                    data-url="{{ route('to-do.edit', $todo->id) }}" data-size="md"
                                                    data-ajax-popup="true" data-title="{{ __('Edit To-Do') }}"
                                                    title="{{ __('Edit To-Do') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top"><i class="ti ti-edit "></i></a>
                                            </div>
                                        @endcan

                                        @can('delete todo')
                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="#"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                    data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                    data-confirm-yes="delete-form-{{ $todo['id'] }}"
                                                    title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            </div>
                                        @endcan
                                        {!! Form::open([
                                        'method' => 'DELETE',
                                        'route' => ['to-do.destroy', $todo['id']],
                                        'id' => 'delete-form-' . $todo['id'],
                                        ]) !!}
                                        {!! Form::close() !!}

                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane fade " id="pills-user-2" role="tabpanel"
                        aria-labelledby="pills-user-tab-2 table-responsive">
                        <table class="table dataTable2 data-table ">
                            <thead>
                                <tr>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Due Date') }}</th>
                                    {{-- <th>{{ __('Relate to') }}</th> --}}
                                    <th>{{ __('Assigned by') }}</th>
                                    <th>{{ __('Assign to') }}</th>
                                    <th width="100px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pending_todo as $todo)
                                <tr>
                                    <td>{{ strlen($todo['description']) > 20 ? substr($todo['description'], 0, 20) . '...' : $todo['description'] }}</td>

                                    <td>{{ $todo['due_date'] }}</td>

                                    {{-- <td>{{ strlen(App\Models\Cases::getCasesById($todo['relate_to'])) > 20 ?
                                        substr(App\Models\Cases::getCasesById($todo['relate_to']), 0, 20) . '...' :
                                        App\Models\Cases::getCasesById($todo['relate_to']) }}</td> --}}

                                    <td>{{ $todo['assign_by'] }}</td>

                                    <td>
                                        @php
                                            $assign_to = App\Models\User::getTeams($todo['assign_to']);
                                        @endphp
                                        {{ strlen($assign_to) > 20 ? substr($assign_to, 0, 20) . '...' : $assign_to }}
                                    </td>
                                    <td>
                                        @can('edit todo')
                                            <div class="action-btn  disabled-form-switch">
                                                <a href="#" data-size="md"
                                                    data-url="{{ route('to-do.status', $todo['id']) }}"
                                                    data-ajax-popup="true" class="action-item"
                                                    data-title="{{$todo['status'] == 1 ? __('Mark Completed?') : __('Completed') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title=""
                                                    data-bs-original-title="{{$todo['status'] == 1 ? __('Mark Completed?') : __('Completed') }}">


                                                    @if ($todo['status'] == 0)
                                                        <input type="checkbox" class="form-check-input" disabled="disabled" name="status" id="{{ $todo['id'] }}" {{ $todo['status']==0 ? 'checked' : '' }}>
                                                    @else

                                                        <i class="ti ti-clock text-warning"></i>
                                                    @endif

                                                    <label class="form-check-label" for="{{ $todo['id'] }}"></label>
                                                </a>
                                            </div>
                                        @endcan
                                        @can('edit todo')
                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                    data-url="{{ route('to-do.edit', $todo['id']) }}" data-size="md"
                                                    data-ajax-popup="true" data-title="{{ __('Edit To-Do') }}"
                                                    title="{{ __('Edit To-Do') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top"><i class="ti ti-edit "></i></a>
                                            </div>
                                        @endcan
                                        @can('delete todo')
                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="#"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                    data-confirm-yes="delete-form-{{ $todo['id'] }}"
                                                    title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            </div>
                                        @endcan
                                        {!! Form::open([
                                        'method' => 'DELETE',
                                        'route' => ['to-do.destroy', $todo['id']],
                                        'id' => 'delete-form-' . $todo['id'],
                                        ]) !!}
                                        {!! Form::close() !!}

                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane fade " id="pills-user-3" role="tabpanel"
                        aria-labelledby="pills-user-tab-3 table-responsive">
                        <table class="table dataTable3 data-table ">
                            <thead>
                                <tr>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Due Date') }}</th>
                                    {{-- <th>{{ __('Relate to') }}</th> --}}
                                    <th>{{ __('Assigned by') }}</th>
                                    <th>{{ __('Assign to') }}</th>
                                    <th width="100px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($upcoming_todo as $todo)
                                <tr>
                                    <td>{{ strlen($todo['description']) > 20 ? substr($todo['description'], 0, 20) .
                                        '...' : $todo['description'] }}</td>
                                    <td>{{ $todo['due_date'] }}</td>

                                    {{-- <td>{{ strlen(App\Models\Cases::getCasesById($todo['relate_to'])) > 20 ?
                                        substr(App\Models\Cases::getCasesById($todo['relate_to']), 0, 20) . '...' :
                                        App\Models\Cases::getCasesById($todo['relate_to']) }}</td> --}}

                                    <td>{{ $todo['assign_by'] }}</td>

                                    <td>
                                        @php
                                            $assign_to = App\Models\User::getTeams($todo['assign_to']);
                                        @endphp
                                        {{ strlen($assign_to) > 20 ? substr($assign_to, 0, 20) . '...' : $assign_to }}
                                    </td>


                                    <td>
                                        @can('edit todo')
                                            <div class="action-btn  disabled-form-switch">
                                                <a href="#" data-size="md"
                                                    data-url="{{ route('to-do.status', $todo['id']) }}"
                                                    data-ajax-popup="true" class="action-item"
                                                    data-title="{{$todo['status'] == 1 ? __('Mark Completed?') : __('Completed') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title=""
                                                    data-bs-original-title="{{$todo['status'] == 1 ? __('Mark Completed?') : __('Completed') }}">
                                                    @if ($todo['status']==0)
                                                    <input type="checkbox" class="form-check-input" disabled="disabled" name="status" id="{{ $todo['id'] }}" {{
                                                        $todo['status']==0 ? 'checked' : '' }}>
                                                    @else

                                                    <i class="ti ti-clock text-warning"></i>
                                                    @endif
                                                    <label class="form-check-label" for="{{ $todo['id'] }}"></label>
                                                </a>
                                            </div>
                                        @endcan

                                        @can('edit todo')
                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                    data-url="{{ route('to-do.edit', $todo['id']) }}" data-size="md"
                                                    data-ajax-popup="true" data-title="{{ __('Edit To-Do') }}"
                                                    title="{{ __('Edit To-Do') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top"><i class="ti ti-edit "></i></a>
                                            </div>
                                        @endcan
                                        @can('delete todo')
                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="#"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                    data-confirm-yes="delete-form-{{ $todo['id'] }}"
                                                    title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            </div>
                                        @endcan
                                        {!! Form::open([
                                        'method' => 'DELETE',
                                        'route' => ['to-do.destroy', $todo['id']],
                                        'id' => 'delete-form-' . $todo['id'],
                                        ]) !!}
                                        {!! Form::close() !!}

                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane fade " id="pills-user-4" role="tabpanel"
                        aria-labelledby="pills-user-tab-4 table-responsive">
                        <table class="table dataTable4 data-table ">
                            <thead>
                                <tr>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Due Date') }}</th>
                                    {{-- <th>{{ __('Relate to') }}</th> --}}
                                    <th>{{ __('Assigned by') }}</th>
                                    <th>{{ __('Assign to') }}</th>
                                    <th>{{ __('Completed By') }}</th>
                                    <th>{{ __('Completed At') }}</th>
                                    <th width="100px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($complted as $todo)
                                <tr>
                                    <td>{{ strlen($todo->description) > 20 ? substr($todo->description, 0, 20) . '...' :
                                        $todo->description }}</td>
                                    <td>{{ $todo->due_date }}</td>
                                    {{-- <td>{{ strlen(App\Models\Cases::getCasesById($todo['relate_to'])) > 20 ?
                                        substr(App\Models\Cases::getCasesById($todo['relate_to']), 0, 20) . '...' :
                                        App\Models\Cases::getCasesById($todo['relate_to']) }}</td> --}}

                                    <td>{{ $todo->assignedByUser->name}}</td>

                                    <td>
                                        @php
                                            $assign_to = App\Models\User::getTeams($todo->assign_to);
                                        @endphp
                                        {{ strlen($assign_to) > 20 ? substr($assign_to, 0, 20) . '...' : $assign_to }}
                                    </td>

                                    <td> {{ App\Models\User::find($todo->completed_by)->name }} </td>
                                    <td>{{ $todo->completed_at }}</td>
                                    <td>
                                        @can('delete todo')
                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="#"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                    data-confirm-yes="delete-form-{{ $todo->id }}"
                                                    title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            </div>
                                        @endcan
                                        {!! Form::open([
                                        'method' => 'DELETE',
                                        'route' => ['to-do.destroy', $todo->id],
                                        'id' => 'delete-form-' . $todo->id,
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
@endsection
@push('custom-script')
<script>
    $('#priorities').on('change',function(){
        this.form.submit();

    })
</script>
@endpush

