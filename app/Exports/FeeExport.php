<?php
namespace App\Exports;


use App\Models\Fee;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FeeExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if (Auth::user()->can('manage feereceived')) {


            $expenses = Fee::where('created_by',Auth::user()->creatorId())->get();

            foreach ($expenses as $k => $timesheet) {
                unset($timesheet->created_at,$timesheet->updated_at);

                $created_bys = User::find($timesheet->created_by);
                $created_by = $created_bys->name;
                $expenses[$k]['created_by'] = $created_by;

                $expenses[$k]['member'] = User::getTeams($timesheet->member) ;
                $expenses[$k]['method'] = $timesheet->method == 0 ? 'Bank Transfer' : ($timesheet->method == 1 ? 'Cash' : ($timesheet->method == 2 ? 'Cheque' : ($timesheet->method == 3 ? 'Online Payment' : '')));

            }


            return $expenses;

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
            "Money",
            "Method",
            "Notes",
            "Member",
            "Created_by",
        ];
    }
}
