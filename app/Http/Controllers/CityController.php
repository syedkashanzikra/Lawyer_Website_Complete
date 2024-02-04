<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->type == 'super admin') {
            $countries = Country::all()->pluck('country','id');
            return view('city.create',compact('countries'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->type == 'super admin') {

            $validator = Validator::make(
                $request->all(), [
                    'country' => 'required',
                    'state' => 'required',
                    'city' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $country = New City();
            $country->country_id = $request->country;
            $country->region_id = $request->state;
            $country->city = ucfirst($request->city);
            $country->save();

            return redirect()->back()->with('success', __('State successfully added.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->type == 'super admin') {
            $city = City::find($id);

            $countries = Country::all()->pluck('country','id');
            $country = Country::find($city->country_id);

            $states = State::all()->pluck('region','id');
            $state = State::find($city->region_id);

            return view('city.edit',compact('city','countries','country','states','state'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->type == 'super admin') {

            $validator = Validator::make(
                $request->all(), [
                    'country' => 'required',
                    'state' => 'required',
                    'city' => 'required|unique:city,city',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $city = City::find($id);
            $city->country_id = $request->country;
            $city->region_id = $request->state;
            $city->city = ucfirst($request->city);
            $city->save();

            return redirect()->back()->with('success', __('City successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->type == 'super admin') {

            if (City::find($id)->delete()) {
                return redirect()->back()->with('success', __('City successfully deleted.'));
            }

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
