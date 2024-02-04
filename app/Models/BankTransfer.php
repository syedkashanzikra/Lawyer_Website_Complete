<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransfer extends Model
{
    use HasFactory;
    protected  $fillable =
    [
        'id',
        'invoice_id',
        'order_id',
        'amount',
        'status',
        'receipt',
        'date',
        'created_by',
    ];
}
