<?php
namespace App\Exports;

use App\Models\Advocate;
use App\Models\Bill;
use App\Models\Cases;
use App\Models\CauseList;
use App\Models\Country;
use App\Models\Lead;
use App\Models\State;
use App\Models\Timesheet;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TimesheetsExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if (Auth::user()->can('manage timesheet')) {

            $timesheets = Timesheet::where('created_by',Auth::user()->creatorId())->get();

            foreach ($timesheets as $k => $timesheet) {
                unset($timesheet->created_at,$timesheet->updated_at);

                $created_bys = User::find($timesheet->created_by);
                $created_by = $created_bys->name;
                $timesheets[$k]['created_by'] = $created_by;

                $timesheets[$k]['member'] = User::getTeams($timesheet->member) ;

            }


            return $timesheets;

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    public function headings(): array
    {
        return [
            "Id",
            "Case",
            "Date",
            "Particulars",
            "Time",
            "Member",
            "Created_by",
        ];
    }
}
