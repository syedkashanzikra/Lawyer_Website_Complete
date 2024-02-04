<?php

namespace App\Http\Controllers;

use App\Models\Bench;
use App\Models\CauseList;
use App\Models\Court;
use App\Models\HighCourt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CauseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('manage cause')) {
            $causes = CauseList::where('created_by',Auth::user()->creatorId())->with('getCourt','highCourt','getBench')->get();

            return view('causelist.index', compact('causes'));

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
        if (Auth::user()->can('create cause')) {
            $courts = Court::where('created_by',Auth::user()->creatorId())->pluck('name','id')->prepend('Please Select', '');

            return view('causelist.create',compact('courts'));
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

        if (Auth::user()->can('create cause')) {
            $validator = Validator::make(
                $request->all(), [
                    'court' => 'required',
                    'advocate_name' => 'required',
                ]
            );
            if ($request->has('highcourt')) {
                $validator = Validator::make(
                    $request->all(), [
                        'highcourt' => 'required',
                    ]
                );

            }
            if ($request->has('bench')) {
                $validator = Validator::make(
                    $request->all(), [
                        'bench' => 'required',
                    ]
                );

            }
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $cause = new CauseList();
            $cause['court'] = $request->court;
            $cause['highcourt'] = $request->highcourt;
            $cause['bench'] = $request->bench;
            $cause['causelist_by'] = $request->causelist_by;
            $cause['advocate_name'] = $request->advocate_name;
            $cause['created_by'] = Auth::user()->creatorId();
            $cause->save();

            return redirect()->route('cause.index')->with('success', __('Cause successfully created.'));
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
       if (Auth::user()->can('manage cause')) {
            $cause = CauseList::find($id);

            return view('causelist.view', compact('cause'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->can('edit cause')) {
            $cause = CauseList::find($id);
            $courts = Court::where('created_by',Auth::user()->creatorId())->pluck('name', 'id')->prepend('Please Select', '');

            return view('causelist.edit', compact('courts','cause'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->can('delete cause')) {

            $cause = CauseList::find($id);
            if (!empty($cause)) {
                $cause->delete();
                return redirect()->route('cause.index')->with('success', __('Cause successfully deleted.'));
            }else{

                return redirect()->back()->with('error', __('Cause not found.'));
            }

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    public function getHighCourt(Request $request)
    {
        $highcourts = HighCourt::where('created_by',Auth::user()->creatorId())->where('court_id',$request->selected_opt)->pluck('name','id');
        $status = 0;
        $data = '';
        if (count($highcourts) > 0) {
            $status = 1;
            $data = $highcourts;
        }

        return response()->json([
            'status' => $status,
            'dropdwn' => $data,
        ]);
    }

    public function getBench(Request $request)
    {

        $bench = Bench::where('created_by',Auth::user()->creatorId())->where('highcourt_id',$request->selected_opt)->pluck('name','id');
        $status = 0;
        $data = '';
        if (count($bench) > 0) {
            $status = 1;
            $data = $bench;
        }

        return response()->json([
            'status' => $status,
            'dropdwn' => $data,
        ]);
    }


}
