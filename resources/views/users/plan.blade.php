<div class="table-border-style ">
    <div class="table-responsive">
        <table class="table">
            <tr>
                <th>{{ __('Name / Price') }}</th>
                <th>{{ __('Users') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
            @foreach ($plans as $plan)
                <tr>

                    <td>{{ $plan->name }} </td>
                    <td>{{ $plan->max_users }}</td>
                    <td>
                        @if ($user->plan == $plan->id)
                            <div class="btn btn-primary btn-sm rounded-pill my-auto w-50 mb-2">{{ __('Active') }}</div>
                            @if ($user->plan != 1)
                                <a href="{{ route('plan.deactivate', [$user->id,1]) }}"
                                    class="btn btn-danger btn-sm rounded-pill my-auto w-50 mb-2"
                                    title="{{ __('Click to Upgrade Plan') }}">{{ 'cancel' }}</a>
                            @endif
                        @else
                            <div class="btn btn-primary btn-sm rounded-pill my-auto w-50 mb-2">
                                <a href="{{ route('plan.active', [$user->id, $plan->id]) }}"
                                    class="btn btn-primary btn-sm rounded-pill my-auto w-100"
                                    title="{{ __('Click to Upgrade Plan') }}"><i class="fas fa-cart-plus"></i></a>
                            </div>
                        @endif

                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
