<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bench extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'highcourt_id',
        'created_by'
    ];
    public static function getHighcourtType($highcourt_id){
        return HighCourt::find($highcourt_id)->name;
    }
}
