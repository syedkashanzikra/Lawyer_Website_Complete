<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientDeal extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'deal_id',
    ];
}
