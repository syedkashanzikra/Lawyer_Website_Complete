<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToDo extends Model
{
    use HasFactory;
    protected $table = 'todos';
    protected $fillable = [
        'description',
        'due_date',
        'relate_to',
        'assign_to',
        'assign_by',
        'start_date',
        'end_date',
        'status',
        'completed_by',
        'completed_at',
    ];
    public function assignedByUser()
    {
        return $this->belongsTo(User::class, 'assign_by');
    }

    public static function priorities(){
        return [
            'Urgent',
            'High',
            'Medium',
            'Low',
        ];
    }
}
