<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class City extends Model
{
    protected $primaryKey = "id";
    protected $table = "city";
    public $incrementing = false;
    public $timestamps = false;

    public static function getCity($region_id){
        try {
            $result = City::where('region_id',$region_id)->get();
            if (isset($result) && !empty($result)){
                return $result;
            }
            return null;
        }catch (QueryException $ex){
            return null;
        }
    }
}
