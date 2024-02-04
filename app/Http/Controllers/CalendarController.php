<?php

namespace App\Http\Controllers;

use App\Models\Cases;
use App\Models\Hearing;
use App\Models\ToDo;
use App\Models\Utility;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('view calendar')) {

            $todo_data = ToDo::where('created_by', Auth::user()->creatorId())->get();
            $case_data = Cases::where('created_by', Auth::user()->creatorId())->get();
            $hearings = Hearing::where('created_by', Auth::user()->creatorId())->get();

            $todos = [];
            $cases = [];

            foreach ($todo_data as $key => $value) {
                $startDate = explode(' ',$value['start_date']);
                $startDate = date("Y-m-d", strtotime($startDate[0]));

                $endDate = explode(' ',$value['end_date']);
                $endDate = date("Y-m-d", strtotime($endDate[0]));

                $arr['id'] = $value['id'];
                $arr['title'] = $value['description'];
                $arr['start'] = $startDate;
                $arr['end'] = $endDate;
                $arr['description'] = $value['description'];
                $arr['url'] = route('to-do.show', $value['id']);
                $arr['className'] = 'event-info';
                $arr['data_name'] = 'todo';

                $todos[] = $arr;
            }

            foreach ($hearings as $key => $value) {
                $case = Cases::find($value->case_id);

                $caseArr['id'] = $case->id;
                $caseArr['title'] = $case->title;
                $caseArr['start'] = $value->date;
                $caseArr['end'] = $value->date;
                $caseArr['description'] = $case->description;
                $caseArr['url'] = route('cases.show', $case->id);
                $caseArr['className'] = 'event-warning';
                $caseArr['data_name'] = 'hearing';

                $cases[] = $caseArr;
            }

            $events = array_merge($todos,$cases);

            return view('calendar.index',compact('events','todo_data','case_data','hearings'));
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

    public function get_call_data(Request $request)
    {
        $arrayJson = [];
        $arrTodo = [];
        $arrHearing = [];

        if ($request->get('calender_type') == 'goggle_calender') {
            if ($type = 'task') {

                $arrTodo = Utility::getCalendarData($type);
            }

            if ($type = 'appointment') {

                $arrHearing = Utility::getCalendarData($type);
            }
            $arrayJson = array_merge($arrTodo, $arrHearing);


        } else {
            $todo_data = ToDo::where('created_by', Auth::user()->creatorId())->get();

            $hearings = Hearing::where('created_by', Auth::user()->creatorId())->get();
            $todos = [];
            $cases = [];

            foreach ($todo_data as $key => $value) {
                $startDate = explode(' ', $value['start_date']);
                $startDate = date("Y-m-d", strtotime($startDate[0]));

                $endDate = explode(' ', $value['end_date']);
                $endDate = date("Y-m-d", strtotime($endDate[0]));

                $arr['id'] = $value['id'];
                $arr['name'] = $value['title'];
                $arr['start_date'] = $startDate;
                $arr['end_date'] = $endDate;
                $arr['description'] = $value['description'];
                $arr['url'] = route('to-do.show', $value['id']);
                $arr['className'] = 'event-info';
                $arr['data_name'] = 'todo';

                $todos[] = $arr;
            }

            foreach ($hearings as $key => $value) {
                $case = Cases::find($value->case_id);

                $caseArr['id'] = $case->id;
                $caseArr['name'] = $case->title;
                $caseArr['start_date'] = $value->date;
                $caseArr['end_date'] = $value->date;
                $caseArr['description'] = $case->description;
                $caseArr['url'] = route('cases.show', $case->id);
                $caseArr['className'] = 'event-warning';
                $caseArr['data_name'] = 'hearing';


                $cases[] = $caseArr;
            }

            $events = array_merge($todos, $cases);
            foreach ($events as $val) {
                $end_date = date_create($val['end_date']);
                date_add($end_date, date_interval_create_from_date_string("1 days"));
                $arrayJson[] = [
                    "id" => $val['id'],
                    "title" => $val['name'],
                    "start" => $val['start_date'],
                    "end" => date_format($end_date, "Y-m-d H:i:s"),
                    "className" => $val['className'],
                    "url" => $val['data_name'] == 'todo' ? route('to-do.show', $val['id']) : route('cases.show', $val['id']),
                    "textColor" => '#000',
                    "allDay" => true,
                ];
            }
        }

        return $arrayJson;
    }
}
