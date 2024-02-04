<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_from',
        'advocate',
        'custom_advocate',
        'custom_address',
        'title',
        'bill_number',
        'due_date',
        'items',
        'subtotal',
        'total_tax',
        'total_amount',
        'description',
        'created_by',
        'bill_to',
        'custom_email',

    ];

    public function get_products(){
        return $this->hasOne('App\Models\InvoiceProduct', 'invoice_id', 'id');

    }
}

