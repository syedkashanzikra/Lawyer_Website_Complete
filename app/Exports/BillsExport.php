<?php
namespace App\Exports;

use App\Models\Advocate;
use App\Models\Bill;
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

class BillsExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if (Auth::user()->can('manage bill')) {

            $bills = Bill::where('created_by', Auth::user()->creatorId())->get();

            foreach ($bills as $k => $bill) {
                unset(
                    $bill->custom_advocate,
                    $bill->custom_address,
                    $bill->custom_email,
                    $bill->items,
                    $bill->created_at,$bill->updated_at);

                $created_bys = User::find($bill->created_by);
                $created_by = $created_bys->name;
                $bills[$k]['created_by'] = $created_by;

                $bills[$k]['advocate'] =  Advocate::getAdvocates($bill->advocate) ;
                $bills[$k]['bill_to'] =  User::getUser($bill->bill_to)->name ;


            }


            return $bills;

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    public function headings(): array
    {
        return [
            "Id",
            "bill_from",
            "advocate",
            "title",
            "bill_number",
            "due_date",
            "subtotal",
            "total_tax",
            "total_disc",
            "total_amount",
            "description",
            "Created_by",
            "bill_to",
            "reciept_date",
            "status",
            "due_amount",

        ];
    }
}
