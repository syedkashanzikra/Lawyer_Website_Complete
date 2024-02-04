@extends('layouts.app')

@section('page-title', __('Case Journey'))

@section('action-button')


@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Case Journey') }}</li>
@endsection

@section('content')
    <div class="row g-0 p-0">
        <div class="col-sm-12 border-bottom">
            <div class="card shadow-none bg-transparent">
                <div class="card-header">
                    <h4> {{ __('Case Journey - ') }} {{ $case->title }} {{ __('(#'.$case->case_number.')') }}</h4>
                </div>
{{-- @dd(explode(',',$case->journey)) --}}
                <div class="card-body pt-0 text-center">
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="row btn-chk-work" role="group">
                                @foreach ($case->caseJourney() as $key => $step)
                                    <div class="col-md-2 col-sm-4 col-6">
                                        <input type="checkbox" class="btn-check" id="{{ $key }}" autocomplete="off" @if (in_array($key, explode(',',$case->journey))) checked @endif>
                                        <label class="btn btn-outline-primary p-3 w-100 d-flex justify-content-center align-items-center rounded-0 box" for="{{ $key }}"
                                            style="min-height: 100px; font-size: initial;">
                                            {{ $step }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('custom-script')
    <script>

        $('.btn-check').change(function() {
            var checkedCheckboxes = $('.btn-chk-work input[type="checkbox"]:checked');


            var checkedValues = [];

            checkedCheckboxes.each(function() {
                checkedValues.push($(this).attr('id'));
            });

            $.ajax({
                url: '{{ route('update.journey',$case->id) }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    journeys: checkedValues
                },
                success: function(response) {
                    show_toastr('Success',response.msg, 'success')
                }
            });
        });
    </script>
@endpush
