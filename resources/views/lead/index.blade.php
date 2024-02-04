@extends('layouts.app')

@section('page-title', __('Lead'))
<link rel="stylesheet" href="{{ asset('assets/css/plugins/dragula.min.css') }}">
@section('action-button')
    @php
        $premission = [];
        $premission_arr = [];
        if (\Auth::user()->super_admin_employee == 1) {
            $premission = json_decode(\Auth::user()->permission_json);
            $premission_arr = get_object_vars($premission);
        }
    @endphp
    @if (
        (Auth::user()->super_admin_employee == 1 && array_search('manage crm', $premission_arr)) ||
            Auth::user()->type == 'company')
        <div class="row align-items-center mb-3">
            <div class="col-md-12 d-flex justify-content-sm-end">

                    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
                        <button class="btn btn-sm btn-primary mx-1 dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
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
                    <a href="{{ route('lead.grid') }}" class="btn btn-sm btn-primary mx-1">
                        <i class="ti ti-layout-grid" data-bs-toggle="tooltip"
                            data-bs-original-title="{{ __('Grid View') }}">
                        </i>
                    </a>
                </div>

                <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
                    <a href="{{ route('leads.export') }}" class="btn btn-sm btn-primary mx-1" data-bs-toggle="tooltip"
                        data-title=" {{ __('Export') }}" title="{{ __('Export') }}">
                        <i class="ti ti-file-export"></i>
                    </a>
                </div>

                @if ( Auth::user()->type != 'company')

                    <div class="text-sm-end d-flex all-button-box justify-content-sm-end">

                        <a href="#" class="btn btn-sm btn-primary mx-1" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="{{ __('Import') }}" data-size="md" data-ajax-popup="true"
                            data-title="{{ __('Import client CSV file') }}" data-url="{{ route('leads.file.import') }}">
                            <i class="ti ti-file-import text-white"></i>
                        </a>
                    </div>


                    <div class="text-end d-flex all-button-box justify-content-md-end justify-content-center">
                        <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="md"
                            data-title="Add Lead" data-url="{{ route('lead.create') }}" data-toggle="tooltip"
                            title="{{ __('Create New Lead') }}" data-bs-original-title="{{ __('Create New Lead') }}"
                            data-bs-placement="top" data-bs-toggle="tooltip">
                            <i class="ti ti-plus"></i>
                        </a>
                    </div>
                @endif

            </div>
        </div>
    @endif



    @endsection{{--  --}}

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Lead') }}</li>
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
    <div class="row">
        <div class="col-sm-12">
            @if ($pipeline)

                @php
                    $lead_stages = $pipeline->leadStages;

                    $json = [];
                    foreach ($lead_stages as $lead_stage) {
                        $json[] = 'kanban-blacklist-' . $lead_stage->id;
                    }
                @endphp
                <div class="row  kanban-wrapper horizontal-scroll-cards kanban-board"
                    data-containers='{!! json_encode($json) !!}' data-plugin="dragula">
                    @foreach ($lead_stages as $lead_stage)

                        @php $leads = $lead_stage->lead();  @endphp
                        <div class="col-md-3">
                            <div class="card border rounded-0">
                                <div class="card-header">
                                    <div class="float-end">
                                        <label class="btn btn-sm btn-primary btn-icon task-header"
                                            style="--bs-btn-padding-y: 0.25rem;">
                                            <span class="count text-white">{{ count($leads) }}</span>
                                        </label>
                                    </div>
                                    <h4 class="mb-0">{{ $lead_stage->name }}</h4>
                                </div>
                                <div class="card-body kanban-box" id="kanban-blacklist-{{ $lead_stage->id }}"
                                    data-id="{{ $lead_stage->id }}">
                                    @foreach ($leads as $lead)
                                        @php $labels = $lead->labels() @endphp
                                        <div
                                            class="card shadow-none bg-transparent border rounded-0 mb-3"data-id="{{ $lead->id }}">
                                            <div class="pt-3 ps-3">
                                                @if ($labels)
                                                    @foreach ($labels as $label)
                                                        <span
                                                            class="badge rounded-pill bg-{{ $label->color }} ml-1">{{ $label->name }}</span>
                                                    @endforeach
                                                @endif
                                                <div class="card-header border-0 pb-0 position-relative">
                                                    <h5>
                                                        <a href="{{ route('lead.show', \Crypt::encrypt($lead->id)) }}"
                                                            data-bs-whatever="{{ __('View Lead Details') }}"
                                                            data-bs-toggle="tooltip" title
                                                            data-bs-original-title="{{ __('Lead Detail') }}">{{ $lead->name }}</a>
                                                    </h5>
                                                    <div class="card-header-right">
                                                        <div class="btn-group card-option">
                                                            <button type="button" class="btn dropdown-toggle"
                                                                data-bs-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                <i class="ti ti-dots-vertical"></i>
                                                            </button>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                @if (!$lead->is_active)
                                                                    <a href="#" class="table-action">
                                                                        <i class="ti ti-lock"></i>
                                                                    </a>
                                                                @else
                                                                    @if (\Auth::user()->super_admin_employee == 1 )
                                                                        <a href="#" class="dropdown-item"
                                                                            data-ajax-popup="true" data-size="md"
                                                                            data-title="Edit Lead"
                                                                            data-url="{{ route('lead.edit', $lead->id) }}"
                                                                            data-toggle="tooltip"
                                                                            title="{{ __('Edit') }}"
                                                                            data-bs-original-title="{{ __('Edit') }}"
                                                                            data-bs-placement="top"
                                                                            data-bs-toggle="tooltip">
                                                                            <i class="ti ti-edit"></i>
                                                                            <span>{{ __('Edit') }}</span>
                                                                        </a>
                                                                    @endif

                                                                    @if (\Auth::user()->super_admin_employee == 1 || Auth::user()->type == 'company')
                                                                        <a href="#" class="dropdown-item"
                                                                            data-ajax-popup="true" data-size="md"
                                                                            data-title="Add Lead"
                                                                            data-url="{{ route('lead.label', $lead->id) }}"
                                                                            data-toggle="tooltip"
                                                                            title="{{ __('Add Label') }}"
                                                                            data-bs-original-title="{{ __('Add Label') }}"
                                                                            data-bs-placement="top"
                                                                            data-bs-toggle="tooltip">
                                                                            <i class="ti ti-sticker"></i>
                                                                            <span>{{ __('Add Label') }}</span>
                                                                        </a>
                                                                    @endif

                                                                    @if (\Auth::user()->super_admin_employee == 1 || Auth::user()->type == 'company')
                                                                        {!! Form::open([
                                                                            'method' => 'DELETE',
                                                                            'route' => ['lead.destroy', $lead->id],
                                                                            'id' => 'delete-form-' . $lead->id,
                                                                        ]) !!}
                                                                        <a href="#"
                                                                            class="dropdown-item bs-pass-para"
                                                                            data-id="{{ $lead['id'] }}"
                                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                            data-confirm-yes="delete-form-{{ $lead->id }}"
                                                                            title="{{ __('Delete') }}"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-placement="top">
                                                                            <i class="ti ti-archive"></i>
                                                                            <span> {{ __('Delete') }}</span>
                                                                        </a>
                                                                        {!! Form::close() !!}
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <p class="text-muted text-sm" data-bs-toggle="tooltip"
                                                        data-bs-original-title="{{ __('Description') }}">
                                                        {{ $lead->subject }}</p>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <ul class="list-inline mb-0">
                                                            <li class="list-inline-item d-inline-flex align-items-center">
                                                                <i
                                                                    class="f-16 text-primary ti ti-message-2"></i>{{ \Auth::user()->dateFormat($lead->date) }}
                                                            </li>
                                                        </ul>
                                                        <div class="user-group">

                                                            @foreach ($lead->users as $user)
                                                                <a href="#" class="avatar rounded-circle avatar-sm"
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
                            var pipeline_id = '1';

                            $("#" + source.id).parent().find('.count').text($("#" + source.id + " > div")
                                .length);
                            $("#" + target.id).parent().find('.count').text($("#" + target.id + " > div")
                                .length);

                            $.ajax({
                                url: '{{ route('lead.order') }}',
                                type: 'POST',
                                data: {
                                    lead_id: id,
                                    stage_id: stage_id,
                                    order: order,
                                    new_status: new_status,
                                    old_status: old_status,
                                    pipeline_id: pipeline_id,
                                    "_token": $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(data) {
                                    show_toastr('Success', 'Card moved successfully',
                                        'success');
                                },
                                error: function(data) {
                                    data = data.responseJSON;
                                    show_toastr('{{ __('Error') }}', data.error, 'error')
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
            $(document).on("change", "#change-pipeline select[name=default_pipeline_id]", function() {
                $('#change-pipeline').submit();
            });
        </script>
        <script>
            $(document).on("click", ".pipeline_id", function() {
                var pipeline_id = $(this).attr('data-id');
                $.ajax({
                    url: '{{ route('lead.change.pipeline') }}',
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
