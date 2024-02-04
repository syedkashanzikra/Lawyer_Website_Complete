<div class="card shadow-none bg-transparent sticky-top" style="top:70px">
    <div class="list-group list-group-flush rounded-0" id="useradd-sidenav">
        <a href="{{route('category.index')}}" class="list-group-item list-group-item-action {{ (Request::route()->getName() == 'category.index' ) ? ' active' : '' }}">{{ __('Category') }}
            <div class="float-end dark"><i class="ti ti-chevron-right"></i></div>
        </a>
        <a href="{{ route('operating_hours.index') }}" class="list-group-item list-group-item-action {{ (Request::route()->getName() == 'operating_hours.index' ) ? ' active' : '' }}">{{ __('Operating Hours') }}
            <div class="float-end dark"><i class="ti ti-chevron-right"></i></div>
        </a>
        <a href="{{ route('priority.index') }}" class="list-group-item list-group-item-action {{ (Request::route()->getName() == 'priority.index' ) ? ' active' : '' }}" >{{ __('Priority') }}
            <div class="float-end "><i class="ti ti-chevron-right"></i></div>
        </a>
        <a href="{{ route('policiy.index') }}" class="list-group-item list-group-item-action {{ (Request::route()->getName() == 'policiy.index' ) ? ' active' : '' }}">{{ __('SLA Policy Setting') }}
            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
        </a>
        <a href="{{ route('ticket.custom.field.index') }}" class="list-group-item list-group-item-action {{ (Request::route()->getName() == 'ticket.custom.field.index' ) ? ' active' : '' }}">{{ __('Ticket Fields Settings') }}
            <div class="float-end"><i class="ti ti-chevron-right"></i></div>
        </a>
    </div>
</div>