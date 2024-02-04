<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'rate',
    ];

    public static function getTax($id){
        $tax = Tax::find($id);
        return $tax;
    }
}
