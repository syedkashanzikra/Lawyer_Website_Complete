<?php

namespace App\Http\Controllers;

use App\Models\HearingType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HearingTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $HearingType = HearingType::where('created_by',Auth::user()->creatorId())->get();
        return view('hearingType.index',compact('HearingType'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('hearingType.create');

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

        $hicrt = new HearingType();
        $hicrt['type'] = $request->type;
        $hicrt['description'] = $request->description;
        $hicrt['created_by'] = Auth::user()->creatorId();
        $hicrt->save();

        return redirect()->route('hearingType.index')->with('success', __('Hearing Type successfully created.'));
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
        $HearingType = HearingType::find($id);

        return view('hearingType.edit',compact('HearingType'));
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
        $validator = Validator::make(
            $request->all(), [
                'type' => 'required',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $hicrt = HearingType::find($id);
        $hicrt['type'] = $request->type;
        $hicrt['description'] = $request->description;
        $hicrt->save();

        return redirect()->route('hearingType.index')->with('success', __('Hearing Type successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $HearingType = HearingType::find($id);

        if ($HearingType) {
            $HearingType->delete();
        }

        return redirect()->back()->with('success', __('Hearing Type successfully deleted.'));
    }
}
