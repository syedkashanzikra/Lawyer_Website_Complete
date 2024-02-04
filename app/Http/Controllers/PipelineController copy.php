<?php

namespace App\Http\Controllers;

// use App\Models\Deal;
// use App\Models\Lead;
use App\Models\Pipeline;
use Illuminate\Http\Request;

class PipelineController extends Controller
{

    public function index()
    {
        if(\Auth::user()->super_admin_employee == 1)
        {
            $pipelines = Pipeline::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('pipeline.index', compact('pipelines'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        return view('pipeline.create');
    }


    public function store(Request $request)
    {
        $premission=[];
        if(\Auth::user()->super_admin_employee==1)
        {
            $premission=json_decode(\Auth::user()->permission_json);
            $premission_arr = get_object_vars($premission);
        }
        if(\Auth::user()->super_admin_employee == 1 &&  array_search("manage crm",$premission_arr))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('pipeline.index')->with('error', $messages->first());
            }

            $pipeline             = new Pipeline();
            $pipeline->name       = $request->name;
            $pipeline->created_by = \Auth::user()->creatorId();
            $pipeline->save();

            return redirect()->route('pipeline.index')->with('success', __('Pipeline successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function show(Pipeline $pipeline)
    {
        //
    }


    public function edit(Pipeline $pipeline)
    {
        return view('pipeline.edit', compact('pipeline'));
    }


    public function update(Request $request, Pipeline $pipeline)
    {
        $premission=[];
        if(\Auth::user()->super_admin_employee==1)
        {
            $premission=json_decode(\Auth::user()->permission_json);
            $premission_arr = get_object_vars($premission);
        }
        if(\Auth::user()->super_admin_employee == 1 &&  array_search("manage crm",$premission_arr))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('pipeline.index')->with('error', $messages->first());
            }

            $pipeline->name       = $request->name;
            $pipeline->created_by = \Auth::user()->creatorId();
            $pipeline->save();

            return redirect()->route('pipeline.index')->with('success', __('Pipeline successfully update.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function destroy(Pipeline $pipeline)
    {
        if(\Auth::user()->super_admin_employee==1)
        {


            $pipeline->delete();

            return redirect()->route('pipeline.index')->with('success', __('Pipeline successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
