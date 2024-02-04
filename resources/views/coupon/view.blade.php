@extends('layouts.app')

@section('page-title')
{{__('Coupon Detail')}}
@endsection

@section('breadcrumb')

<li class="breadcrumb-item active" aria-current="page">{{__('Coupon Detail')}}</li>
@endsection

@section('content')
<div class="row p-0">
    <div class="col-xl-12">
        <div class="card shadow-none">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table dataTable">
                        <thead>
                            <tr>
                                <th> {{__('User')}}</th>
                                <th> {{__('Date')}}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($userCoupons as $userCoupon)
                            <tr>
                                <td>{{ !empty($userCoupon->userDetail)?$userCoupon->userDetail->name:'' }}</td>
                                <td>{{ $userCoupon->created_at }}</td>
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
