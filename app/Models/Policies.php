<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policies extends Model
{
    use HasFactory;
    protected $fillable = [
        'priority_id',
        'response_within',
        'response_time',
        'resolve_within',
        'resolve_time',
        'created_by',

    ];

    public function priority(){
        return $this->hasOne('App\Models\Priority','id','priority_id');
    }

}
