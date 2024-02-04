<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealFile extends Model
{
    use HasFactory;
    protected $fillable = [
        'deal_id',
        'file_name',
        'file_path',
    ];
}
