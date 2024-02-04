<?php

namespace App\Http\Controllers;

//use App\Models\Deal;
use App\Models\Label;
//use App\Models\Lead;
use App\Models\Pipeline;
use Illuminate\Http\Request;

class LabelController extends Controller
{

    public function index()
    {
        if(\Auth::user()->super_admin_employee == 1)
        {
            $labels    = Label::select('labels.*', 'pipelines.name as pipeline')->join('pipelines', 'pipelines.id', '=', 'labels.pipeline_id')->where('pipelines.created_by', '=', \Auth::user()->creatorId())->where('labels.created_by', '=', \Auth::user()->creatorId())->orderBy('labels.pipeline_id')->get();
            $pipelines = [];

            foreach($labels as $label)
            {
                if(!array_key_exists($label->pipeline_id, $pipelines))
                {
                    $pipelines[$label->pipeline_id]           = [];
                    $pipelines[$label->pipeline_id]['name']   = $label['pipeline'];
                    $pipelines[$label->pipeline_id]['labels'] = [];
                }
                $pipelines[$label->pipeline_id]['labels'][] = $label;
            }

            return view('label.index')->with('pipelines', $pipelines);
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function create()
    {
        $pipelines = Pipeline::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $pipelines->prepend('Select Pipeline', '');
        $colors = Label::$colors;

        return view('label.create')->with('pipelines', $pipelines)->with('colors', $colors);
    }


    public function store(Request $request)
    {
        if(\Auth::user()->super_admin_employee == 1)
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20',
                                   'pipeline_id' => 'required',
                                   'color' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('label.index')->with('error', $messages->first());
            }

            $label              = new Label();
            $label->name        = $request->name;
            $label->color       = $request->color;
            $label->pipeline_id = $request->pipeline_id;
            $label->created_by  = \Auth::user()->creatorId();
            $label->save();

            return redirect()->route('label.index')->with('success', __('Label successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function show(Label $label)
    {
        //
    }


    public function edit(Label $label)
    {
        $pipelines = Pipeline::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $pipelines->prepend('Select Pipeline', '');
        $colors = Label::$colors;

        return view('label.edit', compact('label', 'pipelines', 'colors'));
    }


    public function update(Request $request, Label $label)
    {
        if(\Auth::user()->super_admin_employee == 1)
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20',
                                   'pipeline_id' => 'required',
                                   'color' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('label.index')->with('error', $messages->first());
            }

            $label->name        = $request->name;
            $label->color       = $request->color;
            $label->pipeline_id = $request->pipeline_id;
            $label->save();

            return redirect()->route('label.index')->with('success', __('Label successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function destroy(Label $label)
    {
        if(\Auth::user()->super_admin_employee == 1)
        {

            $label->delete();

            return redirect()->route('label.index')->with('success', __('Label successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
