<?php

namespace App\Http\Controllers;

use App\Models\Cases;
use App\Models\Hearing;
use App\Models\ToDo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function index(Request $request)
    {
        if (Auth::user()->can('manage diary')) {
            $todos_data = ToDo::where('created_by',Auth::user()->creatorId())->get();
            $todos = [];

            if (!empty($request->from) && !empty($request->to)) {

                $from = $request->from;
                $to = $request->to;

                $hearings = Hearing::where('created_by', Auth::user()->creatorId())->whereBetween('date',[$from, $to])->pluck('case_id')->toArray();

                $cases = Cases::where('created_by',Auth::user()->creatorId())->whereIn('id',$hearings)->get();

                foreach ($todos_data as $key => $value) {
                    $due = explode(' ', $value->start_date);

                    $from_format = date("d-m-y", strtotime($from));
                    $to_format = date("d-m-y", strtotime($to));

                    $due_str = strtotime($due[0]);
                    $from_str = strtotime($from_format);
                    $to_str = strtotime($to_format);

                    if ($due_str > $from_str && $due_str < $to_str ) {

                        $todos[$key]['id'] = $value['id'];
                        $todos[$key]['description'] = $value['description'];
                        $todos[$key]['start_date'] = $value['start_date'];
                        $todos[$key]['assign_to'] = $value['assign_to'];
                        $todos[$key]['assign_by'] = $value['assign_by'];
                        $todos[$key]['relate_to'] = $value['relate_to'];
                    }

                }

            }else{

                $hearings = Hearing::where('created_by', Auth::user()->creatorId())->where('date',date('Y-m-d'))->pluck('case_id')->toArray();
                $cases = Cases::where('created_by',Auth::user()->creatorId())->whereIn('id',$hearings)->get();


                foreach ($todos_data as $key => $value) {
                    $due = explode(' ', $value->start_date);

                    if($due[0] == date('Y-m-d')){

                        $todos[$key]['id'] = $value['id'];
                        $todos[$key]['description'] = $value['description'];
                        $todos[$key]['start_date'] = $value['start_date'];
                        $todos[$key]['assign_to'] = $value['assign_to'];
                        $todos[$key]['assign_by'] = $value['assign_by'];
                        $todos[$key]['relate_to'] = $value['relate_to'];
                    }

                }
            }

            return view('casediary.index', compact('cases','todos'));
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }
}
