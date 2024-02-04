<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealTask extends Model
{
    use HasFactory;
    protected $fillable = [
        'deal_id',
        'name',
        'date',
        'time',
        'priority',
        'status',
    ];

    public static $priorities = [
        1 => 'Low',
        2 => 'Medium',
        3 => 'High',
    ];
    public static $status     = [
        0 => 'On Going',
        1 => 'Completed',
    ];
}
