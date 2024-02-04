@extends('layouts.app')
@push('custom-script')
    <script>
        $(document).on('click', '.code', function () {
            var type = $(this).val();
            if (type == 'manual') {
                $('#manual').removeClass('d-none');
                $('#manual').addClass('d-block');
                $('#auto').removeClass('d-block');
                $('#auto').addClass('d-none');
            } else {
                $('#auto').removeClass('d-none');
                $('#auto').addClass('d-block');
                $('#manual').removeClass('d-block');
                $('#manual').addClass('d-none');
            }
        });

        $(document).on('click', '#code-generate', function () {
            var length = 10;
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            $('#auto-code').val(result);
        });
    </script>
@endpush
@section('page-title')
    {{__('Manage Coupons')}}
@endsection

@section('breadcrumb')

    <li class="breadcrumb-item active" aria-current="page">{{__('Coupons')}}</li>
@endsection

@section('action-button')
    @can('create coupon')
        <div class="float-end">
            <a href="#"  class="btn btn-sm btn-primary btn-icon" title="{{__('Create')}}" data-bs-toggle="tooltip" data-bs-placement="top" data-ajax-popup="true" data-title="{{__('Create New Coupon')}}" data-url="{{route('coupons.create')}}"><i class="ti ti-plus"></i></a>
        </div>
    @endcan
@endsection

@section('content')
<div class="row pt-0">
        <div class="col-xl-12">
    <div class="card shadow-none rounded-0">
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <table class="table dataTable">
                    <thead>
                    <tr>
                        <th> {{__('Name')}}</th>
                        <th> {{__('Code')}}</th>
                        <th> {{__('Discount (%)')}}</th>
                        <th> {{__('Limit')}}</th>
                        <th> {{__('Used')}}</th>
                        <th width="100px">{{__('Action')}} </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($coupons as $coupon)
                        <tr class="">
                            <td><a href="{{ route('coupons.show',$coupon->id) }}" class="btn btn-sm" title="{{__('Detail')}}">
                                {{ $coupon->name }}
                            </a></td>
                            <td>{{ $coupon->code }}</td>
                            <td>{{ $coupon->discount }}</td>
                            <td>{{ $coupon->limit }}</td>
                            <td>{{ $coupon->used_coupon() }}</td>
                            <td>
                                <div class="action-btn bg-light-secondary ms-2">
                                    <a href="{{ route('coupons.show',$coupon->id) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center" title="{{__('Detail')}}" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti ti-eye"></i></a>
                                </div>

                                @can('edit coupon')
                                    <div class="action-btn bg-light-secondary ms-2">
                                        <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" title="{{__('Edit')}}" data-bs-toggle="tooltip" data-bs-placement="top" data-url="{{ route('coupons.edit',$coupon->id) }}" data-ajax-popup="true" data-title="{{__('Edit Coupon')}}" data-size="md"><i class="ti ti-edit"></i></a>
                                    </div>
                                @endcan
                                @can('delete coupon')
                                <div class="action-btn bg-light-secondary ms-2">
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['coupons.destroy', $coupon->id],'id'=>'delete-form-'.$coupon->id]) !!}
                                        <a href="#" class="bs-pass-para mx-3 btn btn-sm d-inline-flex align-items-center"  data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="delete-form-{{$coupon->id}}" title="{{__('Delete')}}" data-bs-toggle="tooltip" data-bs-placement="top"><i class="ti ti-trash"></i></a>
                                        </div>
                                    {!! Form::close() !!}
                                @endcan
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
