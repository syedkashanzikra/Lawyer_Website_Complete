<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hearing extends Model
{
    use HasFactory;

    public static function getHearingType($id){
        return HearingType::find($id);
    }

    public function case()
    {
        return $this->belongsTo(Cases::class, 'case_id');
    }
}
