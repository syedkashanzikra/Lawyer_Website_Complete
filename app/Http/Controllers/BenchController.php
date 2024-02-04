<?php

namespace App\Http\Controllers;

use App\Models\Bench;
use App\Models\Cases;
use App\Models\HighCourt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

class BenchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('manage bench')) {
            $benches = Bench::where('created_by',Auth::user()->creatorId())->get();

            return view('bench.index',compact('benches'));
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
        if (Auth::user()->can('create bench')) {
            $highcourts = HighCourt::where('created_by',Auth::user()->creatorId())->pluck('name', 'id');
            return view('bench.create', compact('highcourts'));

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
        if (Auth::user()->can('create bench')) {

            $validator = Validator::make(
                $request->all(), [
                    'name' => 'required',
                    'highcourt_id' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $hicrt = new Bench();
            $hicrt['name'] = $request->name;
            $hicrt['highcourt_id'] = $request->highcourt_id;
            $hicrt['created_by'] = Auth::user()->creatorId();
            $hicrt->save();

            return redirect()->route('bench.index')->with('success', __('Bench successfully created.'));

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


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->can('edit bench')) {
            $bench = Bench::find($id);
            $highcourts = HighCourt::where('created_by',Auth::user()->creatorId())->pluck('name', 'id');

            return view('bench.edit', compact('bench', 'highcourts'));
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
        if (Auth::user()->can('edit bench')) {

            $validator = Validator::make(
                $request->all(), [
                    'name' => 'required',
                    'highcourt_id' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $hicrt = Bench::find($id);
            $hicrt['name'] = $request->name;
            $hicrt['highcourt_id'] = $request->highcourt_id;
            $hicrt->save();

            return redirect()->route('bench.index')->with('success', __('Bench successfully updated.'));

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
        if (Auth::user()->can('delete bench')) {
            try {
                $cases = Cases::where('highcourt',$id)->get();
                
                if (count($cases) > 0) {
                    return redirect()->route('bench.index')->with('error', __('This bench is assigned to case.'));
                }else{

                    Bench::find($id)->delete();

                    return redirect()->route('bench.index')->with('success', __('Bench successfully deleted.'));
                }

            } catch (Throwable $th) {

                return redirect()->back()->with('error', $th);
            }

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }
}
