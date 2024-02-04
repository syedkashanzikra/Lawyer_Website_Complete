<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'color','created_by'
    ];
    public function policies(){
        return $this->hasOne('App\Models\Policies','priority_id','id');
    }
}
