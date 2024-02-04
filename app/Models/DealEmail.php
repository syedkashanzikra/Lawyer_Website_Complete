<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealEmail extends Model
{
    use HasFactory;
    protected $fillable = [
        'deal_id',
        'to',
        'subject',
        'description',
    ];
}
