<?php

namespace App\Http\Controllers;

//use App\Models\Lead;
use App\Models\Source;
use Illuminate\Http\Request;

class SourceController extends Controller
{

    public function index()
    {
         if(\Auth::user()->super_admin_employee == 1)
        {
            $sources = Source::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('source.index', compact('sources'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        return view('source.create');
    }


    public function store(Request $request)
    {
         if(\Auth::user()->super_admin_employee == 1)
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('source.index')->with('error', $messages->first());
            }

            $source             = new Source();
            $source->name       = $request->name;
            $source->created_by = \Auth::user()->creatorId();
            $source->save();

            return redirect()->route('source.index')->with('success', __('Source successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show(Source $source)
    {
        //
    }


    public function edit(Source $source)
    {
        return view('source.edit', compact('source'));
    }


    public function update(Request $request, Source $source)
    {
         if(\Auth::user()->super_admin_employee == 1)
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('source.index')->with('error', $messages->first());
            }

            $source->name = $request->name;
            $source->save();

            return redirect()->route('source.index')->with('success', __('Source successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function destroy(Source $source)
    {
         if(\Auth::user()->super_admin_employee == 1)
        {
            
            $source->delete();

            return redirect()->route('source.index')->with('success', __('Source successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
