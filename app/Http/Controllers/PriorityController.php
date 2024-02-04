<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Priority;
use App\Models\Policies;


class PriorityController extends Controller
{
    //
    public function index(Request $request)
    {

            $priority = Priority::where('created_by',\Auth::user()->supportTicketCreatorId())->get();

            return view('priority.index',compact('priority'));

    }

    public function create()
    {
        $user = \Auth::user();

        return view('priority.create');


    }

    public function store(Request $request)
    {
        $user = \Auth::user();

           $validation = [
            'name' => 'required|string|max:255',
            'content' => 'required|string|max:255',
          ];
          $priority = new Priority();
          $priority->name = $request->name;
          $priority->color = $request->color;
          $priority->created_by = \Auth::user()->supportTicketCreatorId();
          $priority->save();

          $policies = new Policies();
          $policies->priority_id = $priority->id;
          $policies->response_time = 'Hour';
          $policies->resolve_time = 'Hour';
          $policies->created_by = \Auth::user()->supportTicketCreatorId();
          $policies->save();

          return redirect()->route('priority.index')->with('success', __('Priority created successfully'));


    }

    public function edit($id)
    {
        $user = \Auth::user();

            $priority = Priority::find($id);

            return view('priority.edit', compact('priority'));


    }

    public function update(Request $request,$id)
    {

        $userObj = \Auth::user();

            $priority = Priority::find($id);
            $priority->name = $request->name;
            $priority->color = $request->color;
            $priority->save();
            return redirect()->route('priority.index')->with('success', __('Priority updated successfully'));


    }

    public function destroy($id)
    {

        $user = \Auth::user();

            $priority = Priority::find($id);
            $priority->delete();

            return redirect()->back()->with('success', __('Priority deleted successfully'));

    }

}
