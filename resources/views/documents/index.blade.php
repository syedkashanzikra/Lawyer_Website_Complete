@extends('layouts.app')

@section('page-title', __('Documents'))
@php
$docfile = \App\Models\Utility::get_file('uploads/documents/');

@endphp
@section('action-button')
    @can('create document')
        <div class="text-sm-end d-flex all-button-box justify-content-sm-end">
            <a href="#" class="btn btn-sm btn-primary mx-1" data-ajax-popup="true" data-size="lg" data-title="Add Documents"
                data-url="{{ route('documents.create') }}" data-toggle="tooltip" title="{{ __('Create New Documents') }}" data-bs-original-title="{{__('Create New Documents')}}" data-bs-placement="top" data-bs-toggle="tooltip">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endcan

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Documents') }}</li>
@endsection

@section('content')
    <div class="row p-0">
        <div class="col-xl-12">
            <div class=" shadow-none">
                <div class="card-header card-body table-border-style">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table dataTable data-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Size') }}</th>
                                    <th>{{ __('Uploaded by') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Uploaded at') }}</th>
                                    <th width="100px">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($docs as $doc)
                                    <tr>
                                        {{-- <td><a href="{{$docfile.$doc->file}}" target="_blank" class="btn btn-sm text-dark">
                                            {{ $doc->file }}
                                        </a></td> --}}
                                        <td>{{ $doc->name }}</td>
                                        <td>{{ $doc->doc_size }} MB</td>
                                        <td>{{ $doc->user->name }}</td>
                                        <td>{{ optional($doc->getDocType)->name }}</td>
                                        <td>{{ explode(' ', $doc->created_at)[0] }}</td>
                                        <td>
                                            @can('view document')

                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                                        data-url="{{ route('documents.show', $doc->id) }}" data-size="md"
                                                        data-ajax-popup="true" data-title="{{ __('View Document') }}"
                                                        title="{{ __('View') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"><i
                                                                class="ti ti-eye "></i></a>
                                                </div>
                                            @endcan
                                            @can('edit document')
                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                        data-url="{{ route('documents.edit', $doc->id) }}" data-size="lg"
                                                        data-ajax-popup="true" data-title="{{ __('Update Document') }}"
                                                        title="{{ __('Edit') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top">
                                                        <i
                                                                class="ti ti-edit "></i></a>
                                                </div>
                                            @endcan
                                            @can('delete document')
                                                <div class="action-btn bg-light-secondary ms-2">
                                                    <a href="#"
                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                        data-confirm-yes="delete-form-{{ $doc->id }}"
                                                        title="{{ __('Delete') }}" data-bs-toggle="tooltip"
                                                        data-bs-placement="top">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                </div>
                                            @endcan
                                            {!! Form::open([
                                                'method' => 'DELETE',
                                                'route' => ['documents.destroy', $doc->id],
                                                'id' => 'delete-form-' . $doc->id,
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
@endsection


@push('custom-script')
<script>
    $(document).ready(function() {
        $(document).on('change', '.documentType', function() {
            var selected_opt = $(this).val();

            $.ajax({
                url: "{{ route('get.docSubType') }}",
                datType: 'json',
                method: 'POST',
                data: {
                    selected_opt: selected_opt
                },
                success: function(data) {
                    if (data.status == 1) {
                        $('.documentSubType').empty();
                        $.each(data.getdata, function(key, value) {
                            $('.documentSubType').append('<option value="' + key + '">' + value + '</option>');
                        });

                    } else {
                        $('.documentSubType').empty();
                    }

                }
            })

        });
    });
</script>
@endpush
