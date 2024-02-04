<?php

namespace App\Classes;

use App\Models\City;
use App\Models\Country;
use App\Models\State;

class CountryStateCity
{

    public function __construct(Country $country)
    {
        $this->country = $country;
    }

    public function getCountry(){
        try {
            $country = $this->country->getCountry();
            if (isset($country) && !empty($country)){
                return response()->json(['status'=>true,'message'=>"country get success",'data'=>$country])->setStatusCode(200);
            }
            return response()->json(['status'=>false,'message'=>"error while get country"])->setStatusCode(400);
        }catch (\Exception $ex){
            return response()->json(['status'=>false,'message'=>"internal server error"])->setStatusCode(500);
        }
    }

    public function getState($country_id){
        try {
            $state = State::getState($country_id);
            if (isset($state) && !empty($state)){
                return response()->json(['status'=>true,'message'=>"state get success",'data'=>$state])->setStatusCode(200);
            }
            return response()->json(['status'=>false,'message'=>"error while get state"])->setStatusCode(400);
        }catch (\Exception $ex){
            return response()->json(['status'=>false,'message'=>"internal server error"])->setStatusCode(500);
        }
    }

    public function getCity($region_id){
        try {
            $city = City::getCity($region_id);
            if (isset($city) && !empty($city)){
                return response()->json(['status'=>true,'message'=>"city get success",'data'=>$city])->setStatusCode(200);
            }
            return response()->json(['status'=>false,'message'=>"error while get city"])->setStatusCode(400);
        }catch (\Exception $ex){
            return response()->json(['status'=>false,'message'=>"internal server error"])->setStatusCode(500);
        }
    }
}
