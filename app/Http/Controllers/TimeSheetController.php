<?php

namespace App\Http\Controllers;

use App\Exports\TimesheetsExport;
use App\Models\Cases;
use App\Models\Timesheet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class TimeSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('manage timesheet')) {
            $timesheets = Timesheet::where('created_by',Auth::user()->creatorId())->get();
            return view('timesheet.index',compact('timesheets'));

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
        if (Auth::user()->can('manage timesheet')) {
            $cases = Cases::where('created_by',Auth::user()->creatorId())->get()->pluck('title', 'id');
            $members = User::where('created_by',Auth::user()->creatorId())->where('type','advocate')->get()->pluck('name', 'id');

            return view('timesheet.create',compact('cases','members'));

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
        if (Auth::user()->can('create timesheet')) {
            $validator = Validator::make(
                $request->all(), [
                    'case' => 'required',
                    'date' => 'required',
                    'particulars' => 'required',
                    'member' => 'required',
                    'time' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $timesheet = new Timesheet();
            $timesheet['case'] = $request->case;
            $timesheet['date'] = $request->date;
            $timesheet['particulars'] = $request->particulars;
            $timesheet['time'] = $request->time;
            $timesheet['member'] = $request->member;
            $timesheet['created_by'] = Auth::user()->creatorId();
            $timesheet->save();
            return redirect()->route('timesheet.index')->with('success', __('Timesheet successfully created.'));

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
        if (Auth::user()->can('view timesheet')) {
            $cases = Cases::get()->pluck('title', 'id');
            $members = User::get()->pluck('name', 'id');
            $timesheet = Timesheet::find($id);
            return view('timesheet.view', compact('cases', 'members', 'timesheet'));

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
        if (Auth::user()->can('edit timesheet')) {
            $cases = Cases::where('created_by',Auth::user()->creatorId())->get()->pluck('title', 'id');
            $members = User::where('created_by',Auth::user()->creatorId())->where('type','advocate')->get()->pluck('name', 'id');
            $timesheet = Timesheet::find($id);
            return view('timesheet.edit', compact('cases', 'members','timesheet'));

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
        if (Auth::user()->can('edit timesheet')) {
            $validator = Validator::make(
                $request->all(), [
                    'case' => 'required',
                    'date' => 'required',
                    'particulars' => 'required',
                    'member' => 'required',
                    'time' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $timesheet = Timesheet::find($id);
            $timesheet['case'] = $request->case;
            $timesheet['date'] = $request->date;
            $timesheet['particulars'] = $request->particulars;
            $timesheet['time'] = $request->time;
            $timesheet['member'] = $request->member;
            $timesheet['created_by'] = Auth::user()->id;
            $timesheet->save();
            
            return redirect()->route('timesheet.index')->with('success', __('Timesheet successfully updated.'));

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
        if (Auth::user()->can('edit timesheet')) {
            $timesheet = Timesheet::find($id);
            if ($timesheet) {
                $timesheet->delete();
            }
            return redirect()->route('timesheet.index')->with('success', __('Timesheet successfully deleted.'));

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));

        }

    }

    public function exportFile()
    {
        $name = 'timesheets_' . date('Y-m-d i:h:s');
        $data = Excel::download(new TimesheetsExport(), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }
}
