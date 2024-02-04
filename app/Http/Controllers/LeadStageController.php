<?php

namespace App\Http\Controllers;

//use App\Models\Lead;
use App\Models\LeadStage;
use App\Models\Pipeline;
use Illuminate\Http\Request;

class LeadStageController extends Controller
{

    public function index()
    {
        if(\Auth::user()->super_admin_employee == 1)
        {
            $lead_stages = LeadStage::select('lead_stages.*', 'pipelines.name as pipeline')->join('pipelines', 'pipelines.id', '=', 'lead_stages.pipeline_id')->where(
                'pipelines.created_by', '=', \Auth::user()->creatorId()
            )->where('lead_stages.created_by', '=', \Auth::user()->creatorId())->orderBy('lead_stages.pipeline_id')->orderBy('lead_stages.order')->get();
            $pipelines   = [];

            foreach($lead_stages as $lead_stage)
            {
                if(!array_key_exists($lead_stage->pipeline_id, $pipelines))
                {
                    $pipelines[$lead_stage->pipeline_id]                = [];
                    $pipelines[$lead_stage->pipeline_id]['name']        = $lead_stage['pipeline'];
                    $pipelines[$lead_stage->pipeline_id]['lead_stages'] = [];
                }
                $pipelines[$lead_stage->pipeline_id]['lead_stages'][] = $lead_stage;
            }

            return view('leadStage.index', compact('pipelines'));
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

        return view('leadStage.create', compact('pipelines'));
    }


    public function store(Request $request)
    {
        // dd($request->all());
        if(\Auth::user()->super_admin_employee == 1)
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'pipeline_id' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('lead_stages.index')->with('error', $messages->first());
            }
            $leadStage              = new LeadStage();
            $leadStage->name        = $request->name;
            $leadStage->pipeline_id = $request->pipeline_id;
            $leadStage->created_by  = \Auth::user()->creatorId();
            $leadStage->save();

            return redirect()->route('leadStage.index')->with('success', __('Lead Stage successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(LeadStage $leadStage)
    {
        //
    }


    public function edit(LeadStage $leadStage)
    {
        $pipelines = Pipeline::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $pipelines->prepend('Select Pipeline', '');

        return view('leadStage.edit', compact('pipelines', 'leadStage'));
    }


    public function update(Request $request, LeadStage $leadStage)
    {
        if(\Auth::user()->super_admin_employee == 1)
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required',
                                   'pipeline_id' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('lead_stages.index')->with('error', $messages->first());
            }
            $leadStage->name        = $request->name;
            $leadStage->pipeline_id = $request->pipeline_id;
            $leadStage->created_by  = \Auth::user()->creatorId();
            $leadStage->save();

            return redirect()->route('leadStage.index')->with('success', __('Lead Stage successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(LeadStage $leadStage)
    {
        if(\Auth::user()->super_admin_employee == 1)
        {

            

            $leadStage->delete();

            return redirect()->route('leadStage.index')->with('success', __('Lead Stage successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function order(Request $request)
    {
        $post = $request->all();
        foreach($post['order'] as $key => $item)
        {
            // dd($item);
            $lead_stage        = LeadStage::where('id', '=', $item)->first();
            $lead_stage->order = $key;
            $lead_stage->save();
        }

    }
}
