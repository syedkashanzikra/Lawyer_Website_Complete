<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadEmail extends Model
{
    use HasFactory;
    protected $fillable = [
        'lead_id',
        'to',
        'subject',
        'description',
    ];
}
