<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDefualtView extends Model
{
    use HasFactory;
    protected $fillable = [
        'module',
        'route',
        'view',
        'user_id',
    ];
}
