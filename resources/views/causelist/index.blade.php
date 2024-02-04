@extends('layouts.app')

@section('page-title', __('Cause List'))

@section('action-button')
@can('create cause')
<div class="text-sm-end d-flex all-button-box justify-content-sm-end">
    <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="md" data-title="Add Cause"
        data-url="{{ route('cause.create') }}" data-toggle="tooltip" title="{{ __('Create New Cause') }}" data-bs-original-title="{{__('Create New Cause')}}" data-bs-placement="top" data-bs-toggle="tooltip">
        <i class="ti ti-plus"></i>
    </a>
</div>
@endcan
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">{{ __('Cause List') }}</li>
@endsection

@section('content')
<div class="row p-0">
    <div class="col-xl-12">
        <div class="">
            <div class="card-header card-body table-border-style">
                <h5></h5>
                <div class="table-responsive">
                    <table class="table dataTable data-table">
                        <thead>
                            <tr>
                                <th>{{ __('Function by - Name') }}</th>
                                <th>{{ __('Courts/Tribunal') }}</th>
                                <th>{{ __('Court Name') }}</th>
                                <th>{{ __('Circuit/Devision') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($causes as $caus)

                            <tr>
                                <td>
                                    <a href="#" class="btn btn-sm" data-url="{{ route('cause.show', $caus->id) }}" data-size="md" data-ajax-popup="true"
                                    data-title="{{ __('View CauseList') }}">
                                    {{$caus->causelist_by}} - {{ $caus->advocate_name }}
                                    </a>
                                </td>
                                <td> {{ $caus->getCourt->name }} </td>
                                <td>{{ $caus->highCourt ? $caus->highCourt->name : '-' }}</td>
                                <td>{{ $caus->getBench ? $caus->getBench->name : '-' }}</td>
                                <td>


                                    @can('delete cause')
                                    <div class="action-btn bg-light-secondary ms-2">
                                        <a href="#"
                                            class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                            data-confirm="{{ __('Are You Sure?') }}"
                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                            data-confirm-yes="delete-form-{{ $caus->id }}" title="{{ __('Delete') }}"
                                            data-bs-toggle="tooltip" data-bs-placement="top">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                    </div>
                                    @endcan
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['cause.destroy',$caus->id], 'id'
                                    => 'delete-form-'.$caus->id]) !!}
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
    @endsection

    @push('custom-script')
    <script>
        $(document).on('change', '#court', function() {
                var selected_opt = $(this).val();

                $.ajax({
                    url: "{{ route('get.highcourt') }}",
                    datType: 'json',
                    method: 'POST',
                    data: {
                        selected_opt: selected_opt
                    },
                    success: function(data) {
                        if (data.status == 1) {
                            $('#highcourt_div').removeClass('d-none');
                            $('#highcourt_div').empty();
                            $('#highcourt_div').append('<label for="highcourt" class="form-label">High Court</label> <select class="form-control" name="highcourt" id="highcourt"> </select>');
                            $('#highcourt').append('<option value="">{{ __("Please Select") }}</option>');

                            $.each(data.dropdwn, function(key, value) {
                                $('#highcourt').append('<option value="' + key + '">' + value +'</option>');
                            });

                        }else{
                            $('#highcourt_div').addClass('d-none').empty();
                            $('#bench_div').addClass('d-none').empty();
                        }

                    }
                })
            });

            $(document).on('change', '#highcourt', function() {
                var selected_opt = $(this).val();

                $.ajax({
                    url: "{{ route('get.bench') }}",
                    datType: 'json',
                    method: 'POST',
                    data: {
                        selected_opt: selected_opt
                    },
                    success: function(data) {

                        if (data.status == 1) {
                            $('#bench_div').removeClass('d-none');
                            $('#bench_div').empty();
                            $('#bench_div').append('<label for="bench" class="form-label">Circuit/Devision</label> <select class="form-control" name="bench" id="bench"> </select>');
                            $('#bench').append('<option value="">{{ __("Please Select") }}</option>');

                            $.each(data.dropdwn, function(key, value) {
                                $('#bench').append('<option value="' + key + '">' + value +'</option>');
                            });

                        }else{
                            $('#bench_div').addClass('d-none').empty();
                        }

                    }
                })
            });

            $(document).on('change', '#causelist_by', function() {
                $('#adv_label').html($(this).val())
            });
    </script>
    @endpush
