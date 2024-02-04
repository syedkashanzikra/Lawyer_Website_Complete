<div class="modal-body">
<div class="row">
    <div class="col-lg-12">

        <div class="">
            <dl class="row">
                <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Case:') }}</span></dt>
                <dd class="col-md-8"><span class="text-md">{{App\Models\Cases::getCasesById($expense->case)}}</span></dd>

                <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Date:') }}</span></dt>
                <dd class="col-md-8"><span class="text-md">{{ $expense->date}}</span></dd>

                <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Particulars:') }}</span></dt>
                <dd class="col-md-8"><span class="text-md">{{ $expense->particulars }}</span></dd>

                <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Money Spent:') }}</span></dt>
                <dd class="col-md-8"><span class="text-md">{{ $expense->money}}</span></dd>

                <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Payment Method:') }}</span></dt>
                <dd class="col-md-8"><span class="text-md">{{ $expense->method}}</span></dd>

                <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Member:') }}</span></dt>
                <dd class="col-md-8"><span class="text-md">{{ App\Models\User::getTeams($expense->member)}}</span></dd>

                <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Notes:') }}</span></dt>
                <dd class="col-md-8"><span class="text-md">{{ $expense->notes}}</span></dd>
            </dl>
        </div>

    </div>

</div>
</div>
