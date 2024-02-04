<?php

namespace App\Http\Controllers;

use App\Models\Cases;
use App\Models\Hearing;
use App\Models\HearingType;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Imports\ImportHearing;
class HearingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($case_id)
    {

        $hearing_type = HearingType::where('created_by',Auth::user()->creatorId())->pluck('type','id');
        return view('hearings.create',compact('hearing_type','case_id'));
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
                'date' => 'required',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $hearing = new Hearing();
        $hearing['case_id'] = $request->case_id;
        $hearing['date'] = $request->date;
        $fileNameToStores = '';
        if(!$request->file == null){

            $image_size = $request->file('file')->getSize();
            $result = Utility::updateStorageLimit(Auth::user()->id, $image_size);

            if($result==1) {
                $filenameWithExt = $request->file('file')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('file')->getClientOriginalExtension();
                $fileNameToStores = 'document_' . time() . '.' . $extension;

                $settings = Utility::getStorageSetting();
                if ($settings['storage_setting'] == 'local') {
                    $dir = 'uploads/documents/';
                } else {
                    $dir = 'uploads/documents/';
                }
                $path = Utility::upload_file($request, 'file', $fileNameToStores, $dir, []);

                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }

            }
        }

        $hearing['remarks'] = $request->remarks;
        $hearing['order_seet'] = $fileNameToStores;
        $hearing['created_by'] = Auth::user()->creatorId();
        $hearing->save();

        $case = Cases::find($hearing->case_id);

        if ($request->get('is_check') == '1') {
            $type = 'appointment';
            $request1 = new Cases();
            $request1->title = $case->title;
            $request1->start_date = $request->date;
            $request1->end_date = $request->date;
            Utility::addCalendarData($request1, $type);
        }

        return redirect()->back()->with('success', __('Hearing successfully created.'));
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
        $hearing = Hearing::find($id);
        $hearing_types = HearingType::where('created_by',Auth::user()->creatorId())->pluck('type','id');
        $hearing_type = HearingType::find($hearing->type);

        return view('hearings.edit',compact('hearing','hearing_types','hearing_type'));
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
                'date' => 'required',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $hearing = Hearing::find($id);
        $hearing['date'] = $request->date;
        $fileNameToStores = '';
        if(!$request->file == null){

            $image_size = $request->file('file')->getSize();
            $result = Utility::updateStorageLimit(Auth::user()->id, $image_size);

            if($result == 1) {
                $filenameWithExt = $request->file('file')->getClientOriginalName();
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('file')->getClientOriginalExtension();
                $fileNameToStores = 'document_' . time() . '.' . $extension;

                $settings = Utility::getStorageSetting();
                if ($settings['storage_setting'] == 'local') {
                    $dir = 'uploads/documents/';
                } else {
                    $dir = 'uploads/documents/';
                }
                $path = Utility::upload_file($request, 'file', $fileNameToStores, $dir, []);

                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }

            }
        }

        $hearing['remarks'] = $request->remarks;
        $hearing['order_seet'] = $fileNameToStores;
        $hearing->update();

        return redirect()->back()->with('success', __('Hearing successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $HearingType = Hearing::find($id);

        if ($HearingType) {
            $HearingType->delete();
        }

        return redirect()->back()->with('success', __('Hearing successfully deleted.'));
    }

    public function importFile($case_id)
    {
        return view('hearings.import',compact('case_id'));
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

        $datas = (new ImportHearing())->toArray(request()->file('file'))[0];
        $totaldata = count($datas) - 1;
        $errorArray    = [];
        $n=0;
        for($i = 1; $i <= count($datas) - 1; $i++)
        {
            $data = $datas[$i];

            if(empty($data[1]) && $data[1]=='' && $data[1]==null)
            {
                $data[1]=date('Y-m-d');
            }
            $hearing = new Hearing();
            $hearing['case_id'] = $request->case_id;
            $hearing['date'] =date('Y-m-d', strtotime($data[1]));
            $hearing['remarks'] = $data[2];
            $hearing['created_by'] = Auth::user()->creatorId();


            if(empty($hearing))
            {
                $errorArray[] = $hearing;
            }
            else
            {
                $hearing->save();
            }

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
            $data['msg']    = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totaldata . ' ' . 'record');



        }

        return redirect()->back()->with($data['status'], $data['msg']);
    }
}
