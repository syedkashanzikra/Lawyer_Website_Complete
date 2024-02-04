<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">

            <div class="">
                <dl class="row">

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Courts/Tribunal:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{
                    $cause->getCourtById($cause->court)
                            }}</span>
                    </dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Highcourt:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{  $cause->getHighCourtById($cause->highcourt)}}</span></dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Circuit/Devision:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{ $cause->getBenchById($cause->bench) }}</span></dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Causelist By:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{ $cause->causelist_by }}</span></dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Advocate Name:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{ $cause->advocate_name }}</span></dd>

                </dl>
            </div>

        </div>

    </div>
</div>
