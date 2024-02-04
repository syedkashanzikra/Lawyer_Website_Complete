<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advocate extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'age',
        'father_name',
        'website',
        'tin',
        'gstin',
        'pan_number',
        'hourly_rate',
        'ofc_address_line_1',
        'ofc_address_line_2',
        'ofc_country',
        'ofc_state',
        'ofc_city',
        'ofc_zip_code',
        'home_address_line_1',
        'home_address_line_2',
        'home_country',
        'home_state',
        'home_city',
        'home_zip_code',
        'user_id',
    ];

    public static function getCountryName($id){
        return Country::find($id)->id;
    }

    public static function getCountry($id){
        return Country::find($id)->country;
    }

    public static function getStateByCountry($id){
        return State::where('country_id',$id)->get();
    }

    public static function getSelectedState($id){

        $state = State::find($id);
        if ($state) {
            return $state->id;
        }
        // return State::find($id)->id;
    }

    public static function getStateName($id){
        return State::find($id)->region;
    }

    public static function getCityByState($id){
        return City::where('region_id',$id)->get();
    }

    public static function getSelectedCity($id){
        $state = City::find($id);
        if ($state) {
            return $state->id;
        }
        // return City::find($id)->id;
    }

    public static function getCityName($id){
        return City::find($id)->city;
    }

    public static function getAdvocates($id){
        $advName = User::whereIn('id',explode(',',$id))->pluck('name')->toArray();
        return implode(', ',$advName);

    }

    public static function getAdvocat($id){
        $advName = Advocate::find($id);
        return $advName;
    }

    public function getAdvUser(){

        return $this->belongsTo(User::class, 'user_id');
    }
}
