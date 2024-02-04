<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'members',
    ];
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
