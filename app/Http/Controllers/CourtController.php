<?php

namespace App\Http\Controllers;

use App\Models\Cases;
use App\Models\Court;
use App\Models\HighCourt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

class CourtController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('manage court')) {
            $courts = Court::where('created_by',Auth::user()->creatorId())->get();
            return view('court.index',compact('courts'));
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
        if (Auth::user()->can('create court')) {
            return view('court.create');
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
        if (Auth::user()->can('create court')) {
            $validator = Validator::make(
                $request->all(), [
                    'name' => 'required'
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $crt = new Court();
            $crt['name'] = $request->name;
            $crt['type'] = $request->type;
            $crt['location'] = $request->location;
            $crt['address'] = $request->address;
            $crt['created_by'] = Auth::user()->creatorId();
            $crt->save();

            return redirect()->route('courts.index')->with('success',__('Court successfully created.'));
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
        if (Auth::user()->can('edit court')) {
            try {
                $crt = Court::find($id);
                return view('court.edit',compact('crt'));

            } catch (Throwable $th) {

                return redirect()->back()->with('error',$th);
            }

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
        if (Auth::user()->can('edit court')) {
            $validator = Validator::make(
                $request->all(), [
                    'name' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $crt = Court::find($id);
            $crt['name'] = $request->name;
            $crt['type'] = $request->type;
            $crt['location'] = $request->location;
            $crt['address'] = $request->address;
            $crt->save();

            return redirect()->route('courts.index')->with('success', __('Court successfully updated.'));


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
        if (Auth::user()->can('delete court')) {
            try {
                $cases = Cases::where('court',$id)->get();
                $HighCourt = HighCourt::where('court_id',$id)->get();

                if (count($cases) > 0) {
                    return redirect()->route('courts.index')->with('error', __('This court is assigned to case.'));

                }elseif (count($HighCourt) > 0) {
                    return redirect()->route('courts.index')->with('error', __('This court is assigned to case.'));
                }
                else{

                    Court::find($id)->delete();
                    return redirect()->route('courts.index')->with('success', __('Court successfully deleted.'));
                }


            } catch (Throwable $th) {

                return redirect()->back()->with('error', $th);
            }

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }
}
