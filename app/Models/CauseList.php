<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CauseList extends Model
{
    use HasFactory;
    protected $fillable = [
        'court',
        'highcourt',
        'bench',
        'causelist_by',
        'advocate_name',
    ];

    public function getCourt()
    {
        return $this->belongsTo(Court::class, 'court');
    }

    public function highCourt()
    {
        return $this->belongsTo(HighCourt::class, 'highcourt');
    }

    public function getBench()
    {
        return $this->belongsTo(Bench::class, 'bench');
    }

    public static function getCourtById($id){
        return Court::find($id)->name;
    }

    public static function getBenchById($id){
        $return = Bench::find($id);

        if (!empty($return)) {
            return $return->name;
        }else{
            return '-';
        }
    }

    public static function getHighCourtById($id){
        $return = HighCourt::find($id);
        if (!empty($return)) {
            return $return->name;
        }else{
            return '-';
        }
    }
}
