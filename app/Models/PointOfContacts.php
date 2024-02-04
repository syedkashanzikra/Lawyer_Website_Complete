<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointOfContacts extends Model
{
    use HasFactory;
    protected $fillable = [
        'advocate_id',
        'contact_name',
        'contact_email',
        'contact_phone',
        'contact_designation',
    ];
}
