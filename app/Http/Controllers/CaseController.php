<?php

namespace App\Http\Controllers;

use App\Exports\CasesExport;
use App\Imports\ImportCase;
use App\Models\Advocate;
use App\Models\Cases;
use App\Models\Court;
use App\Models\Document;
use App\Models\Hearing;
use App\Models\HearingType;
use App\Models\Motion;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class CaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('manage case')) {
            if (Auth::user()->type == 'client') {
                $user = Auth::user()->id;
                $case = DB::table("cases")
                    ->select("cases.*")
                    ->get();
                $cases = [];
                foreach ($case as $value) {
                    $data = json_decode($value->your_party_name);
                    foreach ($data as $key => $val) {
                        if (isset($val->clients) && $val->clients == $user) {
                            $cases[$value->id] = $value;
                        }
                    }
                }
            } else {

                // $cases = Cases::where('created_by',Auth::user()->creatorId())->get();
                $cases = Cases::with('getCourt')
                    ->where('created_by', Auth::user()->creatorId())
                    ->get();
            }


            return view('cases.index', compact('cases'));
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
        if (Auth::user()->can('create case')) {
            $courts = Court::where('created_by', Auth::user()->creatorId())->pluck('name', 'id');
            $advocates = User::where('created_by', Auth::user()->creatorId())->where('type', 'advocate')->pluck('name', 'id');
            $clients = User::where('created_by', Auth::user()->creatorId())->where('type', 'client')->pluck('name', 'id')->prepend('Please Select', '');
            $team = User::where('created_by', Auth::user()->creatorId())->where('type', '!=', 'advocate')->pluck('name', 'id');

            $HearingType = HearingType::where('created_by', Auth::user()->creatorId())->pluck('type', 'id');
            $motions = Motion::where('created_by', Auth::user()->creatorId())->pluck('type', 'id');
            return view('cases.create', compact('courts', 'advocates', 'team', 'HearingType', 'motions', 'clients'));
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
        if (Auth::user()->can('create case')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'court' => 'required',
                    'year' => 'required',
                    'case_number' => 'required',
                    'title' => 'required',
                    'filing_date' => 'required',
                    'your_party' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $your_party_name_temp = array();
            foreach ($request->your_party_name as $items) {

                foreach ($items as $k => $item) {

                    if (($k == 'name')  && (!empty($item) && $item != null)) {
                        $your_party_name_temp[] = $items;
                    }

                    if (empty($item) && $item < 0) {

                        $msg['flag'] = 'error';
                        $msg['msg'] = __('Please enter your party name');

                        return redirect()->back()->with('error', $msg['msg']);
                    }
                }
            }


            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $opp_party_name_temp = array();

            foreach ($request->opp_party_name as $items) {
                foreach ($items as $ke => $item) {
                    if ($ke == 'name' && !empty($item) && $item != null) {
                        $opp_party_name_temp[] = $items;
                    }
                    if (empty($item) && $item < 0) {
                        $msg['flag'] = 'error';
                        $msg['msg'] = __('Please enter your opponent party name');

                        return redirect()->back()->with('error', $msg['msg']);
                    }
                }

                // $validator = Validator::make(
                //     $items, [
                //         'name' => 'required',

                //     ]
                // );

            }

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $case = new Cases();
            $case['court'] = $request->court;
            $case['highcourt'] = $request->highcourt;
            $case['bench'] = $request->bench;
            $case['casetype'] = $request->casetype;
            $case['casenumber'] = $request->casenumber;
            $case['diarybumber'] = !empty($request->diarybumber) ? $request->diarybumber : null;
            $case['year'] = $request->year;
            $case['case_number'] = $request->case_number;
            $case['filing_date'] = $request->filing_date;
            $case['court_hall'] = $request->court_hall;
            $case['floor'] = $request->floor;
            $case['title'] = $request->title;
            $case['description'] = $request->description;
            $case['under_acts'] = $request->under_acts;
            $case['under_sections'] = $request->under_sections;
            $case['FIR_number'] = $request->FIR_number;
            $case['FIR_year'] = $request->FIR_year;
            $case['opponents'] = json_encode($request->opponents);
            $case['filing_party'] = $request->filing_party;
            $case['case_status'] = $request->case_status;
            $case['motion'] = $request->motion;
            $case['opponent_advocates'] = json_encode($request->opponent_advocates);
            $case['advocates'] = $request->advocates != null ? implode(',', $request->advocates) : '';
            $case['court_room'] = $request->court_room;
            $case['opp_adv'] = $request->opp_adv;
            $case['stage'] = $request->stage;
            $case['created_by'] = Auth::user()->creatorId();
            $case['judge'] =  isset($request->judge) ? $request->judge : '';
            $case['police_station'] = $request->police_station;
            $case['your_party_name'] = json_encode($your_party_name_temp);
            $case['opp_party_name'] = json_encode($opp_party_name_temp);


            $file_name = [];
            if (!empty($request->case_docs) && count($request->case_docs) > 0) {
                foreach ($request->case_docs as $key => $file) {
                    $image_size = $file->getSize();
                    $result = Utility::updateStorageLimit(Auth::user()->creatorId(), $image_size);
                    if ($result == 1) {
                        $filenameWithExt = $file->getClientOriginalName();
                        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                        $extension = $file->getClientOriginalExtension();
                        $fileNameToStore = $filename . '_' . time() . rand(1, 100) . '.' . $extension;

                        $dir = 'uploads/case_docs/';
                        $path = Utility::keyWiseUpload_file($request, 'case_docs', $fileNameToStore, $dir, $key, []);
                        if ($path['flag'] == 1) {
                            $url = $path['url'];
                            $file_name[] = $fileNameToStore;
                        }
                    }
                }
            }

            $case['case_docs'] = !empty($file_name) ? implode(',', $file_name) : '';

            $case->save();


            return redirect()->route('cases.index')->with('success', __('Case successfully created.'));
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
        if (Auth::user()->can('view case')) {

            $case = Cases::find($id);
            $docs = Document::where('created_by', Auth::user()->creatorId())->where('cases', $case->id)->get();

            $documents = [];
            if (!empty($case->case_docs)) {
                $documents = explode(',', $case->case_docs);
            }
            $hearings = Hearing::where('case_id', $id)->get();
            return view('cases.view', compact('case', 'documents', 'hearings', 'docs'));
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
        if (Auth::user()->can('edit case')) {
            $courts = Court::where('created_by', Auth::user()->creatorId())->pluck('name', 'id')->prepend('Please Select', '');
            $advocates = User::where('created_by', Auth::user()->creatorId())->where('type', 'advocate')->pluck('name', 'id');
            $team = User::where('created_by', Auth::user()->creatorId())->where('type', '!=', 'advocate')->pluck('name', 'id');
            $case = Cases::find($id);
            $your_advocates = [];
            if (!empty($case->advocates)) {
                $your_advocates = User::whereIn('id', explode(',', $case->advocates))
                    ->where(function ($query) {
                        $query->where('created_by', Auth::user()->creatorId())->orWhere('id', Auth::user()->creatorId());
                    })->get();
            }
            $your_team = User::where('created_by', Auth::user()->creatorId())->whereIn('id', explode(',', $case->your_team))->get();
            $priorities = ['Super Critical' => 'Super Critical', 'Critical' => 'Critical', 'Important' => 'Important', 'Routine' => 'Routine', 'Normal' => 'Normal'];
            $motions = Motion::where('created_by', Auth::user()->creatorId())->pluck('type', 'id');
            $clients = User::where('created_by', Auth::user()->creatorId())->where('type', 'client')->pluck('name', 'id');
            $case_typ = Cases::caseType();
            $documents = [];
            if (!empty($case->case_docs)) {
                $documents = explode(',', $case->case_docs);
            }
            return view('cases.edit', compact('courts', 'advocates', 'team', 'case', 'your_advocates', 'your_team', 'priorities', 'case_typ', 'motions', 'clients', 'documents'));
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
        if (Auth::user()->can('edit case')) {

            $validator = Validator::make(
                $request->all(),
                [
                    'court' => 'required',
                    'year' => 'required',
                    'case_number' => 'required',
                    'title' => 'required',
                    'filing_date' => 'required',
                    'your_party' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $your_party_name_temp = array();
            if ($request->your_party_name != null && !empty($request->your_party_name) && $request->your_party_name != "") {
                foreach ($request->your_party_name as $items) {

                    foreach ($items as $k => $item) {

                        if (($k == 'name')  && (!empty($item) && $item != null)) {
                            $your_party_name_temp[] = $items;
                        }

                        if (empty($item) && $item < 0) {

                            $msg['flag'] = 'error';
                            $msg['msg'] = __('Please enter your party name');

                            return redirect()->back()->with('error', $msg['msg']);
                        }
                    }
                }
            }
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $opp_party_name_temp = array();

            if ($request->opp_party_name != null && !empty($request->opp_party_name) && $request->opp_party_name != "") {
                foreach ($request->opp_party_name as $items) {
                    foreach ($items as $ke => $item) {
                        if ($ke == 'name' && !empty($item) && $item != null) {
                            $opp_party_name_temp[] = $items;
                        }
                        if (empty($item) && $item < 0) {
                            $msg['flag'] = 'error';
                            $msg['msg'] = __('Please enter your opponent party name');

                            return redirect()->back()->with('error', $msg['msg']);
                        }
                    }
                }
            }

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }


            $case = Cases::find($id);
            $case['court'] = $request->court;
            $case['highcourt'] = $request->highcourt;
            $case['bench'] = $request->bench;
            $case['casetype'] = $request->casetype;
            $case['casenumber'] = $request->casenumber;
            $case['diarybumber'] = !empty($request->diarybumber) ? $request->diarybumber : null;
            $case['year'] = $request->year;
            $case['case_number'] = $request->case_number;
            $case['filing_date'] = $request->filing_date;
            $case['court_hall'] = $request->court_hall;
            $case['floor'] = $request->floor;
            $case['title'] = $request->title;
            $case['description'] = $request->description;
            $case['under_acts'] = $request->under_acts;
            $case['under_sections'] = $request->under_sections;
            $case['FIR_number'] = $request->FIR_number;
            $case['FIR_year'] = $request->FIR_year;
            $case['opponents'] = json_encode($request->opponents);
            $case['opponent_advocates'] = json_encode($request->opponent_advocates);
            $case['filing_party'] = $request->filing_party;
            $case['case_status'] = $request->case_status;
            $case['motion'] = $request->motion;
            $case['advocates'] = $request->advocates != null ? implode(',', $request->advocates) : '';
            $case['court_room'] = $request->court_room;
            $case['judge'] =  isset($request->judge) ? $request->judge : '';
            $case['police_station'] = $request->police_station;
            $case['your_party_name'] = json_encode($your_party_name_temp);
            $case['opp_party_name'] = json_encode($opp_party_name_temp);
            $case['opp_adv'] = $request->opp_adv;
            $case['stage'] = $request->stage;
            $file_name = [];

            if (!empty($request->case_docs) && count($request->case_docs) > 0) {

                foreach ($request->case_docs as $key => $file) {

                    $image_size = $file->getSize();
                    $result = Utility::updateStorageLimit(Auth::user()->creatorId(), $image_size);

                    if ($result == 1) {
                        $filenameWithExt = $file->getClientOriginalName();
                        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                        $extension = $file->getClientOriginalExtension();
                        $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                        $dir = 'uploads/case_docs/';
                        $path = Utility::keyWiseUpload_file($request, 'case_docs', $fileNameToStore, $dir, $key, []);

                        if ($path['flag'] == 1) {
                            $url = $path['url'];
                            $file_name[] = $fileNameToStore;
                        }
                    }
                }
            }

            $case['case_docs'] = !empty($file_name) ? implode(',', $file_name) : '';

            $case->save();

            return redirect()->route('cases.index')->with('success', __('Case successfully updated.'));
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
        if (Auth::user()->can('delete case')) {

            $case = Cases::find($id);

            if ($case) {
                if (!empty($case->case_docs)) {
                    $documents = explode(',', $case->case_docs);
                    foreach ($documents as $pro) {

                        if (isset($pro)) {

                            $filePath = 'uploads/case_docs/' . $pro;

                            Utility::changeStorageLimit(Auth::user()->creatorId(), $filePath);

                            if (File::exists($filePath)) {
                                File::delete($filePath);
                            }
                        }
                    }
                }


                $case->delete();
            }

            return redirect()->route('cases.index')->with('success', __('Case successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function journey($id)
    {
        $case = Cases::find($id);

        if ($case) {
            return view('cases.journey', compact('case'));
        }
    }
    public function updateJourney(Request $request, $id)
    {
        $case = Cases::find($id);

        if ($case) {
            if (!$request->journeys) {
                $journeys = null;
            } else {
                $journeys = implode(',', $request->journeys);
            }
            $case->journey = $journeys;
            $case->update();

            return response()->json([
                'status' => 'success',
                'msg' => 'Case journey updated.',
            ]);
        }
    }

    public function importFile()
    {
        return view('cases.import');
    }

    public function import(Request $request)
    {

        $rules = [
            'file' => 'required|mimes:csv,txt',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $cases = (new ImportCase())->toArray(request()->file('file'))[0];

        $totalcase = count($cases) - 1;
        $errorArray    = [];
        $n=0;

        try {
            for($i = 1; $i <= count($cases) - 1; $i++)
            {
                $case = $cases[$i];


                $your_party_name_temp=array();
                $temp_your_party_name=explode('-',$case[16]);
                $temp_your_party_client_name=explode('-',$case[17]);
                foreach ($temp_your_party_name as $ke => $items) {
                    if(!empty($items))
                    {
                        $client=isset($temp_your_party_client_name[$ke])?$temp_your_party_client_name[$ke]:'';
                        $clients = User::where('name',$client)->where('created_by',Auth::user()->creatorId())->where('type','client')->first();
                        if($clients)
                        {
                            $client=$clients->id;
                        }
                        else{
                            $client='';
                        }
                        $name=isset($items)?$items:'';
                        $your_party_name_temp[]=array('name'=>$name,'clients'=>$client);
                    }


                }

                $temp_opp_party_name = explode('-',$case[18]);
                $opp_party_name = [];
                foreach ($temp_opp_party_name as $key => $value) {
                    $opp_party_name[]=array("name"=> $value);
                }
                $temp_adv = explode('-',$case[19]);
                $adv_ids=[];
                foreach ($temp_adv as $key => $value) {
                    $advocates = User::where('name',$value)->where('created_by',Auth::user()->creatorId())->where('type','!=','super admin')->where('type','!=','company')->where('type','!=','client')->first();
                    if($advocates)
                    {
                        $adv_ids[]=$advocates->id;
                    }

                }

                $court = Court::where("name",$case[1])->first();
                if($court)
                {
                    $case[1] = $court->id ;
                }
                else
                {
                    $case[1]=1;
                }
                if($case[15] == 'Respondent/Defendant')
                {
                    $party=1;
                }
                else
                {
                    $party=0;
                }
                $caserData = new Cases();
                $advocates = implode(",",$adv_ids);

                if( !empty($case[3]) && !empty($case[2])&&!empty($case[4])&&!empty($case[5]) &&  !empty($party))
                {
                    $caserData->court               = $case[1];
                    $caserData->case_number         = $case[2];
                    $caserData->year                = $case[3];
                    $caserData->title               = $case[4];
                    $caserData->filing_date         = $case[5];
                    $caserData->Judge               = isset($case[6])?$case[6]:'';
                    $caserData->court_room          = $case[7];
                    $caserData->description         = $case[8];
                    $caserData->under_acts          = $case[9];
                    $caserData->under_sections      = $case[10];
                    $caserData->police_station      = $case[11];
                    $caserData->FIR_number          = $case[12];
                    $caserData->FIR_year            = $case[13];
                    $caserData->stage               = $case[14];
                    $caserData->your_party          = $party;
                    $caserData->your_party_name     = json_encode($your_party_name_temp);
                    $caserData->opp_party_name      = json_encode($opp_party_name);
                    $caserData->advocates           = $advocates;
                    $caserData->opp_adv             = $case[20];
                    $caserData->created_by          = \Auth::user()->creatorId();
                    $caserData->save();
                }

                if(isset($caserData->id) && !empty($caserData->id))
                {
                    $caserData->save();
                }
                else
                {
                    $n++;
                    $errorArray[]=$n;
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', 'Please follow sample file structure');

        }


        $errorRecord = [];

        if(empty($errorArray))
        {
            $data['status'] = 'success';
            $data['msg']    = __('Record successfully imported');
        }
        else
        {
            $data['status'] = 'error';
            $data['msg']    = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalcase . ' ' . 'record');



        }

        return redirect()->back()->with($data['status'], $data['msg']);
    }

    public function exportFile()
    {
        $name = 'cases_' . date('Y-m-d i:h:s');
        $data = Excel::download(new CasesExport(), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }
}
