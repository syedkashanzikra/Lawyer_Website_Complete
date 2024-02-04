<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    public function getDocType(){
        return $this->hasOne('App\Models\DocType', 'id', 'type');
    }
    public function user() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
