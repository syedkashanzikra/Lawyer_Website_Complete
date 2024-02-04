<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'price',
        'pipeline_id',
        'stage_id',
        'group_id',
        'sources',
        'items',
        'created_by',
        'notes',
        'labels',
        'permissions',
        'status',
        'is_active',
    ];

    public static $permissions = [
        'Client View Tasks',
        'Client View Products',
        'Client View Sources',
        'Client View Contacts',
        'Client View Files',
        'Client View Invoices',
        'Client View Custom fields',
        'Client View Members',
        'Client Add File',
        'Client Deal Activity',
    ];

    public static $statues = [
        'Active' => 'Active',
        'Won' => 'Won',
        'Loss' => 'Loss',
    ];

    public $customField;

    public function labels()
    {
        if($this->labels)
        {
            return Label::whereIn('id', explode(',', $this->labels))->get();
        }

        return false;
    }

    public function pipeline()
    {
        return $this->hasOne('App\Models\Pipeline', 'id', 'pipeline_id');
    }
    public function stage()
    {
        return $this->hasOne('App\Models\DealStage', 'id', 'stage_id');
    }

    public function group()
    {
        return $this->hasOne('App\Models\Group', 'id', 'group_id');
    }

    public function clients()
    {
        return $this->belongsToMany('App\Models\User', 'client_deals', 'deal_id', 'client_id');
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_deals', 'deal_id', 'user_id');
    }

    public function items1()
    {
        if($this->products)
        {
            return Item::whereIn('id', explode(',', $this->products))->get();
        }

        return [];
    }

    public function sources()
    {
        if($this->sources)
        {
            return Source::whereIn('id', explode(',', $this->sources))->get();
        }

        return [];
    }

    public function files()
    {
        return $this->hasMany('App\Models\DealFile', 'deal_id', 'id');
    }

    public function tasks()
    {
        return $this->hasMany('App\Models\DealTask', 'deal_id', 'id');
    }

    public function complete_tasks()
    {
        return $this->hasMany('App\Models\DealTask', 'deal_id', 'id')->where('status', '=', 1);
    }

    public function invoices()
    {
        return $this->hasMany('App\Models\Invoice', 'deal_id', 'id');
    }

    public function calls()
    {
        return $this->hasMany('App\Models\DealCall', 'deal_id', 'id');
    }

    public function emails()
    {
        return $this->hasMany('App\Models\DealEmail', 'deal_id', 'id')->orderByDesc('id');
    }

    public function activities()
    {
        return $this->hasMany('App\Models\ActivityLog', 'deal_id', 'id')->orderBy('id', 'desc');
    }

    public function discussions()
    {
        return $this->hasMany('App\Models\DealDiscussion', 'deal_id', 'id')->orderBy('id', 'desc');
    }

    public static function getDealSummary($deals)
    {
        $total = 0;

        foreach($deals as $deal)
        {
            $total += $deal->price;
        }

        return \Auth::user()->priceFormat($total);
    }
}
