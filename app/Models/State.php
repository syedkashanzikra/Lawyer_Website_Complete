<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class State extends Model
{
    protected $primaryKey = "id";
    protected $table = "region";
    public $incrementing = false;
    public $timestamps = false;

    public static function getState($country_id){
        try {
            $result = State::where('country_id',$country_id)->get();
            if (isset($result) && !empty($result)){
                return $result;
            }
            return null;
        }catch (QueryException $ex){
            return null;
        }
    }

    public static function StatebyId($id){
        try {
            $result = State::find($id);

            if (isset($result) && !empty($result)){
                return $result->region;
            }
            return null;
        }catch (QueryException $ex){
            return null;
        }
    }
}
