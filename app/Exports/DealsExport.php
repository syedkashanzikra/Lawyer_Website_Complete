<?php
namespace App\Exports;
use App\Models\Deal;
use App\Models\DealStage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class DealsExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if(Auth::user()->super_admin_employee == '1'){
            $data = Deal::select('deals.*')->join('user_deals', 'user_deals.deal_id', '=', 'deals.id')
                    ->where('user_deals.user_id', '=', Auth::user()->id)
                    ->orderBy('deals.order')->get();
        }elseif(Auth::user()->type == 'company'){
            $data = Deal::select('deals.*')
                    ->join('client_deals', 'client_deals.deal_id', '=', 'deals.id')
                    ->join('users', function ($join) {
                        $join->on('client_deals.client_id', '=', 'users.id')
                            ->where('users.type', '=', 'advocate')
                            ->where('users.created_by', '=', Auth::user()->id);
                    })
                    ->orderBy('deals.order')
                    ->distinct()
                    ->get();
        }else{
            $data = Deal::select('deals.*')->join('client_deals', 'client_deals.deal_id', '=', 'deals.id')
                    ->where('client_deals.client_id', '=', Auth::user()->id)
                    ->orderBy('deals.order')->get();
        }

        foreach ($data as $k => $deal) {
            unset( $deal->sources, $deal->products, $deal->notes, $deal->labels, $deal->order);
            $created_bys = User::find($deal->created_by);
            $created_by = $created_bys->name;
            $data[$k]['created_by'] = $created_by;
        }
        return $data;
    }
    public function headings(): array
    {
        return [
        "Id",
        "Name",
        "Price",
        "Phone",
        "pipeline_id",
        "Stage_id",
        "Status",
        "created_by",
        "is_active",
        "created_at",
        "updated_at",
        ];
    }
}
