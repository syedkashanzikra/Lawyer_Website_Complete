<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;
    protected $fillable = [
        'case',
        'date',
        'particulars',
        'money',
        'method',
        'notes',
        'member',
        'created_by',
    ];
}
