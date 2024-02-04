<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCatgory extends Model
{
    use HasFactory;
    protected $table = 'user_categories';
    protected $fillable = [
      'user_id','category_id'
    ];

    public function getCategoryUser()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
