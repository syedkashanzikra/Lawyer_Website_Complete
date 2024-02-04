@extends('layouts.app')

@section('page-title', __('Case'))

@section('action-button')
    @can('create case')
        <div class="text-sm-end d-flex all-button-box justify-content-sm-end mx-1">
            <a href="#" data-size="md"  data-bs-toggle="tooltip" title="{{__('Import')}}" data-url="{{ route('case.file.import') }}" data-ajax-popup="true"
            data-title="{{__('Import customer CSV file')}}" class="btn btn-sm btn-primary">
                <i class="ti ti-file-import"></i>
            </a>
            <a href="{{ route('cases.export') }}" class="btn btn-sm btn-primary mx-1" data-bs-toggle="tooltip"
                data-title=" {{ __('Export') }}" title="{{ __('Export') }}">
                <i class="ti ti-file-export"></i>
            </a>
            <a href="{{ route('cases.create') }}" class="btn btn-sm btn-primary " data-toggle="tooltip"
                title="{{ __('Create Case') }}" data-bs-original-title="{{ __('Add Case') }}" data-bs-placement="top"
                data-bs-toggle="tooltip">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endcan

@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Case') }}</li>
@endsection

@section('content')

    <div class="row p-0">
        <div class="col-xl-12">

            <div class="card-header card-body table-border-style">
                <h5></h5>
                <div class="table-responsive">
                    <table class="table dataTable">
                        <thead>
                            <tr>
                                <th>{{ __('S.No.') }}</th>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Case No.') }}</th>
                                <th>{{ __('Year') }}</th>
                                <th>{{ __('Courts/Tribunal') }}</th>
                                <th>{{ __('Advocate') }}</th>
                                <th>{{ __('Date of filing') }}</th>
                                <th width="100px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cases as $key => $case)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $case->title }}</td>


                                <td>
                                    {{ !empty($case->case_number) ? $case->case_number : ' ' }}
                                </td>
                                <td>
                                    {{ !empty($case->year) ? $case->year : '2023' }}
                                </td>

                                <td>
                                    <a href="{{ route('cases.show', $case->id) }}" class="btn btn-sm" data-title="{{ __('View Case') }}">
                                        {{ App\Models\CauseList::getCourtById($case->court) }}
                                    </a>
                                </td>

                                <td>{{ App\Models\Advocate::getAdvocates($case->advocates) }}</td>
                                <td>{{date('d-m-Y',strtotime($case->filing_date))}}</td>
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
                                    <div class="action-btn bg-light-secondary ms-2">
                                        <a href="{{ route('cases.journey', $case->id) }}"
                                            class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                            title="{{ __('Case Journey') }}" data-bs-toggle="tooltip"
                                            data-bs-placement="top">
                                            <i class="ti ti-affiliate"></i>
                                        </a>
                                    </div>

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
@endsection

