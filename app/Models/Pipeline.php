<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pipeline extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'created_by',
    ];
    public function stages()
    {
        return $this->hasMany('App\Models\DealStage', 'pipeline_id', 'id')->where('created_by', '=', \Auth::user()->crmcreatorId())->orderBy('order');
    }

    public function leadStages()
    {
        return $this->hasMany('App\Models\LeadStage', 'pipeline_id', 'id')
                    ->where('created_by', '=', \Auth::user()->crmcreatorId())->orderBy('order');

    }
}
