@extends('layouts.app')
@section('page-title', __('Deal'))
<link rel="stylesheet" href="{{ asset('assets/css/plugins/dragula.min.css') }}">
@php
 $premission = [];
    $premission_arr = [];
    if (\Auth::user()->super_admin_employee == 1) {
        $premission = json_decode(\Auth::user()->permission_json);
        $premission_arr = get_object_vars($premission);
    }
@endphp

@section('action-button')
    @if (Auth::user()->super_admin_employee == 1 || array_search('manage crm', $premission_arr) || Auth::user()->type == 'advocate' ||  Auth::user()->type == 'company')
        <div class="row align-items-center mb-3">
            <div class="col-md-12 d-flex justify-content-sm-end">


                <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
                    <button class="btn btn-sm btn-primary mx-1 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ $pipeline ? $pipeline->name : '' }}
                    </button>
                    <div class="dropdown-menu">
                        @foreach ($pipelines as $pipe)
                            <a class="dropdown-item pipeline_id" data-id="{{ $pipe->id }}"
                                href="#">{{ $pipe->name }}</a>
                        @endforeach
                    </div>
                </div>


                <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
                    <a href="{{ route('deal.list') }}" class="btn btn-sm btn-primary mx-1">
                        <i class="ti ti-list" data-bs-toggle="tooltip" data-bs-original-title="{{ __('List View') }}">
                        </i>
                    </a>
                </div>

                <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
                    <a href="{{ route('deals.export') }}" class="btn btn-sm btn-primary mx-1" data-bs-toggle="tooltip"
                        data-title=" {{ __('Export') }}" title="{{ __('Export') }}">
                        <i class="ti ti-file-export"></i>
                    </a>
                </div>

                @if ( Auth::user()->type != 'company')

                    <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
                        <a href="#" class="btn btn-sm btn-primary mx-1" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="{{ __('Import') }}" data-size="md" data-ajax-popup="true"
                            data-title="{{ __('Import client CSV file') }}" data-url="{{ route('deals.file.import') }}">
                            <i class="ti ti-file-import text-white"></i>
                        </a>
                    </div>



                    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
                        <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="md"
                            data-title="Add Deal" data-url="{{ route('deal.create') }}" data-toggle="tooltip"
                            title="{{ __('Create New Deal') }}" data-bs-original-title="{{ __('Create New Deal') }}"
                            data-bs-placement="top" data-bs-toggle="tooltip">
                            <i class="ti ti-plus"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Deal') }}</li>
@endsection
@push('custom-script')
    <style>
        .btn-sm {
            --bs-btn-padding-y: 0.25rem;
            --bs-btn-padding-x: 0.5rem;
            --bs-btn-font-size: 0.76563rem;
            --bs-btn-border-radius: 4px;

        }
    </style>
@endpush
@section('content')
    <div class="row mb-3">
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-none rounded-0 border">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <div class="d-flex align-items-center">
                                <div class="theme-avtar bg-warning">
                                    <i class="ti ti-shopping-cart"></i>
                                </div>
                                <div class="ms-3">
                                    <!-- <small class="text-muted">{{ __('Statistics') }}</small> -->
                                    <h6 class="m-0">{{ __('Total Deals') }}</h6>
                                    <h4 class="m-0">{{ $cnt_deal['total'] ?? '  ' }}</h4>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-auto text-end">
                                </div> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-none rounded-0 border">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <div class="d-flex align-items-center">
                                <div class="theme-avtar bg-success">
                                    <i class="ti ti-users"></i>
                                </div>
                                <div class="ms-3">
                                    <!-- <small class="text-muted">{{ __('Statistics') }}</small> -->
                                    <h6 class="m-0">{{ __('This Month  Deals') }}</h6>
                                    <h4 class="m-0">{{ $cnt_deal['this_month'] ?? '' }}</h4>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-auto text-end">
                                </div> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-12">
            <div class="card shadow-none rounded-0 border">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <div class="d-flex align-items-center">
                                <div class="theme-avtar bg-danger">
                                    <i class="ti ti-report-money"></i>
                                </div>
                                <div class="ms-3">
                                    <!-- <small class="text-muted">{{ __('Statistics') }}</small> -->
                                    <h6 class="m-0">{{ __('This Week  Deals') }}</h6>
                                    <h4 class="m-0">{{ $cnt_deal['this_week']??''}}</h4>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-auto text-end">
                                </div> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-12">
            <div class="card shadow-none rounded-0 border">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto mb-3 mb-sm-0">
                            <div class="d-flex align-items-center">
                                <div class="theme-avtar bg-info">
                                    <i class="ti ti-report-money"></i>
                                </div>
                                <div class="ms-3">
                                    <!-- <small class="text-muted">{{ __('Statistics') }}</small> -->
                                    <h6 class="m-0">{{ __('Last 30 Days  Deals') }}</h6>
                                    <h4 class="m-0">{{ $cnt_deal['last_30days'] ?? '' }}</h4>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-auto text-end">
                                </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row ">
        <div class="col-sm-12">
            @if ($pipeline)
                @php
                    $stages = $pipeline->stages;
                    $json = [];
                    foreach ($stages as $stage) {
                        $json[] = 'kanban-blacklist-' . $stage->id;
                    }
                @endphp

                <div class="row kanban-wrapper horizontal-scroll-cards kanban-board"
                    data-containers='{!! json_encode($json) !!}' data-plugin="dragula">

                    @foreach ($stages as $stage)

                        @php $deals = $stage->deals() @endphp

                        <div class="col-md-3">
                            <div class="card shadow-none rounded-0 border">
                                <div class="card-header">
                                    <div class="float-end">
                                        <label class="btn btn-sm btn-primary btn-icon task-header">
                                            <span class="count text-white">{{ count($deals) }}</span>
                                        </label>
                                    </div>
                                    <h4 class="mb-0">{{ $stage->name }}</h4>
                                </div>
                                <div class="card-body kanban-box" id="kanban-blacklist-{{ $stage->id }}"
                                    data-id="{{ $stage->id }}">
                                    @foreach ($deals as $deal)
                                        @php $labels = $deal->labels() @endphp
                                        <div class="card shadow-none bg-transparent border rounded-0 mb-3"
                                            data-id="{{ $deal->id }}">
                                            <div class="pt-3 ps-3">
                                                @if ($labels)
                                                    @foreach ($labels as $label)
                                                        <span
                                                            class="badge bg-{{ $label->color }} p-1 px-3 rounded">{{ $label->name }}</span>
                                                    @endforeach
                                                @endif
                                                <div class="card-header border-0 pb-0 position-relative">
                                                    <h5>
                                                        <a href="{{ route('deal.show', \Crypt::encrypt($deal->id)) }}"
                                                            data-bs-whatever="{{ __('View Deal Details') }}"
                                                            data-bs-toggle="tooltip" title
                                                            data-bs-original-title="{{ __('Deal Detail') }}">
                                                            {{ $deal->name }}</a>
                                                    </h5>
                                                    @if (\Auth::user()->super_admin_employee == '1' || Auth::user()->type == 'company')
                                                        <div class="card-header-right">
                                                            <div class="btn-group card-option">
                                                                <button type="button" class="btn dropdown-toggle"
                                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                                    aria-expanded="false">
                                                                    <i class="ti ti-dots-vertical"></i>
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    @if (\Auth::user()->super_admin_employee == '1' )
                                                                        <a href="#" class="dropdown-item"
                                                                            data-ajax-popup="true" data-size="lg"
                                                                            data-title="Edit Deal"
                                                                            data-url="{{ route('deal.edit', $deal->id) }}"
                                                                            data-toggle="tooltip"
                                                                            title="{{ __('Edit Deal') }}"
                                                                            data-bs-original-title="{{ __('Edit Deal') }}"
                                                                            data-bs-placement="top"
                                                                            data-bs-toggle="tooltip">
                                                                            <i class="ti ti-edit"></i>
                                                                            <span>{{ __('Edit') }}</span>
                                                                        </a>

                                                                    @endif

                                                                    @if (\Auth::user()->super_admin_employee == '1' || Auth::user()->type == 'company')
                                                                        {!! Form::open([
                                                                            'method' => 'DELETE',
                                                                            'route' => ['deal.destroy', $deal->id],
                                                                            'id' => 'delete-form-' . $deal->id,
                                                                        ]) !!}
                                                                        <a href="#"
                                                                            class="dropdown-item bs-pass-para"
                                                                            data-id="{{ $deal['id'] }}"
                                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                            data-confirm-yes="delete-form-{{ $deal->id }}"
                                                                            title="{{ __('Delete') }}"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-placement="top">
                                                                            <i class="ti ti-archive"></i>
                                                                            <span> {{ __('Delete') }}</span>
                                                                        </a>
                                                                        {!! Form::close() !!}
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="card-body">
                                                    <p class="text-muted text-sm">
                                                        {{ count($deal->tasks) }}/{{ count($deal->complete_tasks) }}
                                                        {{ __('Task') }}</p>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <ul class="list-inline mb-0">
                                                            <li class="list-inline-item d-inline-flex align-items-center">
                                                                <i
                                                                    class="f-16 text-primary ti ti-report-money"></i>{{ \Auth::user()->priceFormat($deal->price) }}
                                                            </li>
                                                        </ul>
                                                        <div class="user-group">
                                                            @foreach ($deal->users as $user)
                                                                <a href="#" class=""
                                                                    data-original-title="{{ $user->name }}"
                                                                    data-toggle="tooltip">
                                                                    <img @if (!empty($user->avatar)) src="{{ asset('/storage/uploads/profile/' . $user->avatar) }}" @else src="{{ asset('/storage/uploads/profile/avatar.png') }}" @endif
                                                                        avatar="{{ $user->name }}" class="">
                                                                </a>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="col-md-12 text-center">
                    <h4>{{ __('No data available') }}</h4>
                </div>
            @endif
            <!-- [ sample-page ] end -->
        </div>
    </div>
@endsection
@push('custom-script')

    <script src="{{ asset('assets/js/plugins/dragula.min.js') }}"></script>
    @if ($pipeline)
        <script src="{{ asset('assets/js/plugins/dragula.min.js') }}"></script>
        <script>
            ! function(a) {
                "use strict";
                var t = function() {
                    this.$body = a("body")
                };
                t.prototype.init = function() {
                    a('[data-plugin="dragula"]').each(function() {
                        var t = a(this).data("containers"),
                            n = [];
                        if (t)
                            for (var i = 0; i < t.length; i++) n.push(a("#" + t[i])[0]);
                        else n = [a(this)[0]];
                        var r = a(this).data("handleclass");
                        r ? dragula(n, {
                            moves: function(a, t, n) {
                                return n.classList.contains(r)
                            }
                        }) : dragula(n).on('drop', function(el, target, source, sibling) {

                            var order = [];
                            $("#" + target.id + " > div").each(function() {
                                order[$(this).index()] = $(this).attr('data-id');
                            });

                            var id = $(el).attr('data-id');

                            var old_status = $("#" + source.id).data('status');
                            var new_status = $("#" + target.id).data('status');
                            var stage_id = $(target).attr('data-id');
                            var pipeline_id = '{{ $pipeline->id }}';

                            $("#" + source.id).parent().find('.count').text($("#" + source.id + " > div")
                                .length);
                            $("#" + target.id).parent().find('.count').text($("#" + target.id + " > div")
                                .length);

                            $.ajax({
                                url: '{{ route('deal.order') }}',
                                type: 'POST',
                                data: {
                                    deal_id: id,
                                    stage_id: stage_id,
                                    order: order,
                                    new_status: new_status,
                                    old_status: old_status,
                                    pipeline_id: pipeline_id,
                                    "_token": $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(data) {
                                    toastrs('Success', 'Card moved successfully', 'success');
                                },
                                error: function(data) {
                                    data = data.responseJSON;
                                    toastrs('{{ __('Error') }}', data.error, 'error')
                                }
                            });
                        });
                    })
                }, a.Dragula = new t, a.Dragula.Constructor = t
            }(window.jQuery),
            function(a) {
                "use strict";

                a.Dragula.init()

            }(window.jQuery);
        </script>

        <script>
            $(document).on("click", ".pipeline_id", function() {
                var pipeline_id = $(this).attr('data-id');

                $.ajax({
                    url: '{{ route('deal.change.pipeline') }}',
                    type: 'POST',
                    data: {
                        pipeline_id: pipeline_id,
                        "_token": $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        $('#change-pipeline').submit();
                        location.reload();
                    }
                });
            });
        </script>
    @endif

@endpush
