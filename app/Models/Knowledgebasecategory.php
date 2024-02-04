<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Knowledgebasecategory extends Model
{
    use HasFactory;
    protected $table = 'knowledge_base_category';
    protected $fillable = [
        'title'
    ];
}
