<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        
        'invoice_id',
        'product_name',
        'quantity',
        'price',
        'tax',
        'total',
    ];
}
