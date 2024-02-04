<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="">
                <dl class="row">
                    <dt class="col-md-5"><span class="h6 text-md mb-0">{{ __('Timing') }}</span></dt>
                    @php
                        $contents = json_decode($opeating->content, true);
                    @endphp
                    <div class="col-md-7">


                        @foreach ($days as $key => $day)
                            @if (array_key_exists($day, $contents))
                                <div class="row">
                                    <div class="col-md-4">
                                        {{ $day }}
                                    </div>
                                    <div class="col-md-8">
                                        {{ $contents[$day]['start_hour'] }} <span>:</span>
                                        {{ $contents[$day]['start_min'] }} <b> To </b>
                                        {{ $contents[$day]['end_hour'] }} <span>:</span>
                                        {{ $contents[$day]['end_min'] }}
                                    </div>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-md-4">
                                        {{ $day }}
                                    </div>
                                    <div class="col-md-8">
                                        <span class="text-danger"> <b>Close</b> </span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
