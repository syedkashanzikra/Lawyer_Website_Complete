<div class="modal-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="">
                <dl class="row">
                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Name:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{ $advocate->getAdvUser->name }}</span> </dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Email:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{ $advocate->getAdvUser->email }}</span></dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Phone:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{ $advocate->phone_number }}</span></dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Age:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{ $advocate->age }}</span></dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Company Name:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{ $advocate->company_name }}</span></dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Bank Details:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{!! $advocate->bank_details !!}</span></dd>

                    <div class="col-12">
                        <h5>{{ __('Office Address') }}</h5>
                        <hr class="mt-2 mb-2">
                    </div>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Address Line 1:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{ $advocate->ofc_address_line_1 }}</span></dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Address Line 2:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{ $advocate->ofc_address_line_2 }}</span></dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Country:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{ App\Models\Country::countryById($advocate->ofc_country)  }}</span></dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('State:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{  App\Models\State::StatebyId($advocate->ofc_state) }}</span></dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('City:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{ $advocate->ofc_city }}</span></dd>

                    <div class="col-12">
                        <h5>{{ __('Home Address') }}</h5>
                        <hr class="mt-2 mb-2">
                    </div>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Address Line 1:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{ $advocate->home_address_line_1 }}</span></dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Address Line 2:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{ $advocate->home_address_line_2 }}</span></dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('Country:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{  App\Models\Country::countryById($advocate->home_country) }}</span></dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('State:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{ App\Models\State::StatebyId($advocate->home_state) }}</span></dd>

                    <dt class="col-md-4"><span class="h6 text-md mb-0">{{ __('City:') }}</span></dt>
                    <dd class="col-md-8"><span class="text-md">{{ $advocate->home_city }}</span></dd>

                </dl>
            </div>

        </div>

    </div>
</div>
