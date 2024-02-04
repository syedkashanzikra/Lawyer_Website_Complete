
@extends('layouts.app')

@section('page-title', __('Label'))

@section('action-button')
    @if(Auth::user()->super_admin_employee==1 || array_search("manage crm",$premission_arr))
        <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
            <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="md"
                data-title="Add Label" data-url="{{ route('label.create') }}" data-toggle="tooltip"
                title="{{ __('Create New Label') }}" data-bs-original-title="{{__('Create New Label')}}" data-bs-placement="top" data-bs-toggle="tooltip">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endif

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Label') }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-sm-12 col-md-10 col-xxl-8">
        <div class="p-3 card" style="margin-bottom: 24px;">
            <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                @php($i=0)
                @foreach($pipelines as $key => $pipeline)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($i==0) active @endif" id="pills-user-tab-1" data-bs-toggle="pill"
                                data-bs-target="#tab{{$key}}" type="button">{{$pipeline['name']}}
                        </button>
                    </li>
                    @php($i++)
                @endforeach
            </ul>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="tab-content" id="pills-tabContent">
                    @php($i=0)
                    @forelse($pipelines as $key => $pipeline)
                        <div class="tab-pane fade show @if($i==0) active @endif" id="tab{{$key}}" role="tabpanel" aria-labelledby="pills-user-tab-1">
                            <ul class="list-group sortable">
                                @foreach ($pipeline['labels'] as $label)
                                    <li class="list-group-item" data-id="{{$label->id}}">
                                        <span class="badge fix_badges bg-{{$label->color}} p-2 px-3 rounded">{{$label->name}}</span>

                                        <span class="float-end">

                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                    data-url="{{ route('label.edit', $label->id) }}" data-size="md"
                                                    data-ajax-popup="true" data-title="{{ __('Edit Label') }}"
                                                    title="{{ __('Edit') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top">
                                                    <i class="ti ti-edit "></i></a>
                                            </div>
                                            <div class="action-btn bg-light-secondary ms-2">
                                                <a href="#"
                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                    data-confirm-yes="delete-form-{{ $label->id }}"
                                                    title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                    data-bs-placement="top">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            </div>

                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'route' => ['label.destroy', $label->id],
                                                'id' => 'delete-form-' . $label->id,
                                            ]) !!}
                                            {!! Form::close() !!}

                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @php($i++)
                        @empty
                        <div class="col-md-12 text-center">
                            <h4>{{__('No data available')}}</h4>
                        </div>
                    @endforelse


                </div>

            </div>
        </div>
    </div>
</div>
@endsection
@push('custom-script')
<script src="{{ asset('public/assets/js/jquery-ui.js') }}"></script>
<script src="{{ asset('public/assets/js/repeater.js') }}"></script>
<script>
    $(function () {
        $(".sortable").sortable();
        $(".sortable").disableSelection();
        $(".sortable").sortable({
            stop: function () {
                var order = [];
                $(this).find('li').each(function (index, data) {
                    order[index] = $(data).attr('data-id');
                });

                $.ajax({
                    url: "{{route('leadStage.order')}}",
                    data: {order: order, _token: $('meta[name="csrf-token"]').attr('content')},
                    type: 'POST',
                    success: function (data) {
                    },
                    error: function (data) {
                        data = data.responseJSON;
                        toastr('Error', data.error, 'error')
                    }
                })
            }
        });
    });
</script>
@endpush
