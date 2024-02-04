<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Priority;
use App\Models\Policies;
use App\Models\Settings;
use Illuminate\Support\Facades\Validator;

class SLAPoliciyController extends Controller
{
    //
    public function index()
    {
        $priority = Priority::with(['policies'])->where('created_by', \Auth::user()->supportTicketCreatorId())->get();
        return view('policiy.index', compact('priority'));
    }



    public function store(Request $request)
    {

        $user = \Auth::user();

        if (\Auth::user()->supportTicketCreatorId() == 1) {
           
            $validator = Validator::make(
                $request->all(),
                [
                    'priority_id' => 'required|string|max:255',
                    'response_within' => 'required|string|max:255',
                    'response_time' => 'required|string|max:255',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            foreach ($request->priority as $key => $value) {

                $policies = [
                    'response_within' => (int)isset($value['response_within']) ? $value['response_within'] : null,
                    'response_time' => isset($value['response_time']) ? $value['response_time'] : null,
                    'resolve_within' => (int)isset($value['resolve_within']) ? $value['resolve_within'] : null,
                    'resolve_time' => isset($value['resolve_time']) ? $value['resolve_time'] : null,
                ];
                Policies::updateOrCreate(['priority_id' =>  $value['priority_id'], 'created_by' =>  \Auth::user()->supportTicketCreatorId()], $policies);
            }

            $resolve_status = $request->resolve_status ? 1 : 0;

            Settings::updateOrCreate(['name' => 'resolve_status', 'created_by' =>  \Auth::user()->supportTicketCreatorId()], ['value' => $resolve_status]);

            return redirect()->route('policiy.index')->with('success', __('policiy created successfully'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
