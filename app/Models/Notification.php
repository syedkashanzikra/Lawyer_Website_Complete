<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'bill_id',
        'bill_from',
        'bill_to',
        'due_date',
    ];
    public static function getBillDetail($id){

        $bill = Bill::where('id',$id)->first();
        return $bill;
    }


}
