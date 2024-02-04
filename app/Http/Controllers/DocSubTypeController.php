<?php

namespace App\Http\Controllers;

use App\Models\DocSubType;
use App\Models\DocType;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator as FacadesValidator;

class DocSubTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::user()->can('manage doctype')) {
            $data = DocSubType::where('created_by', Auth::user()->creatorId());
            if(isset($request->doctyp_id)){
                $data->where('doctype_id',$request->doctyp_id);

            }
            $doctype = DocType::find($request->doctyp_id);

            $types = $data->get();

            return view('docsubtype.index', compact('types','doctype'));
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
        if (Auth::user()->can('create doctype')) {
            $doctypes = DocType::where('created_by', Auth::user()->creatorId())->pluck('name','id');
            return view('docsubtype.create' ,compact('doctypes'));
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
        if (Auth::user()->can('create doctype')) {

            $validator = FacadesValidator::make(
                $request->all(),
                [
                    'name' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $type = new DocSubType();
            $type['type'] = $request->name;
            $type['doctype_id'] = $request->doctype_id;
            $type['description'] = $request->description;
            $type['created_by'] = Auth::user()->creatorId();
            $type->save();

            return redirect()->route('doctsubype.index')->with('success', __('Document sub type successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DocSubType  $docSubType
     * @return \Illuminate\Http\Response
     */
    public function show(DocSubType $docSubType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DocSubType  $docSubType
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        if (Auth::user()->can('edit doctype')) {
            $type = DocSubType::find($id);
            $doctypes = DocType::where('created_by', Auth::user()->creatorId())->pluck('name','id');

            return view('docsubtype.edit', compact('type','doctypes'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DocSubType  $docSubType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit doctype')) {

            $type = DocSubType::find($id);
            $validator = FacadesValidator::make(
                $request->all(),
                [
                    'type' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $type['type'] = $request->type;
            $type['doctype_id'] = $request->doctype_id;
            $type['description'] = $request->description;
            $type->save();

            return redirect()->route('doctsubype.index')->with('success', __('Document sub type successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DocSubType  $docSubType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->can('delete doctype')) {
            $type = DocSubType::find($id);

            if ($type) {
                $type->delete();
            }
            return redirect()->route('doctsubype.index')->with('success', __('Document sub type successfully deleted.'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function getDocSubType(Request $request){
        $data = DocSubType::where('created_by', Auth::user()->creatorId())->where('doctype_id',$request->selected_opt)->pluck('type','id');
        if($data){
            return response()->json([
                'status' => 1,
                'getdata' => $data
            ]);
        }
    }
}
