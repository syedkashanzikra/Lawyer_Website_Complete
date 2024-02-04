<div class="modal-body">
    <div class="table-responsive">
        <table class="table dataTable data-table">

            <thead>
                <tr>
                   <th>{{ __('Court') }}</th>
                   <th>{{ __('Case No.') }}</th>
                   <th>{{ __('Title') }}</th>
                   <th>{{ __('Advocate') }}</th>
                   <th>{{ __('Date of filing') }}</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($cases as $key => $case)

                <tr>
                   <td>
                       <a class="btn btn-sm"
                       href="{{ route('cases.show', $case['id']) }}" data-size="md"
                           data-ajax-popup="true" data-title="{{ __('View Case') }}">
                           {{ App\Models\CauseList::getCourtById($case['court']) }}
                       </a>
                   </td>
                   <td>
                       {{ !empty($case['case_number']) ? $case['case_number'] : ' ' }}
                   </td>

                   <td>{{ $case['title'] }}</td>

                   <td>{{ App\Models\Advocate::getAdvocates($case['advocates']) }}</td>
                   <td>{{date('d-m-Y ',strtotime($case['filing_date']))}}</td>

               </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>
