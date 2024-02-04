<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">

            <div class="">
                <dl class="row">
                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Description:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{ $todo->description }}</span></dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Start Date:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{date('d-m-Y h:i',strtotime($todo->start_date))}}</span></dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('End Date:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{date('d-m-Y h:i',strtotime($todo->end_date))}}</span></dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Relate To:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{
                            App\Models\Cases::getCasesById($todo->relate_to)}}</span></dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Assign To:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{ App\Models\User::getTeams($todo->assign_to)}}</span>
                    </dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Assign By:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{ App\Models\User::find($todo->assign_by)->name
                            }}</span></dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Status:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">

                            @if ($todo->status == 0)
                            {{__('Completed')}}
                            @elseif (strtotime($todo->start_date) < strtotime(date("d-m-y h:i"))) {{__('Pending')}}
                                @elseif (strtotime($todo->start_date) > strtotime(date("d-m-y h:i")))
                                {{__('Upcoming')}}
                                @endif

                        </span>
                    </dd>

                    @if (!empty($todo->completed_by))

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Completed By:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{ App\Models\User::getTeams($todo->completed_by)
                            }}</span></dd>
                    @endif


                    @if (!empty($todo->completed_at))
                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Completed At:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{ $todo->completed_at }}</span></dd>
                    @endif

                </dl>
            </div>

        </div>

    </div>
</div>
