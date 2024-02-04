<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DealStage extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'pipeline_id',
        'created_by',
        'order',
    ];
    public function deals()
    {
        if(Auth::user()->super_admin_employee == '1'){
            return Deal::select('deals.*')->join('user_deals', 'user_deals.deal_id', '=', 'deals.id')
                    ->where('user_deals.user_id', '=', Auth::user()->id)
                    ->where('deals.stage_id', '=', $this->id)->orderBy('deals.order')->get();
        }elseif(Auth::user()->type == 'company'){
            return Deal::select('deals.*')
                    ->join('client_deals', 'client_deals.deal_id', '=', 'deals.id')
                    ->join('users', function ($join) {
                        $join->on('client_deals.client_id', '=', 'users.id')
                            ->where('users.type', '=', 'advocate')
                            ->where('users.created_by', '=', Auth::user()->id);
                    })
                    ->where('deals.stage_id', '=', $this->id)
                    ->orderBy('deals.order')
                    ->distinct()
                    ->get();
        }else{
            return Deal::select('deals.*')->join('client_deals', 'client_deals.deal_id', '=', 'deals.id')
                    ->where('client_deals.client_id', '=', Auth::user()->id)
                    ->where('deals.stage_id', '=', $this->id)->orderBy('deals.order')->get();
        }

    }
}
