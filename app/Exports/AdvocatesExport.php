<?php
namespace App\Exports;

use App\Models\Advocate;
use App\Models\Cases;
use App\Models\CauseList;
use App\Models\Country;
use App\Models\Lead;
use App\Models\State;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AdvocatesExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if (Auth::user()->can('manage advocate')) {

            $advocates = Advocate::where('created_by', Auth::user()->creatorId())
                        ->with('getAdvUser')
                        ->get();

            foreach ($advocates as $k => $advocate) {
                unset(
                    $advocate->user_id,
                    $advocate->father_name,
                    $advocate->website,
                    $advocate->tin,
                    $advocate->gstin,
                    $advocate->pan_number,
                    $advocate->hourly_rate,
                $advocate->created_at,$advocate->updated_at);

                $created_bys = User::find($advocate->created_by);
                $created_by = $created_bys->name;
                $advocates[$k]['created_by'] = $created_by;
                $advocates[$k]['ofc_country'] = Country::countryById($advocate->ofc_country);
                $advocates[$k]['ofc_state'] =  State::StatebyId($advocate->ofc_state);
                $advocates[$k]['home_country'] =  Country::countryById($advocate->home_country);
                $advocates[$k]['home_state'] =  State::StatebyId($advocate->home_state);

            }


            return $advocates;

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    public function headings(): array
    {
        return [
            "Id",
            "Phone_Number",
            "Age",
            "Company_name",
            "Ofc_address_line_1",
            "Ofc_address_line_2",
            "Ofc_country",
            "Ofc_state",
            "Ofc_city",
            "Ofc_zip_code",
            "Home_address_line_1",
            "Home_address_line_2",
            "Home_country",
            "Home_state",
            "Home_city",
            "Home_zip_code",
            "Bank_details",
            "Created_by"

        ];
    }
}
