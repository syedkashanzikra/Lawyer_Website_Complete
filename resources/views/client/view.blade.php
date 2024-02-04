@extends('layouts.app')

@section('page-title', __('Client Details'))

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Client Details') }}</li>
@endsection
@php
    $docfile = \App\Models\Utility::get_file('uploads/case_docs/');
    $filing_date = '';
    if (!empty($case->filing_date)) {
        $filing_date = date('d-m-Y', strtotime($case->filing_date));
    }

@endphp
@section('content')
  
<div class="col-xl-12">
    <div class="card shadow-none">
        <div class="card-header">
            <h5>{{__('Cases')}}</h5>
            <span class="d-block m-t-5">{{$user->name}}{{__('\'s Cases')}}</span>
        </div>
        <div class="card-header card-body table-border-style">
            <div class="table-responsive">
                <table class="table dataTable data-table">
                    <thead>
                        <tr>
                            <th>{{ __('#') }}</th>
                            <th>{{ __('Courts/Tribunal') }}</th>
                            <th>{{ __('Case No.') }}</th>
                            <th>{{ __('Year') }}</th>
                            <th>{{ __('TITLE') }}</th>
                            <th>{{ __('Advocate(s)') }}</th>
                            <th>{{ __('Court Room') }}</th>
                            <th>{{ __('Judges') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1; @endphp
                        @foreach ($cases as $key => $case)
                        <tr>
                            <td>{{ $i}}</td>@php $i++; @endphp
                            <td>{{ App\Models\CauseList::getCourtById($case->court) }}</td>
                            <td>{{ !empty($case->case_number) ? $case->case_number : ' ' }}</td>
                            <td>
                                {{ !empty($case->year) ? $case->year : '2023' }}
                            </td>
                            
                            <td>{{ $case->title }}</td>
                            <td>{{ App\Models\Advocate::getAdvocates($case->advocates) }}</td>
                            <td>{{ $case->court_room }}</td>
                            <td>{{ $case->judge }}</td>
                            <td>

                                        @can('view case')
                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="{{ route('cases.show', $case->id) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                    data-title="{{ __('View Cause') }}"
                                                    title="{{ __('View') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top"><i class="ti ti-eye "></i></a>
                                            </div>
                                        @endcan

                                        @can('edit case')
                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="{{ route('cases.edit', $case->id) }}"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                    title="{{ __('Edit') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top">
                                                    <i class="ti ti-edit "></i>
                                                </a>
                                            </div>
                                        @endcan

                                        @can('delete case')
                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="#"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                    data-confirm-yes="delete-form-{{ $case->id }}"
                                                    title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                    data-text="{{__('This action can not be undone. Do you want to continue?')}}"
                                                    data-bs-placement="top">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            </div>
                                        @endcan
                                        {!! Form::open([
                                            'method' => 'DELETE',
                                            'route' => ['cases.destroy', $case->id],
                                            'id' => 'delete-form-' . $case->id,
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
<div class="col-xl-12">
    <div class="card shadow-none rounded-0 border">
        <div class="row">
            <div class="col-xl-12">
                <div class="card-header">
                    <div class="row px-3">
                        <div class="col-xl-2">

                            <h5> {{__('Hearing')}} </h5>
                            <span class="d-block m-t-5">{{$user->name}}{{__('\'s Hearings')}}</span>
                        </div>
                        
                    </div>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table dataTable">
                            <thead>
                                <tr>
                                    <th>{{ __('#') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Remarks') }}</th>
                                    <th>{{ __('Case No.') }}</th>
                                    <th>{{ __('Advocate') }}</th>
                                    <th>{{ __('Order Sheet') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cases as $ke => $case)
                                @foreach ($case->hearings as $key => $hearing)
                                <tr>
                                    <td> {{ $key+1 }} </td>

                                    <td> {{date('d-m-Y ',strtotime($hearing->date))}} </td>
                                    <td> {{ $hearing->remarks }} </td>
                                    <td> {{ !empty($case->case_number) ? $case->case_number : ' ' }}</td>
                                    <td>{{ App\Models\Advocate::getAdvocates($case->advocates) }}</td>
                                    
                                    <td>
                                        @if (!empty($hearing->order_seet))
                                            <a href="{{$docfile.$hearing->order_seet}}" target="_blank">{{__('View')}}</a>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-btn bg-light-secondary ms-2">
                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                data-url="{{ route('hearing.edit', $hearing->id) }}" data-size="md"
                                                data-ajax-popup="true" data-title="{{ __('Edit') }}"
                                                title="{{ __('Edit') }}" data-bs-toggle="tooltip"
                                                data-bs-placement="top">
                                                <i class="ti ti-edit "></i>
                                            </a>
                                        </div>
                                        <div class="action-btn bg-light-secondary ms-2">
                                            <a href="#"
                                                class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                data-confirm="{{ __('Are You Sure?') }}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"
                                                data-confirm-yes="delete-form-{{ $hearing->id }}"
                                                title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                data-bs-placement="top">
                                                <i class="ti ti-trash"></i>
                                            </a>
                                        </div>
                                        {!! Form::open([
                                            'method' => 'DELETE',
                                            'route' => ['hearing.destroy', $hearing->id],
                                            'id' => 'delete-form-' . $hearing->id,
                                        ]) !!}
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                                @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-xl-12">
    <div class="card shadow-none rounded-0 border">
        <div class="row">
            <div class="col-xl-12">
                <div class="card-header">
                    <div class="row px-3">
                        <div class="col-xl-2">
                            <h5> {{__('Bills')}} </h5>
                            <span class="d-block m-t-5">{{$user->name}}{{__('\'s Bills')}}</span>

                        </div>
                        
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table dataTable-desc data-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Bill Number') }}</th>
                                    <th>{{ __('Bill From') }}</th>
                                    <th>{{ __('Date Of Reciept') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th width="100px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($bills as $bill)
                                    <tr>
                                        <td> <a href="{{ route('bills.show', $bill->id) }}" class="btn btn-sm">
                                            {{ $bill->bill_number }}
                                        </a> </td>
                                        <td> {{ $bill->bill_from }} </td>
                                        <td> {{date('d-m-Y',strtotime($bill->due_date))}}</td>
                                        <td> {{ $bill->status }} </td>
                                        <td>
                                            @can('view bill')
                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="{{ route('bills.show', $bill->id) }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        title="{{ __('View') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"><i
                                                                class="ti ti-eye "></i></a>
                                                </div>
                                            @endcan

                                            @can('edit bill')
                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="{{ route('bills.edit', $bill->id) }}"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                        title="{{ __('Edit') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"><i
                                                                class="ti ti-edit "></i></a>
                                                </div>
                                            @endcan

                                            @can('delete bill')
                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="#"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                        data-confirm-yes="delete-form-{{ $bill->id }}"
                                                        title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                        data-text="{{__('This action can not be undone. Do you want to continue?')}}"
                                                        data-bs-placement="top">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                </div>
                                            @endcan


                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'route' => ['bills.destroy', $bill->id],
                                                'id' => 'delete-form-' . $bill->id,
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
<div class="col-xl-12">
    <div class="card shadow-none rounded-0 border">
        <div class="row">
            <div class="col-xl-12">
                <div class="card-header">
                    <div class="row px-3">
                        <div class="col-xl-2">
                            <h5> {{__('Fees')}} </h5>
                            <span class="d-block m-t-5">{{$user->name}}{{__('\'s Fees')}}</span>
                            
                        </div>
                        
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table dataTable data-table">
                        <thead>
                            <tr>
                                <th>{{ __('Case') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Particulars') }}</th>
                                <th>{{ __('Fee Received ') }}</th>
                                <th>{{ __('Payment Method') }}</th>
                                <th>{{ __('Team Member') }}</th>
                                <th width="100px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fees as $expense)
                            <tr>
                                <td><a href="#" class="btn btn-sm" data-url="{{ route('fee-receive.show', $expense->id) }}" data-size="md"
                                    data-ajax-popup="true" data-title="{{ __(" View Fee") }}">

                                    {{ App\Models\Cases::getCasesById($expense->case) }}
                                </a></td>
                                <td>{{date('d-m-Y h:i',strtotime($expense->date))}}</td>
                                <td>{{ $expense->particulars }}</td>
                                <td>{{ $expense->money }}</td>
                                <td>{{ $expense->method }}</td>
                                <td>{{ App\Models\User::getTeams($expense->member) }}</td>
                                <td>
                                    @can('view feereceived')
                                    <div class="action-btn bg-light-secondary ms-2">
                                        <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                            data-url="{{ route('fee-receive.show', $expense->id) }}" data-size="md"
                                            data-ajax-popup="true" data-title="{{ __(" View Fee") }}"
                                            title="{{ __('View Fee') }}" data-bs-toggle="tooltip"
                                            data-bs-placement="top"><i
                                                    class="ti ti-eye "></i></a>
                                    </div>
                                    @endcan
                                    @can('edit feereceived')
                                    <div class="action-btn bg-light-secondary ms-2">
                                        <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                            data-url="{{ route('fee-receive.edit', $expense->id) }}" data-size="md"
                                            data-ajax-popup="true" data-title="{{ __('Edit Fee') }}"
                                            title="{{ __('Edit ') }}" data-bs-toggle="tooltip"
                                            data-bs-placement="top"><i
                                                    class="ti ti-edit "></i>
                                        </a>
                                    </div>
                                    @endcan
                                    @can('delete feereceived')
                                    <div class="action-btn bg-light-secondary ms-2">
                                        <a href="#"
                                            class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                            data-confirm="{{ __('Are You Sure?') }}"
                                            data-confirm-yes="delete-form-{{ $expense->id }}" title="{{ __('Delete') }}"
                                            data-bs-toggle="tooltip" data-bs-placement="top">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                    </div>
                                    @endcan
                                    {!! Form::open([
                                    'method' => 'DELETE',
                                    'route' => ['fee-receive.destroy', $expense->id],
                                    'id' => 'delete-form-' . $expense->id,
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
