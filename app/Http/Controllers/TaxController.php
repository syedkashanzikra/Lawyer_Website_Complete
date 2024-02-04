<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('manage tax')) {
            $taxes = Tax::where('created_by',Auth::user()->creatorId())->get();

            return view('tax.index',compact('taxes'));
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
        if (Auth::user()->can('create tax')) {

            return view('tax.create');
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
        if (Auth::user()->can('create tax')) {
      
            $validator = Validator::make(
                $request->all(), [
                    'name' => 'required|max:120',
                    'rate' => 'required|max:120|numeric',

                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $tax = new Tax();
            $tax->name = $request->name;
            $tax->rate = $request->rate;
            $tax->created_by = Auth::user()->creatorId();
            $tax->save();

            return redirect()->route('taxs.index')->with('success', __('Tax successfully created.'));

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
        if (Auth::user()->can('edit tax')) {
            $tax = Tax::find($id);
            return view('tax.edit',compact('tax'));
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
        if (Auth::user()->can('edit tax')) {
            $validator = Validator::make(
                $request->all(), [
                    'name' => 'required|max:120',
                    'rate' => 'required|max:120|numeric',

                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $tax = Tax::find($id);
            $tax->name = $request->name;
            $tax->rate = $request->rate;
            $tax->save();

            return redirect()->route('taxs.index')->with('success', __('Tax successfully updated.'));

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
        if (Auth::user()->can('delete tax')) {
            $tax = Tax::find($id);
            $tax->delete();
            return redirect()->route('taxs.index')->with('success', __('Tax successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    public function getTax(Request $request)
    {

        if ($request->selected && $request->selected > 0) {

            $tax = Tax::find($request->selected);

            return response()->json([
                'rate' => $tax->rate
            ]);



        }

    }
}
