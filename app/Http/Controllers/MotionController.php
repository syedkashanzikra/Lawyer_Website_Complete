<?php

namespace App\Http\Controllers;

use App\Models\Motion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MotionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $motions = Motion::where('created_by',Auth::user()->creatorId())->get();
        return view('motions.index',compact('motions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('motions.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(), [
                'type' => 'required',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $hicrt = new Motion();
        $hicrt['type'] = $request->type;
        $hicrt['description'] = $request->description;
        $hicrt['created_by'] = Auth::user()->creatorId();
        $hicrt->save();

        return redirect()->route('motions.index')->with('success', __('Motion successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Motion  $motion
     * @return \Illuminate\Http\Response
     */
    public function show(Motion $motion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Motion  $motion
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $motion = Motion::find($id);

        return view('motions.edit',compact('motion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Motion  $motion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $validator = Validator::make(
            $request->all(), [
                'type' => 'required',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $hicrt = Motion::find($id);
        $hicrt['type'] = $request->type;
        $hicrt['description'] = $request->description;
        $hicrt->save();

        return redirect()->route('motions.index')->with('success', __('Motion successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Motion  $motion
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $HearingType = Motion::find($id);

        if ($HearingType) {
            $HearingType->delete();
        }

        return redirect()->back()->with('success', __('Motion successfully deleted.'));
    }
}
