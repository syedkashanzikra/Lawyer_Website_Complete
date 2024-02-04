<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class Country extends Model
{
    protected $primaryKey = "id";
    protected $table = "country";
    public $incrementing = false;
    public $timestamps = false;

    public function getCountry(){
        try {
            $result = $this->get();
            if (isset($result) && !empty($result)){
                return $result;
            }
            return null;
        }catch (QueryException $ex){
            return null;
        }
    }


    public static function countryById($id){
        try {
            $result = Country::find($id);

            if (isset($result) && !empty($result)){
                return $result->country;
            }
            return null;
        }catch (QueryException $ex){
            return null;
        }
    }
}
