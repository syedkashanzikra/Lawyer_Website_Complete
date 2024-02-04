<?php
namespace App\Exports;

use App\Models\Advocate;
use App\Models\Cases;
use App\Models\CauseList;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class CasesExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if (Auth::user()->can('manage case')) {
            if (Auth::user()->type == 'client') {
                $user = Auth::user()->id;
                $case = DB::table("cases")
                    ->select("cases.*")
                    ->get();
                $cases = [];
                foreach ($case as $value) {
                    $data = json_decode($value->your_party_name);
                    foreach ($data as $key => $val) {
                        if (isset($val->clients) && $val->clients == $user) {
                            $cases[$value->id] = $value;
                        }
                    }
                }
            } else {

                $cases = Cases::with('getCourt')
                    ->where('created_by', Auth::user()->creatorId())
                    ->get();
            }

            foreach ($cases as $k => $case) {
                unset( $case->your_party,$case->your_party_name,$case->opp_party_name, $case->casetype, $case->casenumber, $case->diarybumber,$case->court_hall,$case->floor, $case->your_team, $case->opponents, $case->opponent_advocates, $case->your_party, $case->your_party_name, $case->opp_party_name, $case->opp_adv, $case->case_docs, $case->filing_party,  $case->case_status,$case->journey,$case->motion,$case->sub_motion,$case->created_at,$case->updated_at);

                $created_bys = User::find($case->created_by);
                $created_by = $created_bys->name;

                $cases[$k]['court'] =  CauseList::getCourtById($case->court) ;
                $cases[$k]['highcourt'] =  CauseList::getHighCourtById($case->highcourt) ;
                $cases[$k]['bench'] =  CauseList::getBenchById($case->bench);
                $cases[$k]['advocates'] =  Advocate::getAdvocates($case->advocates);
                $cases[$k]['created_by'] = $created_by;

            }


            return $cases;

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    public function headings(): array
    {
        return [
            "Id",
            "Court",
            "Highcourt",
            "Bench",
            "Year",
            "Case_Number",
            "Filing_Date",
            "Title",
            "Description",
            "Under_Acts",
            "Under_Sections",
            "FIR_number",
            "FIR_year",
            "Court_room",
            "Judge",
            "Police_station",
            "Stage",
            "Advocates",
            "Created_by",
        ];
    }
}
