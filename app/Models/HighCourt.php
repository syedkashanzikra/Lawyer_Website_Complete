<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HighCourt extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'court_id'
    ];
    public static function getCourtType($court_id){
        return Court::find($court_id)->name;
    }
}
