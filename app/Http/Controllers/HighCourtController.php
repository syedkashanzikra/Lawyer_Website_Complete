<?php

namespace App\Http\Controllers;

use App\Models\Bench;
use App\Models\Cases;
use App\Models\Court;
use App\Models\HighCourt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

class HighCourtController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('manage highcourt')) {
            $hicrts = HighCourt::where('created_by',Auth::user()->creatorId())->get();

            return view('highcourt.index',compact('hicrts'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->can('create highcourt')) {
            $courts = Court::where('created_by',Auth::user()->creatorId())->pluck('name','id');
            return view('highcourt.create',compact('courts'));
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
        if (Auth::user()->can('create highcourt')) {

            $validator = Validator::make(
                $request->all(), [
                    'name' => 'required',
                    'court_id' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $hicrt = new HighCourt;
            $hicrt['name'] = $request->name;
            $hicrt['court_id'] = $request->court_id;
            $hicrt['created_by'] = Auth::user()->creatorId();
            $hicrt->save();

            return redirect()->route('highcourts.index')->with('success', __('High-Court successfully created.'));

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
        if (Auth::user()->can('edit highcourt')) {
            $highcourt = HighCourt::find($id);
            $courts = Court::where('created_by',Auth::user()->creatorId())->pluck('name', 'id');

            return view('highcourt.edit', compact('highcourt','courts'));
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
        if (Auth::user()->can('edit highcourt')) {

            $validator = Validator::make(
                $request->all(), [
                    'name' => 'required',
                    'court_id' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $hicrt = HighCourt::find($id);
            $hicrt['name'] = $request->name;
            $hicrt['court_id'] = $request->court_id;
            $hicrt->save();

            return redirect()->route('highcourts.index')->with('success', __('High-Court successfully updated.'));

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
        if (Auth::user()->can('delete highcourt')) {
            try {

                $benches = Bench::where('highcourt_id',$id)->get();
                $cases = Cases::where('highcourt',$id)->get();

                if (count($benches) > 0) {

                    return redirect()->route('highcourts.index')->with('error', __('This high court is assigned to bench.'));
                }elseif (count($cases) > 0) {
                    return redirect()->route('highcourts.index')->with('error', __('This high court is assigned to case.'));
                }
                else{
                    HighCourt::find($id)->delete();

                    return redirect()->route('highcourts.index')->with('success', __('High Court successfully deleted.'));
                }

            } catch (Throwable $th) {

                return redirect()->back()->with('error', $th);
            }

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }
}
