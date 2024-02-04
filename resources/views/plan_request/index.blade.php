@extends('layouts.app')

@section('page-title')
{{__('Plan Request')}}
@endsection

@section('breadcrumb')

<li class="breadcrumb-item active" aria-current="page">{{__('Plan Request')}}</li>
@endsection

@section('content')
<div class="row pt-0">

    <div class="col-xl-12">
        <div class="">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table dataTable">
                        <thead>
                            <tr>
                                <th>{{__('User Name')}}</th>
                                <th>{{__('Plan Name')}}</th>
                                <th>{{__('Max users')}}</th>
                                <th>{{__('Max advocates')}}</th>
                                <th>{{__('DURATION')}}</th>
                                <th>{{__('Date')}}</th>
                                <th width="100px">{{__('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                                @foreach($plan_requests as $prequest)
                                    <tr>
                                        <td>
                                            <div class="font-style font-weight-bold">{{ $prequest->user->name }}</div>
                                        </td>
                                        <td>
                                            <div class="font-style font-weight-bold">{{ $prequest->plan->name }}</div>
                                        </td>
                                        <td>
                                            <div class="font-weight-bold">{{ $prequest->plan->max_users }}</div>
                                        </td>
                                         <td>
                                            <div class="font-weight-bold">{{ $prequest->plan->max_advocates }}</div>
                                        </td>
                                        <td>
                                            <div class="font-style font-weight-bold">{{ $prequest->duration }}</div>
                                        </td>
                                        <td>{{ \App\Models\User::DateFormat($prequest->created_at,true) }}</td>
                                        <td>
                                            <div>
                                                <a href="{{route('response.request',[$prequest->id,1])}}"
                                                    title="{{__('Accept')}}" data-bs-toggle="tooltip"
                                                    class="action-btn bg-light-secondary ms-2 text-dark">
                                                    <i class="ti ti-check"></i>
                                                </a>
                                                <a href="{{route('response.request',[$prequest->id,0])}}"
                                                    title="{{__('Delete')}}" data-bs-toggle="tooltip"
                                                    class="action-btn bg-light-secondary ms-2 text-dark">
                                                    <i class="ti ti-x"></i>
                                                </a>
                                            </div>
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
@endsection
