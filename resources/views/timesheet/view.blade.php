<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">

            <div class="">
                <dl class="row">
                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Case:') }}</span></dt>
                    <dd class="col-md-8"><span
                            class="text-md">{{ App\Models\Cases::getCasesById($timesheet->case) }}</span></dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Date:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{ $timesheet->date }}</span></dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Particulars:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{ $timesheet->particulars }}</span></dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Time:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{ $timesheet->time }}</span></dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Member:') }}</span></dt>
                    <dd class="col-md-8"><span
                            class="text-md">{{ App\Models\User::getTeams($timesheet->member) }}</span></dd>

                </dl>
            </div>

        </div>

    </div>
</div>
