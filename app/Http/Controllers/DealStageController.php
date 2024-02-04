<?php

namespace App\Http\Controllers;

//use App\Models\Deal;
use App\Models\DealStage;
use App\Models\Pipeline;
//use App\Models\Stage;
use Illuminate\Http\Request;

class DealStageController extends Controller
{

    public function index()
    {
        if(\Auth::user()->super_admin_employee == 1)
        {
            $stages    = DealStage::select('deal_stages.*', 'pipelines.name as pipeline')->join('pipelines', 'pipelines.id', '=', 'deal_stages.pipeline_id')->where('pipelines.created_by', '=', \Auth::user()->creatorId())->where('deal_stages.created_by', '=', \Auth::user()->creatorId())->orderBy('deal_stages.pipeline_id')->orderBy('deal_stages.order')->get();
            $pipelines = [];

            foreach($stages as $stage)
            {
                if(!array_key_exists($stage->pipeline_id, $pipelines))
                {
                    $pipelines[$stage->pipeline_id]                = [];
                    $pipelines[$stage->pipeline_id]['name']        = $stage['pipeline'];
                    $pipelines[$stage->pipeline_id]['deal_stages'] = [];
                }
                $pipelines[$stage->pipeline_id]['deal_stages'][] = $stage;
            }

            return view('dealStage.index')->with('pipelines', $pipelines);
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        $pipelines = Pipeline::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

        return view('dealStage.create')->with('pipelines', $pipelines);
    }


    public function store(Request $request)
    {
        if(\Auth::user()->super_admin_employee == 1)
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20',
                                   'pipeline_id' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('dealStage.index')->with('error', $messages->first());
            }
            $stage              = new DealStage();
            $stage->name        = $request->name;
            $stage->pipeline_id = $request->pipeline_id;
            $stage->created_by  = \Auth::user()->creatorId();
            $stage->save();

            return redirect()->route('dealStage.index')->with('success', __('Stage successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(DealStage $dealStage)
    {
        //
    }


    public function edit(DealStage $dealStage)
    {
        $pipelines = Pipeline::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');

        return view('dealStage.edit', compact('dealStage', 'pipelines'));
    }


    public function update(Request $request, DealStage $dealStage)
    {
        if(\Auth::user()->super_admin_employee == 1)
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20',
                                   'pipeline_id' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('dealStage.index')->with('error', $messages->first());
            }

            $dealStage->name        = $request->name;
            $dealStage->pipeline_id = $request->pipeline_id;
            $dealStage->save();

            return redirect()->route('dealStage.index')->with('success', __('Deal stage successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(DealStage $dealStage)
    {
        if(\Auth::user()->super_admin_employee == 1)
        {
            // $deals = Deal::where('stage_id', '=', $dealStage->id)->where('created_by', '=', $dealStage->created_by)->count();

            // $data = Deal::where('stage_id', $dealStage->id)->first();
            // if(!empty($data))
            // {
            //     return redirect()->back()->with('error', __('this stage is already use so please transfer or delete this stage related data.'));
            // }

            // if($deals == 0)
            // {
                $dealStage->delete();

                 return redirect()->route('dealStage.index')->with('success', __('Stage successfully deleted.'));
            // }
            // else
            // {
            //     return redirect()->route('dealStage.index')->with('error', __('There are some Deals on Stage, please remove it first.'));
            // }
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
            $stage        = DealStage::where('id', '=', $item)->first();
            $stage->order = $key;
            $stage->save();
        }
    }

    public function json(Request $request)
    {
        $stage = new DealStage();
        if($request->pipeline_id)
        {
            $stage = $stage->where('pipeline_id', '=', $request->pipeline_id);
        }
        $stage = $stage->get()->pluck('name', 'id');

        return response()->json($stage);
    }
}
